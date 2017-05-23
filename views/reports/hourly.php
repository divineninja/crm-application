<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li class="active">QA Hourly Report - Team</li>
</ol>
<div class="col-lg-3">
    <?php $url = $this->set_url('reports/getReportsByHour'); ?>
	<form action="<?php echo $url; ?>" method="POST" class="hourlyReport-team">
		<div class="form-group">
			<label for="from_date">Start Shift</label>
			<input type="text" data-date-format="yyyy-mm-dd" name="startDate" value="" class="form-control datepicker" id="from_date" placeholder="Select Date From">
			 <small class="description">
			    "Start Shift" must be smaller than "End Shift"
			 </small>			
		</div>
		<hr />
		<div class="form-group">
			<label for="to_date">End Shift</label>
			<input type="text" data-date-format="yyyy-mm-dd" name="endDate" value=""  class="form-control datepicker" id="end_date" placeholder="Select Date From">		
			 <small class="description">
			    "End Shift" must be higher than "Start Shift"
			 </small>		
		</div>
		<div class="form-group">
			<button class="btn btn-info">View Record</button>
			<a href="<?php echo $this->set_url() ?>" class="btn btn-default">Back</a>
		</div>
	</form>
</div>
<div class="col-lg-9">
	<div class="hourlyContainer"></div>
</div>
<div class=""></div>
<script type="text/html" id='teamHourlyApplications'>
    <% var total = 0 %>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Number of Applications</th>
            </tr>
        </thead>
        <tbody>
            <%_.each(items, function(value,key){
                total = parseInt(total) + parseInt(value.num_applications);
             %>
            <tr>
                <td><%=value.date%></td>
                <td><%=value.num_applications%></td>
            </tr>
            <% }) %>
            <tr>
                <td>Total</td>
                <td><%=total%></td>
            </tr>
        <tbody>
    </table>
    
</script>
<script src="<?php echo URL; ?>public/js/jplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.logAxisRenderer.min.js" type="text/javascript"></script>   
<script src="<?php echo URL; ?>public/js/jplot/jqplot.logAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.canvasTextRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.canvasAxisTickRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.canvasAxisLabelRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.categoryAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jplot/jqplot.highlighter.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo URL; ?>public/js/jplot/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="<?php echo URL; ?>public/js/jplot/jqplot.cursor.min.js"></script>
