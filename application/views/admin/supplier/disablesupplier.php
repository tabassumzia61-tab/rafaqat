<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) {?>
            <small class="pull-right">
                <a href="<?php echo base_url(); ?>admin/supplier/create" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_supplier'); ?>
                </a>
            </small>
        <?php }?>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                        }?>
                        <div class="row">
                            <div class="col-md-6">
                                <form role="form" action="<?php echo site_url('admin/supplier/disablesupplierlist') ?>" method="post" class="">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <label for="inpuFname"><?php echo $this->lang->line('search_by_keyword'); ?></label><small class="req"> *</small>
                                        <div class="input-group">
                                            <div class="input-group-btn bs-dropdown-to-select-group">
                                                <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                    <span data-bind="bs-drp-sel-label">
                                                        <?php 
                                                            if (set_value('selected_value_supplier',$selected_value_supplier) == 'supplier_id'){
                                                                echo $this->lang->line('supplier_id');
                                                                $inputvalsupplier = 'supplier_id';
                                                            }elseif (set_value('selected_value_supplier',$selected_value_supplier) == 'name') {
                                                                echo $this->lang->line('name');
                                                                $inputvalsupplier = 'name';
                                                            }else{
                                                                echo $this->lang->line('supplier_id');
                                                                $inputvalsupplier = 'supplier_id';
                                                            }   
                                                        ?>
                                                    </span>
                                                    <input data-value="<?php echo $inputvalsupplier;  ?>" type="hidden" name="selected_value_supplier" data-bind="bs-drp-sel-value" value="<?php echo $inputvalsupplier;  ?>">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu" style="">
                                                    <li data-value="supplier_id"><a href="#" ><?php echo $this->lang->line('supplier_id'); ?></a></li>
                                                    <li data-value="name"><a href="#"><?php echo $this->lang->line('name'); ?></a></li>
                                                </ul>
                                            </div>
                                            <input type="text" id="search_text" value="<?php echo set_value('text_supplier',$search_text_supplier); ?>" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text_supplier" placeholder="<?php echo $this->lang->line('search_by_supplier'); ?>" id="search-query">
                                            <div id="suggesstion-box"></div>
                                            <span class="input-group-btn">
                                                <button name="search" value="search_full" class="btn btn-primary btn-searchsm add-btn" type="submit"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </span>

                                        </div>
                                        <span class="text-danger"><?php echo form_error('text_supplier'); ?></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php
                if (isset($resultlist)) {
                    ?>
                    <div class="box-header ptbnull"></div>
                    <div class="nav-tabs-custom border0">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('card_view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="download_label"><?php echo $title; ?></div>
                            <div class="tab-pane table-responsive no-padding" id="tab_2">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('supplier_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('contact_person'); ?></th>
                                            <th><?php echo $this->lang->line('address'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($resultlist)) {

                                        } else {
                                            $count = 1;
                                            foreach ($resultlist as $supplier) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $supplier['supplier_id']; ?></td>
                                                    <td>
                                                        <a <?php if ($this->rbac->hasPrivilege('supplier', 'can_view')) {?> href="<?php echo base_url(); ?>admin/supplier/profile/<?php echo $supplier['id']; ?>"
                                                        <?php }?>><?php echo $supplier['name'] . " " . $supplier['surname']; ?>
                                                        </a>
                                                        <?php
                                                        if ($supplier['phone'] != "") {
                                                            ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover" >
                                                            <i class="fa fa-phone-square"></i> <?php echo $supplier['phone'] ?>
                                                            <br>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($supplier['email'] != "") {
                                                            ?>
                                                            <i class="fa fa-envelope"></i> <?php echo $supplier['email'] ?>

                                                            <?php
                                                        }
                                                        ?>

                                                        <div class="fee_detail_popover" style="display: none">
                                                            <?php
                                                            if ($supplier['description'] == "") {
                                                                ?>
                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <p class="text text-info"><?php echo $supplier['description']; ?></p>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($supplier['contact_person_name'] != "") {
                                                            ?>
                                                            <i class="fa fa-user"></i> <?php echo $supplier['contact_person_name'] ?>
                                                            <br>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($supplier['contact_person_phone'] != "") {
                                                            ?>
                                                            <i class="fa fa-phone-square"></i> <?php echo $supplier['contact_person_phone'] ?>
                                                            <br>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($supplier['contact_person_email'] != "") {
                                                            ?>
                                                            <i class="fa fa-envelope"></i> <?php echo $supplier['contact_person_email'] ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php
                                                        if ($supplier['address'] != "") {
                                                            ?>
                                                            <i class="fa fa-building"></i> <?php echo $supplier['address'] ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="pull-right">
                                                        <?php if ($this->rbac->hasPrivilege('show_supplier_ledger', 'can_view')) { ?>
                                                            <a class="btn btn-primary btn-xs" title="<?php echo $this->lang->line('show_supplier_ledger'); ?>"  href="<?php echo base_url() . "admin/supplier/addpurchasebill/" . $supplier["id"] ?>"><i class="fa fa-file-text-o"></i></a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('supplier', 'can_view')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/supplier/profile/<?php echo $supplier['id'] ?>" class="btn btn-info btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                <i class="fa fa-reorder"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane active" id="tab_1">
                                <div class="mediarow">
                                    <div class="row">
                                        <?php if (empty($resultlist)) {?>
                                            <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                            <?php
                                        } else {
                                            $count = 1;
                                            foreach ($resultlist as $supplier) { ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6 img_div_modal">
                                                    <div class="staffinfo-box">
                                                        <div class="staffleft-box">
                                                            <?php
                                                            if (!empty($supplier["image"])) {
                                                                $image = $supplier["image"];
                                                            } else {
                                                                if ($supplier['gender'] == 'Male') {
                                                                    $image = "default_male.jpg";
                                                                } else {
                                                                    $image = "default_female.jpg";
                                                                }

                                                            }
                                                            ?>
                                                            <img  src="<?php echo $this->media_storage->getImageURL( "uploads/supplier_images/" . $image) ?>" />
                                                        </div>
                                                        <div class="staffleft-content">
                                                            <h5><span data-toggle="tooltip" title="<?php echo $this->lang->line('name'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $supplier["name"] . " " . $supplier["surname"]; ?></span></h5>
                                                            <p><font data-toggle="tooltip" title="<?php echo "Supplier Id"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $supplier["supplier_id"] ?></font></p>
                                                            <p><font data-toggle="tooltip" title="<?php echo "Contact Number"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $supplier["phone"] ?></font></p>
                                                        </div>
                                                        <div class="overlay3">
                                                            <div class="stafficons">
                                                                <?php if ($this->rbac->hasPrivilege('show_supplier_ledger', 'can_view')) { ?>
                                                                    <a title="<?php echo $this->lang->line('show_supplier_ledger'); ?>"  href="<?php echo base_url() . "admin/supplier/addpurchasebill/" . $supplier["id"] ?>"><i class="fa fa-file-text-o"></i></a>
                                                                <?php } ?>
                                                                <?php if ($this->rbac->hasPrivilege('supplier', 'can_view')) { ?>
                                                                    <a title="<?php echo $this->lang->line('view'); ?>"  href="<?php echo base_url() . "admin/supplier/profile/" . $supplier["id"] ?>"><i class="fa fa-navicon"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div><!--./col-md-3-->
                                            <?php
                                            }
                                        }
                                        ?>
                                </div><!--./col-md-3-->
                            </div><!--./row-->
                        </div><!--./mediarow-->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</div>
</section>
</div>
<script>
    $(document).on('click', '.dropdown-menu li', function () {
        $("#suggesstion-box ul").empty();
        $("#suggesstion-box").hide();
    });
    $(document).ready(function (e) {
        var activetab ='';
        $(".nav-tabs li a").click(function(){
            activetab = $(this).attr("href");
            //alert(activetab);
            window.location.hash = activetab;
        });
        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
                    .end()
                    .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                    .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
            return false;
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
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>