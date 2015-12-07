<?php

/*
  Plugin Name: African Tusk Quote
  Plugin URI: https://www.freelancer.com/u/hina2329.html
  Description: Custom Product Quote System for Wordpress. Use <code>[atq]</code> shortcode to display the system on front end.
  Version: 1.0
  Author: Hina Farid
  Author URI: https://www.freelancer.com/u/hina2329.html
 */

/**
 * Main Class
 */
class ATQ {

    protected $wpdb;
    protected $page;
    protected $staff_member_tbl;
    protected $fabrics_tbl;
    protected $clients_tbl;
    protected $categories_tbl;
    protected $categories_relation_tbl;
    protected $products_tbl;
    protected $products_fp_combos_tbl;
    protected $quotes_tbl;
    protected $quote_items_tbl;
    protected $ajax_handler;

    public function __construct() {

        // Globalizing $wpdb variable
        global $wpdb;
        $this->wpdb = $wpdb;

        // User HTTP request for class
        $this->page = filter_input(INPUT_GET, 'page');

        // Table names
        $this->staff_member_tbl = $this->wpdb->prefix . 'atq_staff_member';
        $this->fabrics_tbl = $this->wpdb->prefix . 'atq_fabrics';
        $this->clients_tbl = $this->wpdb->prefix . 'atq_clients';
        $this->categories_tbl = $this->wpdb->prefix . 'atq_categories';
        $this->categories_relation_tbl = $this->wpdb->prefix . 'atq_categories_relation';
        $this->products_tbl = $this->wpdb->prefix . 'atq_products';
        $this->products_fp_combos_tbl = $this->wpdb->prefix . 'atq_products_fp_combos';
        $this->quotes_tbl = $this->wpdb->prefix . 'atq_quotes';
        $this->quote_items_tbl = $this->wpdb->prefix . 'atq_quote_items';

        // Installing new tables in the database
        add_action('plugins_loaded', array($this, 'install_tables'));

        // Adding the main page
        add_action('admin_menu', array($this, 'atq_menu'));

        // Loading plugin resources for admin
        add_action('admin_head', array($this, 'register_admin_resources'));

        // Loading plugin resources for front end
        add_action('wp_head', array($this, 'register_frontend_resources'));

        // Add heading action
        add_action('wp_ajax_add_heading', array($this, 'add_heading'));

        // Add delete item action
        add_action('wp_ajax_del_item', array($this, 'del_quote_item'));

        // Add separator action
        add_action('wp_ajax_add_sep', array($this, 'add_separator'));

        // Add product item action
        add_action('wp_ajax_add_prod', array($this, 'add_product'));

        // Allow redirection
        ob_start();
    }

    // WP Menu
    public function atq_menu() {
        add_menu_page('African Tusk Qoute', 'African Tusk Qoute', 'edit_pages', 'quotes', array($this, 'atq_main'), 'dashicons-format-aside');
        add_submenu_page('quotes', 'Quotes', 'Quotes', 'edit_pages', 'quotes', array($this, 'atq_main'));
        add_submenu_page('quotes', 'Products', 'Products', 'edit_pages', 'products', array($this, 'atq_main'));
        add_submenu_page('quotes', 'Categories', 'Categories', 'edit_pages', 'categories', array($this, 'atq_main'));
        add_submenu_page('quotes', 'Fabrics', 'Fabrics', 'edit_pages', 'fabrics', array($this, 'atq_main'));
        add_submenu_page('quotes', 'Clients', 'Clients', 'edit_pages', 'clients', array($this, 'atq_main'));
        add_submenu_page('quotes', 'Staff Member', 'Staff Member', 'edit_pages', 'staff_member', array($this, 'atq_main'));
        \
        add_submenu_page('quotes', 'CSV Prices Update', 'CSV Prices Update', 'edit_pages', 'csv_prices_update', array($this, 'atq_main'));
        add_submenu_page('quotes', 'CSV Products Import', 'CSV Products Import', 'edit_pages', 'csv_products_import', array($this, 'atq_main'));
        add_submenu_page('quotes', 'CSV Fabric Price Combos Import', 'CSV Fabric Price Combos Import', 'edit_pages', 'csv_fabric_price_combos_import', array($this, 'atq_main'));
    }

