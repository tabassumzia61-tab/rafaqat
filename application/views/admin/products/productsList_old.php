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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('products_services_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('products', 'can_add')) {?>
                            <small class="pull-right">
                                <a href="<?php echo base_url(); ?>admin/products/create" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_product_services'); ?>
                                </a>
                            </small>
                        <?php }?>
                    </div><!-- /.box-header -->
                    <div class="box-body  ">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg'); ?>
                        <?php }?>
                        <div class="mailbox-messages table-responsive overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('products_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sr'); ?></th>
                                        <th><?php echo $this->lang->line('image'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('cost'); ?></th>
                                        <th><?php echo $this->lang->line('unit'); ?></th>
                                        <th><?php echo $this->lang->line('reorder_units'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($productslist)) { ?>
                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($productslist as $product) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $count; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if(!empty($product['image'])){ ?>
                                                            <img src="<?php echo base_url() ?>uploads/puproduct/<?php echo $product['image'] ?>" style="width:30px; height:30px; "/>
                                                    <?php }else{ ?>
                                                            <img src="<?php echo base_url() ?>uploads/puproduct/no_image.png" style="width:30px; height:30px; "/>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php echo $product['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $product['code']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $product['category']; ?>
                                                </td>
                                                <td>
                                                    <?php echo (float)$product['cost']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $product['unit_name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo (float)$product['alert_quantity']; ?>
                                                </td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php if ($this->rbac->hasPrivilege('products', 'can_edit')) {?>
                                                        <a href="<?php echo base_url(); ?>admin/products/edit/<?php echo $product['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }if ($this->rbac->hasPrivilege('products', 'can_delete')) {?>
                                                        <a href="<?php echo base_url(); ?>admin/products/delete/<?php echo $product['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php }?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                        
                                    }
                                    ?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
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