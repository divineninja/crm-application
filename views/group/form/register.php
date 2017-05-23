<?php echo $this->create_form( $this->set_url('group/save') ); ?>
<table class="table">
	<tr>
		<td>Name: </td>
		<td><input type="text" name="name" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Code: </td>
		<td><input type="text" name="postalCode" class="required"  required="required" /></td>
	</tr>
	<tr>
		<td>Status: </td>
		<td>
			<select name="status" class="required" required="required" >
					<option value="0">Enable</option>
					<option value="1">Disable</option>
			</select>
		</td>
	</tr>
</table>
<?php echo $this->end_form(); ?>