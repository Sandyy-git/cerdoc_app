<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <?php
        $titleresult = $this->customlib->getTitleName();
        if (!empty($titleresult["name"])) {
            $title_name = $titleresult["name"];
        } else {
            $title_name = "Hospital Name Title";
        }
        ?>
        <title><?php echo $title_name; ?></title>
        <!--favican-->
        <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">

        <!-- extra added -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
        <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
        <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>
        <!-- end -->

        <!-- for autosignup -->
        <meta name="google-signin-client_id" content="446842558210-e0n6rfkcvinclkcud7lgncnauttcmpr1.apps.googleusercontent.com">
        <script src="https://accounts.google.com/gsi/client" async defer></script>
        <!-- as end -->

        <script type="text/javascript">
        var baseurl = "<?php echo base_url(); ?>";
        var chk_validate = "<?php echo $this->config->item('SHLK') ?>";
    </script>

        <style type="text/css">

            /* .inner-bg {padding: 10px 0 170px 0;}*/
            body{background: #424242;}        
            .discover{margin-top: -90px;position: relative;z-index: -1;}
            .form-bottom {box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.35); padding-bottom: 10px !important}
            .gradient{margin-top: 40px;text-align: right;padding: 10px;background: rgb(72,72,72);
                      background: -moz-linear-gradient(left, rgba(72,72,72,1) 1%, rgba(73,73,73,1) 44%, rgba(73,73,73,1) 100%);
                      background-image: linear-gradient(to right, rgba(72, 72, 72, 0.23) 1%, rgba(37, 37, 37, 0.64) 44%, rgba(73, 73, 73, 0) 100%);
                      background-position-x: initial;
                      background-position-y: initial;
                      background-size: initial;
                      background-repeat-x: initial;
                      background-repeat-y: initial;
                      background-attachment: initial;
                      background-origin: initial;
                      background-clip: initial;
                      background-color: initial;
                      background: -webkit-linear-gradient(left, rgba(72,72,72,1) 1%,rgb(73, 73, 73) 44%,rgba(73,73,73,1) 100%);
                      background: linear-gradient(to right, rgba(72, 72, 72, 0.02) 1%,rgba(37, 37, 37, 0.67) 30%,rgba(73, 73, 73, 0) 100%);
                      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#484848', endColorstr='#494949',GradientType=1 );}        
            @media (min-width: 320px) and (max-width: 991px){
                .width100{width: 100% !important;display: block !important;
                          float: left !important; margin-bottom: 5px !important;
                          border-radius: 2px !important;}
                .width50{width: 50% !important;
                         margin-bottom: 5px !important;
                         display: block !important;
                         border-radius:2px 0px 0px 2px !important;
                         float: left !important;
                         margin-left: 0px !important; }
                .widthright50{width: 50% !important;
                              display: block !important;
                              margin-bottom: 5px !important;
                              border-radius: 0px 2px 2px 0px !important;
                              float: left !important;margin-left: 0px !important;} }
            input[type="text"], input[type="password"], textarea, textarea.form-control {
                height: 40px;border: 1px solid #999;}

            input[type="text"]:focus, input[type="password"]:focus, textarea:focus, textarea.form-control:focus {border: 1px solid #424242;}

            button.btn {height: 40px;line-height: 40px;}      
            @media(max-width:767px){
                .discover{margin-top: 10px}
                .gradient {text-align: center;}
                .logowidth{padding-right:0px;}     
            }  
            @media(min-width:768px) and (max-width:992px){
                .discover{margin-top: 10px}
                .logowidth{padding-right:0px;} 
                .gradient {text-align: center;}  
            }

            .bgwhite{ background: #e4e5e7;
                      box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.5);overflow: auto;border-radius: 6px;}


            label.radio-inline{font-size: 14px; line-height: 30px;}
            .radio-inline input[type=radio]{position: absolute; margin-top: 8px; outline: none;}
            .backstretch{position: relative;}
            .backstretch:after {
                position: absolute;
                z-index: 2;
                width: 100%;
                height: 100%;
                display: block;
                left: 0;
                top: 0;
                content: "";
                background-color: rgba(16, 16, 16, 0.70);
            }
        </style>
    </head>
    <body>
        <!-- Top content -->
        <?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
$marital_status = $this->config->item('marital_status');
?>

        <div class="top-content">
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <?php
                            $logoresult = $this->customlib->getLogoImage();
                            if (!empty($logoresult["image"])) {
                                $logo_image = base_url() . "uploads/hospital_content/logo/" . $logoresult["image"];
                            } else {
                                $logo_image = base_url() . "uploads/hospital_content/logo/s_logo.png";
                            }
                            if (!empty($logoresult["mini_logo"])) {
                                $mini_logo = base_url() . "uploads/hospital_content/logo/" . $logoresult["mini_logo"];
                            } else {
                                $mini_logo = base_url() . "uploads/hospital_content/logo/smalllogo.png";
                            }
                            ?>
                            <div class="">
                                <img src="<?php echo $logo_image; ?>" class="logowidth">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                            <div class="signupbg">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3 class="font-white"><?php echo $this->lang->line('patient_signup'); ?></h3>

                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-key"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <?php
                                    if (isset($error_message)) {
                                        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                    }
                                    ?>
<section class="login-block">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">


                            <div class="form-group form-primary">
                            <div id="g_id_onload"
         data-client_id="446842558210-e0n6rfkcvinclkcud7lgncnauttcmpr1.apps.googleusercontent.com"
         data-callback="handleCredentialResponse" class="center">
    </div>
    <div class="g_id_signin center" data-type="standard"></div>


                            </div>

                            

                <div class="or-container ajaxlist text-center" style="color:#cee8ff"><div class="line-separator"></div> <div class="or-label">or</div><div class="line-separator"></div></div>


                <form id="formaddpa" accept-charset="utf-8" action="" enctype="multipart/form-data" method="post"> 
<!-- //na -->
                <input id="lat" name="latitude" type="hidden" value=" ">
                <span class="text-danger"><?php echo form_error('latitude'); ?></span>

                <input id="long" name="longitude" type="hidden" value=" ">
                <span class="text-danger"><?php echo form_error('longitude'); ?></span>
<!--na end -->
                    <div class="auth-box card">
                        <div class="card-block">
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center heading" >Patient Signup</h3>
                                    
                                </div>
                            </div> -->
                            <div class="form-group form-primary">
                            <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small> 
                                                <input id="name" name="name" placeholder="" type="text" class="form-control name_append"  value="<?php echo set_value('name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                 <!-- <input type="text" class="form-control" name="first_name" value="" placeholder="Display name" id="first_name">  -->
                            </div>

                            <div class="form-group form-primary">
                            <label for="dob"><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                            <input type="text" name="dob" id="birth_date" placeholder=""  class="form-control date patient_dob disableFuturedate" /><?php echo set_value('dob'); ?>
                                <!-- <input type="text" class="form-control" name="email" value="" placeholder="Email" id="email"> -->
                               
                            </div>

                          

                            <div class="form-group form-primary" id="calculate">
                            <label><?php echo $this->lang->line('age').' ('.$this->lang->line('yy_mm_dd').')'; ?> </label><small class="req"> *</small> 
                                                        <div style="clear: both;overflow: hidden;">
                                                            <input type="text" placeholder="<?php echo $this->lang->line('year'); ?>" name="age[year]" id="age_year" value="" class="form-control patient_age_year" style="width: 30%; float: left;">

                                                            <input type="text" id="age_month" placeholder="<?php echo $this->lang->line('month'); ?>" name="age[month]" value="" class="form-control patient_age_month" style="width: 36%;float: left; margin-left: 4px;">
                                                             <input type="text" id="age_day" placeholder="<?php echo $this->lang->line('day'); ?>" name="age[day]" value="" class="form-control patient_age_day" style="width: 26%;float: left; margin-left: 4px;">
                                                        </div>
                               <!-- <input type="password" class="form-control" name="password" placeholder="Password" value="" id="password"> -->
                              
                            </div>

                            <div class="form-group form-primary">
                            <label> <?php echo $this->lang->line('gender'); ?></label>
                                                        <select class="form-control" name="gender" id="addformgender">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($genderList as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                <!-- <input type="password" class="form-control" name="password_confirm" placeholder="Repeat password" value="" id="password_confirm"> -->
                                
                            </div>

                            <div class="form-group form-primary">

                            <label for="pwd"><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                                                <input id="number" autocomplete="off" name="mobileno"  type="text" placeholder="" class="form-control"  value="<?php echo set_value('mobileno'); ?>" />
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                            </div>
                            <div class="form-group form-primary">
                            <label><?php echo $this->lang->line('email'); ?></label>
                                                <input type="text" placeholder="" id="addformemail" value="<?php echo set_value('email'); ?>" name="email" class="form-control email_append">
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                            </div>

                            <div class="form-group form-primary" style="width:100px;height:100px">
                                 <img src="" class="img_append">
                                                        </div>

                            <div class="form-group form-primary">
                            <label for="exampleInputFile">
                                                            <?php echo $this->lang->line('patient_photo'); ?>
                                                        </label>
                                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' data-height="26" />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                <button type="submit" id="formaddpabtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('sign_up'); ?></button>

                                    <!-- <input type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20" name="submit" value="Signup Now"> -->
                                   <!--  <button type="button" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20"><i class="fa fa-lock"></i> Signup Now </button> -->
                                </div>
                            </div>



                            <!-- <div class="row">
                                <div class="col-md-12">
                                  <a class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="#"><img src="https://img.icons8.com/color/16/000000/google-logo.png"> Signup Using Google</a>

                                </div>
                            </div> -->
                            <br>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <a href="<?php echo site_url('site/userlogin') ?>" class="forgot"><i class="fa fa-key"></i> <?php echo $this->lang->line('user_login'); ?></a>

</section>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Javascript -->
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>


        <script type= "text/javascript" src= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel= "stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <script type="text/javascript">
    $(document).ready(function () {
        var base_url = '<?php echo base_url(); ?>';
        $.backstretch([
            base_url + "backend/usertemplate/assets/img/backgrounds/user15.jpg"
        ], {duration: 3000, fade: 750});
        $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
            $(this).removeClass('input-error');
        });
        $('.login-form').on('submit', function (e) {
            $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
                if ($(this).val() == "") {
                    e.preventDefault();
                    $(this).addClass('input-error');
                } else {
                    $(this).removeClass('input-error');
                }
            });
        });
    });


    
