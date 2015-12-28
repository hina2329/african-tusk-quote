<?php

// Shortcode Class

class atq_shortcode
{

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
    public function __construct()
    {

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

        // Add product shortcode
        add_shortcode('atq', array($this, 'atq_code'));

        // Add quote form shortcode
        add_shortcode('atq-quote-form', array($this, 'atq_quote_form'));

        // Loading plugin resources for front end
        add_action('wp_head', array($this, 'register_frontend_resources'));

        // jQuery for wp_header
        add_action('wp_head', array($this, 'atq_jquery'));

        // Register ajax
        add_action('wp_ajax_atq_load_product', array($this, 'load_product'));

        // Register ajax
        add_action('wp_ajax_nopriv_atq_load_product', array($this, 'load_product'));

        // Sessions
        add_action('init', array($this, 'atq_session_start'), 1);
        add_action('wp_ajax_set_session', array($this, 'atq_session'));
        add_action('wp_ajax_nopriv_set_session', array($this, 'atq_session'));
    }

    // Registering plugin front end resources
    public function register_frontend_resources()
    {
        // Stylesheet
        wp_register_style('atq-style', plugins_url('african-tusk-quote/css/atq-style.css'));
        wp_enqueue_style('atq-style');
    }

    // jQuery for ajax product popup loader
    public function atq_jquery()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                // Load product popup
                $('.product-link').click(function () {
                    var prod_id = $(this).data('product-id');

                    var data = {
                        action: 'atq_load_product',
                        prod_id: prod_id
                    };

                    $('#atq-product-popup, .atq-overlay').fadeIn('fast');
                    $('.atq-loader').show();

                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function (result) {
                        $('.atq-loader').hide();
                        $('.atq-product-content').html(result);
                    });

                    return false;
                });

                // Close product popup
                $('.atq-close').click(function () {
                    $('#atq-product-popup, .atq-overlay').fadeOut('fast');
                    $('.atq-product-content').empty();
                    return false;
                });

                // Add to quote
                $('.fab-color').live('change', function () {
                    var color = $(this).val();
                    $(this).parent().find('.add-to-quote').attr('data-fabric-color', color);
                });

                $('.add-to-quote').live('click', function () {
                    var id = $(this).data('product-id');
                    var fid = $(this).data('fabric-id');
                    var color = $(this).data('fabric-color');

                    $('.atq-cart-success').fadeIn();

                    var data = {
                        action: 'set_session',
                        id: id,
                        fid: fid,
                        color: color
                    };

                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function (result) {
                        $('.atq-cart-success').fadeOut();
                    });

                });


            });
        </script>
        <?php
    }

    public function atq_code()
    {
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
                            <div class="atq-product-title"><strong><?php echo $product->prod_code; ?></strong>
                                - <?php echo $product->prod_name; ?></div>
                            <div class="atq-product-image"><img src="<?php echo $images[0]; ?>" width="100%"
                                                                height="auto"></div>
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
        <div class="atq-cart-success">Product adding to cart... Please wait!</div>
        <div class="atq-overlay"></div>

        <?php
    }

    // Ajax call
    public function load_product()
    {

        $pid = filter_input(INPUT_POST, 'prod_id');
        //Get Products
        $product = $this->wpdb->get_row("SELECT * FROM $this->products_tbl WHERE prod_id = $pid");
        $images = unserialize($product->prod_images);
        ?>
        <div class="atq-left-col">
            <?php
            if ($images) {
                foreach ($images as $image_id => $image) {

                    if ($image_id == 0) {
                        ?>
                        <img src="<?php echo $image; ?>">
                        <?php
                    } else {
                        ?>
                        <img src="<?php echo $image; ?>" width="80px;">
                    <?php } ?>

                    <?php
                }
            }
            ?>
        </div>

        <div class="atq-right-col">
            <h1><?php echo $product->prod_name; ?></h1>
            <h3><?php echo $product->prod_code; ?></h3>
            <?php echo $product->prod_desc; ?>
            <p>
                <strong>Product size:</strong>
                <?php echo $product->prod_size; ?>
            </p>
            <?php
            $fab_combos = $this->wpdb->get_results("SELECT * FROM $this->products_fp_combos_tbl WHERE combo_pid = $pid");
            foreach ($fab_combos as $fab_combo) {
                $fab_code = $fab_combo->combo_code;
                list($code, $fab_suffix) = explode('-', $fab_code);
                $fab_name = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_suffix ='$fab_suffix'");
                ?>
                <div class="atq-fabric">
                    <span style="float: left;"><?php echo $fab_name->fab_name; ?></span>
                    <span style="float: right;">
                        <select class="fab-color">
                            <option>Select a Colour</option>
                            <?php
                            $fab_colors = unserialize($fab_name->fab_colors);
                            foreach ($fab_colors as $fab_color) {
                                ?>
                                <option
                                    value="<?php echo $fab_color['fab_color']; ?>" <?php selected($fab_color['fab_color']); ?>>
                                    <?php echo $fab_color['fab_color']; ?></option>
                                <?php
                            }
                            ?>

                        </select>
                        <button
                            class="add-to-quote"
                            data-product-id="<?php echo $product->prod_id; ?>"
                            data-fabric-id="<?php echo $fab_combo->combo_fid; ?>"
                            data-fabric-color="">
                            Add to Quote
                        </button>
                    </span>
                </div>
                <div class="atq-item-colors">
                    <?php
                    $fab_colors = unserialize($fab_name->fab_colors);
                    foreach ($fab_colors as $fab_id => $fab_color) {
                        ?>
                        <img src="<?php echo $fab_color['fab_img']; ?>" width="30px;">
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

    // Setting session
    public function atq_session_start()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function atq_session()
    {
        $id = filter_input(INPUT_POST, 'id');
        $fid = filter_input(INPUT_POST, 'fid');
        $color = filter_input(INPUT_POST, 'color');

        $_SESSION['atq_cart'][] = [
            'id' => $id,
            'fid' => $fid,
            'color' => $color
        ];

        wp_die();
    }


    // Qoute form
    public function atq_quote_form()
    {
        ?>
        <div id="atq-quote-form">
            <form method="post" action="">

                <?php if ($_SESSION['atq_cart']) { ?>

                    <div class="atq-quote-cart">
                        <h4>Quote Cart:</h4>
                        <table>
                            <thead>
                            <tr>
                                <td width="5%">#</td>
                                <td width="55%">Name</td>
                                <td width="15%">Fabric</td>
                                <td width="15%">Color</td>
                                <td width="10%">Price</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($_SESSION['atq_cart'] as $cart) {
                                $i++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $id = $cart['id'];
                                        $product = $this->wpdb->get_row("SELECT * FROM $this->products_tbl WHERE prod_id = $id");
                                        echo $product->prod_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $fid = $cart['fid'];
                                        $fabric = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_id = $fid");
                                        echo $fabric->fab_name;
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $cart['color']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $fid = $cart['fid'];
                                        $fabric_combo = $this->wpdb->get_row("SELECT * FROM $this->products_fp_combos_tbl WHERE combo_id = $fid");
                                        echo $fabric_combo->combo_price;
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

                <p>
                    <label>First Name <span class="required">*</span></span></label>
                    <input type="text" name="first_name" id="first_name" required>
                </p>
                <p>
                    <label>Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" id="last_name" required>
                </p>
                <p>
                    <label>Company Name <span class="required">*</span></label>
                    <input type="text" name="company_name" id="company_name" required>
                </p>
                <p>
                    <label>Cell Number <span class="required">*</span></label>
                    <input type="text" name="cell_number" id="cell_number" required>
                </p>
                <p>
                    <label>Contact Number <span class="required">*</span></label>
                    <input type="text" name="contact_number" id="contact_number" required>
                </p>
                <p>
                    <label>Email Address <span class="required">*</span></label>
                    <input type="email" name="email_address" id="email_address" required>
                </p>
                <p>
                    <label>Comments - What garments are you looking for:</label>
                    <textarea name="comments" id="comments"></textarea>
                </p>
                <p>
                    <label>What Catalogue would you like?</label>
                    <select multiple="mutiple" name="catalogue" id="catalogue">
                        <option>Core Range Catalogue</option>
                        <option>Corporate & Hotel Core Range</option>
                        <option>Curio Shop Suggested Items</option>
                        <option>Stock Core Range</option>
                        <option>eChef</option>
                    </select>
                </p>
                <p>
                    <label>How did you learn about African Tusk Clothing and eChef initially?</label>
                    <select name="" id="">
                        <option value="">Please Select</option>
                        <option value="Via our website">Via our website.</option>
                        <option value="Responded to an advert">Responded to an advert.</option>
                        <option value="Contacted directly by our sales team">Contacted directly by our sales team.
                        </option>
                    </select>
                </p>

                <p>
                    <input type="submit" value="Submit">
                </p>
            </form>
        </div>

        <?php

    }

}
