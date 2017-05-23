<form action="<?php echo $this->set_url("advise/update/{$this->coaching->id}"); ?>" method="POST" id="submit">
    <div class="form-group">
        <label for="exampleInputEmail1">Coaching ID:</label>
        <input type="text" readonly="readonly" name="coaching_id" class="form-control" value="<?php echo $this->coaching->coaching_id; ?>"/>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo $this->coaching->phone; ?>"/>
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
                <?php
                    $status = $this->coaching->meta[$i-1]->status
                ?>
                <select class="form-control scoring" data-key="<?php echo $key?>" name="status[<?php echo $i; ?>]">
                    <option <?php echo $this->set_selected(1, $status); ?> value="1">Unacceptable</option>
                    <option <?php echo $this->set_selected(2, $status); ?> value="2">Needs Improvement</option>
                    <option <?php echo $this->set_selected(3, $status); ?> value="3">Meets Expectations</option>
                    <option <?php echo $this->set_selected(4, $status); ?> value="4">Exceeds Expectations</option>
                    <option <?php echo $this->set_selected(5, $status); ?> value="5">Outstanding</option>
                </select>
            </div>
            <div class="form-group col-lg-12">
                <div class="row">
                    <div class="col-lg-7">
                        <label for="exampleInputEmail1">Comments</label>
                        <textarea class="form-control" name="comment[<?php echo $i; ?>]"><?php echo $this->coaching->meta[$i-1]->remarks; ?></textarea>
                    </div>
                    <div class="col-lg-5">
                        <label for="exampleInputEmail1">Score (<output name="amount_<?php echo $i; ?>" for="rangeInput_<?php echo $i; ?>"><?php echo $this->coaching->meta[$i-1]->score; ?></output>%)</label>
                        <?php
                            if ($status == 1) {
                                $max = '0';
                                $min = '0';
                            } else if ($status == 2) {
                                $max = '25';
                                $min = '1';
                            } else if ($status == 3) {
                                $max = '49';
                                $min = '26';
                            } else if ($status == 4) {
                                $max = '75';
                                $min = '50';
                            } else if ($status == 5) {
                                $max = '100';
                                $min = '76';
                            } 
                        ?>
                        <p>
                            <input
                                type="range"
                                class="col-lg-12"
                                id="rangeInput_<?php echo $key; ?>" 
                                name="rangeInput_<?php echo $i; ?>"
                                min="<?php echo $min; ?>"
                                max="<?php echo $max; ?>"
                                value="<?php echo $this->coaching->meta[$i-1]->score; ?>"
                                oninput="amount_<?php echo $i; ?>.value=rangeInput_<?php echo $i; ?>.value"
                            />
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
                <textarea name="overall_comment" class="col-lg-12 form-control" placeholder="Enter your overall comment here!"><?php echo $this->coaching->overall_comment; ?></textarea>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Agent</label>
                <select class="form-control" name="agent_id">
                    <?php foreach ($this->agents as $key => $value) { ?>
                        <option <?php echo $this->set_selected($value->agent_id, $this->coaching->agent_id); ?> value="<?php echo $value->agent_id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label for="exampleInputEmail1">Your name</label>
                <select class="form-control" name="tl_id">
                    <?php foreach ($this->users as $key => $value) { ?>
                        <option <?php echo $this->set_selected($value->id, $this->coaching->tl_id); ?> value="<?php echo $value->id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</form>