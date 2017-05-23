<?php

	// Used for displaying question on frontend
	$question = $this->question;

	// get all responses from previous applications
	$responses = (isset($this->responses[$question->id])) ? $this->responses[$question->id]: 0;

	// check if question has exceeded the quouta
	if (( $responses < $question->max_apps) || $question->max_apps == 0) {
		$logic_role = (in_array($question->id, $this->parentWlogic)) ? 'parent': 'child';
		if($question->status == 0) {
			return;
		}

		$supression_class = "";

		if($question->supression) {
			$supression_class=" supression_warning";
		}
?>
	<div class="survey-start order-<?php echo $question->order. $supression_class; ?> question-<?php echo $question->id; ?> <?php echo $question->condition; ?>" data-order="<?php echo $question->order; ?>">		
	
	<p class="crm-enlarge col-lg-9"><?php echo $question->question; ?></p>
	<div class="input-group col-lg-3">
		<input type="hidden" name="question[]" value="<?php echo $question->id; ?>" />
		<?php 
			if($question->choices){
				?>
				<select data-logic="<?php echo $logic_role; ?>" data-role="<?php echo $question->role; ?>" data-gid="<?php echo $this->group_id; ?>" data-qid="<?php echo $question->id; ?>" class="select_answer form-control" name="answer[]">
						<option value="">--Select--</option>
					<?php foreach($question->choices as $choice){ ?>
						<option value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
					<?php } ?>
				</select>
			<?php }else{ ?>
				<input type="text" name="answer[]" class="form-control"/>
			<?php 
			}
		?>
	</div>
	
	<?php if($question->role == 'parent'){ ?>
		<div class="child_<?php echo $question->id; ?>_container child_container"></div>
	<?php } ?>
	</div>
<?php }