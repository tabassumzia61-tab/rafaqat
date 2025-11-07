<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('sales_reports') ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php 
                    if ($this->rbac->hasPrivilege('customer_wise_sales_summary', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/sales/customerwisesalessum'); ?>"><a href="<?php echo base_url(); ?>admin/sales/customerwisesalessum"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('customer').' '.$this->lang->line('wise').' '.$this->lang->line('sales').' '.$this->lang->line('summary'); ?></a></li>
                        <?php 
                    } 
                    if ($this->rbac->hasPrivilege('customer_wise_details', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/sales/customerwisesalesdet'); ?>"><a href="<?php echo base_url(); ?>admin/sales/customerwisesalesdet"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('customer').' '.$this->lang->line('wise').' '.$this->lang->line('sales').' '.$this->lang->line('details'); ?></a></li>
                        <?php 
                    }
                    if ($this->rbac->hasPrivilege('sales_wise_produts_summary', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/sales/itemwisesalessum'); ?>"><a href="<?php echo base_url(); ?>admin/sales/itemwisesalessum"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('products').' '.$this->lang->line('wise').' '.$this->lang->line('sales').' '.$this->lang->line('summary'); ?></a></li>
                        <?php 
                    } 
                    if ($this->rbac->hasPrivilege('sales_wise_produts_details', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/sales/itemwisesalesdet'); ?>"><a href="<?php echo base_url(); ?>admin/sales/itemwisesalesdet"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('products').' '.$this->lang->line('wise').' '.$this->lang->line('sales').' '.$this->lang->line('details'); ?></a></li>
                        <?php 
                    } 
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>