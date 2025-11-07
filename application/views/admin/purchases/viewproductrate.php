<div class="col-md-12">
    <form id="submitproductsrate" action="<?php echo site_url('admin/purchases/addproductsrate') ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                            <th><?php echo $this->lang->line('quantity') ?></th>
                            <th><?php echo $this->lang->line('rate') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $this->cart->destroy();
                            $cartItem = json_decode($inv['cart']);
                            //pr($cartItem);
                            foreach ($cartItem as $item ){
                                $cart[] = array(
                                    'id'                => $item->id,
                                    'avbil_qty'         => $item->avbil_qty,
                                    'qty'               => $item->qty,
                                    'price'             => $item->price,
                                    'name'              => $item->name,
                                    // 'description'       => $item->description,
                                );
                            }

                            $this->cart->insert($cart); 
                            if(!empty($this->cart->contents())) { 
                                foreach ($this->cart->contents() as $cart) { ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="pid[]" value="<?php echo $cart['id']; ?>" />
                                            <input type="hidden" name="pqty[]" value="<?php echo $cart['qty']; ?>" />
                                            <input type="hidden" name="pname[]" value="<?php echo $cart['name']; ?>" />
                                            <?php echo $cart['name']; ?>
                                        </td>
                                        <td><?php echo $cart['qty']; ?></td>
                                        <td><input class="form-control text-center" id="price" name="price[]" type="text" value="<?php echo $cart['price']; ?>" /></td>
                                    </tr>
                                <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box-footer">
                    <?php if ($this->rbac->hasPrivilege('add_product_rate', 'can_add')) { ?>
                    <button type="button" class="btn btn-primary submitproductsrate pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>" ><?php echo $this->lang->line('save'); ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.submitproductsrate', function (e) {
            var $this = $(this);
            var formData = new FormData($('#submitproductsrate')[0]);
            // var files = $this[0].files;
            // console.log(formData.append('file', $('#documents')[0].files[0]));
            $this.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/purchases/addproductsrate") ?>',
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