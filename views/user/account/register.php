<?php echo $this->create_form( $this->set_url('user/save_site') ); ?>
<table class="table">
	<tr>
		<td>Title: </td>
		<td><input type="text" name="title" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Value: </td>
		<td><input type="text" name="value" class="required"  required="required" /></td>
	</tr>
</table>
<?php echo $this->end_form(); ?>