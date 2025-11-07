<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-10">
                <div class="nav-tabs-custom box box-primary theme-shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('payment_methods'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs nav-tabs2">
                        <li class="active"><a href="#tab_13" data-toggle="tab"><?php echo $this->lang->line('jazzcash'); ?></a></li>
                    </ul>
                    <div class="tab-content pb0">
                        <div class="tab-pane active" id="tab_13">
                            <form role="form" id="jazzcash" action="<?php echo site_url('admin/paymentsettings/jazzcash') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <?php
                                            $jazzcash_result = check_in_array('jazzcash', $paymentlist);
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                    <?php echo $this->lang->line('pp_merchantid'); ?>
                                                    <small class="req"> *</small></label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input  name="jazzcash_pp_MerchantID" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_secret_key) ? $jazzcash_result->api_secret_key : ""; ?>" />
                                                        <span class=" text text-danger jazzcash_pp_MerchantID_error"></span>
                                                    </div>  </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5 col-sm-12 col-xs-12" for="exampleInputEmail1">
                                                            <?php echo $this->lang->line('pp_password'); ?>
                                                            <small class="req"> *</small></label>
                                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                                <input  name="jazzcash_pp_Password" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo isset($jazzcash_result->api_password) ? $jazzcash_result->api_password : ""; ?>" />
                                                                <span class=" text text-danger jazzcash_pp_Password_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 text text-center disblock">
                                                        <a href="https://www.jazzcash.com.pk/" target="_blank">
                                                            <h5><?php echo $this->lang->line('payment_gateway_for_pakistan'); ?></h5>
                                                            <img src="<?php echo base_url(); ?>/assets/images/jazzcash.jpg<?php echo img_time(); ?>" width="200"><p>https://www.jazzcash.com.pk/</p></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                                <div class="box-footer">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                                <button type="submit" class="btn btn-primary jazzcash_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="box box-primary">
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <form role="form" action="<?php echo site_url('admin/paymentsettings/setting') ?>" id="payment_gateway" method="POST">
                                        <div class="box-body minheight199">
                                            <div class="form-group"> <!-- Radio group !-->
                                                <?php
                                                $radio_check = check_selected($paymentlist);
                                                ?>

                                                <label class="control-label"><?php echo $this->lang->line('select_payment_gateway'); ?></label>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio"  name="payment_setting" value="jazzcash" <?php
                                                        if ($radio_check == 'jazzcash') {
                                                            echo "checked";
                                                        }
                                                    ?>>
                                                    <?php echo $this->lang->line('jazzcash'); ?>
                                                    </label>
                                                </div>
                                                <span class="text text-danger payment_setting_error"></span>

                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                        <div class="box-footer">
                                            <?php if ($this->rbac->hasPrivilege('payment_methods', 'can_edit')) {?>
                                                <button type="submit" class="btn btn-primary pull-right payment_gateway_save" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                                            <?php }?>
                                        </div>
                                    </form>
                                </div>
                            </div>
        </div>
    </section>
</div>
<?php

function check_selected($array)
{
    $selected = "none";
    if (!empty($array)) {

        foreach ($array as $a => $element) {
            if ($element->is_active == "yes") {
                $selected = $element->payment_type;
            }
        }
    }
    return $selected;
}

function check_in_array($find, $array)
{
    if (!empty($array)) {
        foreach ($array as $element) {
            if ($find == $element->payment_type) {
                return $element;
            }
        }
    }
    $object            = new stdClass();
    $object->id        = "";
    $object->type      = "";
    $object->api_id    = "";
    $object->username  = "";
    $object->url       = "";
    $object->name      = "";
    $object->contact   = "";
    $object->password  = "";
    $object->authkey   = "";
    $object->senderid  = "";
    $object->is_active = "";
    return $object;
}
?>

<script type="text/javascript">
    $("#payment_gateway").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payment_gateway_save");
        $this.button('loading');
var url = $(this).attr('action'); // the script where you handle the form input.

