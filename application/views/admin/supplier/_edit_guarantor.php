<input type="hidden" name="id" value="<?php echo $guarantorlist["id"] ?>" id="guarantor_id">
<input type="hidden" name="supplier_id" value="<?php echo $guarantorlist["supplier_id"] ?>" id="supplier_id">
<div class="row" style="margin-top: 10px;">
    <div class="col-md-4">
        <div class="form-group">
            <label for=""><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
            <input id="gura_name" name="gura_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('gura_name',$guarantorlist["name"]); ?>" />
            <span class="text-danger"><?php echo form_error('gura_name'); ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for=""><?php echo $this->lang->line('cnic'); ?></label><small class="req"> *</small>
            <input id="gura_cnic" name="gura_cnic" placeholder="" type="text" class="form-control" value="<?php echo set_value('gura_cnic',$guarantorlist["cnic"]); ?>" />
            <span class="text-danger"><?php echo form_error('gura_cnic'); ?></span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for=""><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
            <input id="gura_phone" name="gura_phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('gura_phone',$guarantorlist["phone"]); ?>" />
            <span class="text-danger"><?php echo form_error('gura_phone'); ?></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
            <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
            </div>
            <span class="text-danger"><?php echo form_error('file'); ?></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputFile"><?php echo $this->lang->line('current'); ?> <?php echo $this->lang->line('address'); ?></label>
            <div><textarea name="address" class="form-control"><?php echo set_value('address',$guarantorlist["address"]); ?></textarea></div>
            <span class="text-danger"></span>
        </div>
    </div>    
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleInputFile"><?php echo $this->lang->line('permanent_address'); ?></label>
            <div><textarea name="permanent_address" class="form-control"><?php echo set_value('permanent_address',$guarantorlist["permanent_address"]); ?></textarea></div>
            <span class="text-danger"></span>
        </div>
    </div>
</div>
<div class="row">
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
                    <td><?php echo $this->lang->line('cnic'); ?></td>
                    <td>
                        <input class="filestyle form-control" type='file' name='first_doc' id="doc1" >
                        <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td><?php echo $this->lang->line('resignation_letter'); ?></td>
                    <td>
                        <input class="filestyle form-control" type='file' name='third_doc' id="doc3" >
                        <span class="text-danger"><?php echo form_error('third_doc'); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table">
            <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th><?php echo $this->lang->line('title'); ?></th>
                    <th><?php echo $this->lang->line('documents'); ?></th>
                </tr>
                <tr>
                    <td>3.</td>
                    <td><?php echo $this->lang->line('other_documents'); ?><input type="hidden" name='fourth_title' class="form-control" placeholder="Other Documents"></td>
                    <td>
                        <input class="filestyle form-control" type='file' name='fourth_doc' id="doc4" >
                        <span class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $('.filestyle').dropify();
</script>