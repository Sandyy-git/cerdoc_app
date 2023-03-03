<?php

class Prescription extends Patient_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->load->library('Enc_lib');
        $this->load->library('Customlib');
        $this->marital_status = $this->config->item('marital_status');
        $this->payment_mode   = $this->config->item('payment_mode');
        $this->blood_group    = $this->config->item('bloodgroup');

        $this->opd_prefix = $this->prefix_model->getByCategory(array('opd_no'))[0]->prefix;


        $this->load->model('prefix_model');
        $this->load->model('finding_model');

    }

    public function getPrescription($visitid)
    {
       
        $result     = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $data["result"]        = $result;
        $data["id"]            = $visitid;
        $data["opd_id"]        = $result->opd_detail_id;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $this->load->view("patient/prescription", $data);
    }

    public function getPrescriptionmanual($visitid)
    {
        $result                   = $this->prescription_model->getmanual($visitid);
        $opddata                  = $this->patient_model->getopdvisitDetailsbyvisitid($visitid);
        $opdid                    = $opddata['opdid'];
        $data['blood_group_name'] = $opddata['blood_group_name'];

        // $result     = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $data["result"]        = $result;
        $data["visitid"]            = $visitid;
        // $data["opd_id"]        = $result->opd_detail_id;
        $data["opdid"]            = $opdid;

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $data['opd_prefix'] = $this->opd_prefix;

        $this->load->view("patient/prescriptionmanual", $data);
    }

    public function getPrescriptionSend($visitid)
    {
       
        $result     = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $data["result"]        = $result;
        $data["id"]            = $visitid;
        $data["opd_id"]        = $result->opd_detail_id;
        $patient_id = $this->customlib->getUsersID(); 
        $patient_data = $this->patient_model->getpatientbyid($patient_id); 
        $pat_locality  = $patient_data['locality_id'];
        $data['chemist_locality']  = $this->patient_model->getchemistlistLocality($pat_locality);
        $data['chemist']       = $this->patient_model->getchemistlist($pat_locality);

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $this->load->view("patient/prescriptionsend", $data);
    }

    public function getPrescriptionSendManual($visitid)
    {
       
        $result     = $this->prescription_model->getPrescriptionByVisitID($visitid);
        $data["print_details"] = $this->printing_model->getheaderfooter('opdpre');
        $data["result"]        = $result;
        $data["id"]            = $visitid;
        $data["opd_id"]        = $result->opd_detail_id;
        $patient_id = $this->customlib->getUsersID(); 
        $patient_data = $this->patient_model->getpatientbyid($patient_id); 
        $pat_locality  = $patient_data['locality_id'];
        $data['chemist_locality']  = $this->patient_model->getchemistlistLocality($pat_locality);
        $data['chemist']       = $this->patient_model->getchemistlist($pat_locality);

        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $this->load->view("patient/prescriptionsendmanual", $data);
    }

    public function sendPrescription(){
        $chemist = $this->input->post('chemist'); 
        $visitId = $this->input->post('visitid'); 
        $patient_id = $this->customlib->getUsersID(); 
        $data1 = array();
        foreach($chemist as $chem){
            $details = array(
                'send_to' => $chem,
                'visit_id' => $visitId,
                'patient_id' => $patient_id
            );

            $chk_existanceof_sendprestochemist = $this->prescription_model->checkExisofSendPrestoChemist($details);
            if ($chk_existanceof_sendprestochemist == 0) {
                $data1[] = $details;
            }else{
                $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('prescription_record_exist'));
            }
        }
        $chem_data = $chem_array;
        $insertId = $this->prescription_model->addSendPrestoChemist($data1);
        if($insertId){
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        // var_dump($insertId); die;
        echo json_encode($array);
    }

    public function sendPrescriptionManual(){
        $chemist = $this->input->post('chemist_man'); 
        $visitId = $this->input->post('visitid'); 
        $patient_id = $this->customlib->getUsersID(); 
        $data1 = array();
        foreach($chemist as $chem){
            $details = array(
                'send_to' => $chem,
                'visit_id' => $visitId,
                'patient_id' => $patient_id,
                'manual_prescription' => 'yes'
            );

            $data1[] = $details;
        }

        $chem_data = $chem_array;
        $insertId = $this->prescription_model->addSendPrestoChemist($data1);
    }


    public function getChemistByPincode(){
        $pat_pincode = $this->input->post('pincode');
        $result  = $this->patient_model->getchemistlistByPincode($pat_pincode);
        echo json_encode($result);
        
    }

    public function getIPDPrescription($id, $ipdid)
    {
        $result                = $this->prescription_model->getPrescriptionByTable($id,'ipd_prescription');
        $data["print_details"] = $this->printing_model->getheaderfooter('ipdpres');
        $data["result"]        = $result;
        $data["id"]            = $id;
        $data["ipdid"]         = $ipdid;
        if (isset($_POST['print'])) {
            $data["print"] = 'yes';
        } else {
            $data["print"] = 'no';
        }
        $this->load->view("patient/ipdprescription", $data);
    }
}
