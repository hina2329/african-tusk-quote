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
	protected $fabric_tbl;
	protected $clients_tbl;
	protected $categories_tbl;
	protected $products_tbl;

	function __construct() {

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
		$this->products_tbl = $this->wpdb->prefix . 'atq_products';

		// Installing new tables in the database
		add_action('plugins_loaded', array($this, 'install_tables'));

		// Adding the main page
		add_action('admin_menu', array($this, 'atq_menu'));

		// Loading plugin resources for admin
		add_action('admin_head', array($this, 'register_admin_resources'));
	}

	// WP Menu
	function atq_menu() {
		add_menu_page('African Tusk Qoute', 'African Tusk Qoute', 'manage_options', 'atq_main', array($this, 'atq_main'), 'dashicons-format-aside');
		add_submenu_page('atq_main', 'Products', 'Products', 'manage_options', 'products', array($this, 'atq_main'));
		add_submenu_page('atq_main', 'Categories', 'Categories', 'manage_options', 'categories', array($this, 'atq_main'));
		add_submenu_page('atq_main', ' Fabrics', 'Fabrics', 'manage_options', 'fabrics', array($this, 'atq_main'));
		add_submenu_page('atq_main', 'Quotes', 'Quotes', 'manage_options', 'quotes', array($this, 'atq_main'));
		add_submenu_page('atq_main', 'Clients', 'Clients', 'manage_options', 'clients', array($this, 'atq_main'));
		add_submenu_page('atq_main', 'Staff Member', 'Staff Member', 'manage_options', 'staff_member', array($this, 'atq_main'));
	}

	// Main Page
	function atq_main() {

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
	function register_admin_resources() {
		// Admin Stylesheet
		wp_register_style('atq-admin-style', plugins_url('african-tusk-quote/css/atq-admin-style.css'));
		wp_enqueue_style('atq-admin-style');
		wp_enqueue_style('thickbox');

		// Admin JavaScript
		wp_register_script('atq-script-admin', plugins_url('african-tusk-quote/js/atq-script-admin.js'));
		wp_enqueue_script('atq-script-admin');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
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

	// Tables queries for database
	public function install_tables() {

		// Queries to create tables
		$fabrics_table = "CREATE TABLE $this->fabrics_tbl (
            fab_id INT(5) NOT NULL AUTO_INCREMENT,
            fab_name VARCHAR(100) NOT NULL,
            fab_suffix VARCHAR(100) NOT NULL,
            fab_colors VARCHAR(500) NOT NULL,
            PRIMARY KEY (fab_id)
            ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

		$staff_member_table = "CREATE TABLE $this->staff_member_tbl (
            staff_id INT(5) NOT NULL AUTO_INCREMENT,
            staff_name VARCHAR(100) NOT NULL,
            staff_email VARCHAR(100) NOT NULL,
            staff_position VARCHAR(100) NOT NULL,
            staff_contactno VARCHAR(100) NOT NULL,
            PRIMARY KEY (staff_id)
            ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

		$clients_table = "CREATE TABLE $this->clients_tbl (
            client_id INT(5) NOT NULL AUTO_INCREMENT,
            client_fname VARCHAR(100) NOT NULL,
            client_lname VARCHAR(100) NOT NULL,
            client_email VARCHAR(100) NOT NULL,
            client_contactno VARCHAR(100) NOT NULL,
            client_cellno VARCHAR(100) NOT NULL,
            client_companyname VARCHAR(100) NOT NULL,
            PRIMARY KEY (client_id)
            ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

		$categories_table = "CREATE TABLE $this->categories_tbl (
            cat_id INT(5) NOT NULL AUTO_INCREMENT,
            cat_name VARCHAR(100) NOT NULL,
            PRIMARY KEY (cat_id)
            ) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";
		$products_table = "CREATE TABLE $this->products_tbl(
         	prod_id INT(5) NOT NULL AUTO_INCREMENT,
         	prod_unique_id VARCHAR(100) NOT NULL,
         	prod_name VARCHAR(100) NOT NULL,
         	prod_desc VARCHAR(100) NOT NULL,
         	prod_price VARCHAR(100) NOT NULL,
         	prod_image VARCHAR(100) NOT NULL,
         	prod_code VARCHAR(100) NOT NULL,
         	prod_cat VARCHAR(100) NOT NULL,
         	prod_size VARCHAR(100) NOT NULL,
         	prod_fab VARCHAR(100) NOT NULL,
         	prod_sale VARCHAR(50) NOT NULL,
         	prod_featured VARCHAR(50) NOT NULL,
         	PRIMARY KEY(prod_id)
         	) COLLATE = 'utf8_general_ci', ENGINE = 'InnoDB';";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($fabrics_table);
		dbDelta($staff_member_table);
		dbDelta($clients_table);
		dbDelta($categories_table);
		dbDelta($products_table);
	}

}

new ATQ;
