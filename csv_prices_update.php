<?php

// CSV update class
class csv_prices_update extends ATQ {

    // Contructor
    public function __construct() {
        parent::__construct();
    }

    // Iniating main method
    public function init() {
        ?>
        <h1><?php echo get_admin_page_title(); ?></h1>
        
        <?php
        $price = filter_input(INPUT_GET, 'price');
        
        if (isset($price) && $price == 'invalid') {
            echo '<div id="message" class="notice notice-error is-dismissible"><p>Invalid file extension. Please use *.csv file.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
        
        if (isset($price) && $price == 'updated') {
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>Prices updated successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
        ?>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=update_prices'); ?>" enctype="multipart/form-data">
            <p>
                <input type="file" name="update_prices">
            </p>
            <p>
                <input type="submit" value="Update Prices" class="button button-primary">
            </p>
        </form>

        <p><em><strong>NOTE:</strong> Please upload the file with *.csv extension and data should be separated by comma (,)</em></p>

        <?php
    }

    public function update_prices() {

        // Get the file
        $file = $_FILES['update_prices'];
        
        // Get file extension
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        if ($file_ext == 'csv') {

            // File temporary name
            $file_data = file_get_contents($file['tmp_name']);
            
            // Breaking the data in rows
            $data_arr = explode(PHP_EOL, $file_data);
            
            foreach ($data_arr as $data) {
                // Breaking rows into variables $code, $price
                list($code, $price) = explode(',', $data);
                
                $price_data = array(
                    'combo_price' => $price
                );
                
                $code_data = array(
                    'combo_code' => $code
                );
                
                // Update the price
                $this->wpdb->update($this->products_fp_combos_tbl, $price_data, $code_data);
            }
            
            // If all goes well :)
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&price=updated'));
            exit;
            
        } else {
            // If not a valid file extension (CSV)
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&price=invalid'));
            exit;
        }
    }

}
