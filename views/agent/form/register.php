<?php echo $this->create_form( $this->set_url('agent/save') ); ?>
<table class="table">
	<tr>
		<td>First Name: </td>
		<td><input type="text" name="first_name" class="required" required="required" /></td>
	</tr>                      
	<tr>                       
		<td>Last Name: </td>   
		<td><input type="text"  name="last_name" class="required"  required="required" /></td>
	</tr>	                   
	<tr>                       
		<td>Username: </td>      
		<td><input type="text" name="agent_number" class="required"  required="required" /></td>
	</tr> 
</table>
<?php echo $this->end_form(); ?>