</script>

<!-- <script>
$(document).ready(function () {
      var currentDate = new Date();
      $('.disableFuturedate').datepicker({
      format: 'dd/mm/yyyy',
      autoclose:true,
      endDate: "currentDate",
      maxDate: currentDate
      }).on('changeDate', function (ev) {
         $(this).datepicker('hide');
      });
      $('.disableFuturedate').keyup(function () {
         if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9^-]/g, '');
         }
      });
   });
    </script> -->

<script >
$ (document).ready( function() {
$ ("#birth_date") .datepicker();
});
</script>

<script type="text/javascript">
   $(".patient_dob").on('changeDate', function(event, date) {     
       var birth_date = $(".patient_dob").val();   
    //    alert(birth_date);    
        $.ajax({
            url: '<?php echo base_url(); ?>site/getpatientage',
            type: "POST",
            dataType: "json",
            data: {birth_date:birth_date},
            success: function (data) {
                alert(data);
              $('.patient_age_year').val(data.year); 
              $('.patient_age_month').val(data.month);
              $('.patient_age_day').val(data.day);
            }
       });
    });
//
    $(".newpatient").click(function(){	
	$('#formaddpa').trigger("reset");
	$(".dropify-clear").trigger("click");
});	

//
$(document).ready(function (e) {
        $("#formaddpa").on('submit', (function (e) {
            alert("add pat");
        let clicked_submit_btn= $(this).closest('form').find(':submit');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>site/addpatient',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                 beforeSend: function() {
                 clicked_submit_btn.button('loading') ; 
                },
                success: function (data) {
                    console.log(data);
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                       
                        errorMsg(message);
                        // $('.ajaxlist').DataTable().ajax.reload();
                        $('#myModal').modal('hide');
                        // window.location.reload(true);

                    } else {
                        successMsg(data.status);                        
                        // window.location.reload(true);
                    }
                        clicked_submit_btn.button('reset') ;                   
                },
                 error: function(xhr) { // if error occured
        alert("Error occured.please try again");
         clicked_submit_btn.button('reset') ; 
             },
    complete: function() {
     clicked_submit_btn.button('reset') ; 
    }
            });
        }));
    });

    
