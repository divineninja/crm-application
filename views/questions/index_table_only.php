<form class="submit" id="question_order" action="<?php echo $this->set_url("questions/save_order"); ?>" method="POST">
		<table class="table-striped table-bordered types">
			<thead>
				<tr>
					<th width="5%">[ ]</th>
					<th width="5%">code</th>
					<th width="5%">Type</th>
					<th width="40%">Questions</th>
					<th width="15%">Group</th>
					<th width="5%">Status</th>
					<th width="10%">Revenue</th>
					<th width="9%">order</th>
					<th width="8%">Configure</th>
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
			<li class="<?php echo ($i == $this->items->page) ? 'active': ''; ?>"><a class="paginate" href="#" data-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
		<?php } ?>
	</ul>
	<div class="alert alert-info">
		Maximum revenue for this campaign is <strong><?php echo $full_rev; ?></strong> for <strong><?php echo count($this->items->questions); ?></strong> questions.
	</div>