    // Main Page
    public function atq_main() {

        echo '<div class="wrap" id="atq-wrap">';

        if ($this->page == 'atq_main') {
            
        } else {
            //Requestig Appropriate object
            require_once $this->page . '.php';
            $obj = new $this->page;

            // User HTTP request for method
            $action = filter_input(INPUT_GET, 'action');

            if (!isset($action)) {
                $action = 'init';
            }

            $obj->$action();
        }
        echo '</div>';
    }

    // Registering plugin admin resources
    public function register_admin_resources() {

        // Admin Stylesheet
        wp_register_style('atq-admin-style', plugins_url('african-tusk-quote/css/atq-admin-style.css'));
        wp_enqueue_style('atq-admin-style');

        // Admin JavaScript
        wp_enqueue_media();
        wp_register_script('atq-script-admin', plugins_url('african-tusk-quote/js/atq-script-admin.js'));
        wp_enqueue_script('atq-script-admin');
        wp_localize_script('atq-script-admin', 'b29_lib', array(
            'title' => 'Upload an Image',
            'button' => 'Use this image',
                )
        );
        wp_enqueue_script('jquery-ui-sortable');
    }

    // Registering plugin front end resources
    public function register_frontend_resources() {
        // Stylesheet
        wp_register_style('atq-style', plugins_url('african-tusk-quote/css/atq-style.css'));
        wp_enqueue_style('atq-style');
    }

