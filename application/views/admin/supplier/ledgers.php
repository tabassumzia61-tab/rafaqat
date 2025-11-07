<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
$language = $this->customlib->getLanguage();
$language_name = $language["short_code"];
?>
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
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1>
                    <i class="fa fa-money"></i> <?php echo $this->lang->line('supplier').' '.$this->lang->line('ledger'); ?><small><?php echo $this->lang->line('supplier').' '.$this->lang->line('ledger'); ?></small></h1>
            </section>
        </div>
    </div>
    <!-- /.control-sidebar -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title"><?php echo $this->lang->line('supplier')." ".$this->lang->line('ledger'); ?><small title="<?php echo $itemsupplier['phone']; ?>">( 
                                    <?php 
                                        echo $itemsupplier['name']." / " .$itemsupplier['phone'];
                                         ?> )
                                    </small></h3>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group pull-right">
                                    <span style="font-weight: bold;color: red;font-size: 18px;margin-right: 15px;" class="total_payable"></span>
                                    <a href="<?php echo base_url() ?>admin/supplier" type="button" class="btn btn-primary btn-xs pull-right" style="margin-left:10px;">
                                        <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                                    <a href="#" class="btn btn-primary btn-xs pull-right" style="margin-left:10px;" data-toggle="modal" data-target="#mypaymentModal" data-supplier_id="<?php echo $itemsupplier['id']; ?>" data-toggle="tooltip" class=""  title="<?php echo $this->lang->line('add_payment'); ?>"><i class="fa fa fa-money"></i> <?php echo $this->lang->line('add_payment'); ?></a>
                                    <a href='<?php echo base_url(); ?>report/pdfsupplierledgers?supplier_id_id=<?php echo $itemsupplierID; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>' class='btn bg-orange btn-xs pull-right' style="margin-left:10px;"><i class='fa fa-download'></i> <?php echo $this->lang->line('supplier')." ".$this->lang->line('ledger'); ?> PDF</a>
                                </div>
                            </div>
                        </div>
                    </div><!--./box-header-->
                    <div class="box-body" style="padding-top:0;">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <div class="col-md-12" style="padding:15px 0 40px 0">
                            <form role="form" action="<?php echo site_url('admin/supplier/ledgers/'.$itemsupplierID) ?>" method="post" id="searchform" class="searchform">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('search_type'); ?></label><small class="req"> *</small>
                                        <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                            <?php foreach ($searchlist as $key => $search) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php
                                                if ((isset($search_type)) && ($search_type == $key)) {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $search ?></option>
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
                            </form>
                        </div>
                        <div class="col-md-12" style="padding:15px 0 0 0">
                            <div class="table-responsive mailbox-messages">
                                <div class="download_label"><?php echo $itemcustomers['name']; ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('document_no'); ?></th>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th><?php echo $this->lang->line('debit'); ?></th>
                                            <th><?php echo $this->lang->line('credit'); ?></th>
                                            <th><?php echo $this->lang->line('balance'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="debit">
                                        <?php //if($balam > 0){ ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td> </td>
                                            <td class="text-right text-bold"><?php echo $this->lang->line('opening_balance'); ?></td>
                                            <td class="text-right total_debit"><?php echo $balam; ?></td>
                                            <td class="text-right total_credit">0</td>
                                            <td class="text-right balance">0</td>
                                        </tr>
                                        <?php //} ?>
                                        <?php if(!empty($ledgerlist)){
                                            array_multisort(array_column($ledgerlist, 'date'), SORT_ASC, $ledgerlist);
                                            $count = 1;
                                            foreach($ledgerlist as $led_key => $led_val){ ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php if(!empty($led_val['date']) && $led_val['date'] !='0000-00-00'){  echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($led_val['date'])); } ?></td>
                                                    <td>
                                                        <?php if(!empty($led_val['amount'])){ ?>
                                                        <a href="#" onclick="viewDetail('<?php echo $led_val['id']; ?>')" data-toggle="tooltip"  title="<?php echo $this->lang->line("show"); ?>" ><?php echo $led_val['bill_no']; ?></a>
                                                        <?php }else{ ?>
                                                            <a href="#" onclick="viewpaymentDetail('<?php echo $led_val['id']; ?>')" data-toggle="tooltip"  title="<?php echo $this->lang->line("show"); ?>" ><?php echo $led_val['bill_no']; ?></a>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo $led_val['note']; ?></td>
                                                    <td class="text-right total_debit"><?php if(!empty($led_val['paid_amount'])){ echo (float)$led_val['paid_amount']; } ?></td>
                                                    <td class="text-right total_credit"><?php if(!empty($led_val['amount'])){ echo (float)$led_val['amount']; } ?></td>
                                                    <td class="text-right balance">0</td>
                                                </tr>
                                            <?php $count++; } ?>
                                            <tr class="total-bg">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right text-bold"><?php echo $this->lang->line('grand_total'); ?> :</td>
                                                <td class="text-right text-bold total_debit_amount"></td>
                                                <td class="text-right text-bold total_credit_amount"></td>
                                                <td class="text-right text-bold total_balance_amount"></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>
<div class="mypaymentModal modal fade" id="mypaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_payment'); ?> </h4>
            </div>
            <div class="modal-body modal-payment-body" style="padding: 8px;width: 100%;display: inline-block;">
                <form id="accvoucherform" action="<?php echo base_url() ?>admin/payments/savepaymentout" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="col-md-12">
                    <div class="row">
                        <input type="hidden" name="supplier_id" value="<?php echo $itemsupplier['id'] ?>" />
                        <input type="hidden" name="payment_to" value="1" />
                        <input type="hidden" name="brc_id" value="<?php echo $itemsupplier['brc_id'] ?>" />
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('payment').' '.$this->lang->line('date'); ?></label> <small class="req">*</small>
                                <input id="payment_date" name="payment_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('payment_date', date($this->customlib->getSystemDateFormat())); ?>" readonly="readonly" />
                                <span class="text-danger"><?php echo form_error('payment_date'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('received').' From '; ?></label> <small class="req">*</small>
                                <select autofocus="" id="received_in_id" name="received_in_id" class="form-control received_in_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($acclist as $acc_val) { ?>
                                        <option value="<?php echo $acc_val['id'] ?>" <?php if (set_value('received_in_id') == $acc_val['id']) echo "selected=selected"; ?>><?php echo $acc_val['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('received_in_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label> <small class="req">*</small>
                                <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount'); ?>" />
                                <span class="text-danger"><?php echo form_error('amount'); ?></span>
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
                    <div class="row">
                        <div class="pull-right">
                            <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="saveprint" class="btn btn-info printsavebtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?></button>                        
                            <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="" id="savenew" class="btn btn-info savenew"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save_new'); ?></button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewpaymentModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div style="float: right;"> 
                    <div style="float: right;" id='edit_deletebill'>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('bill') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="paymentsdata"></div>
            </div>
        </div>
    </div>    
</div>
<script>
<?php
if ($search_type == 'period') {
    ?>
        $(document).ready(function () {
            showdate('period');
        });
    <?php
}
?>
</script>
<script type="text/javascript">
    $(document).ready(function (e) {
        $('.mypaymentModal','.myeditpaymentModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });

</script>
<script type="text/javascript">
    var htmlData = "";
    $(document).ready(function () {
        $(document).on('click','.printsavebtn', function (e) {
            var $this = $(this);
            $this.button('loading');
            var formData = new FormData($('#accvoucherform')[0]);
            $.ajax({
                url: '<?php echo site_url("admin/payments/printpaymentout") ?>',
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
                url: '<?php echo site_url("admin/payments/savepaymentout") ?>',
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
<div class="delmodal modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('confirmation'); ?></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure want to delete <b class="invoice_no"></b> invoice, this action is irreversible.</p>
                <p>Do you want to proceed?</p>
                <p class="debug-url"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <a class="btn btn-danger btn-ok"><?php echo $this->lang->line('revert'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewModal"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div style="float: right;"> 
                    <div style="float: right;" id='edit_deletebill'>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('bill') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="reportdata"></div>
            </div>
        </div>
    </div>    
</div>
<script type="text/javascript">
    function viewDetail(id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/purchases/getBillDetails/' + id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdata').html(data);
                // $('#edit_deletebill').html("<a href='#' class='btn btn-info' style='color:#fff;float: left;padding: 2px 10px 0 0;' data-toggle='tooltip' onclick='printData(" + id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a>");
                holdModal('viewModal');
            },
        });
    }
    function viewpaymentDetail(id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/supplier/getPaymentBill/' + id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#paymentsdata').html(data);
                // $('#edit_deletebill').html("<a href='#' class='btn btn-info' style='color:#fff;float: left;padding: 2px 10px 0 0;' data-toggle='tooltip' onclick='printData(" + id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a>");
                holdModal('viewpaymentModal');
            },
        });
    }
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        //generateBillNo()
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#select_all_fee').on('click', function () {
            if (this.checked) {
                var totals = 0;
                $('.checkboxs').each(function () {
                    totals += Number($(this).val());
                    $(this).closest("tr").find('.input_amount').find('input').val($(this).val());
                    $(this).closest("tr").find('.input_amount').addClass("hasVal");
                    $(this).closest("tr").find('.input_amount').find('input').addClass("hasVal");
                    this.checked = true;
                });
                $("tr#total-fee").find('td#total-fee-amount').text(Number(totals));
            } else {
                $('.checkboxs').each(function () {
                    $(this).closest("tr").find('.input_amount').find('input').val('');
                    $(this).closest("tr").find('.input_amount').removeClass("hasVal");
                    $(this).closest("tr").find('.input_amount').find('input').removeClass("hasVal");
                    this.checked = false;
                });
                $("tr#total-fee").find('td#total-fee-amount').text(Number(0));
            }
        });

        $('.checkbox').on('click', function () {
            if ($('.checkboxs:checked').length == $('.checkboxs').length) {
                $('#select_all_fee').prop('checked', true);
            } else {
                $('#select_all_fee').prop('checked', false);
            }
        });

        $('.checkboxs').on('click', function () {
            if (this.checked) {
                $(this).closest("tr").find('.input_amount').find('input').val($(this).val());
                $(this).closest("tr").find('.input_amount').addClass("hasVal");
                $(this).closest("tr").find('.input_amount').find('input').addClass("hasVal");
            }else{
                $(this).closest("tr").find('.input_amount').find('input').val('');
                $(this).closest("tr").find('.input_amount').removeClass("hasVal");
                $(this).closest("tr").find('.input_amount').find('input').removeClass("hasVal");
            }
            var sum = 0;
            $("tr#total-fee").find('td#total-fee-amount').text('');
            $('input.hasVal').each(function(){
              sum += Number($(this).val());
            });
            $("tr#total-fee").find('td#total-fee-amount').text(sum);
        });
    });
</script>
<script type="text/javascript">
    var htmlData = "";
    $(document).ready(function () {
        var prev_balance = 0;
        var credit = 0;
        var debit = 0;
        var waiveoff = 0;
        // var debit = 0;
        var balance = 0;
        var next_debit = 0;
        var new_balance = 0;
        $("#debit tr:first").find(".balance").text($("#debit tr:first").find(".total_debit").text());
        $("#debit tr").each(function(){
            balance = $(this).find(".balance").text();
            debit = $(this).find(".total_debit").text();
            $(this).find(".balance").text(Number(balance) - Number(debit));
            prev_balance = $(this).find(".balance").text();
            next_debit = $(this).next("tr").find(".total_credit").text();
            new_balance = Number(prev_balance) + Number(next_debit);
            $(this).next("tr").find(".balance").text(new_balance);
        });
        var total_credit = 0;
        var total_debit = 0;
        var total_balance = 0;
        $("#debit tr").each(function(){
            var curr_credit = $(this).find(".total_credit").text();
            total_credit += Number(curr_credit);
            var curr_debit = $(this).find(".total_debit").text();
            total_debit += Number(curr_debit);
            var curr_balance = $(this).find(".balance").text();
            total_balance += Number(curr_balance);
        });
        $("#debit tr").find('.total_credit_amount').text(total_credit);
        $("#debit tr").find('.total_debit_amount').text(total_debit);
        $("#debit tr").find('.total_balance_amount').text(Number(total_credit) - Number(total_debit));
        $('.total_payable').text('<?php echo $this->lang->line('payable').": "; ?>' + ( Number(total_credit) - Number(total_debit)) );
    });

    $(document).ready(function () {
        var lastdate = $('.forenterdata:last .datess').text();
        var descption = $('.forenterdata:last .descption').text();
        var lastcredit = $('.forenterdata:last .total_credit').text();
        var lastdebit = $('.forenterdata:last .total_debit').text();
        //alert($('.forenterdata:last .total_credit').text());
        $('.datesss').html(lastdate);
        $('.descptions').html(descption);
        if (lastcredit !='') {
            $('.amountsssc').html('C '+lastcredit);
        }else{
            $('.amountsssd').html('D '+ lastdebit);
        }
        $(document).on('click', '#received', function (e) {
            $(".descriptions").html('');
            var masg = 'Cash Received';
            $(".descriptions").val(masg);
        });
        $(document).on('click', '#add_charges', function (e) {
            $(".descriptions").html('');
            var masg = 'Add Charges';
            $(".descriptions").val(masg);
        });
        $(document).on('click', '#waive_off', function (e) {
            $(".descriptions").html('');
            var masg = 'Waive Off';
            $(".descriptions").val(masg);
        });

        $(document).on('click', '.closebtnfee', function (e) {
             window.location.reload(true);
        });
        
        $(document).on('click', '.cancelbtnfee', function (e) {
             window.location.reload(true);
        });

        $(document).on('change', '.input_amount input', function(){
            if($(this).val() == "" || $(this).val() == null || $(this).val() == "undefined"){
                $(this).removeClass("hasVal");
                $(this).parent(".input_amount").removeClass("hasVal");
            }
            else{
                $(this).addClass("hasVal");   
                $(this).parent(".input_amount").addClass("hasVal");
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.delmodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#confirm-delete').on('click', '.btn-ok', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var arrdel = [];
            $('input:checkbox[name="check[]"]:checked').each(function(){
                supplier_id = $(this).attr("data-supplier_id");
                main_invoice_detail  = $(this).attr("data-main_invoice_detail");
                $(this).val();
                arrdel.push({
                    'id': main_invoice_detail,
                    'supplier_id': supplier_id,
                });
            });
            var del_list = (!jQuery.isEmptyObject(arrdel)) ? JSON.stringify(arrdel) : "";
            $modalDiv.addClass('modalloading');
            $.ajax({
                type: "post",
                url: '<?php echo site_url("admin/supplier/deleteById") ?>',
                dataType: 'JSON',
                data: {'del_list':del_list},
                success: function (data) {
                    $modalDiv.modal('hide').removeClass('modalloading');
                    if (data.status == "fail") {
                        errorMsg(data.message);
                    } else {
                        successMsg(data.message);
                        location.reload(true);
                    }
                }
            });
        });
    });
</script>