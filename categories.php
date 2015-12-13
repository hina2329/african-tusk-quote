<?php

// Categories Class
class categories extends ATQ {

    public function __construct() {
        parent::__construct();
    }

    // Iniating main method to display categories
    public function init() {
        ?>

        <h1><?php echo get_admin_page_title(); ?> <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form'); ?>" class="page-title-action">Add New Category</a></h1>

        <?php $this->notify('Category'); ?>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=cat_order'); ?>">

            <table class="wp-list-table widefat striped ">
                <thead>
                    <tr>
                        <th width="15%">Category ID</th>
                        <th width="15%">Category Image</th>
                        <th width="45%">Category Name</th>
                        <th width="15%">Order</th>
                        <th width="10%" class="actions">Actions</th>
                    </tr>

                </thead>

                <tbody id="the-list">

                    <?php
                    // Getting categories
                    $results = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = 0 ORDER BY cat_order ASC");

                    if ($results) {

                        foreach ($results as $row) {
                            ?>
                            <tr id="<?php echo $row->cat_id; ?>" >
                                <td>
                                    <?php echo $row->cat_id; ?>
                                </td>
                                <td>
                                    <?php
                                    if ($row->cat_image) {
                                        ?>
                                        <img src="<?php echo $row->cat_image; ?>" width="80">
                                        <?php
                                    } else {
                                        ?>
                                        <div class="no_img">NO IMAGE</div>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="column-title">
                                    <strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->cat_id); ?>"><?php echo $row->cat_name; ?></a></strong>
                                </td>
                                <td>
                                    <input type="text" name="cat_order[<?php echo $row->cat_id; ?>]" id="cat_order" style="width:30px; text-align: center;" value="<?php echo $row->cat_order; ?>">
                                </td>

                                <td class="actions">
                                    <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->cat_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                    <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $row->cat_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this category, doing so will delete all the products belongs to this category as well?');"></a>
                                </td>
                            </tr>

                            <?php
                            //getting child categories
                            $child_cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = $row->cat_id");
                            $child_cat_count = count($child_cats);
                            $i = 0;
                            foreach ($child_cats as $child_cat) {
                                $i++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $child_cat->cat_id; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($child_cat->cat_image) {
                                            ?>
                                            <img src="<?php echo $child_cat->cat_image; ?>" width="80">
                                            <?php
                                        } else {
                                            ?>
                                            <div class="no_img">NO IMAGE</div>
                                            <?php
                                        }
                                        ?>
                                    </td>


                                    <td>
                                        <strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $child_cat->cat_id); ?>">— <?php echo $child_cat->cat_name; ?></a></strong></td>
                                    <td>
                                        <input type="text" name="cat_order[<?php echo $child_cat->cat_id; ?>]" id="cat_order" style="width:30px; text-align: center;" value="<?php echo $child_cat->cat_order; ?> ">

                                    </td>
                                    <td class="actions">
                                        <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $child_cat->cat_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                        <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $child_cat->cat_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this category, doing so will delete all the products belongs to this category as well?');"></a>
                                    </td>


                                </tr>
                                <?php
                                //getting sub categories
                                $sub_cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = $child_cat->cat_id");
                                $sub_cat_count = count($sub_cats);
                                $i = 0;
                                foreach ($sub_cats as $sub_cat) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $sub_cat->cat_id; ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($sub_cat->cat_image) {
                                                ?>
                                                <img src="<?php echo $sub_cat->cat_image; ?>" width="80">
                                                <?php
                                            } else {
                                                ?>
                                                <div class="no_img">NO IMAGE</div>
                                                <?php
                                            }
                                            ?>
                                        </td>


                                        <td>
                                            <strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $sub_cat->cat_id); ?>">—— <?php echo $sub_cat->cat_name; ?></a></strong></td>
                                        <td>
                                            <input type="text" name="cat_order[<?php echo $sub_cat->cat_id; ?>]" id="cat_order" style="width:30px; text-align: center;" value="<?php echo $sub_cat->cat_order; ?> ">

                                        </td>
                                        <td class="actions">
                                            <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $sub_cat->cat_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                            <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $sub_cat->cat_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this category, doing so will delete all the products belongs to this category as well?');"></a>
                                        </td>
                                        <?php
                                    }
                                    ?>


                                </tr>
                                <?php
                            }
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

            <div class="btn-right">
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Order"></p>
            </div>

        </form>

