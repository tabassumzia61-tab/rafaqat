<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
    <title>Customer Ledgers</title>
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
            padding: 10px 10px;
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
    </style>
</head>
<body style="background-color: #FFF;">
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
                        <div style="font-weight: bold;font-size: 12px;"><?php echo 'Customer Ledger'; ?></div>
                    </div>
                    <div style="width: 15%;float: left;text-align: center;">
                    </div>
                </div>
            </header>
            <div style="width: 100%;float: left;margin-bottom: 10px;">
                
            </div>
            <div style="width: 100%;float: left;">
                <?php if(!empty($ledgerlist)){ ?>
                    <div style="width: 100%;float: left;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo 'Sr.#'; ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('date') ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('document_no'); ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('description'); ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('debit') ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('credit') ?></b></th>
                                    <th style="padding:3px;font-size: 12px"><b><?php echo $this->lang->line('balance') ?></b></th>
                                </tr>
                            </thead>
                            <tbody id="debit">
                                <?php 
                                    $counter = 1;
                                    $total_debit_amount = 0;
                                    $total_credit_amount = 0;
                                    array_multisort(array_column($ledgerlist, 'date'), SORT_ASC, $ledgerlist);
                                    foreach($ledgerlist as $led_key => $led_val){ ?>
                                        <tr>
                                            <td style="padding:3px;font-size: 12px"><?php echo $counter; ?></td>
                                            <td style="padding:3px;font-size: 12px"><?php if(!empty($led_val['date']) && $led_val['date'] !='0000-00-00'){  echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($led_val['date'])); } ?></td>
                                            <td style="padding:3px;font-size: 12px"><?php echo $led_val['bill_no']; ?></td>
                                            <td style="padding:3px;font-size: 12px"><?php echo $led_val['note']; ?></td>
                                            <td style="text-align: right;padding:3px;font-size: 12px" class="total_debit">
                                                <?php 
                                                    if(!empty($led_val['amount'])){ 
                                                        echo (float)$led_val['amount']; 
                                                        $total_debit_amount += (float)$led_val['amount'];
                                                    } 
                                                ?>
                                            </td>
                                            <td style="text-align: right;padding:3px;font-size: 12px" class="total_credit">
                                                <?php 
                                                    if(!empty($led_val['paid_amount'])){ 
                                                        echo (float)$led_val['paid_amount']; 
                                                        $total_credit_amount += (float)$led_val['paid_amount'];
                                                    } 
                                                ?>
                                            </td>
                                            <td style="text-align: right;font-weight:bold;padding:3px;font-size: 12px" class="balance">0</td>
                                        </tr>
                                <?php $counter++; } ?>
                                <tr style="background-color:#C0C0C0;">
                                    <td style="text-align: right;font-weight:bold" colspan="4"><?php echo $this->lang->line('grand_total'); ?> :</td>
                                    <td style="text-align: right;font-weight:bold" class="total_debit_amount"><?php echo $total_debit_amount; ?></td>
                                    <td style="text-align: right;font-weight:bold" class="total_credit_amount"><?php echo $total_credit_amount; ?></td>
                                    <td style="text-align: right;font-weight:bold" class="total_balance_amount"><?php echo ($total_debit_amount - $total_credit_amount); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <!--<div style="width: 100%;float: left;margin-top:10px;margin-bottom:5px;">-->
            <!--    <div style="width: 50%;float: left;text-align:left;">-->
            <!--        <span  style="border-top:2px solid #000;font-weight: bold;text-align: center;color:#000;display: inline-block;padding:3px">-->
            <!--            <?php echo "Teacher's Signature"?>-->
            <!--        </span>-->
            <!--    </div>-->
            <!--    <div style="width: 50%;float: left;text-align:right;">-->
            <!--        <span  style="border-top:2px solid #000;font-weight: bold;text-align: center;color:#000;display: inline-block;padding:3px">-->
            <!--            <?php echo "Co-ordinator's Signature"?>-->
            <!--        </span>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </main>
    <script>
        $(document).ready(function () {
            var prev_balance = 0;
            var credit = 0;
            var waiveoff = 0;
            // var debit = 0;
            var balance = 0;
            var next_debit = 0;
            var new_balance = 0;
            $("#debit tr:first").find(".balance").text($("#debit tr:first").find(".total_debit").text());
            $("#debit tr").each(function(){
                balance = $(this).find(".balance").text();
                credit = $(this).find(".total_credit").text();
                $(this).find(".balance").text(Number(balance) - Number(credit));
                prev_balance = $(this).find(".balance").text();
                next_debit = $(this).next("tr").find(".total_debit").text();
                new_balance = Number(prev_balance) + Number(next_debit);
                $(this).next("tr").find(".balance").text(new_balance);
            });
            var total_credit = 0;
            var total_debit = 0;
            var total_balance = 0;
            $("#debit tr").each(function(){
                var curr_credit = $(this).find(".total_credit").text();
                total_credit += Number(curr_credit);
                var curr_debit = $(this).find(".total_debit").text();
                total_debit += Number(curr_debit);
                var curr_balance = $(this).find(".balance").text();
                total_balance += Number(curr_balance);
            });
            $("#debit tr").find('.total_credit_amount').text(total_credit);
            $("#debit tr").find('.total_debit_amount').text(total_debit);
            $("#debit tr").find('.total_balance_amount').text(Number(total_debit) - Number(total_credit));
            $('.total_receivable').text('<?php echo $this->lang->line('receivable').": "; ?>' + ( Number(total_debit) - Number(total_credit)) );
        });
    </script>
</body>
</html>