<div class="row">
	<div class="col-lg-12">
		<div class="form-group col-lg-6">
			<label for="exampleInputEmail1">From</label>
			<input type="date" name="dateStart" id="dateStart" class="form-control" value=""/>
		</div>
		<div class="form-group col-lg-6">
			<label for="exampleInputEmail1">To</label>
			<input type="date" name="dateEnd" id="dateEnd" class="form-control" value=""/>
		</div>
	</div>
</div>

<div style="display: inline-block; width: 100%;">
	<div class="col-lg-12">
		<p class="alert alert-warning">
			<span>Save "Changes button" is not working. Please use the button below.</span>
		</p>
	</div>
</div>
<a class="btn btn-info btn-small download_report" name="download" href="<?php echo $this->set_url("advise/download");?>">
	<span>Download</span>
</a>

<script>
jQuery(document).ready(function($) {
	$('.download_report').click( function(e) {
		e.preventDefault();

		var dateStart = $('#dateStart').val();
		var dateEnd = $('#dateEnd').val();
		var url = $(this).attr('href');

		if (dateStart != '' && dateEnd != '') {
			window.location = url+'?dateStart='+dateStart+'&dateEnd='+dateEnd;
		} else {
			alert('Incomplete data!');
		}
	});
});
</script>