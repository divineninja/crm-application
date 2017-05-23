<div class="col-lg-1"><h4>#</h4></div>
<div class="col-lg-1"><h4>Code</h4></div>
<div class="col-lg-4"><h4>Question</h4></div>
<div class="col-lg-3"><h4>Answer</h4></div>
<div class="col-lg-3"><h4>Choices</h4></div>
<?php
$counter = 1;
foreach($this->applications->responses as $question){
 ?>
    <div class="survey-start">
        <p class="crm-enlarge col-lg-1"><?php echo $counter++; ?></p>
        <p class="crm-enlarge col-lg-1"><?php echo $question->code; ?></p>
        <p class="crm-enlarge col-lg-4"><?php echo $question->question; ?></p>
        <p class="crm-enlarge col-lg-3"><?php echo ($question->choices) ? $question->label: $question->manual; ?></p>
        <div class="input-group col-lg-3">
            <input type="hidden" name="question[]" value="<?php echo $question->id; ?>" />
            <?php if($question->choices){ ?>
                <select class="select_answer form-control" name="answer[]">
                        <option value="">--Select--</option>
                    <?php foreach($question->choices as $choice){ ?>
                        <option <?php echo $this->set_selected($question->answer,$choice->choices_id) ?> value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
                    <?php } ?>
                </select>
            <?php }else{ ?>
                <input type="text" name="answer[]" class="form-control" value="<?php echo $question->answer; ?>"/>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="col-lg-12">
    <h4>Remarks</h4>
    <textarea class="form-control" name="remarks"><?php echo $this->applications->application->remarks; ?></textarea>
    <br />
</div>
<div class="col-lg-4  col-lg-offset-4">
    <h4>Validator</h4>
    <select class="form-control" name="validator">
        <?php foreach($this->users as $user) { ?>
            <option <?php echo $this->set_selected($user->id,$this->applications->application->validator) ?> value="<?php echo $user->id; ?>">
            <?php echo $user->first_name.' '.$user->last_name; ?>
            </option>
        <?php } ?>
    </select>
</div>
<div class="col-lg-4">
    <h4>Status</h4>
    <select class="form-control" name="validation_status">
        <option <?php echo $this->set_selected('Updated tagging',$this->applications->application->validation_status) ?>>Updated tagging</option>
        <option <?php echo $this->set_selected('Reject',$this->applications->application->validation_status) ?>>Reject</option>
        <option <?php echo $this->set_selected('Approved',$this->applications->application->validation_status) ?>>Approved</option>
        <option <?php echo $this->set_selected('Approved with Error',$this->applications->application->validation_status) ?>>Approved with Error</option>
    </select>
    <br />
</div>
