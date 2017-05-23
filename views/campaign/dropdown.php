<form action="<?php echo $this->set_url('campaign/switchCampaign'); ?>" id="changer-form" method="POST">
    <select class="form-input campaign-changed" name="campaign">
        <option> Select Campaign </option>
        <?php
        foreach ($this->campaigns as $key => $value) {
            ?>
            <option value="<?php echo base64_encode(serialize($value)); ?>"><?php echo $value->name; ?></option>
        <?php
        }
        ?>
    </select>
</form>