<?php echo $this->create_form( $this->set_url('agent/update_object') ); ?>
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
		<td><input type="text" value="<?php echo $this->item->agent_number; ?>" name="agent_number" class="required"  required="required" /></td>
	</tr>
	<tr>
		<td>Status: </td>
		<td>
			<select name="status">
				<option value="0" <?php echo $this->set_selected(0, $this->item->status); ?>>Logged Out</option>
				<option value="1" <?php echo $this->set_selected(1, $this->item->status); ?>>Logged In</option>
				<option value="2" <?php echo $this->set_selected(2, $this->item->status); ?>>Disable</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>IP: </td>
		<td><?php echo $this->item->ip; ?></td>
	</tr>
</table>
<input type="hidden" name="agent_id" value="<?php echo $this->item->agent_id; ?>">
<?php echo $this->end_form(); ?>