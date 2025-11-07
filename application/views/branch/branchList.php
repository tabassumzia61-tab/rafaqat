<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if ($this->rbac->hasPrivilege('multi_branch', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_branch'); ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "branchsettings/edit/" . $id;
                        } else {
                            $url = base_url() . "branchsettings/create";
                        }
                        ?>
                        <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="tshadow mb25 bozero"> 
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_information'); ?> </h4>
                                    <div class="around10">
                                        <div class="row">
                                            <div class="form-group">
                                                <?php
                                                if (!empty($name)) { ?> 
                                                    <input autofocus="" id="type"  name="id" placeholder="" type="hidden" class="form-control"  value="<?php echo $id; ?>" />
                                                <?php } ?>
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                                <input class="form-control" id="name" name="name" placeholder="" type="text" value="<?php echo set_value('name',$name); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('code'); ?></label>
                                                <input id="code" name="code" placeholder="" type="text" class="form-control" value="<?php echo set_value('code',$code); ?>" />
                                                <span class="text-danger"><?php echo form_error('code'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                                                <input class="form-control" id="phone" name="phone" placeholder="" type="text" value="<?php echo set_value('phone',$phone); ?>" />
                                                <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                <small class="req"> *</small>
                                                <input class="form-control" id="email" name="email" placeholder="" type="text" value="<?php echo set_value('email',$email); ?>"/>
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('registration_date'); ?></label>
                                                <small class="req"> *</small>
                                                <input id="sch_reg_date" name="regd_date" placeholder="" type="text" class="form-control"  value="<?php if(!empty($regd_date)){ echo set_value('regd_date', date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($regd_date))); }else{ echo set_value('regd_date', date($this->customlib->getSystemDateFormat())); } ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('regd_date'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label><small class="req"> *</small>
                                                <textarea class="form-control" style="resize: none;" rows="2" id="address" name="address" placeholder=""><?php echo set_value('address',$address); ?></textarea>
                                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('country'); ?></label> 
                                                <select  id="country_id" name="country_id" class="form-control"  >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($countrylist as $country) {
                                                        ?>
                                                        <option value="<?php echo $country['id'] ?>"<?php if (set_value('country_id',$country_id) == $country['id']) echo "selected=selected" ?>><?php echo $country['name'] ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('country_id'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('state'); ?></label> 
                                                <select  id="state_id" name="state_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('city'); ?></label> 
                                                <select  id="city_id" name="city_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('city_id'); ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo $this->lang->line('area'); ?></label> 
                                                <select  id="area_id" name="area_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('area_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
                if (($this->rbac->hasPrivilege('multi_branch', 'can_add')) || ($this->rbac->hasPrivilege('multi_branch', 'can_view'))) {
                    echo "8";
                } else {
                    echo "12";
                }
                ?> ">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('branch_list'); ?></h3>                   
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('branch_list'); ?></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('sr'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('code'); ?></th>
                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                            <th><?php echo $this->lang->line('email'); ?></th>
                                            <th><?php echo $this->lang->line('registration_date'); ?></th>
                                            <th><?php echo $this->lang->line('country'); ?></th>
                                            <th><?php echo $this->lang->line('state'); ?></th>
                                            <th><?php echo $this->lang->line('city'); ?></th>
                                            <th><?php echo $this->lang->line('area'); ?></th>
                                            <th><?php echo $this->lang->line('address'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($branchlist)){
                                            $count = 1;
                                            foreach($branchlist as $branch_val){ ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $branch_val['name']; ?></td>
                                                    <td><?php echo $branch_val['code']; ?></td>
                                                    <td><?php echo $branch_val['phone']; ?></td>
                                                    <td><?php echo $branch_val['email']; ?></td>
                                                    <td><?php echo date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($branch_val['regd_date'])); ?></td>
                                                    <td><?php echo $branch_val['country']; ?></td>
                                                    <td><?php echo $branch_val['state']; ?></td>
                                                    <td><?php echo $branch_val['city']; ?></td>
                                                    <td><?php echo $branch_val['area']; ?></td>
                                                    <td><?php echo $branch_val['address']; ?></td>
                                                    <td class="text-right">
                                                        <?php if ($this->rbac->hasPrivilege('multi_branch', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url(); ?>branchsettings/edit/<?php echo $branch_val['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php $count++; } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>
<script>
    ( function ( $ ) {
    'use strict';
    $(document).ready(function () {
        initDatatable('branch-list','branchsettings/getbranchlist', [],[], 100,
            [
                //{ "bSortable": true, "aTargets": [ -2 ] ,'sClass': 'dt-body-right'},
                 //{ "bSortable": false, "aTargets": [ -1 ] ,'sClass': 'dt-body-right'}
            ]);
    });
} ( jQuery ) )
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click','.status_checks',function(){ 
            var status = ($(this).hasClass("checked")) ? '0' : '1'; 
            //alert(status);
            var msg = (status == '0') ? 'Disabled' : 'Enabled'; 
            //var msg = (status == '1') ? 'Deactivate' : 'Activate'; 
            //alert(msg);
            if(confirm("Are you sure to "+ msg)){ 
                var current_element = $(this); 
                var id = $(current_element).attr('data');
                url = "<?php echo base_url().'campussettings/update_status'?>"; 
                $.ajax({
                  type:"POST",
                  url: url, 
                  data: {"id":id,"status":status}, 
                  success: function(data) { 
                    successMsg("Status updated Successfully");
                    window.location.reload(true);
                  } 
                });
            }  
        });
        var date_format = '<?php echo $result = strtr($this->customlib->getSystemDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $('#date,#sch_reg_date').datepicker({
            //  format: "dd-mm-yyyy",
            format: date_format,
            //endDate: '+0d',
            autoclose: true
        });

        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var country_id =   '<?php echo set_value('country_id',$country_id) ?>';
        var state_id = '<?php echo set_value('state_id',$state_id) ?>';
        var city_id = '<?php echo set_value('city_id',$city_id) ?>';
        var area_id = '<?php echo set_value('area_id',$area_id) ?>';
        getStateByCountry(country_id,state_id);
        getCityByCountryState(country_id,state_id, city_id);
        getAreaByCityIDByCountryState(country_id,state_id, city_id,area_id);
        $(document).on('change', '#country_id', function (e) {
            $('#state_id').html("");
            var country_id = $(this).val();
            getStateByCountry(country_id, 0);

        });
        $(document).on('change', '#state_id', function (e) {
            $('#city_id').html("");
            var country_id = $('#country_id').val();
            var state_id = $(this).val();
            getCityByCountryState(country_id,state_id, 0);

        });

        $(document).on('change', '#city_id', function (e) {
            $('#area_id').html("");
            var country_id = $('#country_id').val();
            var state_id = $('#state_id').val();
            var city_id = $(this).val();
            getAreaByCityIDByCountryState(country_id,state_id, city_id,0);
        });
    });

    function getStateByCountry(country_id, state_id) {
        if (country_id != "" ) {
            $('#state_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getstateByCountry" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/area/"+url,
                data: {'country_id': country_id},
                dataType: "json",
                  beforeSend: function(){
                 $('#state_id').addClass('dropdownloading');
                 },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (state_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#state_id').append(div_data);
                },  
               complete: function(){
              $('#state_id').removeClass('dropdownloading');
               }
            });
        }
    }

    function getCityByCountryState(country_id, state_id,city_id) {
        if (country_id != "" && state_id !="") {
            $('#city_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getCityByCountryState" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/area/"+url,
                data: {'country_id': country_id,'state_id': state_id},
                dataType: "json",
                  beforeSend: function(){
                 $('#state_id').addClass('dropdownloading');
                 },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (city_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#city_id').append(div_data);
                },  
               complete: function(){
              $('#state_id').removeClass('dropdownloading');
               }
            });
        }
    }

    function getAreaByCityIDByCountryState(country_id, state_id,city_id,area_id) {
        if (country_id != "" && state_id !="") {
            $('#area_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getAreaByCityByCountryState" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/area/"+url,
                data: {'country_id': country_id,'state_id': state_id,'city_id': city_id},
                dataType: "json",
                  beforeSend: function(){
                 $('#area_id').addClass('dropdownloading');
                 },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (area_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#area_id').append(div_data);
                },  
               complete: function(){
              $('#area_id').removeClass('dropdownloading');
               }
            });
        }
    }
    
</script>