<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Add item</h5>
                    <!-- Switches -->
                    <div class="d-flex align-items-center gap-2">

                        <span class="fw-bold" id="leftLabel" style="opacity:1;">Product</span>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="typeSwitch" onchange="setType()" checked />
                        </div>

                        <span class="fw-bold" id="rightLabel" style="opacity:0.3;">Service</span>

                        
                    </div>
                    
                    <!-- <div class="d-flex align-items-center gap-2">
                        <span id="typeLabelLeft">Product</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="typeSwitch" onchange="updateLabel()">
                        </div>
                        <span id="typeLabelRight">Service</span>
                    </div>
                    <script>
                    function updateLabel() {
                        let chk = document.getElementById("typeSwitch").checked;
                        
                        document.getElementById("typeLabelLeft").style.opacity = chk ? 0.3 : 1;
                        document.getElementById("typeLabelRight").style.opacity = chk ? 1 : 0.3;
                    }
                    </script> -->
                    <small class="text-muted float-end"> </small>
                    <small class="text-muted float-end"> </small>
                    <small class="text-muted float-end"> </small>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('admin/products/create.html') ?>"  id="item_form" name="item_form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        
                    <?php if ($this->session->flashdata('msg')) {?>
                        <?php echo $this->session->flashdata('msg');
                        $this->session->unset_userdata('msg'); ?>
                    <?php }?>
                    <?php echo $this->customlib->getCSRF(); ?>

                        <input type="hidden" name="product_type" id="productType" value="product">
                        <div class="row mb-3">
                            <div class="col-sm-3 item_name">
                                <label class="form-label" for="item_name">Itam name</label>
                                <input type="text" name="item_name" id="item_name" placeholder="Itam name" class="form-control" />
                            </div>

                            <div class="col-sm-3 service_name" style="display:none;">
                                <label class="form-label" for="service_name">Service name</label>
                                <input type="text" name="service_name" id="service_name" placeholder="Service name" class="form-control" />
                            </div>

                            <div class="col-sm-3">
                                <label for="largeSelect" class="form-label">Categories</label>
                                <select name="categories" id="categories" class="form-select">
                                    <option>Select...</option>
                                    <?php if (!empty($categories_list)) { ?>
                                        <?php foreach ($categories_list as $cat) { ?>
                                            <option value="<?php echo $cat->code ?>"><?php echo $cat->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="item_unit" class="form-label">Units </label>
                                <select name="item_unit" id="item_unit" class="form-select">
                                    <option>Select...</option>
                                    <?php if (!empty($units_list)) { ?>
                                        <?php foreach ($units_list as $unit) { ?>
                                            <option value="<?php echo $unit->code ?>"><?php echo $unit->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_unit_model"> + </button>
                            </div>
                            <div class="col-sm-3">
                                <label for="image" class="form-label">Upload image</label>
                                <input class="form-control" type="file" name="image" id="image" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <label class="orm-label" for="description">Description</label>
                                <input type="text" class="form-control" name="description" id="description" placeholder="Description" />
                            </div>
                            <div class="col-sm-3 item_code">
                                <label class="form-label" for="item_code">Item code</label>
                                <input type="text" class="form-control" name="item_code" id="item_code" placeholder="Item code" value="<?php echo $item_code?>" readonly="readonly"/>
                            </div>
                            <div class="col-sm-3 service_code"  style="display:none;">
                                <label class="form-label" for="service_code">Service code</label>
                                <input type="text" class="form-control" name="service_code" id="service_code" placeholder="Service code" value="<?php echo $service_code?>" readonly="readonly"/>
                            </div>
                        </div>
                        
                        <!-- <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-check mt-3">
                                    <input name="batch_tracking" id="batch_tracking" class="form-check-input" type="radio" value="" />
                                    <label class="form-check-label" for="batch_tracking"> Batch Tracking </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-3">
                                    <input name="serial_no_tracking" id="serial_no_tracking" class="form-check-input" type="radio" value="" />
                                    <label class="form-check-label" for="serial_no_tracking"> Serial No. Tracking </label>
                                </div>
                            </div>
                        </div> -->

                        <div class="row mb-3">
                            <div class="col-xl">
                                <!-- <h6 class="text-muted">Filled Tabs</h6> -->
                                <div class="nav-align-top mb-4">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        <li class="nav-item">
                                            <button type="button"class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-sale-price" aria-controls="navs-justified-sale-price" aria-selected="true"> Pricing  </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-stock" aria-controls="navs-justified-stock" aria-selected="false">Stock </button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-manufacturing" aria-controls="navs-justified-manufacturing" aria-selected="false" >Manufacturing</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-justified-sale-price" role="tabpanel">
                                            <div class="card mb-4">    
                                                <div class="card-body">
                                                    <div class="row gy-3">    
                                                        <h5>MRP</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="mrp">MRP</label>
                                                                <input type="text" class="form-control" name="mrp" id="mrp" placeholder="MRP" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="disc_on_mrp_sale">Disc on MRP for sale(%)</label>
                                                                <input type="text" class="form-control" name="disc_on_mrp_sale" id="disc_on_mrp_sale" placeholder="Disc on MRP for sale(%)" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="disc_on_mrp_for_wholesale">Disc on MRP for Wholesale</label>
                                                                <input type="text" class="form-control" name="disc_on_mrp_for_wholesale" id="disc_on_mrp_for_wholesale" placeholder="Disc on MRP for Wholesale(%)" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-4">    
                                                <div class="card-body">
                                                    <div class="row gy-3">    
                                                        <h5>Sale price</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <input type="text" name="sale_price" id="sale_price" class="form-control" placeholder="sale price"/>
                                                                    <button id="sale_price_dropdown_btn" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Select </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end" id="sale_price_dropdown">
                                                                        <li><span class="dropdown-item" data-value="1">With tax</span></li>
                                                                        <li><span class="dropdown-item" data-value="0">Without tax</span></li>
                                                                    </ul>
                                                                    <input type="text" name="sale_price_type" id="sale_price_type">
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.querySelectorAll('#sale_price_dropdown .dropdown-item').forEach(item => {
                                                                    item.addEventListener('click', function () {
                                                                        let text = this.textContent;
                                                                        let value = this.getAttribute('data-value');
                                                                        // Update button
                                                                        document.getElementById('sale_price_dropdown_btn').textContent = text;
                                                                        // Update hidden input
                                                                        document.getElementById('sale_price_type').value = value;
                                                                    });
                                                                });
                                                            </script>

                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <input type="text" name="disc_on_sale_price" id="disc_on_sale_price" class="form-control" placeholder="Disc on sale price"/>
                                                                    <button id="disc_on_sale_price_dropdown_btn" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Select </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end" id="disc_on_sale_price_dropdown">
                                                                        <li><span class="dropdown-item" data-value="1">Persentage</span></li>
                                                                        <li><span class="dropdown-item" data-value="0">Amount</span></li>
                                                                    </ul>
                                                                    <input type="text" name="disc_on_sale_price_type" id="disc_on_sale_price_type">
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.querySelectorAll('#disc_on_sale_price_dropdown .dropdown-item').forEach(item => {
                                                                    item.addEventListener('click', function () {
                                                                        let text = this.textContent;
                                                                        let value = this.getAttribute('data-value');
                                                                        // Update button
                                                                        document.getElementById('disc_on_sale_price_dropdown_btn').textContent = text;
                                                                        // Update hidden input
                                                                        document.getElementById('disc_on_sale_price_type').value = value;
                                                                    });
                                                                });
                                                            </script>
                                                        </div>

                                                        <h5>Wholesale price</h5>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <input type="text" name="wholesale_price" id="wholesale_price" class="form-control" placeholder="Wholesale price"/>
                                                                    <button id="wholesale_price_dropdown_btn" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Select </button>
                                                                    <ul class="dropdown-menu dropdown-menu-end" id="wholesale_price_dropdown">
                                                                        <li><span class="dropdown-item" data-value="1">With tax</span></li>
                                                                        <li><span class="dropdown-item" data-value="0">Without tax</span></li>
                                                                    </ul>
                                                                    <input type="text" name="wholesale_price_type" id="wholesale_price_type">
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.querySelectorAll('#wholesale_price_dropdown .dropdown-item').forEach(item => {
                                                                    item.addEventListener('click', function () {
                                                                        let text = this.textContent;
                                                                        let value = this.getAttribute('data-value');
                                                                        // Update button
                                                                        document.getElementById('wholesale_price_dropdown_btn').textContent = text;
                                                                        // Update hidden input
                                                                        document.getElementById('wholesale_price_type').value = value;
                                                                    });
                                                                });
                                                            </script>

                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" name="disc_on_mrp_for_wholesale" id="disc_on_mrp_for_wholesale" placeholder="Disc on MRP for Wholesale(%)" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gy-3">
                                                <div class="col-sm-6">
                                                    <div class="card mb-4">    
                                                        <div class="card-body">
                                                            <div class="row gy-3"> 
                                                                <h5>Purchase price</h5>
                                                                <div class="col-sm-8">
                                                                    <div class="input-group">
                                                                        <input type="text" name="purchase_price" id="purchase_price" class="form-control" placeholder="Purchase price"/>
                                                                        <button id="purchase_price_dropdown_btn" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> Select </button>
                                                                        <ul class="dropdown-menu dropdown-menu-end" id="purchase_price_dropdown">
                                                                            <li><span class="dropdown-item" data-value="1">With tax</span></li>
                                                                            <li><span class="dropdown-item" data-value="0">Without tax</span></li>
                                                                        </ul>
                                                                        <input type="text" name="purchase_price_type" id="purchase_price_type">
                                                                    </div>
                                                                </div>
                                                                <script>
                                                                    document.querySelectorAll('#purchase_price_dropdown .dropdown-item').forEach(item => {
                                                                        item.addEventListener('click', function () {
                                                                            let text = this.textContent;
                                                                            let value = this.getAttribute('data-value');
                                                                            // Update button
                                                                            document.getElementById('purchase_price_dropdown_btn').textContent = text;
                                                                            // Update hidden input
                                                                            document.getElementById('purchase_price_type').value = value;
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card mb-4">    
                                                        <div class="card-body">
                                                            <div class="row gy-3"> 
                                                                <h5>Taxes</h5>
                                                                <div class="col-sm-8">
                                                                    <div class="form-group">
                                                                        <select name="taxes" id="taxes" class="form-select">
                                                                            <option>Select...</option>
                                                                            <?php if (!empty($taxs_list)) { ?>
                                                                                <?php foreach ($taxs_list as $tax) { ?>
                                                                                    <option value="<?php echo $tax->id ?>"><?php echo $tax->name . "   (".$tax->rate.")" ?></option>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-justified-stock" role="tabpanel">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="row gy-3">
                                                        <div class="row mb-3">
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="opening_quantity">Opening quantity</label>
                                                                <input type="text" class="form-control" name="opening_quantity" id="opening_quantity" placeholder="Opening quantity" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="at_price">At price</label>
                                                                <input type="text" class="form-control" name="at_price" id="at_price" placeholder="At price" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="as_of_date">As of date</label>
                                                                <div class="mb-3 row">
                                                                    <input class="form-control" name="as_of_date" id="as_of_date" type="date" value="0000-00-00"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="minimun_stock_to_mantain">Minimun stock to mantain</label>
                                                                <input type="text" class="form-control" name="minimun_stock_to_mantain" id="minimun_stock_to_mantain" placeholder="Minimun stock to mantain" />
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="form-label" for="location">Location</label>
                                                                <input type="text" class="form-control" name="location" id="location" placeholder="Location" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-justified-manufacturing" role="tabpanel">
                                            <p>Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly</p>
                                            <p class="mb-0">ramels danish toffee. Brownie b</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>

                    <div class="modal fade" id="add_unit_model" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">Add Unit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="unit_name">Unit name</label>
                                            <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Unit name" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="short_name">Short name</label>
                                            <input type="text" class="form-control" id="short_name" name="short_name" placeholder="Short name" />
                                        </div>
                                    </div>
                                
                                    <!-- <div class="row">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="name">Primary unit</label>
                                                <select id="largeSelect" class="form-select">
                                                <option>Large select</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close </button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->


<script>
    function setType() {
        let chk = document.getElementById("typeSwitch").checked;

        document.getElementById("productType").value = chk ? "service" : "product";

        document.getElementById("leftLabel").style.opacity  = chk ? 1 : 0.3;
        document.getElementById("rightLabel").style.opacity = chk ? 0.3 : 1;
            // Show/Hide Fields
        if (chk) {
            document.getElementById("productType").value = "product";
            // Product selected
            document.querySelector(".item_name").style.display = "block";
            document.querySelector(".service_name").style.display = "none";

            document.querySelector(".item_code").style.display = "block";
            document.querySelector(".service_code").style.display = "none";
        } else {
            document.getElementById("productType").value = "service";
            // Service selected
            document.querySelector(".item_name").style.display = "none";
            document.querySelector(".service_name").style.display = "block";

            document.querySelector(".item_code").style.display = "none";
            document.querySelector(".service_code").style.display = "block";
        }
    }
    </script>