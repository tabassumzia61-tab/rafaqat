<div class="col-md-12">
    <form id="submitkhoya" action="<?php echo site_url('admin/purchases/addproductkhoya') ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
        <div class="row">  
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php  echo $this->lang->line('purchase_details'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped table-borderless" style="margin-bottom:0;">
                        <tbody>
                            <tr>
                                <td><?php  echo $this->lang->line('reference_no'); ?></td>
                                <td><?php echo $inv['bill_no']; ?></td>
                            </tr>
                            <tr>
                                <td><?php  echo $this->lang->line('supplier'); ?></td>
                                <td><?php echo $inv['supplier_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php  echo $this->lang->line('warehouse'); ?></td>
                                <td><?php echo $inv['warehouse_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php  echo $this->lang->line('status'); ?></td>
                                <td><strong><?php if($inv['status'] == 2){ echo 'Received'; } ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <input type="hidden" value="<?php echo $inv['id']; ?>" name="purchase_id"/>
            <input type="hidden" value="<?php echo $inv['warehouse_id']; ?>" name="warehouse_id"/>
            <input type="hidden" value="<?php echo $inv['supplier_id']; ?>" name="supplier_id"/>
            <div class="col-md-12">
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr class="active">
                            <th style="text-align: left;"><?php echo $this->lang->line('name') ?></th>
                            <th><?php echo $this->lang->line('quantity'); ?></th>
                            <th><?php echo $this->lang->line('khoya_qty').' / 40KG'; ?></th>
                            <th><?php echo $this->lang->line('khoya'); ?></th>
                            <th><?php echo $this->lang->line('unit_cost') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $cartItem = json_decode($inv['cart']);
                            if($cartItem){
                                foreach ($cartItem as $option) { 
                                    if($option->id == 1){ 
                                        $khoyadet = $this->purchases_model->getPurchaseItemsKhoya($inv['id'],$option->id);
                                        ?>
                                        <tr>    
                                            <td>
                                                <input type="hidden" value="<?php if(!empty($khoyadet)){ echo $khoyadet->id; } ?>" name="khoya_det_id"/>
                                                <input type="hidden" value="<?php echo $option->id; ?>" name="products_id[]"/>
                                                <input type="hidden" value="<?php echo $option->name; ?>" name="products_name[]"/>
                                                <input type="hidden" value="<?php echo $option->qty; ?>" name="quantity[]"/>
                                                <?php echo  $option->name; ?> 
                                            </td>
                                            <td>
                                                <?php 
                                                    echo (float)$option->qty;
                                                    $qty_it = $option->qty;
                                                    $man = $qty_it/41; 
                                                    $khy_t = $man * 7;  
                                                ?>
                                                <input class="qty_man" id="qty_man" type="hidden" value="<?php echo $man; ?>" name="man[]"/>
                                            </td>         
                                            <td>
                                                <input class="form-control text-center" onkeyup="myFunctionkhoya(this)" name="khoya_par[]" type="text" readonly value="<?php if(!empty($khoyadet->khoya_par)){ echo (float)$khoyadet->khoya_par; }else{ echo '7'; } ?>"/>
                                            </td>          
                                            <td><input class="form-control text-center" id="rkhoya" name="khoya_qty[]" type="text" readonly value="<?php if(!empty($khoyadet->khoya_quantity)){ echo (float)$khoyadet->khoya_quantity; }else{ echo $khy_t; } ?>" /></td>
                                            <td><input class="form-control text-center" name="unit_cost[]" type="text" value="<?php if(!empty($khoyadet->khoya_unit_cost)){ echo (float)$khoyadet->khoya_unit_cost; } ?>" /></td>                              
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box-footer">
                    <?php if ($this->rbac->hasPrivilege('add_khoya_rate', 'can_add')) { ?>
                    <button type="button" class="btn btn-primary submitkhoya pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $this->lang->line('save'); ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function myFunctionkhoya(val){
        //alert(val.value);
        var kho = val.value;
        var $qty_man = document.getElementById("qty_man").value;
        ///alert($qty_man);
        var kh = $qty_man * kho; 
        document.getElementById("rkhoya").value = kh;


    }
    $(document).ready(function () {
        $(document).on('click', '.submitkhoya', function (e) {
            var $this = $(this);
            var formData = new FormData($('#submitkhoya')[0]);
            // var files = $this[0].files;
            // console.log(formData.append('file', $('#documents')[0].files[0]));
            $this.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/purchases/addproductkhoya") ?>',
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
        });
    });
</script>
