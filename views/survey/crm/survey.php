<script>
	var dialer = [];
	<?php $dialers = unserialize($this->get_cdn_url()); ?>
	<?php foreach ($dialers as $key => $value) { ?>
	dialer.push('<?php echo $value; ?>')
	<?php } ?>
</script>
<div class="crm_survey_container">
	<?php echo $this->create_form( $this->set_url('survey/insert'),'crm-submit crm-first-part' ); ?>
		<div class="col-lg-3">
			<div class="input-group">
			  <span class="input-group-addon">Date</span>
			  <input type="text" class="form-control" name="interview_started_date"  value="<?php echo $this->current_date; ?>" readonly="readonly" />
			</div>
			<div class="input-group">
			  <span class="input-group-addon">Time</span>
			  <input type="text" class="form-control" name="interview_started_time" value="<?php echo $this->current_time; ?>" readonly="readonly" />
			</div>
			<div class="input-group">
			  <span class="input-group-addon">URN Original</span>
			  <input type="text" name="urn_original" class="form-control" placeholder="" />
			</div>                                                              
			<div class="input-group">                                           
			  <span class="input-group-addon">Post Codes</span>                 
			  <input type="text" name="post_code" class="form-control post-code" placeholder="" required="required" />
			</div>                                                              
			<div class="input-group">                                           
			  <span class="input-group-addon">Country</span>                    
			  <input type="text" name="country" class="form-control" placeholder="" required="required" />
			</div>
		</div>
		<div class="col-lg-5">
			<div class="input-group">                                           
			  <span class="input-group-addon">Phone Number</span>                 
			  <input type="number" id="phone-number" name="phone" class="form-control customer-phone" placeholder="" required="required" />
			  <span data-validate="<?php echo $this->set_url('survey/validate') ?>" class="input-group-addon find-customer"><i class="fa fa-search"></i></span>
			</div>      
			<div class="input-group">
			  <span class="input-group-addon">Title</span>
			  <select class="form-control" name="title"><option>Ms</option><option>Mr</option><option>Mrs</option></select>
			</div>
			<div class="input-group">
			  <span class="input-group-addon">First Name</span>
			  <input type="text" name="first_name" class="form-control first-name" required="required" />
			</div>
			<div class="input-group">
			  <span class="input-group-addon">Last Name</span>
			  <input type="text" name="last_name" class="form-control last-name" placeholder="" required="required" />
			</div>                                                
			<div class="input-group">                                           
			  <span class="input-group-addon">Agent</span>
			  <!-- <select class="form-control" name="agent_id" required="required">
				<option></option>
				<option value="" disabled></option>
				<?php foreach($this->agents as $agent){ ?>
					<option value="<?php echo $agent->agent_id; ?>"><?php echo $agent->first_name. ' '.$agent->last_name; ?></option>
				<?php } ?>
			  </select> -->
			  <input type="hidden" name="agent_id" value="<?php echo (isset($this->agent_id)) ? $this->agent_id: 0; ?>" required="required"/>
			  <input type="text" readonly="readonly" class="form-control" disabled="disabled" value="<?php echo (isset($this->agent_id)) ? $this->agent_name: 'Agent Not Set'; ?>">
			</div>
		</div>
		<div class="col-lg-4">     
			<div class="input-group">
			  <span class="input-group-addon">Email</span>
			  <input type="text" name="email" class="form-control email"  required="required" />
			</div>
			<div class="input-group">
			  <span class="input-group-addon">Website</span>
			  <input type="text" name="website" class="form-control website"  required="required" />
			</div>	
			<div class="input-group">
			  <span class="input-group-addon">Company Name</span>
			  <input type="text" name="company" class="form-control company"  required="required" />
			</div>		
			<div class="input-group">                                           
			  <span class="input-group-addon">Title/Position</span>                 
			  <input type="text" name="position" class="form-control position" placeholder="" required="required" />
			</div>
			<div class="input-group">                                           
			  <span class="input-group-addon">Gender</span>                 
			  <input type="text" name="gender" class="form-control" placeholder="" required="required" />
			</div>       
		</div>
		<div class="col-lg-8">
			<div class="input-group">
			  <span class="input-group-addon">Address 1</span>
			  <input type="text" name="address1" class="form-control address"  required="required" />
			</div>
			<div class="input-group">
			  <span class="input-group-addon">Address 2</span>
			  <input type="text" name="address2" class="form-control"  required="required" />
			</div>
		</div>
		<div class="col-lg-4">
			<div class="input-group">
			  <span class="input-group-addon">Address 3</span>
			  <input type="text" name="address3" class="form-control" placeholder="" required="required" />
			</div>     
			<div class="input-group">                                           
			  <span class="input-group-addon">Town</span>                 
			  <input type="text" name="town" class="form-control" placeholder="" required="required" />
			</div>      
		</div>
		<div class="col-lg-12">
            <?php if ( $this->get_site_meta('opening_text_option')) { ?>
                <div class="crm-opening-message">
                    <?php
                    $legal_1 = $this->get_site_meta('legal_1');
                    if($legal_1){ 
                    ?>
                    <p><?php echo $legal_1; ?></p>
                    <?php }else{ ?>
                    <p>Hi, this is _________. I'm calling from <span class="county">(County)</span> Research Agency. This is not a sales call. We are conducting a short survey in your area asking only simple questions answerable by Yes or No. It will only take a few minutes. Shall we start? Can we begin? Is this Okay? </p>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if ( $this->get_site_meta('legal_text_option')) { ?>
                <div class="crm-details">
                  <?php
                    $legal_2 = $this->get_site_meta('legal_2');
                    if($legal_2){ 
                    ?>
                    <p><?php echo $legal_2; ?></p>
                    <?php }else{ ?>
                    <p>Let me just verify your house number is "<i><span class="address-text">_________</span></i>" and your post code is "<i><span class="post-code-text">_________</span></i>" (if incorrect get the correct information) </p>
                    <p>And just to confirm I have got your last name as "<i><span class="last-name-text">_________</span></i>" and your first name as "<span class="first-name-text">_________</span>" is this correct? (If non lead name get the correct information)</p>
                    <?php } ?>
                </div>
                <div class="col-lg-2 col-lg-offset-10">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <input type="checkbox" id="legal" name="legal" required="required"/>
                      </span>
                      <label class="form-control" for="legal">Legal</label>
                    </div><!-- /input-group -->
                </div>
            <?php } ?>
		</div>
		<div class="col-lg-12">
            <?php 
            if ($this->get_site_meta('optin_text_option')) { ?>
            <div class="crm-opening-message crm-y-auto">
                <?php
                    $confirm_1 = $this->get_site_meta('confirm_1');
                    if($confirm_1){ 
                ?>
                    <p><?php echo $confirm_1; ?></p>
                <?php }else{ ?>
                    Before we go to the first questions I need to let you know that based on your answers <span class="county">(County)</span> Research Agency and its trusted partners name on this survey may like to contact you in the future either by phone or mail. Shall we start? Can we begin? Is this Okay? 
                <?php } ?>
            </div>
			<p class="col-lg-12"><i>(There MUST be a positive expression of interest to continue with the survey)</i></p>
			<div class="col-lg-2 col-lg-offset-10">
				<div class="input-group">
				  <span class="input-group-addon">
					<input type="checkbox" id="confirm" name="confirm" required="required"/>
				  </span>
				  <label class="form-control" for="confirm">confirm</label>
				</div><!-- /input-group -->
			</div>
             <?php } ?>
		</div>
		<div class="col-lg-12">
			<p class="text-muted">Survey questions.</p>
			<?php require( dirname(__FILE__).'/questions.php' ); ?>
		</div>
		<div class="col-lg-3 col-lg-offset-9">
			<input type="hidden" name="redirect" value="<?php echo $this->set_url('survey/next'); ?>"  required="required" />
			<button href="#" class="insert-application btn btn-survey btn-primary pull-right btn-xs" role="button">Next</button>
			<a href="#" data-url="<?php echo $this->set_url('survey/disposition')?>" class="btn btn-survey btn-default pull-right btn-xs btn-disposition" role="button">Incomplete</a>
		</div>
	<?php echo $this->end_form(); ?>
</div>