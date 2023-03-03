<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!--  <?php //if (($this->rbac->hasPrivilege('department', 'can_add')) || ($this->rbac->hasPrivilege('department', 'can_edit'))) {
?>      -->
            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>

            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('assign_supplier_list'); ?></h3>
                        <div class="box-tools pull-right">
                            
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('medicine_category_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('assign_supplier'); ?></th>
                                        <th><?php echo $this->lang->line('assign_supplier'); ?></th>
                                        <!-- <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <!-- DOCTOR LOGIN START -->

                                    <?php if(!empty($shift)){ 
                                        foreach ($shift as $shift_key => $shift_value) {
                                    ?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <a href="#" data-toggle="popover" class="detail_popover"><?php echo $shift_value['name']." ".$shift_value["surname"]; ?> (<?php echo $shift_value["employee_id"]; ?>)</a>
                                        </td>

                                        <td>
                                            <?php if($shift_value['assign_status'] == 0){  ?>
                                        <input type="checkbox" onclick="assignsupplier(this,<?php echo $shift_value['id']; ?>)"
                                        id="global_shift_<?php echo $shift_value['id']; ?>" name="global_shift[]" value="<?php echo $shift_value['id']; ?>" data-id = <?= $shift_value['id'].$shift_value['assign_status']; ?>>
                                        <span class="hide" id="checkbox_print_<?= $shift_value['id'].$shift_value['assign_status']; ?>">
                                                    <?php 
                                                        if(in_array($gvalue['id'], $doctor_shift)){
                                                            echo $this->lang->line("yes");
                                                        }else{
                                                            echo $this->lang->line("no");
                                                        }
                                                    ?>
                                                </span>

                                                <?php }else{  ?>
                                                    <input type="checkbox" checked="checked" onclick="assignsupplier(this,<?php echo $shift_value['id']; ?>)"
                                        id="global_shift_<?php echo $shift_value['id']; ?>" name="global_shift[]" value="<?php echo $shift_value['id']; ?>" data-id = <?= $shift_value['id'].$shift_value['assign_status']; ?>>
                                        <span class="hide" id="checkbox_print_<?= $shift_value['id'].$shift_value['assign_status']; ?>">
                                                    <?php 
                                                        if(in_array($gvalue['id'], $doctor_shift)){
                                                            echo $this->lang->line("yes");
                                                        }else{
                                                            echo $this->lang->line("no");
                                                        }
                                                    ?>
                                                </span>
                                                    <?php

                                                } ?>
                                        </td>
                                        <!-- <?php foreach ($global_shift as $gkey => $gvalue) { 
                                           
                                            ?>
                                            
                                            <td>
                                               <?php  $doctor_shift = array_column($shift_value["doctor_shift"], "id");  ?>
                                               <input type="checkbox" 
                                               <?php if ($this->rbac->hasPrivilege('online_appointment_doctor_shift', 'can_edit')) { ?>
                                                    onclick="changeShift(<?php echo $shift_value['id']; ?>,<?php echo $gvalue['id']; ?>,this)" 
                                               <?php }else{
                                                   echo " disabled";
                                               } ?>
                                               id="global_shift_<?php echo $gvalue['id']; ?>" name="global_shift[]" value="<?php echo $gvalue['id']; ?>" data-id = <?= $shift_value['id'].$gvalue['id']; ?>
                                               <?php if(in_array($gvalue['id'], $doctor_shift)){echo "checked=checked";} ?>
                                               />
                                                <span class="hide" id="checkbox_print_<?= $shift_value['id'].$gvalue['id']; ?>">
                                                    <?php 
                                                        if(in_array($gvalue['id'], $doctor_shift)){
                                                            echo $this->lang->line("yes");
                                                        }else{
                                                            echo $this->lang->line("no");
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                        <?php } ?> -->
                                    </tr>
                                    <?php } } ?>
                                    <!-- DOCTOR LOGIN END -->

                                 
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_medicine_category'); ?></h4> 
            </div>

            <form id="formadd" action="<?php echo site_url('admin/medicinecategory/add') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">  
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="medicine_category" placeholder="" type="text" class="form-control"  value="<?php
                            if (isset($result)) {
                                echo $result["medicine_category"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>

                        </div>          

                    </div>
                </div><!--./modal-body-->        
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
                <h4 class="modal-title"> <?php echo $this->lang->line('edit_medicine_category'); ?></h4> 
            </div>


            <form id="editformadd" action="<?php echo site_url('admin/medicinecategory/add') ?>" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="medicine_category" name="medicine_category" placeholder="" type="text" class="form-control"  value="<?php
                            if (isset($result)) {
                                echo $result["medicine_category"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                            <input type="hidden" id="id" name="medicinecategoryid">
                        </div>                 
                    </div>
                </div><!--./madal-body-->     
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

            url: '<?php echo base_url(); ?>admin/medicinecategory/get_data/' + id,

            success: function (result) {

                $('#id').val(result.id);
                $('#medicine_category').val(result.medicine_category);

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


$(".medicine").click(function(){
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
     function delete_recordById(id) {
              
              if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
                    $.ajax({
                        url: '<?php echo base_url()."admin/medicinecategory/delete/"; ?>'+id,
                        data:{id:id},
                        type:"post",
                        success: function (res) {
                           toastr.success(
                            "<?php echo $this->lang->line('record_deleted') ?>",
                            '',
                            {
                              timeOut: 1000,
                              fadeOut: 1000,
                              onHidden: function () {
                                 window.location.reload(true);
                                }
                            }
                          );  
                        }
                    });
                }
            }

</script>

<script>
    function assignsupplier(checkbox,doctor_id){
        // alert(doctor_id);

        console.log("checkbox_print_"+checkbox.dataset.id);
        if(checkbox.checked){
            status = 1;
            document.querySelector("#checkbox_print_"+checkbox.dataset.id).innerHTML = "<?= $this->lang->line("yes"); ?>";
        }else{
            status = 0;
            document.querySelector("#checkbox_print_"+checkbox.dataset.id).innerHTML = "<?= $this->lang->line("no"); ?>";
        }
        $.ajax({
                url: '<?php echo base_url(); ?>admin/medicinecategory/editAssignPermissions',
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