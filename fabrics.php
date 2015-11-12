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
                    <th width="30%">Name</th>
                    <th width="30%">Suffix</th>
                    <th width="30%">Colors</th>
                    <th width="10%" class="actions">Actions</th>
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
                            <td>COLORS NAME</td>
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
                        <td colspan="4" style="text-align: center;"><strong>No Records Found</strong></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>

        </table>

        <?php
    }

    // Add new or edit form
    public function form() {

        // Getting options data if user requests to edit
        $id = filter_input(INPUT_GET, 'id');
        $row = $this->wpdb->get_row("SELECT * FROM $this->options_tbl WHERE fab_id = $id");
        ?>

        <h1><?php echo isset($id) ? 'Edit Option' : 'Add New Option'; ?></h1>

        <div class="col-left">
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">
                <input type="hidden" name="fab_id" value="<?php echo $id; ?>">
                <div class="form-field">
                    <label for="fab_name">Fabric Name <span>*</span></label>
                    <input name="fab_name" id="fab_name" type="text" value="<?php echo $row->fab_name; ?>" required>
                </div>
                <div class="form-field">
                    <label for="fab_suffix">Suffix<span>*</span></label>
                    <input name="fab_suffix" id="fab_suffix" type="text" value="<?php echo $row->fab_suffix; ?>" required>
                </div>
                <div class="form-field">
                      <label for="fab_colors">Fabric Colors</label>
                </div>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo isset($id) ? 'Edit Option' : 'Add New Option'; ?>"></p>
            </form>
        </div>

        <?php
    }

}
