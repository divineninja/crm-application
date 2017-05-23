<!--Daily Graph content-->
<div class="col-lg-12">
    <div class="col-lg-6">
        <h4>Latest Update of version <?php echo VERSION; ?></h4>
        <ol>
            <li>Major Change: Update database library to add bulk insert for questions</li>
        </ol>
        <?php
        if ($this->updateButton) {
            ?>
            <p>System found something wrong with your database. Please click the button to fix the error.</p>
            <a href="<?php echo $this->set_url('setup/updateDatabase'); ?>" class="btn btn-default btn-small">Update Database</a>
            <?php
        }
        ?>
        <?php
        if ($this->setupNotif) {
            ?>
            <hr />
            <p class="alert alert-danger">Important! set up CRM Notification.</p>
            <a href="<?php echo $this->set_url('setup/notification'); ?>" class="btn btn-default btn-small">Continue</a>
            <?php
        }
        ?>
    </div>
    <div class="col-lg-6">
        <div class="welcome" id="nav-home">
            <p>Today(<span class='app-date'></span>) the CRM has total of 
            <span class='apps'></span> Applications</p>
            <p>Since the CRM start working it accomplishes a total of 
               <span class="totalApps"></span> Applications</p>
            <table class="table">
                <tr>
                    <th>Date</th>
                    <th>Applications</th>
                </tr>
                <tr>
                    <td>Beggining of time</td>
                    <td class='totalApps'></td>
                </tr>
                <tr>
                    <td>Today (<span class='app-date'></span>)</td>
                    <td class='apps'></td>
                </tr>
            </table>
			<?php $role = strtolower($_SESSION['role']);?>
        </div>
    </div>
</div>
<!--End Daily graph-->

<!--Daily Graph content-->
<div class="col-lg-12">
    <div class="col-lg-6 daily-report report-container" id="nav-daily">
        <div id="daily"></div>
    </div>
    <div class="col-lg-6 week-report report-container" id="nav-weekly">
        <div id="weekly"></div>
    </div>
</div>
<!--End Daily graph-->

<!--Daily Graph content-->
<div class="col-lg-12">
    <div class="daily-report report-container" id="nav-daily">
        <div class="col-lg-6">
            <h4>Dailly Applications</h4>
            <div class="dailyDescription report-description description"></div>
        </div>
        <div class="col-lg-6">
            <h4>Weekly Applications</h4>
            <div class="weeklyDescription report-description description"></div>
        </div>
    </div>
</div>
<!--End Daily graph-->


<?php include dirname(__FILE__).'/tpl/underscore.phtml'; ?>

<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.logAxisRenderer.min.js" type="text/javascript"></script>   
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.logAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.canvasTextRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.canvasAxisTickRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.canvasAxisLabelRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.categoryAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.highlighter.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.pointLabels.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/jplot/jqplot.cursor.min.js" type="text/javascript"></script>
<script src="<?php echo TEMPLATE_URL; ?>public/js/report.js" type="text/javascript"></script>