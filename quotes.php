<?php

//Quote Class
class quotes extends ATQ {

    public function __construct() {
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

            <h1>Add New Quote</h1>

            <?php
            $quote_step = filter_input(INPUT_GET, 'quote_step');
            if (!isset($quote_step)) {
                ?>
                <form method = "post" action = "<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">

                    <label for = "qoute_staff">Staff Member<span>*</span></label><br>
                    <div class = "form-field">
                        <select name = "qoute_staff" id = "qoute_staff" required>
                            <option value = "">Please select...</option>
                            <?php
                            // Getting staff members list
                            $staffs = $this->wpdb->get_results("SELECT * FROM $this->staff_member_tbl");

                            // Listing all staff members
                            foreach ($staffs as $staff) {
                                echo '<option value="' . $staff->staff_id . '" ';

                                selected($staff->staff_id, $row->staff_id);

                                echo '>' . $staff->staff_name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <p>&nbsp;</p>
                    <h3>Existing Client</h3>
                    <div class="form-field">
                        <select name="qoute_client" id="qoute_client">
                            <option value="">Please select...</option>
                            <?php
                            // Getting clients list
                            $clients = $this->wpdb->get_results("SELECT * FROM $this->clients_tbl");

                            // Listing clients members
                            foreach ($clients as $client) {
                                echo '<option value="' . $client->client_id . '" ';

                                selected($client->client_id, $row->client_id);

                                echo '>' . $client->client_fname . ' ' . $client->client_lname . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <p>&nbsp;</p>
                    <h3>Or New Client?</h3>
                    <div class="form-field">
                        <label for="client_fname">First Name</label><br>
                        <input name="client_fname" id="client_fname" type="text">
                    </div>
                    <div class="form-field">
                        <label for="client_lname">Last Name</label><br>
                        <input name="client_lname" id="client_lname" type="text">
                    </div>
                    <div class="form-field">
                        <label for="client_email">Email</label><br>
                        <input type="text" name="client_email" id="client_email">
                    </div>
                    <div class="form-field">
                        <label for="client_contactno">Contact No</label><br>
                        <input type="text" name="client_contactno" id="client_contactno">
                    </div>
                    <div class="form-field">
                        <label for="client_cellno">Cell No</label><br>
                        <input type="text" name="client_cellno" id="client_cellno">
                    </div>
                    <div class="form-field">
                        <label for="client_companyname">Company Name</label><br>
                        <input type="text" name="client_companyname" id="client_companyname">
                    </div>

                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Quote"></p>

                </form>
                <?php
            } else {
                ?>
                SECOND FORM HERE!!!
                <?php
            }
            ?>
        </div>
        <?php
    }

    // Save Client
    public function save() {

        // Getting submitted data
        $qoute_staff = filter_input(INPUT_POST, 'qoute_staff', FILTER_SANITIZE_NUMBER_INT);
        $qoute_client = filter_input(INPUT_POST, 'qoute_client', FILTER_SANITIZE_NUMBER_INT);


        $client_fname = filter_input(INPUT_POST, 'client_fname', FILTER_SANITIZE_STRING);
        $client_lname = filter_input(INPUT_POST, 'client_lname', FILTER_SANITIZE_STRING);
        $client_email = filter_input(INPUT_POST, 'client_email', FILTER_SANITIZE_STRING);
        $client_contactno = filter_input(INPUT_POST, 'client_contactno', FILTER_SANITIZE_STRING);
        $client_cellno = filter_input(INPUT_POST, 'client_cellno', FILTER_SANITIZE_STRING);
        $client_companyname = filter_input(INPUT_POST, 'client_companyname', FILTER_SANITIZE_STRING);

        if (empty($qoute_client)) {

            // Get new client data
            $client_data = array(
                'client_fname' => $client_fname,
                'client_lname' => $client_lname,
                'client_email' => $client_email,
                'client_contactno' => $client_contactno,
                'client_cellno' => $client_cellno,
                'client_companyname' => $client_companyname
            );

            // Insert new client data
            $this->wpdb->insert($this->clients_tbl, $client_data);

            // Get new client ID
            $qoute_client = $this->wpdb->insert_id;
        }

        // Get new quote data
        $quote_data = array(
            'quote_staff' => $qoute_staff,
            'quote_client' => $qoute_client,
            'quote_subject' => '',
            'quote_comment' => ''
        );

        // Insert new quote data
        $this->wpdb->insert($this->quotes_tbl, $quote_data);

        // Redirect to next quote form
        wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&quote_step=2'));
    }

}
