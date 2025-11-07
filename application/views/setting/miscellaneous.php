<div class="content-wrapper" style="min-height: 348px;">   
    <section class="content">
        <div class="row">
        
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-gear"></i> <?php echo $this->lang->line('miscellaneous'); ?></h3>
                        <div class="box-tools pull-right">
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="">
                        <form role="form" id="miscellaneous_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                            <div class="box-body">                                                            
                                
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                    ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_miscellaneous" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('save'); ?></button>
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
    $("input[name='event_reminder']",$('#radioBtnDiv')).change(
    function(e)
    {
        var event_reminder = $('.event_reminder:checked').val();
        if(event_reminder == 'enabled'){
            $('#reminder_before_days').removeClass('hide'); 
        }else if(event_reminder == 'disabled'){
            $('#reminder_before_days').addClass('hide');   
        }
    });      
</script >

<script type = "text/javascript">  
    window.onload = function(){  
        var event_reminder = $('.event_reminder:checked').val();
        if(event_reminder == 'enabled'){
            $('#reminder_before_days').removeClass('hide'); 
        }else if(event_reminder == 'disabled'){
            $('#reminder_before_days').addClass('hide');   
        }
    }  
</script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
 
    $(".edit_miscellaneous").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/savemiscellaneous") ?>',
            type: 'POST',
            data: $('#miscellaneous_form').serialize(),
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
                }

                $this.button('reset');
            }
        });
    });
</script>