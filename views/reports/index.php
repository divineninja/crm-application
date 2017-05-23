<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li class="active">QA CRM</li>
</ol>
<div class="col-lg-3">
	<form action="<?php echo $this->set_url('reports/raw_applications'); ?>" method="GET" class="get_raw_applications">
		<div class="form-group">
			<label for="from_date">Start Date</label>
			<input type="text" data-date-format="yyyy-mm-dd" name="from_date" value="<?php echo $this->current_date; ?>" class="form-control datepicker" id="from_date" placeholder="Select Date From">			
		</div>
		<div class="form-group">
			<label for="from_time">Start Time</label>
			<input type="text" class="form-control" name="from_time" id="from_time" placeholder="11:00" value="22:00">		
		</div>
		<hr />
		<div class="form-group">
			<label for="to_date">End Date</label>
			<input type="text" data-date-format="yyyy-mm-dd" name="to_date" value="<?php echo $this->to_date; ?>"  class="form-control datepicker" id="end_date" placeholder="Select Date From">			
		</div>
		<div class="form-group">
			<label for="to_time">End Time</label>
			<input type="text" class="form-control" name="to_time"  id="end_time" placeholder="4:00" value="4:00">		
		</div>
		<div class="form-group">
			<button class="btn btn-info">View Record</button>
			<a href="<?php echo $this->set_url() ?>" class="btn btn-default">Back</a>
		</div>
	</form>
</div>
<div class="col-lg-9">
	<div class="realtime_content"></div>
	<script type="text/html" id='raw_applications'>
	<%console.log(items)%>
	<table class="table">
			<thead>	
				<tr>
					<th>Name</th>
					<th>Username</th>
					<th>Applications</th>
					<th>Status</th>
					<th>Validator</th>
					<th>Validation</th>
					<th>Date</th>
					<th>Revenue</th>
					<th>Validate</th>
				</tr>
			</thead>
			<tbody>
				<tbody>
				<% var count = 0; var rev = 0; %>
				<% if( !items.length ){ %> 
					<tr>
						<td colspan="3">No Data Found.</td>
					</tr>
				<% }else{ %>	
				<% 
				var complete_count = 0;
				var incomplete_count = 0;
				
				_.each(items, function(value,key){
					
					count++;
					rev =  parseFloat(rev) + parseFloat(value.revenue);
				
					var validator = (value.validation_status == "Not Validated" || value.validation_status == "" || value.validation_status == "on going survey") ? "": value.agent_f_name + " " + value.agent_l_name;
					var completion_status = (value.status == 1 ) ? "Complete": "Incomplete";
						
						if(value.status == 1)
						{
							completion_status = "Complete";
							complete_count++;
						}else if(value.status == 0) {
							completion_status = "Incomplete";
							incomplete_count++;
						} else {
							completion_status = "No Status";
						}
						
					var status = value.validation_status;
					status = status.replace(/ /g, "_");
					%>				
					<tr <% if(value.validation_status){ %> class="<%=status%>"<% } %>>
						<td><a data-agentid="<%=value.ag_id%>" data-title="<%=value.first_name%> <%=value.last_name%>'s Application" class="view-user-applications" data-url="<?php echo $this->set_url("reports/appplication/"); ?><%=value.ag_id%>" href="#"><%=value.first_name%> <%=value.last_name%></a></td>
						<td><%=value.agent_number%></td>
						<td><%=value.phone%></td>
						<td><%=validator%></td>
						<td><%=completion_status%></td>
						<td>
						<% if(value.validation_status){ %>
							 <%=value.validation_status%>
						<% }else{ %>
							Not validated
						<% } %>
						</td>
						<td><%=value.app_date%></td>
						<td><%=value.revenue%></td>
						<td>
							<a target="_blank" href="<?php echo $this->set_url('reports/qa_crm')?>/<%=value.ag_id%>">Validate</a> |
							<a class="delete-item" href="<?php echo $this->set_url('reports/delete_application')?>/<%=value.ag_id%>">Delete</a>
						</td>
					</tr>
				<% 
					})
					
					console.log("incomplete "+ incomplete_count);
					console.log("<br /> complete "+ complete_count);
				}
				%>
				</tbody>
			</tbody>
		</table>
		<div class="col-lg-3">
			<p><%=count%> Applications</p>
		</div>
		<div class="col-lg-3">
			<p>&pound; <%=rev%></p>
		</div>
		<div class="col-lg-3"><a href="<?php echo $this->set_url('reports/download'); ?>?<%=$.param(output_info)%>" class="btn btn-info btn-download"><i class="fa fa-download"></i> Download Report</a></div>
		<div class="col-lg-3"><a href="<?php echo $this->set_url('reports/download_xfactor'); ?>?<%=$.param(output_info)%>" class="btn btn-default btn-download"><i class="fa fa-download"></i> Download with 25% revene</a></div>
	</script>
</div>
<script>

</script>