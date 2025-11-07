<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

            $('.date_from').datepicker({
                format: date_format,
            }).trigger('change');
            $('.date_to').datepicker({
                format: date_format,
            }).trigger('change');
</script>
<?php
if (isset($_POST['date_from']) && !empty($_POST['date_from'])) {
    $date_from = date($this->customlib->getSystemDateFormat(), $this->customlib->datetostrtotime($_POST['date_from']));
} else {
    $date_from = date($this->customlib->getSystemDateFormat());
}
if (isset($_POST['date_to']) && !empty($_POST['date_to'])) {
    $date_to = date($this->customlib->getSystemDateFormat(), $this->customlib->datetostrtotime($_POST['date_to']));
} else {
    $date_to = date($this->customlib->getSystemDateFormat());
}
?>
<div class="col-sm-6 col-md-3">
    <div class="form-group">
        <label><?php echo $this->lang->line('date_from'); ?></label>
        <input name="date_from" id="date_from" placeholder="" type="text" class="form-control date_from" value="<?php echo $date_from; ?>"  />
        <span class="text-danger"><?php echo form_error('date_from'); ?></span>
    </div>
</div> 

<div class="col-sm-6 col-md-3">
    <div class="form-group">
        <label><?php echo $this->lang->line('date_to'); ?></label>
        <input  name="date_to" id="date_to" placeholder="" type="text" class="form-control date_to" value="<?php echo $date_to; ?>"  />
        <span class="text-danger"><?php echo form_error('date_to'); ?></span>
    </div>
</div>