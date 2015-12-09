<?php

//CVS Product class
class csv_products_import extends ATQ {

    //Controller
    public function __construct() {
        parent:: __construct();
    }

    public function init() {
     ?>
     	<h1><?php echo get_admin_page_title(); ?></h1>
        
        <?php
        $product = filter_input(INPUT_GET, 'product');
        
        if (isset($product) && $product == 'invalid') {
            echo '<div id="message" class="notice notice-error is-dismissible"><p>Invalid file extension. Please use *.csv file.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
        
        if (isset($product) && $product == 'updated') {
            echo '<div id="message" class="updated notice notice-success is-dismissible"><p>Product updated successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        }
        ?>

        <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=update_products'); ?>" enctype="multipart/form-data">
            <p>
                <input type="file" name="update_products">
            </p>
            <p>
                <input type="submit" value="Import Products" class="button button-primary">
            </p>
        </form>

        <p><em><strong>NOTE:</strong> Please upload the file with *.csv extension and data should be separated by comma (,)</em></p>
     <?php
    }
	
	public function update_products() {

        // Get the file
        $file = $_FILES['update_products'];
        
        // Get file extension
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        if ($file_ext == 'csv') {
			// File temporary name
            $file_data = file_get_contents($file['tmp_name']);
            
            // Breaking the data in rows
            $data_arr = explode(PHP_EOL, $file_data);
			
			foreach ($data_arr as $data) {
				$kaboom_data = explode(';', $data);
				
				echo "<pre>";
					print_r($kaboom_data);
				echo "</pre>";
				/*list($prod_id, $prod_name, $prod_desc, $prod_images, $prod_code, $prod_cat, $prod_size, $prod_seller, $prod_sale, $prod_new) = explode(';', $data);
                
                $prod_id_data = array(
                    'prod_id' => $prod_id
                );
                $products_data = array(
                    'prod_name' => $prod_name,
                    'prod_desc' => $prod_desc,
                    'prod_images' => $prod_images,
                    'prod_code' => $prod_code,
                    'prod_cat' => $prod_cat,
                    'prod_size' => $prod_size,
                    'prod_seller' => $prod_seller,
                    'prod_sale' => $prod_sale,
                    'prod_new' => $prod_new
                );
				
				// Update the price
                $this->wpdb->update($this->products_tbl, $products_data, $prod_id_data);*/
			}
			// If all goes well :)
            /*wp_redirect(admin_url('admin.php?page=' . $this->page . '&product=updated'));
            exit;
            
        } else {
            // If not a valid file extension (CSV)
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&product=invalid'));
            exit;*/
		}
		
	}
	
}
