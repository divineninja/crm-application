<?php require( dirname(__FILE__).'/configure/navigation.php'); ?>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
	<ol class="breadcrumb">
	  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
	  <li><a href="<?php echo $this->set_url('questions'); ?>">Questions</a></li>
	  <li class="active"><?php echo $this->question->code; ?></li>
	</ol>
	<div class="question-list">
		<?php if(!empty($this->referene_questions)){  ?>
			<p class="alert-info alert"><i class="icon-exclamation"></i> This question will display if the conditions are meet.</p>
		<?php
		require dirname(__FILE__).'/list.php';
		}else{ 
		?>
			<p class="alert alert-warnings"><i class="icon-exclamation"></i> No Current Condition. <a href="#" class="btn-survey show-question-list" role="button">Click here to add condition to the question.</a></p>
		<?php } ?>
	</div>
	<?php require( dirname(__FILE__).'/configure/parent-rule-form.php'); ?>
	<?php // require( dirname(__FILE__).'/configure/postal-form.php'); ?>
</div>