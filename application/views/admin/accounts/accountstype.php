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
            if (($this->rbac->hasPrivilege('accounts_type', 'can_add')) || ($this->rbac->hasPrivilege('accounts_type', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        $camp = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/accounts/accountstypeedit/" . $id;
                        } else {
                            $url = base_url() . "admin/accounts/accountstypecreate";
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('accounts').' '.$this->lang->line('head'); ?></label><small class="req"> *</small> 
                                    <select  id="accounts_type_id" name="accounts_type_id" class="form-control selectval">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($accountstypelist as $acc_type) { ?>
                                            <option value="<?php echo $acc_type['id'] ?>"<?php if (set_value('accounts_type_id',$accounts_type_id) == $acc_type['id']) echo "selected=selected" ?>><?php echo $acc_type['name'] ?></option>
                                        <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('accounts_type_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('type').' '.$this->lang->line('name'); ?></label> <small class="req"> *</small>
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
            if (($this->rbac->hasPrivilege('new_accounts', 'can_add') ) || ($this->rbac->hasPrivilege('new_accounts', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('accounts').' '.$this->lang->line('type').' '.$this->lang->line('list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('account').' '.$this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('account').' '.$this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultacclist as $val) {?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <?php echo $val->code.'. '.$val->name; ?>
                                        </td>
                                        <td class="mailbox-name"></td>
                                        <td class="mailbox-name"></td>
                                    </tr>
                                    <?php 
                                        $newaccounthead = $val->newaccounts;
                                        foreach ($newaccounthead as $head_key => $head_val) { ?>
                                            <tr>
                                                <td class="mailbox-name"></td>
                                                <td class="mailbox-name"><?php echo $head_val->code.'. '.$head_val->name; ?></td>
                                                <td class="mailbox-date text-right">
                                                    <?php if ($this->rbac->hasPrivilege('new_accounts', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/accounts/accountstypeedit/<?php echo $head_val->id ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>  
                                            </tr>
                                        <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>
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
