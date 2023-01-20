<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body> -->
    <style>
.center{
   position:absolute;
   left:50%;
   top:50%;
   transform:translate(-50%, -50%);

   color: #cd8d42;
    font-size: xx-large;

    box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  width: 300px;
  height: 300px;
  border: 2px solid #969696;
  background: #d9dbda;
  margin: 2px;
  text-align: center;   
}

/* div {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  width: 120px;
  height: 120px;
  border: 20px solid #969696;
  background: #d9dbda;
  margin: 10px;
} */
        </style>
<div class="content-wrapper ">
<div class="card bg-dark text-white center">  
<div class="card-body" style="margin-top: 30%;">


    <!-- Group of default radios - option 1 -->
<!-- <div class="custom-control custom-radio">
  <input type="radio" class="custom-control-input" id="defaultGroupExample1" name="groupOfDefaultRadios">
  <label class="custom-control-label" for="defaultGroupExample1">COD</label>
</div> -->

<!-- Group of default radios - option 2 -->
<!-- <div class="custom-control custom-radio">
  <input type="radio" class="custom-control-input" id="defaultGroupExample2" name="groupOfDefaultRadios" checked>
  <label class="custom-control-label" for="defaultGroupExample2">Online</label>
</div> -->

<!-- Group of default radios - option 3 -->
<!-- <div class="custom-control custom-radio">
  <input type="radio" class="custom-control-input" id="defaultGroupExample3" name="groupOfDefaultRadios">
  <label class="custom-control-label" for="defaultGroupExample3">Option 3</label>
</div> -->
<form action="#" method="post" >
                                        <div >
                                            <button type="button" onclick="cod()" class="btn btn-primary submit_button"><i class="fa fa fa-money"></i> <?php echo $this->lang->line('cod') ?></button>
                                        </div> 
                                </form>

<form action="#" method="post" >
                                        <div >
                                            <button type="button" onclick="pay()" class="btn btn-primary submit_button"><i class="fa fa fa-money"></i> <?php echo $this->lang->line('make_payment') ?></button>
                                        </div> 

                                </form>

</div>
</div>
</div>
<!--     
</body>
</html> -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
<script>
    var SITEURL = "<?php echo base_url() ?>";

    function cod(){
        // alert('cod');
        var totalAmount = <?php echo $total; ?>;
        if(totalAmount != 0){
                $.ajax({
                    url: '<?php echo $return_url; ?>',
                    type: 'post',
                    data: {
                        razorpay_payment_id: "",
                    },
                    success: function (msg) {

                        window.location.assign(SITEURL + 'patient/pay/codinvoice/')
                    }
                });
            }else{
                alert("Invalid amount,payable amount should be grater than 0");
            }

            }

           
    function pay(e) {
        alert('pay');
    var totalAmount = <?php echo $total; ?>;
    var product_id = <?php echo $merchant_order_id; ?>;
    var options = {
            "key": "<?php echo $key_id; ?>",
            "amount": "<?php echo $total; ?>", // 2000 paise = INR 20
            "name": "<?php echo $name; ?>",
            "description": "<?php echo $title; ?>",
            "currency": "<?php echo $currency; ?>",
            "image": "",
            "handler": function (response) {
                alert(response);
                $.ajax({
                    url: '<?php echo $return_url; ?>',
                    type: 'post',
                    data: {
                        razorpay_payment_id: response.razorpay_payment_id, totalAmount: totalAmount, product_id: product_id,
                    },
                    success: function (msg) {

                        window.location.assign(SITEURL + 'patient/pay/successinvoice/')
                    }
                });

            },

            "theme": {
                "color": "#528FF0"
            }
        };
        console.log(options);
        var rzp1 = new Razorpay(options);
        rzp1.open();                           //rpay screen initiation contact details

    }



    </script>