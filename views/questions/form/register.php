<?php echo $this->create_form( $this->set_url('questions/save'),'form-horizontal' ); ?>
<div class="form-group">
    <label class="col-lg-3">Statement:</label>
    <div class="col-lg-9">
        <textarea name="question" class="required form-control" required="required"></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Type:</label>
    <div class="col-lg-9">
        <input type="text" name="type" class="required form-control"/>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Code:</label>
    <div class="col-lg-9">
        <input type="text" name="code" class="required form-control" required="required"/>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Max Apps / Day:</label>
    <div class="col-lg-9">
        <input type="number" name="max_apps" class="form-control" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Exclude Postal Codes:</label>
    <div class="col-lg-9">
        <input type="text" name="ex_postal_codes" class="required form-control" value="0"/>
        <p><small>Separate by comma.</small></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Include Postal Codes:</label>
    <div class="col-lg-9">
        <input type="text" name="in_postal_codes" class="required form-control" value="*"/>
        <p><small>Separate by comma.</small></p>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Order:</label>
    <div class="col-lg-9">
        <input type="number" name="order" value="0" class="required form-control" required="required"/>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Status:</label>
    <div class="col-lg-9">
        <select name="status" required="required" class="form-control" >
            <option value="1">Enable</option>
            <option value="0">Disable</option>
            <option value="2">Hidden</option>
            <option value="3">Force</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Supression:</label>
    <div class="col-lg-9">
        <select name="enable_supression" required="required" class="form-control" >
            <option value="0">Disable</option>
            <option value="1">Enable</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3">Group:</label>
    <div class="col-lg-9">
        <select name="group" class="required form-control" required="required" >
            <option value="">NO SELECTED GROUP</option>         
            <?php foreach($this->groups as $group){ ?>
                <option value="<?php echo $group->group_id; ?>"><?php echo $group->name; ?></option>
            <?php } ?>
        </select>
    </div>
</div> 
<div class="form-group">
    <label class="col-lg-3">Choices:</label>
    <div class="col-lg-9">
        <select name="choices[]" multiple class="required question-choices form-control" required="required" >      
                <option value="0">Manual</option>       
            <?php foreach($this->choices as $choice){ ?>
                <option value="<?php echo $choice->choices_id; ?>"><?php echo $choice->label; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<hr /> 
<div class="form-group">
    <label class="col-lg-3">Paid Response:</label>
    <div class="col-lg-4">
        <select name="paid_response[]" class="required form-control paid_response_answer" multiple="multiple" required="required" >
        </select>
    </div>
    <div class="col-lg-2"><label class="col-lg-12">Amount:</label></div>
    <div class="col-lg-3">
        <input type="number" name="amount" class="form-control" required="required" value="0" placeholder="0.00"/>
    </div>
</div>
<hr />
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
<?php echo $this->end_form(); ?>