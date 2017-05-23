    <input type="hidden" name="id" class="form-control"  value="<?php echo $this->applications->application->ag_id; ?>"  placeholder="" required="required" />
        <div class="col-lg-3">
            <div class="input-group">
              <span class="input-group-addon">Date</span>
              <input type="text" class="form-control" name="interview_started_date"  value="<?php echo $this->applications->application->interview_started_date; ?>"  />
            </div>
            <div class="input-group">
              <span class="input-group-addon">Time</span>
              <input type="text" class="form-control" name="interview_started_time" value="<?php echo $this->applications->application->interview_started_time; ?>"  />
            </div>
            <div class="input-group">
              <span class="input-group-addon">URN Original</span>
              <input type="text" name="urn_original" value="<?php echo $this->applications->application->details->urn_original; ?>" class="form-control" placeholder="" />
            </div>                                                              
            <div class="input-group">                                           
              <span class="input-group-addon">Post Codes</span>                 
              <input type="text" name="post_code" value="<?php echo $this->applications->application->details->post_code; ?>" class="form-control post-code" placeholder="" required="required" />
            </div>                                                              
            <div class="input-group">                                           
              <span class="input-group-addon">County</span>                    
              <input type="text" name="country" value="<?php echo $this->applications->application->details->country; ?>" class="form-control" placeholder="" required="required" />
            </div>
        </div>
        <div class="col-lg-5">
            <div class="input-group">                                           
              <span class="input-group-addon">Phone Number</span>                 
              <input type="number" name="phone" value="<?php echo $this->applications->application->phone; ?>" class="form-control customer-phone" placeholder="" required="required" />
            </div>      
            <div class="input-group">
              <span class="input-group-addon">Title</span>
              <select class="form-control" name="title">
                <option <?php echo $this->set_selected('Ms',$this->applications->application->details->title) ?>>Ms</option>
                <option <?php echo $this->set_selected('Mr',$this->applications->application->details->title) ?>>Mr</option>
                <option <?php echo $this->set_selected('Mrs',$this->applications->application->details->title) ?>>Mrs</option>
              </select>
            </div>
            <div class="input-group">
              <span class="input-group-addon">First Name</span>
              <input type="text" name="first_name" value="<?php echo $this->applications->application->details->first_name; ?>" class="form-control first-name" required="required" />
            </div>
            <div class="input-group">
              <span class="input-group-addon">Last Name</span>
              <input type="text" name="last_name" value="<?php echo $this->applications->application->details->last_name; ?>"  class="form-control last-name" placeholder="" required="required" />
            </div>                                                
            <div class="input-group">                                           
              <span class="input-group-addon">Agent</span>
              <select class="form-control" name="agent_id" required="required">
                <option></option>
                <option value="" disabled>--------------------------------------------------------</option>
                <?php foreach($this->agents as $agent){ ?>
                    <option <?php echo $this->set_selected($agent->agent_id,$this->applications->application->agent_id) ?> value="<?php echo $agent->agent_id; ?>"><?php echo $agent->first_name. ' '.$agent->last_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="input-group">
              <span class="input-group-addon">Address 1</span>
              <input type="text" name="address1" class="form-control address" value="<?php echo $this->applications->application->details->address1; ?>" required="required" />
            </div>
            <div class="input-group">
              <span class="input-group-addon">Address 2</span>
              <input type="text" name="address2" class="form-control"  value="<?php echo $this->applications->application->details->address2; ?>" required="required" />
            </div>
            <div class="input-group">
              <span class="input-group-addon">Address 3</span>
              <input type="text" name="address3" class="form-control"  value="<?php echo $this->applications->application->details->address3; ?>" placeholder="" required="required" />
            </div>                                                              
            <div class="input-group">                                           
              <span class="input-group-addon">Town</span>                 
              <input type="text" name="town" class="form-control" placeholder="" value="<?php echo $this->applications->application->details->town; ?>" required="required" />
            </div>
            <div class="input-group">                                           
              <span class="input-group-addon">Gender</span>                 
              <input type="text" name="gender" class="form-control"  value="<?php echo (isset($this->applications->application->details->gender))?$this->applications->application->details->gender:"Undefined"; ?>"  placeholder="" required="required" />
            </div>       
        </div>
		
		<!-- addtional -->
		<div class="col-lg-3">
			 <div class="input-group">
              <span class="input-group-addon">Email</span>
              <input type="text" name="email" class="form-control email" value="<?php echo (isset($this->applications->application->details->email)) ? $this->applications->application->details->email: '';  ?>" required="required" />
            </div>
		</div>
		
		<div class="col-lg-5">
			 <div class="input-group">
              <span class="input-group-addon">Company</span>
              <input type="text" name="company" class="form-control company" value="<?php echo (isset($this->applications->application->details->company)) ? $this->applications->application->details->company: '';  ?>" required="required" />
            </div>
		</div>
		
		<div class="col-lg-4">
			 <div class="input-group">
              <span class="input-group-addon">Position</span>
              <input type="text" name="position" class="form-control position" value="<?php echo (isset($this->applications->application->details->position)) ? $this->applications->application->details->position: '';  ?>" required="required" />
            </div>
		</div>
		<!-- addtional -->
		<div class="col-lg-3">
			<div class="input-group">
			  <span class="input-group-addon">Website</span>
			  <input type="text" name="website" class="form-control website" value="<?php echo (isset($this->applications->application->details->website)) ? $this->applications->application->details->website: '';  ?>" required="required" />
			</div>
		</div>
		<div class="col-lg-3">
			<div class="input-group">
			  <span class="input-group-addon">
				<input type="checkbox" <?php echo $this->set_checked("on", $this->applications->application->details->legal); ?> id="legal" name="legal" required="required"/>
			  </span>
			  <label class="form-control" for="legal">Legal</label>
			</div><!-- /input-group -->
		</div>      
		<div class="col-lg-2">
			<div class="input-group">
			  <span class="input-group-addon">
				<input type="checkbox" id="confirm" name="confirm" required="required" <?php echo $this->set_checked("on",$this->applications->application->details->confirm); ?> />
			  </span>
			  <label class="form-control" for="confirm">confirm</label>
			</div><!-- /input-group -->             
		</div>
		<div class="col-lg-4">
			<div class="input-group">
			  <span class="input-group-addon">
				Status
			  </span>
			  <select name="status" class="form-control">
				<option value="0" <?php echo $this->set_selected('0',$this->applications->application->status) ?>>Unfinished</option>
				<option value="1" <?php echo $this->set_selected('1',$this->applications->application->status) ?>>Finished</option>
			</select>
			</div><!-- /input-group -->
		</div><!-- /input-group --> 