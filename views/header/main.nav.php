<div class="navbar">
  <div class="navbar-inner">
	<div class="container main-nav">
		<div class="page-title">
			<h4 class="change-campaign"><?php echo $this->get_page_title(); ?></h4>
			<input type="hidden" class="hidden hide" id="campaign_url" value="<?php echo $this->set_url('campaign/campaignAccounts'); ?>" />
			<div class="campaing-changer" style="display: none;">
				<i class="fa fa-spinner fa-spin"></i> Loading content
			</div>
		</div>
	</div>
  </div>
</div>