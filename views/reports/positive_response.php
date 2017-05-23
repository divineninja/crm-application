<div class="col-lg-4">
	<form class="generate-positive-response" method="POST" action="<?php echo $this->set_url('reports/generate'); ?>">
		<div class="form-group">
			<label>Starting From:</label>
			<input type="date" name="start_date" class="form-control" value="<?php echo date('m/d/Y'); ?>" required="required"/>
		</div>
		<div class="form-group">
			<label>Until:</label>
			<input type="date" name="end_date" class="form-control" value="<?php echo date('m/d/Y'); ?>" required="required"/>
		</div>
		<div class="form-group">
			<button class="btn btn-default submit-button">Generate Result</button>
		</div>
	</form>
</div>
<div class="col-lg-8 result-wrapper">
	<i class="fa watermark fa-tencent-weibo"></i>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){

		$('.generate-positive-response').submit( function(e){
			e.preventDefault();
			$('.submit-button').html('<i class="fa fa-spinner fa-spin"></i> Loading...')
			var url = $(this).attr('action');
			var data = $(this).serialize();

			$.post(url, data, function(response){
				$('.result-wrapper').html(response);
				$('.submit-button').html('Generate Result');
			});
		});
	});
</script>