<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>

            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('approve_product_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('approve_product', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm supplier"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_product'); ?></a> 
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('supplier_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('product_id'); ?></th>
                                        <th><?php echo $this->lang->line('product_name'); ?></th>
                                        <th><?php echo $this->lang->line('product_composition'); ?></th>
                                        <th><?php echo $this->lang->line('product_unit_packing'); ?></th>
                                        <th><?php echo $this->lang->line('pts'); ?></th>
                                        <th><?php echo $this->lang->line("ptr"); ?></th>
                                        <th><?php echo $this->lang->line('patient_billing_rate'); ?></th>
                                        <th><?php echo $this->lang->line('mrp'); ?></th>
                                        <th><?php echo $this->lang->line('product_gst'); ?></th>
                                        <th><?php echo $this->lang->line('active'); ?></th>

                                        

                                        
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($supplierCategory as $supplier) {
                                        ?>
                                        <tr>
                                            <td><?php echo $supplier['product_id']; ?></td>
                                            <td><?php echo $supplier['product_name']; ?></td>
                                            <td><?php echo $supplier['product_composition']; ?></td>
                                            <td><?php echo $supplier['product_unit_packing']; ?></td>

                                            <td><?php echo $supplier['pts']; ?></td>
                                            <td><?php echo $supplier['ptr']; ?></td>
                                            <td><?php echo $supplier['patient_billing_rate']; ?></td>
                                            <td><?php echo $supplier['product_mrp']; ?></td>
                                            <td><?php echo $supplier['product_gst']; ?></td>
                                            <td><?php if($supplier['active'] == 1){ ?>

                                                <input type="checkbox" checked="checked" onclick="assignsupplier(this,<?php echo $supplier['id']; ?>)"
                                        id="global_shift_<?php echo $supplier['id']; ?>" name="global_shift[]" value="<?php echo $supplier['id']; ?>" data-id = <?= $shift_value['id'].$shift_value['assign_status']; ?>>
                                        <span class="hide" id="checkbox_print_<?= $supplier['id']; ?>">

                                            <?php }else{  ?>

                                                <input type="checkbox" onclick="assignsupplier(this,<?php echo $supplier['id']; ?>)"
                                        id="global_shift_<?php echo $supplier['id']; ?>" name="global_shift[]" value="<?php echo $supplier['id']; ?>" data-id = <?= $shift_value['id'].$shift_value['assign_status']; ?>>
                                        <span class="hide" id="checkbox_print_<?= $supplier['id']; ?>">

                                            <?php } ?>
                                        </td>

                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('medicine_supplier', 'can_edit')) { ?>
                                                    <a data-target="#editmyModal" onclick="get(<?php echo $supplier['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('medicine_supplier', 'can_delete')) {
                                                    ?>
                                                    <a  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('admin/medicinecategory/deletapproveproduct/<?php echo $supplier['id'] ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_product'); ?></h4> 
            </div>

            <form id="formadd" action="<?php echo site_url('admin/medicinecategory/addproduct') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">    
                    <div class="ptt10">
                        <div class="row">

                        <!-- <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_id'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_id" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_id"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('prodcut_id'); ?></span>
                                </div>
                            </div> -->


                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_name'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_name" id="product_name" placeholder="" type="text" onchange="collectProductName(this.value)" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_name"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('prodcut_name'); ?></span>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('company_name'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="company_name" id="company_name" placeholder="" type="text" onchange="collectProductName(this.value)" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["company_name"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('company_name'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('hsn_code'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="hsn_code" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["hsn_code"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('hsn_code'); ?></span>

                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_unit_packing'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_unit_packing" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_unit_packing"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_unit_packing'); ?></span>

                                </div>                      
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_composition'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" id="product_composition" name="product_composition" onchange="collectText(this.value)" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_composition"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_composition'); ?></span>

                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('pts'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="pts" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["pts"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('pts'); ?></span>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line("ptr"); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="ptr" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["ptr"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('ptr'); ?></span>
                                </div>
                            </div>
                                   
                            

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('patient_billing_rate'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="patient_billing_rate" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["patient_billing_rate"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('patient_billing_rate'); ?></span>

                                </div>                      
                            </div>


                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_mrp'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_mrp" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_mrp"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_mrp'); ?></span>

                                </div>                      
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_discount'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_discount" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_discount"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_discount'); ?></span>

                                </div>                      
                            </div>


                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_gst'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_gst" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_gst"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_gst'); ?></span>

                                </div>                      
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_vp'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_vp" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_vp"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_vp'); ?></span>

                                </div>                      
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('product_lp'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="product_lp" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["product_lp"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('product_lp'); ?></span>

                                </div>                      
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>

            </form>
        </div><!--./row-->   
    </div>
</div>


<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_product'); ?></h4> 
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/medicinecategory/addproduct') ?>" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <input type="hidden" id="id" name="approveproductid">
                    <div class="row ptt10">
                        

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product_name'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="prod_name" name="product_name" placeholder="" onchange="collectProductName(this.value)" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_name"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_name'); ?></span>
                            </div>                 
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('company_name'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="company_name" name="company_name" placeholder="" onchange="collectProductName(this.value)" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["company_name"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('company_name'); ?></span>
                            </div>                 
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('hsn_code'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="hsn_code" name="hsn_code" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["hsn_code"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('hsn_code'); ?></span>
                            </div>                 
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product_unit_packing'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_unit_packing" name="product_unit_packing" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_unit_packing"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_unit_packing'); ?></span>


                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('product_composition'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="prod_composition" name="product_composition" onchange="collectTextEdit(this.value)" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_composition"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_composition'); ?></span>

                            </div>                 
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("pts"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="pts" name="pts" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["pts"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('pts'); ?></span>
                            </div>                 
                        </div>

                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('ptr'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="ptr" name="ptr" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["ptr"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('ptr'); ?></span>

                            </div>                 

                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("patient_billing_rate"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="patient_billing_rate" name="patient_billing_rate" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["patient_billing_rate"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('patient_billing_rate'); ?></span>
                            </div>                 
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("product_mrp"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_mrp" name="product_mrp" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_mrp"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_mrp'); ?></span>
                            </div>                 
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("product_discount"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_discount" name="product_discount" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_discount"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_discount'); ?></span>
                            </div>                 
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("product_gst"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_gst" name="product_gst" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_gst"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_gst'); ?></span>
                            </div>                 
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("product_vp"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_vp" name="product_vp" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_vp"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_vp'); ?></span>
                            </div>                 
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("product_lp"); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" id="product_lp" name="product_lp" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["product_lp"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('product_lp'); ?></span>
                            </div>                 
                        </div>


    

                    </div>
                </div><!--./modalbody-->       

                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>


            </form>
        </div><!--./row--> 

    </div>
</div>
<script>


    $(document).ready(function (e) {

        $('#formadd').on('submit', (function (e) {
           
            $("#formaddbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#formaddbtn").button('reset');
                },
                error: function () {

                }
            });


        }));

    });


    function get(id) {
        // alert(id);
        $('#editmyModal').modal('show');
        $.ajax({

            dataType: 'json',

            url: '<?php echo base_url(); ?>admin/medicinecategory/get_dataapprovesupplier/' + id,

            success: function (result) {
                console.log(result.product_name);
                $('#id').val(result.id);
                $('#prod_name').val(result.product_name);
                $('#hsn_code').val(result.hsn_code);
                $('#product_unit_packing').val(result.product_unit_packing);
                $('#prod_composition').val(result.product_composition);
                $('#pts').val(result.pts);
                $('#ptr').val(result.ptr);
                $('#patient_billing_rate').val(result.patient_billing_rate);
                $('#product_mrp').val(result.product_mrp);
                $('#product_discount').val(result.product_discount);
                $('#product_gst').val(result.product_gst);
                $('#product_vp').val(result.product_vp);
                $('#product_lp').val(result.product_lp);
                $('#company_name').val(result.company_name);
            }

        });

    }


    $(document).ready(function (e) {

        $('#editformadd').on('submit', (function (e) {
            $("#editformaddbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#editformaddbtn").button('reset');
                },
                error: function () {

                }
            });
        }));
	});

$(".supplier").click(function(){
	$('#formadd').trigger("reset");
});

    $(document).ready(function (e) {
        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });
    });
</script>

<script>
function collectText(compName){
    $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/getComposition',
                type: "POST",
                data: {product_composition:compName},
                dataType: 'json',
                success: function (data) {
                    if(data.length >= 1){
                        $("#product_composition").val('');
                        $("#product_composition").val(data[0]['product_composition']);
                    }else{

                    }
                },
                error: function () {
                    alert("Fail")
                }
            });
}

</script>
<script>
function collectProductName(productName){
    $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/getProductName',
                type: "POST",
                data: {product_name:productName},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.length >= 1){
                        $("#product_name").val('');
                        $("#product_name").val(data[0]['product_name']);
                    }else{

                    }
                },
                error: function () {
                    alert("Fail")
                }
            });
}
    </script>

<script>
function collectTextEdit(compName){
    $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/getComposition',
                type: "POST",
                data: {product_composition:compName},
                dataType: 'json',
                success: function (data) {
                    if(data.length >= 1){
                        $("#prod_composition").val('');
                        $("#prod_composition").val(data[0]['product_composition']);
                    }else{

                    }
                },
                error: function () {
                    alert("Fail")
                }
            });
}

</script>

<script>
function collectProductNameEdit(productName){
    $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/getProductName',
                type: "POST",
                data: {product_name:productName},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.length >= 1){
                        $("#prod_name").val('');
                        $("#prod_name").val(data[0]['product_name']);
                    }else{

                    }
                },
                error: function () {
                    alert("Fail")
                }
            });
}
    </script>


<script>
    function assignsupplier(checkbox,doctor_id){
        // console.log("checkbox_print_"+checkbox.dataset.id);
        if(checkbox.checked){
            status = 1;
            // document.querySelector("#checkbox_print_"+checkbox.dataset.id).innerHTML = "<?= $this->lang->line("yes"); ?>";
        }else{
            status = 0;
            // document.querySelector("#checkbox_print_"+checkbox.dataset.id).innerHTML = "<?= $this->lang->line("no"); ?>";
        }
        $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/statusUpdateapproveproduct',
                type: "POST",
                data: {doctor_id:doctor_id,  status:status},
                dataType: 'json',
                success: function (data) {
                    if(data.status == "success"){
                        successMsg(data.message);
                    }
                },
                error: function () {
                    alert("Fail")
                }
            });
    }
</script>


