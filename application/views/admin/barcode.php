<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 13px;}
</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>

</head>
<body onload="window.print();">
	<div style="margin-left: 5%">
		<?php
        // print_r($postData); die;
        require_once(APPPATH."views/admin/barcode128.php");

		// include 'barcode128.php';
		$product = $postData['product'];
		$product_id = $postData['product_id'];
		$rate = $postData['rate'];

		for($i=1;$i<=$postData['print_qty'];$i++){
			echo "<p class='inline'><span ><b>Item: $product</b></span>".bar128(stripcslashes($postData['product_id']))."<span ><b>Price: ".$rate." </b><span></p>&nbsp&nbsp&nbsp&nbsp";
			// echo "<p class='inline'><span ><b>Item: $product</b></span>".' '."<span ><b>Price: ".$rate." </b><span></p>&nbsp&nbsp&nbsp&nbsp";

		}

		?>
	</div>
</body>
</html>

