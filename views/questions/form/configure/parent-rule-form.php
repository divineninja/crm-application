<div class="question-form">
	<hr/>
	<?php echo $this->create_form( $this->set_url('questions/insert_survey_configuration'),'crm-submit' ); ?>
		<input type="hidden" name="display_question" value="<?php echo $this->id;?>" /> 
		<div class="container-configure-question">	
		
			<div class="parent-container-form">	
				<div class="form-group">
					<p><a href="#" class="btn btn-small btn-danger remove-parent-form"><i class="icon-trash"></i> Remove</a></p>
					<div class="input-group">
						<span class="input-group-addon">Then Display Question</span>
						<input data-url="<?php echo $this->set_url('questions/get_choices_by'); ?>" class="hide form-control question-selected" name="reference_question[]"  required="required">
						<input list="q_list" class="form-control dropdown_list">
						<datalist id="q_list">
							<option value="">SELECT QUESTION</option>		
							<?php foreach($this->valid_questions as $question){ ?>
								<option value="<?php echo $question->code; ?>"><?php echo strip_tags(substr($question->question, 0, 50))."..."; ?></option>
							<?php } ?>
						</datalist>				
					</div>
					<p class="crm-note badge badge-info"><small>Note: Questions with same group or postal code will be displayed on "Then Display Question"</small></p>
					  <div class="input-group">
						 <span class="input-group-addon">If The Answer Is</span>			
						<select name="operator[]" class="required form-control" required="required" >	
							<option value="0">No Condition</option>
							<option value="equal">Equal</option>
							<option value="not_equal">Not Equal</option>
							<option value="any">Any</option>
						</select>
					</div>
					<div class="input-group">
						 <span class="input-group-addon">To</span>			
						 <select class="form-control choices-list" name="answer[]"  required="required">
							<option value=''>SELECT ANSWER</option>		
						  </select>
					</div>
				</div>
			</div>	
			<div class="child-container-form"></div>
		</div>
	<?php echo $this->end_form(); ?>
	</div>
	
	<script>
	jQuery(document).ready(function(){
		
		var collections = <?php echo json_encode($this->valid_questions); ?>;
	
		$(document).on('change', '.dropdown_list', function(){
			var value = $(this).val();
			var this_parent = $(this).parent();
			$(this_parent).find('.question-selected').val(collections[value].id);
			$(this_parent).find('.question-selected').trigger('change')
		});
		
	});
	</script>