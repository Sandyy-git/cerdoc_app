<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
    </style>
</head>
<!-- <body> -->
<body onload="window.print();">

    <?php if(isset($barcode_image)){
        ?>
        <div class="container">
        <div class="row">
        <div class="col-md-12">

        <div class="text-center">sas</div>

        <img class="center" src="<?php echo base_url(); ?>uploads/barcode/<?php echo $barcode_image; ?>">
        <div class="text-center">sas</div>
    </div>
        <?php
    }
   ?>
</body>
</html>