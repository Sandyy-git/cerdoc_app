<input type="hidden" name="action" value="update">
<input type="hidden" id="visit_details_id" name="visit_details_id" value="<?php echo $result->visit_details_id;?>">
<input type="hidden" name="ipd_prescription_basic_id" value="<?php echo $prescription_id ?>">
<h1>ds</h1>
<?php  
if (!empty($result->tests)) {
  foreach ($result->tests as $test_prev_key => $test_prev_value) {

   if($test_prev_value->pathology_id != ""){
?>
<input type="hidden" name="prev_pathology[]" value="<?php echo $test_prev_value->pathology_id;?>">
<?php
   }elseif($test_prev_value->radiology_id != ""){
?>
<input type="hidden" name="prev_radiology[]" value="<?php echo $test_prev_value->radiology_id;?>">
<?php
   }
  }
}

 ?>
       <div class="row">
                    <div class="col-sm-9">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('header_note'); ?></label> 
                                    <textarea style="height:50px" name="header_note" class="form-control" id="compose-textareanew"><?php echo $result->header_note; ?></textarea>
                                </div> 
                                <hr/>
                            </div>
                            <div class="col-sm-12">   
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td>
                                         <label><?php echo $this->lang->line('finding_category'); ?></label> 
                                            <select class="form-control select2 findingtype " style="width: 100%" name='finding_type' id="finding_type">
                                                <option value=""><?php echo $this->lang->line('select'); ?> </option>
                                                <?php
                                                foreach ($findingresult as $fvalue) {
                                                ?>
                                                    <option value="<?php echo $fvalue["id"]; ?>"><?php echo $fvalue["category"] ?>
                                                            </option>   
                                                <?php } ?>
                                             </select>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="filterinput"> 
                                                    <?php echo $this->lang->line('finding_list'); ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label class="checkbox"><?php echo $this->lang->line('select'); ?></label></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('finding_diagnosis'); ?></label> 
                                                    <textarea name="finding_description" id="finding_description"  class="form-control"  ><?php echo set_value('finding_description',$result->finding_description ) ?> </textarea> 
                                            </div>
                                        </td>
                                        <td>  <label><?php echo $this->lang->line('diagnosis_print'); ?> </label><br/><input type="checkbox" name="finding_print" value="yes" <?php if($result->is_finding_print == 'yes'){ echo 'checked'; } ?>></td>
                                    </tr>
                                </table>
                            </div>



                             <div class="col-sm-12">   
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <td>
                                          <label for="exampleInputFile">
                                                        <?php echo $this->lang->line('symptoms_type'); ?></label>
                                                    <div><select  name='symptoms_type'  id="actcat"  class="form-control select2 act"  style="width:100%" >
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                                ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"] ;?></option>

                                                        <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                        </td>
                                        <td>
                                            <div>
                                                <label for="exampleInputFile"> 
                                                        <?php echo $this->lang->line('symptoms') ; ?></label>
                                                    <div id="dd" class="wrapper-dropdown-4">
                                                        <input class="form-control filtersymptominput" type="text">
                                                        <ul class="dropdown scroll150 section_symptomul">
                                                            <li><label class="checkbox"><?php echo $this->lang->line('select'); ?></label></li>
                                                        </ul>
                                                    </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                 <label><?php echo $this->lang->line('symptoms_description'); ?></label>
                                                    <textarea class="form-control" id="symptoms_description" name="symptoms_description" ><?php echo set_value('symptoms_description',$result->symptoms_description ) ?></textarea>  
                                            </div>
                                        </td>
                                       
                                    </tr>
                                </table>
                            </div>


                            <table class="table table-striped table-bordered table-hover mb0" id="tableID">
                                  <?php 
                                  $medicine_row=1;
