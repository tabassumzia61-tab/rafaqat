<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if (($this->rbac->hasPrivilege('accounts_head', 'can_add')) || ($this->rbac->hasPrivilege('accounts_head', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/accounts/accountsheadedit/" . $id."/".$brc_id;
                        } else {
                            $url = base_url() . "admin/accounts/accountsheadcreate/".$brc_id;
                        }
                        ?>
                        <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php 
                                    if (!empty($name)) { ?>
                                        <input type="hidden" name="id" value="<?php echo set_value('id',$id); ?>" />
                                <?php } ?>
                                <?php  
                                if ($this->rbac->hasPrivilege('student', 'can_branch')) { ?>
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
                                <?php } ?>   
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
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('type'); ?></label><small class="req"> *</small> 
                                    <select  id="account_type_id" name="account_type_id" class="form-control selectval">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('account_type_id'); ?></span>
                                </div>
                                <div id="ooa" style="display: none;">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('name'); ?></label> <small class="req"> *</small>
                                        <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name',$name); ?>" />
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                    <div id="ob" style="display: none;">
                                        <!--<div class="form-group">-->
                                        <!--    <label for="exampleInputEmail1"><?php echo $this->lang->line('opening_balance').' '.$this->lang->line('date'); ?></label>-->
                                        <!--    <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php if(!empty($date)){ echo set_value('date', date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($date))); }else{ echo set_value('date', date($this->customlib->getSystemDateFormat())); } ?>" readonly="readonly" />-->
                                        <!--    <span class="text-danger"><?php echo form_error('date'); ?></span>-->
                                        <!--</div>-->
                                        <!--<div class="form-group">-->
                                        <!--    <label for="exampleInputEmail1"><?php echo $this->lang->line('opening_balance').' '.$this->lang->line('amount'); ?></label>-->
                                        <!--    <input id="opening_balance_amount" name="opening_balance_amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('opening_balance_amount',$amount); ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" />-->
                                        <!--    <span class="text-danger"><?php echo form_error('opening_balance_amount'); ?></span>-->
                                        <!--</div>-->
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description',$description); ?></textarea>
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div id="ooamsg" style="display: none;">
                                    <div class="alert alert-danger text-left trevd" style="display: none;">Please add "trade receivable" in the "customer" menu from "People" tab.</div>
                                    <div class="alert alert-danger text-left trpayabl" style="display: none;">Please add "trade Payable" in the "Supplier" menu from "People" tab.</div>
                                    <div class="alert alert-danger text-left invt" style="display: none;">Please add "Inventories" in the "Product/Service" menu from "Product/Service" tab.</div>
                                    <div class="alert alert-danger text-left salaies" style="display: none;">Please add "Salaries Payables" in the "Employees" menu from "People" tab.</div>
                                    <div class="alert alert-danger text-left sales" style="display: none;">"Sales" accounts cannot be created here. They are automatically generated when adding new products / services.</div>
                                    <div class="alert alert-danger text-left salesreturn" style="display: none;">"Sales Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchases" style="display: none;">"Purchases" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left purchasesreturn" style="display: none;">"Purchases Return" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                    <div class="alert alert-danger text-left costofsales" style="display: none;">"Cost of Sales" accounts cannot be created here. They are automatically generated when adding new products / services</div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
            if (($this->rbac->hasPrivilege('accounts_head', 'can_add') ) || ($this->rbac->hasPrivilege('accounts_head', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('accounts').' '.$this->lang->line('head').' '.$this->lang->line('list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('account').' '.$this->lang->line('head'); ?></th>
                                    <th><?php echo $this->lang->line('account').' '.$this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('account').' '.$this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($resultacclist)){ 
                                        foreach($resultacclist as $head_val){ ?>
                                            <tr>
                                                <td><?php echo $head_val->code.'. '.$head_val->name; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php 
                                            $accounttype = $head_val->newaccounts;
                                            foreach ($accounttype as $type_key => $type_val) { ?>
                                                <tr>
                                                    <td></td>
                                                    <td><?php echo $type_val->code.'. '.$type_val->name; ?></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php 
                                                    $accountlist = $this->accounts_model->getaccountsheadByID($type_val->id,$brc_id); 
                                                    if(!empty($accountlist)){
                                                        foreach($accountlist as $acc_key => $acc_val){ ?>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td><?php echo $acc_val['code'].'. '.$acc_val['name']; ?></td>
                                                                <td class="mailbox-date text-right">
                                                                    <?php if($acc_val['is_posted'] == 1){ ?>
                                                                        <button onclick="changestatuspost('<?php echo $acc_val['id']; ?>')" role="button" class="btn btn-success btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('is_posted'); ?>"><i class="fa fa-plus"></i></button>
                                                                    <?php }else{ ?>
                                                                        <button onclick="changestatuspost('<?php echo $acc_val['id']; ?>')" role="button" class="btn btn-danger btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('is_post'); ?>"> <i class="fa fa-plus"></i></button>
                                                                    <?php } ?>
                                                                    
                                                                    <?php if ($this->rbac->hasPrivilege('accounts_head', 'can_edit')) { 
                                                                        //if($head_val->is_active == 'yes'){ ?>
                                                                            <a href="<?php echo base_url(); ?>admin/accounts/accountsheadedit/<?php echo $acc_val['id'] ?>/<?php echo $acc_val['brc_id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                                <i class="fa fa-pencil"></i>
                                                                            </a>
                                                                        <?php //} ?>
                                                                    <?php } ?>
                                                                    <?php if($acc_val['is_active'] == 'yes'){ ?>
                                                                        <button onclick="changestatus('<?php echo $acc_val['id']; ?>')" role="button" class="btn btn-success btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('active'); ?>"><i class="fa fa-check"></i></button>
                                                                    <?php }else{ ?>
                                                                        <button onclick="changestatus('<?php echo $acc_val['id']; ?>')" role="button" class="btn btn-danger btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('in_active'); ?>"> <i class="fa fa-remove"></i></button>
                                                                    <?php } ?>
                                                                </td> 
                                                            </tr>       
                                                        <?php } ?>
                                                    <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>
<script type="text/javascript">
    window.onload = function(){  
        var account_type_id = '<?php echo $account_type_id; ?>';  
        if(account_type_id == 1){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.trevd').show();
            $('.invt').hide();
            $('.trpayabl').hide();
            //$('.salaies').hide();
            $('.sales').hide();
            $('.salesreturn').hide();
            $('.purchases').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
        }else if(account_type_id == 5){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.invt').show();
            $('.trevd').hide();
            $('.trpayabl').hide();
            //$('.salaies').hide();
            $('.sales').hide();
            $('.salesreturn').hide();
            $('.purchases').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
        }else if(account_type_id == 6){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.trpayabl').show();
            $('.trevd').hide();
            $('.invt').hide();
            //$('.salaies').hide();
            $('.sales').hide();
            $('.salesreturn').hide();
            $('.purchases').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
        }else if(account_type_id == 12){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.sales').show();
            $('.salesreturn').hide();
            $('.purchases').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
            //$('.salaies').hide();
            $('.trevd').hide();
            $('.invt').hide();
            $('.trpayabl').hide();
        }else if(account_type_id == 13){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.salesreturn').show();
            $('.sales').hide();
            $('.purchases').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
            //$('.salaies').hide();
            $('.trevd').hide();
            $('.invt').hide();
            $('.trpayabl').hide();
        }else if(account_type_id == 15){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.purchases').show();
            $('.salesreturn').hide();
            $('.sales').hide();
            $('.purchasesreturn').hide();
            $('.costofsales').hide();
            //$('.salaies').hide();
            $('.trevd').hide();
            $('.invt').hide();
            $('.trpayabl').hide();
        }else if(account_type_id == 16){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.purchasesreturn').show();
            $('.costofsales').hide();
            $('.purchases').hide();
            $('.salesreturn').hide();
            $('.sales').hide();
            //$('.salaies').hide();
            $('.trevd').hide();
            $('.invt').hide();
            $('.trpayabl').hide();
        }else if(account_type_id == 17){
            $('#ooa').hide();
            $('#ooamsg').show();
            $('.costofsales').show();
            $('.purchasesreturn').hide();
            $('.purchases').hide();
            $('.salesreturn').hide();
            $('.sales').hide();
            //$('.salaies').hide();
            $('.trevd').hide();
            $('.invt').hide();
            $('.trpayabl').hide();
        }else{
            $('#ooamsg').hide();
            $('#ooa').show();
        }
    }  
    $(document).ready(function () {
        $(document).on('change', '#account_type_id', function (e) {
            var account_type_id = $(this).val();
            if(account_type_id == 1){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.trevd').show();
                $('.invt').hide();
                $('.trpayabl').hide();
                //$('.salaies').hide();
                $('.sales').hide();
                $('.salesreturn').hide();
                $('.purchases').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
            }else if(account_type_id == 5){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.invt').show();
                $('.trevd').hide();
                $('.trpayabl').hide();
                //$('.salaies').hide();
                $('.sales').hide();
                $('.salesreturn').hide();
                $('.purchases').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
            }else if(account_type_id == 6){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.trpayabl').show();
                $('.trevd').hide();
                $('.invt').hide();
                //$('.salaies').hide();
                $('.sales').hide();
                $('.salesreturn').hide();
                $('.purchases').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
            }else if(account_type_id == 12){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.sales').show();
                $('.salesreturn').hide();
                $('.purchases').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
                //$('.salaies').hide();
                $('.trevd').hide();
                $('.invt').hide();
                $('.trpayabl').hide();
            }else if(account_type_id == 13){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.salesreturn').show();
                $('.sales').hide();
                $('.purchases').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
                //$('.salaies').hide();
                $('.trevd').hide();
                $('.invt').hide();
                $('.trpayabl').hide();
            }else if(account_type_id == 15){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.purchases').show();
                $('.salesreturn').hide();
                $('.sales').hide();
                $('.purchasesreturn').hide();
                $('.costofsales').hide();
               // $('.salaies').hide();
                $('.trevd').hide();
                $('.invt').hide();
                $('.trpayabl').hide();
            }else if(account_type_id == 16){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.purchasesreturn').show();
                $('.costofsales').hide();
                $('.purchases').hide();
                $('.salesreturn').hide();
                $('.sales').hide();
                //$('.salaies').hide();
                $('.trevd').hide();
                $('.invt').hide();
                $('.trpayabl').hide();
            }else if(account_type_id == 17){
                $('#ooa').hide();
                $('#ooamsg').show();
                $('.costofsales').show();
                $('.purchasesreturn').hide();
                $('.purchases').hide();
                $('.salesreturn').hide();
                $('.sales').hide();
                //$('.salaies').hide();
                $('.trevd').hide();
                $('.invt').hide();
                $('.trpayabl').hide();
            }else{
                $('#ooamsg').hide();
                $('#ooa').show();
            }
        });
    });
</script>  
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/accounts/accountshead/'); ?>'+val;          
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
    function changestatuspost(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/accounts/changestatuspost',
            type: 'POST',
            data: {'id': id},
            dataType: "json",
            success: function (data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message); 
                    window.location.reload(true); 
                }
            }
        });
    }
    
    function changestatus(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/accounts/changestatus',
            type: 'POST',
            data: {'id': id},
            dataType: "json",
            success: function (data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message); 
                    window.location.reload(true); 
                }
            }
        });
    }
    
    function getaccounttypeByAccounthead(accounts_head_id, account_type_id) {
        if (accounts_head_id != "") {
            if(accounts_head_id == 1){
                $('#ob').show();
            }else if(accounts_head_id == 2){
                $('#ob').show();
            }else if(accounts_head_id == 3){
                $('#ob').show();
            }else if(accounts_head_id == 4){
                $('#ob').hide();
            }else if(accounts_head_id == 5){
                $('#ob').hide();
            }
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
    
    $(document).ready(function () {
        var accounts_head_id = '<?php echo set_value('accounts_head_id',$accounts_head_id) ?>';
        var account_type_id = '<?php echo set_value('account_type_id',$account_type_id) ?>';
        getaccounttypeByAccounthead(accounts_head_id, account_type_id);
        $(document).on('change', '#accounts_head_id', function (e) {
            var accounts_head_id = $(this).val();
            if(accounts_head_id == 1){
                $('#ob').show();
            }else if(accounts_head_id == 2){
                $('#ob').show();
            }else if(accounts_head_id == 3){
                $('#ob').show();
            }else if(accounts_head_id == 4){
                $('#ob').hide();
            }else if(accounts_head_id == 5){
                $('#ob').hide();
            }
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
                        div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
                    });
                    $('#account_type_id').append(div_data);
                    $(".selectval").select2();
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.show_detail_popover').html();
            }
        });
    });
</script>
