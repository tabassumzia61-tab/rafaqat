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
                    <input id="payable" type="hidden" name="paid_types" value="received" />
                    <input id="invoice_id" type="hidden" name="invoice_id" value="<?php echo set_value('invoice_id',$cashpaylist['id']); ?>" />
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('voucher').' '.$this->lang->line('type'); ?></label> <small class="req">*</small>
                        <select autofocus="" id="voucher_type_id" name="voucher_type_id" class="form-control" >
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <option value="2" <?php if (set_value('voucher_type_id',$cashpaylist['voucher_type_id']) == 2) echo "selected=selected" ?>><?php echo $this->lang->line('cp'); ?></option>
                            <option value="4" <?php if (set_value('voucher_type_id',$cashpaylist['voucher_type_id']) == 4) echo "selected=selected" ?>><?php echo $this->lang->line('bp'); ?></option>
                        </select>
                        <span class="text-danger"><?php echo form_error('voucher_type_id'); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('account').' '.$this->lang->line('mode'); ?></label> <small class="req">*</small>
                        <select autofocus="" id="account_mode_id" name="account_mode_id" class="form-control" >
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                        </select>
                        <span class="text-danger"><?php echo form_error('account_mode_id'); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?></label><small class="req" style="color:red;"> *</small> 
                        <input id="pay_amount" name="pay_amount" placeholder="" type="text" class="form-control pay_amount"  value="<?php echo set_value('pay_amount',$cashpaylist['revd_amount']); ?>" />
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <th><?php echo $this->lang->line('date'); ?></th></label>
                        <small class="req" style="color:red;"> *</small> 
                        <input id="datefor" name="date"  type="text" value="<?php echo set_value('date', date($this->customlib->getSystemDateFormat(), $this->customlib->dateyyyymmddTodateformat($cashpaylist['date']))); ?>" class="form-control feerevdate"/>
                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                    <textarea class="form-control descriptions" id="descriptions" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description', $cashpaylist['note']); ?></textarea>
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
    var voucher_type_id =   '<?php echo set_value('voucher_type_id',$voucher_type_id) ?>';
    var account_mode_id = '<?php echo set_value('account_mode_id',$account_mode_id) ?>';
    getaccountmodebyvoucher(voucher_type_id, account_mode_id);
    $(document).on('change', '#voucher_type_id', function (e) {
        $('#account_mode_id').html("");
        var voucher_type_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "admin/accounts/getaccountmodebyvoucher",
            data: {'voucher_type_id': voucher_type_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.id + " >" + obj.type + "</option>";
                });
                $('#account_mode_id').append(div_data);
            }
        });
    });
    
    function getaccountmodebyvoucher(voucher_type_id, account_mode_id) {
        if (voucher_type_id != "" ) {
            $('#account_mode_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "admin/accounts/getaccountmodebyvoucher",
                data: {'voucher_type_id': voucher_type_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (account_mode_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.type + "</option>";
                    });
                    $('#account_mode_id').append(div_data);
                }
            });
        }
    }
</script>
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
            var radioValue = $("input[name='paid_types']").val();
            //alert(radioValue);
            if (radioValue == 'received') {    
                var payment_mode = $("#account_mode_id").val();
                var payment_type = $("#voucher_type_id").val();
                var invoice_id = $("input[name='invoice_id']").val();
                var description = $("#descriptions").val();
                var paid_id_re = 1;
                var dateforfee = $(".feerevdate").val();
                var itemcustomersID = '<?php echo set_value('itemcustomersID',$customers_id) ?>';
                //var sendnotice = $("input[name='sendnotice']:checked").val();
                $.ajax({
                    url: '<?php echo site_url("admin/customers/updatecashBySupplier") ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {'invoice_id':invoice_id,'paid_types':radioValue,'paid_id':paid_id_re,'paid_amount':amount,'itemcustomersID':itemcustomersID,'payment_mode':payment_mode,'payment_type':payment_type,'description':description,'date':dateforfee},
                    success: function (response) {
                        //htmlData = response;
                        successMsg('Record Saved Successfully');
                        // //console.log(htmlData);
                        // $(".printdiv").html(htmlData);
                        // setTimeout(function () {
                        //     window.print();
                        // }, 1000);
                        
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseText);
                    },
                    complete: function () {
                        window.location.reload(true);
                    }
                });
            }
        }  
    });
</script>