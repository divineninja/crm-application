<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
      <li><a href="#">QA CRM</a></li>
      <li class="active"><?php echo $this->applications->application->phone; ?></li>
	  <li class="pull-right"><?php echo round($this->processTime, 2); ?></strong>s</li>
    </ol>
    <?php echo $this->create_form($this->set_url('survey/qa_update'), 'crm-submit'); ?>
        <div class="col-lg-12">
            <?php require( dirname(__FILE__).'/qacrm/user_details.php' ); ?>
        </div>
        <div class="col-lg-12">
            <?php require( dirname(__FILE__).'/qacrm/questions.php' ); ?>
        </div>      
        <div class="col-lg-3 col-lg-offset-9">
			<?php 
			if($_SESSION['role'] != 'Operation') {
			?>
            <button href="#" class="btn btn-survey btn-primary pull-right btn-xs" role="button">Update</button>
			
			<?php } ?>
        </div>
    <?php echo $this->end_form(); ?>
</div>