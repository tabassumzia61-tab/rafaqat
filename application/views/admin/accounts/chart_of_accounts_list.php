<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">   
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active table-responsive no-padding" id="tab_1">
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('account_head'); ?></th>
                                        <th><?php echo $this->lang->line('account_type'); ?></th>
                                        <th><?php echo $this->lang->line('account_name'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $acclist = $this->accounts_model->getAccountsHeadByTypesIDCoa($brc_id); 
                                        if(!empty($acclist)){
                                            foreach($acclist as $val){ ?>
                                                <tr>
                                                    <td><?php echo $val['account_head']; ?></td>
                                                    <td><?php echo $val['account_type']; ?></td>
                                                    <td style="text-align: left !important;"><?php echo $val['account_code'].'. '.$val['account_name']; ?></td>
                                                </tr>        
                                            <?php } ?>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <div class="panel-group" id="accordion1">
                                <?php foreach ($accountstypelist as $acc_type_key => $acc_type_val) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion1" href="#collapse<?php echo $acc_type_key; ?>"> <?php echo $acc_type_val['code'].'. '.$acc_type_val['name']; ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?php echo $acc_type_key; ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="panel-group" id="accordion11">
                                                <?php 
                                                    $newaccounts = $this->accounts_model->getnewaccountsByID($acc_type_val['id']);
                                                    foreach ($newaccounts as $new_key => $new_val) { ?>
                                                        <div class="panel">
                                                            <a data-toggle="collapse" data-parent="#accordion11" href="#collapse<?php echo $acc_type_key; ?><?php echo $new_key; ?>"> <?php echo $new_val->code.'. '.$new_val->name; ?> &raquo;
                                                            </a>
                                                            <div id="collapse<?php echo $acc_type_key; ?><?php echo $new_key; ?>" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <div class="panel-group" id="accordion111">
                                                                        <?php 
                                                                            $accountshead = $this->accounts_model->getaccountsheadByID($new_val->id,$brc_id); 
                                                                            foreach ($accountshead as $head_key => $head_val) { ?>
                                                                                <?php echo $head_val['code'].'. '.$head_val['name']; ?><br/>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </section>
</div>
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
                return $(this).closest('td').find('.show_detail_popover').html();
            }
        });
    });
</script>
<script type="text/javascript">
    function getBranchByID(val){
        //alert();
        var url ='<?php echo site_url('admin/qms/account/accounts/index/'); ?>'+val;          
        if(url){
            window.location.href = url; 
        }
    }
    function getBranchByIDByUrl(url){          
        if(url){
            window.location.href = url; 
        }
    } 
</script>