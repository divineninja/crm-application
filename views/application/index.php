<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li class="active">Revenue</li>
</ol>
<div class="col-lg-3">
	<form action="<?php echo $this->set_url('application/revenue'); ?>" method="GET" class="execute_query">
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
	<script type="text/html" id='realtime_feeds'>
		<table class="table">
			<thead>	
				<tr>
					<th>Name</th>
					<th>Applications</th>
					<th>Revenue</th>
				</tr>
			</thead>
			<tbody>
				<tbody>
				<% if( !items.length ){ %> 
					<tr>
						<td colspan="3">No Data Found.</td>
					</tr>
				<% }else{ %>	
				<% _.each(items, function(value,key){ %>				
					<tr>
						<td><a data-agentid="<%=value.agent_id%>" data-title="<%=value.first_name%> <%=value.last_name%>'s Application" class="view-application" data-url="<?php echo $this->set_url("application/get_user_application?"); ?>id=<%=value.agent_id%>" href="#"><%=value.first_name%> <%=value.last_name%></a></td>
						<td><%=value.number_of_applications%></td>
						<td><%=value.revenue%></td>
					</tr>
				<% 
					})
				}
				%>
				</tbody>
			</tbody>
		</table>
	</script>
</div>