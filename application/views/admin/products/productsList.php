<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo $this->lang->line('products_services_list'); ?></h5>
                    <?php if ($this->rbac->hasPrivilege('products', 'can_add')) {?>
                        <small class="text-muted float-end">
                            <a href="<?php echo base_url(); ?>admin/products/create.html" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_product_services'); ?>
                            </a>
                        </small>
                    <?php }?>
                </div>
                <div class="d-flex align-items-center ms-auto float-end">
                    <form method="get" class="me-3" style="display:flex;" action="<?php echo base_url('admin/products.html'); ?>">
                        <input type="search" name="search" value="<?php echo isset($search) ? html_escape($search) : ''; ?>" class="form-control form-control-sm" placeholder="Search..." />
                        <button type="submit" class="btn btn-sm btn-info ms-2">Search</button>
                    </form>
                    <?php if (!empty($search)) { ?>
                        <a href="<?php echo base_url('admin/products.html'); ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
                    <?php } ?>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('msg')) {?>
                        <?php echo $this->session->flashdata('msg');
                        $this->session->unset_userdata('msg'); ?>
                    <?php }?>
                    
                    <div class="card">
                        <div class="table-responsive text-nowrap" style="min-height:450px;">
                            <table class="table">
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
                                <tbody class="table-border-bottom-0">
                                    <?php
                                    if (!empty($productslist)) 
                                    {
                                        $count = 1;
                                        foreach ($productslist as $product)
                                        {
                                    ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td>
                                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller" >
                                                        <?php
                                                        if(!empty($product['image'])){ ?>
                                                            <img src="<?php echo base_url() ?>uploads/puproduct/<?php echo $product['image'] ?>" alt="Avatar" class="rounded-circle" />
                                                        <?php }else{ ?>
                                                                <img src="<?php echo base_url() ?>uploads/puproduct/no_image.png" alt="Avatar" class="rounded-circle" />
                                                        <?php } ?>
                                                    </li>
                                                </ul>
                                            </td>

                                            <td><?php echo $product['name']; ?></td>
                                            <td><?php echo $product['code']; ?></td>
                                            <td><?php echo $product['category']; ?></td>
                                            <td><?php echo (float)$product['cost']; ?></td>
                                            <td><?php echo $product['unit_name']; ?></td>
                                            <td><?php echo (float)$product['alert_quantity']; ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php if ($this->rbac->hasPrivilege('products', 'can_edit')) {?>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/products/edit.html?id=<?php echo $product['id'] ?>">
                                                                <i class="bx bx-edit-alt me-1 text-success"></i> <?php echo $this->lang->line('edit'); ?>
                                                            </a>
                                                        <?php }if ($this->rbac->hasPrivilege('products', 'can_delete')) {?>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/products/delete.html?id=<?php echo $product['id'] ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="bx bx-trash me-1 text-danger"></i> <?php echo $this->lang->line('delete'); ?>
                                                            </a>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    $count++;
                                        }
                                    } else { 
                                    ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Products not found.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- footer: total count + pagination -->
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <div>
                                <?php echo 'Total: ' . (isset($total_rows) ? (int)$total_rows : '0'); ?>
                            </div>
                            <div>
                                <?php echo (isset($pagination_links) ? $pagination_links : ''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- / Content -->


<!-- Basic Bootstrap Table -->
<!-- <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-xl">
            <div class="card">
                <h5 class="card-header"><?php echo $this->lang->line('products_services_list'); ?></h5>
                <small class="text-muted float-end">Default label</small>
                
                <div class="table-responsive text-nowrap" style="min-height:450px;">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular Project</strong></td>
                        <td>Albert Cook</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller"
                            >
                              <img src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-primary me-1">Active</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="bx bx-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div> -->
              <!--/ Basic Bootstrap Table -->