<div class="col-lg-3 col-lg-offset-9">
	<?php 
	$rebuttal = $this->get_site_meta('rebuttal');
	if($rebuttal): ?>
		<a href="#" target="_blank" class="btn btn-small btn-default"  onclick="return popitup('<?php echo $rebuttal; ?>')">Show Rebuttal</a>
	<?php else: ?>
		
	<?php endif; ?>
	<br />
	<br />
</div>

<?php
foreach($this->questions as $question){
	
    $responses = (isset($this->responses[$question->id])) ? $this->responses[$question->id]: 0;
	
    if ($responses < $question->max_apps || $question->max_apps == 0) {
		
		$supression_class = "";
		
		if($question->supression) {
			$supression_class=" supression_warning";
		}
	?>
    <div class="survey-start order-<?php echo $question->order. $supression_class; ?> <?php echo $question->condition; ?>">      
        <p class="crm-enlarge col-lg-9">
			<?php echo $question->question; ?>
			<span class="cap_limit">Curent App: <?php echo $responses; ?> | Max App: <?php echo $question->max_apps; ?></span>
		</p>
		
        <div class="input-group col-lg-3">
            <input type="hidden" name="question[]" value="<?php echo $question->id; ?>" />
            <?php 
        if ($question->choices) {
            ?>
            <select <?php echo ($question->is_required)? 'required="required"': ''; ?> data-role="<?php echo $question->role; ?>" data-qid="<?php echo $question->id; ?>" class="select_answer form-control" name="answer[]">
                    <option value="">--Select--</option>
                <?php foreach($question->choices as $choice){ ?>
					<?php if(!empty($choice)): ?>
						<option value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
					<?php endif; ?>
                <?php } ?>
            </select>
        <?php
        } else {
                ?>
                <input type="text" name="answer[]" class="form-control"/>
            <?php
        }
            ?>
        </div>
        <?php if($question->role == 'parent'){ ?>
            <div class="child_<?php echo $question->id; ?>_container child_container"></div>
        <?php } ?>
    </div>
<?php
    }
}
?>
<script>
function popitup(url) {
	newwindow=window.open(url,'name','height=600,width=1000');
	if (window.focus) {newwindow.focus()}
	return false;
}     
</script>