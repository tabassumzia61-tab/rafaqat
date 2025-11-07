<?php $currency_symbol = $this->customlib->getSystemCurrencyFormat(); ?>
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
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('waste', 'can_add')) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_waste_bill'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/waste/wasteSave') ?>"  id="wastesave" class="fromwastesave" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('basic_information'); ?></h3>
                                            <div class="around10">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                                                            <small class="req" style="color:red;"> *</small> 
                                                            <input name="date" id="sale_date"  type="text" value="" class="form-control sale_date"/>
                                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label><?php echo $this->lang->line('bill_no'); ?></label>
                                                            <small class="req" style="color:red;"> *</small> 
                                                            <input name="bill_no" id="bill_no" type="text" class="form-control" style="background-color:#eee3e3;" value="<?php echo $bill_no; ?>" readonly="readonly"/>
                                                            <span class="text-danger"><?php echo form_error('bill_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-4">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label> <th><?php echo $this->lang->line('due').' '.$this->lang->line('date'); ?></th></label>-->
                                                    <!--        <small class="req" style="color:red;"> *</small> -->
                                                    <!--        <input name="due_date" id="due_date"  type="text" value="" class="form-control"/>-->
                                                    <!--        <span class="text-danger error hide"><?php echo 'Plz selected'; ?></span>-->
                                                    <!--        <span class="text-danger"><?php echo form_error('due_date'); ?></span>-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('please_select_these_before_adding_product'); ?></h3>
                                            <div class="around10">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('customer_type'); ?></label> <small class="req">*</small>
                                                            <select  id="customer_type" name="customer_type" class="form-control customer_type js-example-basic-single"  >
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                $ct = [1 => $this->lang->line('cash'),2 => $this->lang->line('credit')];// 'pending' => lang('pending'),
                                                                foreach ($ct as $key => $val) { ?>
                                                                    <option value="<?php echo $key ?>" <?php if (set_value('customer_type',1) == $key) echo "selected=selected" ?>><?php echo $val ?></option>
                                                                    <?php
                                                                    
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('customer_type'); ?></span>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('warehouse'); ?></label> <small class="req">*</small>
                                                            <select  id="warehouse_id" name="warehouse_id" class="form-control js-example-basic-single"  >
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($warehouseslist as $warehouse) {
                                                                    ?>
                                                                    <option value="<?php echo $warehouse['id'] ?>" <?php if (set_value('warehouse_id') == $warehouse['id']) echo "selected=selected" ?>><?php echo $warehouse['name'] ?></option>
                                                                    <?php
                                                                    
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('warehouse_id'); ?></span>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-4 cshow">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('customer'); ?></label> <small class="req">*</small>
                                                            <select  id="customer_id" name="customer_id" class="form-control customer_id js-example-basic-single"  >
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($customerslist as $val) {
                                                                    if($val['id'] == 1){}else{ ?>
                                                                        <option value="<?php echo $val['id'] ?>" <?php if (set_value('customer_id') == $val['id']) echo "selected=selected" ?>><?php echo $val['customers_id'].' - '.$val['name'] ?></option>
                                                                    <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('customer_id'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cshow">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('shipping_address'); ?></label>
                                                            <textarea id="shipping_address" name="shipping_address" placeholder=""  class="form-control shipping_address" style="background-color:#eee3e3;" readonly><?php echo set_value('shipping_address') ?></textarea>
                                                            <span class="text-danger"><?php echo form_error('shipping_address'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('order_items'); ?></h3>
                                            <div class="around10">
                                                <div class="row">
                                                    <div id="cart_view" class="col-md-12"> 
                                                        <div class="table-responsive">
                                                            <table class="table tableover table-striped mb0 table-bordered table-hover tablefull12 tblProducts" id="tableID">
                                                                <thead>
                                                                    <tr class="font13 white-space-nowrap">
                                                                        <!-- <th width="5%">#</th> -->
                                                                        <th width="20%"><?php echo $this->lang->line('products_services'); ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th width="15%"><?php echo $this->lang->line('description'); ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="15%"><?php echo $this->lang->line('quantity'); ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="13%"><?php echo $this->lang->line('rate') . ' (' . $currency_symbol . ')'; ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="2%"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr id="row1">
                                                                        <!-- <td>1.</td> -->
                                                                        <td>
                                                                            <input type="hidden" name="total_rows[]" id="calculate" value="1">
                                                                            <select class="form-control item selectval itemsel" style="width:100%" id="item_id1" name="item_id_1">
                                                                                <option value="<?php echo set_value('product_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                                                                </option>
                                                                                <?php foreach ($productslist as $pkey => $pvalue) { ?>
                                                                                    <option value="<?php echo $pvalue["id"]; ?>"><?php echo $pvalue["name"] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td >
                                                                            <input type="text" name="description_1"  id="description1" class="form-control description" style="background-color:#eee3e3;" readonly />
                                                                        </td> 
                                                                        <td>
                                                                            <input type="text" name="quantity_1" id="quantity1" class="form-control text-right qty" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <input type="text" name="rate_1" id="rate1"  class="form-control text-right rate" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                                                                        </td>
                                                                        <td class="text-right w-100px">
                                                                            <input type="text" name="amount_1" id="amount1" placeholder="" class="form-control text-right subtot" style="background-color:#eee3e3;" value="0" readonly onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                                                        </td>
                                                                        <td>
                                                                             <!-- <button type="button"  class="closebtn delete_row" data-row-id="1" autocomplete="off"><i class="fa fa-remove"></i></button> -->
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <a class="btn btn-info btn-sm addplus-xs add-record mb10" data-added="0"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add')?></a>  
                                                            <a class="btn btn-info btn-sm addplus-xs add-record mb10 pull-right" data-added="0"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add')?></a>
                                                            <br/> 
                                                            <table class="carttable">
                                                                <tbody>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('sub_total') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td colspan="2" class="text-right">
                                                                        <input type="text" placeholder="Total" value="0" name="total" id="total" style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control total"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td colspan="2"  class="text-right">
                                                                            <input type="text" placeholder="" value="0" name="net_amount" id="net_amount" style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control net_amount"/></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('customer_message'); ?></label>
                                                <textarea id="customer_message" name="customer_message" placeholder=""  class="form-control" style="height: 100px"><?php echo set_value('customer_message') ?></textarea>
                                                <span class="text-danger"><?php echo form_error('customer_message'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="saveprint" class="btn btn-info printsavebtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?></button>                        
                                    <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="" id="wastesave" class="btn btn-info wastesave"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save_new'); ?></button>  
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
    $(function () {
        $("#customer_message").wysihtml5();
    });
</script>
<script id="product-template" type="text/template">
   <?php foreach ($productslist as $pkey => $pvalue) { ?>
        <option value='<?php echo $pvalue["id"]; ?>'>
            <?php echo $pvalue["name"] ?>
        </option>
    <?php } ?>
</script>
<script type="text/javascript">
    window.onload = function(){
        $('.cshow').hide();
    }
    $(document).ready(function () {
        $(document).on('change','.customer_type',function(e){
            var customer_type = $(this).val();
            if(customer_type == 1){
                $('.cshow').hide();
            }else{
                $('.cshow').show();
            }
        });
        var total_rows = 1;
        $(document).on('select2:select','.item',function(){
            var item_details = {};
            getItemDetail($(this),$(this).val(),item_details);
        });
        $(document).on('input paste keyup','.qty,.item,.rate', function(e){ 
            update_amount();
        });
        $(document).on('click','.add-record',function(){
            var table = document.getElementById("tableID");         
            var id = total_rows + 1;
            var products_template=$("#product-template").html();
            var div = "<td><input type='hidden' name='total_rows[]' value='" + id + "'><select class='form-control select3 item' style='width:100%' name='item_id_"+id+"'  id='item_id" + id + "' ><option value='<?php echo set_value('item_id'); ?>'><?php echo $this->lang->line('select') ?></option>"+products_template+"</select></td><td><input type='text' name='description_"+id+"' id='description" + id + "' class='form-control description' style='background-color:#eee3e3;' readonly></td><td><input type='text' name='quantity_"+id+"' id='quantity" + id + "' class='form-control text-right qty' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'></td><td> <input type='text' name='rate_"+id+"' id='rate" + id + "'  class='form-control text-right rate' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'/></td></td><td><input type='text' name='amount_"+id+"' id='amount" + id + "' value='0.00' class='form-control text-right subtot' readonly style='background-color:#eee3e3;' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'></td>";
            var row =  "<tr id='row" + id + "'>" + div + "<td><button type='button' data-row-id='"+id+"' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
            $('#tableID').append(row);
            $('.select3').select2();
            total_rows++;       
        });
        $(document).on('click','.delete_row',function(e){
            if(confirm("<?php echo $this->lang->line('are_you_sure_to_delete_this'); ?>")){
                var del_row_id = $(this).data('row-id');
                $("#row" + del_row_id).remove();
                update_amount();
            }        
        });
    });

    function getItemDetail(item_obj,item_id,item_details){
        var current_row = item_obj.closest('tr');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("admin/waste/addtocart") ?>',
            data: {'item_id': item_id},
            dataType: 'JSON',
            asyn:true,
               beforeSend: function(res) {
            
            },
            success: function (res) { 
                //console.log(res);
                current_row.find('.description').val(res.description);
                current_row.find('.qty').val(res.qty);
                current_row.find('.rate').val(res.price);
                update_amount();
            }
        });
    }

    function update_amount(){
        //var discount_percent=$('.discount_percent').val();            
        //var tax_percent=0;
        var grandTotal = 0;      
        // var discount = 0;      
        // var tax = 0;
        // var total_tax_amount=0;    
        // var total_discount_amount=0;    
        var $tblrows = $(".tblProducts tbody tr");
        $tblrows.each(function (index) {
            var $tblrow = $(this);  
            var qty = $tblrow.find(".qty").val();
            var price = $tblrow.find(".rate").val();
            // var discount_percent = $tblrow.find(".discount_per").val();
            // var tax_percentage = $tblrow.find(".rate_tax").val();
            var subTotal = parseInt(qty, 10) * parseFloat(price);     
            //var tax_amount=((subTotal*tax_percentage) / 100 );            
            //var discount_amount=isNaN((subTotal * discount_percent / 100 )) ? 0 : (subTotal * discount_percent / 100 );            
            if (!isNaN(subTotal)) {
                    //total_tax_amount += isNaN(tax_amount) ? 0 : tax_amount;   
                    //total_discount_amount += isNaN(discount_amount) ? 0 : discount_amount;   
                    //$tblrow.find('.tax').val(tax_amount.toFixed(2));
                    //$tblrow.find('.disct').val(discount_amount.toFixed(2));
                    var  subTotalAm = subTotal;
                    $tblrow.find('.subtot').val(subTotalAm.toFixed(2));                    
                    var stval = parseFloat(subTotal.toFixed(2));
                    grandTotal += isNaN(stval) ? 0 : stval;                     
            }else{
                subTotal=0;
                $tblrow.find('.subtot').val(subTotal.toFixed(2));     
            }        
        });
       //discount=isNaN((grandTotal * discount_percent / 100 )) ? 0 : (grandTotal * discount_percent / 100 );                 
       var net_amount=((grandTotal));
       $('.total').val(grandTotal.toFixed(2));
       //$('.discount').val(total_discount_amount.toFixed(2));
       //$('.total_tax').val(total_tax_amount.toFixed(2));
       $('.net_amount').val(net_amount.toFixed(2));
    } 
    function getItem(str) {
        var rowid = str.id;
        var itemID = str.value;
        var link = '<?php echo base_url() ?>';
        $.ajax({
            url: '<?php echo site_url("admin/waste/addtocart") ?>',
            type: "POST",
            data: {
                item_id : itemID,
                rowid: rowid,
            },
            cache: false,
            success: function(response) {
            }
        });
    }       
</script>
<script type="text/javascript">
    var htmlData = "";
    $(document).ready(function () {
        var date_format = "<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>";
        $('#sale_date').datepicker({
            format:date_format,
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#due_date').datepicker({
                format:date_format,
                autoclose: true,
                startDate: minDate,
            })
        });
        var customer_id =   '<?php echo set_value('customer_id') ?>';
        getCustomerByID(customer_id);
        $(document).on('change', '.customer_id', function (e) {
            $('.shipping_address').html("");
            var customer_id = $(this).val();
            getCustomerByID(customer_id);
        });

        $(document).on('change', '.customer_id', function (e) {

        });

        $(document).on('click','.printsavebtn', function (e) {
            var itemsel = $('.itemsel').val();
            if(itemsel == ''){
                errorMsg('Please Select Before Product');
            }else{
                var $this = $(this);
                $this.button('loading');
                $('.printsavebtn').prop("disabled", true);
                var formData = new FormData($('#wastesave')[0]);
                $.ajax({
                    url: '<?php echo site_url("admin/waste/wastePrintSave") ?>',
                    type: 'POST',
                    async: false,
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    //dataType: 'html',
                    success: function (data, status, xhr) {
                        try {
                            var output = JSON.parse(data);
                            var message = "";
                            if (output.status == "fail") {
                                $.each(output.error, function (index, value) {
                                    message += value;
                                });
                                errorMsg(message);
                                $('.printsavebtn').prop("disabled", false);
                                $this.button('reset');
                            }
                        } catch (e) {
                            htmlData = data;
                            //successMsg('Record Saved Successfully');
                            //console.log(htmlData);
                            $(".printdiv").html(htmlData);
                            setTimeout(function () {
                              window.print();
                            }, 2000);
                        }
                    },
                    error: function (xhr) { // if error occured
                        alert("Error occured.please try again");
                    },
                    complete: function () {
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 2000);
                        $('.printsavebtn').prop("disabled", false);
                        $this.button('reset');
                    }
                });
                e.stopImmediatePropagation(); // to prevent more than once submission
                return false; 
            }
        });

        $(document).on('click','.wastesave', function (e) {
            var itemsel = $('.itemsel').val();
            if(itemsel == ''){
                errorMsg('Please Select Order Items Before!');
            }else{
                var $this = $(this);
                $this.button('loading');
                $('.wastesave').prop("disabled", true);
                var formData = new FormData($('#wastesave')[0]);
                $.ajax({
                    url: '<?php echo site_url("admin/waste/wasteSave") ?>',
                    type: 'POST',
                    async: false,
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json",
                    success: function (data, status, xhr) {
                        if (data.status == "fail") {
                            $('.wastesave').prop("disabled", false);
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
                            //window.location.reload(true);
                        }, 1000);
                        $('.wastesave').prop("disabled", false);
                        $this.button('reset');
                    }
                });
                e.stopImmediatePropagation(); // to prevent more than once submission
                return false; 
            }
        });
    });

    function getCustomerByID(customer_id) {
        if (customer_id != "" ) {
            $('.shipping_address').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getCustomerByID" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/customers/"+url,
                data: {'customer_id': customer_id},
                dataType: "json",
                success: function (data) {
                    $('.shipping_address').append(data.address);
                },  
            });
        }
    }
</script>

