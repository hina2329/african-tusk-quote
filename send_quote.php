<?php

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
        $this->staff_member_tbl = $this->wpdb->prefix . 'atq_staff_member';


	}

	public function html_templete() {
	?>
  
    


<table cellpadding="0" cellspacing="0" width="624" style="font-family: arial; margin:0 auto; font-size:14px;">
	<tr>
		<td colspan="2" style="padding-bottom: 5px;"><img src="images/header.jpg"></td>
	</tr>
	<tr>
		<td style="background:#e3e2d6; padding: 10px;" colspan="2"> 
			<p>We pride ourselves on our high standards of service excellence and top quality garments which we have been manufacturing and supplying to the hotel and lodge industry for past 24 years! Thank you for
				requesting a quote.</p></td>
			</tr>
			<tr>
				<td with="157" style="background:#ffff; border:1px solid #ccc; padding: 5px; text-align:center;">
					<img src="images/img1.jpg"  width="153">
				</td>
				<td style="background:#f5f3e6; vertical-align:top; padding:10px;">
					<h2 style="font-size:15px">AT006B / LDS 3/4 SLEEVE REGULAR SHIRT</h2>
					<li style="font-size:11px">3/4 SLEEVE WITH V CUFF</li>
					<li style="font-size:11px">REGULAR LENGTH</li>
					<li style="font-size:11px">FRONT AND BACK DARTS</li>
					<li style="font-size:11px">SIDE SLITS</li>
					<li style="font-size:11px">OPTIONAL POCKETS</li>
					<p>Fabric: Polycotton Basic</p>
					<p>Colour: Cerise </p>
					<p>Quantity: 1</p>
					<div style="float:left">Unit Price: R198.47</div>
					<div style="float:right">
						<h3>R198.47</h3>
					</div>
				</td>
			</tr>
			<tr>
				<td with="157" style="background:#ffff; border:1px solid #ccc; padding: 5px; text-align:center;">
					<img src="images/img2.jpg"  width="153">
				</td>
				<td style="background:#f5f3e6; vertical-align:top; padding:10px;">
					<h2 style="font-size:15px">AT006B / LDS 3/4 SLEEVE REGULAR SHIRT</h2>
					<li style="font-size:11px">3/4 SLEEVE WITH V CUFF</li>
					<li style="font-size:11px">REGULAR LENGTH</li>
					<li style="font-size:11px">FRONT AND BACK DARTS</li>
					<li style="font-size:11px">SIDE SLITS</li>
					<li style="font-size:11px">OPTIONAL POCKETS</li>
					<p>Fabric: Polycotton Basic</p>
					<p>Colour: Cerise </p>
					<p>Quantity: 1</p>
					<div style="float:left">Unit Price: R198.47</div>
					<div style="float:right">
						<h3>R198.47</h3>
					</div>
				</td>
			</tr>

			<tr>
				<td align="right" colspan="3" style="border:1px solid #ccc; padding:10px;">
					<h3>Total   R 442.28</h3>
				</td>
			</tr>
			<tr>
				<td  colspan="3" style="border:1px solid #ccc; padding:10px;">
					<h3>Comments:</h3>
					<p>
						This is a test
						<br>
						<br>
						Tasha 
					</p>
					<p>
						<strong>Many thanks for your request for a quotation. Please take note of the following.</strong>
					</p>
					<p>
						<p>Our delivery time is between 3-6 weeks from the time you place your order, style dependant and once proof of payment has been received. We require full prepayment on all orders. All pricing excludes VAT and freight and is valid for 30 days. Orders are sent without insurance and at the clients risk. Please request insurance if you require.</p>
						<p>Stock AS, AX and EP coded items carry no minimum order requirement. Manufactured AT and EC coded garments carry a minimum order quantity of 10 units per style ordered across the sizes (eg 5 x 32″/3 x 34″/2 x 36″). There is a ‘rise-per-size’ cost of 10% for larger sizes onto prices as quoted.</p>
						<p>Should you require less than 10 units on the AT or EC garments, there is a minimum order surcharge of R30.00 per garment on all garments except jackets which is R50.00 per garment. There is a surcharge of R130 on shoe orders of less than 10 pairs.</p>
						<p>Please note that all shoe quotes and delivery dates are subject to availability of stock and price confirmation at the time of placing the order and a sample must please be ordered before placing your main order as we have a non-returns policy on all shoes. </p>
						<ul>
							<li>No returns on correctly supplied items</li>
							<li>Sizing advice is offered by our sales staff but the ultimate responsibility remains with the client</li>
							<li>We can send you samples to try on to confirm your sizing – please request these</li>
							<li>Conti suits: order by jacket size and the pants are matched 2 sizes smaller automatically – please ask us for samples to fir for sizing – unfortunately no sizing returns</li>
							<li>Shoes: please order sizes you wish to try on as no swaps or returns will be accepted</li>
						</ul>
						<p>Assuring you of our prompt, personal and professional attention at all times.</p>
						<table cellspacing="0" cellpadding="0" width="100%" style="font-size:14px;">
							<tr>
								<td width="22%">
									<h5 style="margin:0;">
										Agent Delails:
									</h5>
									Full Name:<br>
									Landline Number:<br>
									Cell Number:<br>
									Email Address:

								</td>
								<td width="22%">
								<br>
									Dean Byram <br>
									+27 (0) 44 343 1021<br>
									083 261 4947<br>
									<a href="#">dean@africantusk.co.za</a><br>
								</td>
								<td width="22%">
									<h5 style="margin:0;">
										Contact Details:
									</h5>
									Full Name:<br>
									Company Name:<br>
									Contact Number:<br>
									Email Address:
								</td>
								<td width="22%">
								<br>
									Dean<br>
									ATC Test<br>
									<br>
									<a href="#">dean@africantusk.co.za</a><br>
								</td>
							</tr>
						</table>
					<?php
				

  }
}

$send_quote = new send_quote;
$send_quote ->html_templete();

	