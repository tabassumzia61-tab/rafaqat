<?php $currency_symbol = $this->customlib->getSystemCurrencyFormat(); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <i class="fa fa-credit-card"></i> <?php echo $this->lang->line('expenses'); ?> <small><?php echo $this->lang->line('student_fee'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('staff_payment', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php //echo $title;      ?><?php echo $this->lang->line('edit').' '.$this->lang->line('staffpayment'); ?></h3>
                        </div><!-- /.box-header -->
                        <form id="form1" action="<?php echo base_url() ?>admin/payroll/staffpaymentedit/<?php echo $id; ?>/<?php echo $brach_id; ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>
                                <?php
                                if (isset($error_message)) {
                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php  
                                if ($this->rbac->hasPrivilege('superadmin')) { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label><small class="req"> *</small> 
                                        <select  id="brach_id" name="brach_id" class="form-control" onchange="get_data_by_campuss(this.value);">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($branchlist as $branch) {
                                                ?>
                                                <option value="<?php echo $branch['id'] ?>"<?php if (set_value('brach_id',$brach_id) == $branch['id']) echo "selected=selected" ?>><?php echo $branch['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('brach_id'); ?></span>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('staff'); ?></label> <small class="req">*</small>
                                    <select autofocus="" id="staff_id" name="staff_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($staffList as $staff) {
                                            ?>
                                            <option value="<?php echo $staff['id'] ?>" <?php
                                            if (set_value('staff_id',$staffpayment['staff_id']) == $staff['id']) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $staff['name'].' '.$staff['surname'].' ('.$staff['employee_id'].')'; ?></option>

                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('staff_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('voucher').' '.$this->lang->line('type'); ?></label> <small class="req">*</small>
                                    <select autofocus="" id="voucher_type_id" name="voucher_type_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <!-- <option value="1" ><?php //echo $this->lang->line('cr'); ?></option> -->
                                        <option value="2" <?php
                                            if (set_value('voucher_type_id',$staffpayment['voucher_type_id']) == 2) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $this->lang->line('cp'); ?></option>
                                        <!-- <option value="3"><?php //echo $this->lang->line('br'); ?></option> -->
                                        <option value="4" <?php
                                            if (set_value('voucher_type_id',$staffpayment['voucher_type_id']) == 4) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $this->lang->line('bp'); ?></option>
                                        <option value="5" <?php
                                            if (set_value('voucher_type_id',$staffpayment['voucher_type_id']) == 5) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo $this->lang->line('jv'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('voucher_type_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('mode'); ?></label> <small class="req">*</small>
                                    <select autofocus="" id="account_mode_id" name="account_mode_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('account_mode_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('account_head'); ?></label> <small class="req">*</small>
                                    <select id="allowance_type" name="acc_head_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                            foreach ($paytypeList as $type_val) { ?>
                                                <option value="<?php echo $type_val['id'] ?>" <?php if (set_value('acc_head_id',$staffpayment['acc_head_id']) == $type_val['id']) echo "selected=selected" ?>><?php echo $type_val['type']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('acc_head_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                                    <input id="date" name="date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date', date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($staffpayment['date']))); ?>" readonly="readonly" />
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label> <small class="req">*</small>
                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount',$staffpayment['amount']); ?>" />
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description',$staffpayment['note']); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div><!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('staff_payment', 'can_view')) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('staffpayment').' '.$this->lang->line('list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('staffpayment').' '.$this->lang->line('list'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('branch'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('account_head'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($staffotherpaymentList)) {
                                        ?>

                                        <?php
                                    } else {
                                        foreach ($staffotherpaymentList as $otherpayment) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $otherpayment['branch_name']; ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $otherpayment['name'].' '.$otherpayment['surname'] ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
                                                        if ($otherpayment['note'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $otherpayment['note']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo $otherpayment['accountsheadname']; ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($otherpayment['date'])) ?></td>
                                                <td class="mailbox-name"><?php echo $otherpayment['amount']; ?></td>
                                                <td class="mailbox-date text-right">
                                                    
                                                    <?php
                                                    if ($this->rbac->hasPrivilege('staff_payment', 'can_edit')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/payroll/staffpaymentedit/<?php echo $otherpayment['id'] ?>/<?php echo $otherpayment['brach_id']; ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('staff_payment', 'can_delete')) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/payroll/staffpaymentdelete/<?php echo $otherpayment['id'] ?>/<?php echo $otherpayment['brach_id'];?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table -->



                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->

        </div>
        <div class="row">
            <!-- left column -->

            <!-- right column -->
            <div class="col-md-12">

            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function () {
        var voucher_type_id =   '<?php echo set_value('voucher_type_id',$voucher_type_id) ?>';
        var account_mode_id =   '<?php echo set_value('account_mode_id',$account_mode_id) ?>';
        getaccountmodeID(voucher_type_id,account_mode_id);
        $(document).on('change', '#voucher_type_id', function (e) {
            $('#account_mode_id').html("");
            var voucher_type_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getaccountmodebyvoucher",
                data: {'voucher_type_id': voucher_type_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.id + ">" + obj.type + "</option>";
                    });
                    $('#account_mode_id').append(div_data);
                }
            });
        });
    });

    function getaccountmodeID(voucher_type_id,account_mode_id) {
        if (voucher_type_id != "" ) {
            $('#account_mode_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getaccountmodebyvoucher" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/"+url,
                data: {'voucher_type_id': voucher_type_id},
                dataType: "json",
                beforeSend: function(){
                    $('#account_mode_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (account_mode_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.type  + "</option>";
                    });
                    $('#account_mode_id').append(div_data);
                },  
                complete: function(){
                    $('#account_mode_id').removeClass('dropdownloading');
                }
            });
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var brach_id =   '<?php echo set_value('brach_id',$brach_id) ?>';
        var staff_id =   '';
        //getStaffBybrachID(brach_id,staff_id);
        $(document).on('change', '#brach_id', function (e) {
            $('#staff_id').html("");
            var brach_id = $(this).val();
            getStaffBybrachID(brach_id);

        });
    });

    function getStaffBybrachID(brach_id,staff_id) {
        if (brach_id != "" ) {
            $('#staff_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getStaffByBrach" ?>";
            $.ajax({
                type: "POST",
                url: base_url + "admin/payroll/"+url,
                data: {'brach_id': brach_id},
                dataType: "json",
                  beforeSend: function(){
                 $('#staff_id').addClass('dropdownloading');
                 },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (brach_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name +" "+ obj.surname +" ("+ obj.employee_id +")" + "</option>";
                    });
                    $('#staff_id').append(div_data);
                },  
               complete: function(){
              $('#staff_id').removeClass('dropdownloading');
               }
            });
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
       

        $("#btnreset").click(function () {
            $("#form1")[0].reset();
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
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
    function get_data_by_campuss(val){
        //alert();
        var url ='<?php echo site_url('admin/payroll/staffotherpayment/'); ?>'+val;          
        if(url){
            window.location.href = url; 
        }
    }
    function get_data_by_campus(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>