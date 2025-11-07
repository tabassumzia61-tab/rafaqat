<style type="text/css">
   @media print{
        body > *{
            display: none;
        }
        body, html, body .printdiv{
            display: block !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if (($this->rbac->hasPrivilege('payment_in', 'can_add'))) {
                ?>     
                <div class="col-md-12">           
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div> 
                        <form id="accvoucherform" action="<?php echo base_url() ?>admin/payments/savepaymentin" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>        
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-12" style="padding-right: 10px;"> 
                                    <div class="row">
                                        <?php  
                                        if ($this->rbac->hasPrivilege('accounts_voucher', 'can_branch')) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label><small class="req"> *</small> 
                                                    <select  id="brc_id" name="brc_id" class="form-control selectval brc_id" onchange="getBranchByID(this.value);">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($branchlist as $brc_val) {
                                                            $brc_id_log = $this->customlib->getBranchID();
                                                            if ($brc_id_log == 1) { ?>
                                                                <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                            <?php }else{
                                                                if ($brc_id_log == $brc_val['id']) { ?>
                                                                    <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                                <?php  } ?>
                                                            <?php
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('brc_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>              
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' '.$this->lang->line('date'); ?></label> <small class="req">*</small>
                                                <input id="payment_date" name="payment_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('payment_date', date($this->customlib->getSystemDateFormat())); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('payment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('received').' In '; ?></label> <small class="req">*</small>
                                                <select autofocus="" id="received_in_id" name="received_in_id" class="form-control received_in_id">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($acclist as $acc_val) { ?>
                                                        <option value="<?php echo $acc_val['id'] ?>" <?php if (set_value('received_in_id') == $acc_val['id']) echo "selected=selected"; ?>><?php echo $acc_val['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('received_in_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' From '; ?></label> <small class="req">*</small>
                                                <select autofocus="" id="payment_from" name="payment_from" class="form-control payment_from">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="1" <?php if (set_value('payment_from', 1) == 1) echo "selected=selected"; ?> ><?php echo $this->lang->line('customer'); ?></option>
                                                    <option value="3" <?php if (set_value('payment_from')) echo "selected=selected"; ?> ><?php echo $this->lang->line('employee'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('payment_from'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding-right: 10px;"> 
                                    <div class="row">
                                        <div class="tshadow mb25 bozero" style="margin-bottom: 15px;">
                                            <div class="col-md-12 pagetitleh2">
                                                <h4 class="box-title titlefix pull-left" style="margin:7px 0 0 0 ;"><?php echo $this->lang->line('amount_information'); ?></h4>
                                            </div>
                                            <div class="row around10">
                                                <div style="margin-top:5px;" class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-3 cu">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('customer'); ?></label> <small class="req">*</small>
                                                                <select autofocus="" id="customer_id" name="customer_id" class="form-control selectval">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach ($customerlist as $customer) { ?>
                                                                        <option value="<?php echo $customer['id'] ?>" <?php if (set_value('customer_id') == $customer['id']) echo "selected=selected"; ?>><?php echo $customer['code'].' - '.$customer['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('customer_id'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 stf" style="display:none">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('staff'); ?></label> <small class="req">*</small>
                                                                <select autofocus="" id="staff_id" name="staff_id" class="form-control selectval">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach ($stafflist as $staff) { if($staff['id'] == 1) { } else { ?>
                                                                            <option value="<?php echo $staff['id'] ?>" <?php if (set_value('id') == $staff['id']) echo "selected=selected"; ?>><?php echo $staff['employee_id'].' - '.$staff['name'].' '.$staff['surname'] ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('staff_id'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label> <small class="req">*</small>
                                                                <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                                            </div>
                                                        </div>        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="saveprint" class="btn btn-info printsavebtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?></button>                        
                                    <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="" id="savenew" class="btn btn-info savenew"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save_new'); ?></button>  
                                </div>
                            </div>
                        </form>
                    </div>   
                </div>  
            <?php } ?>
        </div>
    </section>
</div>
<script type="text/javascript">
    var htmlData = "";
    $(document).ready(function () {
        $(document).on('click','.printsavebtn', function (e) {
            var $this = $(this);
            $this.button('loading');
            var formData = new FormData($('#accvoucherform')[0]);
            $.ajax({
                url: '<?php echo site_url("admin/payments/printpaymentin") ?>',
                type: 'POST',
                async: false,
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    try {
                        var output = JSON.parse(data);
                            var message = "";
                            if (output.status == "fail") {
                                $.each(output.error, function (index, value) {
                                    message += value;
                                });
                                errorMsg(message);
                            }else if(output.status == "success"){
                                successMsg(output.message);
                                window.location.reload(true); 
                            }
                    } catch (e) {
                        htmlData = data;
                        setTimeout(function () {
                        $(".printdiv").html(htmlData);
                            window.print();
                        }, 1000);
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 1000);
                    }
                    $this.button('reset');
                }
            });
            e.stopImmediatePropagation(); // to prevent more than once submission
            return false; 
        });
        $(document).on('click','.savenew', function (e) {
            var $this = $(this);
            $this.button('loading');
            $('.savenew').prop("disabled", true);
            var formData = new FormData($('#accvoucherform')[0]);
            $.ajax({
                url: '<?php echo site_url("admin/payments/savepaymentin") ?>',
                type: 'POST',
                async: false,
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                dataType: "json",
                success: function (data, status, xhr) {
                    if (data.status == "fail") {
                        $('.savenew').prop("disabled", false);
                        $this.button('reset');
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                        
                    }else{
                        successMsg(data.message);
                    }
                },
                error: function (xhr) { // if error occured
                    alert("Error occured.please try again");
                },
                complete: function () {
                    setTimeout(function () {
                       window.location.reload(true);
                    }, 1000);
                    $('.savenew').prop("disabled", false);
                    $this.button('reset');
                }
            });
            e.stopImmediatePropagation(); // to prevent more than once submission
            return false; 
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change','.payment_from',function(e){
            var payment_from = $(this).val();
            if(payment_from == 1){
                $('.stf').hide();
                $('.cu').show();
            }else{
                $('.stf').show();
                $('.cu').hide();
            }
        });
    });
</script>