foreach ($result->medicines as $medicine_key => $medicine_value) {
    // var_dump($medicine_value);
   ?>
   <input type="hidden" name="ipd_prescription_detail_id_<?php echo $medicine_row?>" value="<?php echo $medicine_value->ipd_prescription_detail_id;?>">
 <tr id="row<?php echo $medicine_row?>">
                                    <td> 
<input type="hidden" name="post_medicine_category_id" value="<?php echo $medicine_value->medicine_category_id; ?>" class="post_medicine_category_id">
<input type="hidden" name="post_pharmacy_id" value="<?php echo $medicine_value->pharmacy_id; ?>" class="post_medicine_id">
<input type="hidden" name="post_dosage_id" value="<?php echo $medicine_value->dosage_id; ?>" class="post_dosage_id">
                                    <input type="hidden" name="rows[]" value="<?php echo $medicine_row?>">        
                                        <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label>
                    <?php echo $this->lang->line('medicine_category'); ?></label> <small class="req"> *</small>
                    <select class="form-control select2 medicine_category" style="width: 100%" name='medicine_cat_<?php echo $medicine_row?>' id="medicine_cat_<?php echo $medicine_row?>">
                    <option value="<?php echo set_value('medicine_category_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                            <?php
                                    foreach ($medicineCategory as $dkey => $dvalue) {
                                                        ?>
                                        <option value="<?php echo $dvalue["id"]; ?>" ><?php echo $dvalue["medicine_category"] ?>

                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div> 
                                        
                                        

                                        <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label>
                    <?php echo $this->lang->line('search_type'); ?></label> <small class="req"> *</small>
                    <select class="form-control select2 search_type" style="width: 100%" name='search_type_<?php echo $medicine_row?>' id="<?php echo $medicine_row?>">
                    <option value="<?php echo set_value('search_type_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                            <?php
                                    foreach ($medicinesearchType as $dkey => $stvalue) {
                                                        ?>
                                        <option value="<?php echo $stvalue["id"]; ?>" <?php echo set_select('organisation', $stvalue["id"], ($medicine_value->medicine_category_id == $stvalue["id"]) ? true : false); ?>><?php echo $stvalue["search_type"] ?>
                                                        </option>   
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line('search'); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_name" data-rowid="<?php echo $medicine_row?>" style="width: 100%"  name="medicine_<?php echo $medicine_row?>">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                </select>
                                                <!-- <span id="stock_info_<?php echo $medicine_row?>"></span>
                                                <div id="suggesstion-box0"></div> -->
                                            </div>
                                        </div>


                                        <div class="col-sm-8 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line('medicine'); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_brand" data-rowid="<?php echo $medicine_row?>" style="width: 100%"  name="medicine_brand_<?php echo $medicine_row?>">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php
                                        foreach ($medListforRedWind as $dkey => $refwindvalue) {
                                                        ?>
                                                    <option value="<?php echo $refwindvalue["id"]; ?>" <?php echo set_select('medicine_brand', $refwindvalue["id"], ($medicine_value->pharmacy_id == $refwindvalue["id"]) ? true : false); ?>><?php echo $refwindvalue["medicine_name"] ?>
                                                        </option>   
                                        <?php } ?>
                                                </select>
                                                <!-- <span id="stock_info_<?php echo $medicine_row?>"></span>
                                                <div id="suggesstion-box0"></div> -->
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="right:0%">
                                            <div class="">
                                                <label></label><small class="req"></small>
                                            <div id="suggesstion-box0" class="form-control"><small id="stock_info_<?php echo $medicine_row?>"> </small></div>                                                
                                            </div>
                                        </div>

                                       
                                        <!-- <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line("dose"); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_dosage" style="width: 100%"  name="dosage_<?php echo $medicine_row?>">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                            </div> 
                                        </div> -->
                                        <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label>
                                                <?php echo $this->lang->line("dose_interval"); ?></label>
                                                <select class="form-control select2 medicine_category" style="width: 100%" name='interval_dosage_<?php echo $medicine_row?>'>
                                                <option value="<?php echo set_value('dose_interval_id'); ?>"><?php echo $this->lang->line('select') ?>
                                                    </option>
                                                   <?php
                                                foreach ($intervaldosage as $dkey => $dvalue) {
                                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>" <?php echo set_select('interval_dosage', $dvalue["id"], ($medicine_value->dose_interval_id == $dvalue["id"]) ? true : false); ?>><?php echo $dvalue["name"] ?>
                                                                    </option>   
                                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label>
                                                <?php echo $this->lang->line("dose_duration"); ?></label>
                                                <select class="form-control select2 medicine_category" style="width: 100%" name='duration_dosage_<?php echo $medicine_row?>'>
                                                <option value="<?php echo set_value('dose_duration_id'); ?>"><?php echo $this->lang->line('select'); ?>
                                                    </option>
                                                   <?php
                                                foreach ($durationdosage as $dkey => $dvalue) {
                                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>" <?php echo set_select('duration_dosage', $dvalue["id"], ($medicine_value->dose_duration_id == $dvalue["id"]) ? true : false); ?>><?php echo $dvalue["name"] ?>
                                                                    </option>   
                                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- NEW AF/BF -->
                                       <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line("af/bf"); ?></label><small class="req"> *</small>
                                                <select class="form-control select2 medicine_afbf" style="width: 100%"  name="afbf_<?php echo $medicine_row?>">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                           
                                            <option value="<?php echo $medicine_value->afbf; ?>" <?php echo set_select('afbf', $medicine_value->afbf, ("After Food" == $medicine_value->afbf) ? true : false); ?>><?php echo "After Food" ?>

                                            <option value="<?php echo $medicine_value->afbf; ?>" <?php echo set_select('afbf', $medicine_value->afbf, ("Before Food" == $medicine_value->afbf) ? true : false); ?>><?php echo "Before Food" ?>
                                            <option value="<?php echo $medicine_value->afbf; ?>" <?php echo set_select('afbf', $medicine_value->afbf, ("With Food" == $medicine_value->afbf) ? true : false); ?>><?php echo "With Food" ?>
                                            <option value="<?php echo $medicine_value->afbf; ?>" <?php echo set_select('afbf', $medicine_value->afbf, ("Not Applicable" == $medicine_value->afbf) ? true : false); ?>><?php echo "Not Applicable" ?>

                                        </select>
                                            </div> 
                                        </div>

                                        <div class="col-sm-2 col-xs-6">
                                            <div class="">
                                                <label><?php echo $this->lang->line('instruction'); ?></label> 
                                                <textarea name="instruction_<?php echo $medicine_row?>" style="height: 28px;" class="form-control" ><?php echo $medicine_value->instruction; ?></textarea>
                                            </div> 
                                        </div>
                                    </td>
                                    <td>    
                                        <!-- <button type="button" class="closebtn delete_row crossbtnfa" data-row-id="1" autocomplete="off"><i class="fa fa-remove"></i></button> -->
                                        <button type="button" class="closebtn delete_row crossbtnfa" data-row-id='<?php echo $medicine_row?>' autocomplete="off"><i class="fa fa-remove"></i></button>
                                    </td>
                                </tr>
   <?php
   $medicine_row++;
}
                                ?>
                            </table>
                            <div class="col-sm-12">
                                <a class="btn btn-info addplus-xs add-record" data-added="0"><i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo $this->lang->line('add_medicine'); ?></a>
                            </div>
                            <br><hr>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('footer_note'); ?></label> 
                                    <textarea style="height:50px" rows="1" name="footer_note" class="form-control" id="compose-textareas"><?php echo $result->footer_note; ?></textarea>
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

            <option value="<?php echo $value["id"]; ?>" <?php echo check_test('pathology',$value["id"],$result) ? " selected" : "ddd" ?>><?php echo " (".$value["short_name"].") ".$value["test_name"]; ?>
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
                                    <option value="<?php echo $value["id"]; ?>"  <?php echo check_test('radiology',$value["id"],$result) ? " selected" : "ddd" ?>><?php echo " (".$value["short_name"].") ".$value["test_name"]; ?>
                                     </option>   
                                <?php } ?>
                             </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                    <div class="ptt10">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('notification_to'); ?></label>
                             <?php
                                foreach ($roles as $role_key => $role_value) {
                                            $userdata = $this->customlib->getUserData();
                                            $role_id = $userdata["role_id"];
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



                      <!-- Central Staffs -->

                      <!-- <?php
                                foreach ($centralStaffs as $role_key => $cs_value) {
                                            $userdata = $this->customlib->getUserData();
                                            $role_id = $userdata["role_id"];
                                            ?>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="visible[]" value="<?php echo $cs_value['id']; ?>" <?php if ($cs_value["id"] == $role_id) {
                                                 echo "checked onclick='return false;'";
                                                }
                                             ?> <?php echo set_checkbox('visible[]', $cs_value['id'], false) ?> /> <b><?php echo $cs_value['name']; ?></b> </label>
                                    </div>
                                    <?php
                                    }
                                    ?> -->

                                    <!-- END CENTARL STAFFS -->
                     </div>

                    </div>

                    <select multiple="" class="form-control" id="health_institutions1">

                </div>
                </div>
                </div> 

                   <?php                   
                    function check_test($type,$id,$array){
                    if(!empty($array->tests)){
                        foreach ($array->tests as $test_key => $test_value) {
                         if($type == "pathology"){
                             if($test_value->pathology_id == $id){
                                return TRUE;
                             }
                         }elseif($type == "radiology"){
                             if($test_value->radiology_id == $id){
                                return TRUE;
                             }
                         }
                        }
                    }
                    return FALSE;
                    }
                    ?>                   