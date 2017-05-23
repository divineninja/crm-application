<table class="table table-striped table-bordered" id="agent-data-table">
<thead>
    <tr>
        <th width="12%">Name</th>
        <th>Code</th>
        <?php
        foreach ($this->params['start_shift_hours'] as $key => $value) {
            ?>
        <th><?php echo date('h:i a', strtotime($value)); ?></th>
        <?php
        }
        ?>
        <?php
        foreach ($this->params['end_shift_hours'] as $key => $value) {
            ?>
        <th><?php echo date('h:i a', strtotime($value)); ?></th>
        <?php
        }
            ?>
        <th width="3%"><i class="fa fa-dollar"></i></th>
    </tr>
</thead>
<tbody>
<?php
foreach ($this->applications as $key => $value) {
        ?>
    <tr>
        <td class="agent-col"><?php echo $value['name'];?></td>
        <td><?php echo $value['code'];?></td>
        <?php
    foreach ($value['query'] as $key => $query) {
            ?>
            <td>
        <?php
        if ($query) {
                ?>
                <div class="revenue-application">
                    <span title="applications"><?php echo $query->applications; ?></span>
                    <span title="revenue">&#163;<?php echo $query->revenue; ?></span>
                </div>
    <?php
        } else {
                    ?>
                <div class="revenue-application">
                    
                </div>
        <?php
        }//end if
            ?>
        </td>
    <?php
    }//end foreach
        ?>
        <td><?php echo $value['revenue']; ?></td>
    </tr>
    <?php
}//end foreach
    ?>
</tbody>
</table>

<div class="note">
    <h5>Important Note!</h5>
    <ul>
        <li>Shift time is starting @ 3:00 pm on selected date and ended on the next day @ 5:00 am the next day.</li>
        <li>(Applications) | (Revenue) is the format for the hourly result.</li>
        <li>Click the <i class="fa fa-search"></i> icon to refresh the result.</li>
        <li>Click on the header of the table to sort out the result.</li>
    </ul>
</div>