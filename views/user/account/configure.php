<?php 
    $cdn_url = unserialize($this->get_site_meta('cdn_url'));
?>
<?php echo $this->create_form( $this->set_url('user/account_save'),'crm-submit' ); ?>    
    <div class="col-lg-12">
		<h3>Site Settings</h3>
		<hr />
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" class="form-control" id="title" placeholder="Enter Site Title" value="<?php echo $this->get_site_meta('title'); ?>">
        </div>

        <div class="form-group">
            <label for="suppresion_server">Suppresion Server</label>
            <input type="text" name="suppression_server" class="form-control" id="suppression_server" placeholder="Enter Suppression Server URL" value="<?php echo $this->get_site_meta('suppression_server'); ?>">
        </div>
		
		<div class="form-group">
            <label for="url">Site Url</label>
            <input type="text" class="form-control" name="url" id="url" placeholder="Enter Site URL" value="<?php echo $this->get_site_meta('url'); ?>">
        </div>
		
		<h3>Cap Limit Settings</h3>
		<hr />
        <div class="form-group">
            <label for="suppresion_server">Cap Limit Start Time</label>
            <input type="text" name="cap_start_time" class="form-control" id="" placeholder="24:00:00" value="<?php echo $this->get_site_meta('cap_start_time'); ?>">
        </div>
		
        <div class="form-group">
            <label for="suppresion_server">Cap Limit End Time</label>
            <input type="text" name="cap_end_time" class="form-control" id="" placeholder="24:00:00" value="<?php echo $this->get_site_meta('cap_end_time'); ?>">
        </div>
		
		
		<h3>Survey Settings</h3>
		<hr />
        
         <div class="form-group">
            <label for="manual_opt">Enable Manual Option</label>
            <select name="manual_opt" id="manual_opt" class="form-control" >
                <option <?php echo $this->check_variables($this->get_site_meta('manual_opt'),'1','selected="selected"') ?> value="1">on</option>
                <option <?php echo $this->check_variables($this->get_site_meta('manual_opt'),'0','selected="selected"') ?> value="0">off</option>
            </select>
        </div>
        <div class="form-group">
            <label for="cdn_url">Dialer's Url</label>
            <div class="cdn-url-wrapper">
                <div class="cdn-main">
                    <?php if($cdn_url){ ?>
                        <?php foreach ($cdn_url as $key => $value) { ?>
                            <p>
                                <input type="text" class="form-control" name="cdn_url[]" value="<?php echo $value; ?>">
                            </p>
                        <?php } ?>
                    <?php }else{ ?> 
                        <p>
                            <input type="text" class="form-control" name="cdn_url[]" placeholder="http://192.168.0.1/cdn-crm" value="">
                        </p>
                    <?php } ?>
                </div>
            </div>
            <p><span class="btn btn-default btn-small add-new-cdn-url" title="Add New Dialer"><i class="fa fa-plus"></i></span></p>
            <p class="description">Just leave the textbox blank if you want to remove url.</p>
        </div>
        
        <div class="form-group">
            <label for="legal_1">Opening</label>
            <textarea type="text" class="form-control" name="legal_1" id="legal_1" ><?php echo $this->get_site_meta('legal_1'); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="legal_2">Legal</label>
            <textarea type="text" class="form-control" name="legal_2" id="legal_2" ><?php echo $this->get_site_meta('legal_2'); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="confirm_1">Opt In</label>
            <textarea type="text" class="form-control" name="confirm_1" id="confirm_1" ><?php echo $this->get_site_meta('confirm_1'); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="closing">Closing</label>
            <textarea type="text" class="form-control" name="closing" id="closing" ><?php echo $this->get_site_meta('closing'); ?></textarea>
        </div>
       
    </div>
    
    <div class="col-lg-12">
        <div class="form-group col-lg-3">
            <label for="opening_text_option">Enable Opening Text</label>
            <select name="opening_text_option" id="opening_text_option" class="form-control" >
                <option <?php echo $this->check_variables($this->get_site_meta('opening_text_option'),'1','selected="selected"') ?> value="1">on</option>
                <option <?php echo $this->check_variables($this->get_site_meta('opening_text_option'),'0','selected="selected"') ?> value="0">off</option>
            </select>
        </div>
        
        <div class="form-group col-lg-3">
            <label for="legal_text_option">Enable Legal Text</label>
            <select name="legal_text_option" id="legal_text_option" class="form-control" >
                <option <?php echo $this->check_variables($this->get_site_meta('legal_text_option'),'1','selected="selected"') ?> value="1">on</option>
                <option <?php echo $this->check_variables($this->get_site_meta('legal_text_option'),'0','selected="selected"') ?> value="0">off</option>
            </select>
        </div>
        
        <div class="form-group col-lg-3">
            <label for="optin_text_option">Enable Opt-in Text</label>
             <select name="optin_text_option" id="optin_text_option" class="form-control" >
                <option <?php echo $this->check_variables($this->get_site_meta('optin_text_option'),'1','selected="selected"') ?> value="1">on</option>
                <option <?php echo $this->check_variables($this->get_site_meta('optin_text_option'),'0','selected="selected"') ?> value="0">off</option>
            </select>
        </div>
        
        <div class="form-group col-lg-3">
            <label for="closing_text_option">Enable Closing Text</label>
            <select name="closing_text_option" id="closing_text_option" class="form-control" >
                <option <?php echo $this->check_variables($this->get_site_meta('closing_text_option'),'1','selected="selected"') ?> value="1">on</option>
                <option <?php echo $this->check_variables($this->get_site_meta('closing_text_option'),'0','selected="selected"') ?> value="0">off</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-info btn-large"><i class="fa fa-save"></i> Save Changes</button>
    </div>
<?php echo $this->end_form(); ?>
<hidden class="cdn-url-main" style="display:none;">
    <div class="cdn-child-wrapper input-group">
        <div class="input-group">
          <input type="text"  class="form-control" name="cdn_url[]" placeholder="http://192.168.0.1/cdn-crm" value="" />
          <span class="remove btn-small btn btn-danger input-group-addon remove-text" title="Remove"><i class="fa fa-times"></i></span>
        </div>
    </div>
</hidden>
<script>
jQuery(document).ready(function($){
    $('.add-new-cdn-url').click(function(){
        // get parent text html
        var cdn_text = $('.cdn-url-main').html();
        console.log(cdn_text)
        // add the text to the wrapper
        $('.cdn-url-wrapper').append(cdn_text);
    });
    // button remove click event
    $(document).on('click','.remove-text', function(){
        // get the parent div
        $(this).parent().parent().fadeOut(function(){
            // remove the text box and the button
            $(this).remove();
        });
    })
})
</script>