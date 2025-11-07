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
                    <form action="<?php echo site_url('admin/accounts/generalledger/'.$brc_id) ?>"  method="post" accept-charset="utf-8">
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
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('head'); ?></label><small class="req"> *</small> 
                                        <select  id="accounts_head_id" name="accounts_head_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($accountstypelist as $acc_type) { ?>
                                                <option value="<?php echo $acc_type['id'] ?>"<?php if (set_value('accounts_head_id',$accounts_head_id) == $acc_type['id']) echo "selected=selected" ?>><?php echo $acc_type['name'] ?></option>
                                            <?php }?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('accounts_head_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3" >
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('type'); ?></label><small class="req"> *</small> 
                                        <select  id="account_type_id" name="account_type_id" class="form-control selectval account_type_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('account_type_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3 aname" >
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('name'); ?></label><small class="req"> *</small> 
                                        <select  id="account_name_id" name="account_name_id" class="form-control selectval">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('account_name_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
                                <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('generalledger'); ?></h3>
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
                                                <th class="text text-right"><?php echo $this->lang->line('debit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('credit'); ?></th>
                                                <th class="text text-right"><?php echo $this->lang->line('balance'); ?></th>
                                            </tr>
                                        </thead> 
                                        <tbody id="tbldetail">
                                            <?php 
                                            $gtobam = 0;
                                            if(!empty($openbalance)){
                                                $total_debit_amount_ob = 0;
                                                $total_credit_amount_ob = 0;
                                                foreach($openbalance as $obkey => $obval){ 
                                                    if(!empty($obval['ob_dr'])){
                                                        $total_debit_amount_ob = $total_debit_amount_ob + $obval['ob_dr'];
                                                    }
                                                    if(!empty($obval['ob_cr'])){
                                                        $total_credit_amount_ob = $total_credit_amount_ob + $obval['ob_cr'];    
                                                    }
                                                }  ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Opening Balance</td>
                                                    <td class="text text-right"></td>
                                                    <td class="text text-right"></td>
                                                    <td class="text text-right">
                                                        <?php
                                                            if($accounts_head_id == "2"){
                                                                $gtobam = $total_credit_amount_ob - $total_debit_amount_ob;
                                                            }else if($accounts_head_id == "3"){
                                                                $gtobam = $total_credit_amount_ob - $total_debit_amount_ob;
                                                            }else if($accounts_head_id == "4"){
                                                                $gtobam = $total_credit_amount_ob - $total_debit_amount_ob;
                                                            }else{
                                                                $gtobam = $total_debit_amount_ob - $total_credit_amount_ob ;
                                                            }
                                                            echo number_format($gtobam, 2, '.', ','); 
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php 
                                                $count = 1; 
                                                $total_debit_amount = 0;
                                                $total_credit_amount = 0;
                                                if(!empty($genledgerlist)){
                                                    array_multisort(array_column($genledgerlist, 'date'), SORT_ASC, $genledgerlist);
                                                    foreach($genledgerlist as $legkey => $legval){ ?>
                                                    <tr>
                                                        <td><?php echo $count; ?></td>
                                                        <td><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($legval['date'])); ?></td>
                                                        <td><?php echo $legval['bill_no']; ?></td>
                                                        <!--<td></td>-->
                                                        <td><?php echo $legval['account_name']; ?></td>
                                                        <td><?php echo $legval['note']; ?></td>
                                                        <td class="text text-right">
                                                            <?php 
                                                                if(!empty($legval['dr'])){ 
                                                                    $total_debit_amount = $total_debit_amount + $legval['dr'];
                                                                    echo number_format($legval['dr'], 2, '.', ','); 
                                                                } 
                                                            ?>
                                                        </td>
                                                        <td class="text text-right">
                                                            <?php 
                                                                if(!empty($legval['cr'])){ 
                                                                    $total_credit_amount = $total_credit_amount + $legval['cr'];
                                                                    echo number_format($legval['cr'], 2, '.', ','); 
                                                                } 
                                                            ?>
                                                        </td>
                                                        <td class="text text-right">
                                                            <?php if($accounts_head_id == "2"){ ?>
                                                                <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount, 2, '.', ','); ?></b>
                                                            <?php }else if($accounts_head_id == "3"){ ?>
                                                                <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount, 2, '.', ','); ?></b>
                                                            <?php }else if($accounts_head_id == "4"){ ?>
                                                                <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount, 2, '.', ','); ?></b>
                                                            <?php }else { ?>
                                                                <b><?php echo number_format($gtobam + $total_debit_amount - $total_credit_amount, 2, '.', ','); ?></b>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php $count++; } ?>
                                                <?php } ?>
                                                <tr class="total-bg">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <!--<td></td>-->
                                                    <td></td>
                                                    <td class="text-right text-bold"><?php echo $this->lang->line('grand_total'); ?> :</td>
                                                    <td class="text-right text-bold"><?php echo number_format($total_debit_amount, 2, '.', ','); ?></td>
                                                    <td class="text-right text-bold"><?php echo number_format($total_credit_amount, 2, '.', ','); ?></td>
                                                    <td class="text-right text-bold">
                                                        <?php if($accounts_head_id == "2"){ ?>
                                                            <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount  , 2, '.', ','); ?></b>
                                                        <?php }else if($accounts_head_id == "3"){ ?>
                                                            <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount , 2, '.', ','); ?></b>
                                                        <?php }else if($accounts_head_id == "4"){ ?>
                                                            <b><?php echo number_format($gtobam + $total_credit_amount - $total_debit_amount , 2, '.', ','); ?></b>
                                                        <?php }else { ?>
                                                            <?php echo number_format($gtobam + $total_debit_amount - $total_credit_amount, 2, '.', ','); ?>
                                                        <?php } ?>
                                                    </td>
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
        var url ='<?php echo site_url('admin/accounts/generalledger/'); ?>'+val;          
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
<script type="text/javascript">
    var account_type_id = '<?php echo $accounts_type_id; ?>';
    window.onload = function(){
        if(account_type_id == 1){
            $('.aname').hide();
        }else if(account_type_id == 5){
            $('.aname').hide();
        }else if(account_type_id == 6){
            $('.aname').hide();
        }else if(account_type_id == 12){
            $('.aname').hide();
        }else if(account_type_id == 13){
            $('.aname').hide();
        }else if(account_type_id == 15){
            $('.aname').hide();
        }else if(account_type_id == 16){
            $('.aname').hide();
        }else if(account_type_id == 17){
            $('.aname').hide();
        }else{
            $('.aname').show();
        }
        
    }
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
            if(account_type_id == 1){
                $('.aname').hide();
            }else if(account_type_id == 5){
                $('.aname').hide();
            }else if(account_type_id == 6){
                $('.aname').hide();
            }else if(account_type_id == 12){
                $('.aname').hide();
            }else if(account_type_id == 13){
                $('.aname').hide();
            }else if(account_type_id == 15){
                $('.aname').hide();
            }else if(account_type_id == 16){
                $('.aname').hide();
            }else if(account_type_id == 17){
                $('.aname').hide();
            }else{
                $('.aname').show();
            }
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