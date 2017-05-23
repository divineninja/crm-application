<table class="table table-bordered">
	<thead>
		<tr>
			<th>ID #</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<body>
	<?php
	if ($this->children) {
		foreach ($this->children as $key => $value) { ?>
		<tr>
			<td><?php echo $value->coaching_id; ?></td>
			<td>
				<a class="modal-change" data-url="<?php echo $this->set_url("advise/edit/{$value->id}") ?>">Edit</a> |
				<a class="show_confirm" href="<?php echo $this->set_url("advise/delete/{$value->id}") ?>">Delete</a>
			</td>
		</tr>
	<?php } 	
	} else { ?>
	<tr>
		<td colspan="2">No record found. Click "<a data-url="<?php echo $this->set_url("advise/followup/{$this->id}") ?>" class="modal-change">Add New</a>"</td>
	</tr>
	<?php }
	?>
	</body>
</table>
<a class="btn btn-small btn-info modal-change" data-url="<?php echo $this->set_url("advise/followup/{$this->id}") ?>"><i class="fa fa-plus"></i> Followup</a>