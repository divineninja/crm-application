<?php echo $this->create_form( $this->set_url('choices/update_object') ); ?>
<table class="table">
	<tr>
		<td>Label: </td>
		<td><input type="text" value="<?php echo $this->item->label; ?>" name="label" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Amount: </td>
		<td><input type="number" value="<?php echo $this->item->amount; ?>" name="amount" class="required" required="required"/></td>
	</tr>
</table>
<input type="hidden" name="choices_id" value="<?php echo $this->item->choices_id; ?>">
<?php echo $this->end_form(); ?>