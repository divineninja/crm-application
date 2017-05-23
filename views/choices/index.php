<div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a data-url="<?php echo URL; ?>choices/register" data-title="Type" id="show_add_item"><i class="fa fa-plus"></i> Add</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>choices/bulk" class="show_add_item"><i class="fa fa-upload"></i> Bulk add</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>choices/edit_item" class="edit_item"><i class="fa fa-edit"></i> Edit</a>
			</li>
			<li>
				</i><a data-url="<?php echo URL; ?>choices/delete_item" class="delete_item"><i class="fa fa-times"></i> Delete</a>
			</li>
			<li><a href="<?php echo $this->set_url(); ?>"><i class="fa fa-mail-reply"></i>  Back</a></li>
		</ul>
	</div>
</div>
<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
	<ul class="pagination">
		<?php for($i = 1; $i <= $this->items->pages; $i++) { ?> 
			<li class="<?php echo ($i == $this->items->page) ? 'active': ''; ?>"><a class="paginate" href="<?php echo $this->set_url("choices/index/$i"); ?>"><?php echo $i; ?></a></li>
		<?php } ?>
	</ul>
	<div class="search-for-key">
		<div class="input-group">
			<input class="form-control search-key" value="<?php echo isset($_GET['key']) ? $_GET['key']: ''; ?>" placeholder="Search" aria-describedby="basic-addon2"> <span class="input-group-addon" id="execute-search">Go</span>
		</div>
	</div>
	<table class="table-striped table-bordered types" width="100%">
		<thead>
			<tr>
				<th>[ ]</th>
				<th>Name</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>	
		<?php foreach ($this->items->choices as $value) { ?>		
			<tr>
				<td><input id="item-<?php echo $value->choices_id; ?>" type="checkbox" class="item_id" name="users" value="<?php echo $value->choices_id; ?>" /></td>
				<td><?php echo $value->label; ?></td>
				<td><?php echo number_format((float)$value->amount, 2, '.', ''); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<ul class="pagination">
		<?php for($i = 1; $i <= $this->items->pages; $i++) { ?> 
			<li class="<?php echo ($i == $this->items->page) ? 'active': ''; ?>"><a class="paginate" href="<?php echo $this->set_url("choices/index/$i"); ?>"><?php echo $i; ?></a></li>
		<?php } ?>
	</ul>
</div>

<script>
jQuery(document).ready(function(){
	
	$(document).on('click','#execute-search', function(){
		
		var url = 'http://' + window.location.host + window.location.pathname;
		var key = $('.search-key').val();
		window.location = url + '?key='+ key;
		
	});
	
})
</script>
<style>
#execute-search {
	cursor: pointer;
}
table.table-striped.table-bordered.types {
    text-align: left;
}
table.table-striped.table-bordered.types tr th,
table.table-striped.table-bordered.types tr td {
	padding: 5px 10px;
}
</style>