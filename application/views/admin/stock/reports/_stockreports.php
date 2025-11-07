<div class="row">
    <div class="col-md-12">
        <div class="box box-primary border0 mb0 margesection">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('stock_reports') ?></h3>
            </div>
            <div class="">
                <ul class="reportlists">
                    <?php 
                    if ($this->rbac->hasPrivilege('quantity_wise_stock', 'can_view')) {  ?>
						<li class="col-lg-4 col-md-4 col-sm-6 <?php echo set_SubSubmenu('Reports/stock/qtywisestock'); ?>"><a href="<?php echo base_url(); ?>admin/stock/qtywisestock"><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('quantity').' '.$this->lang->line('wise').' '.$this->lang->line('stock').' '.$this->lang->line('report'); ?></a></li>
                        <?php 
                    } 
                    ?>

                </ul>
            </div>
        </div>
    </div>
</div>