<input type="hidden" id="visit_details_id" name="visit_details_id" value="<?php echo $visit_details_id;?>">
<input type="hidden" name="action" value="add">
<input type="hidden" name="ipd_prescription_basic_id" value="0">
                <div class="row">
                    <div class="col-sm-9">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_note'); ?></label> 
                                    <textarea style="height:50px"  name="header_note" class="form-control" id="compose-textareaneww" ></textarea>
                                </div> 
                                <hr/>
                            </div>
                             <div class="col-sm-12">  
                                <table class="table table-striped table-bordered table-hover" >
                                    <tr>
                                        <td style="display:none"><div class="form-group">
                                            <!-- <label><?php echo $this->lang->line('finding_category'); ?></label> -->
                                            <label><?php echo $this->lang->line('finding_diagnosis'); ?></label>
                                            <select class="form-control select2 findingtype" style="width: 100%" name='finding_type' id="finding_type">
                                                <option value=""><?php echo $this->lang->line('select'); ?> </option>
                                                <?php
                                                foreach ($findingtype as $fvalue) {
                                                ?>
                                                <option value="<?php echo $fvalue["id"]; ?>"><?php echo $fvalue["category"] ?>
                                                            </option>   
                                                        <?php } ?>
                                             </select>
                                            </div>
                                        </td>
                                        <td style="display:none">
                                           
                                                <label for="filterinput" > 
                                                    <?php echo $this->lang->line('finding_list'); ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label class="checkbox"><?php echo $this->lang->line('select'); ?></label></li>
                                                    </ul>
                                                </div>
                                           
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('finding_diagnosis'); ?></label>
                                                    <textarea name="finding_description" id="finding_description"  class="form-control"> </textarea> 
                                            </div>
                                        </td>
                                        <td>  <label><?php echo $this->lang->line('diagnosis_print'); ?> </label><br/><input type="checkbox" name="finding_print" value="yes" checked></td>
                                    </tr>
                                </table>
                                </div>
                              
                            <table class="table table-striped table-bordered table-hover mb0" id="tableID">
                            <tr id="row1">
                                    <td> 
                                    <input type="hidden" name="rows[]" value="1">          
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">

                                            <div class="">
                                                <label>
                                            <?php echo $this->lang->line('medicine_category'); ?></label><small class="req"> *</small>
                                            <select class="form-control select2 medicine_category" style="width: 70%" name='medicine_cat_1' id="medicine_cat_1">
                                            <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                                    </option>
                                            <?php
                                            foreach ($medicineCategory as $dkey => $dvalue) {
                                            ?>
                                            <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["medicine_category"] ?>
                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>    
                                        
                                        
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="right:4%">

                                            <div class="">
                                                <label>
                                            <?php echo $this->lang->line('search_type'); ?></label><small class="req"> *</small>
                                            <select class="form-control select2 search_type" style="width: 70%" name='search_type_1' id="1">
                                            <option value="<?php echo set_value('search_type_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                                    </option>
                                            <?php
                                            foreach ($medicinesearchType as $dkey => $stvalue) {
                                            ?>
                                            <option value="<?php echo $stvalue["id"]; ?>"><?php echo $stvalue["search_type"] ?>
                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" style="right:8%">
                                            <div class="">
                                                <label><?php echo $this->lang->line('search'); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_name" data-rowid="1" style="width: 100%"  name="medicine_1">
                                                    <option value=""><?php echo $this->lang->line('select');?></option>
                                                </select>
                                                <!-- <div id="suggesstion-box0"><small id="stock_info_1"> </small></div> -->
                                                
                                            </div>
                                        </div>


                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <label><?php echo $this->lang->line('medicine'); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_brand" data-rowid="1" style="width: 100%"  name="medicine_brand_1">
                                                    <option value=""><?php echo $this->lang->line('select');?></option>
                                                </select>
                                                <!-- <div id="suggesstion-box0"><small id="stock_info_1"> </small></div> -->
                                                
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="right:0%">
                                            <div class="">
                                                <label><?php echo $this->lang->line('available_qty'); ?></label><small class="req"></small>
                                            <div id="suggesstion-box0" class="form-control"><small id="stock_info_1"> </small></div>                                                
                                            </div>
                                        </div>


                                        <!-- HIDED DOSE INSTEAD ADDING BF/AF at last -->
                                        <!-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                            <div class="">
                                                <label><?php echo $this->lang->line("dose"); ?></label>
                                                <select class="form-control select2 medicine_dosage" style="width: 100%"  name="dosage_1">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                            </div> 
                                        </div> -->

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="right:0%">
                                            <div class="">
                                               <label><?php echo $this->lang->line("dose_frequency"); ?></label> 
                                               <select class="form-control  select2 interval_dosage" style="width:100%" id="interval_dosage_id" name='interval_dosage_1'>
                                                    <option value="<?php echo set_value('interval_dosage_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                                        <?php foreach ($intervaldosage as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["name"] ?>
                                                        </option>
                                                                <?php }?>
                                                    </select>   
                                                    <span class="text-danger"><?php echo form_error('interval_dosage_id'); ?></span>
                                            </div> 
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6" style="right:0%">
                                            <div class="">
                                                <label><?php echo $this->lang->line("dose_duration"); ?></label> <small class="req"> *</small>
                                               <select class="form-control  select2" style="width:100%" id="interval_dosage_id" name='duration_dosage_1'>
                                                    <option value="<?php echo set_value('interval_dosage'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                                        <?php foreach ($durationdosage as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["name"] ?>
                                                        </option>
                                                                <?php }?>
                                                    </select>   
                                                    <span class="text-danger"><?php echo form_error('interval_dosage_id'); ?></span>
                                            </div> 
                                        </div>
                                       <!-- NEW AF/BF -->
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                            <div class="">
                                                <!-- <label><?php echo $this->lang->line("af/bf"); ?></label><small class="req"> *</small> -->
                                                <label>AF/BF</label><small class="req"> *</small>

                                                <select class="form-control select2 medicine_afbf" style="width: 100%"  name="afbf_1">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <option value="BF">Before Food</option>
                                            <option value="AF">After Food</option>
                                            <option value="With Food">With Food</option>
                                            <option value="Not Applicable">Not Applicable</option>


                                                </select>
                                            </div> 
                                        </div>


                                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line('instruction'); ?></label> 
                                                <textarea name="instruction_1" style="height: 28px;" class="form-control" ></textarea>
                                            </div> 
                                        </div>
                                    </td>
                                    <td>    
                                        <button type="button" class="closebtn delete_row crossbtnfa"  data-row-id="1" autocomplete="off"><i class="fa fa-remove"></i></button>
                                    </td>
                                </tr>
                            </table>            

                            <div class="col-sm-12">
                                     <a class="btn btn-info add-record addplus-xs" data-added="0"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_medicine');?></a>
                                <hr>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_note'); ?></label> 
                                    <textarea style="height:50px" rows="1" name="footer_note" class="form-control" id="compose-textareass"></textarea>
                                </div> 
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="col-sm-3">
                    <div class="ptt10">
                    <div class="col-sm-12">
                        <div class="form-group">
                        <label>
                             <?php echo $this->lang->line('pathology'); ?></label>
                            
                             <select class="form-control multiselect2" style="width: 100%" name='pathology[]' multiple id="pathologyOpt">
                             
                                <?php foreach ($pathology as $key => $value) { ?>
                                    <option value="<?php echo $value["id"]; ?>"><?php echo " (".$value["short_name"].") ".$value["test_name"]; ?>
                                     </option>   
                                <?php } ?>
                             </select>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                        <label>
                             <?php echo $this->lang->line('radiology'); ?></label>
                             <select class="form-control multiselect2" style="width: 100%" name='radiology[]' id="radiologyOpt" multiple>
                            
                                <?php foreach ($radiology as $key => $value) { ?>
                                    <option value="<?php echo $value["id"]; ?>"><?php echo " (".$value["short_name"].") ".$value["test_name"]; ?>
                                     </option>   
                                <?php } ?>
                             </select>
                        </div>
                    </div>
                    <!-- <div class="col-sm-12">
                    <div class="ptt10">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('notification_to'); ?></label>
                             <?php
                                foreach ($roles as $role_key => $role_value) {
                                            $userdata = $this->customlib->getUserData();
                                            $role_id = $userdata["id"];
                                            ?>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="visible[]" value="<?php echo $role_value['id']; ?>" <?php if ($role_value["id"] == $role_id) {
                                                 echo "checked onclick='return false;'";
                                                }
                                             ?> <?php echo set_checkbox('visible[]', $role_value['id'], false) ?> /> <b><?php echo $role_value['name']; ?></b> </label>
                                    </div>
                                    <?php
                                    }
                                    ?>

                                   

                     </div>
                    </div> -->

    
    <select multiple="" class="form-control" id="health_institutions1">
                    
                </div>
                </div>
                </div> 