        <?php
    }

    // Add new or edit category form
    public function form() {

        // Getting category data if user requests to edit
        $id = filter_input(INPUT_GET, 'id');
        $row = $this->wpdb->get_row("SELECT * FROM $this->categories_tbl WHERE cat_id = $id");
        ?>

        <h1><?php echo isset($id) ? 'Edit Category' : 'Add New Category'; ?></h1>

        <div class="col-left">
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">
                <input type="hidden" name="cat_id" value="<?php echo $row->cat_id; ?>">
                <div class="form-field">
                    <label for="cat_name">Category Name <span>*</span></label>
                    <input name="cat_name" id="cat_name" type="text" value="<?php echo $row->cat_name; ?>" required>
                </div>
                <div class="form-field">
                    <label for="cat_desc">Category Description</label>
                    <textarea name="cat_desc" id="cat_desc" rows="5" cols="40" ><?php echo $row->cat_desc; ?></textarea>
                </div>
                <div class="form-field">
                    <label for="cat_image">Category Image</label>
                    <input name="cat_image" id="cat_img" class="img_field" type="text" size="20" value="<?php echo $row->cat_image; ?>">
                    <input class="upload_image_button" type="button" value="Upload Image">
                </div>
                <div class="form-field">
                    <label for="cat_parent">Parent</label>
                    <select name="cat_parent" id="cat_parent">
                        <option value="0">Select Parent</option>
                        <?php
                        // Getting categories list
                        $cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = 0");

                        // Listing all categories
                        foreach ($cats as $cat) {
                            echo "<option value=\"" . $cat->cat_id . '" ' . selected($cat->cat_id, $row->cat_id, false) . '>' . $cat->cat_name . '</option>';

                            // Sub cats
                            $sub_cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = $cat->cat_id");

                            foreach ($sub_cats as $sub_cat) {
                                echo '<option value="' . $sub_cat->cat_id . '" ' . selected($sub_cat->cat_id, $row->cat_id, false) . '>— ' . $sub_cat->cat_name . '</option>';

                                $sub_sub_cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl WHERE cat_parent = $sub_cat->cat_id");

                                foreach ($sub_sub_cats as $sub_sub_cat) {
                                    echo '<option value="' . $sub_sub_cat->cat_id . '" ' . selected($sub_sub_cat->cat_id, $row->cat_id, false) . '>—— ' . $sub_sub_cat->cat_name . '</option>';
                                }
                            }
                        }
                        ?>

                    </select>
                </div>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo isset($id) ? 'Update Category' : 'Add New Category'; ?>"></p>
            </form>
        </div>

        <?php
    }

    // Save category
    public function save() {

        // Getting submitted data

        $id = filter_input(INPUT_POST, 'cat_id');
        $cat_name = filter_input(INPUT_POST, 'cat_name', FILTER_SANITIZE_STRING);
        $cat_desc = filter_input(INPUT_POST, 'cat_desc');
        $cat_image = filter_input(INPUT_POST, 'cat_image');
        $cat_parent = filter_input(INPUT_POST, 'cat_parent');

        if (!empty($id)) {

            $cat_data = array(
                'cat_name' => $cat_name,
                'cat_desc' => $cat_desc,
                'cat_image' => $cat_image,
                'cat_parent' => $cat_parent
            );
            $data_id = array(
                'cat_id' => $id
            );

            $this->wpdb->update($this->categories_tbl, $cat_data, $data_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=updated'));

            exit;
        } else {

            $cat_data = array(
                'cat_name' => $cat_name,
                'cat_desc' => $cat_desc,
                'cat_image' => $cat_image,
                'cat_parent' => $cat_parent
            );

            $this->wpdb->insert($this->categories_tbl, $cat_data);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=added'));

            exit;
        }
    }

    // Sort orders
    public function cat_order() {
        $orders = filter_input(INPUT_POST, 'cat_order', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        foreach ($orders as $cat_id => $order) {

            $sort = array(
                'cat_order' => $order
            );

            $cat = array(
                'cat_id' => $cat_id
            );


            $this->wpdb->update($this->categories_tbl, $sort, $cat);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=updated'));
        }

        exit;
    }

    // Delete category
    public function del() {

        // Getting category ID
        $id = filter_input(INPUT_GET, 'id');

        $cat_data = array(
            'cat_id' => $id
        );

        $this->wpdb->delete($this->categories_tbl, $cat_data);

        wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=deleted'));

        exit;
    }

}
