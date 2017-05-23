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
			<li class="parent">Status <i class="fa fa-ban"></i> a</li>
				<li>Disabled</li>
				<li>Enabled</li>
				<li>Hidden</li>
				<li>Force</li>
		</ul>
	</div>
</div>
<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
	<?php
		if(Session::get('notice')) { ?>
		<div class="alert alert-success"><p><?php echo Session::get('message'); ?></p></div>
		<?php 
		unset($_SESSION['notice']);
		unset($_SESSION['message']);
		}
	?>
	<form class="submit" id="question_order" action="<?php echo $this->set_url("questions/save_order"); ?>" method="POST">
		<table class="table-striped table-bordered types">
			<thead>
				<tr>
					<th width="5%">[ ]</th>
					<th width="5%">code</th>
					<th width="5%">Type</th>
					<th width="40%">Questions</th>
					<th width="15%">Group</th>
					<th width="10%">Status</th>
					<th width="10%">Revenue</th>
					<th width="10%">order</th>
					<th width="5%">Configure</th>
				</tr>
			</thead>
			<tbody>	
			<?php
			$full_rev = 0;
			foreach ($this->items->questions as $value) {
				if($value->status != 0) {
					$full_rev = $full_rev + $value->amount;
				}
			 ?>		
				<tr>
					<td>
					<input id="item-<?php echo $value->id; ?>" type="checkbox" class="item_id" name="users" value="<?php echo $value->id; ?>" />				
					</td>
					<td><?php echo $value->code; ?></td>
					<td><?php echo $value->type; ?></td>
					<td><?php echo $value->question; ?></td>
					<td><?php echo $value->name; ?></td>
					<td><?php echo $this->show_status($value->status); ?></td>
					<td><?php echo $value->amount; ?></td>
					<td>
						<input type="hidden" class="form-control" value="<?php echo $value->id; ?>" name="id[]" />
						<input type="text" class="form-control" value="<?php echo $value->order; ?>" name="order[]" />
					</td>
					<td>
						<a title="Edit" href="<?php echo $this->set_url( "questions/edit/{$value->id}"); ?>"><i class="fa fa-pencil"></i> Edit</a> | 
						<a title="Configure" href="<?php echo $this->set_url( "questions/configure/{$value->id}"); ?>"><i class="fa fa-gear"></i></a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</form>
	<ul class="pagination">
		<?php for($i = 1; $i <= $this->items->pages; $i++) { ?> 
			<li class="<?php echo ($i == $this->items->page) ? 'active': ''; ?>"><a href="<?php echo $this->set_url("questions/get/$i"); ?>"><?php echo $i; ?></a></li>
		<?php } ?>
	</ul>
	<div class="alert alert-info">
		Maximum revenue for this campaign is <strong><?php echo $full_rev; ?></strong> for <strong><?php echo count($this->items); ?></strong> questions.
	</div>
</div>

<style>
table.types {
	width: 100%;
}
table.types tr td {
    padding: 5px 2px;
    font-size: 10px;
    text-align: center;
}
</style>