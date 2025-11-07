<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
        <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_branch'); ?> Select Branch</h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/production/assign_branch') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">

                                <?php
                                if ($this->session->flashdata('msg')) {
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                }
                                ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php  
                                if ($this->rbac->hasPrivilege('superadmin')) { ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label><small class="req"> *</small> 
                                            <select  id="brach_id" name="brach_id" class="form-control" onchange="get_data_by_campuss(this.value);">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($branchlist as $branch) {
                                                    ?>
                                                    <option value="<?php echo $branch['id'] ?>"<?php if (set_value('brach_id',$brach_id) == $branch['id']) echo "selected=selected" ?>><?php echo $branch['name'] ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('brach_id'); ?></span>
                                        </div>
                                    </div>
                                <?php } ?>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                                    </div>
                             </div>
                        </form>
                    </div>
            </div>
            <?php if ($brach_id) { ?>
                <div class="col-md-12">
                    <!-- general form elements -->
                    <form id='form2' action="<?php echo site_url('admin/production/save_branch_products/'.$brach_id) ?>"  method="post" accept-charset="utf-8">
                    <div class="box box-primary" id="exphead">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('products_list'); ?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {?>
                                <?php echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg'); ?>
                            <?php }?>
                            <div class="mailbox-messages table-responsive overflow-visible">
                                <div class="download_label"><?php echo $this->lang->line('products_list'); ?></div>
                                
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" /> <?php echo $this->lang->line('select'); ?> all</th>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('image'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('code'); ?></th>
                                            <th><?php echo $this->lang->line('branch').' '.$this->lang->line('price'); ?></th>
                                           
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
                                                    <?php
                                                         $checked = "";
                                                        if (array_key_exists($product['id'],$resultlist))
                                                        {
                                                            $checked = "checked";
                                                        }
                                                        if(!$resultlist){
                                                            $checked = "checked";
                                                        }
                                                        
                                                        ?>
                                                        <input type="checkbox" name="product_ids[]" class=" "  value="<?php echo $product['id'] ?>" <?php echo $checked?> />
                                                    </td>
                                                    <td>
                                                        <?php echo $count; ?>
                                                    </td>
                                                    
                                                    <td>
                                                        <?php
                                                            if(!empty($product['image'])){ ?>
                                                                <img src="<?php echo base_url() ?>uploads/saleproduct/<?php echo $product['image'] ?>" style="width:30px; height:30px; "/>
                                                        <?php }else{ ?>
                                                                <img src="<?php echo base_url() ?>uploads/saleproduct/no_image.png" style="width:30px; height:30px; "/>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $product['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $product['code']; ?>
                                                    </td>
                                                    <td>
                                                        
                                                        <div class="form-group">
                                                        <?php
                                                         $price = "";
                                                        if (array_key_exists($product['id'],$resultlist))
                                                        {
                                                            $price = (float)$resultlist[$product['id']];
                                                        }
                                                        
                                                        ?>
                                                                    <input type="text" id="product_prices" name="product_prices_<?php echo $product['id'] ?>" class="form-control "  value="<?php echo $price; ?>" />
                                                                </div>
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
                        <div class="box-footer">
                            <?php if ($this->rbac->hasPrivilege('products', 'can_add')) {?>
                                <small class="pull-right">
                                    <button type="submit" name="save_prices" <?php if($brach_id == 0){echo "disabled";} ?> value="save_prices" class="btn btn-primary btn-sm pull-right checkbox-toggle"> Save prices</button>
                                   
                                </small>
                            <?php }?>
                        </div>
                    </div>
                    </form>
                </div>
            <?php } ?>
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
<script language="JavaScript">
     $('#select-all').click(function() {
        var checked = this.checked;
        $('input[type="checkbox"]').each(function() {
            this.checked = checked;
        });
    });
// function toggle(source) {
//   checkboxes = document.getElementsByName('product_ids');
//   for(var i=0, n=checkboxes.length;i<n;i++) {
//     checkboxes[i].checked = source.checked;
//   }
// }
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
</script>