<div class="content-wrapper">  
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('general_setting'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="schsetting_form" action="<?php //echo site_url('schsettings/ajax_schedit_new'); ?>" class="" method="post" enctype="multipart/form-data">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('sys_name'); ?><small class="req"> *</small> </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="name" name="sys_name" value="<?php echo $result->name; ?>">
                                                <span class="text-danger"><?php echo form_error('name'); ?></span> 
                                                <input type="hidden" name="sys_id" value="<?php echo $result->id; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('sys_code'); ?></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="sys_code" name="sys_dise_code" value="<?php echo $result->dise_code; ?>">
                                                <span class="text-danger"><?php echo form_error('sys_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-2"><?php echo $this->lang->line('address'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="address" name="sys_address" value="<?php echo $result->address; ?>"> <span class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('phone'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="phone" name="sys_phone" value="<?php echo $result->phone; ?>"><span class="text-danger"><?php echo form_error('phone'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('email'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control"  id="email" name="sys_email" value="<?php echo $result->email; ?>">
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('date_time'); ?></h4>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('date_format'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select  id="date_format" name="sys_date_format" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($dateFormatList as $key => $dateformat) {
                                                        ?>
                                                        <option value="<?php echo $key ?>" <?php
                                                        if ($key == $result->date_format) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $dateformat; ?></option>
                                                            <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('date_format'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('timezone'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-8"> 
                                                <select  id="language_id" name="sys_timezone" class="form-control" >
                                                    <option value="">--<?php echo $this->lang->line('select') ?>--</option>
                                                    <?php foreach ($timezoneList as $key => $timezone) {
                                                        ?>
                                                        <option value="<?php echo $key ?>" <?php
                                                        if ($key == $result->timezone) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo $timezone ?></option>
                                                            <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('timezone'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('currency') ?></h4>
                                    </div><!--./col-md-12-->                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('currency_format'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                    <select  id="currency_format" name="currency_format" class="form-control" >
                                                    <option value="">
                                                    <?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($currency_formats as $cur_format_key => $cur_format) {
                                                        ?>
                                                        <option value="<?php echo $cur_format_key ?>" <?php
                                                        if ($cur_format_key == $result->currency_format) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo $cur_format; ?></option>
                                                            <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('currency_format'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 hidden">
                                        <div class="form-group row">
                                            <label class="col-sm-3"><?php echo $this->lang->line('currency_symbol_place'); ?><small class="req"> *</small></label>
                                            <div class="col-sm-9">
                                                <?php foreach ($currencyPlace as $currency_place_k => $currency_place_v) {
                                                    ?>
                                                    <label class="radio-inline hidden">
                                                        <input type="hidden" name="currency_place" value="<?php echo $currency_place_k; ?>" <?php
                                                        if ($result->currency_place == $currency_place_k) {
                                                            echo "checked";
                                                        }
                                                        ?>  ><?php echo $currency_place_v; ?>
                                                    </label>

                                                <?php } ?>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('currency_symbol'); ?></span>
                                        </div>
                                    </div>
                                </div><!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('file_upload_path'); ?></h4>
                                    </div><!--./col-md-12-->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('base_url'); ?><small class="req"> *</small></label>
                                      <div class="col-sm-8">
                                                <input type="text" class="form-control" id="base_url" name="base_url" value="<?php echo $result->base_url; ?>">
                                                <span class="text-danger"><?php echo form_error('base_url'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('file_upload_path'); ?><small class="req"> *</small></label>
                                       <div class="col-sm-8">
                                                <input type="text" class="form-control"  id="folder_path" name="folder_path" value="<?php echo $result->folder_path; ?>">
                                                <span class="text-danger"><?php echo form_error('folder_path'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--./row-->                               
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    $(".edit_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/generalsetting") ?>',
            type: 'POST',
            data: $('#schsetting_form').serialize(),
            dataType: 'json',
            success: function (data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload();
                }
                $this.button('reset');
            }
        });
    });
</script>