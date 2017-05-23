<?php
foreach($this->questions as $question){
	$responses = (isset($this->responses[$question->id])) ? $this->responses[$question->id]: 0;
	if (( $responses < $question->max_apps) || $question->max_apps == 0) {

		$logic_role = (in_array($question->id, $this->parentWlogic)) ? 'parent': 'child';

		$supression_class = "";
    		if($question->supression) {
    			$supression_class=" supression_warning";
    		}
?>
	<div class="survey-start order-<?php echo $question->order . $supression_class; ?> question-<?php echo $question->id; ?> <?php echo $question->condition; ?>" data-order="<?php echo $question->order; ?>">		
		<p class="crm-enlarge col-lg-9"><?php echo $question->question; ?></p>
		<div class="input-group col-lg-3">
			<input type="hidden" name="question[]" value="<?php echo $question->id; ?>" />
			<span class="cap_limit">Curent App: <?php echo $responses; ?> | Max App: <?php echo $question->max_apps; ?></span>
			<?php 
				if($question->choices){
					/*data-logic="<?php echo $logic_role; ?>"*/
					?>
					<select data-logic="<?php echo $logic_role; ?>" data-role="<?php echo $question->role; ?>" data-qid="<?php echo $question->id; ?>" data-gid="<?php echo $this->group_id; ?>" class="select_answer form-control" name="answer[]">
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
} ?>