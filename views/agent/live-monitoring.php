<?php
$total_application = 0;
$total_revenue = 0;
?>
<h3>Statistics</h3>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Phone Number</th>
				<th width="20%">Name</th>
				<th>Finished</th>
				<th>Unfinished</th>
				<th>Total</th>
				<th>Revenue</th>
				<th>Hours</th>
				<th>Revenue/Hour</th>
				<th>Survey/Hour</th>
				<th width="20%">Completion Rate</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$complete = 0;
			$incomplete = 0;
			foreach($this->monitor as $agent) {
				
				$complete = $complete + (int)$agent->finished_app;
		
				$incomplete = $incomplete + (int)$agent->unfinished_app;
				
				$total_revenue = $total_revenue + (float)$agent->revenue;
				$total_application = $total_application + $agent->app_count;
				
			?>
			<tr>
				<td align="right"><?php echo ($agent->agent_number) ? $agent->agent_number: "Not Defined"; ?></td>
				<td><?php echo ($agent->first_name || $agent->last_name)?$agent->first_name. ' '. $agent->last_name:"Anonymous"; ?></td>
				<td align="right"><?php echo $agent->finished_app; ?></td>
				<td align="right"><?php echo $agent->unfinished_app; ?></td>
				<td align="right"><?php echo $agent->app_count; ?></td>
				<td align="right"><?php echo $agent->revenue; ?></td>
				<td align="right"><?php echo $agent->hour; ?></td>
				<td align="right"><?php echo number_format((float)$agent->revenue_average, 2, '.', ''); ?></td>
				<td align="right"><?php echo number_format((float)$agent->sph, 2, '.', ''); ?></td>
				<td><?php echo number_format((float)$agent->completion_rate, 2, '.', ''); ?>%</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="col-lg-12">
		<div class="col-lg-3">
			<div class="col-lg-6"><strong>Total</strong></div>
			<div class="col-lg-6"><?php echo $total_application; ?></div>
		</div>
		<div class="col-lg-3">
			<div class="col-lg-6"><strong>Revenue</strong></div>
			<div class="col-lg-6"><?php echo $total_revenue; ?></div>
		</div>
		<div class="col-lg-3">
			<div class="col-lg-6"><strong>Complete</strong></div>
			<div class="col-lg-6"><?php echo $complete; ?></div>
		</div>
		<div class="col-lg-3">
			<div class="col-lg-6"><strong>Incomplete</strong></div>
			<div class="col-lg-6"><?php echo $incomplete; ?></div>
		</div>
	</div>