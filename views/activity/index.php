<?php
 /**
  * Activity template page
  * 
  * @category  PHP
  * @package   CRM
  * @author    Rey Lim Jr <junreyjr1029@gmail.com>
  * @copyright 2013 TELEQUEST BPO
  * @license   GPLv2 http://gplv2.org
  * @link      null
  * @since     2.0
  */
?>
<div class="col-lg-3 col-lg-offset-5">
    <h1>Activity Log</h1>
</div>
<div class="col-lg-12">
    <div class="col-lg-12">

        <ul class="icon-type">
            <?php
            foreach ($this->types as $key => $value) {
                ?>
                <li class="<?php echo $value->active; ?>">
                    <span class="type-icon">
                        <a href="<?php echo $value->url; ?>">
                            <span title="<?php echo $value->title; ?>">
                                <i class="fa <?php echo $value->icon; ?>"></i>
                            </span>
                        </a>
                    </span>
                </li>
            <?php
            }
            ?>
            <li class="icon-title-wrapper"></li>
        </ul>
    </div>
    <table class="table table-bordered dataTable" id="dataTable">
        <thead>
            <th>Date</th>
            <th>Action</th>
        </thead>
        <tbody>
<?php
if (empty($this->activities) === false) {
    foreach ($this->activities as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value->date; ?></td>
                            <td><?php echo $value->action; ?></td>
                        </tr>
    <?php
    }//end foreach
}
?>
        </tbody>
    </table>
</div>
