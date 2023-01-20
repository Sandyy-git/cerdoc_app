<style>
/* #abc > tbody > th:nth-child(1)  {
    color: red !important;
} */

#abc > tbody > tr > td:nth-child(2)  {
    color: #b70a80 !important;
}
#abc > tbody > tr > td:nth-child(3)  {
    color: #1db136 !important;
}
 
</style>
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> Staff credentials Details </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="abc"  class="table table-striped table-bordered table-hover ajaxlist" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('patient_login_credential'); ?>">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Clinic Name</th>
                                <th>Created By</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-right">Password</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- //========datatable start===== -->
<script type="text/javascript">
    ( function ( $ ) {

        'use strict';
        $(document).ready(function () {
            initDatatable('ajaxlist','admin/staff/getcredentialdatatable',[],[],100);
        });
    } ( jQuery ) )
</script>
<!-- //========datatable end===== -->
