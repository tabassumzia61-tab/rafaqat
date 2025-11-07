<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('products', 'can_add')) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_product_services'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/products/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group switch-inline">
                                            <label><?php echo 'products'; ?></label>
                                            <div class="material-switch switchcheck">
                                                <input id="enable_dob" name="is_active_dob" type="checkbox" class="chk" value="1" />
                                                <label for="enable_dob" class="label-success"></label>
                                            </div>
                                            <label style="padding-left:10px;"><?php echo 'Service'; ?></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tshadow mb25 bozero">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('general_information'); ?> </h4>
                                            <div class="around10">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('code'); ?></label><small class="req"> *</small>
                                                            <div class="form-group">
                                                                <input type="text" name="code" class="form-control" id="code" style="background-color:#eee3e3;" value="<?php echo set_value('code',$codeno); ?>" readonly="readonly" />
                                                                <!--<span class="input-group-addon pointer" id="random_num" style="padding: 1px 10px;">-->
                                                                <!--    <i class="fa fa-random"></i>-->
                                                                <!--</span>-->
                                                            </div> 
                                                            <!--<span class="help-block"><?php //echo $this->lang->line('you_scan_your_barcode_too') ?></span>-->
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product_type'); ?></label><small class="req"> *</small>
                                                            <select id="product_type" name="product_type" class="js-example-basic-single form-control" onchange="getInitial(this)">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($producttype as $key => $val) { ?>
                                                                    <option value="<?php echo $val->id ?>" <?php echo set_select('product_type', $val->id, set_value('product_type')); ?>><?php echo $val->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('product_type'); ?></span>
                                                        </div>
                                                        
                                                        
                                                        <!--<div class="form-group">-->
                                                        <!--    <label for="exampleInputEmail1"><?php echo $this->lang->line('barcode_symbology'); ?></label>-->
                                                        <!--    <select id="barcode_symbology" name="barcode_symbology" class="js-example-basic-single form-control">-->
                                                                <?php
                                                                //$bs = ['code128' => 'Code128','code25' => 'Code25', 'code39' => 'Code39', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca' => 'UPC-A', 'upce' => 'UPC-E'];
                                                                //foreach ($bs as $key => $val) { ?>
                                                        <!--            <option value="<?php echo $key ?>" <?php //echo set_select('barcode_symbology', $key, set_value('barcode_symbology')); ?>><?php //echo $val; ?></option>-->
                                                                <?php //} ?>
                                                        <!--    </select>-->
                                                        <!--    <span class="text-danger"><?php //echo form_error('barcode_symbology'); ?></span>-->
                                                        <!--</div>-->
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('unit'); ?></label>
                                                            <button type="button" class="btn btn-success btn-xs" id="btn_open_add_unit" style="margin-left:8px;"><i class="fa fa-plus"></i> Add Unit</button>
                                                            <select id="unit" name="unit" class="js-example-basic-single form-control">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($unitlist as $key => $val) { ?>
                                                                    <option value="<?php echo $val['id'] ?>" <?php echo set_select('unit', $val['id'], set_value('unit')); ?>><?php echo $val['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('reorder_units'); ?></label>
                                                            <div class="input-group">
                                                                <input autofocus="" id="alert_quantity" name="alert_quantity" placeholder="" type="text" class="form-control"  value="<?php echo set_value('alert_quantity'); ?>" /> 
                                                                <span class="input-group-addon">
                                                                    <input type="checkbox" name="track_quantity" id="track_quantity" value="1" checked="checked" />
                                                                </span>
                                                            </div>
                                                            <span class="text-danger"><?php echo form_error('alert_quantity'); ?></span>
                                                        </div>
                                                        <!--<div class="form-group initial_quantity hide">-->
                                                        <!--    <label for="exampleInputEmail1"> <?php echo $this->lang->line('initial_quantity'); ?></label>-->
                                                        <!--    <input autofocus="" id="initial_quantity" name="initial_quantity" placeholder="" type="text" class="form-control"  value="<?php echo set_value('initial_quantity'); ?>" />-->
                                                        <!--    <span class="text-danger"><?php echo form_error('initial_quantity'); ?></span>-->
                                                        <!--</div> -->
                                                    </div>
                                                    <div class="col-md-6 col-md-offset-1">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"> <?php echo $this->lang->line('product').' '.$this->lang->line('name'); ?></label><small class="req"> *</small>
                                                            <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product_subtype'); ?></label><small class="req"> *</small>
                                                            <select id="product_subtype" name="product_subtype" class="js-example-basic-single product_subtype form-control">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('product_subtype'); ?></span>
                                                        </div> 
                                                        <!--<div class="form-group">-->
                                                        <!--    <label for="exampleInputEmail1"> <?php echo $this->lang->line('secondary').' '.$this->lang->line('name'); ?></label>-->
                                                        <!--    <input autofocus="" id="second_name" name="second_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('second_name'); ?>" />-->
                                                        <!--    <span class="text-danger"><?php echo form_error('second_name'); ?></span>-->
                                                        <!--</div>-->
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>
                                                            <button type="button" class="btn btn-success btn-xs" id="btn_open_add_category" style="margin-left:8px;"><i class="fa fa-plus"></i> Add Category</button>
                                                            <select id="category" name="category" class="js-example-basic-single form-control">
                                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                <?php
                                                                foreach ($categories as $key => $val) { ?>
                                                                    <option value="<?php echo $val->id ?>" <?php echo set_select('category', $val->id, set_value('category')); ?>><?php echo $val->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <span class="text-danger"><?php echo form_error('category'); ?></span>
                                                        </div>
                                                        <!--<div class="form-group">-->
                                                        <!--    <label for="exampleInputEmail1"><?php echo $this->lang->line('sub').' '.$this->lang->line('category'); ?></label>-->
                                                        <!--    <select id="subcategory" name="subcategory" class="js-example-basic-single subcategory form-control">-->
                                                        <!--        <option value=""><?php echo $this->lang->line('select'); ?></option>-->
                                                        <!--    </select>-->
                                                        <!--    <span class="text-danger"><?php echo form_error('subcategory'); ?></span>-->
                                                        <!--</div>-->
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product').' '.$this->lang->line('image'); ?></label><small><?php echo ' ( Size Less Then 1024Kb )'; ?></small>
                                                            <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
                                                            <span class="text-danger"><?php echo form_error('documents'); ?></span>
                                                        </div>
                                                        <!--<div class="form-group">-->
                                                        <!--    <label for="exampleInputEmail1"> <?php echo $this->lang->line('product_has_attributes'); ?></label><small> <?php echo $this->lang->line('eg_sizes_colors'); ?></small>-->
                                                        <!--    <input autofocus="" id="attributes" name="attributes" placeholder="<?php echo $this->lang->line('enter_attributes'); ?>" type="text" class="form-control"  value="<?php echo set_value('attributes'); ?>" />-->
                                                        <!--    <span class="text-danger"><?php echo form_error('attributes'); ?></span>-->
                                                        <!--</div> -->
                                                        <!--<div class="form-group initial_quantity_date hide">-->
                                                        <!--    <label for="exampleInputEmail1"> <?php echo $this->lang->line('initial_quantity_date'); ?></label>-->
                                                        <!--    <input autofocus="" id="initial_quantity_date" name="initial_quantity_date" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('initial_quantity_date'); ?>" />-->
                                                        <!--    <span class="text-danger"><?php echo form_error('initial_quantity_date'); ?></span>-->
                                                        <!--</div>-->
                                                        
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('product_details'); ?></label>
                                                            <textarea id="product_detailss" name="product_details" placeholder=""  class="form-control" style="height: 100px"><?php echo set_value('product_details') ?></textarea>
                                                            <span class="text-danger"><?php echo form_error('product_details'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"> <?php echo 'Pricing'; ?> </a></li>
                                                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"> <?php echo 'Stock'; ?></a></li>
                                                <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false"> <?php echo 'Manufacturing'; ?></a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4>Sale Price</h4>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" id="search_text" value="<?php echo set_value('text_std'); ?>" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text_std" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>" id="search-query">
                                                                    <div id="suggesstion-box"></div>
                                                                    <div class="input-group-btn bs-dropdown-to-select-group">
                                                                        <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                                            <span data-bind="bs-drp-sel-label">
                                                                                <?php echo 'Without Tax'; ?>
                                                                            </span>
                                                                            <input data-value="" type="hidden" name="selected_value_std" data-bind="bs-drp-sel-value" value="" placeholder="Sale Price">
                                                                            <span class="caret"></span>
                                                                        </button>
                                                                        <ul class="dropdown-menu" role="menu" style="">
                                                                            <li data-value="admit_no"><a href="#" ><?php echo 'Without Tax'; ?></a></li>
                                                                            <li data-value="student_name"><a href="#"><?php echo 'With Tax' ?></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <!--<label for="exampleInputEmail1"> <?php echo 'Sale Price'; ?></label>-->
                                                                <!--<input autofocus="" id="cost" name="cost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cost'); ?>" />-->
                                                                <!--<span class="text-danger"><?php echo form_error('cost'); ?></span>-->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'Disc On Sale Price'; ?></label>
                                                                <input autofocus="" id="sale_price" name="sale_price" placeholder="" type="text" class="form-control"  value="<?php echo set_value('sale_price'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('sale_price'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4>Wholesale Price</h4>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'Wholesale Price'; ?></label>
                                                                <input autofocus="" id="cost" name="cost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cost'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('cost'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'Minimum Wholesale Qty'; ?></label>
                                                                <input autofocus="" id="sale_price" name="sale_price" placeholder="" type="text" class="form-control"  value="<?php echo set_value('sale_price'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('sale_price'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4>Purchase Price</h4>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'Purchase Price'; ?></label>
                                                                <input autofocus="" id="cost" name="cost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cost'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('cost'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('tax_rate'); ?></label>
                                                                <select id="tax_rate" name="tax_rate" class="js-example-basic-single form-control">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php
                                                                    foreach ($tax_rates as $tax_key => $tax_val) { ?>
                                                                        <option value="<?php echo $tax_val['id'] ?>" <?php echo set_select('tax_rate', $tax_val['id'] , set_value('tax_rate')); ?>><?php echo $tax_val['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span class="text-danger"><?php echo form_error('tax_rate'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_2">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'Opening Quantity'; ?></label>
                                                                <input autofocus="" id="cost" name="cost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cost'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('cost'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'At Price'; ?></label>
                                                                <input autofocus="" id="sale_price" name="sale_price" placeholder="" type="text" class="form-control"  value="<?php echo set_value('sale_price'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('sale_price'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="exampleInputEmail1"> <?php echo 'As of Date'; ?></label>
                                                                <input autofocus="" id="sale_price" name="sale_price" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('sale_price'); ?>" />
                                                                <span class="text-danger"><?php echo form_error('sale_price'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_3">
                                                    <div class="row">
                                                        <div id="raw_material_sectionvv" style="margin-top:20px;">
                                                            <h4>Raw Materials</h4>
            
                                                            <table class="table table-bordered" id="raw_material_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Raw Material Name</th>
                                                                        <th>Qty</th>
                                                                        <th>Unit</th>
                                                                        <th>Price per Unit</th>
                                                                        <th>Estimated Cost</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="raw_material_body">
                                                                    <tr>
                                                                        <td>
                                                                            <select name="raw_materials[0][product_id]" class="form-control rm_product_select" required>
                                                                                <option value="">Select</option>
                                                                                <?php if (!empty($raw_material_options)) { foreach ($raw_material_options as $opt) { ?>
                                                                                    <option value="<?php echo (int)$opt['id']; ?>"><?php echo htmlspecialchars($opt['name']); ?></option>
                                                                                <?php }} ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" step="0.0001" name="raw_materials[0][qty]" class="form-control qty" required>
                                                                        </td>
                                                                        <td>
                                                                            <select name="raw_materials[0][unit_id]" class="form-control" required>
                                                                                <option value="">Select</option>
                                                                                <?php foreach ($unitlist as $key => $val) { ?>
                                                                                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" inputmode="decimal" name="raw_materials[0][price_per_unit]" class="form-control price" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="raw_materials[0][estimated_cost]" class="form-control estimated_cost" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm remove_row">X</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
            
                                                                <tfoot style="display: table-footer-group !important;">
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <button type="button" class="btn btn-primary btn-sm" id="add_raw_material">+ Add Row</button>
                                                                        </td>
                                                                        <td colspan="3" class="text-right">
                                                                            <strong>Total Estimated Cost: </strong>
                                                                            <span id="total_cost">0.00</span>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                            <div style="margin-top:10px;">
                                                                <button type="button" class="btn btn-info" id="toggle_additional_cost" style="border-radius:20px; padding:6px 14px;">
                                                                    <i class="fa fa-plus-circle"></i> Additional Cost
                                                                </button>
                                                            </div>
            
                                                            <div id="additional_cost_section" style="margin-top:10px;">
                                                                <datalist id="additional_cost_types">
                                                                    <option value="Labour"></option>
                                                                    <option value="Electricity"></option>
                                                                    <option value="Packaging"></option>
                                                                    <option value="Logistics"></option>
                                                                    <option value="Other Charges"></option>
                                                                </datalist>
                                                                <table class="table table-bordered" id="additional_cost_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:60%;">Additional Cost Type</th>
                                                                            <th style="width:25%;">Amount</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="additional_cost_body">
                                                                        <tr>
                                                                            <td>
                                                                                <select name="additional_costs[0][name]" class="form-control ac_type_select" required>
                                                                                    <option value="">Select</option>
                                                                                    <option value="Labour">Labour</option>
                                                                                    <option value="Electricity">Electricity</option>
                                                                                    <option value="Packaging">Packaging</option>
                                                                                    <option value="Logistics">Logistics</option>
                                                                                    <option value="Other Charges">Other Charges</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" inputmode="decimal" name="additional_costs[0][amount]" class="form-control ac_amount" required>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-danger btn-sm remove_ac_row">X</button>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot style="display: table-footer-group !important;">
                                                                        <tr>
                                                                            <td>
                                                                                <button type="button" class="btn btn-primary btn-sm" id="add_additional_cost">+ Add Row</button>
                                                                            </td>
                                                                            <td class="text-right" colspan="2">
                                                                                <strong>Total Additional Cost: </strong>
                                                                                <span id="additional_total_cost">0.00</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
            
                                                            <div id="grand_total_container" class="text-right" style="margin-top:10px;">
                                                                <strong>Total Estimated Cost (Raw Material + Additional Cost): </strong>
                                                                <span id="grand_total_estimated_cost">0.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
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
<!-- Modal: Add Unit -->
<div class="modal fade" id="modal_add_unit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Unit</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="new_unit_name" class="form-control" placeholder="Unit name (e.g., Gram)">
                </div>
                <div class="form-group">
                    <label>Short Name</label>
                    <input type="text" id="new_unit_short" class="form-control" placeholder="Short (e.g., g)">
                </div>
                <div id="new_unit_error" class="text-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_new_unit">Save</button>
            </div>
        </div>
    </div>
 </div>
<!-- Modal: Add Category -->
<div class="modal fade" id="modal_add_category" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Category</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="new_cat_name" class="form-control" placeholder="Category name">
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" id="new_cat_slug" class="form-control" placeholder="category-slug">
                </div>
                <div class="form-group">
                    <label>Parent</label>
                    <select id="new_cat_parent" class="form-control">
                        <option value="">None</option>
                        <?php
                        if (!empty($categories)) {
                            foreach ($categories as $val) { ?>
                                <option value="<?php echo $val->id ?>"><?php echo $val->name; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="new_cat_description" class="form-control" rows="3"></textarea>
                </div>
                <div id="new_cat_error" class="text-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btn_save_new_category">Save</button>
            </div>
        </div>
    </div>
 </div>
<script>
    $(document).on('click', '.dropdown-menu li', function () {
        $("#suggesstion-box ul").empty();
        $("#suggesstion-box").hide();
    });
    $(document).ready(function (e) {
        var activetab ='';
        $(".nav-tabs li a").click(function(){
            activetab = $(this).attr("href");
            //alert(activetab);
            window.location.hash = activetab;
        });
        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
            return false;
        });

    });
</script>
<script>
    $(function () {
        $("#product_details").wysihtml5();
        $("#details").wysihtml5();

    });
</script>
<script type="text/javascript">
    function getInitial(x) {
        if (x.value == 1) {
            $('.initial_quantity').removeClass('hide');   
            $('.initial_quantity_date').removeClass('hide');   
        }else{
            $('.initial_quantity').addClass('hide');   
            $('.initial_quantity_date').addClass('hide');   
        }
    }
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
        var product_type    =   '<?php echo set_value('product_type',$product_type) ?>';
        var product_subtype =   '<?php echo set_value('product_subtype',$product_subtype) ?>';
        var category        =   '<?php echo set_value('category',$category) ?>';
        var subcategory     =   '<?php echo set_value('subcategory',$subcategory) ?>';
        getsubproducttypeByproducttype(product_type,product_subtype);
        getsubcategoryByCategory(category,subcategory);
        $(document).on('change', '#category', function (e) {
            $('#subcategory').html("");
            var category = $(this).val();
            getsubcategoryByCategory(category, 0);

        });
        
        $(document).on('change', '#product_type', function (e) {
            $('#product_subtype').html("");
            var product_type = $(this).val();
            getsubproducttypeByproducttype(product_type, 0);

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
                url: base_url + "admin/products/"+url,
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
                    $(".subcategory").select2();
                },  
                complete: function(){
                    $('#subcategory').removeClass('dropdownloading');
                }
            });
        }
    }
    
    function getsubproducttypeByproducttype(product_type, product_subtype) {
        if (product_type != "" ) {
            $('#product_subtype').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getSubProducttype" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/products/"+url,
                data: {'product_type': product_type},
                dataType: "json",
                beforeSend: function(){
                    $('#product_subtype').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (product_subtype == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#product_subtype').append(div_data);
                    $(".product_subtype").select2();
                },  
                complete: function(){
                    $('#product_subtype').removeClass('dropdownloading');
                }
            });
        }
    }
    // Explicit function to select product type via buttons
    function selectProductType(kind){
        try {
            var p = (kind||'').toLowerCase();
            var matched = null;
            $('#product_type option').each(function(){
                var t = ($(this).text()||'').toLowerCase();
                if (p==='manufacturing' && t.indexOf('manufactur')!==-1) { matched = $(this).val(); }
                if (p==='other' && t.indexOf('manufactur')===-1 && !matched && $(this).val()) { matched = $(this).val(); }
            });
            if (matched){
                $('#product_type').val(matched).trigger('change');
            }
            $('#product_type_toggle button').removeClass('btn-primary').addClass('btn-default');
            $('#product_type_toggle button[data-ptype="'+p+'"]').addClass('btn-primary');
            return false;
        } catch(e) { return false; }
    }
    // Add new category inline (modal)
    $(document).on('change', '#category', function(){
        var v = $(this).val();
        if (v === '__add_new__') {
            $('#modal_add_category').modal('show');
            // Reset select to previous value to avoid invalid selection
            $(this).val('').trigger('change.select2');
        }
    });
    $(document).on('click', '#btn_open_add_category', function(){
        $('#modal_add_category').modal('show');
    });
    $(document).on('click', '#btn_open_add_unit', function(){
        $('#modal_add_unit').modal('show');
    });
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
<script>
    (function($) {
        // 1) Find the "Manufacturing Item" product_type ID (case-insensitive, matches names containing "manufactur")
        var manufacturingItemId = (function() {
            <?php
            $manufacturing_id = 0;
            if (!empty($producttype)) {
                foreach ($producttype as $pt) {
                    if (preg_match('/manufactur/i', (string)$pt->name)) {
                        $manufacturing_id = (int)$pt->id;
                        break;
                    }
                }
            }
            ?>
            return <?php echo (int)$manufacturing_id; ?>;
        })();

        // 2) Toggle section function (robust fallback: if ID not found, checks text)
        function isManufacturingSelected() {
            var selVal = $('#product_type').val();
            if (manufacturingItemId) {
                return String(selVal) === String(manufacturingItemId);
            }
            // Fallback if no ID detected — match option text
            var txt = ($('#product_type option:selected').text() || '').toLowerCase();
            return txt.indexOf('manufactur') !== -1;
        }

        function toggleRawMaterialSection() {
            if (isManufacturingSelected()) {
                $('#raw_material_section').slideDown(120);
            } else {
                $('#raw_material_section').slideUp(120);
            }
        }

        function setTypeButtonsFromSelection() {
            var manu = isManufacturingSelected();
            $('#product_type_toggle button').removeClass('btn-primary').addClass('btn-default');
            if (manu) {
                $('#product_type_toggle button[data-ptype="manufacturing"]').addClass('btn-primary');
            } else {
                $('#product_type_toggle button[data-ptype="other"]').addClass('btn-primary');
            }
            $('#reorder_group').toggle(!manu);
            toggleRawMaterialSection();
        }

        // 3) Row add/remove + calculations
        var rowIndex = 1;

        function recalcRow($row) {
            var qty = parseFloat($row.find('.qty').val()) || 0;
            var price = parsePakistanAmount($row.find('.price').val());
            var cost = qty * price;
            $row.find('.estimated_cost').val(formatPakistanAmount(cost));
        }

        function recalcTotal() {
            var total = 0;
            $('#raw_material_body .estimated_cost').each(function() {
                total += parsePakistanAmount($(this).val()) || 0;
            });
            $('#total_cost').text(formatPakistanAmount(total));
            recalcGrandTotal();
        }

        function recalcAdditionalTotal() {
            var acTotal = 0;
            $('#additional_cost_body .ac_amount').each(function() {
                acTotal += parsePakistanAmount($(this).val()) || 0;
            });
            $('#additional_total_cost').text(formatPakistanAmount(acTotal));
            recalcGrandTotal();
        }

        function recalcGrandTotal() {
            var rm = parsePakistanAmount($('#total_cost').text()) || 0;
            var ac = parsePakistanAmount($('#additional_total_cost').text()) || 0;
            var grand = rm + ac;
            $('#grand_total_estimated_cost').text(formatPakistanAmount(grand));
        }

        function formatPakistanAmount(num) {
            var n = Number(num || 0);
            var parts = n.toFixed(2).split('.');
            var integer = parts[0];
            var decimal = parts[1];
            var last3 = integer.slice(-3);
            var rest = integer.slice(0, -3);
            if (rest !== '') {
                rest = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',');
                integer = rest + ',' + last3;
            }
            return integer + '.' + decimal;
        }

        function parsePakistanAmount(str) {
            if (typeof str !== 'string') str = String(str || '0');
            var normalized = str.replace(/,/g, '');
            var val = parseFloat(normalized);
            return isNaN(val) ? 0 : val;
        }

        $(document).ready(function() {

            // Initial toggle on page load (works with select2 as well)
            toggleRawMaterialSection();

            // Toggle on change (keeps your existing onchange=getInitial(this))
            $(document).on('change', '#product_type', function() {
                toggleRawMaterialSection();
                $('#reorder_group').toggle(!isManufacturingSelected());
                setTypeButtonsFromSelection();
            });

            // Product type toggle buttons (delegated to ensure click works)
            $(document).on('click', '#product_type_toggle button', function(){
                $('#product_type_toggle button').removeClass('btn-primary').addClass('btn-default');
                $(this).addClass('btn-primary');
                var p = $(this).data('ptype');
                // Auto-select a matching product type option if available
                var matched = null;
                $('#product_type option').each(function(){
                    var t = ($(this).text()||'').toLowerCase();
                    if (p==='manufacturing' && t.indexOf('manufactur')!==-1) { matched = $(this).val(); }
                    if (p==='other' && t.indexOf('manufactur')===-1 && !matched && $(this).val()) { matched = $(this).val(); }
                });
                if (matched) {
                    $('#product_type').val(matched).trigger('change');
                }
                $('#product_type_group').hide();
            });

            // Initial hide/show of reorder based on preselected type
            setTypeButtonsFromSelection();

            // Add row
            $(document).on('click', '#add_raw_material', function() {
                var firstSelectHtml = $('#raw_material_table tbody select:first').html() || '<option value="">Select</option>';
                var newRow = `
                <tr>
                    <td>
                        <select name="raw_materials[${rowIndex}][product_id]" class="form-control rm_product_select" required>
                            ${($('#raw_material_table tbody select.rm_product_select:first').html() || '<option value="">Select</option>')}
                        </select>
                    </td>
                    <td><input type="number" step="0.0001" name="raw_materials[${rowIndex}][qty]" class="form-control qty" required></td>
                    <td>
                        <select name="raw_materials[${rowIndex}][unit_id]" class="form-control" required>
                            ${firstSelectHtml}
                        </select>
                    </td>
                    <td><input type="number" step="0.0001" name="raw_materials[${rowIndex}][price_per_unit]" class="form-control price" required></td>
                    <td><input type="text" name="raw_materials[${rowIndex}][estimated_cost]" class="form-control estimated_cost" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
                </tr>
            `;
                $('#raw_material_body').append(newRow);
                rowIndex++;
            });

            // Remove row
            $(document).on('click', '.remove_row', function() {
                $(this).closest('tr').remove();
                recalcTotal();
            });

            // Raw materials price: format on blur, parse while typing
            $(document).on('focus', '.price', function(){
                var raw = parsePakistanAmount($(this).val());
                $(this).val(raw ? String(raw) : '');
            });
            $(document).on('blur', '.price', function(){
                var $row = $(this).closest('tr');
                var raw = parsePakistanAmount($(this).val());
                $(this).val(formatPakistanAmount(raw));
                recalcRow($row);
                recalcTotal();
            });
            $(document).on('input', '.qty, .price', function(){
                var $row = $(this).closest('tr');
                recalcRow($row);
                recalcTotal();
            });

            // Also recalc the first row if user fills it
            $('#raw_material_body tr').each(function() {
                recalcRow($(this));
            });
            recalcTotal();

            // Toggle Additional Cost section
            $(document).on('click', '#toggle_additional_cost', function() {
                $('#additional_cost_section').slideToggle(120);
            });

            // Additional cost: add row
            var acRowIndex = 1;
            $(document).on('click', '#add_additional_cost', function() {
                var newRow = `
                <tr>
                    <td>
                        <select name="additional_costs[${acRowIndex}][name]" class="form-control ac_type_select" required>
                            <option value="">Select</option>
                            <option value="Labour">Labour</option>
                            <option value="Electricity">Electricity</option>
                            <option value="Packaging">Packaging</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Other Charges">Other Charges</option>
                        </select>
                    </td>
                    <td><input type="text" inputmode="decimal" name="additional_costs[${acRowIndex}][amount]" class="form-control ac_amount" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove_ac_row">X</button></td>
                </tr>`;
                $('#additional_cost_body').append(newRow);
                acRowIndex++;
            });

            // Additional cost: remove row
            $(document).on('click', '.remove_ac_row', function() {
                $(this).closest('tr').remove();
                recalcAdditionalTotal();
            });

            // Additional cost: format on blur, parse while typing
            $(document).on('focus', '.ac_amount', function(){
                var raw = parsePakistanAmount($(this).val());
                $(this).val(raw ? String(raw) : '');
            });
            $(document).on('blur', '.ac_amount', function(){
                var raw = parsePakistanAmount($(this).val());
                $(this).val(formatPakistanAmount(raw));
                recalcAdditionalTotal();
            });
            $(document).on('input', '.ac_amount', function(){
                recalcAdditionalTotal();
            });

            // Initial grand total calc
            recalcAdditionalTotal();
            recalcGrandTotal();

            // Save new category
            $(document).on('click', '#btn_save_new_category', function() {
                var $btn = $(this);
                var name = ($.trim($('#new_cat_name').val()) || '');
                var slug = ($.trim($('#new_cat_slug').val()) || '');
                var parent = $('#new_cat_parent').val();
                var description = $('#new_cat_description').val();
                $('#new_cat_error').hide().text('');
                if (!name || !slug) {
                    $('#new_cat_error').text('Name and slug are required').show();
                    return;
                }
                $btn.prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>admin/categories/create_ajax',
                    dataType: 'json',
                    data: { name: name, slug: slug, parent: parent, description: description, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
                    success: function(res){
                        if (res && res.success) {
                            var id = res.id;
                            var display = res.name || name;
                            // Append to category select if not present
                            if ($('#category option[value="'+id+'"]').length === 0) {
                                $('#category').append('<option value="'+id+'">'+display+'</option>');
                            }
                            // Select the new category
                            $('#category').val(String(id)).trigger('change.select2');
                            $('#modal_add_category').modal('hide');
                            // reset inputs
                            $('#new_cat_name').val('');
                            $('#new_cat_slug').val('');
                            $('#new_cat_parent').val('');
                            $('#new_cat_description').val('');
                        } else {
                            $('#new_cat_error').text(res && res.error ? res.error : 'Failed to create category').show();
                        }
                    },
                    error: function(){
                        $('#new_cat_error').text('Network error').show();
                    },
                    complete: function(){
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Save new unit
            $(document).on('click', '#btn_save_new_unit', function() {
                var $btn = $(this);
                var name = ($.trim($('#new_unit_name').val()) || '');
                var short_name = ($.trim($('#new_unit_short').val()) || '');
                $('#new_unit_error').hide().text('');
                if (!name || !short_name) {
                    $('#new_unit_error').text('Name and short name are required').show();
                    return;
                }
                $btn.prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>admin/units/create_ajax',
                    dataType: 'json',
                    data: { name: name, short_name: short_name, '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' },
                    success: function(res){
                        if (res && res.success) {
                            var id = res.id;
                            var display = res.name || name;
                            if ($('#unit option[value="'+id+'"]').length === 0) {
                                $('#unit').append('<option value="'+id+'">'+display+'</option>');
                            }
                            $('#unit').val(String(id)).trigger('change.select2');
                            $('#modal_add_unit').modal('hide');
                            $('#new_unit_name').val('');
                            $('#new_unit_short').val('');
                        } else {
                            $('#new_unit_error').text(res && res.error ? res.error : 'Failed to create unit').show();
                        }
                    },
                    error: function(){
                        $('#new_unit_error').text('Network error').show();
                    },
                    complete: function(){
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    })(jQuery);
</script>
<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
</script>