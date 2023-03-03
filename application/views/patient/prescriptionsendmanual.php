<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>

<div class="print-area">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $this->lang->line('prescription'); ?></title>
    </head>


    <div class="row">
            <div class="col-md-4">
                    <input type="search" name="pincode" class="form-control" id="pincode_manual" placeholder="Search for pincode" text-align="center">
                </div>
</div>

    <div id="html-2-pdfwrapper">
    
        <div class="row">
            <!-- left column -->
           
                <!-- <button type="button" class="btn btn-primary" style="left:34%" id="searchpincodebtn">
    <i class="fas fa-search"></i>
  </button> -->
 
                
                    

<div>
                <form id="formsendPresMan" accept-charset="utf-8" enctype="multipart/form-data" method="post"  class="ptt10">
                <div>
                <div id='loc_staff_man'>
                <?php 
                if(!empty($chemist_locality)){
                    foreach($chemist_locality as $key => $cl_value) {
                        if($key == 0){
                        
                        ?>
                        
                            <div><?php echo $cl_value['name']; ?><input type="checkbox"  name="chemist_man[]" value="<?php echo $cl_value['id']; ?>" checked></div>
                        <?php }else{
                            ?>
                            <div><?php echo $cl_value['name']; ?><input type="checkbox" name="chemist_man[]" value="<?php echo $cl_value['id']; ?>" ></div>
                           
                            <?php
                        } ?>
                        
                        <?php
                    }
                }
            
                ?>
 </div>

               <div id='my_staff_man'>
                <?php 
                if(!empty($chemist)){
                    foreach($chemist as $key => $ch_value) {
                        
                        
                        ?>
                        
                        <?php 
                            ?>
                            <div><?php echo $ch_value['name']; ?><input type="checkbox" name="chemist_man[]" value="<?php echo $ch_value['id']; ?>" ></div>
                    
                            <?php
                        } }?>
                        </div>
                       





<input type="hidden" name="visitid" value="<?php echo $id; ?>">
<div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>" id="formsendPresbtnMan" class="btn btn-info pull-right"><?php echo $this->lang->line('send'); ?>
                                    </button>
                                </div>
                            </div>
                    </div>
            </form>


            </div>
                
                                      
                  
                </div>
            <!--/.col (left) -->
        </div>
    </div>
</div>

<script>
        $(document).ready(function (e) {
        $("#formsendPresMan").on('submit', (function (e) {
            alert('ds');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>patient/prescription/sendPrescriptionManual/',
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
                    $("#formsendPresbtnMan").button('reset');
                },
                error: function () {

                }
            });
        }));
    });
</script>
<script>
//   $(document).ready(function (e) {
    
//         $("#searchpincodebtn").on('click', (function (e) {
//             var pincode = $("#pincode").val();
//             var div_data ='';
//             $.ajax({
//             url: '<?php echo base_url(); ?>patient/prescription/getChemistByPincode/',
//             type: 'POST',
//             data: {pincode: pincode},
//             success: function (res) {
//                 var parsedData = JSON.parse(res);
//                 $("#loc_staff").html('');
//                 $("#my_staff").html('');
//                 $.each(parsedData, function(i, item) {
//                     div_data += '<div> '+item.name +' <input type="checkbox"  name="chemist[]" value='+item.id+' ></div>';
//                 });
//                 $("#loc_staff").append(div_data);
            
                
//             }
//             });
//         }));
//     });
    </script>

<script>
  $(document).ready(function (e) {
    
        $("#pincode_manual").on('keyup', (function (e) {
            var pincode = $("#pincode_manual").val();
           
            var div_data ='';
            $.ajax({
            url: '<?php echo base_url(); ?>patient/prescription/getChemistByPincode/',
            type: 'POST',
            data: {pincode: pincode},
            success: function (res) {
                var parsedData = JSON.parse(res);
                $("#loc_staff_man").html('');
                $("#my_staff_man").html('');
                $.each(parsedData, function(i, item) {
                    div_data += '<div> '+item.name +' <input type="checkbox"  name="chemist_man[]" value='+item.id+' ></div>';
                });
                $("#loc_staff_man").append(div_data);
            
                // popup(result);
            }
            });
        }));
    });
    </script>