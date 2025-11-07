<style type="text/css">
   @media print{
        body > *{
            display: none;
        }
        body, html, body .printdiv{
            display: block !important;
        }
    }
</style>
<div class="col-md-12">
    <div class="row">
    	<div class="box box-primary">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label><small class="req" style="color:red;"> *</small> 
                        <input id="pay_amount" name="pay_amount" placeholder="" type="text" class="form-control pay_amount"  value=" " />
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                        <small class="req" style="color:red;"> *</small> 
                        <input id="datefor" name="date"  type="text" value="<?php echo set_value('date', date($this->customlib->getSystemDateFormat())); ?>" class="form-control feerevdate"/>
                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                    <textarea class="form-control descriptions" id="descriptions" name="description" placeholder="" rows="3" placeholder="Enter ...">Opening Balance</textarea>
                    <span class="text-danger"></span>
                </div>
                <!--<div class="form-group">-->
                <!--    <label class="checkbox-inline"><input id="sendnotice" type="checkbox" name="sendnotice" checked="checked" value="sendnotice">Send Notification</label>-->
                <!--</div>-->
            </div>
            <div class="box-footer">
                <?php if ($this->rbac->hasPrivilege('add_payment', 'can_add')) { ?>
                <button type="submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>" class="btn btn-primary pull-right submit_fees"><?php echo $this->lang->line('save'); ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var htmlData = "";
    $(document).on('click', '.submit_fees', function (e) {
        $('.submit_fees').prop("disabled", true);
        var amount = $('#pay_amount').val();
        //alert(amount);
        if(amount == ''){
            alert("Enter Amount !");
        }else{
            var $this = $(this);
            $this.button('loading'); 
            var radioValue = 'received';
            //alert(radioValue);
            if (radioValue == 'received') {    
                var description = $("#descriptions").val();
                var dateforfee = $(".feerevdate").val();
                var itemcustomersID = '<?php echo set_value('itemcustomersID',$customers_id) ?>';
                //var sendnotice = $("input[name='sendnotice']:checked").val();
                $.ajax({
                    url: '<?php echo site_url("admin/customers/addopenbalanceByCustomersID") ?>',
                    type: 'post',
                    data: {'paid_types':radioValue,'amount':amount,'itemcustomersID':itemcustomersID,'description':description,'date':dateforfee},
                    success: function (response) {
                        //htmlData = response;
                        successMsg('Record Saved Successfully');
                        //console.log(htmlData);
                        // $(".printdiv").html(htmlData);
                        // setTimeout(function () {
                        //     window.print();
                        // }, 1000);
                        
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText);
                    },
                    complete: function () {
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 1000);
                        $('.submit_fees').prop("disabled", false);
                    }
                });
            }
        }  
    });
</script>