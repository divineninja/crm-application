<table class="table">
	<thead>
		<tr>
			<th>Phone</th>
			<th>Interview Started</th>
			<th>Interview Ended</th>
			<th>Revenue</th>
			<th>Status</th>
		</tr>		
	</thead>
	<tbody>
		<?php foreach($this->apps as $apps){ ?>
			<tr>
				<td><?php echo $apps->phone ?></td>
				<td><?php echo $apps->interview_started_date. ' ' .$apps->interview_started_time; ?></td>
				<td><?php echo $apps->date ?></td>
				<td><?php echo $apps->revenue ?></td>
				<td><?php echo ($apps->status)?'Finished':'Not Finished'; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>