$.ajax({
    type: "POST",
    dataType: 'JSON',
    url: url,
data: $("#payment_gateway").serialize(), // serializes the form's elements.
success: function (data, textStatus, jqXHR)
{
    if (data.st === 1) {
        $.each(data.msg, function (key, value) {
            $('.' + key + "_error").html(value);
        });
    } else {
        successMsg(data.msg);
    }
},
error: function (jqXHR, textStatus, errorThrown)
{
    $(".custom_loader").html("");
//if fails
}, complete: function () {
    $this.button('reset');
}
});

e.preventDefault(); // avoid to execute the actual submit of the form.
});

    $("#paypal").submit(function (e) {
        $("[class$='_error']").html("");

        var $this = $(".paypal_save");
        $this.button('loading');
var url = $(this).attr('action'); // the script where you handle the form input.

$.ajax({
    type: "POST",
    dataType: 'JSON',
    url: url,
data: $("#paypal").serialize(), // serializes the form's elements.
success: function (data, textStatus, jqXHR)
{
    if (data.st === 1) {
        $.each(data.msg, function (key, value) {
            $('.' + key + "_error").html(value);
        });
    } else {
        successMsg(data.msg);
    }
},
error: function (jqXHR, textStatus, errorThrown)
{
    $(".custom_loader").html("");
//if fails
}, complete: function () {
    $this.button('reset');
}
});

e.preventDefault(); // avoid to execute the actual submit of the form.
});

    $("#stripe").submit(function (e) {
        $("[class$='_error']").html("");

        var $this = $(".stripe_save");
        $this.button('loading');
var url = $(this).attr('action'); // the script where you handle the form input.

$.ajax({
    type: "POST",
    dataType: 'JSON',
    url: url,
data: $("#stripe").serialize(), // serializes the form's elements.
success: function (data, textStatus, jqXHR)
{
    if (data.st === 1) {
        $.each(data.msg, function (key, value) {
            $('.' + key + "_error").html(value);
        });
    } else {
        successMsg(data.msg);
    }
},
error: function (jqXHR, textStatus, errorThrown)
{
    $(".custom_loader").html("");
//if fails
}, complete: function () {
    $this.button('reset');
}
});

e.preventDefault(); // avoid to execute the actual submit of the form.
});

    $("#payu").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payu_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payu").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#ccavenue").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".ccavenue_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#ccavenue").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#paystack").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".paystack_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#paystack").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#instamojo").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".instamojo_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#instamojo").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#razorpay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".razorpay_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#razorpay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#paytm").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".paytm_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#paytm").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#midtrans").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".midtrans_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#midtrans").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#pesapal").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".pesapal_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#pesapal").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#ipayafrica").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".ipayafrica_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#ipayafrica").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".ipayafrica_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#flutterwave").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".flutterwave_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#flutterwave").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".flutterwave_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#jazzcash").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".jazzcash_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#jazzcash").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#billplz").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".billplz_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#billplz").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#sslcommerz").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".sslcommerz_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#sslcommerz").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#walkingm").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".walkingm_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#walkingm").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#mollie").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".mollie_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#mollie").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#cashfree").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".cashfree_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#cashfree").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#payfast").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".payfast_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payfast").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#toyyibpay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".toyyibpay_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#toyyibpay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#twocheckout").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".twocheckout_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#twocheckout").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#skrill").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".skrill_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#skrill").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            },
            complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#payhere").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".skrill_save");
        $this.button('loading');
        var url = $(this).attr('action');
        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#payhere").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            },
            complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });

    $("#onepay").submit(function (e) {
        $("[class$='_error']").html("");
        var $this = $(".onepay_save");
        $this.button('loading');
        var url = $(this).attr('action');

        $.ajax({
            type: "POST",
            dataType: 'JSON',
            url: url,
            data: $("#onepay").serialize(),
            success: function (data, textStatus, jqXHR)
            {
                if (data.st === 1) {
                    $.each(data.msg, function (key, value) {
                        $('.' + key + "_error").html(value);
                    });
                } else {
                    successMsg(data.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $(".custom_loader").html("");
            }, complete: function () {
                $this.button('reset');
            }
        });
        e.preventDefault();
    });
</script>