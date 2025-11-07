<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('supplier', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_supplier'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/supplier/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                                    <input id="phone" name="phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('phone'); ?>" />
                                    <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('email'); ?></label>
                                    <input id="text" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email'); ?>" />
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                    <textarea class="form-control" id="address" name="address" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('address'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('cnic'); ?></label>
                                    <input id="text" name="cnic" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cnic'); ?>" />
                                    <span class="text-danger"><?php echo form_error('cnic'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('ntn_no'); ?></label>
                                    <input id="ntn_no" name="ntn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('ntn_no'); ?>" />
                                    <span class="text-danger"><?php echo form_error('ntn_no'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('company'); ?></label>
                                    <input id="company" name="company" placeholder="" type="text" class="form-control"  value="<?php echo set_value('company'); ?>" />
                                    <span class="text-danger"><?php echo form_error('company'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('contact_person_name'); ?></label>
                                    <input id="contact_person_name" name="contact_person_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact_person_name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('contact_person_name'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('contact_person_phone'); ?></label>
                                    <input id="contact_person_phone" name="contact_person_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact_person_phone'); ?>" />
                                    <span class="text-danger"><?php echo form_error('contact_person_phone'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('contact_person_email'); ?></label>
                                    <input id="contact_person_email" name="contact_person_email" placeholder="" type="email" class="form-control"  value="<?php echo set_value('contact_person_email'); ?>" />
                                    <span class="text-danger"><?php echo form_error('contact_person_email'); ?></span>
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
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('postal_code'); ?></label>
                                    <input id="postal_code" name="postal_code" placeholder="" type="text" class="form-control"  value="<?php echo set_value('postal_code'); ?>" />
                                    <span class="text-danger"><?php echo form_error('postal_code'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?></span>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php }?>
            <div class="col-md-<?php
            if ($this->rbac->hasPrivilege('supplier', 'can_add')) {
                echo "8";
            } else {
                echo "12";
            }
        ?>">
        <!-- general form elements -->
        <div class="box box-primary" id="exphead">
            <div class="box-header ptbnull">
                <h3 class="box-title titlefix"><?php echo $this->lang->line('supplier_list'); ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body  ">
                <div class="mailbox-messages table-responsive overflow-visible">
                    <div class="download_label"><?php echo $this->lang->line('supplier_list'); ?></div>
                    <table class="table table-striped table-bordered table-hover example">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('supplier'); ?></th>
                                <th><?php echo $this->lang->line('contact_person'); ?></th>
                                <th><?php echo $this->lang->line('address'); ?></th>
                                <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($supplierlist)) { ?>
                                <?php
                            } else {
                                $count = 1;
                                foreach ($supplierlist as $supplier) {
                                    ?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <?php if ($this->rbac->hasPrivilege('show_supplier_ledger', 'can_view')) { ?>
                                            <a href="<?php echo base_url(); ?>admin/supplier/addpurchasebill/<?php echo $supplier['id']; ?>" target="_blank">
                                                <?php echo $supplier['name'] ?>
                                            </a>
                                            <?php } ?>
                                            <?php
                                            if ($supplier['phone'] != "") {
                                                ?>
                                                <a href="#" data-toggle="popover" class="detail_popover" >
                                                <i class="fa fa-phone-square"></i> <?php echo $supplier['phone'] ?>
                                                <br>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($supplier['email'] != "") {
                                                ?>
                                                <i class="fa fa-envelope"></i> <?php echo $supplier['email'] ?>

                                                <?php
                                            }
                                            ?>

                                            <div class="fee_detail_popover" style="display: none">
                                                <?php
                                                if ($supplier['description'] == "") {
                                                    ?>
                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <p class="text text-info"><?php echo $supplier['description']; ?></p>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td class="mailbox-name">
                                            <?php
                                            if ($supplier['contact_person_name'] != "") {
                                                ?>
                                                <i class="fa fa-user"></i> <?php echo $supplier['contact_person_name'] ?>
                                                <br>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($supplier['contact_person_phone'] != "") {
                                                ?>
                                                <i class="fa fa-phone-square"></i> <?php echo $supplier['contact_person_phone'] ?>
                                                <br>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if ($supplier['contact_person_email'] != "") {
                                                ?>
                                                <i class="fa fa-envelope"></i> <?php echo $supplier['contact_person_email'] ?>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="mailbox-name">
                                            <?php
                                            if ($supplier['address'] != "") {
                                                ?>
                                                <i class="fa fa-building"></i> <?php echo $supplier['address'] ?>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td class="mailbox-date pull-right no-print">
                                            <?php if ($this->rbac->hasPrivilege('supplier', 'can_edit')) {?>
                                                <a href="<?php echo base_url(); ?>admin/supplier/edit/<?php echo $supplier['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php }if ($this->rbac->hasPrivilege('supplier', 'can_delete')) {?>
                                                <a href="<?php echo base_url(); ?>admin/supplier/delete/<?php echo $supplier['id'] ?>" class="btn btn-danger btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $count++;
                            }
                            ?>
                        </tbody>
                    </table><!-- /.table -->
                </div><!-- /.mail-box-messages -->
            </div><!-- /.box-body -->
        </div>
    </div>
    <!-- right column -->
</div>   <!-- /.row -->
</section><!-- /.content -->
</div>
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
<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
</script>