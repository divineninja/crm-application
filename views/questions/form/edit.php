<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a data-url="<?php echo URL; ?>questions/register" data-title="Question" id="show_add_item"><i class="fa fa-plus"></i> Add</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>questions/edit_item" class="edit_item"><i class="fa fa-edit"></i> Edit</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>questions/delete_item" class="delete_item"><i class="fa fa-times"></i> Delete</a>
			</li>
			<li><a href="#" class="update_question"><i class="fa fa-save"></i> Save</a></li>
			
			<li><a href="<?php echo $this->set_url('questions'); ?>"><i class="fa fa-mail-reply"></i>  Back</a></li>
		</ul>
	</div>
</div>
<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
	<?php echo $this->create_form( $this->set_url('questions/update_question'),'form-horizontal' ); ?>
	<div class="form-group">
		<label class="col-lg-3">Statement:</label>
		<div class="col-lg-9">
			<textarea name="question" class="required form-control" required="required"><?php echo $this->item->question; ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Type:</label>
		<div class="col-lg-9">
			<input type="text" name="type" class="required form-control" value="<?php echo $this->item->type; ?>"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Code:</label>
		<div class="col-lg-9">
			<input type="text" name="code" class="required form-control" value="<?php echo $this->item->code; ?>" required="required"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Max Apps / Day:</label>
		<div class="col-lg-9">
			<input type="number" name="max_apps" class="form-control" value="<?php echo $this->item->max_apps; ?>"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Exclude Postal Codes:</label>
		<div class="col-lg-9">
			<input type="text" name="ex_postal_codes" class="required form-control"  value="<?php echo $this->item->ex_postal_codes; ?>"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Include Postal Codes:</label>
		<div class="col-lg-9">
			<input type="text" name="in_postal_codes" class="required form-control" value="<?php echo $this->item->in_postal_codes; ?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Order:</label>
		<div class="col-lg-9">
			<input type="number" name="order" value="<?php echo $this->item->order; ?>" class="required form-control" required="required"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Status:</label>
		<div class="col-lg-9">
			<select name="status" required="required" class="form-control" >
				<option <?php echo $this->set_selected(1,$this->item->status) ?> value="1">Enable</option>
				<option <?php echo $this->set_selected(0,$this->item->status) ?> value="0">Disable</option>
				<option <?php echo $this->set_selected(2,$this->item->status) ?> value="2">Hidden</option>
				<option <?php echo $this->set_selected(3,$this->item->status) ?> value="3">Force</option>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-3">Supression:</label>
		<div class="col-lg-9">
			<select name="enable_supression" required="required" class="form-control" >
				<option <?php echo $this->set_selected(0,$this->item->enable_supression) ?> value="0">Disable</option>
				<option <?php echo $this->set_selected(1,$this->item->enable_supression) ?> value="1">Enable</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3">Group:</label>
		<div class="col-lg-9">
			<select name="group" class="required form-control" required="required" >
				<?php foreach($this->groups as $group){ ?>
					<option <?php echo $this->set_selected($group->group_id,$this->item->group) ?> value="<?php echo $group->group_id; ?>"><?php echo $group->name; ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-lg-3">Is Required:</label>
		<div class="col-lg-9">
			<select name="is_required" required="required" class="form-control" >
				<option <?php echo $this->set_selected(1,$this->item->is_required) ?> value="1">Yes</option>
				<option <?php echo $this->set_selected(0,$this->item->is_required) ?> value="0">No</option>
			</select>
		</div>
	</div>
	<?php /* <div class="form-group">
	
	<label class="col-lg-3">Choices:</label>
		<div class="col-lg-9">
			<!-- <select name="choices[]" multiple class="required question-choices form-control" required="required" >          -->
			<select name="choices[]" multiple class="required multiselect-js form-control" required="required" >          
					<?php 
					$choice_selected = unserialize($this->item->choices);
					foreach($this->choices as $choice){ ?>
						<option <?php echo $this->compare_by_array($choice->choices_id,$choice_selected) ?> value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
					<?php } ?>
			</select>
		</div>
	</div>
	*/ ?>
	<div class="form-group">
		<label class="col-lg-3">Choices:</label>
		<div class="col-lg-9">
			<input type="text" class="search_for_choices required form-control" />
			<select name="choices[]" multiple class="required hidden multiselect-js question-choices form-control" required="required" ></select>
			<div class="display_as_text"></div>
			<div class="choices_result">
				<ul></ul>
			</div>
		</div>
	</div>
	<hr /> 
	<div class="form-group">
		<label class="col-lg-3">Paid Response:</label>
		<div class="col-lg-4">
			<?php 
			$response = unserialize($this->item->paid_response);
			$paid_response = ($response)? $response : array();
			
			?>
			<select name="paid_response[]" class="required form-control paid_response_answer" multiple="multiple" required="required" >
				<?php foreach($this->choice_selected as $choice){
					if(!empty($choice)):
				?>
					<option <?php echo $this->compare_by_array($choice->choices_id,$paid_response) ?> value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
				<?php endif; 
				} ?>
			</select>
		</div>
		<div class="col-lg-2"><label class="col-lg-12">Amount:</label></div>
		<div class="col-lg-3">
			<input type="number" name="amount" value="<?php echo $this->item->amount?>" class="form-control" placeholder="0.00"/>
		</div>
	</div>
	<hr />
	<div class="condition-lists">
		<table class="table">
			<thead>
				<tr>
					<th>Question</th>
					<th>Operation</th>
					<th>answer</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!$this->parent_conditions) { ?>
					<tr align="center">
						<td colspan="4"><h4>No data found.</h4>
						</td>
					</tr>
				<?php }else{ ?> 
					<?php foreach($this->parent_conditions as $condition) { ?>
					<tr>
						<td><?php echo $condition->display_question_statement; ?></td>
						<td><?php echo $condition->condition; ?></td>
						<td><?php echo $condition->label; ?></td>
						<td><a class="delete-condition-question" href="<?php echo $this->set_url("questions/remove_condition/{$condition->id}"); ?>"><i class="icon-remove"></i> Remove</a></td>
					</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="parent-control-child-condition">
		<div class="form-group">
			<label class="col-lg-3">Condition:</label>
			<div class="col-lg-9">
				<select name="condition[]" class="required form-control" required="required" >  
					<option value="0">No Condition</option>
					<option value="equal">Equal</option>
					<option value="not_equal">Not Equal</option>
					<option value="any">Any</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3">Conditional Answer:</label>
			<div class="col-lg-9">
				<select name="conditional_answer[]" class="required conditional_answer form-control" required="required" >          
					<option value="0">Any Answer</option>
					<?php foreach($this->choice_selected as $choice){ ?>
						<option value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3">Ask this question:</label>
			<div class="col-lg-9">
				<select name="child[]" class="form-control">        
				<option value="">NO SELECTED QUESTION</option>
					<?php foreach($this->questions as $question){ ?>
						<option value="<?php echo $question->id; ?>"><?php echo $question->code; ?>: <?php echo $question->question; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	<div class="child-condition-list"></div>
	<a href="#" class="btn btn-info btn-add-new-child-condition"><i class="fa fa-plus"></i></a>
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
	<?php echo $this->end_form(); ?>
</div>

<script>
jQuery(document).ready(function($) {
	var url = "<?php echo $this->set_url('questions/search_choices'); ?>";
	var data = {};
	var selected_choices = {};
	
	var actions = {
		
		populate_select : function()
		{
			$('.multiselect-js').html('');
			
			$.each(selected_choices, function(key, val){
				$('.multiselect-js').append('<option selected id="choice_'+key+'" value="'+key+'">'+val+'</option>');
			});
			
		},
		
		display_as_text: function()
		{
			$('.display_as_text').html('');
			$.each(selected_choices, function(key, val){
				$('.display_as_text').append('<span><small data-id="'+key+'" class="remove_this_choice">&times;</small> '+val+'</span>');
			})
		},
		
		initialize_choices: function()
		{
			
			var my_choices = <?php echo json_encode($this->my_choices); ?>;
			
			selected_choices = my_choices;
		}
	}
	
	$(document).ready(function(){
		actions.initialize_choices();
		actions.display_as_text();
		actions.populate_select();
	});
	
	$(document).on('keyup','.search_for_choices', function(e) {
		e.preventDefault();
		
		var key = $(this).val();
		
		$('.choices_result').find('ul').html('');
		
		if(key.length >= 2) {
			
			data = {
				value: key
			}
			
			$.get(url,data, function(response){
				
				$.each(response, function(key, value){
					$('.choices_result').find('ul').append('<li class="select-choice" data-id="'+value.choices_id+'">'+value.label+'</li>');
				})
			},'json');
		}
	});
	
	$(document).on('click','.select-choice', function(e) {
		
		var value = $(this).html();
		var choices_id = $(this).data('id');
		
		selected_choices[choices_id] = value;
		
		actions.populate_select();
		actions.display_as_text();
		
		$('.question-choices').trigger('change');
	
	});
	
	$(document).on('click','.remove_this_choice', function(e) {
		
		var choices_id = $(this).data('id');
		
		delete selected_choices[choices_id];
		
		actions.populate_select();
		actions.display_as_text();
		
		$('.question-choices').trigger('change');
	});
	
	$(document).on('click','.update_question', function(e) {
		$('#submit').trigger('submit');
	});
	
})
</script>
<style>
.choices_result ul li {
    display: inline-flex;
    padding: 5px 10px;
    margin: 4px auto;
    width: 30%;
	cursor: pointer;
}
.choices_result ul li:hover {
	background: #2b6591;
	color: #FFF;
}
.choices_result ul {
    margin: 0;
    padding: 0;
}
.display_as_text span {
    background: #2b6591;
    margin: 2px 2px;
    padding: 0 5px;
    border-radius: 5px;
    color: #FFF;
    display: inline-block;
}
.display_as_text span:hover {
	background: #333;
}
.remove_this_choice {
	cursor: pointer;
}
</style>