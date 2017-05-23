<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	<div class="btn-group span9">
		<h3>Toolbars</h3>
		<ul class="nav nav-pills nav-stacked">
			<li>
				<a href="#" class="retrieve"><i class="fa fa-refresh"></i> Refresh Now</a>
			</li>
			<li>
				<a data-url="<?php echo $this->set_url('agent/session'); ?>" data-title="Session" id="show_add_item"><i class="fa fa-upload"></i> Set Session</a>
			</li>
			<li>
				<a href="<?php echo $this->set_url(''); ?>"><i class="fa fa-mail-reply"></i> Back</a>
			</li>
		</ul>
	</div>
</div>
<div class="col-lg-9 statistics-list"></div>

<script>
jQuery(document).ready(function(){
	
	window.retrieve_url = "<?php echo $this->set_url("agent/live_monitor?source={$_GET['url']}"); ?>";
	window.orig_url = "<?php echo $this->set_url("agent/live_monitor?source={$_GET['url']}"); ?>";
	
	setInterval(function(){
		window.retrieve_data();
	},5000);
	
	$('.retrieve').click(function(e){
		e.preventDefault();
		window.retrieve_data();
	});
	
	window.retrieve_data = function(url){
		if(url){
			var local_retrieve_url = url;
			console.log('yes')
		}else{
			var local_retrieve_url = window.retrieve_url;
		}
		$.ajax({
			url: local_retrieve_url,
			type: 'GET',
			success: function(response){
				$('.statistics-list').html(response);
			},
			done: function(done){
			},
			error: function(error){
				console.log(error)
			}
		})
	}
	
	window.retrieve_data();
})
</script>