    // Notifications
    public function notify($module) {
        $msg = filter_input(INPUT_GET, 'update');
        $settings = filter_input(INPUT_GET, 'settings-updated');
        if (isset($msg)) {
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>' . $module . ' ' . $msg . ' successfully!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        } else if (isset($settings)) {
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>' . $module . ' updated successfully!</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
    }

    // Add heading in quote items listing
    public function add_heading() {

        // Get quote id
        $quote_id = filter_input(INPUT_POST, 'quote_id');
        $heading = filter_input(INPUT_POST, 'add_heading');

        $item_heading = array(
            'item_qid' => $quote_id,
            'heading' => $heading
        );

        $this->wpdb->insert($this->quote_items_tbl, $item_heading);

        $item_id = $this->wpdb->insert_id;

        echo '<tr>';
        echo '<td colspan="6"><h2>' . $heading . '</h2></td>';
        echo '<td class="actions">';
        echo '<a href="#" data-item-id="' . $item_id . '" data-code-id="' . $quote_id . '"  class="dashicons-before dashicons-trash del-item-row" title="Delete" onclick="return confirm(Are you sure you want to delete this?");"></a>';
        echo '</td>';
        echo '</tr>';

        wp_die();
    }

    // Delete quote item
    public function del_quote_item() {

        $qid = filter_input(INPUT_POST, 'qid');
        $iid = filter_input(INPUT_POST, 'iid');

        // If delete product with in the quote
        $item_data = array(
            'item_id' => $iid
        );

        $this->wpdb->delete($this->quote_items_tbl, $item_data);

        wp_die();
    }

    //separator
    public function add_separator() {
        $sid = filter_input(INPUT_POST, 'sid');
        $item_sep = array(
            'item_qid' => $sid,
            'sep' => 1,
        );

        $this->wpdb->insert($this->quote_items_tbl, $item_sep);
        $item_id = $this->wpdb->insert_id;


        echo '<tr>';
        echo '<td colspan="6"><hr style="height: 3px; background: #666;"></td>';
        echo '<td class="actions">';
        echo '<a href="#" data-item-id="' . $item_id . '" data-quote-id="' . $sid . '" class="dashicons-before dashicons-trash del-item-row" title="Delete" onclick="return confirm(Are you sure you want to delete this?);"></a>';
        echo '</td>';
        echo '</tr>';

        wp_die();
    }

    public function add_product() {
        
        // Get quote id &product id
        $quote_id = filter_input(INPUT_POST, 'qid');
        $prod_id = filter_input(INPUT_POST, 'pid');
        
        // Get product data of db
        $product = $this->wpdb->get_row("SELECT * FROM $this->products_tbl WHERE prod_id = $prod_id");
        $fp_combos = $this->wpdb->get_row("SELECT * FROM $this->products_fp_combo_tbl WHERE combo_pid = $prod_id");


        // Save existing product data into wp_atq_quote_items table
        $product_data = array(
            'item_qid' => $quote_id,
            'item_pid' => $prod_id,
            'item_code' => $product->prod_code,
            'item_images' => $product->prod_images,
            'item_name' => $product->prod_name,
            'item_desc' => $product->prod_desc,
            'item_cat' => $product->prod_cat
        );

        $this->wpdb->insert($this->quote_items_tbl, $product_data);
        $item_id = $this->wpdb->insert_id;

        $images = unserialize($product->prod_images);
        $textarea_id = 'desc' . $product->prod_id;
        echo '<tr>';
        echo '<td>';

        if ($images) {
            foreach ($images as $image) {
                echo '<img src="' . $image . '" alt="" width="auto" height="150"><br>';
            }
        }

        echo '<input type="text" name="item_name" value=" ' . $product->prod_name . '">';
        echo '</td>';
        echo '<td>';

        // WordPress WYSIWYG Editor
        wp_editor($product->prod_desc, $textarea_id, array('textarea_name' => 'text'));
//        \_WP_Editors::enqueue_scripts();
//        print_footer_scripts();
//        \_WP_Editors::editor_js();

        echo '</td>';
        echo '<td>';


        echo '<select name="fab_type" id="fab_type">';
        echo '<option value="">Please Select...</option>';

        //getting fabric suffix
        $prod_fps = $this->wpdb->get_results("SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $prod_id");
        foreach ($prod_fps as $prod_fp) {

            $combo_code = $prod_fp->combo_code;
            //breaking rows into $prod_code & $fab_suffix
            list($prod_code, $fab_suffix) = explode('-', $combo_code);
            $fab_suffix;

            //getting fabric names             
            $fab_type = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_suffix = '$fab_suffix'");

            echo '<option value="' . $fab_type->fab_name . '" ';

            selected($fab_type->fab_name);

            echo '>' . $fab_type->fab_name . '</option>';
        }



        echo '</select>';
        echo '</td>';

        $item = $this->wpdb->get_row("SELECT * FROM $this->quote_items_tbl WHERE item_id = $item_id");

        echo '<td>';
        echo '<input type="text" name="item_qty" value="' . $item->item_qty . '" class="x-small-text item-qty">';
        echo '</td>';
        echo '<td>';
        echo 'R <input type="text" name="item_qty" value="" class="x-small-text unit-price">';
        echo '</td>';
        echo '<td>';
        echo 'R <input type="text" name="item_qty" value="" class="x-small-text sub-total">';
        echo '</td>';
        echo '<td class="actions">';
        echo '<a href="#" data-item-id="' . $item_id . '" data-quote-id="' . $qoute_id . '" class="dashicons-before dashicons-trash del-item-row" title="Delete" onclick="return confirm(Are you sure you want to delete this?);"></a>';
        echo '</td>';
        echo '</tr>';
        wp_die();
    }

    // Tables queries for database
    public function install_tables() {

        // Queries to create tables
        $fabrics_table = "CREATE TABLE $this->fabrics_tbl (
        fab_id INT(5) NOT NULL AUTO_INCREMENT,
        fab_name VARCHAR(100) NOT NULL,
        fab_suffix VARCHAR(100) NULL,
        fab_colors LONGTEXT NULL,
        PRIMARY KEY (fab_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';
        ";

        $staff_member_table = "CREATE TABLE $this->staff_member_tbl (
        staff_id INT(5) NOT NULL AUTO_INCREMENT,
        staff_name VARCHAR(100) NOT NULL,
        staff_email VARCHAR(100) NOT NULL,
        staff_position VARCHAR(100) NOT NULL,
        staff_contactno VARCHAR(100) NOT NULL,
        PRIMARY KEY (staff_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';
        ";

        $clients_table = "CREATE TABLE $this->clients_tbl (
        client_id INT(5) NOT NULL AUTO_INCREMENT,
        client_fname VARCHAR(100) NOT NULL,
        client_lname VARCHAR(100) NOT NULL,
        client_email VARCHAR(100) NOT NULL,
        client_email_2 VARCHAR(100) NULL,
        client_contactno VARCHAR(100) NOT NULL,
        client_cellno VARCHAR(100) NOT NULL,
        client_companyname VARCHAR(100) NOT NULL,
        PRIMARY KEY (client_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';
        ";

        $categories_table = "CREATE TABLE $this->categories_tbl (
        cat_id INT(5) NOT NULL AUTO_INCREMENT,
        cat_name VARCHAR(100) NOT NULL,
        cat_desc VARCHAR(500) NULL,
        cat_image VARCHAR(255) NULL,
        cat_parent INT(3) DEFAULT 0,
        cat_order INT(2) DEFAULT 0,
        PRIMARY KEY (cat_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';
        ";

        $categories_rel_table = "CREATE TABLE $this->categories_relation_tbl (
        prod_id INT(5) DEFAULT 0,
        cat_id INT(5) DEFAULT 0,
        PRIMARY KEY (prod_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';
        ";

        $products_table = "CREATE TABLE $this->products_tbl(
        prod_id INT(5) NOT NULL AUTO_INCREMENT,
        prod_name VARCHAR(100) NOT NULL,
        prod_desc LONGTEXT NULL,
        prod_images LONGTEXT NULL,
        prod_code VARCHAR(100) NOT NULL,  
        prod_cat LONGTEXT NOT NULL,
        prod_size VARCHAR(100) NULL,
        prod_seller TINYINT DEFAULT 0,
        prod_sale TINYINT DEFAULT 0,
        prod_new TINYINT DEFAULT 0,
        PRIMARY KEY(prod_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

        $products_fp_combos_table = "CREATE TABLE $this->products_fp_combos_tbl(
        combo_id INT(9) NOT NULL AUTO_INCREMENT,
        combo_pid INT(9) NOT NULL,
        combo_code VARCHAR(50) NOT NULL,
        combo_price VARCHAR(50) NOT NULL,
        PRIMARY KEY(combo_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

        $quotes_table = "CREATE TABLE $this->quotes_tbl(
        quote_id INT(5) NOT NULL AUTO_INCREMENT,
        quote_staff VARCHAR(100) NOT NULL,
        quote_client VARCHAR(100) NOT NULL,
        quote_subject VARCHAR(100) NOT NULL,
        quote_comment LONGTEXT NOT NULL,
        quote_date TIMESTAMP NOT NULL,
        quote_status INT(1) DEFAULT 0,
        PRIMARY KEY(quote_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

        $quote_items_table = "CREATE TABLE $this->quote_items_tbl(
        item_id INT(5) NOT NULL AUTO_INCREMENT,
        item_qid VARCHAR(100) NULL,
        item_pid VARCHAR(100) NULL,
        item_code VARCHAR(100) NULL,
        item_images LONGTEXT NULL,
        item_name VARCHAR(100) NULL,
        item_desc LONGTEXT NULL,
        item_cat LONGTEXT NULL,
        item_qty INT(3) DEFAULT 1,
        item_order INT(2) DEFAULT 0,
        heading VARCHAR(255) NULL,
        sep TINYINT DEFAULT 0,
        PRIMARY KEY(item_id)
        ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";


        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($fabrics_table);
        dbDelta($staff_member_table);
        dbDelta($clients_table);
        dbDelta($categories_table);
        dbDelta($categories_rel_table);
        dbDelta($products_table);
        dbDelta($products_fp_combos_table);
        dbDelta($quotes_table);
        dbDelta($quote_items_table);
    }

}

new ATQ;
