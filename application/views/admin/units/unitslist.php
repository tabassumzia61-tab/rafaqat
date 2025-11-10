<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <div class="row">
        <?php
        if (($this->rbac->hasPrivilege('units', 'can_add')) || ($this->rbac->hasPrivilege('units', 'can_edit')))
        {
        ?>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo $title ?></h5>
                </div>
                <?php
                $url = "";
                if (!empty($name))
                {
                    $url = base_url() . "admin/units/edit.html?id=" . $id;
                } else {
                    $url = base_url() . "admin/units/create.html";
                }
                ?>

                <div class="card-body">
                    <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        <?php if ($this->session->flashdata('msg')) { ?>
                        <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>    
                        <?php echo $this->customlib->getCSRF(); ?>
                        <?php 
                            if (!empty($name)) { ?>
                                <input type="hidden" name="id" value="<?php echo set_value('id',$id); ?>" />
                        <?php } ?>

                        <div class="mb-3">
                            <div class="form-group">
                                <label for="name"><?php echo $this->lang->line('unit').' '.$this->lang->line('name'); ?></label> <small class="req"> *</small>
                                <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control gen_slug"  value="<?php echo set_value('name',$name); ?>" />
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="short_name">Short <?php echo $this->lang->line('name'); ?></label> <small class="req"> *</small>
                                <input autofocus="" id="short_name" name="short_name" placeholder="Short name..." type="text" class="form-control" value="<?php echo set_value('short_name',$short_name); ?>" />
                                <span class="text-danger"><?php echo form_error('short_name'); ?></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="primary_unit"> Primary <?php echo $this->lang->line('unit'); ?></label>
                                <select id="primary_unit" name="primary_unit" placeholder="" type="text" class="js-example-basic-single form-control" >
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($resultlist as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['code'] ?>" <?php if (set_value('primary_unit',$primary_unit) == $value['primary_unit']) echo "selected=selected" ?> ><?php echo $value['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('primary_unit'); ?></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="yes" <?= ($unit['is_active'] ?? '') == 'yes' ? 'selected' : ''; ?>>Active</option>
                                <option value="no" <?= ($unit['is_active'] ?? '') == 'no' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description',$description); ?></textarea>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <?php 
        }
        ?>
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card">
                        <h5 class="card-header">Units list</h5>
                        <div class="d-flex align-items-center ms-auto">
                            <form method="get" class="me-3" style="display:flex;" action="<?php echo base_url('admin/units.html'); ?>">
                                <input type="search" name="search" value="<?php echo isset($search) ? html_escape($search) : ''; ?>" class="form-control form-control-sm" placeholder="Search units..." />
                                <button type="submit" class="btn btn-sm btn-info ms-2">Search</button>
                            </form>
                            <?php if (!empty($search)) { ?>
                                <a href="<?php echo base_url('admin/units.html'); ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
                            <?php } ?>
                        </div>
                        <div class="table-responsive text-nowrap" style="min-height:350px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('short_name'); ?>Short name</th>
                                        <th><?php echo $this->lang->line('primary_unit'); ?>Primary unit</th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                <?php
                                if (!empty($resultlist)) {
                                    $count = 1;
                                    foreach ($resultlist as $val)
                                    {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['name']; ?></td>
                                        <td><?php echo $val['short_name']; ?></td>
                                        <td><?php echo $this->units_model->get_unit_name_by_code($val['primary_unit']);  ?></td>
                                        <td><span class="badge bg-label-<?php echo ($val['is_active'] == 'yes') ? 'success' : 'danger';?> me-1"><?php echo ($val['is_active'] == 'yes') ? 'Active' : 'Deactive'; ?></span></td>
                                        <td><?php echo $val['description']; ?></td>
                                        
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <?php if ($this->rbac->hasPrivilege('units', 'can_edit')) { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>admin/units/edit.html?id=<?php echo $val['id'] ?>">
                                                            <i class="bx bx-edit-alt me-1 text-success"></i> <?php echo $this->lang->line('edit'); ?>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('units', 'can_delete')) { ?>
                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>admin/units/delete.html?id=<?php echo $val['id'] ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="bx bx-trash me-1 text-danger"></i> <?php echo $this->lang->line('delete'); ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    $count++;
                                } else { 
                                ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No records found.</td>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#base_unit').change(function(e) {
            var bu = $(this).val();
            if(bu > 0)
                $('#measuring').slideDown();
            else
                $('#measuring').slideUp();
        });
    });
</script>