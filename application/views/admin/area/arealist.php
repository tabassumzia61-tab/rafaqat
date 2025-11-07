<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php
            if (($this->rbac->hasPrivilege('area', 'can_add')) || ($this->rbac->hasPrivilege('area', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title ?></h3>
                        </div> 
                        <?php
                        $url = "";
                        if (!empty($name)) {
                            $url = base_url() . "admin/area/edit/" . $id;
                        } else {
                            $url = base_url() . "admin/area/create";
                        }
                        ?>
                        <form id="form1" action="<?php echo $url ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <?php 
                                    if (!empty($name)) { ?>
                                        <input type="hidden" name="id" value="<?php echo set_value('id',$id); ?>" />
                                <?php } ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('country'); ?></label><small class="req"> *</small> 
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
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('state'); ?></label><small class="req"> *</small> 
                                    <select  id="state_id" name="state_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('state_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('city'); ?></label><small class="req"> *</small> 
                                    <select  id="city_id" name="city_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('city_id'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('area'); ?></label> <small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name',$name); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo set_value('description',$description); ?></textarea>
                                    <span class="text-danger"></span>
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
            if (($this->rbac->hasPrivilege('area', 'can_add') ) || ($this->rbac->hasPrivilege('area', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('area_list'); ?></h3>
                        <div class="box-tools pull-right"> 
                        </div><!-- /.box-tools -->                 
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('area_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('country'); ?></th>
                                        <th><?php echo $this->lang->line('state'); ?></th>
                                        <th><?php echo $this->lang->line('city'); ?></th>
                                        <th><?php echo $this->lang->line('area'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($resultlist)){
                                        foreach ($resultlist as $val) { ?>
                                            <tr>
                                                <td><?php echo $val['country_name'];?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php 
                                                $statelist = $this->area_model->getstatebycountry($val['country_id']); 
                                                if(!empty($statelist)){
                                                    foreach ($statelist as $state_key => $state_val) { ?>
                                                        <tr>
                                                            <td></td>
                                                            <td><?php echo $state_val['state_name']; ?></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <?php 
                                                            $citylist = $this->area_model->getcitybycountrybystate($val['country_id'],$state_val['state_id']);
                                                            if(!empty($citylist)){
                                                                foreach ($citylist as $city_key => $city_val) { ?>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td><?php echo $city_val['city_name']; ?></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <?php 
                                                                        $arealist = $this->area_model->getareabycountrybystatebycity($val['country_id'],$state_val['state_id'],$city_val['city_id']);
                                                                        if(!empty($arealist)){
                                                                            foreach ($arealist as $area_key => $area_val) { ?>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td>
                                                                                        <a href="#" data-toggle="popover" class="detail_popover"><?php echo $area_val['name']; ?></a>
                                                                                        <div class="show_detail_popover" style="display: none">
                                                                                            <?php
                                                                                            if ($area_val['note'] == "") {
                                                                                                ?>
                                                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                                <?php
                                                                                            } else {
                                                                                                ?>
                                                                                                <p class="text text-info"><?php echo $area_val['note']; ?></p>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="mailbox-date text-right">
                                                                                        <?php if ($this->rbac->hasPrivilege('area', 'can_edit')) { ?>
                                                                                            <a href="<?php echo base_url(); ?>admin/area/edit/<?php echo $area_val['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                                                <i class="fa fa-pencil"></i>
                                                                                            </a>
                                                                                        <?php } ?>
                                                                                        <?php if ($this->rbac->hasPrivilege('area', 'can_delete')) { ?>
                                                                                            <a href="<?php echo base_url(); ?>admin/area/delete/<?php echo $area_val['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                                                <i class="fa fa-remove"></i>
                                                                                            </a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                    <?php } ?> 
                                                <?php } ?> 
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 

        </div> 
    </section>
</div>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.show_detail_popover').html();
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var country_id =   '<?php echo set_value('country_id',$country_id) ?>';
        var state_id = '<?php echo set_value('state_id',$state_id) ?>';
        var city_id = '<?php echo set_value('city_id',$city_id) ?>';
        getStateByCountry(country_id, state_id);
        getCityByStateByCountry(country_id, state_id,city_id);
        $(document).on('change', '#country_id', function (e) {
            $('#state_id').html("");
            var country_id = $(this).val();
            getStateByCountry(country_id, 0);
        });
        $(document).on('change', '#state_id', function (e) {
            $('#city_id').html("");
            var country_id = $('#country_id').val();
            var state_id = $(this).val();
            getCityByStateByCountry(country_id, state_id, 0);
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

    function getCityByStateByCountry(country_id, state_id, city_id) {
        if (country_id != "" ) {
            $('#city_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getCityByCountryState" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/area/"+url,
                data: {'country_id': country_id,'state_id':state_id},
                dataType: "json",
                  beforeSend: function(){
                 $('#city_id').addClass('dropdownloading');
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
              $('#city_id').removeClass('dropdownloading');
               }
            });
        }
    }
</script>