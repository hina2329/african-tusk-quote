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
                    <th width="12%">ID</th>
                    <th width="25%">Subject</th>
                    <th width="15%">Staff Member</th>
                    <th width="15%">Client</th>
                    <th width="15%">Date</th>
                    <th width="8%">Status</th>
                    <th width="10%" class="actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">

                <?php
                // Get all the quotes
                $quotes = $this->wpdb->get_results("SELECT qt.*, cl.*, mb.* FROM $this->quotes_tbl AS qt "
                        . "INNER JOIN $this->clients_tbl AS cl ON qt.quote_client = cl.client_id "
                        . "INNER JOIN $this->staff_member_tbl AS mb ON qt.quote_staff = mb.staff_id");

                if ($quotes) {

                    foreach ($quotes as $row) {

                        // Quote submitted date
                        $date = date_create($row->quote_date)
                        ?>
                        <tr>
                            <td>
                                AT-<?php echo $row->quote_id . '-' . date_format($date, 'Y-m-d'); ?>
                            </td>
                            <td><?php echo $row->quote_subject; ?></td>
                            <td><?php echo $row->staff_name; ?></td>
                            <td><?php echo $row->client_fname . ' ' . $row->client_lname; ?></td>
                            <td><?php echo date_format($date, 'Y-m-d'); ?></td>
                            <td>
                                <?php
                                if ($row->quote_status == 0) {
                                    echo '<span class="quote_status quote_new">New</span>';
                                } else {
                                    echo '<span class="quote_status quote_sent">Sent</span>';
                                }
                                ?>
                            </td>
                            <td class="actions">
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $row->quote_id); ?>" class="dashicons-before dashicons-edit" title="Edit"></a>
                                <a href="<?php echo admin_url('admin.php?page=' . $this->page . '&action=del&id=' . $row->quote_id); ?>" class="dashicons-before dashicons-trash" title="Delete" onclick="return confirm('Are you sure you want to delete this?');"></a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" style="text-align: center;"><strong>No Records Found</strong></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>

        </table>

        <?php
    }

    // Add new quote form
    public function form() {

        // Get ID
        $id = filter_input(INPUT_GET, 'id');

        $this->notify('Quote');

        echo isset($id) ? '<a href="#" class="send-quote button-primary">SEND QUOTE</a>' : '';
        ?>

        <h1><?php echo isset($id) ? 'Edit Quote' : 'Add New Quote'; ?></h1>

        <?php
        if (isset($id)) {

            // Get quote data
            $quote = $this->wpdb->get_row("SELECT * FROM $this->quotes_tbl WHERE quote_id = $id");

            // Get client data
            $client = $this->wpdb->get_row("SELECT * FROM $this->clients_tbl WHERE client_id = $quote->quote_client");
            ?>

            <div class="col-left">
                <fieldset>
                    <Legend>Client</legend>
                    <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save&update=client'); ?>">
                        <input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
                        <input type="hidden" name="quote_client" value="<?php echo $quote->quote_client; ?>">
                        <div class="form-field">
                            <label for="client_fname">First Name</label><br>
                            <input name="client_fname" id="client_fname" type="text" value="<?php echo $client->client_fname; ?>">
                        </div>
                        <div class="form-field">
                            <label for="client_lname">Last Name</label><br>
                            <input name="client_lname" id="client_lname" type="text" value="<?php echo $client->client_lname; ?>">
                        </div>
                        <div class="form-field">
                            <label for="client_email">Email</label><br>
                            <input type="text" name="client_email" id="client_email" value="<?php echo $client->client_email; ?>">
                        </div>
                        <div class="form-field">
                            <label for="client_contactno">Contact No</label><br>
                            <input type="text" name="client_contactno" id="client_contactno" value="<?php echo $client->client_contactno; ?>">
                        </div>
                        <div class="form-field">
                            <label for="client_cellno">Cell No</label><br>
                            <input type="text" name="client_cellno" id="client_cellno" value="<?php echo $client->client_cellno; ?>">
                        </div>
                        <div class="form-field">
                            <label for="client_companyname">Company Name</label><br>
                            <input type="text" name="client_companyname" id="client_companyname" value="<?php echo $client->client_companyname; ?>">
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
                    </form>
                </fieldset>
                <fieldset>
                    <Legend>Member</legend>
                    <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save&update=member'); ?>">
                        <input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
                        <input type="hidden" name="quote_staff" value="<?php echo $quote->quote_staff; ?>">
                        <div class="form-field">
                            <label for="quote_subject">Member</label><br>
                            <select name="quote_staff_new" id="quote_staff" required>
                                <?php
                                // Getting staff members list
                                $staffs = $this->wpdb->get_results("SELECT * FROM $this->staff_member_tbl");

                                // Listing all staff members
                                foreach ($staffs as $staff) {
                                    echo '<option value="' . $staff->staff_id . '" ';

                                    selected($staff->staff_id, $quote->quote_staff);

                                    echo '>' . $staff->staff_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
                    </form>
                </fieldset>
            </div>
            <div class="col-right">
                <fieldset>
                    <Legend>Subject</legend>
                    <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save&update=subject'); ?>">
                        <input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
                        <div class="form-field">
                            <label for="quote_subject">Subject</label><br>
                            <input name="quote_subject" id="quote_subject" type="text" value="<?php echo $quote->quote_subject; ?>">
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
                    </form>
                </fieldset>
                <fieldset>
                    <Legend>Comments</legend>
                    <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save&update=comments'); ?>">
                        <input type="hidden" name="quote_id" value="<?php echo $quote->quote_id; ?>">
                        <div class="form-field">
                            <?php
                            // WordPress WYSIWYG Editor
                            wp_editor($quote->quote_comment, 'quote_comment', array('textarea_name' => 'quote_comment'));
                            ?>
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update"></p>
                    </form>
                </fieldset>
            </div>

            <fieldset class="quote-products">
                <legend>Quote Items</legend>
                <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save&update=quote'); ?>">
                    <div class="quote-item-search">
                        Simply specify the first 3 characthers of a product code, e.g. AT1. It will then give you options, select one and click "Add Product". <input type="text" class="large-text"><button class="button">Add Product</button>
                        <ul>
                            <?php
                            $products = $this->wpdb->get_results("SELECT * FROM $this->products_tbl");
                            foreach ($products as $product) {
                                ?>
                                <li><a href="#"><?php echo $product->prod_code . ' / ' . $product->prod_name; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                            <tr>
                                <th width="20%">Picture</th>
                                <th width="25%">Description</th>
                                <th width="15%">Fabric Type</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Unit Price</th>
                                <th width="10%">Sub Total</th>
                                <th width="10%" class="actions">Actions</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th style="text-align: right;" colspan="7">Total R <input type="text" class="small-text"></th>
                            </tr>
                        </tfoot>

                        <tbody id="the-list">
                        </tbody>

                    </table>
                </form>
                <p class="submit" style="text-align: right;"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Quote"></p>
            </fieldset>
            <?php
        } else {
            ?>
            <div class="col-left">
                <form method="post" action="<?php echo admin_url('admin.php?page=' . $this->page . '&action=save'); ?>">
                    <label for="quote_staff">Staff Member<span>*</span></label><br>
                    <div class="form-field">
                        <select name="quote_staff" id = "quote_staff" required>
                            <option value="">Please select...</option>
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
                        <select name="quote_client" id="quote_client">
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
            </div>
            <?php
        }
    }

    // Save Client
    public function save() {

        // Get param of updating
        $update = filter_input(INPUT_GET, 'update');

        // Get quote ID
        $quote_id = filter_input(INPUT_POST, 'quote_id');

        // Get new client data
        $client_fname = filter_input(INPUT_POST, 'client_fname', FILTER_SANITIZE_STRING);
        $client_lname = filter_input(INPUT_POST, 'client_lname', FILTER_SANITIZE_STRING);
        $client_email = filter_input(INPUT_POST, 'client_email', FILTER_SANITIZE_STRING);
        $client_contactno = filter_input(INPUT_POST, 'client_contactno', FILTER_SANITIZE_STRING);
        $client_cellno = filter_input(INPUT_POST, 'client_cellno', FILTER_SANITIZE_STRING);
        $client_companyname = filter_input(INPUT_POST, 'client_companyname', FILTER_SANITIZE_STRING);

        // Get member / updated member and exisitng client IDs
        $quote_staff = filter_input(INPUT_POST, 'quote_staff', FILTER_SANITIZE_NUMBER_INT);
        $quote_staff_new = filter_input(INPUT_POST, 'quote_staff_new', FILTER_SANITIZE_NUMBER_INT);
        $quote_client = filter_input(INPUT_POST, 'quote_client', FILTER_SANITIZE_NUMBER_INT);

        // Get quote subject
        $quote_subject = filter_input(INPUT_POST, 'quote_subject', FILTER_SANITIZE_STRING);

        // Get quote comment
        $quote_comment = filter_input(INPUT_POST, 'quote_comment');

        // Get client data
        $client_data = array(
            'client_fname' => $client_fname,
            'client_lname' => $client_lname,
            'client_email' => $client_email,
            'client_contactno' => $client_contactno,
            'client_cellno' => $client_cellno,
            'client_companyname' => $client_companyname
        );


        if (isset($update) && $update == 'client') {
            // If update client

            $client_id = array(
                'client_id' => $quote_client
            );

            // Update client data
            $this->wpdb->update($this->clients_tbl, $client_data, $client_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&&update=updated'));
        } else if (isset($update) && $update == 'member') {
            // If update staff
            // 
            // Get updated staff data
            $staff_data = array(
                'quote_staff' => $quote_staff_new
            );

            $staff_id = array(
                'quote_staff' => $quote_staff,
                'quote_id' => $quote_id
            );

            // Update staff data
            $this->wpdb->update($this->quotes_tbl, $staff_data, $staff_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated'));
        } else if (isset($update) && $update == 'subject') {
            // If update subject
            // 
            // Get subject data
            $subject_data = array(
                'quote_subject' => $quote_subject
            );

            $subject_id = array(
                'quote_id' => $quote_id
            );

            // Update subject
            $this->wpdb->update($this->quotes_tbl, $subject_data, $subject_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated'));
        } else if (isset($update) && $update == 'comments') {
            // If update commnents
            // 
            // Get comments data
            $comment_data = array(
                'quote_comment' => $quote_comment
            );

            $comment_id = array(
                'quote_id' => $quote_id
            );

            // Update subject
            $this->wpdb->update($this->quotes_tbl, $comment_data, $comment_id);

            wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $quote_id . '&update=updated'));
        } else {

            if (empty($quote_client)) {

                // Insert new client data
                $this->wpdb->insert($this->clients_tbl, $client_data);

                // Get new client ID
                $quote_client = $this->wpdb->insert_id;
            }

            // Get new quote data
            $quote_data = array(
                'quote_staff' => $quote_staff,
                'quote_client' => $quote_client,
                'quote_subject' => '',
                'quote_comment' => ''
            );

            // Insert new quote data
            $this->wpdb->insert($this->quotes_tbl, $quote_data);

            // Get new quote ID
            $quote_id = $this->wpdb->insert_id;

            // Redirect to next quote form
            wp_redirect(admin_url('admin.php?page=' . $this->page . '&action=form&id=' . $quote_id));
        }
    }

}
