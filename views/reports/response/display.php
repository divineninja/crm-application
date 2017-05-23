<?php
if(	$this->responses){ ?>
	<form action="<?php echo $this->set_url('reports/download_response');?>" method="POST">
		<hidden class="hidden">
			<input type="text" name="start_date" value="<?php echo $this->params['start_date']; ?>" />
			<input type="text" name="end_date" value="<?php echo $this->params['end_date']; ?>"/>
		</hidden>
		<button type="submit" class="btn btn-warning start_download" title="Download CSV Format of This Report">Download CSV</button>
		<hr />
	</form>
	<?php
	}
?>
<table class="border table">
	<thead>
		<th>Code</th>
		<th>Question</th>
		<th>Positive Response</th>
		<th>Amount</th>
	</thead>
	<tbody>
<?php
	if(!$this->responses){ ?>
		<tr>
			<td colspan="4">No Result Found!</td>
		</tr>
		<?php
	} else {
		foreach ($this->responses as $key => $value) {
		?>
		<tr>
			<td><?php echo $value['code']; ?></td>
			<td><?php echo $value['question']; ?></td>
			<td><?php echo $value['total']; ?></td>
			<td><?php echo $value['amount']; ?></td>
		</tr>
		<?php
		}	
	}
?>
	</tbody>
</table>