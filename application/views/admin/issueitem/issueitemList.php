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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('issueitem_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('issueitem', 'can_add')) {?>
                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>admin/issueitem/create" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_issueitem'); ?>
                                </a>
                            </small>
                        <?php }?>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <div class="col-md-12" style="padding:15px 0 0 0">
                            <form role="form" action="<?php echo site_url('admin/issueitem/searchissueitem') ?>" method="post" id="searchform" class="searchform">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line("supervisor"); ?></label>
                                        <select name="supervisor_id" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line("select"); ?></option>
                                            <?php foreach ($supervisor as $spkey => $sp_value) {  ?>
                                                <option <?php
                                                if (set_value('supervisor_id') == $sp_value["id"]) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $sp_value['id'] ?>"><?php echo $sp_value['name'] ?></option>
                                                <?php }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('supervisor_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('shift'); ?></label>
                                        <select  id="shift" name="shift" class="form-control js-example-basic-single">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                             $st = ['Morning' => $this->lang->line('morning'), 'Evening' => $this->lang->line('evening'),'Night' => $this->lang->line('night')];
                                            foreach ($st as $key => $val) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (set_value('shift') == $key) echo "selected=selected" ?>><?php echo $val ?></option>
                                                <?php
                                                
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('shift'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('department'); ?></label>
                                        <select  id="dept_id" name="dept_id" class="form-control js-example-basic-single"  >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($department as $deptval) {
                                                ?>
                                                <option value="<?php echo $deptval['id'] ?>" <?php if (set_value('dept_id') == $deptval['id']) echo "selected=selected" ?>><?php echo $deptval['department_name'] ?></option>
                                                <?php
                                                
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('dept_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
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
                        <div class="col-md-12" style="padding:15px 0 0 0">
                            <div class="mailbox-messages table-responsive overflow-visible">
                                <div class="download_label"><?php echo $this->lang->line('issueitem_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover issueitem-list">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('reference_no'); ?></th>
                                            <th><?php echo $this->lang->line('supervisor'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('product_name'); ?></th>
                                            <th><?php echo $this->lang->line('qty'); ?></th>
                                            <th><?php echo $this->lang->line('attachment'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table><!-- /.table -->
                            </div><!-- /.mail-box-messages -->
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
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
<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('issueitem-list','admin/issueitem/getissueitemlist', [],[], 100,
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
                            initDatatable('issueitem-list','admin/issueitem/getsearchissueitemlist',response.params);
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
<script type="text/javascript">
    function viewDetail(id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/issueitem/getBillDetails/' + id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdata').html(data);
                $('#edit_deletebill').html("");
                holdModal('viewModal');
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