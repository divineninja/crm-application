<ol class="breadcrumb">
  <li><a href="<?php echo $this->set_url(); ?>">Home</a></li>
  <li><a href="#">Agent</a></li>
  <li class="active">Survey Form</li>
  <?php 
  $manual = (int)$this->get_site_meta('manual_opt');
  if( $manual ) { ?>
  <li><a href="#" class="enable-manual">Manual Input</a></li>
   <?php } ?>
  <li class="pull-right "><a href="<?php echo $this->set_url('user/logout'); ?>" class="enable-manual">Logout</a></li>

</ol>