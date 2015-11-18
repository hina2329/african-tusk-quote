<?php

/**
 * Staff Members Class
 */
class staff_member extends ATQ {

    public function __construct() {
        parent::__construct();
    }

    // Iniating main method to display staff members
    public function init() {
        ?>

        <h1><?php echo get_admin_page_title(); ?> <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form'); ?>" class="page-title-action">Add New Staff Member</a></h1>

        <?php $this->notify('Staff'); ?>

        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th width="22%">Name</th>
                    <th width="22%">Email</th>
                    <th width="22%">Position</th>
                    <th width="22%">Contact No</th>
                    <th width="12%" class="actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php
                // Getting staff members
                $results = $this->wpdb->get_results("SELECT * FROM $this->staff_member_tbl");

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
        <?php
    }

    // Add new or edit staff member
    public function form() {

        // Getting staff member data if user requests to edit
        $id = filter_input(INPUT_GET, 'id');
        $row = $this->wpdb->get_row("SELECT * FROM $this->staff_member_tbl WHERE staff_id = $id");
        ?>

        <h1><?php echo isset($id) ? 'Edit Staff Members' : 'Add New Member'; ?></h1>

        <div class="col-left">
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">
                <input type="hidden" name="staff_id" value="<?php echo $id; ?>">
                <div class="form-field">
                    <label for="staff_name">Name <span>*</span></label><br>
                    <input name="staff_name" id="staff_name" type="text" value="<?php echo $row->staff_name; ?>" required>
                </div>
                <div class="form-field">
                    <label for="staff_email">Email <span>*</span></label><br>
                    <input type="text" name="staff_email" id="staff_email" value="<?php echo $row->staff_email; ?>" required>
                </div>
                <div class="form-field">
                    <label for="staff_position">Position <span>*</span></label><br>
                    <input type="text" name="staff_position" id="staff_position" value="<?php echo $row->staff_position; ?>" required>
                </div>
                <div class="form-field">
                    <label for="staff_contactno">Contact NO <span>*</span></label><br>
                    <input type="text" name="staff_contactno" id="staff_contactno" value="<?php echo $row->staff_contactno; ?>" required>
                </div>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo isset($id) ? 'Update staff Member' : 'Add New Member'; ?>"></p>
            </form>
        </div>

        <?php
    }

    // Save Staff members
    public function save() {

        // Getting submitted data
        $id = filter_input(INPUT_POST, 'staff_id');
        $staff_name = filter_input(INPUT_POST, 'staff_name', FILTER_SANITIZE_STRING);
        $staff_email = filter_input(INPUT_POST, 'staff_email', FILTER_SANITIZE_STRING);
        $staff_position = filter_input(INPUT_POST, 'staff_position', FILTER_SANITIZE_STRING);
        $staff_contactno = filter_input(INPUT_POST, 'staff_contactno', FILTER_SANITIZE_STRING);

        if (!empty($id)) {

            $staff_data = array(
                'staff_name' => $staff_name,
                'staff_email' => $staff_email,
                'staff_position' => $staff_position,
                'staff_contactno' => $staff_contactno
            );
            
            $data_id = array(
                'staff_id' => $id
            );
            
            $this->wpdb->update($this->staff_member_tbl, $staff_data, $data_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=updated'));

            exit;
            
        } else {

            $staff_data = array(
                'staff_name' => $staff_name,
                'staff_email' => $staff_email,
                'staff_position' => $staff_position,
                'staff_contactno' => $staff_contactno
            );
            $this->wpdb->insert($this->staff_member_tbl, $staff_data);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=added'));

            exit;
        }
    }

    // Delete staff members
    public function del() {

        // Getting staff id
        $id = filter_input(INPUT_GET, 'id');

        $staff_data = array(
            'staff_id' => $id
        );

        $this->wpdb->delete($this->staff_member_tbl, $staff_data);

        wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=deleted'));

        exit;
    }

}
