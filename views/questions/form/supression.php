<form method="POST" class="supression_upload" action="<?php echo $this->set_url('questions/saveSupression'); ?>" enctype="multipart/form-data">
	<div class="alert alert-warning">
		<span>Supression file must be ".txt", and only phone numbers are in the file.</span><br/>
		<span>Important to limit the one milion (1,000,000) phone numbers per file, to ensure that all phone numbers will be stored in the database. </span>
	</div>
	<div class="form-group">
		<input type="hidden" class="form-control" name="question_id" required="required" value="<?php echo $this->question_id; ?>" />
		<input type="file" class="form-control" name="supression_file" required="required" accept=".txt" />
	</div>
		<a class="btn btn-danger btn-small delete-button" href="<?php echo $this->set_url("questions/deleteSuppression/$this->question_id"); ?>">Delete All Data</a>

</form>
<script>
	jQuery(document).ready(function(){
		$('.supression_upload').submit( function(){
			$('.supression_upload').append('<div class="loading-text"><i class="fa fa-spin fa-spinner"></i> Loading time varies upon the size of the file uploaded...</div>');
		});

		$('.delete-button').click( function() {
			$('.supression_upload').append('<div class="loading-text-delete"><i class="fa fa-spin fa-spinner"></i> System is removing all data in the database.</div>');
		})
	});
</script>