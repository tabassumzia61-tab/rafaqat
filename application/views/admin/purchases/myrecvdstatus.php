<div class="col-md-12">
    <form id="submitrecvdqty" action="<?php echo site_url('admin/purchases/addproductsrecvdstatus') ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                <td><strong><?php if($inv['status'] == 1){ echo 'Ordered'; } ?></strong></td>
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $this->lang->line('status'); ?></label> <small class="req">*</small>
                    <select  id="status" name="status" class="form-control js-example-basic-single"  >
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php
                        $post = ['2' => $this->lang->line('received')];
                        foreach ($post as $key => $val) { ?>
                            <option value="<?php echo $key ?>" <?php if (set_value('status',$key) == $key) echo "selected=selected" ?>><?php echo $val ?></option>
                        <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box-footer">
                    <?php if ($this->rbac->hasPrivilege('add_received_quantity', 'can_add')) { ?>
                    <button type="button" class="btn btn-primary submitrecvdqty pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $this->lang->line('save'); ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.submitrecvdqty', function (e) {
            var $this = $(this);
            var formData = new FormData($('#submitrecvdqty')[0]);
            // var files = $this[0].files;
            // console.log(formData.append('file', $('#documents')[0].files[0]));
            $this.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/purchases/addproductsrecvdstatus") ?>',
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