<?php

// settings Class
class settings extends ATQ {

	public function __construct() {
		parent:: __construct();


	}
    
    // Iniating main method to display settings
	public function init() {

		?>
		
	   
	   <h1><?php echo get_admin_page_title(); ?></h1>

        <?php $this->notify('Settnigs'); ?>

        <div class="col-left">
            <form method="post" action="options.php">
                <?php settings_fields('atq_settings'); ?>
	   <div class="form-field">
	   <label for="atq_header">Header</label>
	   <textarea name="atq_settings[atq_header]" id="atq_header" value="<?php echo $this->setting->atq_header; ?>" ></textarea>
	   </div>

	   <div class="form-field">
	   <label for="atq_footer"> Footer</label>
	   <textarea name="atq_settings[atq_footer]" id="atq_footer" value="<?php echo $this->setting->atq_footer; ?>" ></textarea>
	   	</div>

	   	<label for="atq_mail"> Email</label>
	   <input type="text" name="atq_settings[atq_mail]" id="atq_mail" value="<?php echo $this->setting->atq_mail; ?>">
	   	</div>

	   	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings"></p>
	   
	   </form> 
	   </div>
	 



   <?php

	}

}