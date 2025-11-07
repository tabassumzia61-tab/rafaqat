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
                        <form id="accvoucherform" action="<?php echo base_url() ?>admin/expenses/saveexpense" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('expense').' '.$this->lang->line('date'); ?></label> <small class="req">*</small>
                                                <input id="expense_date" name="expense_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('expense_date', date($this->customlib->getSystemDateFormat())); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('expense_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('bill_type'); ?></label> <small class="req">*</small>
                                                <select  id="bill_type" name="bill_type" class="form-control bill_type js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    $ct = [1 => $this->lang->line('cash'),2 => $this->lang->line('credit')];// 'pending' => lang('pending'),
                                                    foreach ($ct as $key => $val) { ?>
                                                        <option value="<?php echo $key ?>" <?php if (set_value('bill_type',1) == $key) echo "selected=selected" ?>><?php echo $val ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('bill_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 paymentmode">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment_mode'); ?></label> <small class="req">*</small>
                                                <select autofocus="" id="payment_mode" name="payment_mode" class="form-control payment_mode">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($acclist as $acc_val) { ?>
                                                        <option value="<?php echo $acc_val['id'] ?>" <?php if (set_value('payment_mode') == $acc_val['id']) echo "selected=selected"; ?>><?php echo $acc_val['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 cshow">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier'); ?></label> <small class="req">*</small>
                                                <select  id="supplier_id" name="supplier_id" class="form-control supplier_id js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($supplierlist as $val) {
                                                        if($val['id'] == 1){}else{ ?>
                                                            <option value="<?php echo $val['id'] ?>" <?php if (set_value('supplier_id') == $val['id']) echo "selected=selected" ?>><?php echo $val['code'].' - '.$val['name'] ?></option>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('supplier_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding-right: 10px;"> 
                                    <div class="row">
                                        <div class="tshadow mb25 bozero" style="margin-bottom: 15px;">
                                            <div class="col-md-12 pagetitleh2">
                                                <h4 class="box-title titlefix pull-left" style="margin:7px 0 0 0 ;"><?php echo $this->lang->line('amount_information'); ?></h4>
                                                <div class="box-tools pull-right">
                                                    <button id="btnAdd" style="margin-left: 20px" class="btn btn-primary btn-sm checkbox-toggle pull-right addtype" type="button" data-typeid="" value="<?php echo $brc_id; ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                                                </div>
                                            </div>
                                            <div class="row around10">
                                                <div class="col-md-12" style="padding:10px 0 0 0;">
                                                    <div style="margin-top:5px;" class="col-md-12">
                                                        <div class="col-md-5" style="padding:0 10px 0 0;">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('accounts').' '.$this->lang->line('name'); ?> <small class="req">*</small></label>
                                                                <select id="account_head" name="account_head[]" class="form-control account_head">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach ($accheadlist as $acch_val) { ?>
                                                                        <option value="<?php echo $acch_val['id'] ?>"><?php echo $acch_val['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('account_head[]'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3" style="padding:0 10px 0 0;">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('amount').' '; ?> </label> <small class="req">*</small>
                                                                <input type="text" id="debit_amount" name="debit_amount[]" class="form-control debit_amount" onkeyup="getdebittotal(this)" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="fieldwrap">
                                                        
                                                    </div>
                                                    <div class="amounttotalwrap">
                                                        <div style="margin-top:5px;" class="col-md-12">
                                                            <div class="col-md-5" style="padding-left:0px;text-align:right;font-weight:bold;">
                                                                <?php echo $this->lang->line('total_amount'); ?>
                                                            </div>
                                                            <div class="col-md-3 total_debit_amount" style="padding-left:30px;font-weight:bold;">0</div>
                                                            <input type="hidden" name="grand_total" class="grand_total_debit_amount" value="0" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                        <span class="text-danger"></span>
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
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/expenses/create/'); ?>'+val;          
        if(url){
            window.location.href = url; 
        }
    }
    function getBranchByIDByUrl(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>
<script type="text/javascript">
    window.onload = function(){
        $('.cshow').hide();
    }
    $(document).ready(function () {
        $(document).on('change','.bill_type',function(e){
            var bill_type = $(this).val();
            if(bill_type == 1){
                $('.cshow').hide();
                $('.paymentmode').show();
            }else{
                $('.cshow').show();
                $('.paymentmode').hide();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '#btnAdd', function (e) {
            var data_row = '';
            data_row += '<div style="margin-top:5px;" class="col-md-12 remove_field_warp">';
            data_row += '<div class="col-md-5" style="padding:0 10px 0 0;">';
            data_row += '<select id="account_head" name="account_head[]" class="form-control account_head">';
            data_row += '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                            <?php if(!empty($accheadlist)){ 
                                foreach ($accheadlist as $head_val) { ?>
                                    data_row += '<option value="<?php echo $head_val['id']; ?>"><?php echo $head_val['name'];//$head_val->code.'. '.$head_val->name; ?></option>';
                            <?php } ?>
                        <?php } ?>
            data_row += '</select>';
            data_row += '</div>';
            data_row += '<div class="col-md-3" style="padding:0 10px 0 0;">';
            data_row += '<input type="text" id="debit_amount" name="debit_amount[]" class="form-control debit_amount" onkeyup="getdebittotal(this)" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="">';
            data_row += '</div>';
            data_row += '<div style="margin-top:1px;text-align:center;padding:0px;" class="col-md-1">';
            data_row += '<button type="button" class="btn btn-sm btn-danger remove_field" >';
            data_row += '<i class="fa fa-trash"></i>';
            data_row += '</button>';
            data_row += '</div>';
            data_row += '</div>';
            $(".fieldwrap").append(data_row); //add input box
        });
        $(document).on("click",".remove_field", function(e){ //user click on remove text
            $(this).closest(".remove_field_warp").remove();
            getdebittotal();
        });
    });
</script>
<script type="text/javascript">
    function getdebittotal() {
        $('.total_debit_amount').html("");
        $('.grand_total_debit_amount').html("");
        var debitsum = 0;
        $('.debit_amount').each(function(){
            debitsum += Number($(this).val());
        });
        $('.total_debit_amount').append(debitsum);
        $('.grand_total_debit_amount').val(debitsum);
    }

    function getcredittotal(val) {
        $('.total_credit_amount').html("");
        var creditsum = 0;
        $('.credit_amount').each(function(){
            creditsum += Number($(this).val());
        });
        $('.total_credit_amount').append(creditsum);
    }
</script>
<script type="text/javascript">
    var htmlData = "";
    $(document).ready(function () {
        $(document).on('click','.printsavebtn', function (e) {
            var $this = $(this);
            $this.button('loading');
            var formData = new FormData($('#accvoucherform')[0]);
            $.ajax({
                url: '<?php echo site_url("admin/expenses/printexpense") ?>',
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
                url: '<?php echo site_url("admin/expenses/saveexpense") ?>',
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