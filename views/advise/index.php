<?php
$date = (isset($this->params['coaching.app_date'])) ? $this->params['coaching.app_date']: date('Y-m-d');
$tl = (isset($this->params['coaching.tl_id'])) ? $this->params['coaching.tl_id']: '';
$agent_id = (isset($this->params['coaching.agent_id'])) ? $this->params['coaching.agent_id']: '';
$coaching_id = (isset($this->params['coaching.coaching_id'])) ? $this->params['coaching.coaching_id']: '';

if ($this->enableUpdate) { ?>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <button class="btn btn-small btn-default show_modal" href="#" title="New Coaching" data-url="<?php echo $this->set_url('advise/add'); ?>">
                    <i class="fa fa-plus"></i> <span>New Coaching</span>
                </button>
                <button class="btn btn-small btn-default show_modal" title="Download Report" href="#" data-url="<?php echo $this->set_url('advise/downloadForm'); ?>">
                    <i class="fa fa-download"></i>
                </button>
            </div>
            <div class="col-lg-7">
                <div class="input-group col-lg-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="coaching_id" value="<?php echo $coaching_id; ?>" title="<?php echo $coaching_id; ?>" class="form-control" id="coaching_id" placeholder="Coaching ID" />
                </div>
                <div class="input-group col-lg-3">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <select class="form-control" name="agent_id" id="agent_id">
                        <option value="">-- Select --</option>
                        <?php foreach ($this->agents as $key => $value) { ?>
                            <option <?php echo $this->set_selected($agent_id, $value->agent_id); ?> value="<?php echo $value->agent_id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="input-group col-lg-3">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <select class="form-control" name="tl_id" id="tl_id">
                        <option value="">-- Select --</option>
                        <?php foreach ($this->users as $key => $value) { ?>
                            <option <?php echo $this->set_selected($tl, $value->id); ?> value="<?php echo $value->id; ?>"><?php echo $value->last_name.', '.$value->first_name; ?></option>
                        <?php } ?>
                    </select>
                </div>                
                <div class="input-group col-lg-3">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" data-date-format="yyyy-mm-dd" name="date" value="<?php echo $date; ?>" class="form-control datepicker" id="date" placeholder="Select Date From">
                </div>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-info" id="startFind">
                    <span><i class="fa fa-search"></i></span>
                </button>
                <button class="btn btn-default" id="reset">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
         <div class="space"></div>
        <div class="row">
            <div class="col-lg-12">
                <table class="dataTable table table-bordered">
                    <thead>
                        <tr>
                            <th width="3%">ID</th>
                            <th width="5%">Phone</th>
                            <th width="12%">Date</th>
                            <th width="12%">Agent</th>
                            <th width="13%">TL</th>
                            <th width="13%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->coaching as $key => $value) { ?>
                            <tr>
                                <td><?php echo $value->coaching_id; ?></td>
                                <td><?php echo $value->phone; ?></td>
                                <td><?php echo $value->app_date; ?></td>
                                <td><?php echo $value->agent_fname. ' '. $value->agent_lname; ?></td>
                                <td><?php echo $value->user_fname. ' '. $value->user_lname; ?></td>
                                <td>
                                    <a class="show_modal" href="#" title='Followup for "<?php echo $value->coaching_id; ?>"' data-url="<?php echo $this->set_url("advise/child_list/{$value->id}"); ?>"><span>Followup</span></a> |
                                    <a class="show_modal" href="#" title='Edit "<?php echo $value->coaching_id; ?>"' data-url="<?php echo $this->set_url("advise/edit/{$value->id}"); ?>"><span>Edit</span></a> |
                                    <a class="show_confirm" href="<?php echo $this->set_url("advise/delete/{$value->id}"); ?>">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        $(document).on('click', '#startFind',function(){

            var date     = $('#date').val();
            var agent_id = $('#agent_id').val();
            var tl_id    = $('#tl_id').val();
            var c_id     = $('#coaching_id').val();

            var url      = "<?php echo $this->set_url('advise'); ?>";
            var data = window.btoa(date+'&'+agent_id+'&'+tl_id+'&'+c_id);

            window.location = url + '?data='+data;
        });

        $(document).on('click', '#reset',function(){
            var url = "<?php echo $this->set_url('advise'); ?>";
            window.location = url;
        });
    });
    </script>
<?php } else { ?>
    <div class="col-lg-12">
        <div class="col-lg-12 alert">
            <h5 class="pull-left">System detected an error on your database.</h5>
            <a href="<?php echo $this->set_url('setup/advise'); ?>" class="pull-right btn btn-small btn-warning">Fix database</a>
        </div>
    </div>
<?php } ?>