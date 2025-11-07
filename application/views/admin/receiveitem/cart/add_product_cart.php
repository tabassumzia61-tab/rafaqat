<?php
// echo '<pre>';
// print_r($this->cart->contents());
// echo '</pre>';
?>
<div class="table">
    <table class="table" id="tireFields">
        <thead>

        <tr style="background-color: #ECEEF1">
            <th style="width: 15px">#</th>
            <th class="col-sm-5"><?php  echo strtoupper( $this->lang->line('products')) ?></th>
            <th class=""><?php  echo strtoupper( $this->lang->line('box')) ?></th>
            <th class=""><?php  echo strtoupper($this->lang->line('qty')) ?></th>
            <th class=""><?php  echo strtoupper($this->lang->line('t_weight')) ?></th>
            <th class=""><?php  echo strtoupper($this->lang->line('b_weight')) ?></th>
            <th class=""><?php  echo strtoupper($this->lang->line('n_weight')) ?></th>
            <th class=""> </th>
        </tr>
        </thead>
        <tbody>

        <?php $i=1; if(!empty($this->cart->contents())) { foreach ($this->cart->contents() as $cart) { ?>
            <tr>
                <?php 
                    if (!empty($this->session->userdata('issueitemid'))) {
                        $getproductdetail = $this->issueitem_model->getItemsByIssueItemIDByProID($this->session->userdata('issueitemid'),$cart["id"]);
                        if (!empty($getproductdetail)) { ?>
                            <input type="hidden" name="carts_id[]" value="<?php echo set_value('carts_id',$cart["id"]); ?>" />
                            <input type="hidden" name="pdetil_id[]" value="<?php echo set_value('pdetil_id',$getproductdetail['id']); ?>" />
                        <?php }else{ ?>
                            <input type="hidden" name="carts_id[]" value="<?php echo set_value('carts_id',$cart["id"]); ?>" />
                            <input type="hidden" name="pdetil_id[]" value="<?php echo set_value('pdetil_id',$cart['id']); ?>" />
                        <?php } ?>
                    <?php } ?>
                <td>
                    <div class="form-group form-group-bottom">
                        <?php echo $i ?>
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom p_div">
                        <select class="form-control js-example-basic-single itemsel" style="width: 100%" onchange="pur_product_id(this)" id="<?php echo $cart['rowid']?>">
                            <option value=""><?php echo  $this->lang->line('select') ?></option>
                            <?php if(!empty($itemcatlist)){  ?>
                                <?php 
                                foreach($itemcatlist as $itemcat_val){?>
                                    <option value="<?php echo $itemcat_val['id'];  ?>" <?php echo $cart['id'] === $itemcat_val['id'] ? 'selected':''  ?>><?php echo $itemcat_val['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom ">
                        <select class="form-control js-example-basic-single itemsel" style="width: 50%" onchange="pur_updateItem(this)" id="<?php echo 'box'.$cart['rowid']?>">
                            <option value=""><?php echo  $this->lang->line('select') ?></option>
                            <?php if(!empty($boxlist)){  ?>
                                <?php 
                                foreach($boxlist as $boxlist_val){?>
                                    <option value="<?php echo $boxlist_val['id'];  ?>" <?php echo $cart['box_id'] === $boxlist_val['id'] ? 'selected':''  ?>><?php echo $boxlist_val['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom">
                        <input class="form-control" type="text" name="qty" onblur ="pur_updateItem(this);" value="<?php echo $cart['qty'] ?>" id="<?php echo 'qty'.$cart['rowid'] ?>">
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom">
                        <input class="form-control" type="text" name="tweight" onblur ="pur_updateItem(this);" value="<?php echo $cart['tweight'] ?>" id="<?php echo 'twe'.$cart['rowid'] ?>">
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom">
                        <input class="form-control" type="text" name="bweight" onblur ="pur_updateItem(this);" value="<?php echo $cart['bweight'] ?>" id="<?php echo 'bwe'.$cart['rowid'] ?>">
                    </div>
                </td>
                <td>
                    <div class="form-group form-group-bottom">
                        <input class="form-control" type="text" name="nweight" onblur ="pur_updateItem(this);" value="<?php echo $cart['nweight'] ?>" id="<?php echo 'nwe'.$cart['rowid'] ?>">
                    </div>
                </td>
                <td>
                    <a href="javascript:void(0)" id="<?php echo $cart['rowid'] ?>" onclick="pur_removeItem(this);"  class="remTire" style="color: red"><i class="glyphicon glyphicon-trash"></i></a>
                </td>
            </tr>

        <?php $i++; };} ?>

        <tr>
            <td>
                <div class="form-group form-group-bottom">

                </div>
            </td>

            <td>
                <div class="form-group form-group-bottom p_div">
                    <select class="form-control js-example-basic-single itemsel" style="width: 100%" onchange="pur_product_id(this)" id="">
                        <option value=""><?php echo  $this->lang->line('select') ?></option>
                        <?php if(!empty($itemcatlist)){ foreach ($itemcatlist as $itemcatkey => $itemcatval){ ?>
                            <option value="<?php echo $itemcatval['id']?>"><?php echo $itemcatval['name']?></option>
                        <?php }; } ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group form-group-bottom p_div">
                    <select class="form-control js-example-basic-single itemsel" style="width: 50%"  id="">
                        <option value=""><?php echo  $this->lang->line('select') ?></option>
                        <?php if(!empty($boxlist)){ foreach ($boxlist as $boxcatkey => $boxcatval){ ?>
                            <option value="<?php echo $boxcatval['id']?>"><?php echo $boxcatval['name']?></option>
                        <?php }; } ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group form-group-bottom">
                    <input class="form-control" type="text" readonly>
                </div>
            </td>
            <td>
                <div class="form-group form-group-bottom">
                    <input class="form-control" type="text" readonly>
                </div>
            </td>
            <td>
                <div class="form-group form-group-bottom">
                    <input class="form-control" type="text" readonly>
                </div>
            </td>
            <td>
                <div class="form-group form-group-bottom">
                    <input class="form-control" type="text" readonly>
                </div>
            </td>


        </tr>

        </tbody>
    </table>
</div>