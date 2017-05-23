<?php 
if( !isset($_SESSION['logged_in'] ) ){ ?>
    <div class="col-lg-3 col-lg-offset-5">
        <a class="btn btn-large btn-default" href="<?php echo URL.'survey' ?>">Agent</a>
        <a class="show-login-form btn btn-large btn-success" href="<?php echo URL.'user/show_login_form' ?>">Administrator</a>
    </div>
<?php }else if($_SESSION['role'] == 'agent') { ?>
    <script>
        window.location = site_url+'survey'
    </script>
<?php }else if($_SESSION['role'] == 'viewer') { ?>
    <script>
        window.location = '<?php echo "http://{$_SERVER['HTTP_HOST']}/crm-api/frontend/" ?>';
    </script>
 <?php }else{ ?>
    <div style="text-align:center;">
        <p>You are currently logged in as <strong>"<?php echo $_SESSION['user_data']->role; ?>"</strong></p>
        <h1>Welcome <?php echo $_SESSION['user_data']->first_name.' '.$_SESSION['user_data']->last_name; ?></h1>
    </div>
    <?php require('administrator/index.php') ?>
<?php } ?>