<?php
/**
 * Leads main form template
 *
 * Display the template for the user to upload
 * leads and see the result from the differencial.
 *
 *
 * PHP VERSION 5
 *
 * @category PHP
 * @package  TeleQuest BPO
 * @author   Rey Lim Jr <junreyjr1029@gmail.com>
 * @version  3.0
 */
?>
<div class="col-lg-3 col-lg-offset-2">
    <form enctype="multipart/form-data" method="POST" action="<?php echo $this->set_url('leadsDiff/validateLeads'); ?>">
        <div class="form-group">
            <label for="from_date">Leads</label>
            <input type="file" name="leads" value="" required="required" class="form-control" id="leads" placeholder="">    
            <p><small class="description">Please put the leads here!</small></p>
        </div>
        <div class="form-group">
            <label for="from_date">File name</label>
            <input type="text" name="filename" value="leads<?php echo date('ymdhis'); ?>" required="required" class="form-control" id="filename" placeholder="Enter file name to download.">    
            <p><small class="description">Please specify the filename to download.</small></p>
        </div>
        <div class="form-group">
            <label for="from_date">Options</label>
            <select name="display_options" class="form-control">
                <option value="1">Display unique phone numbers.</option>
                <option value="0">Display duplicate phone numbers.</option>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-default" name="submit">
                <span>Validate Leads</span>
            </button>
        </div>
    </form>
</div>

<div class="col-lg-5">
    <?php
    if ($this->notice) {
        ?>
        <div class="alert alert-danger">
            <p><i class='fa fa-exclamation-circle'></i> <?php echo $this->notice; ?></p>
        </div>
        <?php
    }
    ?>
    <h3>Guidelines <i class='fa fa-exclamation'></i></h3>
    <div class="description">
        <p>
            <small>
                This module will generate the difference between 2 leads,
                this will show the phone numbers that are unique in the files you uploaded.
                <br /><br />
                Please be attentive to the file you upload in the form. The valid file format in the leads is <strong>CSV</strong>.
                <br />
                This module is case sensitive, make sure that the phone numbers is free from extra spaces in the start and end of line.
                <br />
                <br />
                <strong>Important Note!</strong><br />
                Make sure that the master leads, and leads is correct or else it will display no result.
                <br /><br />
                <strong>Master list:</strong> Already stored in the filesystem, Please contact developer to change the master lists.
                <br/>
                <strong>Leads:</strong>  It contain the dialed leads.
            </small>
        </p>
    </div>
</div>