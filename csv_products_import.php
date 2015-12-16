<?php

//CVS Product class
class csv_products_import extends ATQ {

	//Controller
	public function __construct() {
		parent:: __construct();
	}

	public function init() {
		?>
		<h1><?php echo get_admin_page_title(); ?></h1>

		<?php
		$product = filter_input( INPUT_GET, 'product' );

		if ( isset( $product ) && $product == 'invalid' ) {
			echo '<div id="message" class="notice notice-error is-dismissible"><p>Invalid file extension. Please use *.csv file.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}

		if ( isset( $product ) && $product == 'updated' ) {
			echo '<div id="message" class="updated notice notice-success is-dismissible"><p>Product updated successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}
		?>

		<form method="post"
		      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=update_products' ); ?>"
		      enctype="multipart/form-data">
			<p>
				<input type="file" name="update_products">
			</p>
			<p>
				<input type="submit" value="Import Products" class="button button-primary">
			</p>
		</form>

		<p><em><strong>NOTE:</strong> Please upload the file with *.csv extension and data should be separated by comma
				(,)</em></p>
		<?php
	}

	/**
	 *
	 */
	public function update_products() {

		// Get the file
		$file = $_FILES['update_products'];

		// Get file extension
		$file_ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

		if ( $file_ext == 'csv' ) {

			// File temporary name
			$file_data = file_get_contents( $file['tmp_name'] );

			// Breaking the data in rows
			$data_arr = explode( PHP_EOL, $file_data );

			foreach ( $data_arr as $data ) {

				// Assign variables to each column value
				list( $name, $desc, $images, $code, $cats, $size ) = explode( ';', $data );

				// Serialize images
				$images_arr = explode(',', $images);
				$images_srl = serialize($images_arr);

				// Add category relationships
				$cats_arr = explode(',', $cats);


				$products_data = array(
					'prod_name' => $name,
					'prod_desc' => $desc,
					'prod_images' => $images_srl,
					'prod_code' => $code,
					'prod_size' => $size,
				);

				// Update the price
				$this->wpdb->insert($this->products_tbl, $products_data);

				// Last product id
				$last_id = $this->wpdb->insert_id;

				foreach ($cats_arr as $cat) {

					// Cat relationship data
					$cat_data = array(
						'prod_id' => $last_id,
						'cat_id' => $cat
					);

					// Insert data into wp_atq_categories_relation
					$this->wpdb->insert($this->categories_relation_tbl, $cat_data);

				}

			}
			// If all goes well :)
			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&product=updated' ) );

			exit;

		} else {
			// If not a valid file extension (CSV)
			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&product=invalid' ) );
			exit;
		}

	}

}
