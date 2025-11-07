<style type="text/css">
    .radio {
        padding-left: 20px; }
    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px; }
    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid #cccccc;
        border-radius: 50%;
        background-color: #fff;
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out; }
    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33); }
    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1; }
    .radio input[type="radio"]:focus + label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px; }
    .radio input[type="radio"]:checked + label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1); }
    .radio input[type="radio"]:disabled + label {
        opacity: 0.65; }
    .radio input[type="radio"]:disabled + label::before {
        cursor: not-allowed; }
    .radio.radio-inline {
        margin-top: 0; }
    .radio-primary input[type="radio"] + label::after {
        background-color: #337ab7; }
    .radio-primary input[type="radio"]:checked + label::before {
        border-color: #337ab7; }
    .radio-primary input[type="radio"]:checked + label::after {
        background-color: #337ab7; }
    .radio-danger input[type="radio"] + label::after {
        background-color: #d9534f; }
    .radio-danger input[type="radio"]:checked + label::before {
        border-color: #d9534f; }
    .radio-danger input[type="radio"]:checked + label::after {
        background-color: #d9534f; }
    .radio-info input[type="radio"] + label::after {
        background-color: #5bc0de; }
    .radio-info input[type="radio"]:checked + label::before {
        border-color: #5bc0de; }
    .radio-info input[type="radio"]:checked + label::after {
        background-color: #5bc0de;}
    @media (max-width:767px){
        .radio.radio-inline {display: inherit;}
    }    
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php
                        if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        }
                        ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staffattendance/staffattendancebrachsubmit') ?>" method="post" class="attend_form">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">
                                               <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label>
                                                    <select autofocus="" id="brach_id" name="brach_id" class="form-control all_brach_id" >
                                                        <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($branchlist as $key => $brach) {
                                                            ?>
                                                            <option value="<?php echo $brach["id"] ?>"   <?php
                                                            if ($brach["id"] == $brach_id) {
                                                                echo "selected =selected";
                                                            }
                                                        ?>><?php print_r($brach["name"])?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('brach_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" style="padding-right: 0px;">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">
                                                        <?php echo $this->lang->line('attendance'); ?>
                                                        <?php echo $this->lang->line('date'); ?>
                                                    </label>
                                                    <input  name="date" placeholder="" type="text" id="date"  class="form-control date_att"  value="<?php echo set_value('date', date($this->customlib->getSystemDateFormat())); ?>" readonly="readonly"/>
                                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="button" data-toggle="modal" data-target="#conformationModal" class="btn btn-primary btn-sm pull-right checkbox-toggle"><?php echo $this->lang->line('save'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staffattendance/staffattendancebrach') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label>
                                                <select autofocus="" id="brach_id" name="brach_id" class="form-control" >
                                                    <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($branchlist as $key => $brach) {
                                                        ?>
                                                        <option value="<?php echo $brach["id"] ?>"   <?php
                                                        if ($brach["id"] == $brach_id) {
                                                            echo "selected =selected";
                                                        }
                                                    ?>><?php print_r($brach["name"])?></option>
                                                    <?php
                                                }
                                                ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('brach_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">
                                                    <?php echo $this->lang->line('attendance'); ?>
                                                    <?php echo $this->lang->line('date'); ?>
                                                </label>
                                                <input  name="date" placeholder="" type="text" id="date_att" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getSystemDateFormat())); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="inpuFname"><?php echo $this->lang->line('search_by_keyword'); ?></label><small class="req"> *</small>
                                                <div class="input-group">
                                                    <div class="input-group-btn bs-dropdown-to-select-group">
                                                        <button type="button" class="btn btn-default btn-searchsm dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                                            <span data-bind="bs-drp-sel-label">
                                                                <?php 
                                                                    if (set_value('selected_value_staff',$selected_value_staff) == 'staff_id'){
                                                                        echo $this->lang->line('staff_id');
                                                                        $inputvalstaff = 'staff_id';
                                                                    }else{
                                                                        echo $this->lang->line('staff_id');
                                                                        $inputvalstaff = 'staff_id';
                                                                    }   
                                                                ?>
                                                            </span>
                                                            <input data-value="<?php echo $inputvalstaff;  ?>" type="hidden" name="selected_value_staff" data-bind="bs-drp-sel-value" value="<?php echo $inputvalstaff;  ?>">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu" style="">
                                                            <li data-value="staff_id"><a href="#" ><?php echo $this->lang->line('staff_id'); ?></a></li>
                                                        </ul>
                                                    </div>
                                                    <input type="text" id="search_text" value="<?php echo set_value('text_staff',$search_text_staff); ?>" data-record="" data-email="" data-mobileno="" class="form-control" autocomplete="off" name="text_staff" placeholder="<?php echo $this->lang->line('search_by_staff'); ?>" id="search-query">
                                                    <div id="suggesstion-box"></div>
                                                    <span class="input-group-btn">
                                                        <button name="search" value="search_filter" class="btn btn-primary btn-searchsm add-btn" type="submit"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                    </span>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('text_staff'); ?></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
                <?php
                if (isset($resultlist)) {
                    ?>
                    <div class="box box-primary">  
                    
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('list'); ?></h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            if (!empty($resultlist)) {
                                $checked = "";
                                if (!isset($msg)) {
                                    if ($resultlist[0]['staff_attendance_type_id'] != "") {
                                        if ($resultlist[0]['staff_attendance_type_id'] != 6) {
                                            ?>
                                            <div class="alert alert-success"><?php echo $this->lang->line('attendance_already_submitted_you_can_edit_record'); ?></div>
                                            <?php
                                        } else {
                                            $checked = "checked='checked'";
                                            ?>
                                            <div class="alert alert-warning"><?php echo $this->lang->line('attendance_already_submitted_as_holiday'); ?>. <?php echo $this->lang->line('you_can_edit_record'); ?></div>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="alert alert-success"><?php echo $this->lang->line('attendance_saved_successfully'); ?></div>
                                    <?php
                                }
                                ?>
                                <form action="<?php echo site_url('admin/staffattendance/staffAttendenceByAttID') ?>" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="mailbox-controls">
                                        <span class="button-checkbox">
                                            <button type="button" class="btn btn-sm btn-primary" data-color="primary"><?php echo $this->lang->line('mark_as_holiday'); ?></button>
                                            <input type="checkbox" id="checkbox1" class="hidden" name="holiday" value="checked" <?php echo $checked; ?>/>
                                        </span>
                                        <div class="pull-right">
                                            <?php if ($this->rbac->hasPrivilege('staff_attendance', 'can_add')) { ?>
                                                <button type="submit" name="search" value="saveattendence" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                                    <input type="hidden" name="brach_id" value="<?php echo $brach_id; ?>">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped example">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th class=""><?php echo $this->lang->line('attendance'); ?></th>
                                                    <th class=""><?php echo $this->lang->line('note'); ?></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $row_count = 1;
                                                foreach ($resultlist as $key => $value) {

                                                    $attendendence_id = $value["id"];
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="staff_id_arr[]" value="<?php echo $value['staff_id']; ?>">
                                                            <input  type="hidden" value="<?php echo $attendendence_id ?>"  name="attendendence_id<?php echo $value["staff_id"]; ?>">
                                                            <?php echo $row_count; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['employee_id']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['name'] . " " . $value['surname']; ?>
                                                        </td>
                                                        <td><?php echo $value['user_type']; ?></td>

                                                        <td>
                                                            <?php
                                                            $c = 1;
                                                            $count = 0;
                                                            foreach ($attendencetypeslist as $key => $type) {

                                                                if ($type['key_value'] != "H") {
                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                    if ($value["date"] != "xxx") {
                                                                        ?>
                                                                        <div class="radio radio-info radio-inline">
                                                                            <input <?php if ($value['staff_attendance_type_id'] == $type['id']) echo "checked"; ?>  type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>">
                                                                            <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                <?php echo $type['type']; ?> 
                                                                            </label>
                                                                        </div>
                                                                        <?php
                                                                    }else {
                                                                        ?>

                                                                        <div class="radio radio-info radio-inline">
                                                                            <input <?php if (($c == 1) && ($resultlist[0]['staff_attendance_type_id'] != 5)) echo "checked"; ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" >
                                                                            <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>"> 
                                                                                <?php echo $type['type']; ?> 
                                                                            </label>

                                                                        </div>

                                                                        <?php
                                                                    }
                                                                    $c++;
                                                                    $count++;
                                                                }
                                                            }
                                                            ?>

                                                        </td>
                                                        <?php if ($value["date"] == 'xxx') { ?> 
                                                            <td><input type="text" name="remark<?php echo $value["staff_id"] ?>" ></td>
                                                        <?php } else { ?>

                                                            <td><input type="text" name="remark<?php echo $value["staff_id"] ?>" value="<?php echo $value["remark"]; ?>" ></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                    $row_count++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>
<!--==confirm modal===-->
<div class="modal" id="conformationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Attendence Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you, want to Attendence confirm?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit_att_all"><?php echo $this->lang->line('save'); ?></button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--===-->
<script>
    $(document).on('click', '.dropdown-menu li', function () {
        $("#suggesstion-box ul").empty();
        $("#suggesstion-box").hide();
    });
    $(document).ready(function (e) {
        $(document).on('click', '.bs-dropdown-to-select-group .dropdown-menu li', function (event) {
            var $target = $(event.currentTarget);
            $target.closest('.bs-dropdown-to-select-group')
            .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
            .end()
            .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
            .find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
            return false;
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#date').datepicker({
            format: date_format,
            startDate: "-5d",
            endDate: "+0d",
            autoclose: true
        });
        $('#date_att').datepicker({
            format: date_format,
            startDate: "-5d",
            endDate: "+0d",
            autoclose: true
        });
        $('#conformationModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $('#conformationModal').on('click', '.submit_att_all', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var datastring = $(".attend_form").serialize();
            var brach_id = $('.all_brach_id').val();
            var att_date = $('.date_att').val();
            $.ajax({
                type: "POST",
                url: '<?php echo site_url("admin/staffattendance/staffattendancebrachsubmit") ?>',
                data: {'att_date': att_date,'brach_id':brach_id},
                dataType: 'json',
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                    $('.sessionmodal_body').html(data);
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);

                    } else {
                        if (data.statuss == "fail") {
                            errorMsg(data.message);
                            location.reload(true);
                        }else{
                            successMsg(data.message);
                            location.reload(true);
                        }

                    }
                    $('#conformationModal').modal('hide');
                },
                error: function (xhr) { // if error occured
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function () {
                    $modalDiv.removeClass('modal_loading');
                },
            });

        });

    });
</script>
            <script type="text/javascript">
                $(document).ready(function () {
                    $.extend($.fn.dataTable.defaults, {
                        searching: false,
                        ordering: true,
                        paging: false,
                        retrieve: true,
                        destroy: true,
                        info: false
                    });
                    var table = $('.example').DataTable();
                    table.buttons('.export').remove();
                });
            </script>       
            <script type="text/javascript">


                window.onload = function xy() {


                    var ch = '<?php
                if (!empty($resultlist)) {
                    echo $resultlist[0]['staff_attendance_type_id'];
                }
                ?>';

                    if (ch == 5) {

                        $("input[type=radio]").attr('disabled', true);

                    } else {

                        $("input[type=radio]").attr('disabled', false);
                    }

                };
                $(document).ready(function () {


                 


                    $('#checkbox1').change(function () {

                        if (this.checked) {
                            var returnVal = confirm("Are you sure?");
                            $(this).prop("checked", returnVal);

                            $("input[type=radio]").attr('disabled', true);


                        } else {
                            $("input[type=radio]").attr('disabled', false);
                            $("input[type=radio][value='1']").attr("checked", "checked");

                        }

                    });
                });


            </script>
            <script type="text/javascript">
                $(function () {
                    $('.button-checkbox').each(function () {
                        var $widget = $(this),
                                $button = $widget.find('button'),
                                $checkbox = $widget.find('input:checkbox'),
                                color = $button.data('color'),
                                settings = {
                                    on: {
                                        icon: 'glyphicon glyphicon-check'
                                    },
                                    off: {
                                        icon: 'glyphicon glyphicon-unchecked'
                                    }
                                };
                        $button.on('click', function () {
                            $checkbox.prop('checked', !$checkbox.is(':checked'));
                            $checkbox.triggerHandler('change');
                            updateDisplay();
                        });
                        $checkbox.on('change', function () {
                            updateDisplay();
                        });

                        function updateDisplay() {
                            var isChecked = $checkbox.is(':checked');
                            $button.data('state', (isChecked) ? "on" : "off");
                            $button.find('.state-icon')
                                    .removeClass()
                                    .addClass('state-icon ' + settings[$button.data('state')].icon);
                            if (isChecked) {
                                $button
                                        .removeClass('btn-success')
                                        .addClass('btn-' + color + ' active');
                            } else {
                                $button
                                        .removeClass('btn-' + color + ' active')
                                        .addClass('btn-primary');
                            }
                        }

                        function init() {
                            updateDisplay();
                            if ($button.find('.state-icon').length == 0) {
                                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                            }
                        }
                        init();
                    });
                });
            </script>     
<script type="text/javascript">
    function get_data_by_campuss(val){
        //alert();
        var url ='<?php echo site_url('admin/staffattendance/index/'); ?>'+val;          
        if(url){
            window.location.href = url; 
        }
    } 
</script>   