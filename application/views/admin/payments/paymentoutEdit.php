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
            <?php if (($this->rbac->hasPrivilege('payment_out', 'can_edit'))) {
                ?>     
                <div class="col-md-12">           
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div> 
                        <form id="accvoucherform" action="<?php echo base_url() ?>admin/payments/paymentoutedit/<?php echo $id; ?>/<?php echo $brc_id; ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                                <input id="payment_date" name="payment_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('payment_date', date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($vouchers['date']))); ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('payment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' From '; ?></label> <small class="req">*</small>
                                                <select autofocus="" id="received_in_id" name="received_in_id" class="form-control received_in_id">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($acclist as $acc_val) { ?>
                                                        <option value="<?php echo $acc_val['id'] ?>" <?php if (set_value('received_in_id',$vouchers['par_acc_head_id']) == $acc_val['id']) echo "selected=selected"; ?>><?php echo $acc_val['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('received_in_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' To '; ?></label> <small class="req">*</small>
                                                <select autofocus="" id="payment_to" name="payment_to" class="form-control payment_to">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="2" <?php if (set_value('payment_to',$vouchers['payment_type_id']) == 2) echo "selected=selected"; ?> ><?php echo $this->lang->line('supplier'); ?></option>
                                                    <option value="3" <?php if (set_value('payment_to',$vouchers['payment_type_id']) == 3) echo "selected=selected"; ?> ><?php echo $this->lang->line('employee'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('payment_to'); ?></span>
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
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier'); ?></label> <small class="req">*</small>
                                                                <select autofocus="" id="supplier_id" name="supplier_id" class="form-control selectval">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach ($supplierlist as $supplier) { ?>
                                                                        <option value="<?php echo $supplier['id'] ?>" <?php if (set_value('supplier_id',$vouchers['supplier_id']) == $supplier['id']) echo "selected=selected"; ?>><?php echo $supplier['code'].' - '.$supplier['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('supplier_id'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 stf" style="display:none">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('staff'); ?></label> <small class="req">*</small>
                                                                <select autofocus="" id="staff_id" name="staff_id" class="form-control selectval">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php foreach ($stafflist as $staff) { if($staff['id'] == 1) { } else { ?>
                                                                            <option value="<?php echo $staff['id'] ?>" <?php if (set_value('id',$vouchers['staff_id']) == $staff['id']) echo "selected=selected"; ?>><?php echo $staff['employee_id'].' - '.$staff['name'].' '.$staff['surname'] ?></option>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('staff_id'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label> <small class="req">*</small>
                                                                <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount',$vouchers['debit_amount']); ?>" />
                                                                <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                                                <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description',$vouchers['note']); ?></textarea>
                                                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="" id="savenew" class="btn btn-info savenew"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>  
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
    window.onload = function(){  
        var payment_type_id = '<?php echo $vouchers['payment_type_id']; ?>';  
        if(payment_type_id == '2'){
            $('.stf').hide();
            $('.cu').show();
        }else if(payment_type_id == '3'){
            $('.stf').show();
            $('.cu').hide();
        }
    }  
    $(document).ready(function () {
        $(document).on('change','.payment_to',function(e){
            var payment_to = $(this).val();
            if(payment_to == 2){
                $('.stf').hide();
                $('.cu').show();
            }else{
                $('.stf').show();
                $('.cu').hide();
            }
        });
    });
</script>