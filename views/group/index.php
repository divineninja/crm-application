<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a data-url="<?php echo URL; ?>group/register" data-title="Type" id="show_add_item"><i class="fa fa-plus"></i> Add</a>
			</li>		
			<li>
				<a data-url="<?php echo URL; ?>group/edit_item" class="edit_item"><i class="fa fa-edit"></i> Edit</a>
			</li>
			<li>
				</i><a data-url="<?php echo URL; ?>group/delete_item" class="delete_item"><i class="fa fa-times"></i> Delete</a>
			</li>
			<li><a href="<?php echo $this->set_url(); ?>"><i class="fa fa-mail-reply"></i>  Back</a></li>
		</ul>
	</div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
	<div class="span3 page-title">
		<h3>Groups</h3>
	</div>
	<table class="table dataTable table-striped table-bordered types">
		<thead>
			<tr>
				<th>[ ]</th>
				<th>Name</th>
				<th>Code</th>
			</tr>
		</thead>
		<tbody>	
		<?php foreach ($this->items as $value) { ?>		
			<tr>
				<td><input id="item-<?php echo $value->group_id; ?>" type="checkbox" class="item_id" name="users" value="<?php echo $value->group_id; ?>" /></td>
				<td><?php echo $value->name; ?></td>
				<td><?php echo $value->postalCode; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>