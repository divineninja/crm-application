<div class="col-lg-12">
	<h3>Select Postal Code</h3>
	<form action="<?php echo $this->set_url("postal/question/{$this->id}"); ?>" method="POST" class="crm-submit-item">
		<div class="col-lg-12">
			<div class="col-lg-offset-11">
				<button type="submit" class="btn btn-info">Save</button>
			</div>
		</div>
		<?php $this->set_up_postal_codes($this->postal,$this->post_codes);?>
		<div class="col-lg-12">
			<div class="col-lg-offset-11">
				<button type="submit" class="btn btn-info">Save</button>
			</div>
		</div>
	</form>
</div>
<script>
jQuery(document).ready(function(){
	$('.parent').change(function(){
		var checked = $(this).attr('checked');
		if(checked){
			$(this).parent().parent().find('input[type="checkbox"]').attr('checked','checked');
		}else{
			$(this).parent().parent().find('input[type="checkbox"]').removeAttr('checked');
		}
	});
});
</script>