<?php

//CVS fabric price class
class csv_fabric_price_combos_import extends ATQ {
	//Controller
	public function __construct() {
		parent:: __construct();
	}

	// iniating main function
	public function init() {
		?>
		<h1><?php echo get_admin_page_title(); ?></h1>
		<?php
		$import = filter_input( INPUT_GET, 'import' );

		if ( isset( $import ) && $import == 'invalid' ) {
			echo '<div id="message" class="notice notice-error is-dismissible"><p>Invalid file extension. Please use *.csv file.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

		}
		if ( isset( $import ) && $import == 'success' ) {
			echo '<div id="message" class="updated notice notice-success is-dismissible"><p>file imported successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

		}

		?>
		<form method="post"
		      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=import_combos' ); ?>"
		      enctype="multipart/form-data">
			<p>
				<input type="file" name="import_combos">
			</p>
			<p>
				<input type="submit" value="Import Fabrics" class="button button-primary">
			</p>
		</form>
		<?php
	}

	public function import_combos() {
		//Get the file
		$file = $_FILES['import_combos'];

		//Get file extension
		$file_ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

		if ( $file_ext == 'csv' ) {
			//File  temporary name

			$file_data = file_get_contents( $file['tmp_name'] );

			$fil_content_arr = explode( PHP_EOL, $file_data );

			foreach ( $fil_content_arr as $file_content ) {

				// Assign variables to each column value
				list ( $id, $code ) = explode( ';', $file_content );

				// Trim that F***ING break >:(
				$rt_code = rtrim($code);

				// Get product info
				$prod = $this->wpdb->get_row("SELECT * FROM $this->products_tbl WHERE prod_id = $id");

				// Find fabric ID with fabric suffix
				$fab = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_suffix = '$rt_code'");

				// Check if product have code
				if ($rt_code) {
					$code_full = $prod->prod_code . '-' . $rt_code;
					$fid = $fab->fab_id;
				} else {
					$code_full = $prod->prod_code;
					$fid = null;
				}

				// Prep data to insert
				$data = array(
					'combo_pid'  => $id,
					'combo_fid'  => $fid,
					'combo_code' => $code_full,
				);

				/*echo '<pre>';
				print_r($data);
				echo '<pre>';*/

				$this->wpdb->insert( $this->products_fp_combos_tbl, $data );
			}

			wp_redirect( admin_url( 'admin.php?page =' . $this->page . '&import=success' ) );

		} else {
			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&import=invalid' ) );
		}

	}

}

?>