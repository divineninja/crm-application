<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li class="active">QA agent monitoring per hour</li>
</ol>
<div class="col-lg-3 col-lg-offset-5">
	<form action="<?php echo $this->set_url('reports/get_agent_hourly_monitoring'); ?>" method="POST" id="hourlyReport-idividual">
		
		<div class="input-group">
		  <input type="text" data-date-format="yyyy-mm-dd" name="startDate" value="" class="form-control datepicker" id="from_date" placeholder="Select Date From">
		  <span class="input-group-addon" id="show-individual-result"><i class="fa fa-search"></i></span>
		</div>
		 <small class="description">
		    "Select date for the starting shift of the day"
		 </small>
	</form>
</div>
<br />
<div class="col-lg-12">
	<div class="hourly-agent-display"></div>
</div>