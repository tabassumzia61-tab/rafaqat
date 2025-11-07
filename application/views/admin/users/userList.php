<style type="text/css">
    
    .table .pull-right {text-align: initial; width: auto; float: right !important;}
</style>

<div class="content-wrapper">  
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php //echo $this->lang->line('system_settings'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">        

            <div class="col-md-12">            
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_staff"  data-toggle="tab"><?php echo $this->lang->line('staff') ?></a></li>
                        <li class="pull-left header"><?php echo $this->lang->line('users'); ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane table-responsive active" id="tab_staff">
                            <div class="download_label"><?php echo $this->lang->line('users'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('staff_id'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('role'); ?></th>
                                        <th><?php echo $this->lang->line('designation'); ?></th>
                                        <th><?php echo $this->lang->line('department'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($staffList)) {
                                        $count = 1;
                                        foreach ($staffList as $staff) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $staff['employee_id'] ?></td>
                                                <td class="mailbox-name"> <a href="<?php echo base_url(); ?>admin/staff/profile/<?php echo $staff['id']; ?>"><?php echo $staff['name'] ?></a></td>
                                                <td class="mailbox-name"> <?php echo $staff['email'] ?></td>
                                                <td class="mailbox-name"> <?php echo $staff['role'] ?></td>
                                                <td class="mailbox-name"> <?php echo $staff['designation'] ?></td>
                                                <td class="mailbox-name"> <?php echo $staff['department'] ?></td>
                                                <td class="mailbox-name"> <?php echo $staff['contact_no'] ?></td>
                                                <td class="relative">
                                                    <div class="float-rtl-right">
                                                        <div class="material-switch pull-right float-rtl-right">
                                                            <?php if($staff['role_id'] == 1) {}else{ ?>
                                                                <input id="staff<?php echo $staff['id'] ?>" name="someSwitchOption001" type="checkbox" class="chk" data-rowid="<?php echo $staff['id'] ?>" data-role="staff" value="checked" <?php if ($staff['is_active'] == 1) echo "checked='checked'"; ?> />
                                                                <label for="staff<?php echo $staff['id'] ?>" class="label-success"></label>
                                                            <?php } ?>
                                                        </div>
                                                    </div>    
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div> 
        </div> 
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '.chk', function () {
            var checked = $(this).is(':checked');
            var rowid = $(this).data('rowid');
            var role = $(this).data('role');
            if (checked) {
                if (!confirm('<?php echo $this->lang->line("are_you_sure_you_active_account");?>')) {
                    $(this).removeAttr('checked');
                } else {
                    var status = "yes";
                    changeStatus(rowid, status, role);

                }
            } else if (!confirm('<?php echo $this->lang->line("are_you_sure_you_deactive_account"); ?>')) {
                $(this).prop("checked", true);
            } else {
                var status = "no";
                changeStatus(rowid, status, role);

            }
        });
    });

    function changeStatus(rowid, status, role) {


        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            type: "POST",
            url: base_url + "admin/users/changeStatus",
            data: {'id': rowid, 'status': status, 'role': role},
            dataType: "json",
            success: function (data) {
                successMsg(data.msg);
            }
        });
    }


</script>