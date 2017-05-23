<?php
	$question = $this->question;
	$responses = (isset($this->responses[$question->id])) ? $this->responses[$question->id]: 0;
	if (( $responses <= $question->max_apps) || $question->max_apps == 0) {
		$logic_role = (in_array($question->id, $this->parentWlogic)) ? 'parent': 'child';
?>
	<script type="text/javascript">
		var ids = '';
		<?php
		$count = count($this->parentRule);
		$i = 1	;
		foreach ($this->parentRule as $key => $value) {

			$separator = ($i < $count) ? '|': '';

			$display = $value->reference_question.'&'.$value->answer.$separator;

			?>
			var ids = ids + "<?php echo $display; ?>"
		<?php 
		$i++;
		}
		?>
		var question = ids.split('|');

		jQuery.each(question, function(key, value){
			var info = value.split('&');
			var el = jQuery('.question-'+info[0]);
			if(el) {
				var answer = el.find('.select_answer').val();
				if (answer != info[1]) {
					jQuery('.question-<?php echo $question->id; ?>').remove();
				}	
			}
		});
	</script>