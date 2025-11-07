<?php
$currency_symbol = $this->customlib->getSystemCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 393px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="box-title"><?php echo $this->lang->line('staff_details'); ?></h3>
                            </div>
                            <div class="col-md-8 ">
                                <div class="btn-group pull-right">
                                    <a href="<?php echo base_url() ?>admin/payroll" type="button" class="btn btn-primary btn-xs">
                                        <i class="fa fa-arrow-left"></i> </a>
                                    </div>
                                </div>
                            </div>
                        </div><!--./box-header-->
                        <div class="box-body" style="padding-top:0;">
                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <div class="sfborder">
                                        <div class="col-md-2">
                                            <div class="row">
                                                <?php
                                                $image = $result['image'];
                                                if (!empty($image)) {

                                                    $file = $result['image'];
                                                } else {

                                                    $file = "no_image.png";
                                                }
                                                $image=$this->media_storage->getImageURL("uploads/staff_images/" . $file);
                                                ?>
                                                <img width="115" height="115" class="round5" src="<?php echo $image ?>" alt="No Image">
                                            </div>
                                        </div>

                                        <div class="col-md-10">
                                            <div class="row">
                                                <table class="table mb0 font13">
                                                    <tbody>
                                                        <tr>
                                                            <th class="bozero"><?php echo $this->lang->line("name"); ?></th>
                                                            <td class="bozero"><?php echo $result["name"] . " " . $result["surname"] ?></td>
                                                            <th class="bozero"><?php echo $this->lang->line('staff_id'); ?></th>
                                                            <td class="bozero"><?php echo $result["employee_id"] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                                            <td><?php echo $result["contact_no"] ?></td>
                                                            <th><?php echo $this->lang->line('email'); ?></th>
                                                            <td><?php echo $result["email"] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('epf_no'); ?></th>
                                                            <td><?php echo $result["epf_no"] ?></td>
                                                            <th><?php echo $this->lang->line('role'); ?></th>
                                                            <td><?php echo $result["user_type"] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo $this->lang->line('department'); ?></th>
                                                            <td><?php echo $result["department"] ?></td>
                                                            <th><?php echo $this->lang->line('designation'); ?></th>
                                                            <td><?php echo $result["designation"] ?>   </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div></div><!--./col-md-8-->
                                    <div class="col-md-4 col-sm-12">
                                        <div class="sfborder relative overvisible">
                                            <div class="letest">
                                                <div class="rotatetest"><?php echo $this->lang->line("attendance") ?></div>
                                            </div>
                                            <div class="padd-en-rtl33">
                                                <table class="table mb0 font13" >
                                                    <tr>
                                                        <th  class="bozero"><?php echo $this->lang->line('month'); ?></th>
                                                        <?php foreach ($attendanceType as $key => $value) {?>
                                                            <th class="bozero"><span data-toggle="tooltip" title="<?php echo $value["type"]; ?>"><?php echo strip_tags($value["key_value"]); ?></span></th>
                                                        <?php }
                                                        ?>
                                                        <th class="bozero"><span data-toggle="tooltip" title="<?php echo $this->lang->line('approved_leave'); ?>">V</span></th>
                                                    </tr>
                                                    <?php
                                                    foreach ($monthAttendance as $attendence_key => $attendence_value) {
                                                    ?><tr>
                                                        <td><?php echo $this->lang->line(strtolower(date("F", strtotime($attendence_key)))); ?></td>
                                                        <td><?php echo $attendence_value['present'] ?></td>
                                                        <td><?php echo $attendence_value['absent']; ?></td>
                                                        <td><?php echo $attendence_value['leave']; ?></td>
                                                        <td><?php echo $attendence_value['late']; ?></td>
                                                        <td><?php echo $attendence_value['half_day']; ?></td>
                                                        <td><?php echo $attendence_value['holiday']; ?></td>
                                                        <td><?php echo $monthLeaves[date("m", strtotime($attendence_key))]; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div><!--./col-md-8-->
                                <div class="col-md-12">
                                    <div style="background: #dadada; height: 1px; width: 100%; clear: both; margin-bottom: 10px;"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <form class="form-horizontal" action="<?php echo site_url('admin/payroll/payslip') ?>" method="post"  id="employeeform">
                            <div class="box-header">
                                <div class="row display-flex">
                                    <div class="col-md-4 col-sm-4">
                                        <h3 class="box-title"><?php echo $this->lang->line('earning'); ?></h3>
                                        <button type="button" onclick="add_more()" class="plusign"><i class="fa fa-plus"></i></button>
                                        <div class="sameheight">
                                            <div class="feebox">
                                                <table class="table3" id="tableID">
                                                    <tr id="row0">
                                                       <td>
                                                            <div class="form-group" style="width: 98%;margin-left: -9px;">
                                                                <select id="allowance_type" name="allowance_type[]" class="form-control pay_type">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php
                                                                        foreach ($paytypeList as $type_val) { ?>
                                                                            <option value="<?php echo $type_val['id'] ?>" <?php if (set_value('fee_type[]') == $type_val['id']) echo "selected=selected" ?>><?php echo $type_val['type']; ?></option> 
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" id="allowance_amount" name="allowance_amount[]" class="form-control allowance_amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="0" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!--./col-md-4-->
                                    <div class="col-md-4 col-sm-4">
                                        <h3 class="box-title"><?php echo $this->lang->line('deduction'); ?></h3>
                                        <button type="button" onclick="add_more_deduction()" class="plusign"><i class="fa fa-plus"></i></button>
                                        <div class="sameheight">
                                            <div class="feebox">
                                                <table class="table3" id="tableID2">
                                                    <tr id="deduction_row0">
                                                        <td>
                                                            <div class="form-group" style="width: 98%;margin-left: -9px;">
                                                                <select id="deduction_type" name="deduction_type[]" class="form-control deduction_type">
                                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                                    <?php
                                                                        foreach ($paydeductionList as $ded_val) { ?>
                                                                            <option value="<?php echo $ded_val['id'] ?>" <?php if (set_value('fee_type[]') == $ded_val['id']) echo "selected=selected" ?>><?php echo $ded_val['type']  ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" id="deduction_amount" name="deduction_amount[]" class="form-control deduction_amount" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" value="0" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div><!--./col-md-4-->
                                    <div class="col-md-4 col-sm-4">
                                        <h3 class="box-title"><?php echo $this->lang->line('payroll_summary'); ?> (<?php echo $currency_symbol ?>)</h3>
                                        <button type="button" onclick="add_allowance()" class="plusign"><i class="fa fa-calculator"></i> <?php echo $this->lang->line('calculate'); ?></button>
                                        <div class="sameheight">
                                            <div class="payrollbox feebox">
                                                <?php 
                                                    $total_amount_pay = 0;
                                                    $total_bic_pay = 0;
                                                    foreach ($assignpayList as $pay_key => $pay_val) { 
                                                        $total_amount_pay = $total_amount_pay + $pay_val['amount'];
                                                        if($pay_val['payallownc_id'] == 14){
                                                            $readonly = 'readonly="readonly"';
                                                            $total_bic_pay = $total_bic_pay + $pay_val['amount'];
                                                        }else{
                                                            $readonly = '';
                                                        }
                                                        if($pay_val['payallownc_id'] == 14){
                                                        ?>
                                                        <div class="row" style="padding:0 15px 0 0" >
                                                            <div class="col-md-9">
                                                                <div class="form-group" style="margin-bottom: 5px">
                                                                    <input type="hidden" name="allowances_m_type[]" value="<?php echo $pay_val['payallownc_id']; ?>" />
                                                                    <label class="control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $pay_val['type'] ?></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group" style="margin-bottom: 3px">
                                                                <input id="basic_paay" class="form-control typepayamount" name="allowances_m_amount[]" value="<?php
                                                                    if (!empty($pay_val['amount'])) {
                                                                        echo number_format($pay_val['amount'], 0, '.', '');
                                                                    } else {
                                                                        echo "0";
                                                                    }
                                                                    ?>"  type="text" <?php echo $readonly; ?>  data-paytypeid="<?php echo $pay_val['payallownc_id']; ?>"  data-amount="<?php echo $pay_val['amount']; ?>" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php }else{ ?>
                                                            <div class="row" style="padding:0 15px 0 0" >
                                                                <div class="col-md-9">
                                                                    <div class="form-group" style="margin-bottom: 5px">
                                                                        <input type="hidden" name="allowances_e_type[]" value="<?php echo $pay_val['payallownc_id']; ?>" />
                                                                        <label class="control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $pay_val['type'] ?></label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group" style="margin-bottom: 3px">
                                                                    <input id="payallowanceam" onkeyup="getcurrenttotalam(this)" class="form-control typepayamount payallowanceam" name="allowances_e_amount[]" value="<?php
                                                                        if (!empty($pay_val['amount'])) {
                                                                            echo number_format($pay_val['amount'], 0, '.', '');
                                                                        } else {
                                                                            echo "0";
                                                                        }
                                                                        ?>"  type="text"  data-paytypeid="<?php echo $pay_val['payallownc_id']; ?>"  data-amount="<?php echo $pay_val['amount']; ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                <?php } ?>
                                                <div class="row" style="padding:0 15px 0 0" >
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="margin-bottom: 5px;text-align: left;font-size: 16px;font-weight: bold;margin-left: 0;">
                                                            <?php echo 'Total Amount'; ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group totampay" style="margin-bottom: 3px;text-align: right;font-size: 20px;font-weight: bold;">
                                                            <?php echo $total_amount_pay; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-7 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('salary'); ?></label>
                                                            <div class="col-sm-5" style="padding: 0px;">
                                                                <input class="form-control" name="basic" value="<?php
                                                                if (!empty($total_bic_pay)) {
                                                                    echo $total_bic_pay;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="basic"  type="text" readonly="readonly" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('per_day'); ?></label>
                                                            <div class="col-sm-7" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="per_day" value="<?php
                                                                $perdaysalary = 0;
                                                                if(!empty($total_bic_pay)){
                                                                    $perdaysalary = $total_bic_pay/30;
                                                                }
                                                                if (!empty($perdaysalary)) {
                                                                    echo (number_format($perdaysalary, 2, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="per_day"  type="text"  readonly="readonly"/>
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('present'); ?></label>
                                                            <div class="col-sm-7" style="padding: 0px;">
                                                                <input class="form-control" name="present" onchange="total_present_amounts(this)" value="<?php
                                                                
                                                                if (!empty($current_month_days)) {
                                                                    echo $total_day = 30 - $att_absent - $att_leave - $att_late - $att_half_day;
                                                                } else {
                                                                    echo $total_day = "0";
                                                                }
                                                                ?>" id="present"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('amount'); ?></label>
                                                            <div class="col-sm-7" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="present_amount" value="<?php
                                                                if (!empty($total_day)) {
                                                                    echo (number_format($total_day * $perdaysalary, 0, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="present_amount"  type="text"  readonly="readonly"/>
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('absent'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding: 0px;">
                                                                <input class="form-control" name="absent" onchange="total_absent_amounts(this)" value="<?php
                                                                if (!empty($att_absent)) {
                                                                    echo $att_absent;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="absent"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('amount'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="absent_amount" value="<?php
                                                                if (!empty($att_absent)) {
                                                                    echo (number_format($att_absent * $perdaysalary, 0, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="absent_amount"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('leave'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding: 0px;">
                                                                <input class="form-control " name="leave" onchange="total_leave_amounts(this)" value="<?php
                                                                if (!empty($att_leave)) {
                                                                    echo $att_leave;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="leave"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('amount'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="leave_amount" value="<?php
                                                                if (!empty($att_leave)) {
                                                                    echo (number_format($att_leave * $perdaysalary, 0, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="leave_amount"  type="text"  readonly="readonly"/>
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('late'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding: 0px;">
                                                                <input class="form-control" name="late" onchange="total_late_amounts(this)"  value="<?php
                                                                if (!empty($att_late)) {
                                                                    echo $att_late;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="late"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('amount'); ?></label>
                                                            <div class="col-sm-7 deductiondred" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="late_amount" value="<?php
                                                                if (!empty($att_late)) {
                                                                    echo (number_format($att_late * $perdaysalary, 0, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="late_amount"  type="text"  readonly="readonly"/>
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-7 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('half_day'); ?></label>
                                                            <div class="col-sm-5 deductiondred" style="padding: 0px;">
                                                                <input class="form-control" name="halfday" onchange="total_halfday_amounts(this)" value="<?php
                                                                if (!empty($att_half_day)) {
                                                                    echo $att_half_day;
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="halfday"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('amount'); ?></label>
                                                            <div class="col-sm-8 deductiondred" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="halfday_amount" value="<?php
                                                                if (!empty($att_half_day)) {
                                                                    echo (number_format($att_half_day * $perdaysalary, 0, '.', ''));
                                                                } else {
                                                                    echo "0";
                                                                }
                                                                ?>" id="halfday_amount"  type="text"  readonly="readonly"/>
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('total').' '.$this->lang->line('security'); ?></label>
                                                            <div class="col-sm-6" style="padding:0px 15px 0 0;">
                                                                <?php 
                                                                    $staffsecded = $this->payroll_model->getDedByAccByStaff($staff_id,48,'negative','sec'); 
                                                                    $tot_sec = 0;
                                                                    if(!empty($staffsecded)){
                                                                        $tot_sec = $staffsecded['tot_ded_amount'];
                                                                    }
                                                                ?>
                                                                <input class="form-control" name="total_sec" id="total_sec" type="text" value="<?php echo $tot_sec; ?>" readonly />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('total').' '.$this->lang->line('gp_fund'); ?></label>
                                                            <div class="col-sm-6" style="padding:0px 15px 0 0;">
                                                                <?php 
                                                                    $staffgpded = $this->payroll_model->getDedByAccByStaff($staff_id,45,'negative','gp'); 
                                                                    $tot_gp = 0;
                                                                    if(!empty($staffgpded)){
                                                                        $tot_gp = $staffgpded['tot_ded_amount'];
                                                                    }
                                                                ?>
                                                                <input class="form-control" name="total_gp_fund" id="total_gp_fund" type="text" value="<?php echo $tot_gp; ?>" readonly />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('total').' '.$this->lang->line('advance'); ?></label>
                                                            <div class="col-sm-6" style="padding:0px 15px 0 0;">
                                                                <?php 
                                                                    $staffadvpayment = $this->payroll_model->getotherpayByAccByStaff($result['id'],44); 
                                                                    $staffadvded = $this->payroll_model->getDedByAccByStaff($result['id'],44,'negative','adv'); 
                                                                    $tot_adv = $staffadvpayment['tot_othpay_amount'] - $staffadvded['tot_ded_amount'];
                                                                ?>
                                                                <input class="form-control" name="total_adv" id="total_adv" type="text" value="<?php echo $tot_adv; ?>" readonly />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('total').' '.$this->lang->line('loan'); ?></label>
                                                            <div class="col-sm-6" style="padding:0px 15px 0 0;">
                                                                <?php 
                                                                    $staffloanpayment = $this->payroll_model->getotherpayByAccByStaff($result['id'],47); 
                                                                    $staffloanded = $this->payroll_model->getDedByAccByStaff($result['id'],47,'negative','loan'); 
                                                                    $tot_loan = $staffloanpayment['tot_othpay_amount'] - $staffloanded['tot_ded_amount'];
                                                                ?>
                                                                <input class="form-control" name="total_loan" id="total_loan" type="text" value="<?php echo $tot_loan; ?>" readonly />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" style="padding: 0 8px 0 0;">
                                                            <label class="col-sm-5 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('earning'); ?></label>
                                                            <div class="col-sm-7" style="padding: 0px;">
                                                                <input class="form-control" name="total_allowance" id="total_allowance"  type="text" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('deduction'); ?></label>
                                                            <div class="col-sm-6 deductiondred" style="padding:0px 15px 0 0;">
                                                                <input class="form-control" name="total_deduction" id="total_deduction" type="text" style="color:#f50000" />
                                                            </div>
                                                        </div><!--./form-group-->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="col-md-6">
                                                            <div class="form-group" style="padding: 0 8px 20px 0;">
                                                                <label class="col-sm-7 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('gross_salary'); ?></label>
                                                                <div class="col-sm-5" style="padding:0px 0px 0 0;">
                                                                    <input class="form-control" name="gross_salary" id="gross_salary" value="0" type="text" />
                                                                </div>
                                                            </div><!--./form-group-->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-6 control-label" style="text-align: left;padding: 7px 0 0 15px;"><?php echo $this->lang->line('net_salary'); ?></label>
                                                                <div class="col-sm-6 net_green" style="padding:0px 15px 0 0;">
                                                                    <input class="form-control greentest"  name="net_salary" id="net_salary"  type="text" />
                                                                    <span class="text-danger" id="err"><?php echo form_error('net_salary'); ?></span>
        
                                                                    <input class="form-control" name="staff_id" value="<?php echo $result["id"]; ?>"  type="hidden" />
        
                                                                    <input class="form-control" name="month" value="<?php echo $month; ?>"  type="hidden" />
                                                                    <input class="form-control" name="year" value="<?php echo $year; ?>"  type="hidden" />
        
                                                                    <input class="form-control" name="status" value="generated"  type="hidden" />
        
                                                                </div>
                                                            </div><!--./form-group-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--./col-md-4-->
                                    <div class="col-md-12 col-sm-12">
                                        <button type="submit" id="contact_submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                    </div><!--./col-md-12-->
                            </form>
                        </div><!--./row-->
                    </div><!--./box-header-->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section>
</div>

<script type="text/javascript">
    function total_present_amounts(){
       var per_day = document.getElementById("per_day").value;
       var present = document.getElementById("present").value;
       var total_amt = present *  per_day;
       $('#present_amount').html('');
       $('#present_amount').val(total_amt.toFixed(0));
    }

    function total_absent_amounts(){
       var per_day = document.getElementById("per_day").value;
       var absent = document.getElementById("absent").value;
       var total_amt = absent * per_day;
       $('#absent_amount').html('');
       $('#absent_amount').val(total_amt);
    }

    function total_leave_amounts(){
       var per_day = document.getElementById("per_day").value;
       var leave = document.getElementById("leave").value;
       var total_amt = leave * per_day;
       $('#leave_amount').html('');
       $('#leave_amount').val(total_amt);
    }

    function total_late_amounts(){
       var per_day = document.getElementById("per_day").value;
       var late = document.getElementById("late").value;
       var total_amt = late * per_day;
       $('#late_amount').html('');
       $('#late_amount').val(total_amt);
    }

    function total_halfday_amounts(){
       var per_day = document.getElementById("per_day").value;
       var halfday = document.getElementById("halfday").value;
       var half_day = per_day/2;
       var total_amt = halfday * half_day;
       $('#halfday_amount').html('');
       $('#halfday_amount').val(total_amt);
    }

    // function total_holiday_amounts(){
    //    var per_day = document.getElementById("per_day").value;
    //    var holiday = document.getElementById("holiday").value;
    //    var total_amt = holiday * per_day;
    //    $('#holiday_amount').html('');
    //    $('#holiday_amount').val(total_amt);
    // }

    // function total_sundays_amounts(){
    //    var per_day = document.getElementById("per_day").value;
    //    var sundays = document.getElementById("sundays").value;
    //    var total_amt = sundays * per_day;
    //    $('#sundays_amount').html('');
    //    $('#sundays_amount').val(total_amt);
    // }
    
    // function total_extra_day_amounts(){
    //    var per_day = document.getElementById("per_day").value;
    //    var extra_day = document.getElementById("extra_day").value;
    //    var total_amt = extra_day * per_day;
    //    $('#extra_day_amount').html('');
    //    $('#extra_day_amount').val(total_amt);
    // }
    
    // function total_over_time_amounts(){
    //    var per_day = document.getElementById("per_day").value;
    //    var over_time = document.getElementById("over_time").value;
    //    var one_hour = per_day/8;
    //    var total_amt = over_time * one_hour;
    //    $('#over_time_amount').html('');
    //    $('#over_time_amount').val(Math.round(total_amt));
    // }
    
    function getcurrenttotalam(val) {
        $('.totampay').html("");
        var feecurrentsum = 0;
        $('.payallowanceam').each(function(){
            feecurrentsum += Number($(this).val());
        });
        var basic_pay = $("#basic_paay").val();
        var pay =  parseInt(basic_pay) +  parseInt(feecurrentsum);
        $('.totampay').append(pay);
    }

    function add_allowance() {
        var basic_pay = $("#basic").val();
        var allwncurrentsum = 0;
        $('.payallowanceam').each(function(){
            allwncurrentsum += Number($(this).val());
        });
        //alert(basic_pay);
        var present_amount = $("#present_amount").val();
        var absent_amount = $("#absent_amount").val();
        //alert(absent_amount);
        var late_amount = $("#late_amount").val();
        //alert(late_amount);
        var halfday_amount = $("#halfday_amount").val();
        //alert(halfday_amount);
        var leave_amount = $("#leave_amount").val();
        //alert(leave_amount);
        var allowance_type = document.getElementsByName('allowance_type[]');
        var allowance_amount = document.getElementsByName('allowance_amount[]');
        //var leave_deduction = $("#leave_deduction").val();
        var tax = 0;//$("#tax").val();
        var total_allowance = 0;
        var deduction_type = document.getElementsByName('deduction_type[]');
        var deduction_amount = document.getElementsByName('deduction_amount[]');
        var total_deduction = 0;
        for (var i = 0; i < allowance_amount.length; i++) {
            var inp = allowance_amount[i];
            if (inp.value == '') {
                var inpvalue = 0;
            } else {
                var inpvalue = inp.value;
            }
            total_allowance += parseInt(inpvalue);
        }

        for (var j = 0; j < deduction_amount.length; j++) {
            var inpd = deduction_amount[j];
            if (inpd.value == '') {
                var inpdvalue = 0;
            } else {
                var inpdvalue = inpd.value;
            }
            total_deduction += parseInt(inpdvalue);
        }
        //total_deduction += parseInt(leave_deduction) ;
        var gross_salary = parseInt(present_amount) + parseInt(allwncurrentsum) +  parseInt(total_allowance) + parseInt(halfday_amount) - parseInt(total_deduction);
        var net_salary = parseInt(present_amount) + parseInt(allwncurrentsum) +  parseInt(total_allowance) + parseInt(halfday_amount) - parseInt(total_deduction);
        //var gross_salary = parseInt(present_amount) + parseInt(allwncurrentsum) +  parseInt(total_allowance) - parseInt(leave_amount) - parseInt(absent_amount) - parseInt(late_amount) - parseInt(halfday_amount) - parseInt(total_deduction);
        //var net_salary = parseInt(present_amount) + parseInt(allwncurrentsum) +  parseInt(total_allowance) - parseInt(leave_amount) - parseInt(absent_amount) - parseInt(late_amount) - parseInt(halfday_amount) - parseInt(total_deduction);
        var grosss_salary = gross_salary.toFixed();
        var nets_salary   = net_salary.toFixed();
        $("#total_allowance").val(total_allowance.toFixed(2));
        $("#total_deduction").val(total_deduction.toFixed(2));
        $("#total_allow").html(total_allowance.toFixed(2));
        $("#total_deduc").html(total_deduction.toFixed(2));
        $("#gross_salary").val(grosss_salary);
        $("#net_salary").val(nets_salary);
    }

    function add_more() {
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'><td><div class='form-group' style='width: 98%;margin-left: -9px;'><select id='allowance_type' name='allowance_type[]' class='form-control pay_type'><option value=''><?php echo $this->lang->line('select'); ?></option><?php foreach ($paytypeList as $type_val) { ?> <option value='<?php echo $type_val['id'] ?>'' <?php if (set_value('fee_type[]') == $type_val['id']) echo 'selected=selected' ?>><?php echo $type_val['type'] ?></option><?php } ?></select></div></td><td><div class='form-group'><input type='text' id='allowance_amount' name='allowance_amount[]' class='form-control allowance_amount' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57' value='0' /></div></td><td><button type='button' onclick='delete_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");
    }

    function add_more_deduction() {
        var table = document.getElementById("tableID2");
        var table_len = (table.rows.length);
        var id = parseInt(table_len);
        var row = table.insertRow(table_len).outerHTML = "<tr id='deduction_row" + id + "'><td><div class='form-group' style='width: 98%;margin-left: -9px;'><select id='deduction_type' name='deduction_type[]' class='form-control pay_type'><option value=''><?php echo $this->lang->line('select'); ?></option><?php foreach ($paydeductionList as $type_val) { ?> <option value='<?php echo $type_val['id'] ?>'' <?php if (set_value('deduction_type[]') == $type_val['id']) echo 'selected=selected' ?>><?php echo $type_val['type']  ?></option><?php } ?></select></div></td><td><div class='form-group'><input type='text' id='deduction_amount' name='deduction_amount[]' class='form-control deduction_amount' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57' value='0' /></div></td><td><button type='button' onclick='delete_deduction_row(" + id + ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";
    }

    function delete_deduction_row(id) {
        var table = document.getElementById("tableID2");
        var rowCount = table.rows.length;
        $("#deduction_row" + id).html("");
    }

    $("#contact_submit").click(function (event) {
        var net = $("#net_salary").val();
        if (net == "") {
            $("#err").html("<?php echo $this->lang->line('net_salary_should_not_be_empty'); ?>");
            $("#net_salary").focus();
            return false;
            event.preventDefault();
        } else {
            $("#err").html("");
        }
    });
</script>
