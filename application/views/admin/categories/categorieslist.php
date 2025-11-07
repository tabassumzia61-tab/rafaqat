<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <?php
      if (($this->rbac->hasPrivilege('categories', 'can_add')) || ($this->rbac->hasPrivilege('categories', 'can_edit')))
      {
    ?>
    <div class="col-xl-4">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><?php echo $page_title; ?></h5>
        </div>
        <div class="card-body">
          <?php
            $url = "";
            if (!empty($name)){
              $url = base_url() . "admin/categories/edit.html?id=". $id;
            } else {
              $url = base_url() . "admin/categories/create.html";
            }
          ?>
          <form method="post" action="<?php echo $url; ?>" id="employeeform" name="employeeform" accept-charset="utf-8" enctype="multipart/form-data">
            <?php if ($this->session->flashdata('msg')) { ?>
            <?php echo $this->session->flashdata('msg') ?>
            <?php } ?>    
            <?php echo $this->customlib->getCSRF(); ?>
            <?php
                if (!empty($name)) { ?>
                    <input type="hidden" name="id" value="<?php echo set_value('id',$id); ?>" />
            <?php } ?>

            <div class="mb-3">
              <label class="form-label" for="name"><?php echo $this->lang->line('category').' '.$this->lang->line('name'); ?></label>
              <input type="text" class="form-control gen_slug" name="name" id="name" placeholder="" value="<?php echo set_value('name', $name); ?>" autofocus="" />
              <span class="text-danger"><?php echo form_error('name'); ?></span>
            </div>
            <!-- <div class="mb-3">
              <label for="code"><?php //echo $this->lang->line('category').' '.$this->lang->line('code'); ?></label> <small class="req">*</small>
              <input id="code" name="code" type="number" class="form-control"  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="<?php echo set_value('code',$code); ?>" />
              <span class="text-danger"><?php //echo form_error('code'); ?></span>
            </div> -->
            <div class="mb-3 ">
              <!-- <label for="slug"><?php //echo $this->lang->line('slug'); ?></label> <small class="req"> *</small> -->
              <input autofocus="" id="slug" name="slug" placeholder="" type="hidden" class="form-control"  value="<?php echo set_value('slug',$slug); ?>" readonly="readonly"/>
              <span class="text-danger"><?php echo form_error('slug'); ?></span>
            </div>
            <div class="mb-3">
              <label for="parent"><?php echo $this->lang->line('parent_category'); ?></label>
              <select id="parent" name="parent" placeholder="" type="text" class="js-example-basic-single form-control" >
                <option value=""><?php echo $this->lang->line('select'); ?>...</option>
                <?php 
                  foreach ($categorieslist as $key => $value) {
                ?>
                 <option value="<?php echo $value->id ?>" <?php if (set_value('parent',$parent) == $value->id) echo "selected=selected" ?> ><?php echo $value->name ?></option>
                <?php 
                }
                ?>
              </select>
              <span class="text-danger"><?php echo form_error('parent'); ?></span>
            </div>


             
            <div class="mb-3">
              <label for="documents"><?php echo $this->lang->line('attach_document'); ?></label>
              <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('documents'); ?>" />
              <span class="text-danger"><?php echo form_error('documents'); ?></span>
            </div>

            <div class="mb-3">

              <label for="description"><?php echo $this->lang->line('description'); ?></label>
              <textarea class="form-control" id="description" name="description" rows="3">
                <?php echo set_value('description',$description); ?>
              </textarea>
              <span class="text-danger"></span>
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
                <h5 class="card-header">Categories list</h5>
                <div class="d-flex align-items-center ms-auto">
                    <form method="get" class="me-3" style="display:flex;" action="<?php echo base_url('admin/categories.html'); ?>">
                        <input type="search" name="search" value="<?php echo isset($search) ? html_escape($search) : ''; ?>" class="form-control form-control-sm" placeholder="Search categories..." />
                        <button type="submit" class="btn btn-sm btn-info ms-2">Search</button>
                    </form>
                    <?php if (!empty($search)) { ?>
                        <a href="<?php echo base_url('admin/categories.html'); ?>" class="btn btn-sm btn-outline-secondary">Clear</a>
                    <?php } ?>
                </div>
                <div class="table-responsive text-nowrap" style="min-height:350px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="20px"><?php echo $this->lang->line('name'); ?></th>
                                <!-- <th><?php // echo $this->lang->line('discription'); ?> discription </th> -->
                                <th><?php echo $this->lang->line('parent'); ?></th>
                                <th><?php echo $this->lang->line('image'); ?></th>
                                <th><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                          <?php
                          $upload_url = base_url('uploads/categories/');
                          $count = 1;
                          foreach ($resultlist as $val) {
                            $image_path = (!empty($val['image'])) ? $upload_url . $val['image'] : '';
                          ?>
                            <tr>
                              <td><?php echo $val['name']; ?></td>
                              <!-- <td><?php //echo $val['description']; ?></td> -->
                              <td><?php echo $val['parent']; ?></td>
                              <td>
                                  <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                      <li
                                      data-bs-toggle="tooltip"
                                      data-popup="tooltip-custom"
                                      data-bs-placement="top"
                                      class="avatar avatar-xs pull-up"
                                      title="<?php echo $val['description']; ?>" >
                                      <img src="<?php echo $image_path; ?>" alt="<?php echo $val['description']; ?>" class="rounded-circle" />
                                      </li>
                                  </ul>
                              </td>
                              <td>
                                  <div class="dropdown">
                                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical-rounded"></i>
                                      </button>
                                      <div class="dropdown-menu">
                                          <?php if ($this->rbac->hasPrivilege('categories', 'can_edit')) { ?>
                                              <a class="dropdown-item" href="<?php echo base_url(); ?>admin/categories/edit.html?id=<?php echo $val['id'] ?>">
                                                  <i class="bx bx-edit-alt me-1 text-success"></i> <?php echo $this->lang->line('edit'); ?>
                                              </a>
                                          <?php } ?>
                                          <?php if ($this->rbac->hasPrivilege('categories', 'can_delete')) { ?>
                                              <a class="dropdown-item" href="<?php echo base_url(); ?>admin/categories/delete.html?id=<?php echo $val['id'] ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
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
<script>
    // $(document).ready(function () {
    //     $("#categoriesTable").DataTable({
    //         responsive: true,
    //         pageLength: 10,
    //         ordering: true,
    //         lengthChange: true,
    //         searching: true,
    //         autoWidth: false,
    //         columnDefs: [
    //             { orderable: false, targets: 3 },
    //         ],
    //         language: {
    //             paginate: { previous: "Previous", next: "Next" }
    //         }
    //     });
    // });
    </script>

<script>
document.getElementById('name').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '_')
        .replace(/[^a-z0-9_]/g, '')
        .replace(/^_+|_+$/g, ''); // Remove leading and trailing underscores
        
    document.getElementById('slug').value = slug;
});
</script>