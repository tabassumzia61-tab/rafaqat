<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
?>
<style type="text/css">

</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('student1'); ?></small></h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        </div>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg') ?> </div> <?php } ?>
                            <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                        <form role="form" action="<?php echo site_url('admin/payments/search/'.$brc_id) ?>" method="post" class="">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <?php  
                                            if ($this->rbac->hasPrivilege('accounts_voucher_search', 'can_branch')) { ?>
                                                <div class="col-sm-4">
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
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' '.$this->lang->line('type'); ?></label>
                                                    <select autofocus="" id="voucher_type_id" name="voucher_type_id" class="form-control" >
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <option value="1" <?php if (set_value('voucher_type_id',$voucher_type_id) == 1) echo "selected=selected" ?> ><?php echo $this->lang->line('payment').' In'; ?></option>
                                                        <option value="2" <?php if (set_value('voucher_type_id',$voucher_type_id) == 2) echo "selected=selected" ?> ><?php echo $this->lang->line('payment').' Out'; ?></option>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('voucher_type_id'); ?></span>
                                                </div>  
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('search') . " " . $this->lang->line('type'); ?></label><small class="req"> *</small>
                                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                                        <!--<option value=""><?php echo $this->lang->line('select'); ?></option>-->
                                                        <?php foreach ($searchlist as $key => $search) { ?>
                                                            <option value="<?php echo $key ?>" <?php if ((isset($search_type)) && ($search_type == $key)) { echo "selected"; } ?> ><?php echo $search ?></option>    
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                                </div>  
                                            </div>
                                            <div id='date_result'>
                                    
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                </div>
                                            </div>
                                        </div>  
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <form role="form" action="<?php echo site_url('admin/payments/search/'.$brc_id) ?>" method="post" class="">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <?php  
                                            if ($this->rbac->hasPrivilege('accounts_voucher_search', 'can_branch')) { ?>
                                            <div class="col-sm-3">
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
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label for="invoice_no"><?php echo $this->lang->line('search_by_vno'); ?></label><small class="req"> *</small>
                                                    <input id="vno" name="vno" placeholder="<?php echo $this->lang->line('search_by_vno'); ?>" type="text" class="form-control"  value="<?php echo set_value('vno',$vno); ?>" />
                                                    <span class="text-danger"><?php echo form_error('vno'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">  
                                                <div class="form-group">
                                                    <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php
                    if (!empty($voucherlist)) { ?>
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="box box-primary">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><?php echo $this->lang->line('voucher_list'); ?></h3>
                                    <div class="box-tools pull-right"> 
                                    </div><!-- /.box-tools -->   
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <div class="mailbox-messages">
                                        <div class="download_label"><?php echo $this->lang->line('voucher_list'); ?></div>
                                        <table class="table table-hover table-striped table-bordered example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('sr'); ?></th>
                                                    <th><?php echo $this->lang->line('date'); ?></th>
                                                    <th><?php echo $this->lang->line('vno'); ?></th>
                                                    <th><?php echo $this->lang->line('payment').' '.$this->lang->line('type'); ?></th>
                                                    <th><?php echo $this->lang->line('customer'); ?></th>
                                                    <th><?php echo $this->lang->line('employee'); ?></th>
                                                    <th><?php echo $this->lang->line('description'); ?></th>
                                                    <th><?php echo $this->lang->line('amount'); ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (empty($voucherlist)) {
                                                    ?>
            
                                                    <?php
                                                } else {
                                                    $count = 1;
                                                    $totalvoucher = 0;
                                                    foreach ($voucherlist as $voucher) {
                                                        $totalvoucher = $totalvoucher + $voucher['debit_amount'];
                                                        ?>
                                                        <tr>
                                                            <td class="mailbox-name"><?php echo $count; ?></td>
                                                            <td class="mailbox-name"><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($voucher['date'])) ?></td>
                                                            <td class="mailbox-name"><?php echo $voucher["invoice_no"]; ?></td>
                                                            <td class="mailbox-name">
                                                                <?php 
                                                                    if($voucher["voucher_type_id"] == 1){
                                                                        echo $this->lang->line('payment').' In';
                                                                    }elseif($voucher["voucher_type_id"] == 2){  
                                                                        echo $this->lang->line('payment').' Out';
                                                                    }
                                                                ?>        
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <?php echo $voucher['c_name'].' '.$voucher['cs_name']; ?>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <?php  echo $voucher['staff_name'].' '.$voucher['staff_surname'].' '.$voucher['name']; ?>
                                                            </td>
                                                            <td class="mailbox-name">
                                                                <?php echo $voucher['note'] ?>
                                                            </td>
                                                            <td class="text-center mailbox-name"><?php echo number_format($voucher['debit_amount'], 0, '.', ''); ?></td>
                                                            <td class="mailbox-date text-right">
                                                                <?php if($voucher["voucher_type_id"] == 1){
                                                                        if ($this->rbac->hasPrivilege('payment_in', 'can_edit')) { ?>
                                                                        <a href="<?php echo base_url(); ?>admin/payments/editpaymentin/<?php echo $voucher['id'] ?>/<?php echo $voucher['brc_id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                        <?php } ?>  
                                                                <?php }else{ 
                                                                    if ($this->rbac->hasPrivilege('payment_out', 'can_edit')) { ?>
                                                                    <a href="<?php echo base_url(); ?>admin/payments/editpaymentout/<?php echo $voucher['id'] ?>/<?php echo $voucher['brc_id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php 
                                                                if ($this->rbac->hasPrivilege('accounts_voucher', 'can_delete')) {
                                                                    ?>
                                                                    <!--<a href="<?php echo base_url(); ?>admin/qms/account/accounts/deletevoucher/<?php echo $voucher['id'] ?>/<?php echo $voucher['brc_id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">-->
                                                                    <!--    <i class="fa fa-remove"></i>-->
                                                                    <!--</a>-->
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php $count++; } ?>
                                                    <tr class="total-bg">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><?php echo $this->lang->line('grand_total'); ?> :</td>
                                                        <td class="text-center text-bold"><?php echo (number_format($totalvoucher, 0, '.', '')); ?></td>
                                                        <td class="text-right text-bold"></td>
                                                    </tr>
                                                <?php } ?>
            
                                            </tbody>
                                        </table><!-- /.table -->
            
            
            
                                    </div><!-- /.mail-box-messages -->
                                </div><!-- /.box-body -->
                            </div>
                        </div>
                <?php } ?>
            </div> 
        </section>
    </div>
<script type="text/javascript">
    function getBranchByID(val){
        var url ='<?php echo site_url('admin/payments/search/'); ?>'+val;          
        if(url){
               window.location.href = url; 
        }
    }
</script>
<script>
    <?php if($search_type=='period'){ ?>
        $(document).ready(function () {
            showdate('period');
        });
    <?php } ?>
</script>