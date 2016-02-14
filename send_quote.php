<?php

require_once '../../../wp-config.php';

// Send Quote Class
class send_quote {

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
	function __construct() {
		
		
		//Globalizing $wpdb variable
        global $wpdb;
        $this->wpdb = $wpdb;
        //table names
        $this->staff_member_tbl = $this->wpdb->prefix . 'atq_staff_member';
        $this->fabrics_tbl = $this->wpdb->prefix . 'atq_fabrics';
        $this->clients_tbl = $this->wpdb->prefix . 'atq_clients';
        $this->categories_tbl = $this->wpdb->prefix . 'atq_categories';
        $this->categories_relation_tbl = $this->wpdb->prefix . 'atq_categories_relation';
        $this->products_tbl = $this->wpdb->prefix . 'atq_products';
        $this->products_fp_combos_tbl = $this->wpdb->prefix . 'atq_products_fp_combos';
        $this->quotes_tbl = $this->wpdb->prefix . 'atq_quotes';
        $this->quote_items_tbl = $this->wpdb->prefix . 'atq_quote_items';

		$id = filter_input(INPUT_GET, 'id');

		$this->wpdb->update($this->quotes_tbl, array('quote_status' => 1), array('quote_id' => $id));


		//$headers = 'From: My Name <hina.6637@gmail.com>' . "\r\n";
        wp_mail( 'hina.6637@gmail.com', 'Get The Subject FROM QUOTE', 'Message' );

        //echo 'MAIL SENT';
        wp_redirect( admin_url( 'admin.php?page=' . quotes . '&update=sent' ) );





	}

	public function html_templete() {

  // Getting plugin settings
        $this->setting = (object) get_option('atq_settings');


	?>
  
    


<table cellpadding="0" cellspacing="0" width="624" style="font-family: arial; margin:0 auto; font-size:14px;">
	<tr>
		<td colspan="2" style="padding-bottom: 5px;"><img src="images/header.jpg"></td>
	</tr>
	<tr>
		<td style="background:#e3e2d6; padding: 10px;" colspan="2"> 
			<p><?php echo $this->setting->atq_header; ?></p></td>
			</tr>
			<?php
			
		    $id = filter_input(INPUT_GET, 'id'); 
			if(isset($id)){
			$status_data = array(
				'quote_status' => '1'
			);

			$status_id = array(
				'quote_id' => $id
			);

			// Update status
			$this->wpdb->update( $this->quotes_tbl, $status_data, $status_id );
		}
		   

                // Get products
				$products = $this->wpdb->get_results("SELECT * FROM $this->quote_items_tbl WHERE item_qid = $id");
			    $quote = $this->wpdb->get_row("SELECT * FROM $this->quotes_tbl WHERE quote_id = $id");
			    $client = $this->wpdb->get_row("SELECT * FROM $this->clients_tbl WHERE client_id= $quote->quote_client");
			    $staff = $this->wpdb->get_row("SELECT * FROM $this->staff_member_tbl WHERE staff_id= $quote->quote_staff");

              foreach ($products as $product) {
              	$fab = $this->wpdb->get_row("SELECT * FROM $this->fabrics_tbl WHERE fab_id = $product->item_fab");
              	$images = unserialize($product->item_images);
              
				?>
			<tr>
				<td with="157" style="background:#ffff; border:1px solid #ccc; padding: 5px; text-align:center;">
				<?php
            if ($images) {
                foreach ($images as $image_id => $image) {

                    if ($image_id == 0) {
                        ?>
                        <img src="<?php echo $image; ?>"  width="153">
                        <?php
                    }
                }
            }
                    ?>
					
				</td>
				
				<td style="background:#f5f3e6; vertical-align:top; padding:10px;">
					<h2 style="font-size:15px"><?php echo $product->item_name; ?></h2>
					<?php echo $product->item_desc; ?>
					<p>Fabric:<?php echo $fab->fab_name; ?></p>
					<p>Colour:<?php echo $product->item_fab_color;?> </p>
					<p>Quantity:<?php echo $product->item_qty; ?></p>
					<div style="float:left">Unit Price:R<?php echo $product->item_price; ?></div>
					<div style="float:right">
					<?php 
					$total = $product->item_qty * $product->item_price;
					$grand_total+= $total;
					?>
						<h3>R<?php echo $total; ?></h3>
					</div>
				</td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td align="right" colspan="3" style="border:1px solid #ccc; padding:10px;">
					<h3>Total   R<?php echo $grand_total; ?></h3>
				</td>
			</tr>
			<tr>
				<td  colspan="3" style="border:1px solid #ccc; padding:10px;">
					<h3>Comments:</h3>
					<p>
						<?php echo $quote->quote_comment; ?>
						<br>

						<br>
						<?php echo $client->client_fname; ?>
						
					</p>
					<p>
					<?php echo $this->setting->atq_footer; ?>
						</p>
						<table cellspacing="0" cellpadding="0" width="100%" style="font-size:14px;">
							<tr>
								<td width="22%">
									<h5 style="margin:0;">
										Staff Delails:
									</h5>
									Full Name: <br>
									Cell Number:<br>
									Email Address:

								</td>
								<td width="22%">
								<br>
									<?php echo $staff->staff_name; ?> <br>
									<?php echo $staff->staff_contactno; ?><br>
									<a href="mailto:<?php echo $staff->staff_email; ?>"><?php echo $staff->staff_email; ?></a><br>
								</td>
								<td width="22%"></td>
								<td width="22%"></td>
								
							</tr>
						</table>
					<?php
				

  }
}

$send = new send_quote;
//$send->html_templete();


	