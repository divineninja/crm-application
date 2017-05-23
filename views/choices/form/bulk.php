<?php echo $this->create_form( $this->set_url('choices/bulk_upload') ); ?>
<table class="table">
	<tr>
		<td>Name: </td>
		<td>
			<input type="text" name="name" class="required" required="required" />
			<p><small>Add post codes here, separate by space</small></p>
		</td>
	</tr>
</table>
<?php echo $this->end_form(); ?>