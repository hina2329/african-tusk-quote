<?php

// Products Class
class products extends ATQ {

	public function __construct() {
		parent::__construct();

		require_once 'products_fabric_price_combo.php';
	}

	// Iniating main method to display products
	/**
	 *
	 */
	public function init() {
		

		
				$search = filter_input(INPUT_POST, 's');
        ?>
        <h1>
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=init&search=true'); ?>" class="search-box">
                <label class="screen-reader-text" for="search-input">Search Clients:</label>
                <input type="search" id="search-input" name="s" value="">
                <input type="submit" id="search-submit" class="button" value="Search Products">
                <a class="button button-primary" href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=init'); ?>">Reset Search</a>
            </form>
            <?php echo get_admin_page_title(); ?> <a
				href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=form' ); ?>"
				class="page-title-action">Add New Product</a></h1>

		<?php $this->notify( 'Product' ); ?>
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<th width="5%">ID</th>
				<th width="10%">Code</th>
				<th width="40%">Title</th>
				<th width="25%">Category</th>
				<th width="15%">Fabric Type</th>
				<th width="15%" class="actions">Actions</th>
			</tr>
			</thead>

			<tbody id="the-list">

			<?php
			// Getting products & categories & fabric types
			 // Getting clients
                if (isset($search)) {
                    $results = $this->wpdb->get_results("SELECT * FROM $this->products_tbl WHERE prod_name LIKE '%$search%' ");
                } else {

			$results = $this->wpdb->get_results( "SELECT * FROM $this->products_tbl" );
		}


			if ( $results ) {

				foreach ( $results as $row ) {
					?>
					<tr>

						<td class="column-title">
							<strong><a
									href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $row->prod_id ); ?>"><?php echo $row->prod_id; ?></a></strong>
						</td>

						<td><?php echo $row->prod_code; ?></td>
						<td><?php echo $row->prod_name; ?></td>
						<td>
							<?php
							$cat_rows  = $this->wpdb->get_results( "SELECT * FROM $this->categories_relation_tbl WHERE prod_id = $row->prod_id" );
							$i         = 0;
							$cat_count = count( $cat_rows );
							foreach ( $cat_rows as $cat_row ) {
								$i ++;

								// Get related gategories

								$cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_id = $cat_row->cat_id" );


								foreach ( $cats as $cat ) {

									echo $cat->cat_name;

									if ( $i < $cat_count ) {
										echo ', ';
									}
								}
							}
							?>
						</td>
						<td>
							<?php
							$prod_fabs       = $this->wpdb->get_results( "SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $row->prod_id" );
							$prod_fabs_count = count( $prod_fabs );
							$i               = 0;

							foreach ( $prod_fabs as $prod_fab ) {
								$i ++;

								// Get related fabrics
								$fab = $this->wpdb->get_row( "SELECT * FROM $this->fabrics_tbl WHERE fab_id =
								$prod_fab->combo_fid" );

								echo $fab->fab_name;

								if ( $i < $prod_fabs_count ) {
									echo ', ';
								}
							}
							?>
						</td>

						<td class="actions">
							<a href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $row->prod_id ); ?>"
							   class="dashicons-before dashicons-edit" title="Edit"></a>
							<a href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=del&id=' . $row->prod_id ); ?>"
							   class="dashicons-before dashicons-trash" title="Delete"
							   onclick="return confirm('Are you sure you want to delete this?');"></a>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="6" style="text-align: center;"><strong>No Records Found</strong></td>
				</tr>
				<?php
			}
			?>


			</tbody>

		</table>

		<?php
	}

