<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Patientwise_product_report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('transaction_model'));
    }

    public function printTransaction()
    {
        $print_details         = $this->printing_model->get('', 'paymentreceipt');
        $id                    = $this->input->post('id');
        $transaction           = $this->transaction_model->getTransaction($id);
        $data['transaction']   = $transaction;
        $data['print_details'] = $print_details;
        $page                  = $this->load->view('admin/transaction/_printTransaction', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function deleteByID()
    {
        $id          = $this->input->post('id');
        $transaction = $this->transaction_model->delete($id);
        $array       = array('status' => 'success', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function download($trans_id)
    {
        $transaction = $this->transaction_model->getTransaction($trans_id);
        $this->load->helper('download');
        $filepath    = "./uploads/payment_document/" . $transaction->attachment;
        $report_name = $transaction->attachment_name;
        $data        = file_get_contents($filepath);
        force_download($report_name, $data);
    }

    //TRANS REPOST CHANGES
    public function patwiseproductreport()
    {
        // var_dump($this->input->post()); die;
        if (!$this->rbac->hasPrivilege('patientwise_product_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/patwisereport/patwiseproductreport');
        $data['title'] = 'title';
        $data['patient'] = $this->patient_model->getAllPatient();
        $data['locality'] = $this->staff_model->getStaffLocality();

        $this->form_validation->set_rules('doctorid[]', $this->lang->line('doctor'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('patientid', $this->lang->line('patient'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_from', $this->lang->line('date_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_to', $this->lang->line('date_to'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'doctorid[]' => form_error('doctorid[]'),
                'patientid' => form_error('patientid'),
                'date_from' => form_error('date_from'),
                'date_to'   => form_error('date_to'),
            );
            $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $doctorid = $this->input->post('doctorid[]');
            $patientid = $this->input->post('patientid');
            $date_from = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date_from')); //2022-12-27
            $date_to   = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date_to'));

            $role                        = $this->customlib->getStaffRole();
            $role_id                     = json_decode($role)->id;
            // var_dump($role_id); die;
            $loginuserId = $this->customlib->getLoggedInUserID();  //sa-1,trinity-94,next - 104

            if($role_id == 73){    //FOR CENTRAL PHARMA ADMIN
                $reportdata = $this->patient_model->getPatwiseProReport($date_from, $date_to,$doctorid,$patientid);
            }elseif($role_id == 7){    //FOR SUPER ADMIN
                $reportdata = $this->patient_model->getPatwiseProReport($date_from, $date_to,$doctorid,$patientid);
            }else{
                $reportdata = $this->patient_model->getPatwiseProReport($date_from, $date_to,$doctorid,$patientid);
            }

            $dt_data = array();

             if (!empty($reportdata)) {
                foreach ($reportdata as $key => $value) {

                $row                        = array();
                $row['date']  = $value->pharm_bill_date;
                $row['product_id'] = $value->product_id;
                $row['product_name'] = $value->medicine_name;
                $row['company_name'] = $value->medicine_company;
                $row['doctor']   = $value->name;
                $row['patient'] = $value->patient_name;
                $row['quantity'] = $value->quantity;
                $row['valuep'] = round($value->valuep);
                $row['tax_cgst']  = $value->sbbcgst;
                $row['tax_sgst']  = $value->sbbsgst;
                $row['tax']  = $value->sbbtax;
                $row['purchase_amount']  = $value->sale_rate;
                $dt_data[]  = $row;
                }
                $data['result'] = $dt_data;
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/patwisereport/patwiseproductreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getpurchasetransactionbydate()
    {
        if (!$this->rbac->hasPrivilege('purchase_report', 'can_view')) {
            access_denied();
        }

         // $userId = $this->customlib->getUsersID();     //sa - '',tri - 1,next -''
         $staffRoleId = $this->customlib->getStaffRole();  //{"id":"7","name":"Super Admin"}, {"id":"69","name":"trinity_role"},{"id":"70","name":"next_role"}
         $loginuserData = $this->customlib->getLoggedInUserData();  //own id and roles - 7,69,70
         $loginuserId = $this->customlib->getLoggedInUserID();  //sa-1,trinity-94,next - 104
         $r_id = json_decode($staffRoleId)->id;

         $transstaffArray = array();
            $transStaffs = $this->transaction_model->getStaffs($r_id);
            if(!empty($transStaffs)){
                foreach ($transStaffs as $key => $staff_cby) {
                    $transstaffArray[] = $staff_cby['id'];
                }
            }

        $date          = $this->input->post('date');
        // var_dump($date); die;
        $data['title'] = 'title';
        // $result = $this->transaction_model->getTransactionBetweenDate($date, $date, 'payment');
        $loginuserId = $this->customlib->getLoggedInUserID(); 
        $result         = $this->purchase_model->getTransactionBetweenDateLoginwise($date, $date, $loginuserId );

        $data['result'] = $result;
        $page           = $this->load->view('admin/purchase/_getpurchasesbydate', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
}
