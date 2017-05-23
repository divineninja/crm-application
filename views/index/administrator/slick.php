<?php
$role = strtolower($_SESSION['role']);

// print_r($this->get_menus);

if($role != 'agent'){
?><?php $menu = $this->get_menus(); ?>
<?php if( $menu ) { ?>
<div class="admin-main-page">
<?php foreach($menu as $key => $main): ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<h3><i class="fa fa-<?php echo $main->parent_icon; ?>"></i> <?php echo $main->parent_label; ?></h3>
			<?php if($main->child): ?>
				<ul>
				<?php foreach($main->child as $parent): ?>
					<li class="parent">
						<a href="<?php echo $this->set_url($parent->url); ?>">
						<i class="fa fa-<?php echo $parent->icon; ?>"></i> <?php echo $parent->name; ?></a>
						<?php if($parent->child): ?>
							<ul class="child">
								<?php foreach($parent->child as $child): ?>
								 <li><a href="<?php echo $this->set_url($child->url); ?>">
									<i class="fa fa-<?php echo $child->icon; ?>"></i> <?php echo $child->name; ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
	</div>
<?php endforeach; ?>
</div>
<?php } else {
?>
<div class="admin-main-page">
<?php if ($this->can_access($role)) { ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><i class="fa fa-wrench"></i> Maintenance</h3>
        <ul>
            <li><a href="<?php echo $this->set_url(); ?>">
                <i class="fa fa-dashboard"></i> Dashboard</a>
            </li><li><a href="<?php echo $this->set_url('group'); ?>">
                <i class="fa fa-group"></i> Groups</a>
            </li> 
            <li class="parent"><a href="#">
                <i class="fa fa-location-arrow"></i> Survey</a>
                <ul class="child">
                    <li><a href="<?php echo $this->set_url('questions'); ?>">
                        <i class="fa fa-question"></i> Questions</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('choices'); ?>">
                        <i class="fa fa-pencil"></i> Choices</a>
                    </li>
                </ul>
            </li>           
        </ul>
    </div>
<?php } ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><i class="fa fa-filter"></i> Reports</h3>
        <ul>
            <li class="parent">
                <a href="<?php echo $this->set_url('reports'); ?>">
                    <i class="fa fa-puzzle-piece"></i> Application QA
                </a>
                <ul class="child">
                    <li><a href="<?php echo $this->set_url('reports'); ?>">
                        <i class="fa fa-keyboard-o"></i> Reports</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('reports/positive_response'); ?>">
                        <i class="fa fa-plus"></i> Positive Response</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('advise'); ?>">
                        <i class="fa fa-institution"></i> Coaching</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('duplicate'); ?>">
                        <i class="fa fa-copy"></i> Duplicate</a>
                    </li>
                </ul>
            </li>
            <li class="parent">
                <a href="#">
                    <i class="fa fa-laptop"></i> Monitoring
                </a>
                <ul class="child">
                    <li><a href="<?php echo $this->set_url('reports/hourly'); ?>">
                        <i class="fa fa-clock-o"></i> Hourly Applications</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('reports/filter'); ?>">
                        <i class="fa fa-filter"></i> Filter</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('reports/agent'); ?>">
                        <i class="fa fa-cubes"></i> Hourly Agent</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('agent/monitoring'); ?>">
                        <i class="fa fa-smile-o"></i> Real-time Agent</a>
                    </li>
					
                    <li><a href="<?php echo $this->set_url('agent/monitoringv2'); ?>">
                        <i class="fa fa-smile-o"></i> Real-time - Revenue</a>
                    </li>
                    <li><a href="<?php echo $this->set_url('application'); ?>">
                        <i class="fa fa-money"></i> Application Revenue</a>
                    </li>
                </ul>
            </li>
            <li><a href="<?php echo $this->set_url('reports'); ?>">
                <i class="fa fa-tasks"></i> Raw Revenue</a>
            </li>
        </ul>
    </div>

    <?php if ($this->can_access($role)) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3><i class="fa fa-cogs"></i> Accounts</h3>
            <ul>
                <li class="parent">
                    <a href="#">
                        <i class="fa fa-users"></i> People</a>
                    <ul class="child">
                        <li><a href="<?php echo $this->set_url('agent'); ?>">
                        <i class="fa fa-eye-slash"></i> Agents</a>
                        </li>
                        <li><a href="<?php echo $this->set_url('user'); ?>">
                            <i class="fa fa-user"></i> QA Validators</a>
                        </li>
                    </ul>
                </li>
                <li><a href="<?php echo $this->set_url('survey'); ?>" class="show-survey">
                    <i class="fa fa-desktop"></i> Survey Page</a>
                </li>
                <li class="parent">
                    <a href="#"><i class="fa fa-cog"></i> Settings</a>
                    <ul class="child">  
                    <?php if ($this->can_access($role)) { ?>
                        <li><a href="<?php echo $this->set_url('user/account'); ?>">
                            <i class="fa fa-terminal"></i> Account Settings</a>
                        </li>
                        <li><a href="<?php echo $this->set_url('user/configure_account'); ?>">
                            <i class="fa fa-wrench"></i> Configure Account</a>
                        </li>
                        <li><a href="<?php echo $this->set_url('activity'); ?>">
                            <i class="fa fa-code"></i> Activity Log</a>
                        </li>
                        <li><a href="<?php echo $this->set_url('leadsDiff'); ?>">
                            <i class="fa fa-cubes"></i> Lead Validator</a>
                        </li>
                        <li><a href="<?php echo $this->set_url('backup'); ?>">
                            <i class="fa fa-database"></i> Backup</a>
                        </li>
                    <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    <?php } ?>
</div>
<?php } ?>
<?php } // end if for role ?>
<div class="admin-main-page">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php if($role == 'agent'){ ?>
        <h5>Welcome agent!</h5>
        <blockquote>
            <p>
                <span>Make everything as simple as possible, but not simpler.</span>
            </p>
            <p><small>Albert Einstein</small></p>
        </blockquote>
    <?php } ?>
        <ul>
        <?php if ($this->can_access($role)) { ?>
            <li>
                <a href="<?php echo $this->set_url('../main-crm'); ?>">
                    <i class="fa fa-cube"></i><span>Site Administrator</span>
                </a>
            </li>
        <?php } ?>
            <li>
                <a href="<?php echo $this->set_url('user/logout'); ?>">
                    <i class="fa fa-hand-o-right"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>



