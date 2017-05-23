<div class="agent-signin-modal">
	<form action="<?php echo $this->set_url('survey/set_agent'); ?>" method="POST">
		<h3>Howdy Agent!</h3>
		<p>
			Please select your name to get started in the survey.
		</p>
		<div class="input-group">
		<span class="input-group-addon">Agent</span>
		  <select class="form-control" name="agent_id" required="required">
			<option></option>
			<option value="" disabled>--------------------------------------------------------</option>
			<?php foreach($this->agents as $agent){ ?>
				<option value="<?php echo $agent->agent_id; ?>|<?php echo $agent->first_name. ' '.$agent->last_name; ?>"><?php echo $agent->first_name. ' '.$agent->last_name; ?></option>
			<?php } ?>
		  </select>
		</div>
		<div class="input-group">
			<button class="btn btn-info">Confirm!</button>
		</div>
		
		<?php if(isset($_GET['message'])) { ?>
			<div class="alert alert-error error_message"><?php echo base64_decode($_GET['message']); ?></div>
		<?php } ?>
	</form>
</div>