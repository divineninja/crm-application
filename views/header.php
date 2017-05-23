<?php
if (isset($_SESSION['login_stat']) === true) {
    header('location: '. $this->set_url('activity/activityLogin'));
    die();
}
?>
<!doctype html>
<html>
    <head>
        <title>CRM | Telequest</title>
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/bootstrap.min.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/bootstrap-responsive.min.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/datepicker.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/style.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/crm-style.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/font-awesome.min.css" type="text/css" />
        <link  rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>public/css/bootstrap-multiselect.css" type="text/css" />
		<script src="<?php echo TEMPLATE_URL; ?>public/js/jquery1.8.2.min.js" type="text/javascript"></script>
		<script src="<?php echo TEMPLATE_URL; ?>public/js/jquery.cookie.js" type="text/javascript"></script>
		<!--[if IE 7]>
			<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<script>
			window.site_url = "<?php echo $this->set_url(); ?>";
            var url = "<?php echo $this->set_url('../crm-api/'); ?>";
		</script>
    </head>
    <body>
        <?php
            /*****************
                Slick menu
            ******************/
            include dirname(__FILE__).'/header/slick-menu.php';
        ?>
        <div class="row-fluid page-main-slick">
			<?php include dirname(__FILE__).'/header/main.nav.php'; ?>
		<div class="container main">
