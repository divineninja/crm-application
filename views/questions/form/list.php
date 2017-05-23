<table class="table dataTable table-striped table-bordered types">
			<thead>
				<tr>
					<th>Question</th>
					<th>Operator</th>
					<th>Answer</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>	
			<?php foreach($this->referene_questions as $reference){ ?>
				<tr>
					<td><?php echo $reference->reference_statement; ?></td>
					<td><?php echo $reference->operator; ?></td>
					<td><?php echo $reference->answer_label; ?></td>
					<td><a class="delete-condition-question" href="<?php echo $this->set_url("questions/remove_follow_up_question_validation/{$reference->id}"); ?>">Delete</a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>