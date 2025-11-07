<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    <?php if ($this->rbac->hasPrivilege('customers', 'can_add')) {?>
                        <small class="pull-right">
                            <a href="<?php echo base_url(); ?>admin/customers/create/<?php echo $brc_id; ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_customers'); ?>
                            </a>
                        </small>
                    <?php } ?>  
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                        }?>
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="<?php echo site_url('admin/customers/index/'.$brc_id) ?>" method="post" class="">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <?php  
                                    if ($this->rbac->hasPrivilege('customers', 'can_branch')) { ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label><small class="req"> *</small> 
                                            <select  id="brc_id" name="brc_id" class="form-control selectval brc_id" onchange="getBranchByID(this.value);">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($branchlist as $brc_val) {
                                                    $brc_id_log = $this->customlib->getBranchID();
                                                    if ($brc_id_log == 1) { ?>
                                                        <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                    <?php }else{
                                                        if ($brc_id_log == $brc_val['id']) { ?>
                                                            <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                        <?php  } ?>
                                                    <?php
                                                    } 
                                                }
                                                ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('brc_id'); ?></span>
                                        </div>
                                    </div>
                                    <?php } ?> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inpuFname"><?php echo $this->lang->line('search_by_keyword'); ?></label><small class="req"> *</small>
                                            <div class="input-group">
                                                <div class="input-group-btn bs-dropdown-to-select-group">
                                                    <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                        <span data-bind="bs-drp-sel-label">
                                                            <?php 
                                                                if (set_value('selected_value_customers',$selected_value_customers) == 'customers_id'){
                                                                    echo $this->lang->line('customers_id');
                                                                    $inputvalcustomers = 'customers_id';
                                                                }elseif (set_value('selected_value_customers',$selected_value_customers) == 'name') {
                                                                    echo $this->lang->line('name');
                                                                    $inputvalcustomers = 'name';
                                                                }else{
                                                                    echo $this->lang->line('customers_id');
                                                                    $inputvalcustomers = 'customers_id';
                                                                }   
                                                            ?>
                                                        </span>
                                                        <input data-value="<?php echo $inputvalcustomers;  ?>" type="hidden" name="selected_value_customers" data-bind="bs-drp-sel-value" value="<?php echo $inputvalcustomers;  ?>">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="">
                                                        <li data-value="customers_id"><a href="#" ><?php echo $this->lang->line('customers_id'); ?></a></li>
                                                        <li data-value="name"><a href="#"><?php echo $this->lang->line('name'); ?></a></li>
                                                    </ul>
                                                </div>
                                                <input type="text" id="search_text" value="<?php echo set_value('text_customers',$search_text_customers); ?>" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text_customers" placeholder="<?php echo $this->lang->line('search_by_customers'); ?>" id="search-query">
                                                <div id="suggesstion-box"></div>
                                                <span class="input-group-btn">
                                                    <button name="search" value="search_full" class="btn btn-primary btn-searchsm add-btn" type="submit"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                </span>
    
                                            </div>
                                            <span class="text-danger"><?php echo form_error('text_customers'); ?></span>
                                        </div>
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
                                            <th><?php echo $this->lang->line('customers_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('company'); ?></th>
                                            <th><?php echo $this->lang->line('address'); ?></th>
                                            <th><?php echo $this->lang->line('credit_limit'); ?></th>
                                            <th><?php echo $this->lang->line('balance'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($resultlist)) {

                                        } else {
                                            $count = 1;
                                            foreach ($resultlist as $customers) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $customers['customers_id']; ?></td>
                                                    <td>
                                                        <a <?php if ($this->rbac->hasPrivilege('customers', 'can_view')) {?> href="<?php echo base_url(); ?>admin/customers/profile/<?php echo $customers['id']; ?>"
                                                        <?php }?>><?php echo $customers['name'] . " " . $customers['surname']; ?>
                                                        </a>
                                                        <?php
                                                        if ($customers['phone'] != "") {
                                                            ?>
                                                            <a href="#" data-toggle="popover" class="detail_popover" >
                                                            <i class="fa fa-phone-square"></i> <?php echo $customers['phone'] ?>
                                                            <br>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($customers['email'] != "") {
                                                            ?>
                                                            <i class="fa fa-envelope"></i> <?php echo $customers['email'] ?>

                                                            <?php
                                                        }
                                                        ?>

                                                        <div class="fee_detail_popover" style="display: none">
                                                            <?php
                                                            if ($customers['description'] == "") {
                                                                ?>
                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <p class="text text-info"><?php echo $customers['description']; ?></p>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($customers['company'] != "") {
                                                            ?>
                                                                <?php echo $customers['company'] ?>
                                                            <br>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php
                                                        if ($customers['address'] != "") {
                                                            ?>
                                                            <i class="fa fa-building"></i> <?php echo $customers['address'].' '.$customers['area_name'].' '.$customers['city_name']; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php
                                                        if ($customers['credit_limit'] != "") {
                                                            ?>
                                                            <?php echo  number_format($customers['credit_limit'], 0, '.', ',') ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <?php
                                                        $salesresult            = $this->customers_model->getSalesBycustomers($customers['brc_id'],$customers['id'],'','');
                                                        $paymentsresult         = $this->customers_model->getPaymentsBycustomers($customers['brc_id'],$customers['id'],'','');
                                                        $ledgerlist             = array_merge($salesresult, $paymentsresult);
                                                        $total_redebit_amount = 0;
                                                        $total_recredit_amount = 0;
                                                        if(!empty($ledgerlist)){
                                                            array_multisort(array_column($ledgerlist, 'date'), SORT_ASC, $ledgerlist);
                                                            foreach($ledgerlist as $led_key => $led_val){
                                                                if(!empty($led_val['amount'])){ 
                                                                    $total_redebit_amount = $total_redebit_amount + $led_val['amount'];
                                                                }
                                                                if(!empty($led_val['paid_amount'])){ 
                                                                    $total_recredit_amount = $total_recredit_amount + $led_val['paid_amount'];
                                                                }
                                                            }
                                                        }
                                                        echo number_format($total_redebit_amount - $total_recredit_amount, 2, '.', ',');
                                                        ?>
                                                    </td>
                                                    <td class="pull-right">
                                                        <?php if ($this->rbac->hasPrivilege('show_customers_ledger', 'can_view')) { ?>
                                                            <a class="btn btn-primary btn-xs" title="<?php echo $this->lang->line('show_customers_ledger'); ?>"  href="<?php echo base_url() . "admin/customers/ledgers/" . $customers["id"] ?>"><i class="fa fa-file-text-o"></i></a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('customers', 'can_view')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/customers/profile/<?php echo $customers['id'] ?>" class="btn btn-info btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>" >
                                                                <i class="fa fa-reorder"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('customers', 'can_edit')) { 
                                                                if($customers['id'] == 1){}else{ ?>
                                                                    <a href="<?php echo base_url(); ?>admin/customers/edit/<?php echo $customers['id'] ?>/<?php echo $customers['brc_id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                            <?php } ?>
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
                                            foreach ($resultlist as $customers) { ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6 img_div_modal">
                                                    <div class="staffinfo-box">
                                                        <div class="staffleft-box">
                                                            <?php
                                                            if (!empty($customers["image"])) {
                                                                $image = $customers["image"];
                                                            } else {
                                                                if ($customers['gender'] == 'Male') {
                                                                    $image = "default_male.jpg";
                                                                } else {
                                                                    $image = "default_female.jpg";
                                                                }

                                                            }
                                                            ?>
                                                            <img  src="<?php echo $this->media_storage->getImageURL( "uploads/customers_images/" . $image) ?>" />
                                                        </div>
                                                        <div class="staffleft-content">
                                                            <h5><span data-toggle="tooltip" title="<?php echo $this->lang->line('name'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $customers["name"] . " " . $customers["surname"]; ?></span></h5>
                                                            <p><font data-toggle="tooltip" title="<?php echo "customers Id"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $customers["customers_id"] ?></font></p>
                                                            <p><font data-toggle="tooltip" title="<?php echo "Contact Number"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><?php echo $customers["phone"] ?></font></p>
                                                        </div>
                                                        <div class="overlay3">
                                                            <div class="stafficons">
                                                                <?php if ($this->rbac->hasPrivilege('show_customers_ledger', 'can_view')) { ?>
                                                                    <a title="<?php echo $this->lang->line('show_customers_ledger'); ?>"  href="<?php echo base_url() . "admin/customers/ledgers/" . $customers["id"] ?>"><i class="fa fa-file-text-o"></i></a>
                                                                <?php } ?>
                                                                <?php if ($this->rbac->hasPrivilege('customers', 'can_view')) { ?>
                                                                    <a title="<?php echo $this->lang->line('view'); ?>"  href="<?php echo base_url() . "admin/customers/profile/" . $customers["id"] ?>"><i class="fa fa-navicon"></i></a>
                                                                <?php } ?>
                                                                <?php if ($this->rbac->hasPrivilege('customers', 'can_edit')) { 
                                                                    if($customers['id'] == 1){}else{ ?>
                                                                        <a title="<?php echo $this->lang->line('edit'); ?>"  href="<?php echo base_url() . "admin/customers/edit/" . $customers["id"].'/'.$customers["brc_id"] ?>"><i class=" fa fa-pencil"></i></a>
                                                                    <?php } ?>
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
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/customers/index/'); ?>'+val;          
        if(url){
            window.location.href = url; 
        }
    }
    function getBranchByIDByUrl(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>
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