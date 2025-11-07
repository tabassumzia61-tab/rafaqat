<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?></h1>
            </section>
        </div>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary" <?php if ($customers["is_active"] == 0) { echo "style='background-color:#f0dddd;'"; } ?>>
                    <div class="box-body box-profile">
                        <?php
                        $image = $customers['image'];
                        if (!empty($image)) {

                            $file = $customers['image'];
                        } else {
                            if ($customers['gender'] == 'Male') {
                                $file = "default_male.jpg";
                            } else {
                                $file = "default_female.jpg";
                            }
                        }
                        ?>
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $this->media_storage->getImageURL( "uploads/customers_images/" . $file); ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $customers['name'] . " " . $customers['surname']; ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('customers_id'); ?></b> <a class="pull-right text-aqua"><?php echo $customers['customers_id']; ?></a>
                            </li>
                            <?php if (($customers["is_active"] == 0)) { ?>
                                <li class="list-group-item listnoback">
                                    <b><?php echo $this->lang->line('date_of_leaving'); ?></b> <a class="pull-right text-aqua"><?php echo $this->customlib->dateformat($customers['disable_at']); ?></a>
                                </li>
                            <?php }if($customers["is_active"] == 0) { ?>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('disable_date'); ?></b> <a class="pull-right text-aqua"><?php echo $this->customlib->dateformat($customers['disable_at']); ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                        <li class=""><a href="#documents" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('documents'); ?></a></li>
                        <li class=""><a href="#guarantor" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('guarantor'); ?></a></li>
                        <?php if ($customers["is_active"] == 1) { ?>
                            <li class="pull-right"><a  class="text-red" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $this->lang->line('disable'); ?>" onclick="disable_customers('<?php echo $id; ?>');"></i> <i class="fa fa-thumbs-o-down"></i></a></li>
                        <?php }else if ($customers["is_active"] == 0) { ?>
                            <!--<li class="pull-right"><a href="<?php //echo base_url('admin/staff/delete/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom"  title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php //echo $this->lang->line('are_you_sure_want_to_delete'); ?>');"></i><i class="fa fa-trash"></i></a></li>-->
                            <li class="pull-right"><a href="<?php echo base_url('admin/customers/enablecustomers/' . $id); ?>" class="text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('enable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>');"><i class="fa fa-thumbs-o-up"></i></a></li>
                        <?php }?>
                        <li class="pull-right"><a href="<?php echo base_url('admin/customers/edit/' . $id); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('edit'); ?>" class="text-light" ><i class="fa fa-pencil"></i></a></li>              
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td><?php echo $this->lang->line('phone'); ?></td>
                                                <td><?php echo $customers['phone']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('email'); ?></td>
                                                <td><?php echo $customers['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('gender'); ?></td>
                                                <td><?php echo $this->lang->line(strtolower($customers['gender'])); ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('note'); ?></td>
                                                <td><?php echo $customers['description']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4"><?php echo $this->lang->line('current_address'); ?></td>
                                                <td class="col-md-5"><?php echo $customers['address']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('permanent_address'); ?></td>
                                                <td><?php echo $customers['permanent_address']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="documents">
                            <div class="timeline-header no-border">
                                <div class="row">
                                    <?php if ((empty($customers["cnic_image"])) && (empty($customers["resignation_letter"])) && (empty($customers["other_document_file"]))) { ?>
                                        <div class="col-md-12">
                                            <div class="alert alert-info"><?php echo $this->lang->line("no_record_found"); ?></div>
                                        </div>
                                    <?php } else { ?>
                                        <?php if (!empty($customers["cnic_image"])) { ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5><?php echo $this->lang->line('cnic'); ?></h5>
                                                    <a href="<?php echo base_url(); ?>admin/customers/download/<?php echo $customers['id'] . "/" . 'cnic_image'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php if($this->rbac->hasPrivilege('customers', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/customers/doc_delete/<?php echo $customers['id'] . "/cnic_image"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <div class="icon">
                                                        <i class="fa fa-file-text-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($customers["resignation_letter"])) { ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5><?php echo $this->lang->line('resignation_letter'); ?></h5>
                                                    <a href="<?php echo base_url(); ?>admin/customers/download/<?php echo $customers['id'] . "/" . 'resignation_letter'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php if($this->rbac->hasPrivilege('customers', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/customers/doc_delete/<?php echo $customers['id'] . "/resignation_letter"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <div class="icon">
                                                        <i class="fa fa-file-text-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($customers["other_document_file"])) { ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="staffprofile">
                                                    <h5><?php echo $this->lang->line('other_documents'); ?></h5>
                                                    <a href="<?php echo base_url(); ?>admin/customers/download/<?php echo $customers['id'] . "/" . 'other_document_file'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php if($this->rbac->hasPrivilege('customers', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/customers/doc_delete/<?php echo $customers['id'] . "/other_document_file"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <div class="icon">
                                                        <i class="fa fa-file-text-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="guarantor">
                            <div class="row">    
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <?php if($this->rbac->hasPrivilege('staff_timeline', 'can_add')){ ?>
                                        <input type="button" id="myguarantorButton"  class="btn btn-sm btn-primary pull-right " value="<?php echo $this->lang->line('add_guarantor') ?>" />
                                    <?php } ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="tshadow mb25 bozero">
                                        <h3 class="pagetitleh2"><?php echo $this->lang->line('guarantor_list'); ?></h3>
                                        <div class="table-responsive around10 pt10" style="margin: 10px 0;">
                                            <?php if (empty($guarantorlist)) { ?>
                                                <br/>
                                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                            <?php } else { ?>
                                                <div class="table-responsive around10 pt0">
                                                    <table class="table table-striped table-bordered table-hover example">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo $this->lang->line('sr'); ?></th>
                                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                                <th><?php echo $this->lang->line('phone'); ?></th>
                                                                <th><?php echo $this->lang->line('cnic'); ?></th>
                                                                <th><?php echo $this->lang->line('action'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $countg = 1; 
                                                                foreach($guarantorlist as $gua_val){ ?>
                                                                    <tr>
                                                                        <td><?php echo $countg; ?></td>
                                                                        <td><?php echo $gua_val['name']; ?></td>
                                                                        <td><?php echo $gua_val['phone']; ?></td>
                                                                        <td><?php echo $gua_val['cnic']; ?></td>
                                                                        <td>
                                                                            <?php if ($this->rbac->hasPrivilege('customers', 'can_view')) {?>
                                                                                <a class="btn btn-info btn-xs info_guarantor" data-id="<?php echo $gua_val["id"]; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                                    <i class="fa fa-reorder"></i>
                                                                                </a>
                                                                            <?php }if ($this->rbac->hasPrivilege('customers', 'can_edit')) {?>
                                                                                <a data-toggle="tooltip" class="btn btn-primary btn-xs edit_guarantor" data-id="<?php echo $gua_val["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                                            <?php }if ($this->rbac->hasPrivilege('customers', 'can_delete')) {?>
                                                                                <a class="btn btn-danger btn-xs" data-toggle="tooltip" title="" onclick="delete_guarantor('<?php echo $gua_val['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                            <?php $countg++; } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myguarantorModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title"><?php echo $this->lang->line('add_guarantor'); ?> </h4>
            </div>
            <form id="guarantorform" name="guarantorform" method="post" action="<?php echo base_url() . "admin/customers/addguarantor" ?>"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id='guarantor_hide_show'>
                        <input type="hidden" name="customers_id" value="<?php echo $customers["id"] ?>" id="customers_id">  
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                <input id="gura_name" name="gura_name" placeholder="" type="text" class="form-control"  />
                                <span class="text-danger"><?php echo form_error('gura_name'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('cnic'); ?></label><small class="req"> *</small>
                                <input id="gura_cnic" name="gura_cnic" placeholder="" type="text" class="form-control"  />
                                <span class="text-danger"><?php echo form_error('gura_cnic'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                                <input id="gura_phone" name="gura_phone" placeholder="" type="text" class="form-control"  />
                                <span class="text-danger"><?php echo form_error('gura_phone'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                </div>
                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile"><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('address'); ?></label>
                                <div><textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea></div>
                                <span class="text-danger"></span>
                            </div>
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile"><?php echo $this->lang->line('permanent_address'); ?></label>
                                <div><textarea name="permanent_address" class="form-control"><?php echo set_value('permanent_address'); ?></textarea></div>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('documents'); ?></th>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td><?php echo $this->lang->line('cnic'); ?></td>
                                        <td>
                                            <input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
                                            <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td><?php echo $this->lang->line('resignation_letter'); ?></td>
                                        <td>
                                            <input class="filestyle form-control" type='file' name='third_doc' id="doc3" >
                                            <span class="text-danger"><?php echo form_error('third_doc'); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('documents'); ?></th>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td><?php echo $this->lang->line('other_documents'); ?><input type="hidden" name='fourth_title' class="form-control" placeholder="Other Documents"></td>
                                        <td>
                                            <input class="filestyle form-control" type='file' name='fourth_doc' id="doc4" >
                                            <span class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="clear:both">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    <button type="reset" id="reset" style="display: none"  class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editguarantorModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_guarantor'); ?></h4>
            </div>
                <form  id="editguarantorform" name="guarantorform" method="post" action="<?php echo base_url() . "admin/customers/editguarantor" ?>"  enctype="multipart/form-data">
                    <div class="modal-body pb0">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div id="editguarantordata"></div>
                    </div>
                    <div class="modal-footer" style="clear:both">
                       <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                        <button type="reset" id="reset" style="display: none"  class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                    </div>
                </form>
        </div>
    </div>
</div>
<div class="modal fade" id="infoguarantorModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('info_guarantor'); ?></h4>
            </div>
            <div class="modal-body pb0">
                <?php echo $this->customlib->getCSRF(); ?>
                <div id="infoguarantordata"></div>
            </div>
            <div class="modal-footer" style="clear:both">
            </div>
        </div>
    </div>
</div>
<div id="disablemodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('disable_customers'); ?></h4>
            </div>
            <form method="post" id="disablebtn" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                        <input type="text" class="form-control date" value="<?php echo date($this->customlib->getSystemDateFormat()); ?>" name="date" readonly="readonly">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"  class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function disable_customers(id){
       $('#disablemodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    }
    
    $(document).ready(function (e) {
        $("#disablebtn").on('submit', (function (e) {
            var customers_id = '<?php echo set_value('customers_id',$id) ?>';
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url('admin/customers/disablestcustomers/') ?>" + customers_id,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }

                },
                error: function (e) {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                    console.log(e);
                }
            });
        }));
    });
</script>
<script type="text/javascript">
    $("#myguarantorButton").click(function () {
        $("#reset").click();
        $('.guarantor_title').html("<b><?php echo $this->lang->line('add_guarantor'); ?></b>");
        $(".dropify-clear").click();
        $('#myguarantorModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    });

    $("#guarantorform").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/customers/addguarantor") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');

            },
            success: function (res)
            {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    $('.edit_guarantor').click(function(){
        $('#editguarantorModal').modal('show');
       var id = $(this).attr('data-id');
        $.ajax({
                url: "<?php echo site_url("admin/customers/getguarantoredit") ?>",
                type: "POST",
                data: {id:id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#editguarantordata').html(response.page);
                }
            });
    })

    $("#editguarantorform").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/customers/editguarantor") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    function delete_guarantor(id) {
        if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/customers/delete_guarantor/' + id,
                success: function (res) {
                    errorMsg('Record Delete Successfully');
                    window.location.reload(true);
                },
                error: function () {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }

    $('.info_guarantor').click(function(){
        $('#infoguarantorModal').modal('show');
        var id = $(this).attr('data-id');
        $.ajax({
                url: "<?php echo site_url("admin/customers/getguarantorinfo") ?>",
                type: "POST",
                data: {id:id},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#infoguarantordata').html(response.page);
                }
            });
    })
</script>

