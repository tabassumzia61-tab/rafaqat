<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php //echo $this->lang->line('fees_collection'); ?> <small> <?php //echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('admin/accounts/reports/_generalreports'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/accounts/trialbalance/'.$brc_id) ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <?php  
                                if ($this->rbac->hasPrivilege('accounts_report', 'can_branch')) { ?>
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
                                <div class="col-sm-3 col-md-3" >
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('period'); ?></label><small class="req"> *</small>
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
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>   
                        </div>
                    </form>
                    <div class="row">
                        <div class="">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('trial_balance'); ?></h3>
                            </div>                              
                            <div class="box-body">
                               <div class="table-responsive">
                                    <div class="download_label"><?php  if(!empty($start_date) && !empty($end_date)){ echo 'From '.  date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($start_date)).' To '.date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($end_date)); }  ?></div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th class="text text-left"><?php echo $this->lang->line('account_head'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('account_type'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('account_name'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('debit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('credit'); ?></th>
                                            </tr>
                                        </thead> 
                                        <tbody id="tbldetail">
                                            <?php 
                                                if(!empty($acclist)){
                                                    foreach($acclist as $acckey => $accval){ ?>
                                                    <tr>
                                                        <?php 
                                                            $acc_head_id = $accval['acc_head_id'];
                                                            $acc_type_id = $accval['acc_type_id'];
                                                            $acc_name_id = $accval['acc_name_id'];
                                                            $purchases   = $this->accounts_model->getPurchaseID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $inventories = $this->accounts_model->getPurchaseByInventoriesID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $payable     = $this->accounts_model->getPurchaseBySupplierstrialID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $purchasgst  = $this->accounts_model->getPurchaseByGstAccID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $purpervdisc = $this->accounts_model->getPurchaseByDisPervioustrialID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $purcdisc    = $this->accounts_model->getPurchaseByDistrialID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $purchpment  = $this->accounts_model->getPurchasesPaymentsByParentstrialID($brc_id,$acc_head_id,$acc_type_id,$acc_name_id,$start_date,$end_date);
                                                            $triallist   = array_merge($purchases,$inventories,$payable,$purchasgst,$purcdisc,$purpervdisc,$purchpment);
                                                            $gtbalam = 0;
                                                            $total_debit_amount  = 0;
                                                            $total_credit_amount = 0;
                                                            foreach($triallist as $tlkey => $tlval){
                                                                if(!empty($tlval['dr'])){
                                                                    $cr = 0;
                                                                    $dr = $tlval['dr'];
                                                                    if($acc_head_id == 1){
                                                                        if($acc_type_id == 5){
                                                                            $total_debit_amount = $total_debit_amount + ($dr - $cr);
                                                                        }else{
                                                                            $total_debit_amount = $total_debit_amount + ($cr - $dr);    
                                                                        }
                                                                        
                                                                    }else if($acc_head_id == 2){
                                                                        if($acc_name_id == 1){
                                                                            $total_debit_amount = $total_debit_amount + ($dr - $cr);
                                                                        }else{
                                                                            $total_debit_amount = $total_debit_amount + ($cr - $dr);    
                                                                        }
                                                                    }else if($acc_head_id == 3){
                                                                        $total_debit_amount = $total_debit_amount + ($cr - $dr);
                                                                    }else if($acc_head_id == 4){
                                                                        $total_debit_amount = $total_debit_amount + ($cr - $dr);
                                                                    }else{
                                                                        $total_debit_amount = $total_debit_amount + ($dr - $cr);
                                                                    }
                                                                }
                                                                if(!empty($tlval['cr'])){
                                                                    $cr = $tlval['cr'];
                                                                    $dr = 0;
                                                                    if($acc_head_id == 1){
                                                                        if($acc_type_id == 5){
                                                                            $total_credit_amount = $total_credit_amount + ($dr - $cr);
                                                                        }else{
                                                                            $total_credit_amount = $total_credit_amount + ($cr - $dr);
                                                                        }
                                                                    }else if($acc_head_id == 2){
                                                                        if($acc_name_id == 1){
                                                                            $total_credit_amount = $total_credit_amount + ($dr - $cr);
                                                                        }else{
                                                                            $total_credit_amount = $total_credit_amount + ($cr - $dr);
                                                                        }
                                                                    }else if($acc_head_id == 3){
                                                                        $total_credit_amount = $total_credit_amount + ($cr - $dr);
                                                                    }else if($acc_head_id == 4){
                                                                        $total_credit_amount = $total_credit_amount + ($cr - $dr);
                                                                    }else{
                                                                        $total_credit_amount = $total_credit_amount + ($dr - $cr);
                                                                    }    
                                                                }
                                                                   
                                                            }
                                                        ?>
                                                        <td><?php echo $accval['account_head']; ?></td>
                                                        <td><?php echo $accval['account_type']; ?></td>
                                                        <td><?php echo $accval['account_code'].'. '.$accval['account_name']; ?></td>
                                                        <td class="text text-right dr">
                                                            <?php
                                                                echo number_format($total_debit_amount, 2, '.', ',');
                                                            ?>
                                                        </td>
                                                        <td class="text text-right cr">
                                                            <?php
                                                                echo number_format($total_credit_amount, 2, '.', ',');
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                                <tr class="total-bg">
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text text-right"><?php echo $this->lang->line('grand_total'); ?> : </td>
                                                    <td class="text text-right gtdr"><?php ?>0</td>
                                                    <td class="text text-right gtcr"><?php ?>0</td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                            
                        </div>  
                    </div>
                </div>
            </div>
    </section>
</div>
<script>
<?php if ($search_type == 'period') { ?>
    $(document).ready(function () {
        showdate('period');
    });
<?php } ?>
</script>
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/accounts/trialbalance/'); ?>'+val;          
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
    $(document).ready(function () {
        var accounts_type_id = '<?php echo set_value('accounts_type_id',$accounts_type_id) ?>';
        var prev_balance = 0;
        var dr = 0;
        var cr = 0;
        var balance = 0;
        var next_dr = 0;
        var new_balance = 0;
        var trdr = $("#debit tr:first").find(".dr").text();
        var trdrfst = trdr.replace(/\,/g,'');
        $("#debit tr:first").find(".balance").text(trdrfst.toLocaleString('en',{minimumFractionDigits: 2}));
        $("#tbldetail tr").each(function(){
            balance = $(this).find(".balance").text();
            dr = $(this).find(".dr").text();
            cr = $(this).find(".cr").text();
            if(accounts_type_id == 12){
                var baltra = Number(balance.replace(/\,/g,'')) + Number(cr.replace(/\,/g,''));
                $(this).find(".balance").text(baltra.toLocaleString('en',{minimumFractionDigits: 2}));    
            }else{
                var baltrb = Number(balance.replace(/\,/g,'')) - Number(cr.replace(/\,/g,''));
                $(this).find(".balance").text(baltrb.toLocaleString('en',{minimumFractionDigits: 2}));    
            }
            prev_balance = $(this).find(".balance").text();
            next_dr = $(this).next("tr").find(".dr").text();
            if(accounts_type_id == 12){
                new_balance = Number(prev_balance.replace(/\,/g,'')) - Number(next_dr.replace(/\,/g,''));
            }else{
                new_balance = Number(prev_balance.replace(/\,/g,'')) + Number(next_dr.replace(/\,/g,''));
            }
            $(this).next("tr").find(".balance").text(new_balance.toLocaleString('en',{minimumFractionDigits: 2}));
        });
        var total_dr = 0;
        var total_cr = 0;
        var total_balance = 0;
        $("#tbldetail tr").each(function(){
            var curr_dr = $(this).find(".dr").text();
            total_dr += Number(curr_dr.replace(/\,/g,''));
            var curr_cr = $(this).find(".cr").text();
            total_cr += Number(curr_cr.replace(/\,/g,''));
            var curr_balance = $(this).find(".balance").text();
            total_balance += Number(curr_balance.replace(/\,/g,''));
        });
        $("#tbldetail tr").find('.gtdr').text(total_dr.toLocaleString('en',{minimumFractionDigits: 2}));
        $("#tbldetail tr").find('.gtcr').text(total_cr.toLocaleString('en',{minimumFractionDigits: 2}));
        if(accounts_type_id == 12){
            var bala = Number(total_dr) + Number(total_cr);
            $("#tbldetail tr").find('.gtbalance').text(bala.toLocaleString('en',{minimumFractionDigits: 2}));
        }else{
            var balb = Number(total_dr) - Number(total_cr);
            $("#tbldetail tr").find('.gtbalance').text(balb.toLocaleString('en',{minimumFractionDigits: 2}));
        }
    });
</script>