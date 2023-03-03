<div class="content-wrapper">  
    <section class="content">
        <div class="row">

            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->rbac->hasPrivilege('leave_types', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/leavetypes" ><?php echo $this->lang->line('leave_type'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('department', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/department" ><?php echo $this->lang->line('department'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('designation', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/designation/designation"><?php echo $this->lang->line('designation'); ?></a></li>
                        <?php } ?>
                         <?php if ($this->rbac->hasPrivilege('specialist', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/specialist" class="active"><?php echo $this->lang->line('specialist'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('staff_locality', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/locality" class="active"><?php echo $this->lang->line('staff_locality'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('locality_city', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/city" class="active"><?php echo $this->lang->line('locality_city'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('assign_pincodes', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/assignpincodes" class="active"><?php echo $this->lang->line('assign_pincodes'); ?></a></li>
                        <?php } ?> 
                        <?php if ($this->rbac->hasPrivilege('assign_pincodes', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/assignpincodestodis" class="active"><?php echo $this->lang->line('add_pincodes_to_dis'); ?></a></li>
                        <?php } ?>  

                    </ul>
                </div>
            </div>

            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('assign_pincodes'); ?></h3>
                        <div class="box-tools pull-right">
                            
                            <?php if ($this->rbac->hasPrivilege('assign_pincodes', 'can_add')) { ?>
                                <a onclick="addModal()" class="btn btn-primary btn-sm medicine"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_pincodes'); ?></a> 
                            <?php } ?> 
                        </div>
                    </div> 
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('locality_city_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('locality_city'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('staff_locality'); ?></th> -->
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($pincodes)) {
                                        foreach ($pincodes as $value) {
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $value['pincode'];  ?></td>
                                                <!-- <td><?php echo $value['locality']; ?></td> -->
                                                
                                               
                                                <td class="text-right">
                                                    <?php  if ($this->rbac->hasPrivilege('locality_city', 'can_edit')) { ?>
                                                        <a data-target="#editmyModal" onclick="get(<?php echo $value['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php
                                                    }
                                                    if ($this->rbac->hasPrivilege('locality_city', 'can_delete')) {
                                                        ?>
                                                        <a  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_staff_pincode('<?php echo $value['id'] ?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php }  ?>
                                                </td>
                                            </tr>
                                            <?php
                                         
                                            
                                        }
                                        
                                    
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




<!-- ADD -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_pincodes'); ?></h4> 
            </div>
            <form id="formadd" action="<?php echo site_url('admin/assignpincodes/add') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                           
                            
                            <div id="dose_fields">                    
                                <div class="row">
                                    <div class="col-sm-5"> 
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('pincode'); ?></label>
                                            <small class="req"> *</small>
                                            <input autofocus="" name="pincode" placeholder="" type="text" class="form-control"/>
                                            <span class="text-danger"><?php echo form_error('pincode'); ?></span>
                                        </div> 
                                    </div> 
                          
                        
                           
                                </div>                       
                            </div> 
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <div class="form-group">
                                        <!-- <label><a class="btn addplus-xs btn-primary add-record" data-added="0"><i class="fa fa-plus"></i>&nbsp;<?php echo $this->lang->line('add'); ?></a></label> -->
                                
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div><!--./modal--> 
                </div>
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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_locality_city'); ?></h4> 
            </div>



            <form id="editformadd" action="<?php echo site_url('admin/assignpincodes/add') ?>" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                       
                        <div class="row">
                        <div class="col-sm-4"> 
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('pincode'); ?></label>
                                <small class="req"> *</small>
                                <input autofocus="" name="staffpincodeid" id="staffpincodeid" placeholder="" value="" type="hidden" class="form-control"   />
                                <input autofocus="" name="pincode" id="pincode" placeholder="" type="text" class="form-control"   />
                            </div> 
                        </div> 
                        
                        
                        
                    </div>              
                    </div>
                </div><!--./modal-body-->         
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row--> 
    </div>
</div> 
<script> 
    
    $(document).on('click','.add-record',function(){
       add_more();
    });

     $(document).on('click','.delete_row',function(){
       var record_id=$(this).data('row-id');
       $('#fields_data'+record_id).html('');
    });

    function add_more(){
        <?php 
        $unit_listval='<option value="">'.$this->lang->line('select').'</option>';
        foreach ($unit_list as $key => $value) { 
           $unit_listval.='<option value="'.$value->id.'" >'.$value->unit.'</option>'; 
        }
        ?>
        var data_id = makeid(8);
        $('#dose_fields').append('<div class="row dosage_row" id="fields_data'+data_id+'"><div class="col-sm-5"><div class="form-group"><input autofocus="" name="dosage[]" placeholder="" type="text" class="form-control"/><span class="text-danger"><?php echo form_error('dosage'); ?></span></div></div><div class="col-sm-6"><div class="form-group"><select autofocus="" name="unit[]" placeholder="" type="text" class="form-control" ><?php echo $unit_listval; ?></select><span class="text-danger"><?php echo form_error('unit'); ?></span></div></div><div class="col-sm-1"><div class="form-group"><button type="button" class="closebtn delete_row" data-row-id="'+data_id+'" autocomplete="off"><i class="fa fa-remove"></i></button></div></div></div></div>');

    }

    function makeid(length) {
        var result = '';
        var characters = '0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    $(document).ready(function (e) {

        $(".select2").select2();
    });

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

            url: '<?php echo base_url(); ?>admin/assignpincodes/get_data/' + id,

            success: function (result) {
                $('#staffpincodeid').val(result.id);
                $('#pincode').val(result.pincode);
            
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
    function addModal(){
        $("#myModal").modal("show");
        $("div").remove(".dosage_row");
    }

   

    

    function delete_staff_pincode(id){
        delete_recordByIdReload('admin/assignpincodes/delete/'+id, '<?php echo $this->lang->line('delete_confirm'); ?>');
    }
</script>