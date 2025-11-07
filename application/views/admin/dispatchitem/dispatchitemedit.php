<div class="content-wrapper">  
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <form id="purchase_edit_sale_from" action="<?php echo site_url('admin/dispatchitem/save_edit_dispatchitem/').$result["id"]; ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="tshadow mb25 bozero">    

                                <h4 class="pagetitleh2"><?php echo $this->lang->line('edit_dispatchitem'); ?> </h4>


                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>  
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" id="dispatchitem_bill_id" name="dispatchitem_bill_id" value="<?php echo set_value('dispatchitem_bill_id',$result['id']); ?>" />
                                    <?php 
                                        $getproductdetail = $this->dispatchitem_model->getbilldetilproductbyid($result["id"]); 
                                        if (!empty($getproductdetail)) {
                                            foreach ($getproductdetail as $detil_key => $detilval) { ?>
                                                <input type="hidden" name="detil_id[]" value="<?php echo set_value('detil_id',$detilval['id']); ?>" />
                                        <?php         
                                            }
                                        }
                                    ?>
                                    <h5 class="pagetitleh2" style="font-size:14px;margin-bottom:10px;"><?php echo $this->lang->line('basic_information'); ?> </h5>
                                    <div class="row" style="padding:7px 15px 0px 17px;">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                                                <small class="req" style="color:red;"> *</small> 
                                                <input name="date" id="datetime_twelve_hour"  type="text" value="<?php echo set_value('date', $this->customlib->YYYYMMDDHisTodateFormat($result['datetime'],'12-hour')); ?>" class="form-control datetime_twelve_hour"/>
                                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('reference_no'); ?></label>
                                                <small class="req" style="color:red;"> *</small> 
                                                <input name="reference_no" id="reference_no" type="text" class="form-control" value="<?php echo $result['bill_no']; ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('reference_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('warehouse'); ?></label> <small class="req">*</small>
                                                <select  id="warehouse_id" name="warehouse_id" class="form-control js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($warehouseslist as $warehouse) {
                                                        ?>
                                                        <option value="<?php echo $warehouse['id'] ?>" <?php if (set_value('warehouse_id',$result['warehouse_id']) == $warehouse['id']) echo "selected=selected" ?>><?php echo $warehouse['name'] ?></option>
                                                        <?php
                                                        
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('warehouse_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('shift'); ?></label> <small class="req">*</small>
                                                <select  id="shift" name="shift" class="form-control js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                     $st = ['Morning' => $this->lang->line('morning'), 'Evening' => $this->lang->line('evening'),'Night' => $this->lang->line('night')];
                                                    foreach ($st as $key => $val) {
                                                        ?>
                                                        <option value="<?php echo $key ?>" <?php if (set_value('shift',$result['shift']) == $key) echo "selected=selected" ?>><?php echo $val ?></option>
                                                        <?php
                                                        
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('shift'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('department'); ?></label> <small class="req">*</small>
                                                <select  id="dept_id" name="dept_id" class="form-control js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($department as $deptval) {
                                                        ?>
                                                        <option value="<?php echo $deptval['id'] ?>" <?php if (set_value('dept_id',$result['dept_id']) == $deptval['id']) echo "selected=selected" ?>><?php echo $deptval['department_name'] ?></option>
                                                        <?php
                                                        
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('dept_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                                <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
                                                <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="pagetitleh2" style="font-size:14px;margin-bottom:10px;"><?php echo $this->lang->line('supervisor_information'); ?> </h5>
                                    <div class="row" style="padding:7px 15px 0px 17px;">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supervisor'); ?></label> <small class="req">*</small>
                                                <select  id="supervisor_id" name="supervisor_id" class="form-control js-example-basic-single"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($supervisor as $val) {
                                                        ?>
                                                        <option value="<?php echo $val['id'] ?>" <?php if (set_value('supervisor_id',$result['supervisor_id']) == $val['id']) echo "selected=selected" ?>><?php echo $val['name'] ?></option>
                                                        <?php
                                                        
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('supervisor_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                                <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name',$result['name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="pagetitleh2" style="font-size:14px;margin-bottom:10px;margin-top:10px;"><?php echo $this->lang->line('product_information'); ?> </h5>
                                    <div id="cart_view" class="row" style="padding:7px 15px 0px 17px;">
                                       <?php echo $this->view('admin/dispatchitem/cart/add_product_cart','', TRUE); ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('note') ?></label>
                                                <textarea class="form-control" name="order_note"><?php if(!empty($result))echo $result['description']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary purchase_edit_sale_add pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>               
            </div>
        </div> 
</div>
</section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.purchase_edit_sale_add', function (e) {
            var itemsel = $('.itemsel').val();
            if(itemsel == ''){
                errorMsg('Please Select Before Product');
            }else{
                var $this = $(this);
                var $id = $('#dispatchitem_bill_id').val();
                var formData = new FormData($('#purchase_edit_sale_from')[0]);
                $this.button('loading');
                setTimeout(function () {
                    $.ajax({
                        url: '<?php echo site_url("admin/dispatchitem/save_edit_dispatchitem/") ?>'+$id,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
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
        
                            $this.button('reset');
                        }
                });
                }, 1000);
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
        var date_format = '<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({
            format: date_format,
            autoclose: true
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '#vendor_id', function (e) {
            $('#email').html("");
            $('#b_address').html("");
            var vendor_id = $(this).val();
            $.ajax({
                url: '<?php echo site_url("admin/purchase/getvendorbyid") ?>',
                type: 'post',
                data: {'vendor_id':vendor_id},
                dataType: 'json',
                success: function (data) {
                   $('#b_address').val(data.b_address);
                   $('#email').val(data.email);
                }
            });
        });
        var date_format = '<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('.datetime').datepicker({
            format: date_format,
            autoclose: true
        });
    });
</script>
<script lang="javascript">
    $(document).ready(function() {
        //***************** Tier Price Option Start *****************//
        $(".addTire").click(function() {
            var row = '';
            row += '<tr>';
            row += '<td><div class="form-group form-group-bottom">1</div></td>';
            row += '<td>';
            row += '<div class="form-group form-group-bottom p_div">';
            row += '<select class="form-control" style="width: 100%">';
            row += '<option value="">Select..</option>';
            row += '<?php if(!empty($itemcatlist)){ foreach ($itemcatlist as $itemcat_key => $itemcat_val){ ?>';
            row += '<option value="<?php echo $itemcat_val['id'];  ?>"><?php echo $itemcat_val['name']; ?></option>';
            row += '<?php } } ?>';
            row += '</select>';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="form-group form-group-bottom">';
            row += '<input class="form-control" type="text">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="form-group form-group-bottom">';
            row += '<input class="form-control" type="text">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="form-group form-group-bottom">';
            row += '<input class="form-control" type="text">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<div class="form-group form-group-bottom">';
            row += '<input class="form-control" type="text">';
            row += '</div>';
            row += '</td>';
            row += '<td>';
            row += '<a href="javascript:void(0);" class="remTire" style="color: red"><i class="glyphicon glyphicon-trash"></i></a>';
            row += '</td>';
            row += '</tr>';
            $("#tireFields").append(row);

            //set();

        });
        //***************** Tire Price Option End *****************//
        //Remove Tire Fields
        $("#tireFields").on('click', '.remTire', function() {
            $(this).parent().parent().remove();
        });
    });
</script>
<script type="text/javascript">
    //*************************PURCHASE CART******************************
    //*******************Cart Ajax Start from here************************
    //********************************************************************

    function pur_product_id(str) {
        var rowid = str.id;
        var itemID = str.value;
        var link = '<?php echo base_url() ?>';
        //var link = getBaseURL();
        //var postUrl     = <?php //echo site_url("admin/purchase/add_to_cart") ?>;
        //var csrftoken = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: '<?php echo site_url("admin/dispatchitem/add_to_cart") ?>',
            type: "POST",
            data: {
                item_id : itemID,
                rowid: rowid,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                //location.reload(); // then reload the page.(3)
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    //alert(cart);
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                $('#overlay').remove();
            }
        });

    }

    function pur_updateItem(str) {
        var val = str.id;
        var rowid = val.slice(3);
        var type = val.slice(0,3);
        var o_val = str.value;
        var link = '<?php echo base_url() ?>';
        var postUrl     = link + 'admin/dispatchitem/update_cart_item';
        //var csrftoken   = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                rowid: rowid,
                type: type,
                o_val: o_val,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                $('#overlay').remove();
            }
        });
    }

    function pur_removeItem(str) {
        var rowid = str.id;
        var link = '<?php echo base_url() ?>';
        var postUrl     = link + 'admin/dispatchitem/remove_item';
        //var csrftoken = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                rowid: rowid,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                //location.reload(); // then reload the page.(3)
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                $('#overlay').remove();
            }
        });
    }

    function pur_order_discount(str) {
        var discount =  str.value;
        var link = '<?php echo base_url() ?>';
        var postUrl     = link + 'admin/dispatchitem/order_discount';
        //var csrftoken = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                'discount': discount,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                //alert(response);
                //location.reload(); // then reload the page.(3)
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                ///$('#overlay').remove();
            }
        });
    }

    function pur_tax(str) {
        var tax =  str.value;
        var link = '<?php echo base_url() ?>';
        var postUrl     = link + 'admin/dispatchitem/order_tax';
        //var csrftoken = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                tax: tax,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                //location.reload(); // then reload the page.(3)
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                $('#overlay').remove();
            }
        });
    }

    function pur_shipping(str) {
        var shipping =  str.value;
        var link = '<?php echo base_url() ?>';
        var postUrl     = link + 'admin/dispatchitem/order_shipping';
        //var csrftoken = getCookie('csrf_cookie_name');
        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                shipping: shipping,
                //status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                //location.reload(); // then reload the page.(3)
                $.get(link + "admin/dispatchitem/show_cart/", function (cart) {
                    $('#cart_view').html(cart);
                    //$('select').select2();
                });
                $('#overlay').remove();
            }
        });
    }

    function get_vendor(str) {
        var vendor_id = str.value;
        var postUrl     = getBaseURL() + 'admin/dispatchitem/select_vendor_by_id';
        var csrftoken = getCookie('csrf_cookie_name');

        //loader();
        $.ajax({
            url: postUrl,
            type: "POST",
            data: {
                vendor_id: vendor_id,
                status: status, 'csrf_test_name': csrftoken
            },
            cache: false,
            success: function(response) {
                var customer = $.parseJSON(response);
                $('[name="email"]').val(customer.email);
                $('[name="b_address"]').val(customer.b_address);
                $('[name="s_address"]').val(customer.s_address);
                $('#overlay').remove();
            }
        });
    }

    function loader() {
        var baseUrl = '<?php echo base_url() ?>';
        var over = '<div id="overlay">' +
            '<img id="loading" src= "'+ baseUrl +'backend/images/loading.gif">' +
            '</div>';
        $(over).appendTo('body');
    }

    function getPurchaseProduct(str)
    {
        var purchaseProductId = str.value;

        var req = getXMLHTTP();
        if (req) {

            var link = '<?php echo base_url() ?>';

            $.post(link + "admin/dispatchitem/add_purchase_product", {
                    purchase_product_id: purchaseProductId,
                    ajax: '1'
                },
                function (data) {
                    if (data == 'true') {

                        $.get(link + "admin/dispatchitem/show_purchase", function (cart) {
                            $("#cart_content").html(cart);
                        });

                    }

                });
            return false;


        }

    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/dist/js/savemode.js"></script>    