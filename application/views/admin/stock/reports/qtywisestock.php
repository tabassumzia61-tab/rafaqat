<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php //echo $this->lang->line('fees_collection'); ?> <small> <?php //echo $this->lang->line('filter_by_name1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('admin/stock/reports/_stockreports'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/stock/qtywisestock/'.$brc_id) ?>"  method="post" accept-charset="utf-8">
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
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('products_services'); ?></label> <small class="req">*</small>
                                        <select autofocus="" id="item_id" name="item_id" class="form-control selectval">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($productslist as $prodt) { ?>
                                                    <option value="<?php echo $prodt['id'] ?>" <?php if (set_value('item_id') == $prodt['id']) echo "selected=selected"; ?>><?php echo $prodt['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('item_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
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
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('quantity').' '.$this->lang->line('wise').' '.$this->lang->line('stock'); ?></h3>
                            </div>                              
                            <div class="box-body">
                               <div class="table-responsive">
                                    <div class="download_label"><?php if(!empty($aclist)){ echo $aclist['name']; }?><?php  if(!empty($start_date) && !empty($end_date)){ echo 'From '.  date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($start_date)).' To '.date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($end_date)); }  ?></div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th class="text text-left"><?php echo $this->lang->line('sr'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('document_no'); ?></th>
                                                <!--<th class="text text-left"><?php echo $this->lang->line('document_type'); ?></th>-->
                                                <th class="text text-left"><?php echo $this->lang->line('account_people'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('description'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('quantity_in'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('quantity_out'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('quantity_balance'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('debit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('credit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('amount').' '.$this->lang->line('balance'); ?></th>
                                            </tr>
                                        </thead> 
                                        <tbody id="tbldetail">
                                            <?php 
                                            $count = 1;
                                            if(!empty($purchaselist)){
                                                foreach($purchaselist as $purkey => $purval){ 
                                                    if($purval['date'] !=""){ ?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>
                                                            <td><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($purval['date'])); ?></td>
                                                            <td><?php echo $purval['bill_no']; ?></td>
                                                            <!--<td></td>-->
                                                            <td><?php echo $purval['account_name']; ?></td>
                                                            <td><?php echo $purval['note']; ?></td>
                                                            <td class="text text-right qtyin"><?php if(!empty($purval['qtyin'])){ echo (float)$purval['qtyin']; } ?></td>
                                                            <td class="text text-right qtyout">0</td>
                                                            <td class="text text-right qtybalance">0</td>
                                                            <td class="text text-right dr"><?php if(!empty($purval['dr'])){ echo (float)$purval['dr']; } ?></td>
                                                            <td class="text text-right cr"><?php echo '0';//if(!empty($purval['cr'])){ echo (float)$purval['cr']; } ?></td>
                                                            <td class="text text-right balance">0</td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php $count++; } ?>
                                            <?php } ?>
                                            <?php 
                                            if(!empty($salelist)){
                                                foreach($salelist as $salkey => $salval){ 
                                                    if($salval['date'] !=""){?>
                                                        <tr>
                                                            <td><?php echo $count; ?></td>
                                                            <td><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($salval['date'])); ?></td>
                                                            <td><?php echo $salval['bill_no']; ?></td>
                                                            <!--<td></td>-->
                                                            <td><?php echo $salval['account_name']; ?></td>
                                                            <td><?php echo $salval['note']; ?></td>
                                                            <td class="text text-right qtyin">0</td>
                                                            <td class="text text-right qtyout"><?php if(!empty($salval['qtyout'])){ echo (float)$salval['qtyout']; } ?></td>
                                                            <td class="text text-right qtybalance">0</td>
                                                            <td class="text text-right dr"><?php echo '0';//if(!empty($salval['dr'])){ echo (float)$salval['dr']; } ?></td>
                                                            <td class="text text-right cr"><?php if(!empty($salval['cr'])){ echo (float)$salval['cr']; } ?></td>
                                                            <td class="text text-right balance">0</td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php $count++; } ?>
                                            <?php } ?>
                                            <tr class="total-bg">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <!--<td></td>-->
                                                <td></td>
                                                <td class="text text-right"><?php echo $this->lang->line('grand_total'); ?> : </td>
                                                <td class="text text-right gtqtyin"><?php ?>0</td>
                                                <td class="text text-right gtqtyout"><?php ?>0</td>
                                                <td class="text text-right gtqtybalance"><?php ?>0</td>
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
        var url ='<?php echo site_url('admin/stock/qtywisestock/'); ?>'+val;          
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
        var account_type_id = '<?php echo $accounts_type_id ?>';
            if(account_type_id == 1){
                $('.stf').hide();
                $('.cu').show();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').hide();
            }else if(account_type_id == 5){
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').show();
            }else if(account_type_id == 6){
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').show();
                $('.aname').hide();
                $('.prodt').hide();
            }else if(account_type_id == 7){
                $('.stf').show();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').hide();
            }else{
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').show();
                $('.prodt').hide();
            }
    } 
    $(document).ready(function () {
        $(document).on('change','.account_type_id',function(e){
            var account_type_id = $(this).val();
            if(account_type_id == 1){
                $('.stf').hide();
                $('.cu').show();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').hide();
            }else if(account_type_id == 5){
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').show();
            }else if(account_type_id == 6){
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').show();
                $('.aname').hide();
                $('.prodt').hide();
            }else if(account_type_id == 7){
                $('.stf').show();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').hide();
                $('.prodt').hide();
            }else{
                $('.stf').hide();
                $('.cu').hide();
                $('.sup').hide();
                $('.aname').show();
                $('.prodt').hide();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var prev_balance = 0;
        var dr = 0;
        var cr = 0;
        var balance = 0;
        var next_dr = 0;
        var new_balance = 0;
        var new_qtybalance = 0;
        var prev_qtybalance = 0;
        var qtyin = 0;
        var qtyout = 0;
        var qtybalance = 0;
        var next_qtyin = 0;
        $("#tbldetail tr:first").find(".qtybalance").text($("#tbldetail tr:first").find(".qtyin").text());
        $("#tbldetail tr:first").find(".balance").text($("#tbldetail tr:first").find(".dr").text());
        $("#tbldetail tr").each(function(){
            qtybalance = $(this).find(".qtybalance").text();
            qtyin = $(this).find(".qtyin").text();
            qtyout = $(this).find(".qtyout").text();
            $(this).find(".qtybalance").text(Number(qtybalance) - Number(qtyout));    
            prev_qtybalance = $(this).find(".qtybalance").text();
            next_qtyin = $(this).next("tr").find(".qtyin").text();
            new_qtybalance = Number(prev_qtybalance) + Number(next_qtyin);
            $(this).next("tr").find(".qtybalance").text(new_qtybalance);
            balance = $(this).find(".balance").text();
            dr = $(this).find(".dr").text();
            cr = $(this).find(".cr").text();
            $(this).find(".balance").text(Number(balance) - Number(cr));    
            prev_balance = $(this).find(".balance").text();
            next_dr = $(this).next("tr").find(".dr").text();
            new_balance = Number(prev_balance) + Number(next_dr);
            $(this).next("tr").find(".balance").text(new_balance);
        });
        var total_qtyin = 0;
        var total_qtyout = 0;
        var total_qtybalance = 0;
        var total_dr = 0;
        var total_cr = 0;
        var total_balance = 0;
        $("#tbldetail tr").each(function(){
            var curr_qtyin = $(this).find(".qtyin").text();
            total_qtyin += Number(curr_qtyin);
            var curr_qtyout = $(this).find(".qtyout").text();
            total_qtyout += Number(curr_qtyout);
            var curr_qtybalance = $(this).find(".qtybalance").text();
            total_qtybalance += Number(curr_qtybalance);
            var curr_dr = $(this).find(".dr").text();
            total_dr += Number(curr_dr);
            var curr_cr = $(this).find(".cr").text();
            total_cr += Number(curr_cr);
            var curr_balance = $(this).find(".balance").text();
            total_balance += Number(curr_balance);
        });
        $("#tbldetail tr").find('.gtqtyin').text(total_qtyin);
        $("#tbldetail tr").find('.gtqtyout').text(total_qtyout);
        $("#tbldetail tr").find('.gtqtybalance').text(Number(total_qtyin) - Number(total_qtyout));
        $("#tbldetail tr").find('.gtdr').text(total_dr);
        $("#tbldetail tr").find('.gtcr').text(total_cr);
        $("#tbldetail tr").find('.gtbalance').text(Number(total_dr) - Number(total_cr));
    });
</script>
<script type="text/javascript">
    function getaccounttypeByAccounthead(accounts_head_id, account_type_id) {
        if (accounts_head_id != "") {
            $('#account_type_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getBynewaccounts",
                data: {'accounts_head_id': accounts_head_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (account_type_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#account_type_id').append(div_data);
                    $(".selectval").select2();
                }
            });
        }
    }
    
    function getAccountNameByID(brc_id,accounts_head_id, account_type_id,account_name_id) {
        if (accounts_head_id != "") {
            $('#account_name_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getAccountsNameByID",
                data: {'brc_id':brc_id,'accounts_head_id':accounts_head_id,'account_type_id': account_type_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (account_name_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#account_name_id').append(div_data);
                    $(".selectval").select2();
                }
            });
        }
    }
    
    $(document).ready(function () {
        var accounts_head_id = '<?php echo set_value('accounts_head_id',$accounts_head_id) ?>';
        var account_type_id = '<?php echo set_value('account_type_id',$accounts_type_id) ?>';
        var account_name_id = '<?php echo set_value('account_name_id',$account_name_id) ?>';
        var brc_id = '<?php echo set_value('brc_id',$brc_id) ?>';
        getaccounttypeByAccounthead(accounts_head_id, account_type_id);
        getAccountNameByID(brc_id,accounts_head_id, account_type_id,account_name_id);
        $(document).on('change', '#accounts_head_id', function (e) {
            $('#account_type_id').html("");
            var accounts_head_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getBynewaccounts",
                data: {'accounts_head_id': accounts_head_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
                    });
                    $('#account_type_id').append(div_data);
                    $(".selectval").select2();
                }
            });
        });
        
        $(document).on('change', '#account_type_id', function (e) {
            $('#account_name_id').html("");
            var accounts_head_id = $("#accounts_head_id").val();
            var brc_id = $("#brc_id").val();
            var account_type_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getAccountsNameByID",
                data: {'brc_id':brc_id,'accounts_head_id':accounts_head_id,'account_type_id': account_type_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
                    });
                    $('#account_name_id').append(div_data);
                    $(".selectval").select2();
                }
            });
        });
    });
</script>