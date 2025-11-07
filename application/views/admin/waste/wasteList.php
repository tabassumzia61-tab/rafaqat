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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('waste_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('waste', 'can_add')) {?>
                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>admin/waste/create" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_waste_bill'); ?>
                                </a>
                            </small>
                        <?php }?>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg'); ?>
                        <?php } ?>
                        <div class="col-md-12" style="padding:15px 0 0 0">
                            <form role="form" action="<?php echo site_url('admin/waste/searchwaste') ?>" method="post" id="searchform" class="searchform">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("product"); ?></label>
                                        <select name="product_id" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($productslist as $p_key => $p_value) {  ?>
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
                                        <label><?php echo $this->lang->line("customer"); ?></label>
                                        <select name="customer_id" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($customerslist as $cukey => $cuvalue) {  ?>
                                                <option <?php
                                                if (set_value('customer_id') == $cuvalue["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $cuvalue['id'] ?>"><?php echo $cuvalue['name'] ?></option>
                                                <?php }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('customer_id'); ?></span>
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
                        <div class="col-md-12" style="padding:20px 0 0 0px">
                            <div class="mailbox-messages table-responsive overflow-visible">
                                <div class="download_label"><?php echo $this->lang->line('waste_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover waste-list">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('bill_no'); ?></th>
                                            <th><?php echo $this->lang->line('customer'); ?></th>
                                            <th><?php echo $this->lang->line('total'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table><!-- /.table -->
                            </div><!-- /.mail-box-messages -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('waste-list','admin/waste/getwastelist', [],[], 100,
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
                            initDatatable('waste-list','admin/waste/getsearchwastelist',response.params);
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