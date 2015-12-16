<?php

// Quote Class
class quotes extends ATQ {

	public function __construct() {
		parent:: __construct();
	}

	// Iniating main method to display quotes
	public function init() {
		?>

		<h1><?php echo get_admin_page_title(); ?> <a
				href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=form' ); ?>"
				class="page-title-action">Add New Quote</a></h1>

		<?php $this->notify( 'Quote' ); ?>

		<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<th width="12%">ID</th>
				<th width="25%">Subject</th>
				<th width="15%">Staff Member</th>
				<th width="15%">Client</th>
				<th width="15%">Date</th>
				<th width="8%">Status</th>
				<th width="10%" class="actions">Actions</th>
			</tr>
			</thead>

			<tbody id="the-list">

			<?php
			// Get all the quotes
			$quotes = $this->wpdb->get_results( "SELECT qt.*, cl.*, mb.* FROM $this->quotes_tbl AS qt "
			                                    . "INNER JOIN $this->clients_tbl AS cl ON qt.quote_client = cl.client_id "
			                                    . "INNER JOIN $this->staff_member_tbl AS mb ON qt.quote_staff = mb.staff_id" );

			if ( $quotes ) {

				foreach ( $quotes as $row ) {

					// Quote submitted date
					$date = date_create( $row->quote_date )
					?>
					<tr>
						<td>
							AT-<?php echo $row->quote_id . '-' . date_format( $date, 'Y-m-d' ); ?>
						</td>
						<td><?php echo $row->quote_subject; ?></td>
						<td><?php echo $row->staff_name; ?></td>
						<td><?php echo $row->client_fname . ' ' . $row->client_lname; ?></td>
						<td><?php echo date_format( $date, 'Y-m-d' ); ?></td>
						<td>
							<?php
							if ( $row->quote_status == 0 ) {
								echo '<span class="quote_status quote_new">New</span>';
							} else {
								echo '<span class="quote_status quote_sent">Sent</span>';
							}
							?>
						</td>
						<td class="actions">
							<a href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $row->quote_id ); ?>"
							   class="dashicons-before dashicons-edit" title="Edit"></a>
							<a href="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=del&id=' . $row->quote_id ); ?>"
							   class="dashicons-before dashicons-trash" title="Delete"
							   onclick="return confirm('Are you sure you want to delete this?');"></a>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="7" style="text-align: center;"><strong>No Records Found</strong></td>
				</tr>
				<?php
			}
			?>

			</tbody>

		</table>

		<?php
	}

