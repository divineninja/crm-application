<form action="<?php echo $this->set_url('advise/save'); ?>" method="POST" id="submit">
	<div class="form-group">
		<label for="exampleInputEmail1">Coaching ID:</label>
		<input type="text" name="coaching_id" class="form-control" value="<?php echo date('mdyhis'); ?>"/>
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Phone</label>
		<input type="text" name="phone" class="form-control" value=""/>
	</div>

	<div class="coach-meta">
		<div class="tab-controls">
			<ul>
			<?php $i = 0; ?>
			<?php foreach ($this->criteria as $key => $value) { ?>
				<li class="tab-control<?php if($i == 0) echo ' active';?>"><a href="#content-<?php echo $key; ?>"><?php echo $value; ?></a></li>
			<?php $i++; ?>
			<?php } ?>
			</ul>
		</div>
		<?php $i = 0; ?>
		<?php foreach ($this->criteria as $key => $value) { ?>		
		<div id="content-<?php echo $key?>" class="tab-content<?php if($i == 0) echo ' active';?>">
		<?php $i++; ?>
			<input type="hidden" name="criteria[<?php echo $i; ?>]" value="<?php echo $i; ?>" />
			<div class="form-group">
				<label for="exampleInputEmail1">Remarks</label>
				<select class="form-control scoring" data-key="<?php echo $key?>" name="status[<?php echo $i; ?>]">
					<option value="1">Unacceptable</option>
					<option value="2">Needs Improvement</option>
					<option value="3">Meets Expectations</option>
					<option value="4">Exceeds Expectations</option>
					<option value="5">Outstanding</option>
				</select>
			</div>
			<div class="form-group col-lg-12">
				<div class="row">
					<div class="col-lg-7">
						<label for="exampleInputEmail1">Comments</label>
						<textarea class="form-control" name="comment[<?php echo $i; ?>]"></textarea>
					</div>
					<div class="col-lg-5">
						<label for="exampleInputEmail1">Score (<output name="amount_<?php echo $i; ?>" for="rangeInput_<?php echo $i; ?>">0</output>%)</label>
						<p>
							<input type="range" class="col-lg-12" id="rangeInput_<?php echo $key; ?>" name="rangeInput_<?php echo $i; ?>" min="0" max="0" value="0" oninput="amount_<?php echo $i; ?>.value=rangeInput_<?php echo $i; ?>.value">
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group col-lg-12">
			<br />
			<label for="exampleInputEmail1">Overall Comment</label>
				<textarea name="overall_comment" class="col-lg-12 form-control" placeholder="Enter your overall comment here!"></textarea>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="form-group col-lg-6">
				<label for="exampleInputEmail1">Agent</label>
				<select class="form-control" name="agent_id">
					<?php foreach ($this->agents as $key => $value) { ?>
						<option value="<?php echo $value->agent_id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-lg-6">
				<label for="exampleInputEmail1">Your name</label>
				<select class="form-control" name="tl_id">
					<?php foreach ($this->users as $key => $value) { ?>
						<option value="<?php echo $value->id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
</form>