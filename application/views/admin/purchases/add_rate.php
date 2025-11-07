<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('purchase_add_rate_list'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <div class="col-md-12" style="padding:15px 0 0 0">
                            <form role="form" action="<?php echo site_url('admin/purchases/searchaddrate') ?>" method="post" id="searchform" class="searchform">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("product"); ?></label>
                                        <select name="product_id" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($itemcatlist as $pkey => $p_value) {  ?>
                                                <option <?php
                                                if (set_value('product_id') == $p_value["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $p_value['id'] ?>"><?php echo $p_value['name'] ?></option>
                                                <?php }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('product_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("supplier"); ?></label>
                                        <select name="supplier_id" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($supplierlist as $spkey => $sp_value) {  ?>
                                                <option <?php
                                                if (set_value('supplier_id') == $sp_value["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $sp_value['id'] ?>"><?php echo $sp_value['name'] ?></option>
                                                <?php }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('supplier_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('search_type'); ?></label> <small class="req"> *</small>
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
                                        <span class="text-danger" id="error_search_type"></span>
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
                        <div class="col-md-12" style="padding:0px">
                            <div class="mailbox-messages" style="margin-top: 15px;">
                                <div class="download_label"><?php echo $this->lang->line('purchases_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover addrate-list">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('reference_no'); ?></th>
                                            <th><?php echo $this->lang->line('supplier'); ?></th>
                                            <th><?php echo $this->lang->line('purchases_status'); ?></th>
                                            <th><?php echo $this->lang->line('product_qty'); ?></th>
                                            <th><?php echo $this->lang->line('khoya_qty'); ?></th>
                                            <th><?php echo $this->lang->line('attachment'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
<div class="myrecvdqtystatusModal modal fade" id="myrecvdqtystatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_received_quantity'); ?> </h4>
            </div>
            <div class="modal-body modal-revdqtystatus-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<div class="myrecvdqtyModal modal fade" id="myrecvdqtyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_received_quantity'); ?> </h4>
            </div>
            <div class="modal-body modal-revdqty-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<div class="mypaymentModal modal fade" id="mypaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_supplier_payment'); ?> </h4>
            </div>
            <div class="modal-body modal-payment-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<div class="mykhoyaqtyModal modal fade" id="mykhoyaqtyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_khoya_qty'); ?> </h4>
            </div>
            <div class="modal-body modal-khoyaqty-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<div class="myrateqtyModal modal fade" id="myrateqtyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_product_rate'); ?> </h4>
            </div>
            <div class="modal-body modal-rateqty-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<div class="mykhoyarateModal modal fade" id="mykhoyarateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title modal-titles" id="myModalLabel"><?php echo $this->lang->line('add_khoya_rate'); ?> </h4>
            </div>
            <div class="modal-body modal-khoyarate-body" style="padding: 8px;width: 100%;display: inline-block;">
            </div>
        </div>
    </div>
</div>
<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('addrate-list','admin/purchases/getaddratelist', [],[], 100,
            [
                //{ "bSortable": true, "aTargets": [ -2 ] ,'sClass': 'dt-body-right'},
                //{ "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}
            ]);
    });
} ( jQuery ) )
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('submit','#searchform',function(e){
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $(this).find("button[type=submit]:focus");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();
            form_data.push({name: 'button_type', value: $this.attr('value')});
            $.ajax({
                   url: url,
                   type: "POST",
                   dataType:'JSON',
                   data: form_data, // serializes the form's elements.
                      beforeSend: function () {
                        $('[id^=error]').html("");
                            $this.button('loading');
                            resetFields($this.attr('value'));
                       },
                      success: function(response) { // your success handler

                        if(!response.status){
                            $.each(response.error, function(key, value) {
                                $('#error_' + key).html(value);
                            });
                        }else{
                            initDatatable('addrate-list','admin/purchases/getsearchaddratelist',response.params);
                        }
                      },
                     error: function() { // your error handler
                         $this.button('reset');
                     },
                     complete: function() {
                     $this.button('reset');
                     }
                 });
        });
    });
    function resetFields(search_type){
        if(search_type == "search_full"){
            $('#search_type').val('');
        }else if (search_type == "search_filter") {
             $('#search_text').val("");
        }
    }
</script>
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
        $('.myrecvdqtyModal,.myrecvdqtystatusModal,.mypaymentModal,.mykhoyaqtyModal,.myrateqtyModal,.mykhoyarateModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#myrecvdqtystatusModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-revdqtystatus-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailrecvdqtystatus') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-revdqtystatus-body').html(response);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('#myrecvdqtyModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-revdqty-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailrecvdqty') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-revdqty-body').html(response);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('#mypaymentModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-payment-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailpayment') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-payment-body').html(response);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('#mykhoyaqtyModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-khoya-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailkhoya') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-khoyaqty-body').html(response);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('#myrateqtyModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-khoya-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailkrateqty') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-rateqty-body').html(response);
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });
        });

        $('#mykhoyarateModal').on('show.bs.modal', function (e) {
            var purchase_id = $(e.relatedTarget).data('purchase_id');
            var supplier_id = $(e.relatedTarget).data('supplier_id');
            var $modalDiv = $(event.delegateTarget);
            var baseUrl = '<?php echo base_url() ?>';
            $('.modal-khoya-body').html("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('admin/purchases/viewdetailkhoyarate') ?>",
                dataType: 'text',
                data: {'purchase_id':purchase_id,'supplier_id':supplier_id},
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (response) {
                    //console.log(response);
                    $('.modal-khoyarate-body').html(response);
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
    var base_url = '<?php echo base_url() ?>';
</script>