	// Add new quote form
	public function form() {

		// Get ID#
		$id = filter_input( INPUT_GET, 'id' );

		$this->notify( 'Quote' );

		echo isset( $id ) ? '<a href="#" class="send-quote button-primary">SEND QUOTE</a>' : '';
		?>

		<h1><?php echo isset( $id ) ? 'Edit Quote' : 'Add New Quote'; ?></h1>

		<?php
		if ( isset( $id ) ) {

			// Get quote data
			$quote = $this->wpdb->get_row( "SELECT * FROM $this->quotes_tbl WHERE quote_id = $id" );

			// Get client data
			$client = $this->wpdb->get_row( "SELECT * FROM $this->clients_tbl WHERE client_id = $quote->quote_client" );
			?>

			<div class="col-left">
				<fieldset>
					<Legend>Client</legend>
					<form method="post"
					      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save&update=client' ); ?>">
						<input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
						<input type="hidden" name="quote_client" value="<?php echo $quote->quote_client; ?>">
						<div class="form-field">
							<label for="client_fname">First Name</label><br>
							<input name="client_fname" id="client_fname" type="text"
							       value="<?php echo $client->client_fname; ?>">
						</div>
						<div class="form-field">
							<label for="client_lname">Last Name</label><br>
							<input name="client_lname" id="client_lname" type="text"
							       value="<?php echo $client->client_lname; ?>">
						</div>
						<div class="form-field">
							<label for="client_email">Email</label><br>
							<input type="text" name="client_email" id="client_email"
							       value="<?php echo $client->client_email; ?>">
						</div>
						<div class="form-field">
							<label for="client_email_2">Email 2</label><br>
							<input type="text" name="client_email_2" id="client_email_2"
							       value="<?php echo $client->client_email_2; ?>">
						</div>
						<div class="form-field">
							<label for="client_contactno">Contact No</label><br>
							<input type="text" name="client_contactno" id="client_contactno"
							       value="<?php echo $client->client_contactno; ?>">
						</div>
						<div class="form-field">
							<label for="client_cellno">Cell No</label><br>
							<input type="text" name="client_cellno" id="client_cellno"
							       value="<?php echo $client->client_cellno; ?>">
						</div>
						<div class="form-field">
							<label for="client_companyname">Company Name</label><br>
							<input type="text" name="client_companyname" id="client_companyname"
							       value="<?php echo $client->client_companyname; ?>">
						</div>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
						                         value="Update"></p>
					</form>
				</fieldset>
				<fieldset>
					<Legend>Member</legend>
					<form method="post"
					      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save&update=member' ); ?>">
						<input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
						<input type="hidden" name="quote_staff" value="<?php echo $quote->quote_staff; ?>">
						<div class="form-field">
							<label for="quote_subject">Member</label><br>
							<select name="quote_staff_new" id="quote_staff" required>
								<?php
								// Getting staff members list
								$staffs = $this->wpdb->get_results( "SELECT * FROM $this->staff_member_tbl" );

								// Listing all staff members
								foreach ( $staffs as $staff ) {
									echo '<option value="' . $staff->staff_id . '" ';

									selected( $staff->staff_id, $quote->quote_staff );

									echo '>' . $staff->staff_name . '</option>';
								}
								?>
							</select>
						</div>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
						                         value="Update"></p>
					</form>
				</fieldset>
			</div>
			<div class="col-right">
				<fieldset>
					<Legend>Subject</legend>
					<form method="post"
					      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save&update=subject' ); ?>">
						<input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
						<div class="form-field">
							<label for="quote_subject">Subject</label><br>
							<input name="quote_subject" id="quote_subject" type="text"
							       value="<?php echo $quote->quote_subject; ?>">
						</div>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
						                         value="Update"></p>
					</form>
				</fieldset>
				<fieldset>
					<Legend>Comments</legend>
					<form method="post"
					      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save&update=comments' ); ?>">
						<input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
						<div class="form-field">
							<?php
							// WordPress WYSIWYG Editor
							wp_editor( $quote->quote_comment, 'quote_comment', array( 'textarea_name' => 'quote_comment' ) );
							?>
						</div>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
						                         value="Update"></p>
					</form>
				</fieldset>
			</div>

			<fieldset class="quote-products">
				<legend>Quote Items</legend>
				<div class="quote-item-heading">
					<strong>Add Heading</strong><br>
					<input type="hidden" name="quote_id" id="quote_id" value="<?php echo $quote->quote_id; ?>">
					<input type="text" name="add_heading" id="add_heading" class="large-text">
					<input type="submit" value="Add Heading" class="add-heading button">&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" class="button add-sep" data-sep-id="<?php echo
					$quote->quote_id; ?>">Add Separator</a>
				</div>
				<div class="quote-item-search">
					<p>
						Simply specify the first 3 characthers of a product code, e.g. AT1. It will then give you
						options, select one and click "Add Product".</p>
					<input type="hidden" class="prod-id" name="prod_id">
					<input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>" class="quote-id">
					<input type="text" class="prod-name medium-text" name="prod_name">
					<button class="button add-prod">Add Product</button>

					<ul>
						<?php
						$products = $this->wpdb->get_results( "SELECT * FROM $this->products_tbl" );
						foreach ( $products as $product ) {
							?>
							<li data-prod-id="<?php echo $product->prod_id; ?>"><a
									href="#"><?php echo $product->prod_code . ' / ' . $product->prod_name; ?></a></li>
							<?php
						}
						?>
					</ul>
				</div>
				<div class="search-cat">
					<p style="line-height: 1em !important;">Hold down the Ctrl (windows) / Command (Mac) button to
						select multiple categories.</p>
					<select name="item_cat[]" class="item-cat" multiple required>
						<?php
						// Getting categories list
						$cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = 0" );

						// Listing all categories
						foreach ( $cats as $cat ) {
							echo '<option value="' . $cat->cat_id . '" ' . selected( true, in_array( $cat->cat_id, $this_cats ), false ) . '>' . $cat->cat_name . '</option>';

							// Sub cats
							$sub_cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = $cat->cat_id" );

							foreach ( $sub_cats as $sub_cat ) {
								echo '<option value="' . $sub_cat->cat_id . '">— ' . $sub_cat->cat_name . '</option>';

								$sub_sub_cats = $this->wpdb->get_results( "SELECT * FROM $this->categories_tbl WHERE cat_parent = $sub_cat->cat_id" );

								foreach ( $sub_sub_cats as $sub_sub_cat ) {
									echo '<option value="' . $sub_sub_cat->cat_id . '">—— ' .
									     $sub_sub_cat->cat_name . '</option>';
								}
							}

						}

						?>
					</select>
					<button class="button add-cat">Find Products</button>

				</div>

				<div id="cat-selective">
					<h3>Products from categories</h3>
					<table class="wp-list-table widefat fixed striped pages cat-selective-list">
						<thead>
						<tr>
							<th width="10%">#</th>
							<th width="20%">Code</th>
							<th width="15%">Picture</th>
							<th width="40%">Name</th>
							<th width="15%" class="actions">Category</th>
						</tr>
						</thead>
						<tbody id="the-selective-list">
						</tbody>
					</table>
					<p class="submit" style="float: right;">
						<a href="#" class="button button-primary add-selective">Add Selected Products</a>
					</p>
				</div>

				<form method="post"
				      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save&update=quote' ); ?>">
					<input type="hidden" name="quote_id" id="quote_id" value="<?php echo $quote->quote_id; ?>">
					<table class="wp-list-table widefat fixed striped pages item-list">
						<thead>
						<tr>
							<th width="15%">Picture</th>
							<th width="25%">Description</th>
							<th width="15%">Fabric Type</th>
							<th width="10%">Quantity</th>
							<th width="10%">Unit Price</th>
							<th width="10%">Sub Total</th>
							<th width="7%">Order</th>
							<th width="7%" class="actions">Actions</th>
						</tr>
						</thead>

						<tfoot>
						<tr>
							<th style="text-align: right;" colspan="8">Total R <input type="text" class="small-text">
							</th>
						</tr>
						</tfoot>

						<tbody id="the-list">
						<?php
						$quote_items = $this->wpdb->get_results( "SELECT * FROM $this->quote_items_tbl ORDER BY item_order ASC" );
						foreach ( $quote_items as $item ) {
							$images   = unserialize( $item->item_images );
							$item_pid = $item->item_pid;
							if ( $item->sep ) {
								?>
								<tr>
									<td colspan="6">
										<hr style="height: 3px; background: #666;">
									</td>
									<td>
										<input type="text" name="item[<?php echo $item->item_id; ?>][order]"
										       value="<?php echo $item->item_order; ?>"
										       style="width:30px; text-align: center;">
									</td>
									<td class="actions">
										<a href="#" data-item-id="<?php echo $item->item_id; ?>"
										   data-quote-id="<?php echo $item->item_qid; ?>"
										   class="dashicons-before dashicons-trash del-item-row" title="Delete"
										   onclick="return confirm('Are you sure you want to delete this?');"></a>
									</td>
								</tr>
								<?php
							} else if ( $item->heading ) {
								?>
								<tr>
									<td colspan="6">
										<h2><?php echo $item->heading; ?></h2>
									</td>
									<td>
										<input type="text" name="item[<?php echo $item->item_id; ?>][order]"
										       value="<?php echo $item->item_order; ?>"
										       style="width:30px; text-align: center;">
									</td>
									<td class="actions">
										<a href="#" data-item-id="<?php echo $item->item_id; ?>"
										   data-quote-id="<?php echo $item->item_qid; ?>"
										   class="dashicons-before dashicons-trash del-item-row" title="Delete"
										   onclick="return confirm('Are you sure you want to delete this?');"></a>
									</td>
								</tr>
								<?php
							} else {
								?>
								<tr>
									<td>
										<?php
										if ( $images ) {
											foreach ( $images as $image ) {
												echo '<img src="' . $image . '" alt="" width="auto" height="150"><br>';
											}
										}
										?>
										<input type="text" name="item[<?php echo $item->item_id; ?>][name]"
										       value="<?php echo $item->item_name; ?>">
									</td>
									<td>
										<?php
										// WordPress WYSIWYG Editor
										//wp_editor($item->item_desc, $textarea_id, array('textarea_name' => 'text'));
										?>
										<textarea name="item[<?php echo $item->item_id; ?>][desc]"
										          rows="10"><?php echo $item->item_desc; ?></textarea>
									</td>
									<td>
										<select name="item[<?php echo $item->item_id; ?>][fab]" id="fab_type">
											<option value="">Please Select...</option>
											<?php
											//getting fabric suffix
											$prod_fps = $this->wpdb->get_results( "SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $item_pid" );
											foreach ( $prod_fps as $prod_fp ) {

												$combo_code = $prod_fp->combo_code;
												//breaking rows into $prod_code & $fab_suffix
												list( $prod_code, $fab_suffix ) = explode( '-', $combo_code );
												$fab_suffix;

												//getting fabric names
												$fab_type = $this->wpdb->get_row( "SELECT * FROM $this->fabrics_tbl WHERE fab_suffix = '$fab_suffix'" );

												echo '<option value="' . $fab_type->fab_name . '" ';

												selected( $fab_type->fab_name );

												echo '>' . $fab_type->fab_name . '</option>';
											}
											?>

										</select>
									</td>
									<td>
										<input type="text" name="item[<?php echo $item->item_id; ?>][qty]"
										       value="<?php echo $item->item_qty; ?>" class="x-small-text item-qty">
									</td>
									<td>
										R <input type="text" name="item[<?php echo $item->item_id; ?>][unit_p]""
										value="<?php echo $item->item_price; ?>" class="x-small-text unit-price">
									</td>
									<td>
										R <input type="text" name="total_p" value="" class="x-small-text sub-total">
									</td>
									<td>
										<input type="text" name="item[<?php echo $item->item_id; ?>][order]"
										       value="<?php echo $item->item_order; ?>"
										       style="width:30px; text-align: center;">
									</td>
									<td class="actions">
										<a href="#" data-item-id=" <?php echo $item->item_id; ?>"
										   data-quote-id="<?php echo $item->item_qid; ?>"
										   class="dashicons-before dashicons-trash del-item-row" title="Delete"
										   onclick="return confirm('Are you sure you want to delete this?');"></a>
									</td>
								</tr>
								<?php
							}
						}
						?>
						</tbody>

					</table>
					<p class="submit" style="text-align: right;">
						<input type="submit" name="submit" id="submit" class="button button-primary"
						       value="Update Quote">
					</p>
				</form>

			</fieldset>
			<?php
		} else {
			?>
			<div class="col-left">
				<form method="post"
				      action="<?php echo admin_url( 'admin.php?page=' . $this->page . '&action=save' ); ?>">
					<label for="quote_staff">Staff Member<span>*</span></label><br>
					<div class="form-field">
						<select name="quote_staff" id="quote_staff" required>
							<option value="">Please select...</option>
							<?php
							// Getting staff members list
							$staffs = $this->wpdb->get_results( "SELECT * FROM $this->staff_member_tbl" );

							// Listing all staff members
							foreach ( $staffs as $staff ) {
								echo '<option value="' . $staff->staff_id . '" ';

								selected( $staff->staff_id, $row->staff_id );

								echo '>' . $staff->staff_name . '</option>';
							}
							?>
						</select>
					</div>
					<p>&nbsp;</p>
					<h3>Existing Client</h3>
					<div class="form-field client-list">
						<input type="hidden" name="quote_client" class="quote-client">
						<input type="text" class="client-holder">
						<ul>
							<?php
							// Getting clients list
							$clients = $this->wpdb->get_results( "SELECT * FROM $this->clients_tbl" );

							// Listing clients members
							foreach ( $clients as $client ) {
								echo '<li data-client-id="' . $client->client_id . '"><strong>' . $client->client_fname . ' ' . $client->client_lname . '</strong> / ' . $client->client_email . ' / ' . $client->client_companyname . '</li>';
							}
							?>
						</ul>
					</div>

					<p>&nbsp;</p>
					<h3>Or New Client?</h3>
					<div class="form-field">
						<label for="client_fname">First Name</label><br>
						<input name="client_fname" id="client_fname" type="text">
					</div>
					<div class="form-field">
						<label for="client_lname">Last Name</label><br>
						<input name="client_lname" id="client_lname" type="text">
					</div>
					<div class="form-field">
						<label for="client_email">Email</label><br>
						<input type="text" name="client_email" id="client_email">
					</div>
					<div class="form-field">
						<label for="client_email_2">Email 2</label><br>
						<input type="text" name="client_email_2" id="client_email_2">
					</div>
					<div class="form-field">
						<label for="client_contactno">Contact No</label><br>
						<input type="text" name="client_contactno" id="client_contactno">
					</div>
					<div class="form-field">
						<label for="client_cellno">Cell No</label><br>
						<input type="text" name="client_cellno" id="client_cellno">
					</div>
					<div class="form-field">
						<label for="client_companyname">Company Name</label><br>
						<input type="text" name="client_companyname" id="client_companyname">
					</div>

					<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
					                         value="Create Quote"></p>

				</form>
			</div>
			<?php
		}
		echo '<div id="update-msg">'
		     . '<img src="' . plugins_url( 'african-tusk-quote/images/' ) . 'loading.gif" width="24" height="24">'
		     . 'Updating...'
		     . '</div>';
	}

