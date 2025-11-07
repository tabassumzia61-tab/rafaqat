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
            if (($this->rbac->hasPrivilege('tax_rates', 'can_add')) || ($this->rbac->hasPrivilege('tax_rates', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/taxrates/edit/" . $id;
                        } else {
                            $url = base_url() . "admin/taxrates/create";
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control gen_slug"  value="<?php echo set_value('name',$name); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('code'); ?></label>
                                    <input id="code" name="code" type="text" class="form-control"  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="<?php echo set_value('code',$code); ?>" />
                                    <span class="text-danger"><?php echo form_error('code'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('rate'); ?></label> <small class="req">*</small>
                                    <input id="rate" name="rate" type="text" class="form-control"  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="<?php echo set_value('rate',$rate); ?>" />
                                    <span class="text-danger"><?php echo form_error('rate'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('type'); ?></label><small class="req"> *</small>
                                    <select id="type" name="type" placeholder="" type="text" class="js-example-basic-single form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php
                                            $types = ['1' => $this->lang->line('percentage'), '2' => $this->lang->line('fixed')]; 
                                            foreach ($types as $key => $value) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php if (set_value('type',$type) == $key) echo "selected=selected" ?> ><?php echo $value ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
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
            if (($this->rbac->hasPrivilege('tax_rates', 'can_add') ) || ($this->rbac->hasPrivilege('tax_rates', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('taxrates_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('taxrates_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('tax_rate'); ?></th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
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
                                            <td class="mailbox-name">
                                                <?php echo $val['code']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php echo (float)$val['rate']; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                <?php 
                                                    if($val['type'] == 1){
                                                        echo $this->lang->line('percentage');
                                                    }else{
                                                        echo $this->lang->line('fixed');
                                                    }
                                                ?>
                                            </td>
                                            <td  class="mailbox-date text-right">
                                                <?php if ($this->rbac->hasPrivilege('tax_rates', 'can_edit')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/taxrates/edit/<?php echo $val['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('tax_rates', 'can_delete')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/taxrates/delete/<?php echo $val['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
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