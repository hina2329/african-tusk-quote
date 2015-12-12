<?php

// Shortcode Class

class atq_shortcode {

    protected $wpdb;
    protected $staff_member_tbl;
    protected $fabrics_tbl;
    protected $clients_tbl;
    protected $categories_tbl;
    protected $categories_relation_tbl;
    protected $products_tbl;
    protected $products_fp_combos_tbl;
    protected $quotes_tbl;
    protected $quote_items_tbl;

    // Constructor
    public function __construct() {

        // Globalizing $wpdb variable
        global $wpdb;
        $this->wpdb = $wpdb;

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

        // Add shortcode
        add_shortcode('atq', array($this, 'atq_code'));

        // Loading plugin resources for front end
        add_action('wp_head', array($this, 'register_frontend_resources'));
        
        // jQuery for wp_header
        add_action('wp_head', array($this, 'atq_jquery'));

        // Register ajax
        add_action('wp_ajax_atq_load_product', array($this, 'load_product'));
        
        // Register ajax
        add_action('wp_ajax_nopriv_atq_load_product', array($this, 'load_product'));
    }

    // Registering plugin front end resources
    public function register_frontend_resources() {
        // Stylesheet
        wp_register_style('atq-style', plugins_url('african-tusk-quote/css/atq-style.css'));
        wp_enqueue_style('atq-style');
    }

    // jQuery for ajax product popup loader
    public function atq_jquery() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                // Load product popup
                $('.product-link').click(function () {
                    var prod_id = $(this).data('product-id');

                    var data = {
                        action : 'atq_load_product',
                        prod_id : prod_id
                    };
                    
                    $('#atq-product-popup, .atq-overlay').fadeIn('fast');
                    $('.atq-loader').show();
                    
                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(result){
                        $('.atq-loader').hide();
                        $('.atq-product-content').html(result);
                    });

                    return false;
                });

                // Close product popup
                $('.atq-close').click(function () {
                    $('#atq-product-popup, .atq-overlay').fadeOut('fast');
                    return false;
                });

            });
</script>        
<?php
}

public function atq_code() {
    ?>

    <div id="atq-products">
        <ul>
            <?php
                // Get products
            $products = $this->wpdb->get_results("SELECT * FROM $this->products_tbl");

                // Display products
            foreach ($products as $product) {
                $images = unserialize($product->prod_images);
                ?>
                <li>
                    <a href="#" data-product-id="<?php echo $product->prod_id; ?>" class="product-link">
                        <div class="atq-product-title"><strong><?php echo $product->prod_code; ?></strong> - <?php echo $product->prod_name; ?></div>
                        <div class="atq-product-image"><img src="<?php echo $images[0]; ?>" width="100%" height="auto"></div>
                    </a>
                </li>
                <?php }
                ?>
            </ul>
        </div>

        <div id="atq-product-popup">
            <div class="atq-product-content-wrap">
                <a href="#" class="atq-close"><img src="<?php echo plugins_url('african-tusk-quote/images/x.png'); ?>"></a>
                <img src="<?php echo plugins_url('african-tusk-quote/images/loading.gif'); ?>" class="atq-loader">
                <div class="atq-product-content">
                </div>
            </div>
        </div>
        <div class="atq-overlay"></div>

        <?php
    }

    // Ajax call
    public function load_product() {

        $pid = filter_input(INPUT_POST, 'prod_id');
        //Get Products
        $product = $this->wpdb->get_row("SELECT * FROM $this->products_tbl WHERE prod_id = $pid"); 
        $images = unserialize($product->prod_images);
        ?>
        <div class="atq-left-col">
         <?php
         if ($images){
            foreach ($images as $image_id => $image) {

                if($image_id == 0){
                    ?>
                    <img src="<?php echo $image;?>" >


                    <?php }
                    else{
                     ?>
                     <img src="<?php echo $image;?>" width="80px;" >

                     <?php   } ?>

                     <?php 

                 }
             } 

             ?>
         </div>

         <div class="atq-right-col">
             <h1><?php echo $product->prod_name;?></h1>
             <h3><?php echo $product->prod_code;?></h3>
             
                <?php echo $product->prod_desc;?>
            
            <p>
                <strong>Product size:</strong>
                <?php echo $product->prod_size;?>
            </p>
            <?php
              $fab_combos = $this->wpdb->get_results("SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $pid");
                    foreach ($fab_combos as $fab_combo) {
                        $fab_code = $fab_combo->combo_code;
                        list($code, $fab_suffix) = explode('-', $fab_code);
                        $fab_name = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_suffix ='$fab_suffix'");

                      
                        ?>  
                        <div class="atq-fabric">
            <span style="float: left;"><?php echo $fab_name->fab_name; ?>
           
                        

            </span>
            <span style="float: right;">
            <select>
                    <option>Select a Colour</option>
            <?php
             $fab_colors = unserialize($fab_name->fab_colors);
                       foreach ( $fab_colors as $fab_color) {
                          
                         
                        
                            ?>
                            <option value="<?php echo $fab_color['fab_color'];?>" <?php selected($fab_color['fab_color']); ?>>
                                <?php echo $fab_color['fab_color'];?></option>>



<?php
}
?>

                </select>
                    <button class="">Add Quotes</button>
                </span>
            </div>
            <div class="atq-item-colors">
            <?php
            $fab_colors = unserialize($fab_name->fab_colors);
                    foreach ( $fab_colors as $fab_id => $fab_color) {

                        ?>

                        <img src="<?php echo $fab_color['fab_img'];?>" width="30px;" >

                        <?php


                    }
                    ?>
            </div>
                        
                        
<?php
                    }

                        
            ?>
            
</div>

           
          
            <?php
            
      

wp_die();
   
}
}

