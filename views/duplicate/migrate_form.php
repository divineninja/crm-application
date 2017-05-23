<form action="<?php echo $this->set_url('duplicate/migrate'); ?>" method="POST" id="submit" class="migrate-apps">
	<div class="form-group form-content">
		<label for="from_date">Start Date</label>
		<input type="text" data-date-format="yyyy-mm-dd" name="start" value="<?php echo $this->current_date; ?>" class="form-control datepicker" id="from_date" placeholder="Select Date From">			
	</div>
	<div class="form-group form-content">
		<label for="to_date">End Date</label>
		<input type="text" data-date-format="yyyy-mm-dd" name="end" value="<?php echo $this->to_date; ?>"  class="form-control datepicker" id="end_date" placeholder="Select Date From">			
	</div>
	<div class="message-container" style="display: none;">
		<p>System is removing incomplete apps.</p>
		<p>Please wait for the system notification and then click close button.</p>
	</div>
</form>
<script>
	jQuery(document).ready(function($){
		$('.datepicker').datepicker();

		$('.migrate-apps').submit(function(e){
			e.preventDefault();
			$('.migrate-apps').find('.form-content').hide();
			$('.migrate-apps').find('.message-container').show();
		});
	});
</script>