<?php
/**
* Fabrics Class
*/
class fabrics extends ATQ {
	
	function __construct() {
		parent::__construct();
	}


	// Display Fabrics list
	function init() {
		?>

		<h1><?php echo get_admin_page_title(); ?> <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form'); ?>" class="page-title-action">Add New Fabric</a></h1>

		<?php $this->notify('staff'); ?>

		<table class="wp-list-table widefat fixed striped pages">
			<thead>
				<tr>
					<th width="22%">Name</th>
					<th width="22%">Suffix</th>
					<th width="12%" class="actions">Actions</th>
				</tr>
			</thead>

			<tbody id="the-list">

				<?php
                // Getting staff members
				$results = $this->wpdb->get_results("SELECT * FROM $this->fabric_tbl");

				if ($results) {

					foreach ($results as $row) {
						?>
						<tr>
							<td class="column-title">
								<strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->staff_id); ?>"><?php echo $row->staff_name; ?></a></strong>
							</td>
							<td><?php echo $row->staff_email; ?></td>
							<td><?php echo $row->staff_position; ?></td>
							<td><?php echo $row->staff_contactno; ?></td>
							<td class="actions">
								<a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->staff_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a> 
								<a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $row->staff_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"></a>
							</td>
						</tr>
						<?php
					}
				} else {
					?>
					<tr>
						<td colspan="5" style="text-align: center;"><strong>No Records Found</strong></td>
					</tr>
					<?php
				}
				?>

			</tbody>

		</table>

		<?php }
	}

	?>