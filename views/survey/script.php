<script>
jQuery(document).ready(function(){
	$('#phone-number').val('<?php echo $this->phone; ?>');
	$('.find-customer').trigger('click');
})
</script>