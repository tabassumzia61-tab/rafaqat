<?php $currency_symbol = $this->customlib->getSystemCurrencyFormat(); ?>
<div class="col-md-12">
    <div class="row">  
    	<div class="panel panel-default">
            <div class="panel-heading">
                <?php  echo $this->lang->line('quotations_details'); ?>
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-striped table-borderless" style="margin-bottom:0;">
                    <tbody>
                        <tr>
                            <td><b><?php  echo $this->lang->line('date'); ?></b></td>
                            <td><?php if(!empty($inv['date']) || $inv['date'] == '0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), strtotime($inv['date'])); } ?></td>
                            <td><b><?php  echo 'Quotations#'; ?></b></td>
                            <td><?php echo $inv['quotes_no']; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php  echo 'Expiry Date:'; ?></b></td>
                            <td><?php if(!empty($inv['expiry_date']) || $inv['expiry_date'] == '0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), strtotime($inv['expiry_date'])); } ?></td>
                            <td><b><?php  echo 'Customer ID :'; ?></b></td>
                            <td><?php echo $inv['customers_no']; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php  echo 'Company Name: '; ?></b></td>
                            <td><?php echo $inv['company']; ?></td>
                            <td><b><?php  echo 'Name :'; ?></b></td>
                            <td><?php echo $inv['name']; ?></td>
                        </tr>
                        <tr>
                            <td><b><?php  echo 'Address: '; ?></b></td>
                            <td><strong><?php echo $inv['address']; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">  
    	<div class="panel panel-default">
            <div class="panel-heading">
                <?php  echo 'Order Items'; ?>
            </div>
            <div class="panel-body">
                <table class="table table-condensed table-striped table-borderless" style="margin-bottom:0;">
                    <thead>
                        <tr class="font13 white-space-nowrap">
                            <th>#</th>
                            <th><?php echo $this->lang->line('products_services'); ?></th>
                            <th><?php echo $this->lang->line('description'); ?></th>
                            <th><?php echo $this->lang->line('quantity'); ?></th>
                            <th><?php echo $this->lang->line('unit'); ?></th>
                            <th><?php echo $this->lang->line('rate') . ' (' . $currency_symbol . ')'; ?></th>
                            <th><?php echo $this->lang->line('sale_tax_rate'); ?></th>
                            <th><?php echo $this->lang->line('sale_tax'); ?></th>
                            <th><?php echo $this->lang->line('discount_percent'); ?></th>
                            <th><?php echo $this->lang->line('discount'); ?></th>
                            <th><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count = 1;  
                            if(!empty($inv['orderitem'])){ 
                                foreach ($inv['orderitem'] as $oi_key => $oi_val) { ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $oi_val['product_name']; ?></td>
                                        <td><?php echo $oi_val['description']; ?></td>
                                        <td><?php echo $oi_val['quantity']; ?></td>
                                        <td><?php echo $oi_val['unit']; ?></td>
                                        <td><?php echo $oi_val['net_unit_price']; ?></td>
                                        <td class="text-right"><?php echo $oi_val['item_tax']; ?></td>
                                        <td class="text-right"><?php echo number_format($oi_val['tax'],2,'.',','); ?></td>
                                        <td class="text-right"><?php echo $oi_val['item_discount']; ?></td>
                                        <td class="text-right"><?php echo number_format($oi_val['discount'],2,'.',','); ?></td>
                                        <td class="text-right"><?php echo number_format($oi_val['subtotal'],2,'.',','); ?></td>
                                    </tr>
                                <?php $count++; } ?>
                        <?php } ?>
                    </tbody>
                </table>
                <br/>
                <br/>
                <br/>
                <table class="carttable" style="width: 35%;display: block;float: right;">
                    <tbody>
                        <tr>
                            <th style="padding-right:130px"><?php echo $this->lang->line('sub_total') . " (" . $currency_symbol . ")"; ?></th>
                            <td class="text-right"><?php echo number_format($inv['total'],2,'.',','); ?></td>
                        </tr>
                        <tr>
                            <th style="padding-right:130px"><?php echo $this->lang->line('total').' '.$this->lang->line('discount') . " (" . $currency_symbol . ")"; ?></th>
                            <td class="text-right"><?php echo number_format($inv['total_discount'],2,'.',','); ?></td>
                        </tr>
                        <tr>
                            <th style="padding-right:130px"><?php echo $this->lang->line('total').' '.$this->lang->line('sale_tax') . " (" . $currency_symbol . ")"; ?></th>
                            <td class="text-right"><?php echo number_format($inv['total_tax'],2,'.',','); ?></td>
                        </tr>
                        <tr>
                            <th style="padding-right:130px"><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?></th>
                            <td class="text-right"><?php echo number_format($inv['grand_total'],2,'.',','); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>