<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-shopping-cart"></i> <?php echo $this->lang->line('purchase'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary" id="bklist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('purchase_invoice'); ?>
                       </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="print-invoice">

                            <!-- View massage -->
                            <?php echo message_box('success'); ?>
                            <?php echo message_box('error'); ?>

                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <?php echo $this->setting_model->getCurrentSysName(); ?>
                                        <small class="pull-right"><?php echo $this->lang->line('date') ?>: <?= $order->date ?></small>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <?php echo $this->lang->line('billing_address') ?>
                                    <address>
                                        <strong><?= $vendor->company_name ?></strong><br>
                                        <?= $order->b_address ?><br>
                                        <?php echo $this->lang->line('phone') ?>: <?= $vendor->phone ?><br>
                                        <?php echo $this->lang->line('email') ?>: <?= $order->email ?>
                                    </address>
                                </div>
                                <!-- /.col -->

                                <div class="col-sm-4 invoice-col">

                                </div>

                                <!-- /.col -->

                                <div class="col-sm-4 invoice-col">
                                    <h3><?php echo $this->lang->line('purchase_ref#') ?><?= INVOICE_PRE + $order->id?></h3><br>
                                    <b><?php echo $this->lang->line('order_date') ?>:</b> <?php echo $order->date;//echo $this->localization->dateFormat($order->date)?><br>
                                    <b><?php echo $this->lang->line('billing_ref') ?>:</b> <?php echo $order->ref ?><br>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row" style="padding-top: 50px">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sl') ?>.</th>
                                            <th><?php echo $this->lang->line('product') ?></th>
                                            <th><?php echo $this->lang->line('description') ?></th>
                                            <th><?php echo $this->lang->line('price') ?></th>
                                            <th><?php echo $this->lang->line('qty') ?></th>
                                            <th><?php echo $this->lang->line('subtotal') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1 ; foreach ($order_details as $item){?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= $item->product_name ?></td>
                                                <td><?= $item->description ?></td>
                                                <td><?= $item->unit_price ?></td>
                                                <td><?= $item->qty ?></td>
                                                <td><?= $item->sub_total ?></td>
                                            </tr>
                                            <?php $i++; } ?>

                                        <?php if(!empty($return)){ ?>
                                            <?php $total_return = 0 ?>
                                            <tr class="warning">
                                                <td colspan="6"><strong><?php echo $this->lang->line('returned_items') ?></strong></td>
                                            </tr>
                                            <?php foreach ($return as $item){ ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= $item->product_name ?></td>
                                                    <td><?= $item->description ?></td>
                                                    <td><?= $item->unit_price ?></td>
                                                    <td><?= '-'.$item->qty ?></td>
                                                    <td><?= '-'. $item->sub_total ?></td>
                                                </tr>
                                                <?php $total_return += $item->sub_total ?>
                                            <?php $i++; } ?>

                                        <?php }?>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">

                                <!-- accepted payments column -->
                                <div class="col-xs-7">

                                    <?php if(!empty($order->order_note)){?>
                                            <p class="lead"><?php echo $this->lang->line('order_note') ?>:</p>
                                            <p>
                                                <?= $order->order_note ?>
                                            </p>
                                    <?php }?>



                                </div>
                                <!-- /.col -->
                                <div class="col-xs-5">

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%"><?php echo $this->lang->line('subtotal') ?>:</th>
                                                    <td><?= $order->cart_total ?></td>
                                                </tr>

                                                <?php if(!empty($return)){ ?>
                                                <tr>
                                                    <th style="width:50%"><?php echo $this->lang->line('total_return') ?>:</th>
                                                    <td>- <?= $total_return ?></td>
                                                </tr>
                                                <?php } ?>

                                                <tr>
                                                    <th><?php echo $this->lang->line('discount') ?>:</th>
                                                    <td>- <?= $order->discount ?></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('tax_amount') ?>:</th>
                                                    <td><?= $order->tax ?></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('shipping') ?>:</th>
                                                    <td><?= $order->shipping ?></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('grand_total') ?>:</th>
                                                    <td><?= $order->grand_total ?></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('paid') ?> :</th>
                                                    <td><?= $order->paid_amount ?></td>
                                                </tr>
                                                <tr>
                                                    <th><?php echo $this->lang->line('balance') ?> :</th>
                                                    <td><?= $order->due_payment ?></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>


                        <?php if(!empty($payment)){ ?>
                            <table class="table table-bordered">
                                <thead>
                                <tr class="info">
                                    <th><?php echo $this->lang->line('date') ?></th>
                                    <th><?php echo $this->lang->line('payment_ref') ?>.</th>
                                    <th><?php echo $this->lang->line('payment_method') ?></th>
                                    <th><?php echo $this->lang->line('amount') ?></th>
                                    <th><?php echo $this->lang->line('received_by') ?></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($payment as $id){ ?>
                                <tr>
                                    <td><?php dateFormat($id->payment_date) ?></td>
                                    <td><?php echo $id->order_ref ?></td>
                                    <td><?php echo $id->payment_method ?></td>
                                    <td><?php echo currency($id->amount) ?></td>
                                    <td><?php echo $id->received_by ?></td>
                                </tr>
                                <?php }?>

                                </tbody>
                            </table>
                        <?php } ?>


                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-xs-12">

                                <a id="printButton" class="btn btn-default"><i class="fa fa-print"></i> <?php echo $this->lang->line('print') ?></a>

                                <a onclick="return confirm('Are you sure want to delete this Invoice ?');" href="<?php echo base_url()?>admin/purchase/deletePurchase/<?php echo get_orderID($order->id) ?> " class="btn btn-danger pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete') ?>
                                </a>

                                <?php if($order->type != 'Return'){ ?>
                                <a href="<?php echo base_url()?>admin/purchase/returnPurchase/<?php echo get_orderID($order->id) ?> " data-target="#modalSmall" data-toggle="modal" class="btn btn-warning pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-angle-double-left"></i> <?php echo $this->lang->line('return_purchase') ?>
                                </a>

                                <a href="<?php echo base_url()?>admin/purchase/receivedProduct/<?php echo get_orderID($order->id) ?> "  data-target="#myModal" data-toggle="modal" class="btn btn-success pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-cube"></i> <?php echo $this->lang->line('received_product') ?>
                                </a>

                                <a href="<?php echo base_url()?>admin/purchase/paymentList/<?php echo get_orderID($order->id) ?> " data-target="#myModal" data-toggle="modal" class="btn bg-olive pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-money"></i> <?php echo $this->lang->line('view_payment') ?>
                                </a>

                                <a href="#" data-pur_id="<?php echo get_orderID($order->id) ?>" data-target="#modalSmall" data-toggle="modal" class="btn bg-purple pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-money"></i> <?php echo $this->lang->line('add_payment') ?>
                                </a>

                                <?php } ?>

                                <a href="<?php echo base_url()?>admin/purchase/pdfInvoice/<?php echo get_orderID($order->id) ?> "  class="btn btn-info pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> <?php echo $this->lang->line('generate_pdf') ?>
                                </a>

                                <a href="<?php echo base_url()?>admin/purchase/sendInvoice/<?php echo get_orderID($order->id) ?> " data-target="#modalSmall" data-toggle="modal" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-envelope"></i> <?php echo $this->lang->line('email') ?>
                                </a>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function(){
        $("#printButton").click(function(){
            var mode = 'iframe'; // popup
            var close = mode == "popup";
            var options = { mode : mode, popClose : close};
            $("div.print-invoice").printArea( options );
        });
        $('.addPayment').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
        $('#modalSmall').on('show.bs.modal', function (e) {
            $('#pur_id', this).val("");

            $('#pur_id', this).val($(e.relatedTarget).data('pur_id'));
            var pur_id = $(e.relatedTarget).data('pur_id');
            // var a = $(event.relatedTarget); // Button that triggered the modal
            // popup_target = a[0].id;
            // var button = $(event.relatedTarget); // Button that triggered the modal
            // console.log(popup_target);
            var $modalDiv = $(event.delegateTarget);
            $('.modal-addpayment-body').html("");
            $.ajax({
                type: "POST",
                url: baseurl + "admin/purchase/addPayment/",
                dataType: 'text',
                data: {'pur_id':pur_id},
                beforeSend: function () {

                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                    $('.modal-addpayment-body').html(data);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });


        });
    });
</script>

<div class="addPayment modal fade" id="modalSmall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_payment'); ?></h4>
            </div>
            <div class="modal-body modal-addpayment-body" style="padding: 8px;">
                <input type="hidden" name="pur_id"  id="pur_id" value="" />
            </div>
        </div>
    </div>
</div>