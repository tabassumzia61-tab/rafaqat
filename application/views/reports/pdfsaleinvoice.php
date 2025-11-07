<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Sale Invoice</title>
    <style type="text/css">
        body{
            width: 100%;
            float: left;
            height: 29.7cm;
            margin: 0 auto;
            color: #000;
            background-color: #FFF;
            font-size: 14px;
            font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
        }
        
        .main{
            width: 100%;
            float: left;
            background-color:#FFF;
            color: #000; 
        }
        .main .main-warp{
            width: 100%;
            float: left;
            background-color:#FFF;
            color: #000; 
        }
        .main .main-warp .first-warp{
            width: 100%;
            float: left;
        }
        .main .main-warp .first-warp .first-warp-content{
            width: 100%;
            float: left;
            border:1px solid #000; 
        }
        h2.name{
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
            padding: 0;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            color: #000;
            background-color: #FFF;
            border: 1px solid #000;
        }

        table th{
            padding: 5px;
            background: #FFF;
            text-align: center;
            color: #000;
            border: 1px solid #000;
            font-size: 14px;
            font-weight: bold;
        }
        table td {
            padding: 2px;
            background: #FFF;
            color: #000;
            text-align: center;
            font-size: 14px;
            border: 1px solid #000;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }
    </style>
</head>
<body style="background-color: #FFF;">
    <?php $currency_symbol = $this->customlib->getSystemCurrencyFormat(); ?>
    <main class="main">
        <div class="main-warp">
            <div class="first-warp">
                <div class="first-warp-content">
                    <div style="width: 100%;float: left;padding: 20px 0;">
                        <div style="width: 17%;float: left; text-align: center;">
                            <img style="height:65px;" src="<?php echo base_url(); ?>uploads/system_content/logo/<?php echo $settinglist->image; ?>">
                        </div>
                        <div style="width: 82%;float: left;">
                            <div style="width: 100%;float: left;">
                                <div style="width: 100%;float: left;text-align: right;color: #000;font-size: 45px;font-weight: bold;"><?php echo 'Sales'; ?></div>   
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 5px;">
                        <div style="width: 50%;float: left;padding:0 0 0 15px;">
                            <div style="width: 100%;float: left;"><b><?php echo $settinglist->name; ?></b></div>
                            <div style="width: 100%;float: left;"><?php echo $settinglist->address; ?></div>
                            <div style="width: 100%;float: left;"><?php echo "Phone:"; ?> <?php echo $settinglist->phone; ?></div>
                            <div style="width: 100%;float: left;"><?php echo "Fax:"; ?><?php echo $settinglist->phone; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'E-mail:'; ?><?php echo $settinglist->email; ?></div>
                        </div>
                        <div style="width: 45%;float:left;padding:0 15px 0 0;">
                            <div style="width: 100%;float: left;">
                                <table style="width: 450px; border: none;float: right;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Date: '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php if(!empty($salesList['date']) || $salesList['date'] == '0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), strtotime($salesList['date'])); } ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Sale#: '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php echo $salesList['sale_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Customer ID : '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php echo $salesList['customers_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Due Date: '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php if(!empty($salesList['due_date']) || $salesList['due_date'] == '0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), strtotime($salesList['due_date'])); } ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 5px;">
                        <div style="width: 50%;float: left;padding:0 0 0 15px;">
                            <div style="width: 100%;float: left;"><span style="display: inline-block;padding: 5px 250px 5px 5px;border: 1px solid #CCC; background-color: #ccc;"><b><?php echo 'Cusomter ';?></b></span></div>
                            <div style="width: 100%;float: left;"><?php echo 'Company Name: ';?> <?php echo $salesList['company']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Name: ';?> <?php echo $salesList['name'].' '.$salesList['surname']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Address: ';?> <?php echo $salesList['address']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'E-mail: ';?> <?php echo $salesList['email']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Phone: ';?> <?php echo $salesList['phone']; ?></div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 5px;">
                        <div style="width: 100%;float: left;padding:0 15px 0 15px;">
                            <div style="width: 100%;float: left;"><span style="display: inline-block;padding: 5px 240px 5px 5px;border: 1px solid #CCC; background-color: #ccc;"><b><?php echo 'Order Items';?></b></span>
                            </div>
                            <div style="width: 100%;float: left;margin-top:10px;">
                                <table>
                                    <thead>
                                        <tr>
                                            <td style="text-align:left;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo 'Sr.#'; ?></td>
                                            <td style="text-align:left;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('products_services'); ?></td>
                                            <td style="text-align:left;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('description'); ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('quantity'); ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('unit'); ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('rate'). ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('sale_tax_rate').' %'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('sale_tax'). ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('discount_percent').' %'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('discount'). ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('amount'). ' (' . $currency_symbol . ')'; ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 1;  
                                            if(!empty($salesList['orderitem'])){ 
                                                foreach ($salesList['orderitem'] as $oi_key => $oi_val) { ?>
                                                    <tr>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $count; ?></td>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $oi_val['product_name']; ?></td>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $oi_val['description']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['quantity']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['unit']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['net_unit_price']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['item_tax']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo number_format($oi_val['tax'],2,'.',','); ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['item_discount']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo number_format($oi_val['discount'],2,'.',','); ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo number_format($oi_val['subtotal'],2,'.',','); ?></td>
                                                    </tr>
                                                <?php $count++; } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 20px;">
                        <div style="width: 100%;float: left;padding:0 15px 0 15px;">
                            <div style="width: 65%;float: left;padding: 15px 15px 0 0;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:left;padding: 3px;border-bottom:1px solid #ccc;background-color: #ccc;"><b><?php echo 'Notes or Special Comments:'; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding: 5px;height: 150px;">
                                                <?php echo $salesList['note']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="width: 30%;float: left;padding-top: 15px;">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('total') . ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($salesList['total'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('discount') . ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($salesList['total_discount'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('sale_tax') . ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($salesList['total_tax'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('net_amount') . ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($salesList['grand_total'],2,'.',','); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>