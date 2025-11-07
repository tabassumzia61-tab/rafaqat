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
            if (($this->rbac->hasPrivilege('state', 'can_add')) || ($this->rbac->hasPrivilege('state', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/state/edit/" . $id;
                        } else {
                            $url = base_url() . "admin/state/create";
                        }
                        ?>
                        <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('country'); ?></label><small class="req"> *</small> 
                                    <select  id="country_id" name="country_id" class="form-control"  >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($countrylist as $country) {
                                            ?>
                                            <option value="<?php echo $country['id'] ?>"<?php if (set_value('country_id',$country_id) == $country['id']) echo "selected=selected" ?>><?php echo $country['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name',$name); ?>" />
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
            if (($this->rbac->hasPrivilege('state', 'can_add') ) || ($this->rbac->hasPrivilege('state', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('state_list'); ?></h3>
                        <div class="box-tools pull-right">
                            
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('state_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('country'); ?></th>
                                        <th><?php echo $this->lang->line('state'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($resultcountry)){
                                    foreach ($resultcountry as $val) { ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <?php echo $val['country_name']; ?>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                        $statelist = $this->state_model->getstatebycountry($val['country_id']);
                                        foreach ($statelist as $state_key => $state_val) { ?>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $state_val['name']; ?></a>
                                                    <div class="show_detail_popover" style="display: none">
                                                        <?php
                                                        if ($state_val['note'] == "") {
                                                            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <p class="text text-info"><?php echo $state_val['note']; ?></p>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td  class="mailbox-date text-right">
                                                    <?php if ($this->rbac->hasPrivilege('state', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/state/edit/<?php echo $state_val['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('state', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/state/delete/<?php echo $state_val['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
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
<script type="text/javascript">
    function get_data_by_campus(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>