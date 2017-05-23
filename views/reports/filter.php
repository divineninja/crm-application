<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li class="active">Fitler</li>
</ol>
<div class="col-lg-3">
    <?php $url = $this->set_url('reports/phoneCollectionFilter'); ?>
	<form action="<?php echo $url; ?>" method="POST" class="filter-report">
		<div class="form-group">
			<label for="quetions">Post code</label>
			<input type="text" name="post-code" placeholder="post code" class="form-control"/>
		</div>
		<div class="input-groups">
			<div class="form-group">
				<label for="quetions">Select Questions</label>
				<select name="questions[]" class="form-control question-select">
					<option value="">- Select -</option>
					<?php foreach ($this->questions as $key => $value) { ?>
						<option value="<?php echo $value['id']; ?>" data-choices="<?php echo $value['choices']; ?>"><?php echo $value['code']; ?>: <?php echo $value['questions']; ?></option>
					<?php } ?>
				</select>
				<small class="description">Qcode: Question Statement</small>			
			</div>
			<div class="form-group">
				<label for="choices">Select Choices</label>
				<select name="choices[]" class="form-control choices-select">
					<option value="">- Select -</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<span class="btn btn-info duplicate"><i class="fa fa-plus"></i></span>
		</div>
		<div class="input-duplicate"></div>
		<div class="form-group">
			<button class="btn btn-info"><i class="fa fa-filter"></i> Filter</button>
			<a href="<?php echo $this->set_url() ?>" class="btn btn-default">Back</a>
		</div>
	</form>
</div>
<div class="col-lg-9">
	<div class="filterApps"></div>
</div>
<script type="text/html" id='filterAppsTemplate'>
	<div class="col-lg-12">
		<ul class="pagination pagination-sm">
			<li><a title="download" disabled='disabled' class="download"><i class="fa fa-download"></i></a></li>
			<% for(var i = 0; i < items.NumberOfPages; i++) { %>
				<li class="paginate" data-value="<%=i+1%>"><a href="#"><%=i+1%></a></li>
			<% } %>
			<li> <span><%=items.totalResult%> Results</span></li>
		</ul>
	   
	</div>
	<br />
	<div class="col-lg-12">
		<table class="table">
			<thead>
				<tr>
					<th>Phone</th>
				</tr>
			</thead>
			<tbody>
			    <%_.each(items.phonenumbers, function(key, value){ %>
			    	<tr>
			    		<td><%=key.phone%></td>
			    	</tr>
			    <%});%>
	    	</tbody>
	    </table>
    </div>
</script>
<script>
jQuery(document).ready( function(){
	jQuery(document).on('change', '.question-select', function() {

		var choices = $('option:selected', this).attr('data-choices');

		var stringChoices = atob(choices);

		var objChoices = jQuery.parseJSON(stringChoices);
		var myChoices = $(this).parent().parent().find('.choices-select');
		$(myChoices).html('<option value="">- Select -</option>');

		$.each(objChoices, function(){
			$(myChoices).append('<option value='+this.choices_id+'>'+this.label+'</option>')
		});
	});

	$(document).on('click', '.duplicate', function() {
		var content = $('.input-groups').html();
		var removeButton = '<div class="form-group"><span class="btn btn-danger removeDuplicate"><i class="fa fa-times"></i></span></div>';
		$('.input-duplicate').append('<div class="form-duplicate">'+content+removeButton+'</div>');
	});

	$(document).on('click', '.removeDuplicate', function() {
		$(this).parent().parent().fadeOut( function() {
			$(this).remove();
		})
	});

	$(document).on('click', '.download', function() {

		window.location = '<?php echo $this->set_url("reports/downloadFilter"); ?>/?'+filterData;
	});

	$(document).on('click', '.paginate', function(e) {
		e.preventDefault();
		var url = $('.filter-report').attr('action');
		var page = $(this).data('value');
		var template = $('#filterAppsTemplate').html();	
		$(".filterApps").html('Loading content please wait!');

	    $.post(url+'?page='+page, filterData, function(response){
	        $(".filterApps").html(_.template(template,{items:response}));
		},'json');
	});

	$(document).on('submit','.filter-report', function(e){

	    e.preventDefault();
	    window.filterData = $(this).serialize();
	    var template = $('#filterAppsTemplate').html();		
	    var url = $(this).attr('action');

	    $(".filterApps").html('Loading content please wait!');

	    $.post(url, filterData, function(response){
	        $(".filterApps").html(_.template(template,{items:response}));
		},'json');
	});

});
</script>