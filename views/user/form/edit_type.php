<?php echo $this->create_form( $this->set_url('user/update_object') ); ?>
<table class="table">
	<tr>
		<td>First Name: </td>
		<td><input type="text" value="<?php echo $this->item->first_name; ?>" name="first_name" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Last Name: </td>
		<td><input type="text" value="<?php echo $this->item->last_name; ?>" name="last_name" class="required"  required="required" /></td>
	</tr>	
	<tr>
		<td>Username: </td>
		<td><input type="text" value="<?php echo $this->item->username; ?>" name="username" class="required"  required="required" /></td>
	</tr>
	<tr>
		<td>Role: </td>
		<td><input type="text" value="<?php echo $this->item->role; ?>" name="role" class="required"  required="required" /></td>
	</tr>
	<tr>
		<td>Password: </td>
		<td><input type="text" name="password"/></td>
	</tr>
	<tr>
		<td>Verify Password: </td>
		<td><input type="text" name="vpassword"/></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->item->id; ?>">
<?php echo $this->end_form(); ?>