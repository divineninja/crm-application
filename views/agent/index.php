<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a data-url="<?php echo URL; ?>agent/register" data-title="Type" id="show_add_item"><i class="fa fa-plus"></i> Add</a>
			</li>
			<li>
				<a data-url="<?php echo URL; ?>agent/edit_item" class="edit_item"><i class="fa fa-edit"></i> Edit</a>
			</li>
			<li>
				</i><a data-url="<?php echo URL; ?>agent/delete_item" class="delete_item"><i class="fa fa-times"></i> Delete</a>
			</li>
			<li><a href="<?php echo $this->set_url(); ?>"><i class="fa fa-mail-reply"></i>  Back</a></li>
		</ul>
	</div>
</div>
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
	<div class="span3 page-title">
		<h3>Agent</h3>
	</div>
	<table class="table dataTable table-striped table-bordered types">
		<thead>
			<tr>
				<th>[ ]</th>
				<th>Name</th>
				<th>Usename</th>
				<th>IP</th>
				<th>Last Login</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>	
		<?php foreach ($this->items as $value) { 
			
			$class = "";
			 
			if($value->status == 0)
			{
				$class = " fa-toggle-off color-grey";
			} else if ($value->status == 1) {
				$class = " fa-toggle-on color-green";
			} else {
				$class = " fa-toggle-on color-red";
			}
		?>		
			<tr>
				<td><input id="item-<?php echo $value->agent_id; ?>" type="checkbox" class="item_id" name="users" value="<?php echo $value->agent_id; ?>" /></td>
				<td><?php echo $value->first_name.' '.$value->last_name; ?></td>
				<td><?php echo $value->agent_number; ?></td>
				<td><?php echo $value->ip; ?></td>
				<td><?php echo date('d M h:i:s a', strtotime($value->last_login)); ?></td>
				<td><i class="fa <?php echo $class; ?>"></i></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>