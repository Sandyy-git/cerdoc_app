<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php 
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
 ?>
<div class="print-area">
  <?php 
$discont_amt=calculatePercent($blood_issues_detail['amount'], $blood_issues_detail['discount_percentage']);
$total_discount_amount = $blood_issues_detail['amount'] - $discont_amt;
$tax_amt=calculatePercent($total_discount_amount, $blood_issues_detail['tax_percentage']);
   ?>

<div class="row">
        <div class="col-12">
           <?php if (!empty($print_details['print_header'])) { ?>
                        <div class="pprinta4">
                            <img src="<?php
                            if (!empty($print_details['print_header'])) {
                                echo base_url() . $print_details['print_header'].img_time();
                            }
                            ?>" class="img-responsive" style="height:100px; width: 100%;">
                        </div>
                    <?php } ?>
              <div class="card">
                <div class="card-body">  
                    <div class="row"> 
                        <div class="col-md-6">
                            
                            <p><?php echo $this->lang->line('bill_no'); ?> : <?php echo $prefix.$blood_issues_detail['id']; ?></p>
                            <p><?php echo $blood_issues_detail['patient_name']." (".$blood_issues_detail['patient_id'].")"; ?></p>
                            <p><?php echo $this->lang->line('case_id'); ?> : <?php echo $blood_issues_detail['case_reference_id']; ?></p>
                            <p><?php echo $this->lang->line('blood_group'); ?> : <?php echo $blood_issues_detail['blood_group']; ?></p>
                            <p><?php echo $this->lang->line('bag'); ?> : <?php echo $this->customlib->bag_string($blood_issues_detail['bag_no'],$blood_issues_detail['volume'],$blood_issues_detail['unit']); ?></p>

                        <?php    if (!empty($fields)) {
                              foreach ($fields as $fields_key => $fields_value) {

                                  $display_field = $blood_issues_detail["$fields_value->name"];
                                  if ($fields_value->type == "link") {
                                      $display_field = "<a href=" . $blood_issues_detail["$fields_value->name"] . " target='_blank'>" . $blood_issues_detail["$fields_value->name"] . "</a>";

                                  }
                                   ?>
                           
                                  <p><?php echo $fields_value->name; ?> : <?php echo $display_field; ?></p> 
                                <?php  }
                              } ?>

                        </div>

                        <div class="col-md-6 text-right">
                            
                              <p><span class="text-muted"><?php echo $this->lang->line('date'); ?>: </span> <?php echo $this->customlib->YYYYMMDDHisTodateFormat($blood_issues_detail['date_of_issue'], $this->customlib->getHospitalTimeFormat()); ?></p>                       
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="print-table">
                             <thead>
                                <tr class="line">
                                   <td><strong>#</strong></td>
                                   <td class="text-left"><strong><?php echo $this->lang->line('description'); ?></strong></td>
                                 
                                   <td class="text-center"><strong><?php echo $this->lang->line('tax'); ?> (%)</strong></td>
                                   <td class="text-right"><strong><?php echo $this->lang->line('amount').' ('. $currency_symbol .')'; ?></strong></td>
                                </tr>
                             </thead>
                             <tbody>
                                <tr>
                                   <td>1</td>
                                   <td><strong><?php echo $blood_issues_detail['charge_category_name'];?></strong><br>
                                    </td>

                                      <td class="text-center">
                                       
                  <?php echo calculatePercent($blood_issues_detail['amount'], $blood_issues_detail['tax_percentage']);?><br>
                                    </td>
                                 
                                   <td class="text-right"><?php echo $blood_issues_detail['amount'] ?></td>
                                </tr>
                                
                                 
                                <tr>
                                   <td colspan="2" class="thick-line"></td>
                                   <td class="text-right thick-line"><strong><?php echo $this->lang->line('total'); ?></strong></td>
                                   <td class="text-right thick-line"><strong><?php echo $currency_symbol . "" . amountFormat($blood_issues_detail['amount']); ?></strong></td>
                                </tr>
                                <tr>
                                   <td colspan="2" class="no-line"></td>
                                   <td class="text-right no-line"><strong><?php echo $this->lang->line('discount'); ?></strong></td>
                                   <td class="text-right no-line"><strong><?php echo "(".$blood_issues_detail['discount_percentage']."%) ".$currency_symbol . "" . $discont_amt; ?></strong></td>
                                </tr>
                                <tr>
                                   <td colspan="2" class="no-line"></td>
                                   <td class="text-right no-line"><strong><?php echo $this->lang->line('tax'); ?></strong></td>
                                   <td class="text-right no-line"><strong><?php echo "(".$blood_issues_detail['tax_percentage']."%) ".$currency_symbol . "" . $tax_amt; ?></strong></td>
                                </tr>
                                <tr>
                                   <td colspan="2" class="no-line"></td>
                                   <td class="text-right no-line"><strong><?php echo $this->lang->line('paid'); ?></strong></td>
                                   <td class="text-right no-line"><strong><?php echo $currency_symbol . "" . amountFormat($blood_issues_detail['total_deposit']); ?></strong></td>
                                </tr>
                                  <tr>
                                   <td colspan="2" class="no-line"></td>
                                   <td class="text-right no-line"><strong><?php echo $this->lang->line('total_due'); ?></strong></td>
                                   <td class="text-right no-line"><strong>
<?php echo $currency_symbol . "" . amountFormat(($blood_issues_detail['amount']-$discont_amt)+$tax_amt-$blood_issues_detail['total_deposit']); ?>
                                   </strong></td>
                                </tr>
                             </tbody>
                          </table>
                        </div>
                    </div>

              
                </div>
            </div>
            <div class="clear">
            <p>
                        <?php
                        if (!empty($print_details['print_footer'] )) {
                            echo $print_details['print_footer'] ;
                        }
                        ?>                          
                        </p>
        </div>
        </div>
    </div>
    </div>