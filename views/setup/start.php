<div class="jumbotron">
	
	<div class="setup-start">
		<h2>Database Setup</h2>
		<p>Click the button below if you want to start the setup.</p>
		<button class="btn btn-info start-setup">Start Setup</button>
	</div>
	<div class="setup-finish" style="display: none;">
		<h2>Database Set-up Finished</h2>
		<p>Set-up is successful, you will be redirected to the main page in 5 seconds.</p>
		<button class="btn btn-info start-setup">Start Setup</button>
	</div>
	<div class="setup-starting" style="display: none;">
		<h2><i class="fa fa-spinner fa-spin"></i> Setup is starting....</h2>
		<p>Please be patient while the system is fixing your database.</p>
		<p class="alert-danger alert"><small>IMPORTANT: do not refresh or close your browser during the setup.</small></p>
	</div>
	
</div>
<script>
	jQuery(document).ready(function($){
		$('.start-setup').click(function(){
			$('.setup-start').addClass('hide');
			$('.setup-starting').fadeIn();
			$.get("<?php echo $this->set_url('setup/start_setup'); ?>",function(response){
				$('.setup-starting').hide();
				$('.setup-finish').fadeIn();
				setTimeout( function(){
					window.location = "<?php echo $this->set_url(); ?>";
				},5000)
			},'json');
		});
		
	});
</script>