	// Save Client
	public function save() {

		// Get param of updating
		$update = filter_input( INPUT_GET, 'update' );

		// Get quote ID
		$quote_id = filter_input( INPUT_POST, 'quote_id' );

		// Get new client data
		$client_fname       = filter_input( INPUT_POST, 'client_fname', FILTER_SANITIZE_STRING );
		$client_lname       = filter_input( INPUT_POST, 'client_lname', FILTER_SANITIZE_STRING );
		$client_email       = filter_input( INPUT_POST, 'client_email', FILTER_SANITIZE_STRING );
		$client_email_2     = filter_input( INPUT_POST, 'client_email_2', FILTER_SANITIZE_STRING );
		$client_contactno   = filter_input( INPUT_POST, 'client_contactno', FILTER_SANITIZE_STRING );
		$client_cellno      = filter_input( INPUT_POST, 'client_cellno', FILTER_SANITIZE_STRING );
		$client_companyname = filter_input( INPUT_POST, 'client_companyname', FILTER_SANITIZE_STRING );

		// Get member / updated member and exisitng client IDs
		$quote_staff     = filter_input( INPUT_POST, 'quote_staff', FILTER_SANITIZE_NUMBER_INT );
		$quote_staff_new = filter_input( INPUT_POST, 'quote_staff_new', FILTER_SANITIZE_NUMBER_INT );
		$quote_client    = filter_input( INPUT_POST, 'quote_client', FILTER_SANITIZE_NUMBER_INT );

		// Get quote subject
		$quote_subject = filter_input( INPUT_POST, 'quote_subject', FILTER_SANITIZE_STRING );

		// Get quote comment
		$quote_comment = filter_input( INPUT_POST, 'quote_comment' );

		// Get quote order item
		$quote_item_arrs = filter_input( INPUT_POST, 'item', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		// Get client data
		$client_data = array(
			'client_fname'       => $client_fname,
			'client_lname'       => $client_lname,
			'client_email'       => $client_email,
			'client_email_2'     => $client_email_2,
			'client_contactno'   => $client_contactno,
			'client_cellno'      => $client_cellno,
			'client_companyname' => $client_companyname
		);


		if ( isset( $update ) && $update == 'client' ) {
			// If update client

			$client_id = array(
				'client_id' => $quote_client
			);

			// Update client data
			$this->wpdb->update( $this->clients_tbl, $client_data, $client_id );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&&update=updated' ) );
		} else if ( isset( $update ) && $update == 'member' ) {
			// If update staff
			//
			// Get updated staff data
			$staff_data = array(
				'quote_staff' => $quote_staff_new
			);

			$staff_id = array(
				'quote_staff' => $quote_staff,
				'quote_id'    => $quote_id
			);

			// Update staff data
			$this->wpdb->update( $this->quotes_tbl, $staff_data, $staff_id );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated' ) );
		} else if ( isset( $update ) && $update == 'subject' ) {
			// If update subject
			//
			// Get subject data
			$subject_data = array(
				'quote_subject' => $quote_subject
			);

			$subject_id = array(
				'quote_id' => $quote_id
			);

			// Update subject
			$this->wpdb->update( $this->quotes_tbl, $subject_data, $subject_id );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated' ) );
		} else if ( isset( $update ) && $update == 'comments' ) {
			// If update commnents
			//
			// Get comments data
			$comment_data = array(
				'quote_comment' => $quote_comment
			);

			$comment_id = array(
				'quote_id' => $quote_id
			);

			// Update subject
			$this->wpdb->update( $this->quotes_tbl, $comment_data, $comment_id );

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated' ) );
		} else if ( isset( $update ) && $update == 'quote' ) {

			// Udate quote items
			foreach ( $quote_item_arrs as $quote_item_id => $quote_item_arr ) {

				$item_data = array(
					'item_name'  => $quote_item_arr['name'],
					'item_desc'  => $quote_item_arr['desc'],
					'item_fab'   => $quote_item_arr['fab'],
					'item_qty'   => $quote_item_arr['qty'],
					'item_price' => $quote_item_arr['unit_p'],
					'item_order' => $quote_item_arr['order']
				);

				$item_id = array(
					'item_id' => $quote_item_id
				);
				$this->wpdb->update( $this->quote_items_tbl, $item_data, $item_id );

			}

			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated' ) );

		} else {

			if ( empty( $quote_client ) ) {

				// Insert new client data
				$this->wpdb->insert( $this->clients_tbl, $client_data );

				// Get new client ID
				$quote_client = $this->wpdb->insert_id;
			}

			// Get new quote data
			$quote_data = array(
				'quote_staff'   => $quote_staff,
				'quote_client'  => $quote_client,
				'quote_subject' => '',
				'quote_comment' => ''
			);

			// Insert new quote data
			$this->wpdb->insert( $this->quotes_tbl, $quote_data );

			// Get new quote ID
			$quote_id = $this->wpdb->insert_id;

			// Redirect to next quote form
			wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&action=form&id=' . $quote_id ) );
		}
	}

	// Delete product
	public function del() {

		// Getting item & quote ID
		$id       = filter_input( INPUT_GET, 'id' );
		$quote_id = filter_input( INPUT_GET, 'quote_id' );

		// If delete quote
		$quote_data = array(
			'quote_id' => $id
		);
		$quote_id   = array(
			'item_qid' => $quote_id
		);

		$this->wpdb->delete( $this->quotes_tbl, $quote_data );
		$this->wpdb->delete( $this->quote_items_tbl, $quote_id );

		wp_redirect( admin_url( 'admin.php?page=' . $this->page . '&update=deleted' ) );


		exit;
	}

}
