<?php
// echo '<pre>';
// print_r($order);
// echo '</pre>';
?>
<form id="form_submit_sale" action="<?php echo site_url('admin/sale/save_sale_modal') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<div class="tshadow mb25 bozero"> 
    <div class="around10">
        <?php if ($this->session->flashdata('msg')) { ?>
            <?php echo $this->session->flashdata('msg') ?>
        <?php } ?>  
        <?php echo $this->customlib->getCSRF(); ?>
        <h5 class="pagetitleh2" style="font-size:14px;margin-bottom:10px;"><?php echo $this->lang->line('customer_information'); ?> </h5>
        <div class="row" style="padding:7px 15px 0px 17px;">
        <div class="col-sm-2">
            <div class="form-group">
                <label><?php echo $this->lang->line('bill') . " " . $this->lang->line('no'); ?></label>
                <small class="req" style="color:red;"> *</small> 
                <input name="bill_no" id="billno" type="text" class="form-control" value="<?php echo $bill_no; ?>" readonly="readonly"/>
                <span class="text-danger"><?php echo form_error('bill_no'); ?></span>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                <small class="req" style="color:red;"> *</small> 
                <input name="date"  type="text" value="<?php echo date($this->customlib->getSchoolDateFormat(true, true)) ?>" class="form-control datetime"/>
                <span class="text-danger"><?php echo form_error('date'); ?></span>
            </div>
        </div>
        <div class="col-md-3">
           <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                <input autofocus="" id="c_name" name="c_name"  placeholder="" type="text" class="form-control"  value="<?php echo set_value('c_name'); ?>"/>
                <span class="text-danger"><?php echo form_error('c_name'); ?></span>
            </div>
        </div>
        </div>
        <h5 class="pagetitleh2" style="font-size:14px;margin-bottom:10px;margin-top:10px;"><?php echo $this->lang->line('product_information'); ?> </h5>
        <div id="cart_view" class="row" style="padding:7px 15px 0px 17px;">
            <div class="table">
                <table class="table" id="tireFields">
                    <thead>

                    <tr style="background-color: #ECEEF1">
                        <th style="width: 15px">#</th>
                        <th class="col-sm-2"><?php  echo strtoupper( $this->lang->line('products')) ?></th>
                        <th class="col-sm-5"><?php  echo strtoupper($this->lang->line('description')) ?></th>
                        <th class=""><?php  echo strtoupper($this->lang->line('qty')) ?></th>
                        <th class=""><?php  echo strtoupper($this->lang->line('rate')) ?></th>
                        <th class=""><?php  echo strtoupper($this->lang->line('amount')) ?></th>
                        <th class=""> </th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $i=1; if(!empty($this->cart->contents())) { foreach ($this->cart->contents() as $cart) {?>

                        <tr>
                            <td>
                                <div class="form-group form-group-bottom">
                                    <?php echo $i ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-group-bottom p_div">
                                    <select class="form-control select2" style="width: 100%" onchange="pur_product_id(this)" id="<?php echo $cart['rowid']?>">
                                        <option value=""><?php echo  $this->lang->line('select') ?></option>
                                        <?php if(!empty($itemcatlist)){  ?>
                                            
                                                <?php foreach ($itemcatlist as $itemcat_key => $itemcat_val){ ?>
                                                    <optgroup label = "<?php echo $itemcat_val['item_category']; ?>">
                                                        <?php 
                                                        foreach($itemcat_val['sub'] as $subcat){?>
                                                            <option value="<?php echo $subcat['id'];  ?>" <?php echo $cart['id'] === $subcat['id'] ?'selected':''  ?>><?php echo $subcat['name']; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                <?php }; ?>
                                            
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-group-bottom">
                                    <input class="form-control" type="text" name="description" onblur ="pur_updateItem(this);" id="<?php echo 'des'.$cart['rowid'] ?>" value="<?php echo $cart['description']?>">
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-group-bottom">
                                    <input class="form-control" type="text" name="qty" onblur ="pur_updateItem(this);" value="<?php echo $cart['qty'] ?>" id="<?php echo 'qty'.$cart['rowid'] ?>">
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-group-bottom">
                                    <input class="form-control" type="text" name="price" value="<?php echo $cart['price'] ?>" onblur ="pur_updateItem(this);" id="<?php echo 'prc'.$cart['rowid'] ?>">
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-group-bottom">
                                    <input class="form-control" type="text" readonly value="<?php echo $cart['subtotal'] ?>">
                                </div>
                            </td>

                            <td>
                                <a href="javascript:void(0)" id="<?php echo $cart['rowid'] ?>" onclick="pur_removeItem(this);"  class="remTire" style="color: red"><i class="glyphicon glyphicon-trash"></i></a>
                            </td>

                        </tr>

                    <?php $i++; };} ?>

                    <tr>
                        <td>
                            <div class="form-group form-group-bottom">

                            </div>
                        </td>

                        <td>
                            <div class="form-group form-group-bottom p_div">
                                <select class="form-control select2" style="width: 100%" onchange="pur_product_id(this)" id="">
                                    <option value=""><?php echo  $this->lang->line('select') ?></option>
                                    <?php if(!empty($itemcatlist)){ foreach ($itemcatlist as $itemcatkey => $itemcatval){ ?>
                                        <optgroup label = "<?php echo $itemcatval['item_category']; ?>">
                                            <?php 
                                                foreach($itemcatval['sub'] as $subcat){?>
                                                    <option value="<?php echo $subcat['id']?>"><?php echo $subcat['name']?></option>
                                            <?php } ?>
                                        </optgroup>
                                    <?php }; } ?>
                                </select>
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-group-bottom">
                                <input class="form-control" type="text">
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-group-bottom">
                                <input class="form-control" type="text">
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-group-bottom">
                                <input class="form-control" type="text">
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-group-bottom">
                                <input class="form-control" type="text" readonly>
                            </div>
                        </td>


                    </tr>

                    </tbody>
                </table>
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td colspan="5" style="text-align: right">
                            <?php echo $this->lang->line('total'); ?>
                        </td>

                        <td style="text-align: right; padding-right: 30px">
                            <?php echo $this->cart->total(); ?>
                        </td>

                    </tr>

                    <tr>
                        <td colspan="5" style="text-align: right">
                            <?php echo $this->lang->line('discount'); ?>
                        </td>

                        <td style="text-align: right; padding-right: 30px">
                            <input type="" class="form-control" style="text-align: right" onblur="pur_order_discount(this)" value="<?php echo $this->session->userdata('discount');?>" name="discount">
                        </td>

                    </tr>

                    <tr>
                        <td colspan="5" style="text-align: right">
                            <?php echo $this->lang->line('tax') ?>
                        </td>

                        <td style="text-align: right; padding-right: 30px">
                            <input type="" class="form-control" style="text-align: right" onblur="pur_tax(this)" value="<?php echo $this->session->userdata('tax');?>" name="tax">
                        </td>

                    </tr>

                   <!--  <tr>
                        <td colspan="5" style="text-align: right">
                            <?php //echo $this->lang->line('transport_cost') ?>
                        </td>

                        <td style="text-align: right; padding-right: 30px">
                            <input type="" class="form-control" style="text-align: right" onblur="pur_shipping(this)" value="<?php echo $this->session->userdata('shipping');?>" name="shipping">
                        </td>

                    </tr> -->

                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold">
                            <?php echo $this->lang->line('grand_total') ?>
                        </td>

                        <?php
                        $gtotal =  $this->cart->total();
                        $discount = $this->session->userdata('discount');
                        $tax = $this->session->userdata('tax');
                        //$shipping = $this->session->userdata('shipping');
                        ?>

                        <td style="text-align: right; padding-right: 30px; font-weight: bold; font-size: 16px">
                            <?php echo $gtotal;// + $tax + $shipping - $discount; ?>
                        </td>

                    </tr>




                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.savebtnsale', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("admin/sale/save_sale_modal") ?>',
            type: 'post',
            data: $('#form_submit_sale').serialize(),
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
            }
        });
    });
</script>