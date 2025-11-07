<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <h1><i class="fa fa-object-group"></i> <?php echo $this->lang->line('inventory'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/sale/sale_bill_report') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?> 
                                        <?php  
                                        if ($this->rbac->hasPrivilege('sale_bill_report', 'can_campus')) { ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('campus'); ?></label><small class="req"> *</small> 
                                                    <select  id="camp_id" name="camp_id" class="form-control campus_id">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($campuslist as $campus) {
                                                            $camp_id_log = $this->customlib->getCampusID();
                                                            if ($camp_id_log == 1) { ?>
                                                                <option value="<?php echo $campus['id'] ?>"<?php if (set_value('camp_id',$camp_id) == $campus['id']) echo "selected=selected" ?>><?php echo $campus['name'] ?></option>
                                                            <?php }else{
                                                                if ($camp_id_log == $campus['id']) { ?>
                                                                    <option value="<?php echo $campus['id'] ?>"<?php if (set_value('camp_id',$camp_id) == $campus['id']) echo "selected=selected" ?>><?php echo $campus['name'] ?></option>
                                                                <?php  } ?>
                                                            <?php
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('camp_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date_from'); ?></label>
                                                <input id="datefrom"  name="date_from" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date_to'); ?></label>
                                                <input id="dateto" name="date_to" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/sale/sale_bill_report') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <?php  
                                        if ($this->rbac->hasPrivilege('sale_bill_report', 'can_campus')) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('campus'); ?></label><small class="req"> *</small> 
                                                    <select  id="camp_id" name="camp_id" class="form-control campus_id">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($campuslist as $campus) {
                                                            $camp_id_log = $this->customlib->getCampusID();
                                                            if ($camp_id_log == 1) { ?>
                                                                <option value="<?php echo $campus['id'] ?>"<?php if (set_value('camp_id',$camp_id) == $campus['id']) echo "selected=selected" ?>><?php echo $campus['name'] ?></option>
                                                            <?php }else{
                                                                if ($camp_id_log == $campus['id']) { ?>
                                                                    <option value="<?php echo $campus['id'] ?>"<?php if (set_value('camp_id',$camp_id) == $campus['id']) echo "selected=selected" ?>><?php echo $campus['name'] ?></option>
                                                                <?php  } ?>
                                                            <?php
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('camp_id'); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date_from'); ?></label>
                                                <input id="datefrom"  name="date_from" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                         <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date_to'); ?></label>
                                                <input id="dateto" name="date_to" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('report_types'); ?></label> <small class="req">*</small>
                                                <select autofocus="" id="paided_types" name="paided_types" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <option value="salebill" <?php
                                                        if ($paid_types === 'salebill') {
                                                            echo "selected =selected";
                                                        }
                                                        ?> ><?php echo 'Sale Bill'; ?></option>
                                                    <option value="bookset" <?php
                                                        if ($paid_types === 'bookset') {
                                                            echo "selected =selected";
                                                        }
                                                        ?>><?php echo 'Sale Bill Bookset'; ?></option>
                                                </select>
                                            </div> 
                                        </div>

                                        <div class="col-sm-12"> 
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div>

                        </div>

                    </div>

                </div>
                <?php if (isset($resultList)) {
                    ?><div class="box box-info" id="exp">

                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $exp_title;//$this->lang->line('expense_result'); ?></h3>
                            <div class="box-tools pull-right">
                                <?php echo $pdf; ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">

                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('bill') . " " . $this->lang->line('no'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('student'); ?></th>
                                        <th><?php echo $this->lang->line('customer_name'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('total') . " " . '(' . $currency_symbol . ')'; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('paid_amount') . " " . '(' . $currency_symbol . ')'; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('balance') . " " . '(' . $currency_symbol . ')'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (empty($resultList)) {
                                        ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>

                                        </tr>
                                    </tfoot>
                                    <?php
                                } else {
                                    $count = 1;
                                    $grand_total = 0;
                                    $paid_amount = 0;
                                    $balance = 0;
                                    foreach ($resultList as $key => $bill) {
                                        $grand_total += $bill['grand_total'];
                                        $paid_amount += $bill['paid_amount'];
                                        $balance += $bill['grand_total'] - $bill['paid_amount'];
                                        ?>
                                        <tr>
                                            <td><?php echo $bill['bill_no']; ?></td>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($bill['date'])) ?></td>
                                            <td><?php echo $bill['firstname'].' '.$bill['lastname']; ?></td>
                                            <td class="mailbox-name"><?php if(!empty($bill['customer_name'])){ echo $bill['customer_name'];}else{echo $bill['name'];} ?></td>
                                            <td class="text-right"><?php echo $bill['grand_total']; ?></td>
                                            <td class="text-right"><?php echo $bill['paid_amount']; ?></td>
                                            <td class="text-right"><?php echo $bill['grand_total'] - $bill['paid_amount']; ?></td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                    <tr class="box box-solid total-bg">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"><?php echo $this->lang->line('grand_total'); ?> </td>
                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($grand_total, 0, '.', '')); ?></td>
                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($paid_amount, 0, '.', '')); ?></td>
                                        <td class="text text-right"><?php echo ($currency_symbol . number_format($balance, 0, '.', '')); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                                </tbody>
                            </table>

                        </div>

                    </div>
                    <?php
                }
                ?>

            </div>      

        </div>   <!-- /.row -->

    </section><!-- /.content -->
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $(".date").datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true,
            todayHighlight: true

        });

        $.extend($.fn.dataTable.defaults, {
            paging: false,
            bSort: false,
        });
    });
</script>
