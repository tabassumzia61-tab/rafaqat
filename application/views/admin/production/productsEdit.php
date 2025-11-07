<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('production_products', 'can_add')) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_product'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/production/edit/'.$id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> <?php echo $this->lang->line('product').' '.$this->lang->line('name'); ?></label><small class="req"> *</small>
                                                <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name',$productsdet['name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('code'); ?></label><small class="req"> *</small>
                                                <div class="input-group">
                                                    <input type="text" name="code" value="<?php echo set_value('code',$productsdet['code']); ?>" class="form-control" id="code">
                                                    <span class="input-group-addon pointer" id="random_num" style="padding: 1px 10px;">
                                                        <i class="fa fa-random"></i>
                                                    </span>
                                                </div> 
                                                <span class="help-block"><?php echo $this->lang->line('you_scan_your_barcode_too') ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('barcode_symbology'); ?></label><small class="req"> *</small>
                                                <select id="barcode_symbology" name="barcode_symbology" class="js-example-basic-single form-control">
                                                    <?php
                                                    $bs = ['code128' => 'Code128','code25' => 'Code25', 'code39' => 'Code39', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca' => 'UPC-A', 'upce' => 'UPC-E'];
                                                    foreach ($bs as $key => $val) { ?>
                                                        <option value="<?php echo $key ?>" <?php if (set_value('barcode_symbology',$productsdet['barcode_symbology']) == $key) echo "selected=selected" ?> ><?php echo $val; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('barcode_symbology'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> <?php echo $this->lang->line('sale').' '.$this->lang->line('price'); ?></label><small class="req"> *</small>
                                                <input autofocus="" id="sale_price" name="sale_price" placeholder="" type="text" class="form-control"  value="<?php echo set_value('sale_price',(float)$productsdet['sale_price']); ?>" />
                                                <span class="text-danger"><?php echo form_error('sale_price'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('unit'); ?></label><small class="req"> *</small>
                                                <select id="unit" name="unit" class="js-example-basic-single form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($unitlist as $key => $val) { ?>
                                                        <option value="<?php echo $val['id']; ?>" <?php if (set_value('unit',$productsdet['unit']) == $val['id']) echo "selected=selected" ?>><?php echo $val['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('alert_quantity'); ?></label>
                                                <div class="input-group">
                                                    <input autofocus="" id="alert_quantity" name="alert_quantity" placeholder="" type="text" class="form-control"  value="<?php echo set_value('alert_quantity',(float)$productsdet['alert_quantity']); ?>" /> 
                                                    <span class="input-group-addon">
                                                        <input type="checkbox" name="track_quantity" id="track_quantity" value="1" checked="checked" />
                                                    </span>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('alert_quantity'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-md-offset-1"> 
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"> <?php echo $this->lang->line('secondary').' '.$this->lang->line('name'); ?></label>
                                                <input autofocus="" id="second_name" name="second_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('second_name',$productsdet['second_name']); ?>" />
                                                <span class="text-danger"><?php echo form_error('second_name'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                                <select id="category" name="category" class="js-example-basic-single form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($categories as $key => $val) { ?>
                                                        <option value="<?php echo $val->id ?>" <?php if (set_value('unit',$category) == $val->id) echo "selected=selected" ?>><?php echo $val->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('category'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('sub').' '.$this->lang->line('category'); ?></label>
                                                <select id="subcategory" name="subcategory" class="js-example-basic-single form-control subcategory">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('subcategory'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('image'); ?></label><small><?php echo ' ( Size Less Then 1024Kb )'; ?></small>
                                                <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
                                                <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="exampleInputEmail1"> <?php echo $this->lang->line('product_has_attributes'); ?></label><small> <?php echo $this->lang->line('eg_sizes_colors'); ?></small>
                                                <input autofocus="" id="attributes" name="attributes" placeholder="<?php echo $this->lang->line('enter_attributes'); ?>" type="text" class="form-control"  value="<?php echo set_value('attributes'); ?>" />
                                                <span class="text-danger"><?php echo form_error('attributes'); ?></span>
                                            </div>  -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product_details'); ?></label>
                                                <textarea id="product_details" name="product_details" placeholder=""  class="form-control" style="height: 100px"><?php echo set_value('product_details',$productsdet['product_details']) ?></textarea>
                                                <span class="text-danger"><?php echo form_error('product_details'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product_details_for_invoice'); ?></label>
                                                <textarea id="details" name="details" placeholder=""  class="form-control" style="height: 100px"><?php echo set_value('details',$productsdet['details']) ?></textarea>
                                                <span class="text-danger"><?php echo form_error('details'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
        $("#product_details").wysihtml5();
        $("#details").wysihtml5();

    });
</script>
<script type="text/javascript">
    // $('#random_num').click(function () {
    //     var code = generateCardNo(8);
    //     alert(code);
    //     // $(this).parent('.input-group').children('input').val(code);
    //     // if (site.settings.use_code_for_slug) {
    //     //     getSlug(code, 'product');
    //     // }
    // });
    $(document).ready(function () {
        $(document).on('click', '#random_num', function (e) {
            var code = generateCardNo(8);
            $("#code").val(code);
        });
    });
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
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var category =   '<?php echo set_value('category',$category) ?>';
        var subcategory =   '<?php echo set_value('subcategory',$subcategory) ?>';
        getsubcategoryByCategory(category,subcategory);
        $(document).on('change', '#category', function (e) {
            $('#subcategory').html("");
            var category = $(this).val();
            getsubcategoryByCategory(category, 0);

        });
    });

    function getsubcategoryByCategory(category, subcategory) {
        if (category != "" ) {
            $('#subcategory').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getSubCategories" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/production/"+url,
                data: {'category': category},
                dataType: "json",
                beforeSend: function(){
                    $('#subcategory').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (subcategory == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#subcategory').append(div_data);
                    $(".subcategory").select2("val", subcategory);
                },  
                complete: function(){
                    $('#subcategory').removeClass('dropdownloading');
                }
            });
        }
    }
</script>
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