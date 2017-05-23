<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a data-url="<?php echo URL; ?>questions/register" data-title="Question" id="show_add_item"><i class="fa fa-plus"></i> Add</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>questions/edit_item" class="edit_item"><i class="fa fa-edit"></i> Edit</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>questions/delete_item" class="delete_item"><i class="fa fa-times"></i> Delete</a>
			</li>
			<li><a href="#" class="save_order"><i class="fa fa-save"></i> Save</a></li>
			<li><a href="<?php echo $this->set_url(); ?>"><i class="fa fa-mail-reply"></i>  Back</a></li>
		</ul>
		
		
		<h3>Filter</h3>
		<ul class="nav nav-pills nav-stacked">
			<li class="parent">Status <i style="color: red;" class="fa fa-ban clear_filter pull-right right" title="Clear filter"></i></li>
			<li><a href="#" class="set_status" data-status="1">Enabled</a></li>
			<li><a href="#" class="set_status" data-status="0">Disabled</a></li>
			<li><a href="#" class="set_status" data-status="2">Hidden</a></li>
			<li><a href="#" class="set_status" data-status="3">Force</a></li>
			
		</ul>
	</div>
</div>
<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
	<div class="search-for-key">
		<div class="col-lg-4">
			<input type="search" data-val="code" class="code-key form-control col-lg-3" placeholder="code" />
		</div>
		<div class="col-lg-4">
			<input type="search" data-val="type" class="type-key form-control col-lg-3" placeholder="type" />
		</div>
		<div class="col-lg-4">
			<input type="search" data-val="question" class="question-key form-control col-lg-3" placeholder="Question" />
		</div>
	</div>
	<div class="question-table-wrapper"></div>
</div>

<style>
table.types {
	width: 100%;
}
table.types tr th,
table.types tr td {
    padding: 5px 2px;
    font-size: 10px;
    text-align: center;
}
</style>

<script>
jQuery(document).ready(function($){
	
	var questions = {
		
		page: 1,
		status: '',
		
		params: {
			code: '',
			question: '',
			type: '',
		},
		
		init : function()
		{
			
		},
		
		fetch: function()
		{
			console.log(this)
			var url = site_url + 'questions/get';
			
			if(this.page){
				url = url + '/' + this.page;
			}
			
			//if(this.key || this.status || this.status == 0) {
			var details = $.param(this.params);

			url = url + '?key=' + btoa(details) +'&status='+this.status;
			
			
			//}
			
			$('.question-table-wrapper').find('table').css('opacity', 0.5)
			
			$.get(url, function(response){
				$('.question-table-wrapper').html(response);
				$('.question-table-wrapper').find('table').css('opacity', 1);
			})
		},
		
		transferPage: function(page)
		{
			this.page = page;
			questions.fetch();
		},
		
		searchCode: function(field,key)
		{
			this.params[field] = key;
			questions.fetch();
		},
		
		filter: function(status)
		{
			this.status = status;
			questions.fetch();
		},
		
		clear_filter: function()
		{
			this.status = '';
			questions.fetch();
		}
		
	}
	
	questions.fetch();
	
	$(document).on('click', '.paginate', function(e){
		e.preventDefault();
		questions.transferPage($(this).data('page'));
	});
	$(document).on('click', '.set_status', function(e){
		e.preventDefault();
		var key = $(this).data('status');
		questions.filter(key);
	});
	
	$(document).on('click', '.clear_filter', function(e){
		e.preventDefault();
		questions.clear_filter();
	});
	
	$(document).on('keyup', '.code-key', function(e){
		e.preventDefault();
		var key = $(this).val();
		questions.searchCode('code',key);
	});
	
	$(document).on('keyup', '.type-key', function(e){
		e.preventDefault();
		var key = $(this).val();
		questions.searchCode('type',key);
	});
	
	
	$(document).on('keyup', '.question-key', function(e){
		e.preventDefault();
		var key = $(this).val();
		questions.searchCode('question',key);
	});
})
</script>