<?php
	$user_name = $_SESSION['user_data']->first_name. ' '. $_SESSION['user_data']->last_name;
?>
<ul class="pull-right nav inline">
	<li>
		<a class="btn btn-primary pull-left top-thumb" href="<?php echo URL.$_SESSION['user_data']->name ?>">
			<small style="padding-left: 10px;">Hi <?php echo $user_name; ?>!</small>
			<img title="<?php echo $user_name; ?>" width="30" height="30" src="<?php echo URL.'public/img/default-thumb.png'; ?>" alt="..." />
		</a>	</li>	<li class="inline_li">
		<a href="<?php echo URL.'user/logout' ?>">Logout</a>
	</li>
</ul>