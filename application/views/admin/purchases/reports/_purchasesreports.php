<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('purchases_reports') ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php
                    if ($this->rbac->hasPrivilege('supplier_wise_purchases_summary', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/purchases/supplierwisepurchasessum'); ?>"><a href="<?php echo base_url(); ?>admin/purchases/supplierwisepurchasessum"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('supplier').' '.$this->lang->line('wise').' '.$this->lang->line('purchases').' '.$this->lang->line('summary'); ?></a></li>
                        <?php 
                    }
                    if ($this->rbac->hasPrivilege('supplier_wise_purchases_details', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/purchases/supplierwisepurchasesdet'); ?>"><a href="<?php echo base_url(); ?>admin/purchases/supplierwisepurchasesdet"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('supplier').' '.$this->lang->line('wise').' '.$this->lang->line('purchases').' '.$this->lang->line('details'); ?></a></li>
                        <?php 
                    } 
                    if ($this->rbac->hasPrivilege('produts_wise_purchases_summary', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/purchases/itemwisepurchasessum'); ?>"><a href="<?php echo base_url(); ?>admin/purchases/itemwisepurchasessum"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('products').' '.$this->lang->line('wise').' '.$this->lang->line('purchases').' '.$this->lang->line('summary'); ?></a></li>
                        <?php 
                    } 
                    if ($this->rbac->hasPrivilege('produts_wise_purchases_details', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/purchases/itemwisepurchasesdet'); ?>"><a href="<?php echo base_url(); ?>admin/purchases/itemwisepurchasesdet"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('products').' '.$this->lang->line('wise').' '.$this->lang->line('purchases').' '.$this->lang->line('details'); ?></a></li>
                        <?php 
                    } 
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>