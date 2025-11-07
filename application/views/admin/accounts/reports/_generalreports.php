<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('general_reports') ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php 
                    if($this->rbac->hasPrivilege('general_ledger', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/accounts/generalledger'); ?>"><a href="<?php echo base_url(); ?>admin/accounts/generalledger"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('general_ledger'); ?></a></li>
                        <?php 
                    } 
                    if($this->rbac->hasPrivilege('trial_balance', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/accounts/trialbalance'); ?>"><a href="<?php echo base_url(); ?>admin/accounts/trialbalance"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('trial_balance'); ?></a></li>
                        <?php 
                    } 
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>