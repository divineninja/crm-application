<?php echo $this->create_form( $this->set_url('survey/save_customer_survey'),'crm-submit' ); ?>
		<input type="hidden" name="answer_group" value="<?php echo $this->group_id; ?>" />
		<input type="hidden" name="redirect" value="<?php echo $this->set_url('survey'); ?>" />
		<input type="hidden" name="status" value="1" />
		<?php  foreach($this->questions as $question){  ?>
			<div class="survey-start">
				<p class="crm-enlarge col-lg-9"><?php echo $question->question; ?></p>
				<div class="input-group col-lg-3">
					<input type="hidden" name="question[]" value="<?php echo $question->id; ?>" />
					<select data-condition="<?php echo $question->condition; ?>" data-conditional-answer="<?php echo $question->condition_answer; ?>" class="select_answer form-control" name="answer[]">
							<option value="">--Select--</option>
						<?php foreach($question->choices as $choice){ ?>
							<option value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
						<?php } ?>
					</select>
				</div>
				<?php 	
				if($question->child){
					$this->setup_children($question->child);
				} ?>
			</div>
		<?php } ?>
		<div class="col-lg-2 col-lg-offset-10">
			<button class="btn btn-info col-lg-12"><i class="icon icon-check-sign"></i> Finish</button>
		</div>
		<?php echo $this->end_form(); ?>