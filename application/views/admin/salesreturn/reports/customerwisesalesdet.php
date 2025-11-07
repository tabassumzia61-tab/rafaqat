<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php //echo $this->lang->line('fees_collection'); ?> <small> <?php //echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('admin/sales/reports/_salesreports'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/sales/customerwisesalesdet/'.$brc_id) ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <?php  
                                if ($this->rbac->hasPrivilege('quantity_wise_stock', 'can_branch')) { ?>
                                    <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('customer'); ?></label> <small class="req">*</small>
                                        <select autofocus="" id="customer_id" name="customer_id" class="form-control selectval">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($customerslist as $cus_val) { ?>
                                                    <option value="<?php echo $cus_val['id'] ?>" <?php if (set_value('customer_id') == $cus_val['id']) echo "selected=selected"; ?>><?php echo $cus_val['code'].' - '.$cus_val['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('customer_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
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
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>   
                        </div>
                    </form>
                    <div class="row">
                        <div class="">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-list"></i> <?php echo $this->lang->line('customer').' '.$this->lang->line('wise').' '.$this->lang->line('sales').' '.$this->lang->line('summary'); ?></h3>
                            </div>                              
                            <div class="box-body">
                               <div class="table-responsive">
                                    <div class="download_label"><?php  if(!empty($start_date) && !empty($end_date)){ echo 'From '.  date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($start_date)).' To '.date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($end_date)); }  ?></div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th class="text text-left"><?php echo $this->lang->line('sr'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('document_no'); ?></th>
                                                <!--<th class="text text-left"><?php echo $this->lang->line('document_type'); ?></th>-->
                                                <th class="text text-left"><?php echo $this->lang->line('accounts_name'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('description'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('debit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('credit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('balance'); ?></th>
                                            </tr>
                                        </thead> 
                                        <tbody id="tbldetail">
                                            <?php 
                                            $count = 1;
                                            if(!empty($salelist)){
                                                foreach($salelist as $salkey => $salval){ ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($salval['date'])); ?></td>
                                                    <td><?php echo $salval['bill_no']; ?></td>
                                                    <!--<td></td>-->
                                                    <td><?php echo $salval['customer']; ?></td>
                                                    <td><?php echo $salval['note']; ?></td>
                                                    <td class="text text-right dr"><?php //if(!empty($salval['dr'])){ echo (float)$salval['dr']; } ?></td>
                                                    <td class="text text-right cr"><?php if(!empty($salval['cr'])){ echo (float)$salval['cr']; } ?></td>
                                                    <td class="text text-right balance">0</td>
                                                </tr>
                                                <?php $count++; } ?>
                                            <?php } ?>
                                            <tr class="total-bg">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!--<td></td>-->
                                                <td></td>
                                                <td class="text text-right"><?php echo $this->lang->line('grand_total'); ?> : </td>
                                                <td class="text text-right gtdr"><?php ?>0</td>
                                                <td class="text text-right gtcr"><?php ?>0</td>
                                                <td class="text text-right gtbalance"><?php ?>0</td>
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
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/sales/customerwisesalesdet/'); ?>'+val;          
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
        var prev_balance = 0;
        var dr = 0;
        var cr = 0;
        var balance = 0;
        var next_dr = 0;
        var new_balance = 0;
        $("#tbldetail tr:first").find(".balance").text($("#tbldetail tr:first").find(".dr").text());
        $("#tbldetail tr").each(function(){
            balance = $(this).find(".balance").text();
            dr = $(this).find(".dr").text();
            cr = $(this).find(".cr").text();
            $(this).find(".balance").text(Number(balance) + Number(cr));    
            prev_balance = $(this).find(".balance").text();
            next_dr = $(this).next("tr").find(".dr").text();
            new_balance = Number(prev_balance) + Number(next_dr);
            $(this).next("tr").find(".balance").text(new_balance);
        });
        var total_dr = 0;
        var total_cr = 0;
        var total_balance = 0;
        $("#tbldetail tr").each(function(){
            var curr_dr = $(this).find(".dr").text();
            total_dr += Number(curr_dr);
            var curr_cr = $(this).find(".cr").text();
            total_cr += Number(curr_cr);
            var curr_balance = $(this).find(".balance").text();
            total_balance += Number(curr_balance);
        });
        $("#tbldetail tr").find('.gtdr').text(total_dr);
        $("#tbldetail tr").find('.gtcr').text(total_cr);
        $("#tbldetail tr").find('.gtbalance').text(Number(total_dr) + Number(total_cr));
    });
</script>