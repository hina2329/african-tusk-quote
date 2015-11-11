<?php
/*
  Plugin Name: African Tusk Quote
  Plugin URI: https://www.freelancer.com/u/hina2329.html
  Description: Custom Product Quote System for Wordpress. Use <code>[atq]</code> shortcode to display the system on front end.
  Version: 1.0
  Author: Hina Farid
  Author URI: https://www.freelancer.com/u/hina2329.html
*/


// Main Pugin Class
  class ATQ {

  	function __construct() {
  		add_action('admin_menu', array($this,'atq_menu'));
  	}

  	function atq_menu() {
  		add_menu_page('African Tusk Qoute', 'African Tusk Qoute', 'manage_options', 'atq_main', array($this, 'atq_main'), 'dashicons-format-aside');
  		add_submenu_page('atq_main', 'Products', 'Products', 'manage_options', 'products', array($this,'atq_main'));
  		add_submenu_page('atq_main',' Fabrics', 'Fabrics', 'manage_options', 'fabrics', array($this,'atq_main'));
  		add_submenu_page('atq_main', 'Categories', 'Categories', 'manage_options', 'categories',array($this,'atq_main'));
  		add_submenu_page('atq_main', 'Quotes', 'Quotes', 'manage_options', 'quotes', array($this,'atq_main'));
  		add_submenu_page('atq_main', 'Clients', 'Clients', 'manage_options', 'clients', array($this,'atq_main'));
  		add_submenu_page('atq_main', 'Staff Member', 'Staff Member', 'manage_options', 'staff_member', array($this,'atq_main'));

  	}

  	function atq_main() {
  		echo 'Misbah&acute;s laptop is super fast like a ferrari :p';
  	}


  }

  new ATQ;

  ?>