<?php

//Quote Class
class quotes extends ATQ {

	public function __construct(){
		parent:: __construct();


	}
	// Iniating main method to display quotes
    public function init() {
        ?>

        <h1><?php echo get_admin_page_title(); ?> <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form'); ?>" class="page-title-action">Add New Quote</a></h1>

        <?php $this->notify('Quote'); ?>

        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr>
                    <th width="10%">ID</th>
                    <th width="20%">Subject</th>
                    <th width="15%">Staff Member</th>
                    <th width="15%">Client</th>
                    <th width="15%">Date</th>
                    <th width="15%">Status</th>
                    <th width="10%" class="actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">


</tbody>

        </table>

        <?php
    }
    // Add new quote form
    public function form() {

        ?>
        <div class="col-left">
        
         <h1> Add New Quote</h1>
            <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">

                    <label for="qoute_staff">Staff Member<span>*</span></label><br>
                    <div class="form-field">
                    <select name="qoute_staff" id="qoute_staff"  required>
                        <?php

                        // Getting staff members list
                        $staffs= $this->wpdb->get_results("SELECT * FROM $this->staff_member_tbl");

                        // Listing all staff members
                        foreach ($staffs as $staff) {
                            echo '<option value="' . $staff->staff_id . '" ';
                            
                                selected($staff->staff_id, $row->staff_id);
                            
                            echo '>' . $staff->staff_name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <label for="qoute_client">Clients<span>*</span></label><br>
                <div class="form-field">
                    <select name="qoute_client" id="qoute_client"  required>
                        <?php

                        // Getting clients list
                        $clients= $this->wpdb->get_results("SELECT * FROM $this->clients_tbl");

                        // Listing clients members
                        foreach ($clients as $client) {
                            echo '<option value="' . $client->client_id . '" ';
                            
                                selected($client->client_id, $row->client_id);
                            
                            echo '>' . $client->client_fname . '</option>';
                        }
                        ?>
                    </select>
                </div>

             <h1>Or New Client</h1>
                <div class="form-field">
                    <label for="client_fname"> FirstName <span>*</span></label><br>
                    <input name="client_fname" id="client_fname" type="text"  required>
                </div>
                <div class="form-field">
                    <label for="client_lname">Last Name <span>*</span></label><br>
                    <input name="client_lname" id="client_lname" type="text"  required>
                </div>
                <div class="form-field">
                    <label for="client_email">Email <span>*</span></label><br>
                    <input type="text" name="client_email" id="client_email"  required>
                </div>
                <div class="form-field">
                    <label for="client_contactno">Contact No<span>*</span></label><br>
                    <input type="text" name="client_contactno" id="client_contactno"  required>
                </div>
                <div class="form-field">
                    <label for="client_cellno">Cell No <span>*</span></label><br>
                    <input type="text" name="client_cellno" id="client_cellno"  required>
                </div>
                <div class="form-field">
                    <label for="client_companyname">Company Name<span>*</span></label><br>
                    <input type="text" name="client_companyname" id="client_companyname"  required>
                </div>






                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Creat Quote"></p>



                
                </form>
                </div>
        <?php
    }
}
?>