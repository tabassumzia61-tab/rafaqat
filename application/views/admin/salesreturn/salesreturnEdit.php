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
            <?php if ($this->rbac->hasPrivilege('salesreturn', 'can_edit')) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_salesreturn_invoice'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/salesreturn/salesreturnUpdateSave/'.$salesreturn['id']) ?>"  id="salesreturnsave" class="fromsalesreturnsave" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('select_date'); ?></h3>
                                            <div class="around10">
                                                <div class="row">
                                                    <?php  
                                                    if ($this->rbac->hasPrivilege('salesreturn', 'can_branch')) { ?>
                                                        <div class="col-sm-3">
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
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                                                            <small class="req" style="color:red;"> *</small> 
                                                            <input name="date" id="sale_date"  type="text" value="<?php if(!empty($salesreturn['date']) && $salesreturn['date'] !='0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($salesreturn['date'])); } ?>" class="form-control sale_date"/>
                                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label><?php echo $this->lang->line('credit_note_no'); ?></label>
                                                            <small class="req" style="color:red;"> *</small> 
                                                            <input name="sale_no" id="sale_no" type="text" class="form-control" style="background-color:#eee3e3;" value="<?php echo $salesreturn['sale_no']; ?>" readonly="readonly"/>
                                                            <span class="text-danger"><?php echo form_error('sale_no'); ?></span>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-3">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label> <th><?php //echo $this->lang->line('due').' '.$this->lang->line('date'); ?></th></label>-->
                                                    <!--        <small class="req" style="color:red;"> *</small> -->
                                                    <!--        <input name="due_date" id="due_date"  type="text" value="<?php //if(!empty($salesreturn['due_date']) && $salesreturn['due_date'] !='0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($salesreturn['due_date'])); } ?>" class="form-control"/>-->
                                                    <!--        <span class="text-danger"><?php //echo form_error('due_date'); ?></span>-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('select_customer'); ?></h3>
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
                                                                    <option value="<?php echo $key ?>" <?php if (set_value('customer_type',$salesreturn['customer_type']) == $key) echo "selected=selected" ?>><?php echo $val ?></option>
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
                                                    <div class="col-md-4 paymentmode">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('payment_mode'); ?></label> <small class="req">*</small>
                                                            <select  id="payment_mode_id" name="payment_mode_id" class="form-control payment_mode_id js-example-basic-single">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($acclist as $accval) { ?>
                                                                    <option value="<?php echo $accval['id'] ?>" <?php if (set_value('payment_mode_id',$salesreturn['payment_mode_id']) == $accval['id']) echo "selected=selected" ?>><?php echo $accval['name'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('payment_mode_id'); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 cshow">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('customer'); ?></label> <small class="req">*</small>
                                                            <select  id="customer_id" name="customer_id" class="form-control customer_id js-example-basic-single"  >
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($customerslist as $val) {
                                                                    if($val['id'] == 1){}else{ ?>
                                                                        <option value="<?php echo $val['id'] ?>" <?php if (set_value('customer_id',$salesreturn['customer_id']) == $val['id']) echo "selected=selected" ?>><?php echo $val['code'].' - '.$val['name'] ?></option>
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
                                            <h3 class="pagetitleh2"><?php echo $this->lang->line('add_items'); ?></h3>
                                            <div class="around10">
                                                <div class="row">
                                                    <div id="cart_view" class="col-md-12"> 
                                                        <div class="table-responsive">
                                                            <table class="table tableover table-striped mb0 table-bordered table-hover tablefull12 tblProducts" id="tableID">
                                                                <thead>
                                                                    <tr class="font13 white-space-nowrap">
                                                                        <!-- <th width="5%">#</th> -->
                                                                        <th width="10%"><?php echo $this->lang->line('products_services'); ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th width="9%"><?php echo $this->lang->line('description'); ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="7%"><?php echo $this->lang->line('quantity'); ?><small class="req" style="color:red;"> *</small> <?php //echo " | " . $this->lang->line('available_qty'); ?></th>
                                                                        <th class="text-right" width="9%"><?php echo $this->lang->line('unit') ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="9%"><?php echo $this->lang->line('rate') . ' (' . $currency_symbol . ')'; ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="" width="9%"><?php echo $this->lang->line('sale_tax_rate'); ?></th>
                                                                        <th class="" width="9%"><?php echo $this->lang->line('sale_tax'); ?></th>
                                                                        <th class="" width="9%"><?php echo $this->lang->line('discount_percent'); ?></th>
                                                                        <th class="" width="9%"><?php echo $this->lang->line('discount'); ?></th>
                                                                        <th class="text-right" width="9%"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?><small class="req" style="color:red;"> *</small></th>
                                                                        <th class="text-right" width="2%"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $orderitem = $this->salesreturn_model->getItemsBysalesreturnID($salesreturn['id']);
                                                                    $rnum = 1;
                                                                    if(!empty($orderitem)){ 
                                                                        foreach ($orderitem as $oikey => $oival) { ?> 
                                                                            <input type="hidden" name="previous_id[]" value="<?php echo $oival['id'];?>">
                                                                            <tr id="row<?php echo $rnum; ?>">
                                                                                <!-- <td>1.</td> -->
                                                                                <td>
                                                                                    <input type="hidden" name="update_id_<?php echo $rnum; ?>" value="<?php echo $oival['id'];?>">
                                                                                    <input type="hidden" name="carts_id[]" value="<?php echo $oival['id'];?>">
                                                                                    <input type="hidden" name="total_rows[]" id="calculate" value="<?php echo $rnum; ?>">
                                                                                    <select class="form-control item selectval itemse<?php echo $rnum; ?>" style="width:100%" id="item_id<?php echo $rnum; ?>" name="item_id_<?php echo $rnum; ?>">
                                                                                        <option value="<?php echo set_value('product_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                                                                        </option>
                                                                                        <?php foreach ($productslist as $pkey => $pvalue) { ?>
                                                                                            <option value="<?php echo $pvalue["id"]; ?>" <?php if ($oival['product_id'] == $pvalue['id']){ echo "selected=selected"; } ?>><?php echo $pvalue["name"] ?></option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </td>
                                                                                <td >
                                                                                    <input type="text" name="description_<?php echo $rnum; ?>"  id="description<?php echo $rnum; ?>" class="form-control description" style="background-color:#eee3e3;" readonly value="<?php echo $oival['description']; ?>" />
                                                                                </td> 
                                                                                <td>
                                                                                    <div class="input-group">
                                                                                        <input type="text" name="quantity_<?php echo $rnum; ?>" id="quantity<?php echo $rnum; ?>" class="form-control text-right qty" value="<?php echo $oival['quantity']; ?>" />
                                                                                        <!--<span class="input-group-addon text-danger available_qty" style="font-size: 10pt" id="totalqty">&nbsp;&nbsp;</span>-->
                                                                                    </div>
                                                                                    <input type="hidden" class="available_quantity" name="available_quantity_<?php echo $rnum; ?>" id="available_quantity<?php echo $rnum; ?>">
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <input type="text" name="unit_<?php echo $rnum; ?>" id="unit<?php echo $rnum; ?>"  class="form-control text-right unit" value="<?php echo $oival['unit']; ?>" style="background-color:#eee3e3;" readonly />
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <input type="text" name="rate_<?php echo $rnum; ?>" id="rate<?php echo $rnum; ?>"  class="form-control text-right rate" value="<?php echo $oival['net_unit_price']; ?>"/>
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class=""> 
                                                                                            <div class="input-group">
                                                                                            <input type="text" class="form-control text-right right-border-none rate_tax"  name="rate_tax_<?php echo $rnum; ?>"  id="rate_tax<?php echo $rnum; ?>"  autocomplete="off" value="<?php echo $oival['item_tax']; ?>" >
                                                                                            <span class="input-group-addon "> %</span>
                                                                                            </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class=""> 
                                                                                            <div class="input-group">
                                                                                            <input type="text" class="form-control text-right right-border-none tax"  name="tax_<?php echo $rnum; ?>"  id="tax<?php echo $rnum; ?>"  readonly autocomplete="off" style="background-color:#eee3e3;" value="<?php echo number_format($oival['tax'],2,'.',','); ?>" >
                                                                                            </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class=""> 
                                                                                            <div class="input-group">
                                                                                            <input type="text" class="form-control text-right right-border-none discount_per"  name="discount_per_<?php echo $rnum; ?>"  id="discount_per<?php echo $rnum; ?>"  autocomplete="off" value="<?php echo $oival['item_discount']; ?>" >
                                                                                            <span class="input-group-addon "> %</span>
                                                                                            </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <div class=""> 
                                                                                            <div class="input-group">
                                                                                            <input type="text" class="form-control text-right right-border-none disct"  name="disct_<?php echo $rnum; ?>"  id="disct<?php echo $rnum; ?>"  readonly autocomplete="off" style="background-color:#eee3e3;" value="<?php echo number_format($oival['discount'],2,'.',','); ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                                                                            </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="text-right w-100px">
                                                                                    <input type="text" name="amount_<?php echo $rnum; ?>" id="amount<?php echo $rnum; ?>" placeholder="" class="form-control text-right subtot" style="background-color:#eee3e3;" value="<?php echo number_format($oival['subtotal'],2,'.',','); ?>" readonly >
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"  class="closebtn delete_row" data-row-id="<?php echo $rnum; ?>" autocomplete="off"><i class="fa fa-remove"></i></button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php $rnum++; } ?>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                            <a class="btn btn-info btn-sm addplus-xs add-record mb10" data-added="<?php echo $rnum; ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add')?></a>  
                                                            <a class="btn btn-info btn-sm addplus-xs add-record mb10 pull-right" data-added="<?php echo $rnum; ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add')?></a>
                                                            <br/> 
                                                            <table class="carttable">
                                                                <tbody>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('sub_total') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td colspan="2" class="text-right">
                                                                        <input type="text" placeholder="Total" value="<?php echo number_format($salesreturn['total'],2,'.',','); ?>" name="total" id="total" style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control total"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('total').' '.$this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td class="text-right">
                                                                            <!-- <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                                            <input type="text" placeholder="<?php echo $this->lang->line('discount'); ?>" name="discount_percent" id="discount_percent"  class="form-control discount_percent" style="width: 70%; float: right;font-size: 12px;"/> -->
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <input type="text" placeholder="" value="<?php echo number_format($salesreturn['total_discount'],2,'.',','); ?>" name="discount" id="discount" readonly style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control discount"/>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('total').' '.$this->lang->line('sale_tax') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td class="text-right">
                                                                        </td>
                                                                        <td class="text-right">
                                                                            <input type="text" placeholder="" name="total_tax" readonly value="<?php echo number_format($salesreturn['total_tax'],2,'.',','); ?>" id="total_tax" style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control total_tax"/>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?></th>
                                                                        <td colspan="2"  class="text-right">
                                                                            <input type="text" placeholder="" value="<?php echo number_format($salesreturn['grand_total'],2,'.',','); ?>" name="net_amount" id="net_amount" style="width: 50%; float: right;background-color:#eee3e3 !important;" class="form-control net_amount"/></td>
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
                                                <textarea id="customer_message" name="customer_message" placeholder=""  class="form-control" style="height: 100px"><?php echo set_value('customer_message',$salesreturn['note']) ?></textarea>
                                                <span class="text-danger"><?php echo form_error('customer_message'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <!-- <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="saveprint" class="btn btn-info printsavebtn"><i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?></button>                         -->
                                    <button type="button" data-loading-text="<?php echo $this->lang->line('processing'); ?>" style="" id="salesreturnsave" class="btn btn-info salesreturnsave"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>  
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
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var id = '<?php echo $id; ?>';
        var url ='<?php echo site_url('admin/salesreturn/edit/'); ?>'+ id +'/'+val;          
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
    var selcustomer_type = '<?php echo $salesreturn['customer_type']; ?>';
    window.onload = function(){
        if(selcustomer_type == 1){
            $('.cshow').hide();
            $('.paymentmode').show();
        }else{
            $('.cshow').show();
            $('.paymentmode').hide();
        }
        
    }
    $(document).ready(function () {
        function generateCardNo(x) {
            if (!x) {
                x = 16;
            }
            chars = '1234567890';
            no = '';
            for (var i = 0; i < x; i++) {
                var rnum = Math.floor(Math.random() * chars.length);
                no += chars.substring(rnum, rnum + 1);
            }
            return no;
        }
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
        $(document).on('input paste keyup','.rate_tax,.qty,.item,.rate,.discount_per,.discount_percent', function(e){ 
            update_amount();
        });
        $(document).on('click','.add-record',function(){
            var table = document.getElementById("tableID");         
            var table_len = (table.rows.length);
            var randomnumber = generateCardNo(3);
            var id = parseInt(table_len) + randomnumber;
            var products_template=$("#product-template").html();
            var div = "<td><input type='hidden' name='total_rows[]' value='" + id + "'><select class='form-control select3 item' style='width:100%' name='item_id_"+id+"'  id='item_id" + id + "' ><option value='<?php echo set_value('item_id'); ?>'><?php echo $this->lang->line('select') ?></option>"+products_template+"</select></td><td><input type='text' name='description_"+id+"' id='description" + id + "' class='form-control description' style='background-color:#eee3e3;' readonly></td><td><div class='input-group'><input type='text' name='quantity_"+id+"' id='quantity" + id + "' class='form-control text-right qty' /></div><input type='hidden' class='available_quantity' name='available_quantity_"+id+"' id='available_quantity" + id + "'><input type='hidden' name='id[]' id='id" + id + "'></td><td> <input type='text' name='unit_"+id+"' id='unit" + id + "'  class='form-control text-right unit' style='background-color:#eee3e3;' readonly /></td><td> <input type='text' name='rate_"+id+"' id='rate" + id + "'  class='form-control text-right rate'/></td><td><div class=''><div class='input-group'><input type='text' class='form-control text-right right-border-none rate_tax' name='rate_tax_"+id+"'  id='rate_tax"+id+"'  autocomplete='off' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'><span class='input-group-addon'> %</span></div></div></td><td><input type='text' name='tax_"+id+"' id='tax" + id + "' class='form-control text-right tax' readonly style='background-color:#eee3e3;' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'></td><td><div class=''><div class='input-group'><input type='text' class='form-control text-right right-border-none discount_per' name='discount_per_"+id+"'  id='discount_per"+id+"'  autocomplete='off' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'><span class='input-group-addon'> %</span></div></div></td><td><input type='text' name='disct_"+id+"' id='disct" + id + "' class='form-control text-right disct' readonly style='background-color:#eee3e3;' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'></td><td><input type='text' name='amount_"+id+"' id='amount" + id + "' value='0.00' class='form-control text-right subtot' readonly style='background-color:#eee3e3;' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'></td>";
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
            url: '<?php echo site_url("admin/salesreturn/addtocart") ?>',
            data: {'item_id': item_id},
            dataType: 'JSON',
            asyn:true,
               beforeSend: function(res) {
            
            },
            success: function (res) { 
                //console.log(res);
                current_row.find('.description').val(res.description);
                current_row.find('.qty').val(res.qty);
                current_row.find('.available_qty').text(res.available_quantity);
                current_row.find('.rate').val(res.price);
                current_row.find('.rate_tax').val(res.rate_tax);
                current_row.find('.tax').val(res.tax);
                current_row.find('.unit').val(res.unit_name);
                // var item_price  = res.price*res.qty;
                // var total_price = item_price + res.tax;
                // current_row.find('.subtot').val(total_price.toFixed(0));
                update_amount();
            }
        });
    }

    function update_amount(){
        var discount_percent=$('.discount_percent').val();            
        var tax_percent=0;
        var grandTotal = 0;      
        var discount = 0;      
        var tax = 0;
        var total_tax_amount=0;    
        var total_discount_amount=0;    
        var $tblrows = $(".tblProducts tbody tr");
        $tblrows.each(function (index) {
            var $tblrow = $(this);  
            var qty = $tblrow.find(".qty").val();
            var price = $tblrow.find(".rate").val();
            var discount_percent = $tblrow.find(".discount_per").val();
            var tax_percentage = $tblrow.find(".rate_tax").val();
            var subTotal = parseFloat(qty) * parseFloat(price);     
            var tax_amount=((subTotal*tax_percentage) / 100 );            
            var discount_amount=isNaN((subTotal * discount_percent / 100 )) ? 0 : (subTotal * discount_percent / 100 );            
            if (!isNaN(subTotal)) {
                    total_tax_amount += isNaN(tax_amount) ? 0 : tax_amount;   
                    total_discount_amount += isNaN(discount_amount) ? 0 : discount_amount;   
                    $tblrow.find('.tax').val(tax_amount.toLocaleString('en',{minimumFractionDigits: 2}));
                    $tblrow.find('.disct').val(discount_amount.toLocaleString('en',{minimumFractionDigits: 2}));
                    var  subTotalAm = subTotal + tax_amount - discount_amount;
                    $tblrow.find('.subtot').val(subTotalAm.toLocaleString('en',{minimumFractionDigits: 2}));                    
                    var stval = parseFloat(subTotal.toFixed(2));
                    grandTotal += isNaN(stval) ? 0 : stval;                     
            }else{
                subTotal=0;
                $tblrow.find('.subtot').val(subTotal.toLocaleString('en',{minimumFractionDigits: 2}));     
            }        
        });
       //discount=isNaN((grandTotal * discount_percent / 100 )) ? 0 : (grandTotal * discount_percent / 100 );                 
       var net_amount = ((grandTotal + total_tax_amount - total_discount_amount));
       $('.total').val(grandTotal.toLocaleString('en',{minimumFractionDigits: 2}));
       $('.discount').val(total_discount_amount.toLocaleString('en',{minimumFractionDigits: 2}));
       $('.total_tax').val(total_tax_amount.toLocaleString('en',{minimumFractionDigits: 2}));
       $('.net_amount').val(net_amount.toLocaleString('en',{minimumFractionDigits: 2}));
    }
    
    function getItem(str) {
        var rowid = str.id;
        var itemID = str.value;
        var link = '<?php echo base_url() ?>';
        $.ajax({
            url: '<?php echo site_url("admin/salesreturn/addtocart") ?>',
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
        var customer_id =   '<?php echo set_value('customer_id',$salesreturn['customer_id']) ?>';
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
                var formData = new FormData($('#salesreturnsave')[0]);
                $.ajax({
                    url: '<?php echo site_url("admin/salesreturn/salesreturnUpdatePrintSave") ?>',
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
                            //window.location.reload(true);
                        }, 2000);
                        $('.printsavebtn').prop("disabled", false);
                        $this.button('reset');
                    }
                });
                e.stopImmediatePropagation(); // to prevent more than once submission
                return false; 
            }
        });

        $(document).on('click','.salesreturnsave', function (e) {
            var itemsel = $('.itemsel').val();
            if(itemsel == ''){
                errorMsg('Please Select Order Items Before!');
            }else{
                var $id = '<?php echo $salesreturn['id']; ?>';
                var $this = $(this);
                $this.button('loading');
                $('.salesreturnsave').prop("disabled", true);
                var formData = new FormData($('#salesreturnsave')[0]);
                $.ajax({
                    url: '<?php echo site_url("admin/salesreturn/salesreturnUpdateSave/") ?>'+$id,
                    type: 'POST',
                    async: false,
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json",
                    success: function (data, status, xhr) {
                        if (data.status == "fail") {
                            $('.salesreturnsave').prop("disabled", false);
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
                        $('.salesreturnsave').prop("disabled", false);
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
                    var addresses = data.address+' '+data.area_name+' '+data.city_name;
                    $('.shipping_address').append(addresses);
                },  
            });
        }
    }
</script>

