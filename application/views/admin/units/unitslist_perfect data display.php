<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <h4 class="fw-bold py-3 mb-4"> <?php echo $title ?></h4>

    <!-- Basic Layout -->
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
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('unit').' '.$this->lang->line('name'); ?></label> <small class="req"> *</small>
                                <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control gen_slug"  value="<?php echo set_value('name',$name); ?>" />
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                        </div>
                        <!-- <div class="mb-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php //echo $this->lang->line('unit').' '.$this->lang->line('code'); ?></label> <small class="req">*</small>
                                <input id="code" name="code" type="number" class="form-control"  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="<?php echo set_value('code',$code); ?>" />
                                <span class="text-danger"><?php //echo form_error('code'); ?></span>
                            </div>
                        </div> -->
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('base_unit'); ?></label>

                                <select id="base_unit" name="base_unit" placeholder="" type="text" class="js-example-basic-single form-control" >
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($resultlist as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['id'] ?>" <?php if (set_value('base_unit',$base_unit) == $value['id']) echo "selected=selected" ?> ><?php echo $value['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('base_unit'); ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div id="measuring" style="display:none;">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('operator'); ?></label>

                                    <select id="operator" name="operator" placeholder="" type="text" class="js-example-basic-single form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                            $oopts = ['*' => $this->lang->line('*'), '/' => $this->lang->line('/'), '+' => $this->lang->line('+'), '-' => $this->lang->line('-')]; 
                                            foreach ($oopts as $key => $value) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php if (set_value('operator',$operator) == $key) echo "selected=selected" ?> ><?php echo $value ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('operator'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('operation_value'); ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="operation_value" name="operation_value" placeholder="" type="text" class="form-control gen_slug"  value="<?php echo set_value('operation_value',$operation_value); ?>" />
                                    <span class="text-danger"><?php echo form_error('operation_value'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" rows="3">
                                    <?php echo set_value('description',$description); ?>
                                </textarea>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Units list</h5>
                </div>
                <div class="card-body">
                    <div class="card">
                        <h5 class="card-header">Units list</h5>
                        <div class="table-responsive text-nowrap" style="min-height:350px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('base_unit'); ?></th>
                                        <th><?php echo $this->lang->line('operator'); ?></th>
                                        <th><?php echo $this->lang->line('operation_value'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                    $count = 1;
                                    foreach ($resultlist as $val)
                                    {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['name']; ?></td>
                                        <td><?php echo $val['description']; ?></td>
                                        <td><?php echo $val['parent']; ?></td>
                                        <td><?php echo $val['operator']; ?></td>
                                        <!-- <td>
                                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                                <li
                                                data-bs-toggle="tooltip"
                                                data-popup="tooltip-custom"
                                                data-bs-placement="top"
                                                class="avatar avatar-xs pull-up"
                                                title="Lilian Fuller" >
                                                <img src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                                                </li>
                                                
                                            </ul>
                                        </td> -->
                                        
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
                                    ?>
                                </tbody>
                            </table>
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