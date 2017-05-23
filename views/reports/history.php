<div class="form-group detail-wrapper">
	<div class="col-lg-6">
	<p><label class="item-label-details">Name:</label><span class="item-detail-value"><?php echo $this->application->application->details->title.' '.$this->application->application->details->first_name.'. '.$this->application->application->details->last_name; ?></span></p>
	<p><label class="item-label-details">Phone:</label><span class="item-detail-value"><?php echo $this->application->application->phone; ?></span></p>
	<p><label class="item-label-details">Revenue:</label><span class="item-detail-value"><?php echo $this->application->application->revenue; ?></span></p>
	<p><label class="item-label-details">URN:</label><span class="item-detail-value"><?php echo $this->application->application->details->urn_original; ?></span></p>
	<p><label class="item-label-details">Gender:</label><span class="item-detail-value"><?php echo (isset($this->applications->application->details->gender))?$this->applications->application->details->gender:"Undefined"; ?></span></p>
	<p><label class="item-label-details">Legal:</label><span class="item-detail-value"><?php echo $this->application->application->details->legal; ?></span></p>
	</div>
	<div class="col-lg-6">
	<p><label class="item-label-details">Address 1:</label><span class="item-detail-value"><?php echo $this->application->application->details->address1.', '.$this->application->application->details->town; ?></span></p>
	<p><label class="item-label-details">Address 2:</label><span class="item-detail-value"><?php echo $this->application->application->details->address2; ?></span></p>
	<p><label class="item-label-details">Address 2:</label><span class="item-detail-value"><?php echo $this->application->application->details->address3; ?></span></p>
	<p><label class="item-label-details">Post Code:</label><span class="item-detail-value"><?php echo $this->application->application->details->post_code; ?></span></p>
	<p><label class="item-label-details">Date:</label><span class="item-detail-value"><?php echo $this->application->application->date; ?></span></p>
	<p><label class="item-label-details">Confirm:</label><span class="item-detail-value"><?php echo $this->application->application->details->confirm; ?></span></p>
	</div>
	<div class="col-lg-12">
		<p><label class="item-label-details">Validator:</label><span class="item-detail-value"><?php echo $this->application->application->first_name; ?> <?php echo $this->application->application->last_name; ?></span></p>		
	</div>
</div>
<div class="form-group detail-wrapper">
	<table class="table">
		<thead>
			<tr>
				<th>Code</th>
				<th>Question</th>
				<th>Answer</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if($this->application->responses){
			foreach($this->application->responses as $details){ ?>
			<tr>
				<td><?php echo $details->code;	 ?></td>
				<td><?php echo $details->question; ?></td>
				<td><?php echo ($details->label)?$details->label:$details->manaul; ?></td>
			</tr>
			<?php }
			}else{ ?>
			<tr>
				<td colspan="3">No Data Found.</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>