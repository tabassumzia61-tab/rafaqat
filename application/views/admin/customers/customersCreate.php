<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-object-group"></i> <?php //echo $this->lang->line('inventory'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('customers', 'can_add')) { ?>
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('add_customers'); ?></h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <form  action="<?php echo site_url('admin/customers/create/'.$brc_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('contact_person'); ?> </h4>
                                    <div class="around10">
                                        <div class="row">
                                            <?php  
                                            if ($this->rbac->hasPrivilege('customers', 'can_branch')) { ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('branch'); ?></label><small class="req"> *</small> 
                                                    <select  id="brc_id" name="brc_id" class="form-control selectval brc_id">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($branchlist as $brc_val) {
                                                            $brc_id_log = $this->customlib->getBranchID();
                                                            if ($brc_id_log == 1) { ?>
                                                                <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                            <?php }else{
                                                                if ($brc_id_log == $brc_val['id']) { ?>
                                                                    <option value="<?php echo $brc_val['id'] ?>"<?php if (set_value('brc_id',$brc_id) == $brc_val['id']) echo "selected=selected" ?>><?php echo $brc_val['name'] ?></option>
                                                                <?php  } ?>
                                                            <?php
                                                            } 
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('brc_id'); ?></span>
                                                </div>
                                            </div>
                                            <?php } ?>         
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('customers_id'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="customers_id" name="customers_id"  placeholder="" type="text" class="form-control" style="background-color:#eee3e3;" readonly  value="<?php echo set_value('customers_id',$custm_no) ?>" />
                                                    <span class="text-danger"><?php echo form_error('customers_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                                    <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('last_name'); ?></label>
                                                    <input autofocus="" id="surname" name="surname" placeholder="" type="text" class="form-control"  value="<?php echo set_value('surname'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                                </div>
                                            </div> -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label> <small class="req"> *</small> 
                                                    <select class="form-control" name="gender">
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($genderList as $key => $value) {
                                                            ?>
                                                            <option value="<?php echo $key; ?>" <?php echo set_select('gender', $key, set_value('gender')); ?>><?php echo $value; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('phone'); ?></label>
                                                    <input id="phone" name="phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('phone'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                    <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('cnic'); ?></label>
                                                    <input id="text" name="cnic" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cnic'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('cnic'); ?></span>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('company_information'); ?> </h4>
                                    <div class="around10">   
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('company'); ?></label>
                                                    <input id="company" name="company" placeholder="" type="text" class="form-control"  value="<?php echo set_value('company'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('company'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('email'); ?></label>
                                                    <input id="text" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label for="exampleInputEmail1"> <?php echo $this->lang->line('contact_person_name'); ?></label>-->
                                            <!--        <input id="contact_person_name" name="contact_person_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact_person_name'); ?>" />-->
                                            <!--        <span class="text-danger"><?php echo form_error('contact_person_name'); ?></span>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!--<div class="col-md-3">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label for="exampleInputEmail1"><?php echo $this->lang->line('contact_person_phone'); ?></label>-->
                                            <!--        <input id="contact_person_phone" name="contact_person_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('contact_person_phone'); ?>" />-->
                                            <!--        <span class="text-danger"><?php echo form_error('contact_person_phone'); ?></span>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!--<div class="col-md-3">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label for="exampleInputEmail1"> <?php echo $this->lang->line('postal_code'); ?></label>-->
                                            <!--        <input id="postal_code" name="postal_code" placeholder="" type="text" class="form-control"  value="<?php echo set_value('postal_code'); ?>" />-->
                                            <!--        <span class="text-danger"><?php echo form_error('postal_code'); ?></span>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('city'); ?></label> 
                                                    <select  id="city_id" name="city_id" class="form-control" >
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                        <?php
                                                        foreach ($citylist as $city) {
                                                            ?>
                                                            <option value="<?php echo $city['id'] ?>"<?php if (set_value('city_id',$city_id) == $city['id']) echo "selected=selected" ?>><?php echo $city['name'] ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('city_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('area'); ?></label> 
                                                    <select  id="area_id" name="area_id" class="form-control" >
                                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    </select>
                                                    <span class="text-danger"><?php echo form_error('area_id'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--<div class="col-md-3">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label for="exampleInputEmail1"> <?php echo $this->lang->line('contact_person_email'); ?></label>-->
                                            <!--        <input id="contact_person_email" name="contact_person_email" placeholder="" type="email" class="form-control"  value="<?php echo set_value('contact_person_email'); ?>" />-->
                                            <!--        <span class="text-danger"><?php echo form_error('contact_person_email'); ?></span>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            
                                            
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('address'); ?></label>
                                                    <div><textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea></div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            
                                            <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('permanent_address'); ?></label>
                                                    <div><textarea name="permanent_address" class="form-control"><?php echo set_value('permanent_address'); ?></textarea></div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div> --> 
                                             
                                        </div>
                                        <div class="row">    
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('note'); ?></label>
                                                    <div><textarea name="note" class="form-control"><?php echo set_value('note'); ?></textarea></div>
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="tshadow mb25 bozero">
                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('accounting_information'); ?> </h4>
                                    <div class="around10">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('ntn'); ?></label>
                                                    <input id="ntn_no" name="ntn_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('ntn_no'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('ntn_no'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"> <?php echo $this->lang->line('gstn'); ?></label>
                                                    <input id="gstn" name="gstn" placeholder="" type="text" class="form-control"  value="<?php echo set_value('gstn'); ?>" />
                                                    <span class="text-danger"><?php echo form_error('gstn'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="margin-bottom:15px;">
                                                <div class="form-group">
                                                    <label class="col-sm-12" for="payment_limit"><?php echo $this->lang->line('tax'); ?></label>
                                                    <div class="col-sm-8" id="radioBtnDiv">
                                                        <label class="radio-inline">
                                                            <input type="radio" class="is_tax_status" name="is_tax_status" value="0" checked="checked"><?php echo $this->lang->line('disabled'); ?>
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" class="is_tax_status" name="is_tax_status" value="1"><?php echo $this->lang->line('enabled'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 hide" id="is_tax_status_percentage">
                                                <div class="form-group">
                                                    <label for="exampleInputFile"><?php echo $this->lang->line('percentage').' %'; ?></label>
                                                    <input type="text" name="tax_percentage" id="tax_percentage" class="form-control" value="<?php echo set_value('tax_percentage'); ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57">
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="credit_limit"><?php echo $this->lang->line('credit_limit'); ?></label><small class="req"> *</small>
                                                    <input id="credit_limit" name="credit_limit" placeholder="" type="text" class="form-control"  value="<?php echo set_value('credit_limit'); ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>
                                                    <span class="text-danger"><?php echo form_error('credit_limit'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='upload_documents_hide_show'>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="tshadow bozero">
                                                <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>

                                                <div class="row around10">
                                                    <div class="col-md-6">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="width: 10px">#</th>
                                                                    <th><?php echo $this->lang->line('title'); ?></th>
                                                                    <th><?php echo $this->lang->line('documents'); ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td><?php echo $this->lang->line('documents'); ?></td>
                                                                    <td>
                                                                        <input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
                                                                        <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
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
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div><!--/.col (right) -->
                <!-- left column -->
            <?php } ?>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
<script type="text/javascript">
     $('input[type=radio][name=is_tax_status]').change(function() {
        if (this.value == '1') {
            $('#is_tax_status_percentage').removeClass('hide'); 
        }
        else if (this.value == '0') {
             $('#is_tax_status_percentage').addClass('hide');   
        }
    }); 
     
    window.onload = function(){  
        var is_tax_status = '<?php echo 0; ?>';  
        if(is_tax_status == '1'){
            $('#is_tax_status_percentage').removeClass('hide'); 
        }else if(is_tax_status == '0'){
            $('#is_tax_status_percentage').addClass('hide');   
        }
    }  
</script>  
<script type="text/javascript">
    $(document).ready(function () {
        var city_id = '<?php echo set_value('city_id',$city_id) ?>';
        var area_id = '<?php echo set_value('area_id',$area_id) ?>';
        getAreaByCityID(city_id,area_id);
        $(document).on('change', '#city_id', function (e) {
            $('#area_id').html("");
            var city_id = $(this).val();
            getAreaByCityID(city_id,0);
        });
    });

    function getAreaByCityID(city_id,area_id) {
        if (city_id != "") {
            $('#area_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php  echo "getAreaByCity" ?>";
            $.ajax({
                type: "GET",
                url: base_url + "admin/area/"+url,
                data: {'city_id': city_id},
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