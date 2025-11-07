<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom theme-shadow">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#infoactivity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                <li class=""><a href="#infodocuments" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('documents'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="infoactivity">
                    <div class="tshadow mb25 bozero">
                        <div class="table-responsive around10 pt0">
                            <table class="table table-hover table-striped tmb0">
                                <tbody>
                                    <tr>
                                        <td><?php echo $this->lang->line('name'); ?></td>
                                        <td><?php echo $guarantor['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('phone'); ?></td>
                                        <td><?php echo $guarantor['phone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('cnic'); ?></td>
                                        <td><?php echo $guarantor['cnic']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tshadow mb25 bozero">
                        <h3 class="pagetitleh2"><?php echo $this->lang->line('address_details'); ?></h3>
                        <div class="table-responsive around10 pt0">
                            <table class="table table-hover table-striped tmb0">
                                <tbody>
                                    <tr>
                                        <td class="col-md-4"><?php echo $this->lang->line('current_address'); ?></td>
                                        <td class="col-md-5"><?php echo $guarantor['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $this->lang->line('permanent_address'); ?></td>
                                        <td><?php echo $guarantor['permanent_address']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="infodocuments">
                    <div class="timeline-header no-border">
                        <div class="row">
                            <?php if ((empty($guarantor["cnic_image"])) && (empty($guarantor["resignation_letter"])) && (empty($guarantor["other_document_file"]))) { ?>
                                <div class="col-md-12">
                                    <div class="alert alert-info"><?php echo $this->lang->line("no_record_found"); ?></div>
                                </div>
                            <?php } else { ?>
                                <?php if (!empty($guarantor["cnic_image"])) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('cnic'); ?></h5>
                                            <a href="<?php echo base_url(); ?>admin/supplier/download/<?php echo $guarantor['id'] . "/" . 'cnic_image'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php if($this->rbac->hasPrivilege('guarantor', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>admin/supplier/doc_delete/<?php echo $guarantor['id'] . "/cnic_image"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($guarantor["resignation_letter"])) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('resignation_letter'); ?></h5>
                                            <a href="<?php echo base_url(); ?>admin/supplier/download/<?php echo $guarantor['id'] . "/" . 'resignation_letter'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php if($this->rbac->hasPrivilege('guarantor', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>admin/supplier/doc_delete/<?php echo $guarantor['id'] . "/resignation_letter"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($guarantor["other_document_file"])) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="staffprofile">
                                            <h5><?php echo $this->lang->line('other_documents'); ?></h5>
                                            <a href="<?php echo base_url(); ?>admin/supplier/download/<?php echo $guarantor['id'] . "/" . 'other_document_file'; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <?php if($this->rbac->hasPrivilege('guarantor', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>admin/supplier/doc_delete/<?php echo $guarantor['id'] . "/other_document_file"; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>