</script>
<script>
function handleCredentialResponse(response) {
     // decodeJwtResponse() is a custom function defined by you
     // to decode the credential response.
    //  console.log(response);
     console.log(response.credential);
     //Decode the encoded jwt token
    const decode =  decodeURIComponent(atob(response.credential.split('.')[1].replace('-', '+').replace('_', '/')).split('').map(c => `%${('00' + c.charCodeAt(0).toString(16)).slice(-2)}`).join(''));
    const parseDecode = JSON.parse(decode);
    //  console.log("ID: " + parseDecode.sub);
    //  console.log('Full Name: ' + parseDecode.name);
    //  console.log('Given Name: ' + parseDecode.given_name);
    //  console.log('Family Name: ' + parseDecode.family_name);
    //  console.log("Image URL: " + parseDecode.picture);
    //  console.log("Email: " + parseDecode.email);

     $('.name_append').val(parseDecode.name); 
     $('.email_append').val(parseDecode.email);
     $('.img_append').attr('src', parseDecode.picture)

  }
</script>
<script>
    // MAP TO COLLECT COORDINATES
// $('#role').on("change", loadMap);
$( document ).ready(
function loadMap()
{
    console.log("reqached");
    // var ddl = document.getElementById("role");
    // var selectedValue = ddl.options[ddl.selectedIndex].value;
    // if (selectedValue == 3)
    // {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById("lat").value = position.coords.latitude;
                document.getElementById("long").value = position.coords.longitude;

            });

        } else {
            document.getElementById("lat").value = "18";
            document.getElementById("long").value = "73";

        }

    // }
    // else{
    //     document.getElementById("lat").value = "";
    //     document.getElementById("long").value = "";
    // }
});

</script>
<script>

    function readURL(input) {
        alert("yes");
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('.img_append').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file").change(function(){
        readURL(this);
    });
    </script>

   

    </body>


</html>
