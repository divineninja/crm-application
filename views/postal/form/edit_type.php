<?php echo $this->create_form( $this->set_url('postal/update_object') ); ?>
<table class="table">
	<tr>
		<td>Name: </td>
		<td><input type="text" value="<?php echo $this->item->name; ?>" name="name" class="required" required="required" /></td>
	</tr>
	<tr>
		<td>Postal Code: </td>
		<td><input type="text" value="<?php echo $this->item->postalCode; ?>" name="postalCode" class="required"  required="required" /></td>
	</tr>	
	<tr>
		<td>Status: </td>
		<td>
			<select name="status" class="required" required="required" >			
				<option <?php echo $this->set_selected('0',$this->item->status) ?> value="0">Disable</option>
				<option <?php echo $this->set_selected('1',$this->item->status) ?> value="1">Enable</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Status: </td>
		<td>
			<select name="parent" class="required" required="required" >
				<option value="0">No Parent</option>
				<?php foreach($this->items as $parent){ ?>
				<option  <?php echo $this->set_selected($parent->postal_id,$this->item->parent) ?> value="<?php echo $parent->postal_id; ?>"><?php echo $parent->name; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
</table>
<input type="hidden" name="postal_id" value="<?php echo $this->item->postal_id; ?>">
<?php echo $this->end_form(); ?>