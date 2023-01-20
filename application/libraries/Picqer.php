<?php

// defined('BASEPATH') or exit('No direct script access allowed');
// use Picqer\Barcode\BarcodeGeneratorPNG;

// require_once APPPATH . 'third_party/omnipay/vendor/autoload.php';
// require_once APPPATH . 'third_party/omnipay/vendor/firebase/php-jwt/src/JWT.php';
// include_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

// include_once(APPPATH . 'third_party/picqer/autoload.php');

class Picqer
{
    public $CI;


    public function __construct()
    {
        $this->CI = &get_instance();
        // $this->zz    = new BarcodeGeneratorPNG();
        $this->zz    = new Picqer\Barcode\BarcodeGeneratorPNG;


    }

     function set_barcode(){
        
        $generator = $this->zz; 
        // var_Dump($generator ); die;
        $redColor = [50,49,46]; 
        $text = "SAny";

        // echo "<p class='inline'><span ><b>Item: $product</b></span>".bar128(stripcslashes($_POST['product_id']))."<span ><b>Price: ".$rate." </b><span></p>&nbsp&nbsp&nbsp&nbsp";
        echo $generator->getBarcode($text, $generator::TYPE_CODE_128, 3, 50, $redColor);
        // file_put_contents('writable/uploads/barcode.png', $generator->getBarcode($text, $generator::TYPE_CODE_128, 3, 50, $redColor)); 
    }
}
?>