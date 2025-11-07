<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Sale Invoice</title>
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Purchase Bill Return</title>
    <script src="<?php echo base_url(); ?>assets/custom/jquery.min.js"></script>
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
            /*border:1px solid #000;*/
        }
        .main .main-warp{
            width: 100%;
            float: left;
            background-color:#FFF;
            color: #000; 
            padding: 0px 10px;
        }
        .main .main-warp .header{
            width: 100%;
            float: left;
            
            /*margin-bottom: 20px;*/
            border-bottom: 1px solid #000;
        }
        .main .main-warp .header .header-main{
            width: 100%;
            float: left;
            /*padding: 10px 10px;*/
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #000;
        }

        table th{
            padding: 5px;
            text-align: left;
            border: 1px solid #000;
        }
        table td {
            padding: 5px;
            text-align: left;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }
        
        footer {
            color: #000;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            left: 0;
            border-top: 1px solid #000;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>
<body style="background-color: #FFF;">
    <?php $currency_symbol = $this->customlib->getSystemCurrencyFormat(); ?>
    <main class="main">
        <div class="main-warp">
            <header class="header">
                <div class="header-main">
                    <div style="width: 15%;float: left;text-align: center;">
                        <img style="height:70px;margin-top: 10px;margin-bottom: 10px;" src="<?php echo base_url(); ?>uploads/system_content/logo/<?php echo $settinglist->image; ?>" />
                    </div>
                    <div style="width: 70%;float: left;text-align: center;">
                        <h1 style="margin:5px 0px 0px 0px;padding:0px;color:#000;"><?php echo $settinglist->name; ?></h1>
                        <div style="font-weight: bold;font-size: 12px;"><?php echo $settinglist->phone; ?></div>
                        <div style="font-weight: bold;font-size: 12px;"><?php echo $settinglist->address; ?></div>
                        <div style="font-weight: bold;font-size: 12px;"><?php echo 'Purchase Return Bill'; ?></div>
                    </div>
                    <div style="width: 15%;float: left;text-align: center;">
                    </div>
                </div>
            </header>
            <div style="width: 100%;float: left;">
                <div style="width: 100%;float: left;">
                    <div style="width: 100%;float: left;padding-bottom: 15px;margin-top:15px;">
                        <div style="width: 50%;float: left;">
                            <div style="width: 100%;float: left;"><span style="display: inline-block;padding: 5px 250px 5px 5px;border: 1px solid #CCC; background-color: #ccc;"><b><?php echo 'Supplier ';?></b></span></div>
                            <div style="width: 100%;float: left;"><?php echo 'Company Name: ';?> <?php echo $purchaseList['company']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Name: ';?> <?php echo $purchaseList['name'].' '.$purchaseList['surname']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Address: ';?> <?php echo $purchaseList['address']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'E-mail: ';?> <?php echo $purchaseList['email']; ?></div>
                            <div style="width: 100%;float: left;"><?php echo 'Phone: ';?> <?php echo $purchaseList['phone']; ?></div>
                        </div>
                        <div style="width: 45%;float:left;padding:0 15px 0 0;">
                            <div style="width: 100%;float: left;">
                                <table style="width: 450px; border: none;float: right;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Date: '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php if(!empty($purchaseList['date']) || $purchaseList['date'] == '0000-00-00'){ echo date($this->customlib->getSystemDateFormat(), strtotime($purchaseList['date'])); } ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Bill No#: '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php echo $purchaseList['purchase_no']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;padding: 5px 5px 5px 30px;border: none;width: 80px;"><b><?php echo 'Supplier ID : '; ?></b></td>
                                            <td style="text-align: left;padding: 5px;width: 80px;"><?php echo $purchaseList['supplier_no']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 0px;">
                        <div style="width: 100%;float: left;">
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
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('rate');//. ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('discount_rate').' %'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('discount'); //. ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('gst_rate').' %'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('gst');//. ' (' . $currency_symbol . ')'; ?></td>
                                            <td style="text-align:right;font-weight: bold;background-color: #CCC;font-size:10px;"><?php echo $this->lang->line('amount'); //. ' (' . $currency_symbol . ')'; ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 1;  
                                            if(!empty($purchaseList['orderitem'])){ 
                                                foreach ($purchaseList['orderitem'] as $oi_key => $oi_val) { ?>
                                                    <tr>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $count; ?></td>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $oi_val['product_name']; ?></td>
                                                        <td style="text-align:left;font-size:10px;"><?php echo $oi_val['description']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['quantity']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['unit']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['net_unit_cost']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['item_discount']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo number_format($oi_val['discount'],2,'.',','); ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo $oi_val['item_tax']; ?></td>
                                                        <td style="text-align:right;font-size:10px;"><?php echo number_format($oi_val['tax'],2,'.',','); ?></td>
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
                        <div style="width: 100%;float: left;">
                            <div style="width: 30%;float: right;padding-top: 5px;">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('gross_amount');// . ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($purchaseList['total'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('discount'); //. ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($purchaseList['total_discount'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('exclusive_of_gst'); //. ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($purchaseList['total'] - $purchaseList['total_discount'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('sale_tax'); //. ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($purchaseList['total_tax'],2,'.',','); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;padding: 5px;width: 100px;"><b><?php echo $this->lang->line('net_amount'); //. ": (" . $currency_symbol . ")"; ?></b></th>
                                            <td style="text-align: right;padding: 5px;width: 133px;"><?php echo number_format($purchaseList['grand_total'],2,'.',','); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;float: left;padding-bottom: 20px;">
                        <div style="width: 100%;float: left;">
                            <div style="width: 100%;float: left;">
                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:left;padding: 3px;border-bottom:1px solid #ccc;background-color: #ccc;"><b><?php echo 'Notes or Special Comments:'; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding: 5px;">
                                                <?php if(!empty($purchaseList['note'])){ echo strip_tags($purchaseList['note']); } ?>
                                            </td>
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
    <footer>
        <div style="font-weight: bold;font-size: 12px;"><?php echo 'Phone: '.$settinglist->phone; ?> <?php echo ' Email '.$settinglist->email; ?></div>
    </footer>
</body>
</html>