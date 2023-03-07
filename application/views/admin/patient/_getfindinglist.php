<?php
if (!empty($finding_list)) {
    foreach ($finding_list as $finding_key => $finding_value) {
        ?>
             <li class='option'><label class='checkbox'><input type='checkbox' id="<?php echo $finding_value->name; ?>" name='finding_title' value='<?php echo $finding_value->name."\n".$finding_value->description ; ?>'> <?php echo $finding_value->name ?></label></li>
        <?php
    }
}
?>
<script type="text/javascript">
	
	$("input[name=finding_title]").change(function() {
  updateAllChecked();
});

$("input[name=addall]").change(function() {
  alert('all');
  if (this.checked) {
    $("input[name=finding_title]").prop('checked', true).change();
  } else {
    $("input[name=finding_title]").prop('checked', false).change();
  }
});

// function updateAllChecked() {
//   $('#finding_description').val('');    //important 
//   $("input[name=finding_title]").each(function() {
//     if (this.checked) {
//       let old_text = $('#finding_description').val() ? $('#finding_description').val() + '\n\n' : '';
//      // let eold_text = $('#esymptoms').val() ? $('#esymptoms').val() + '\n\n' : '';
//       $('#finding_description').val(old_text + $(this).val());
//       $('#efinding_description').val(old_text + $(this).val());

//     }
//   })
// }

// var array = [];



function updateAllChecked() {
  // console.log(array.length);
  var cat = $("#finding_type :selected").text(); 
  var arr = [];
  // console.log(arr);  
  i = 0;
  // $('#finding_description').val('');    //important 
  $("input[name=finding_title]").each(function() {
    if (this.checked) {
      let old_text = $('#finding_description').val() ? $('#finding_description').val() + '\n\n' : '';
      // alert(old_text);
    
      arr[i++] = cat+"-"+$(this).val() ;

  // if (!array.includes($(this).val())) {
  //   array.push($(this).val());
  // }


      
    }
  })
  // console.log(arr);
  $('#finding_description').val(arr);
  $('#efinding_description').val(arr);
}
</script>