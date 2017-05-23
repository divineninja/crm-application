<?php echo $this->create_form( $this->set_url('postal/bulk_upload') ); ?>
<table class="table">
	<tr>
		<td>Name: </td>
		<td>
			<input type="text" name="name" class="required" required="required" />
			<p><small>Add post codes here, separate by space</small></p>
		</td>
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
	<tr>
		<td>Status: </td>
		<td>
			<select name="parent" class="required" required="required" >
				<option value="0">No Parent</option>
				<?php foreach($this->items as $parent){ ?>
				<option value="<?php echo $parent->postal_id; ?>"><?php echo $parent->name; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
</table>
<?php echo $this->end_form(); ?>