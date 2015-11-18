
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

        <?php $this->notify('Fabric'); ?>

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
                $results = $this->wpdb->get_results("SELECT * FROM $this->fabrics_tbl");

                if ($results) {

                    foreach ($results as $row) {
                        ?>
                        <tr>
                            <td class="column-title">
                                <strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->fab_id); ?>"><?php echo $row->fab_name; ?></a></strong>
                            </td>
                            <td><?php echo $row->fab_suffix; ?></td>
                            <td>
                                <?php
                                $fab_colors = unserialize($row->fab_colors);
                                $color_count = count($fab_colors);
                                for ($i = 0; $i < $color_count; $i++) {
                                    echo $fab_colors[$i]['fab_color'];
                                    if (($i + 1) != $color_count) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </td>
                            <td class="actions">
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->fab_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $row->fab_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"></a>
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
        $row = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_id = $id");
        ?>

        <h1><?php echo isset($id) ? 'Edit Fabric' : 'Add New Fabric'; ?></h1>

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
                    <label>Fabric Colors <a href="#" class="btn-fields add-fields">+ Add Color</a></label>
                    <div class="atq-multi-fields-container">

                        <?php
                        if (!empty($id)) {
                            $fab_colors = unserialize($row->fab_colors);
                            foreach ($fab_colors as $color) {
                                ?>
                                <div class="multi-fields">
                                    <input name="fab_color[]" class="fab_color" type="text" value="<?php echo $color['fab_color']; ?>" placeholder="Fabric Color Name">
                                    <input name="fab_img[]" class="fab_img" type="text" value="<?php echo $color['fab_img']; ?>" placeholder="Fabric Thumbnail">
                                    <input class="upload_image_button" type="button" value="Upload Image"><a href="#" class="btn-fields remove-fields">X remove</a>
                                </div>
                                <?php
                            }
                        }
                        ?> 

                    </div>
                </div>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo isset($id) ? 'Update Fabric' : 'Add New Fabric'; ?>"></p>
            </form>
            <!-- CLONE MULTIPLE FIELDS -->
            <div class="multi-fields screen-reader-text">
                <input name="fab_color[]" class="fab_color" type="text" value="" placeholder="Fabric Color Name">
                <input name="fab_img[]" class="fab_img" type="text" value="" placeholder="Fabric Thumbnail">
                <input class="upload_image_button" type="button" value="Upload Image"><a href="#" class="btn-fields remove-fields">X remove</a>
            </div>
            <!-- CLONE MULTIPLE FIELDS -->
        </div>

        <?php
    }

    // Save fabric
    function save() {

        // Get input data
        $id = filter_input(INPUT_POST, 'fab_id');
        $fab_name = filter_input(INPUT_POST, 'fab_name');
        $fab_suffix = filter_input(INPUT_POST, 'fab_suffix');
        $fab_colors_count = count(filter_input(INPUT_POST, 'fab_color', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY));

        $fab_colors_raw = [];
        for ($i = 0; $i < $fab_colors_count; $i++) {
            $fab_colors_raw[$i] = [
                'fab_color' => $_POST['fab_color'][$i],
                'fab_img' => $_POST['fab_img'][$i]
            ];
        }

        $fab_colors = serialize($fab_colors_raw);

        if (!empty($id)) {

            $fab_data = array(
                'fab_name' => $fab_name,
                'fab_suffix' => $fab_suffix,
                'fab_colors' => $fab_colors
            );

            $data_id = array(
                'fab_id' => $id
            );

            $this->wpdb->update($this->fabrics_tbl, $fab_data, $data_id);
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=updated'));

            exit;
            
        } else {
            
            $fab_data = array(
                'fab_name' => $fab_name,
                'fab_suffix' => $fab_suffix,
                'fab_colors' => $fab_colors
            );

            $this->wpdb->insert($this->fabrics_tbl, $fab_data);
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=added'));

            exit;
        }
    }

    // Delete fabric
    public function del() {

        // Getting category ID
        $id = filter_input(INPUT_GET, 'id');

        $fab_data = array(
            'fab_id' => $id
        );
        
        $this->wpdb->delete($this->fabrics_tbl, $fab_data);

        wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=deleted'));
        exit;
    }

}
