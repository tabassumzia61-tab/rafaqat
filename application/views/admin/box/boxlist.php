<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if (($this->rbac->hasPrivilege('box', 'can_add')) || ($this->rbac->hasPrivilege('box', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/box/edit/" . $id;
                        } else {
                            $url = base_url() . "admin/box/create";
                        }
                        ?>
                        <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php 
                                    if (!empty($name)) { ?>
                                        <input type="hidden" name="id" value="<?php echo set_value('id',$id); ?>" />
                                <?php } ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('box').' '.$this->lang->line('name'); ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control gen_slug"  value="<?php echo set_value('name',$name); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description',$description); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
            if (($this->rbac->hasPrivilege('box', 'can_add') ) || ($this->rbac->hasPrivilege('box', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('box_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('box_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($resultlist as $val) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover" class="detail_popover"><?php echo $val['name'] ?></a>

                                                <div class="show_detail_popover" style="display: none">
                                                    <?php
                                                    if ($val['description'] == "") {
                                                        ?>
                                                        <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <p class="text text-info"><?php echo $val['description']; ?></p>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td  class="mailbox-date text-right">
                                                <?php if ($this->rbac->hasPrivilege('box', 'can_edit')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/box/edit/<?php echo $val['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('box', 'can_delete')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/box/delete/<?php echo $val['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
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
    </section>
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
                return $(this).closest('td').find('.show_detail_popover').html();
            }
        });
    });
</script>