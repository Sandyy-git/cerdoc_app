<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>

            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('supplier_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('medicine_supplier', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm supplier"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_supplier'); ?></a> 
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
                                        <th><?php echo $this->lang->line('supplier_name'); ?></th>
                                        <th><?php echo $this->lang->line('supplier_contact'); ?></th>
                                        <th><?php echo $this->lang->line('gst_in'); ?></th>
                                        <th><?php echo $this->lang->line("drug_license_number"); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th><?php echo $this->lang->line('state'); ?></th>
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
                                            <td><?php echo $supplier['supplier']; ?></td>
                                            <td><?php echo $supplier['contact']; ?></td>
                                            <td><?php echo $supplier['gst_in']; ?></td>
                                            <td><?php echo $supplier['supplier_drug_licence']; ?></td>
                                            <td><?php echo $supplier['address']; ?></td>
                                            <td><?php echo $supplier['state']; ?></td>

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
                                                    <a  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('admin/medicinecategory/deletesupplier/<?php echo $supplier['id'] ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_supplier'); ?></h4> 
            </div>

            <form id="formadd" action="<?php echo site_url('admin/medicinecategory/addsupplier') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">    
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier_name'); ?></label>
                                    <small class="req"> *</small>
                                    <input autofocus="" name="supplier_category" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["supplier_category"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('supplier_category'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier_contact'); ?></label>
                                    <input autofocus="" name="contact" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["contact"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('contact'); ?></span>

                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                    <input autofocus="" name="address" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["address"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('address'); ?></span>

                                </div>                      
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('pincode'); ?></label>
                                    <input autofocus="" name="pincode" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["pincode"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('pincode'); ?></span>

                                </div>
                            </div>


                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('state'); ?></label><small class="req"> *</small>
                                    <select class="form-control" name="state">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($state as $bgkey => $bgvalue) {
                                            ?>
                                            <option <?php
                                            if ($staff["blood_group"] == $bgvalue) {
                                                echo "selected";
                                            }
                                            ?> value="<?php echo $bgvalue ?>"><?php echo $bgvalue ?></option>           

                                        <?php } ?>

                                    </select>
                                    <span class="text-danger"><?php echo form_error('state'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line("drug_license_number"); ?></label>
                                    <input autofocus="" name="supplier_drug_licence" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["supplier_drug_licence"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('supplier_drug_licence'); ?></span>
                                </div>
                            </div>
                                   
                            

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('gst_in'); ?></label>
                                    <input autofocus="" name="gst_in" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["gst_in"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('gst_in'); ?></span>

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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_supplier'); ?></h4> 
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/medicinecategory/addsupplier') ?>" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <input type="hidden" id="id" name="suppliercategoryid">
                    <div class="row ptt10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier_name'); ?></label><small class="req"> *</small>
                                <input autofocus="" id="supplier_category" name="supplier_category" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_category"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_category'); ?></span>
                            </div>                 
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('supplier_contact'); ?></label>
                                <input autofocus="" id="contact" name="contact" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["contact"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('contact'); ?></span>

                            </div>                 

                        </div>
                    
                       
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                <input autofocus="" id="address" name="address" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["address"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('address'); ?></span>

                            </div>                 

                        </div>

                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('pincode'); ?></label>
                                <input autofocus="" id="pincode" name="pincode" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["pincode"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('pincode'); ?></span>


                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('state'); ?></label><small class="req"> *</small>
                                <select class="form-control" name="state" id="state">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($state as $bgkey => $bgvalue) {
                                        ?>
                                        <option <?php
                                        if ($result["state"] == $bgvalue) {
                                            echo "selected";
                                        }
                                        ?> value="<?php echo $bgvalue ?>"><?php echo $bgvalue ?></option>           

                                    <?php } ?>

                                </select>
                                <span class="text-danger"><?php echo form_error('state'); ?></span>
                            </div>
                        </div>


                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line("drug_license_number"); ?></label>
                                <input autofocus="" id="supplier_drug_licence" name="supplier_drug_licence" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["supplier_drug_licence"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('supplier_drug_licence'); ?></span>
                            </div>                 
                        </div>

                       
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('gst_in'); ?></label>
                                <input autofocus="" id="gst_in" name="gst_in" placeholder="" type="text" class="form-control"  value="<?php
                                if (isset($result)) {
                                    echo $result["gst_in"];
                                }
                                ?>" />
                                <span class="text-danger"><?php echo form_error('gst_in'); ?></span>

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
        $('#editmyModal').modal('show');
        $.ajax({

            dataType: 'json',

            url: '<?php echo base_url(); ?>admin/medicinecategory/get_datasupplier/' + id,

            success: function (result) {

                $('#id').val(result.id);
                $('#supplier_category').val(result.supplier);
                $('#contact').val(result.contact);
                $('#address').val(result.address);
                $('#pincode').val(result.pincode);
                $('#state').val(result.state);
                $('#supplier_drug_licence').val(result.supplier_drug_licence);
                $('#gst_in').val(result.gst_in);
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
                url: '<?php echo base_url(); ?>admin/medicinecategory/statusUpdatemedicinesupplier',
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

