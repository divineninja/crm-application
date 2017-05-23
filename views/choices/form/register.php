<?php echo $this->create_form( $this->set_url('choices/save') ); ?>
<table class="table">
	<tr>
		<td>Label: </td>
		<td><input type="text" name="label" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Amount: </td>
		<td><input type="number" name="amount" class="required" required="required"/></td>
	</tr>
</table>
<?php echo $this->end_form(); ?>