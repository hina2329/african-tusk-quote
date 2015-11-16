<?php

// Products Class
class products extends ATQ {

    public function __construct() {
        parent::__construct();
    }

    // Iniating main method to display products
    public function init() {
        ?>

        <h1><?php echo get_admin_page_title(); ?> <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form'); ?>" class="page-title-action">Add New Product</a></h1>

        <?php $this->notify('Product'); ?>

        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="20%">Code</th>
                    <th width="25%">Title</th>
                    <th width="25%">Category</th>
                    <th width="15%">Type</th>
                    <th width="15%" class="actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php
// Getting products & categories & fabric types
                $results = $this->wpdb->get_results("SELECT prod.*, cat.*, fab.*  FROM $this->products_tbl AS prod INNER JOIN $this->categories_tbl AS cat ON prod.prod_cat = cat.cat_id INNER JOIN $this->fabrics_tbl AS fab ON prod.prod_fab= fab.fab_id");

                if ($results) {

                    foreach ($results as $row) {
                        ?>
                        <tr>

                            <td class="column-title">
                                <strong><a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->prod_id); ?>"><?php echo $row->prod_id; ?></a></strong>
                            </td>

                            <td><?php echo $row->prod_code; ?></td>
                            <td><?php echo $row->prod_name; ?></td>
                            <td><?php echo $row->cat_name; ?></td>
                            <td><?php echo $row->fab_name; ?></td>
                            <td class="actions">
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->prod_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $row->prod_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"></a>
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
        $id = filter_input(INPUT_GET, 'id');
        $row = $this->wpdb->get_row("SELECT prod.*, cat.*, fab.*  FROM $this->products_tbl AS prod INNER JOIN $this->categories_tbl AS cat ON prod.prod_cat = cat.cat_id INNER JOIN $this->fabrics_tbl AS fab ON prod.prod_fab= fab.fab_id WHERE prod.prod_id = $id");
        ?>
        <div class="col-left">
            <h1><?php echo isset($id) ? 'Edit Product' : 'Add New Product'; ?></h1>
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">
                <input type="hidden" name="prod_id" value="<?php echo $id; ?>">
                <div class="form-field">
                    <div class="form-field">
                        <label for="prod_name">Title<span>*</span></label>
                        <input name="prod_name" id="prod_name" type="text" value="<?php echo $row->prod_name; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="prod_desc">Description <span>*</span></label>
                        <textarea name="prod_desc" id="prod_desc" rows="5" cols="40" required><?php echo $row->prod_desc; ?></textarea>
                    </div>
                    <div class="form-field">
                        <label for="prod_price">Price<span>*</span></label><br>
                        $ <input name="prod_price" id="prod_price" type="text" value="<?php echo $row->prod_price; ?>" class="small-text" required>
                    </div>
                    <div class="form-field">
                        <label for="prod_image">Image <span>*</span></label><br>
                        <input name="prod_image" id="prod_image" type="text" size="20"  value="<?php echo $row->prod_image; ?>" required>
                        <input class="upload_image_button" type="button" value="Upload Image">
                    </div>
                    <div class="form-field">
                        <label for="prod_code">Code<span>*</span></label><br>
                        <input name="prod_code" id="prod_code" type="text" value="<?php echo $row->prod_code; ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="prod_cat">Category <span>*</span></label><br>
                        <select name="prod_cat" id="prod_cat" required>
                            <option value="">Please select...</option>
                            <?php
// Getting categories list
                            $cats = $this->wpdb->get_results("SELECT * FROM $this->categories_tbl");

                            // Listing all categories
                            foreach ($cats as $cat) {
                                echo '<option value="' . $cat->cat_id . '" ';

                                selected($cat->cat_id, $row->cat_id);

                                echo '>' . $cat->cat_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>


                    <div class="form-field">
                        <label for="prod_size">Size<span>*</span></label>
                        <input name="prod_size" id="prod_size" type="text" value="<?php echo $row->prod_size; ?>" required>
                    </div>

                    <div class="form-field">
                        <label for="prod_fab">Fabric Type<span>*</span></label>
                        <input name="prod_fab" id="prod_fab" type="text" value="<?php echo $row->prod_fab; ?>" required>
                    </div>

                    <div class="form-field">
                        <label for="prod_featured">Mark this product as Featured: </label>

                        <?php
                        if ($row->prod_featured & $row->prod_sale) {
                            $check = 'checked';
                        }
                        ?>

                        <input name="prod_featured" id="prod_featured" type="checkbox" value="1" <?php echo $check; ?>>
                    </div>
                    <div class="form-field">
                        <label for="prod_sale">Mark this product as Sale Product: </label>
                        <input name="prod_sale" id="prod_sale" type="checkbox"  value="1" <?php echo $check; ?> >
                    </div>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo isset($id) ? 'Update Product' : 'Add New Product'; ?>"></p>
            </form>
        </div>

        <?php
    }

// Save product
    public function save() {

        // Getting submitted data
        $id = filter_input(INPUT_POST, 'prod_id');
        $prod_name = filter_input(INPUT_POST, 'prod_name', FILTER_SANITIZE_STRING);
        $prod_desc = filter_input(INPUT_POST, 'prod_desc', FILTER_SANITIZE_STRING);
        $prod_price = filter_input(INPUT_POST, 'prod_price');
        $prod_image = filter_input(INPUT_POST, 'prod_image', FILTER_SANITIZE_STRING);
        $prod_code = filter_input(INPUT_POST, 'prod_code', FILTER_SANITIZE_STRING);
        $prod_cat = filter_input(INPUT_POST, 'prod_cat');
        $prod_size = filter_input(INPUT_POST, 'prod_size', FILTER_SANITIZE_STRING);
        $prod_fab = filter_input(INPUT_POST, 'prod_fab');
        $prod_featured = filter_input(INPUT_POST, 'prod_featured');
        $prod_sale = filter_input(INPUT_POST, 'prod_sale');

        if (!empty($id)) {

            $this->wpdb->update($this->products_tbl, array('prod_name' => $prod_name, 'prod_desc' => $prod_desc, 'prod_price' => $prod_price, 'prod_image' => $prod_image, 'prod_code' => $prod_code, 'prod_cat' => $prod_cat, 'prod_size' => $prod_size, 'prod_fab' => $prod_fab, 'prod_featured' => $prod_featured, 'prod_sale' => $prod_sale), array('prod_id' => $id));

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=updated'));

            exit;
        } else {

            $this->wpdb->insert($this->products_tbl, array('prod_name' => $prod_name, 'prod_desc' => $prod_desc, 'prod_price' => $prod_price, 'prod_image' => $prod_image, 'prod_code' => $prod_code, 'prod_cat' => $prod_cat, 'prod_size' => $prod_size, 'prod_fab' => $prod_fab, 'prod_featured' => $prod_featured, 'prod_sale' => $prod_sale));
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=added'));

            exit;
        }
    }

    // Delete product
    public function del() {

        // Getting category ID
        $id = filter_input(INPUT_GET, 'id');

        $this->wpdb->delete($this->products_tbl, array('prod_id' => $id));

        wp_redirect(admin_url('admin.php?page=' . $this->page . '&update=deleted'));

        exit;
    }

}
