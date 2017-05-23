<div class="col-lg-12">

    <div class="col-lg-2 col-lg-offset-10">
    	<a href="<?php echo $this->set_url('duplicate/dl_record'); ?>" class="btn btn-default" title="Download"><i class="fa fa-download"></i></a>
    	<button class="btn btn-default show_modal" data-url="<?php echo $this->set_url('duplicate/front_migrate'); ?>" title="Remove incomplete applications"><i class="fa fa-circle"></i></button>
    	<button class="btn btn-default" title="Generate duplicate report"><i class="fa fa-plus"></i></button>
    </div>

	    <h3 class="align-center">Realtime Agent Duplication Report</h3>

    <div class="col-lg-12 row">
        <table id="duplicate" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody><tr><td colspan="4">Loading Please wait,....</td></tr></tbody>
        </table>
    </div>
    <div class="col-lg-2 col-lg-offset-10">
    	<a href="<?php echo $this->set_url('duplicate/dl_record'); ?>" class="btn btn-default" title="Download"><i class="fa fa-download"></i></a>
    	<button class="btn btn-default show_modal" data-url="<?php echo $this->set_url('duplicate/front_migrate'); ?>" title="Remove incomplete applications"><i class="fa fa-circle"></i></button>
    	<button class="btn btn-default" title="Generate duplicate report"><i class="fa fa-plus"></i></button>
    </div>
</div>


<script>
    jQuery(window).load(function(){
        notification.fetchInfo();
        notification.enable(5000, '#duplicate');
    });
</script>