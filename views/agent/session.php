<form class="form-session">
	<div class="input-group">
		<span class="input-group-addon">Start Shift</span>
		<input type="datetime-local" class="form-control" name="start_shift" value="<?php echo $this->current_date;?>" />
	</div>
	<div class="input-group">
		<span class="input-group-addon">End Shift</span>
		<input type="datetime-local" class="form-control" name="end_shift" value="<?php echo $this->end_date;?>" />
	</div>
</form>