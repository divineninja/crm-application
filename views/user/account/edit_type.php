<?php echo $this->create_form( $this->set_url('user/update_site') ); ?>
<table class="table">
	<tr>
		<td>Title: </td>
		<td><input type="text" value="<?php echo $this->item->title; ?>" name="title" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Value: </td>
		<td><input type="text" value="<?php echo $this->item->value; ?>" name="value" class="required"  required="required" /></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
<?php echo $this->end_form(); ?>