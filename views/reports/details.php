<div class="form-group detail-wrapper">
	<div class="col-lg-6">
	<p><label class="item-label-details">Name:</label>			<span class="item-detail-value"><?php echo $this->details->details['title'].' '.$this->details->details['first_name'].'. '.$this->details->details['last_name']; ?></span></p>
	<p><label class="item-label-details">Phone:</label>			<span class="item-detail-value"><?php echo $this->details->phone; ?></span></p>
	<p><label class="item-label-details">Revenue:</label>		<span class="item-detail-value"><?php echo $this->details->revenue; ?></span></p>
	<p><label class="item-label-details">Validator:</label>		<span class="item-detail-value"><?php echo $this->details->first_name; ?> <?php echo $this->details->last_name; ?></span></p>
	</div>
	<div class="col-lg-6">
	<p><label class="item-label-details">Address:</label>		<span class="item-detail-value"><?php echo $this->details->details['last_name'].', '.$this->details->details['town']; ?></span></p>
	<p><label class="item-label-details">Post Code:</label>		<span class="item-detail-value"><?php echo $this->details->details['post_code']; ?></span></p>
	<p><label class="item-label-details">Date:</label>			<span class="item-detail-value"><?php echo $this->details->date; ?></span></p>
	<p><label class="item-label-details">Status:</label>			<span class="item-detail-value"><?php echo $this->details->validation_status; ?></span></p>
	</div>
		
	<div class="col-lg-12">
		<strong>Remarks</strong>
		<p><?php echo $this->details->remarks; ?></p>
	</div>
</div>
<div class="form-group detail-wrapper">
	<table class="table">
		<thead>
			<tr>
				<th>Code</th>
				<th>Question</th>
				<th>Answer</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$amount_total = floatval(0);
			if($this->details->questions){
				foreach($this->details->questions as $details){
				if($details->answer != ''){
					$amount_total = floatval ($amount_total) + floatval ($details->amount);
				}
				?>
					<tr>
						<td><?php echo $details->code;	 ?></td>
						<td><?php echo $details->question; ?></td>
						<td><?php echo ($details->label != '')?$details->label:$details->manual; ?></td>
						<td><?php echo ($details->answer != '') ? $details->amount .'|'.$amount_total: 0;  ?></td>
					</tr>
				<?php 
				}
			}else{ ?>
			<tr>
				<td colspan="4">No Data Found.</td>
			</tr>
			<?php } ?>
			<?php if($this->details->questions){ ?>
				<tr>
					<td colspan="3">Total</td>
					<td colspan="1"><?php echo $amount_total; ?></td>
				</tr>
			<?php } $amount_total = 0;?>
		</tbody>
	</table>
</div>