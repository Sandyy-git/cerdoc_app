<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php
$this->load->view('admin/onlineappointment/appointmentSidebar');
?>
            </div>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('add_clinics'); ?></h3>
                        <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('add_clinics', 'can_add')){ ?>
                                <button onclick="addShiftModal()" class="btn btn-primary btn-sm addpayment"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_clinics'); ?></button>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('address'); ?></th>
                                        <th><?php echo $this->lang->line('pincode'); ?></th>
                                        <th><?php echo $this->lang->line('staff_locality'); ?></th>
                                        <th><?php echo $this->lang->line('city'); ?></th>
                                        <th><?php echo $this->lang->line('state'); ?></th>
                                        <th><?php echo $this->lang->line('image'); ?></th>

                                        <?php if ($this->rbac->hasPrivilege('add_clinics', 'can_edit') || $this->rbac->hasPrivilege('add_clinics', 'can_delete')) { ?>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        <?php } ?> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($doctorClinics)){ 
                                        foreach ($doctorClinics as $shift_key => $shift_value) {
                                    ?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <a href="#" data-toggle="popover" class="detail_popover"><?php echo $shift_value['clinic_name'] ?></a>
                                        </td>
                                        <td>
                                            <?php echo $shift_value['address'] ?>
                                        </td>
                                        <td>
                                            <?php echo $shift_value['pincode'] ?>
                                        </td>
                                          <td>
                                            <?php echo $shift_value['locality'] ?>
                                        </td>
                                         <td>
                                            <?php echo $shift_value['city'] ?>
                                        </td>
                                          <td>
                                            <?php echo $shift_value['state'] ?>
                                        </td>
                                         <td>
                                            <img style="width: 50px;height: 50px;" src="<?php echo base_url()."/uploads/clinic_images/".$shift_value['clinic_logo'] ?>">
                                        </td>

                                        <td class="mailbox-date pull-right noExport">
                                        <?php if ($this->rbac->hasPrivilege('add_clinics', 'can_edit')){ ?>
                                            <a href="#" onclick="getRecord('<?php echo $shift_value['id'] ?>')" class="btn btn-default btn-xs" data-target="#myModalEdit" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if ($this->rbac->hasPrivilege('add_clinics', 'can_delete')){ ?>
                                            <a  class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_recordByIdReload('admin/onlineappointment/deletedoctorClinics/<?php echo $shift_value['id']; ?>', '<?php echo $this->lang->line('delete_confirm') ?>')" data-original-title="<?php echo $this->lang->line('delete') ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_clinics'); ?></h4>
            </div>
            <form id="addshift" class="ptt10" method="post" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('clinic_name'); ?></label>
                                    <span class="req"> *</span>
                                    <input  name="clinic_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                    <span class="text-danger"><?php echo form_error('clinic_name'); ?></span>

                                </div>
                            </div>

                             <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('address'); ?></label>
                                    <span class="req"> *</span>
                                   
                                      <textarea  name="address" placeholder="" type="text" class="form-control"  value="<?php echo set_value('address'); ?>" ></textarea>
                                      <span class="text-danger"><?php echo form_error('address'); ?></span>

                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('pincode'); ?></label>
                                    <span class="req"> *</span>
                                    <input  name="pincode" placeholder="" type="text" class="form-control"  value="<?php echo set_value('pincode'); ?>" />
                             <span class="text-danger"><?php echo form_error('pincode'); ?></span>

                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('staff_locality'); ?></label>
                                    <span class="req"> *</span>
                                   <select id="specialistOpt" name="locality_id" placeholder="" type="text" class="form-control" onchange="appendCity(this)">
                                                    <?php foreach ($stafflocality as $key => $slvalue) {
                                                        ?>
                                                        <option value="<?php echo $slvalue["id"] ?>" <?php echo set_select('staff_locality', $slvalue['id'], set_value('staff_locality')); ?>><?php echo $slvalue["locality"] ?></option>
                                                    <?php }
                                                    ?>
                                   </select>
                                  <span class="text-danger"><?php echo form_error('staff_locality'); ?></span>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('city'); ?></label>
                                    <span class="req"> *</span>
                                    <input  id="city" name="city" placeholder="" type="text" class="form-control"  value="<?php echo set_value('city'); ?>" />
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('state'); ?></label>
                                    <span class="req"> *</span>
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



                            <div class="col-sm-6">
                                <div class="form-group">
                                   <label for="name"><?php echo $this->lang->line('image'); ?></label>
                                   <span class="req"> *</span>
                                           <input  type="file" name="clinic_logo" style="opacity: 1;" class="form-control" id="clinic_logo" value="<?php echo set_value('clinic_logo'); ?>" />
                                         <span class="text-danger"><?php echo form_error('clinic_logo'); ?></span>
                                </div>
                            </div>

                           
                    </div>
                </div>   
                <div class="modal-footer clear">
                    <div class="pull-right">
                        <button type="submit" id="addshiftbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>     
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_shift') ?></h4>
            </div>

            <form id="editshift" class="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10 row" id="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="clinic_name" name="clinic_name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('clinic_name'); ?>" />
                                <input id="dclinicid" name="dclinicid" placeholder="" type="hidden" class="form-control"  />
                            </div>
                        </div>

                       <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('address'); ?></label>
                                    <span class="req"> *</span>
                                   
                                      <textarea id="address" name="address" placeholder="" type="text" class="form-control"  value="<?php echo set_value('address'); ?>" ></textarea>
                                      <span class="text-danger"><?php echo form_error('address'); ?></span>

                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('pincode'); ?></label>
                                    <span class="req"> *</span>
                                    <input  id="pincode" name="pincode" placeholder="" type="text" class="form-control"  value="<?php echo set_value('pincode'); ?>" />
                             <span class="text-danger"><?php echo form_error('pincode'); ?></span>

                                </div>
                            </div>

                             <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('staff_locality'); ?></label>
                                    <span class="req"> *</span>
                                   <select id="locality_id" name="locality_id" placeholder="" type="text" class="form-control" onchange="appendCity(this)">
                                                    <?php foreach ($stafflocality as $key => $slvalue) {
                                                        ?>
                                                        <option value="<?php echo $slvalue["id"] ?>" <?php echo set_select('staff_locality', $slvalue['id'], set_value('staff_locality')); ?>><?php echo $slvalue["locality"] ?></option>
                                                    <?php }
                                                    ?>
                                   </select>
                                  <span class="text-danger"><?php echo form_error('staff_locality'); ?></span>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('city'); ?></label>
                                    <span class="req"> *</span>
                                    <input  id="mycity" name="city" placeholder="" type="text" class="form-control"  value="<?php echo set_value('city'); ?>" />
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name"><?php echo $this->lang->line('state'); ?></label>
                                    <span class="req"> *</span>
                                    <select id="state" class="form-control" name="state">
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


                            <div class="col-sm-6">
                                <div class="form-group">
                                   <label for="name"><?php echo $this->lang->line('image'); ?></label>
                                   <span class="req"> *</span>
                                           <input  type="file" name="clinic_logo" style="opacity: 1;" class="form-control" id="clinic_logo" value="<?php echo set_value('clinic_logo'); ?>" />
                                  <span class="text-danger"><?php echo form_error('clinic_logo'); ?></span>
                                </div>
                            </div>


                    </div>
                </div>
                <div class="modal-footer clear">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="editshiftbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('focus', '.time', function () {
        var $this = $(this);
        $this.datetimepicker({
            format: 'LT'
        });
    });
    

    $(document).ready(function (e) {
        $('#addshift').on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/onlineappointment/addClinics',
                type: "POST",
                data: new FormData(this),
                // data: fd,
                dataType: 'json',
                contentType: false,
                 cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            $('.' + index).html(value);
                            message += value;
                        });

                        errorMsg(message);
                    }else if(data.status == "invalid"){
                        errorMsg(data.message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#addshiftbtn").button('reset');
                },
                error: function () {
                    alert("<?php echo $this->lang->line('fail'); ?>")
                }
            });
        }));
    });

    $(document).ready(function (e) {
        $('#editshift').on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/onlineappointment/updateClinics',
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
                    }else if(data.status == "invalid"){
                        errorMsg(data.message);
                    }  else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#editshiftbtn").button('reset');
                },
                error: function () {
                    alert("<?php echo $this->lang->line('fail'); ?>")
                }
            });
        }));
    });

     function getRecord(id) {
        $('#myModalEdit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/onlineappointment/editClinics/' + id,
            type: "POST",
            dataType: "json",
            success: function (data) {
                $("#clinic_name").val(data.clinic_name);
                $("#dclinicid").val(id);
                $("#address").val(data.address);
                $("#pincode").val(data.pincode);
                $("#locality_id").val(data.locality_id);
                $("#mycity").val(data.city);
                $("#state").val(data.state);
            },
            error: function () {
                alert("<?php echo $this->lang->line('fail'); ?>")
            }
        });
    }

    function addShiftModal(){
        $('#myModal form')[0].reset();
        $("#myModal").modal("show");
    }
    
    $(document).ready(function (e) {
        $('#myModal,#myModalEdit').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    });

    function appendCity(ele){
    staff_loc = ele.value;
    var base_url = '<?php echo base_url() ?>';
            $.ajax({
                url: base_url + 'admin/staff/getLocationCity',
                type: 'POST',
                data: {id: staff_loc},
                dataType: 'json',
                success: function (result) {
                    $("#city").val(result.city);

                }
            });
    }

    
  
</script>