	// Add new or edit product form
	public function form() {

		// Getting product data if user requests to edit
		$id  = filter_input( INPUT_GET, 'id' );
		$row = $this->wpdb->get_row( "SELECT * FROM $this->products_tbl WHERE prod_id = $id" );
		?>
		<div class="full-width">
			<h1><?php echo isset( $id ) ? 'Edit Product' : 'Add New Product'; ?></h1>
			<form method="post" action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save' ); ?>">
				<input type="hidden" name="prod_id" value="<?php echo $id; ?>">
				<div class="form-field">
					<label for="prod_name">Title<span>*</span></label>
					<input name="prod_name" id="prod_name" type="text" value="<?php echo $row->prod_name; ?>" required>
				</div>
				<div class="form-field">
					<label for="prod_desc">Description</label>
					<?php
					// WordPress WYSIWYG Editor
					wp_editor( $row->prod_desc, 'prod_desc', array( 'textarea_name' => 'prod_desc' ) );
					?>
				</div>
				<div class="form-field">
					<label for="prod_image">Images<a href="#" class="btn-fields add-fields">+ Add Image</a></label><br>
					<div class="atq-multi-fields-container">
						<?php
						$images       = unserialize( $row->prod_images );
						$images_count = count( $images );

						for ( $i = 0; $i < $images_count; $i ++ ) {
							?>
							<div class="multi-fields">
								<input name="prod_image[]" class="img_field" type="text" size="20"
								       value="<?php echo $images[ $i ]; ?>">
								<input class="upload_image_button" type="button" value="Upload Image">
								<a href="#" class="btn-fields remove-fields">X remove</a>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<div class="form-field">
					<label for="prod_code">Code<span>*</span></label><br>
					<input name="prod_code" id="prod_code" type="text" value="<?php echo $row->prod_code; ?>"
					       class="small-text" required>
				</div>
				<div class="form-field">
					<label for="prod_cat">Category <span>*</span></label><br>

					<small style="line-height: 1em !important;">Hold down the Ctrl (windows) / Command (Mac) button to
						select multiple categories.
					</small>
					<br>
					<select name="prod_cat[]" id="prod_cat" multiple required>
						<?php
						// Getting categories list
						$cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = 0" );

						// Listing all categories
						foreach ( $cats as $cat ) {

							$cat_rel = $this->wpdb->get_row( "SELECT * FROM $this->categories_relation_tbl WHERE prod_id = $row->prod_id AND cat_id = $cat->cat_id" );

							echo '<option value="' . $cat->cat_id . '"' . selected( $cat->cat_id, $cat_rel->cat_id, false ) . ' >' . $cat->cat_name . '</option>';

							// Sub cats
							$sub_cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = $cat->cat_id" );

							foreach ( $sub_cats as $sub_cat ) {

								$sub_cat_rel = $this->wpdb->get_row( "SELECT * FROM $this->categories_relation_tbl WHERE prod_id = $row->prod_id AND cat_id = $sub_cat->cat_id" );

								echo '<option value="' . $sub_cat->cat_id . '" ' . selected( $sub_cat->cat_id, $sub_cat_rel->cat_id, false ) . '>— ' . $sub_cat->cat_name . '</option>';

								$sub_sub_cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = $sub_cat->cat_id" );

								foreach ( $sub_sub_cats as $sub_sub_cat ) {

									$sub_sub_cat_rel = $this->wpdb->get_row( "SELECT * FROM $this->categories_relation_tbl WHERE prod_id = $row->prod_id AND cat_id = $sub_sub_cat->cat_id" );

									echo '<option value="' . $sub_sub_cat->cat_id . '" ' . selected( $sub_sub_cat->cat_id, $sub_sub_cat_rel->cat_id, false ) . '>—— ' . $sub_sub_cat->cat_name . '</option>';
								}
							}

						}
						?>
					</select>
				</div>
				<div class="form-field">
					<label for="prod_size">Size</label><br>
					<input name="prod_size" id="prod_size" type="text" value="<?php echo $row->prod_size; ?>"
					       class="small-text">
				</div>
				<div class="form-field">
					<label for="prod_fab">Fabric Type<a href="#" class="btn-fields add-fabric">+ Add Fabric</a></label>
				</div>
				<div class="fabric-list">
					<?php
					// Get combos related to this product
					$combos = $this->wpdb->get_results( "SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $id" );

					foreach ( $combos as $combo ) {
						?>
						<div class="multi-fields-fab-price fab-price">
							<select name="prod_fab[]" id="prod_fab">
								<option value="0">Select Fabric...</option>
								<?php
								// Getting fabrics list
								$fabs = $this->wpdb->get_results( "SELECT * FROM $this->fabrics_tbl" );

								// Listing all fabrics
								foreach ( $fabs as $fab ) {
									$fab_code = $row->prod_code . '-' . $fab->fab_suffix;
									?>
									<option
										value="<?php echo $fab->fab_suffix; ?>" <?php selected( $fab_code, $combo->combo_code ); ?>><?php echo $fab->fab_name . ' / ' . $fab->fab_suffix; ?></option>
									<?php
								}
								?>
							</select><br>
							R <input type="text" name="prod_price[]" id="prod-fab-price" class="small-text"
							         value="<?php echo $combo->combo_price; ?>">
							<a href="#" class="btn-fields remove-fields remove-fab">X remove</a>
						</div>
						<?php
					}
					?>
				</div>

				<div class="form-field">
					<label for="prod_seller">Mark this product as Best Seller: </label>
					<input name="prod_seller" id="prod_seller" type="checkbox"
					       value="1" <?php checked( $row->prod_seller, '1' ); ?>>
				</div>
				<div class="form-field">
					<label for="prod_sale">Mark this product as Sale Product: </label>
					<input name="prod_sale" id="prod_sale" type="checkbox"
					       value="1" <?php checked( $row->prod_sale, '1' ); ?>>
				</div>
				<div class="form-field">
					<label for="prod_new">Mark this product as New: </label>
					<input name="prod_new" id="prod_new" type="checkbox"
					       value="1" <?php checked( $row->prod_new, '1' ); ?>>
				</div>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
				                         value="<?php echo isset( $id ) ? 'Update Product' : 'Add New Product'; ?>"></p>
			</form>
			<!-- CLONE MULTIPLE FIELDS -->
			<div class="cloner">
				<div class="multi-fields screen-reader-text">
					<input name="prod_image[]" class="img_field" type="text" size="20" value="">
					<input class="upload_image_button" type="button" value="Upload Image">
					<a href="#" class="btn-fields remove-fields">X remove</a>
				</div>
				<div class="multi-fields-fab-price screen-reader-text">
					<select name="prod_fab[]" id="prod_fab">
						<option value="0">Select Fabric...</option>
						<?php
						// Getting fabrics list
						$fabs = $this->wpdb->get_results( "SELECT * FROM $this->fabrics_tbl" );

						// Listing all fabrics
						foreach ( $fabs as $fab ) {
							?>
							<option
								value="<?php echo $fab->fab_suffix; ?>;<?php echo $fab->fab_id; ?>">
								<?php echo $fab->fab_name . ' / ' . $fab->fab_suffix; ?>
							</option>
							<?php
						}
						?>
					</select><br>
					R <input type="text" name="prod_price[]" id="prod-fab-price" class="small-text">
					<a href="#" class="btn-fields remove-fields remove-fab">X remove</a>
				</div>
			</div>
			<!-- CLONE MULTIPLE FIELDS -->
		</div>

		<?php
	}

	// Save product
	public function save() {

		// Getting submitted data
		$id              = filter_input( INPUT_POST, 'prod_id' );
		$prod_name       = filter_input( INPUT_POST, 'prod_name', FILTER_SANITIZE_STRING );
		$prod_desc       = filter_input( INPUT_POST, 'prod_desc' );
		$prod_images_arr = filter_input( INPUT_POST, 'prod_image', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$prod_images     = serialize( $prod_images_arr );
		$prod_code       = filter_input( INPUT_POST, 'prod_code', FILTER_SANITIZE_STRING );
		$prod_cat_arr    = filter_input( INPUT_POST, 'prod_cat', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$prod_size       = filter_input( INPUT_POST, 'prod_size', FILTER_SANITIZE_STRING );
		$prod_seller     = filter_input( INPUT_POST, 'prod_seller' );
		$prod_sale       = filter_input( INPUT_POST, 'prod_sale' );
		$prod_new        = filter_input( INPUT_POST, 'prod_new' );
		$prod_fab_arr    = filter_input( INPUT_POST, 'prod_fab', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$prod_price_arr  = filter_input( INPUT_POST, 'prod_price', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$prod_data = array(
			'prod_name'   => $prod_name,
			'prod_desc'   => $prod_desc,
			'prod_images' => $prod_images,
			'prod_code'   => $prod_code,
			'prod_size'   => $prod_size,
			'prod_seller' => $prod_seller,
			'prod_sale'   => $prod_sale,
			'prod_new'    => $prod_new,
		);

		if ( ! empty( $id ) ) {

			$data_id = array(
				'prod_id' => $id
			);

			$this->wpdb->update( $this->products_tbl, $prod_data, $data_id );

			/**
			 *
			 * Adding categories relationships with products
			 *
			 */
			// Delete existing stored relationships
			$this->wpdb->delete( $this->categories_relation_tbl, array( 'prod_id' => $id ) );

			foreach ( $prod_cat_arr as $cat ) {
				$this->wpdb->insert( $this->categories_relation_tbl, array( 'prod_id' => $id, 'cat_id' => $cat ) );
			}

			// Adding combos
			new FPCombo( $id, $prod_fab_arr, $prod_price_arr, $prod_code, 'update' );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&update=updated' ) );

			exit;
		} else {

			$this->wpdb->insert( $this->products_tbl, $prod_data );

			// Get product ID
			$prod_id = $this->wpdb->insert_id;

			/**
			 *
			 * Adding categories relationships with products
			 *
			 */
			foreach ( $prod_cat_arr as $cat ) {
				$this->wpdb->insert( $this->categories_relation_tbl, array( 'prod_id' => $prod_id, 'cat_id' => $cat ) );
			}

			// Adding combos
			new FPCombo( $prod_id, $prod_fab_arr, $prod_price_arr, $prod_code, 'insert' );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&update=added' ) );

			exit;
		}
	}

	// Delete product
	public function del() {

		// Getting category ID
		$id = filter_input( INPUT_GET, 'id' );

		$prod_data = array(
			'prod_id' => $id
		);

		$this->wpdb->delete( $this->products_tbl, $prod_data );

		wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&update=deleted' ) );

		exit;

	}

}
