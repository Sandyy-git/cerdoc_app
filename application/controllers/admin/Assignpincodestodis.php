<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Assignpincodestodis extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->model('unittype_model');
        $this->load->helper('file');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('locality_city', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'city/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/city');
        $medicinecategoryid = $this->input->post("medicinecategoryid");
        $data["title"]      = $this->lang->line('add_medicine_dosage');
        $pincodestodis    = $this->assignpincodes_model->pincodestodis();
        // $unit              = $this->unittype_model->get();
        // $data['unit_list'] = $unit;
        // $dose_result       = array();
        // foreach ($medicineDosage as $key => $value) {
        //     $dose_result[$value['medicine_category_id']][] = $value;
        // }
        $data["city"]   = $pincodestodis;
        $staffPincodes         = $this->assignpincodes_model->getStaffPincode();
        $data["staffPincodes"] = $staffPincodes;
        $this->form_validation->set_rules('locality', $this->lang->line('staff_locality'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line('city'), 'trim|required|xss_clean');
        if ($this->form_validation->run()) {
            $locality     = $this->input->post("locality");
            $cityid = $this->input->post("id");

            if (!empty($medicinedosageid)) {
                $data = array('locality_id' => $locality, 'city' => $this->input->post('city'), 'id' => $cityid);
            } else {

                $data = array('locality_id' => $locality, 'city' => $this->input->post('city'));
            }

            $insert_id = $this->localitycity_model->addLocalityCity($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added_successfully') . '</div>');
            redirect("admin/city/");
        } else {

            $this->load->view("layout/header");
            $this->load->view("admin/staff/assignpincodestodis", $data);
            $this->load->view("layout/footer");
        }
    }

    public function interval()
    {
        if (!$this->rbac->hasPrivilege('dosage_interval', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinedosage/interval');
        $this->load->view("layout/header");
        $this->load->view("admin/pharmacy/dose_interval");
        $this->load->view("layout/footer");
    }

    public function duration()
    {
        if (!$this->rbac->hasPrivilege('dosage_duration', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinedosage/duration');
        $this->load->view("layout/header");
        $this->load->view("admin/pharmacy/dose_duration");
        $this->load->view("layout/footer");
    }

    public function add_interval()
    {

        if (!$this->rbac->hasPrivilege('dosage_interval', 'can_add')) {
            access_denied();
        }
        // $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array('required',
                array('check_exists', array($this->medicine_dosage_model, 'valid_medicine_interval')),
            )
        );

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        if($dep_uploaded_role_id != 7){
            $is_central_login = "no";
        }elseif($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        $interval_id = $this->input->post('id');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('name'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array('id' => $interval_id, 'name' => $this->input->post('name'),'added_by' => $added_by,
            'is_central_login' => $is_central_login);
            $this->medicine_dosage_model->add_interval($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function add_duration()
    {
        if (!$this->rbac->hasPrivilege('dosage_duration', 'can_add')) {
            access_denied();
        }
        // $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array('required',
                array('check_exists', array($this->medicine_dosage_model, 'valid_medicine_duration')),
            )
        );


        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        if($dep_uploaded_role_id != 7){
            $is_central_login = "no";
        }elseif($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        $duration_id = $this->input->post('id');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('name'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array('id' => $duration_id, 'name' => $this->input->post('name'),'added_by' => $added_by,
            'is_central_login' => $is_central_login);
            $this->medicine_dosage_model->add_duration($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function get_doseintervalbyid($id)
    {
        $result = $this->medicine_dosage_model->get_intervalbyid($id);
        echo json_encode($result);
    }

    public function get_dosedurationbyid($id)
    {
        $result = $this->medicine_dosage_model->get_durationbyid($id);
        echo json_encode($result);
    }

    public function add()
    {
        $cityid = $this->input->post("cityid");
        // foreach ($_POST['dosage'] as $key => $value) {
        //     $dose = $_POST['dosage'][$key];
        //     $unit = $_POST['unit'][$key];

        //     if ($dose == "") {
        //         $this->form_validation->set_rules('dosage', $this->lang->line('dose'), 'trim|required|xss_clean');
        //     }
        //     if ($unit == "") {
        //         $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'trim|required|xss_clean');
        //     }

        // }

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        if($dep_uploaded_role_id != 7){
            $is_central_login = "no";
        }elseif($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        // $this->form_validation->set_rules('locality', $this->lang->line('staff_locality'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('pincode', $this->lang->line('pincode'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'pincode' => form_error('pincode'),
                // 'city'        => form_error('city'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            
                $data = array(
                    // 'locality_id' => $_POST['locality'],
                    'pincode'               => $_POST['pincode'],
                    'is_active'      => 'yes',
                   
                );

                if (!empty($cityid) && $cityid != 0) {
                    $data['id'] = $cityid;
                }
                $this->assignpincodes_model->addPincode($data);
            

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }


    public function addPincodestoDis()
    {
        $dispincodeid = $this->input->post("dispincodeid");
        // foreach ($_POST['dosage'] as $key => $value) {
        //     $dose = $_POST['dosage'][$key];
        //     $unit = $_POST['unit'][$key];

        //     if ($dose == "") {
        //         $this->form_validation->set_rules('dosage', $this->lang->line('dose'), 'trim|required|xss_clean');
        //     }
        //     if ($unit == "") {
        //         $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'trim|required|xss_clean');
        //     }

        // }

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        if($dep_uploaded_role_id != 7){
            $is_central_login = "no";
        }elseif($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        $this->form_validation->set_rules('staff_id', $this->lang->line('distributors'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('pincode', $this->lang->line('pincode'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'pincode' => form_error('pincode'),
                'staff_id'        => form_error('distributors'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            
                $data = array(
                    'staff_id' => $_POST['staff_id'],
                    'pincode'               => $_POST['pincode'],
                    'is_active'      => 'yes',
                   
                );

                if (!empty($dispincodeid) && $dispincodeid != 0) {
                    $data['id'] = $dispincodeid;
                }
                $this->assignpincodes_model->addPincodetoDis($data);
            

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }





    

    public function get()
    {
        //get product data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->medicine_category_model->getall();
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $result                   = $this->medicine_dosage_model->getMedicineDosage($id);
        $data["result"]           = $result;
        $data["title"]            = $this->lang->line('edit_category');
        $medicineCategory         = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->load->view("layout/header");
        $this->load->view("admin/pharmacy/medicine_dosage", $data);
        $this->load->view("layout/footer");
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('assign_pincodes', 'can_delete')) {
            access_denied();
        }
        $this->assignpincodes_model->deleteDisPincode($id);
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('delete_message')));
    }

    public function get_data_disPincode($id)
    {
        if (!$this->rbac->hasPrivilege('assign_pincodes', 'can_view')) {
            access_denied();
        }

        $result = $this->assignpincodes_model->getDispcode($id);

        echo json_encode($result);
    }

    public function getMedicineDosage()
    {
        $medicine = $this->input->post('medicine_id');
        $result   = $this->medicine_dosage_model->getDosageByMedicine($medicine);
        echo json_encode($result);
    }

    public function get_doseIntervallist()
    {

        $dt_response = $this->medicine_dosage_model->get_doseIntervallist();

        $dt_response = json_decode($dt_response);
        $dt_data     = array();
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $key => $value) {

                $row = array();
                //====================================
                $action = "<div class='rowoptionview'>";
                if ($this->rbac->hasPrivilege('dosage_interval', 'can_edit')) {
                    $action .= "<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='get(" . $value->id . ")' data-original-title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('dosage_interval', 'can_delete')) {
                    $action .= "<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='delete_intervalById(" . $value->id . ")' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
                }

                $action .= "</div>";
                //==============================
                $row[]     = $value->name;
                $row[]     = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($dt_response->draw),
            "recordsTotal"    => intval($dt_response->recordsTotal),
            "recordsFiltered" => intval($dt_response->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function get_dosedurationlist()
    {

        $dt_response = $this->medicine_dosage_model->get_dosedurationlist();

        $dt_response = json_decode($dt_response);
        $dt_data     = array();
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $key => $value) {

                $row = array();
                //====================================
                $action = "<div class='rowoptionview'>";
                if ($this->rbac->hasPrivilege('dosage_duration', 'can_edit')) {
                    $action .= "<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='get(" . $value->id . ")' data-original-title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('dosage_duration', 'can_delete')) {
                    $action .= "<a  class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='delete_durationById(" . $value->id . ")' data-original-title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
                }

                $action .= "</div>";
                //==============================
                $row[]     = $value->name;
                $row[]     = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($dt_response->draw),
            "recordsTotal"    => intval($dt_response->recordsTotal),
            "recordsFiltered" => intval($dt_response->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function delete_doseInterval($id)
    {
        if (!$this->rbac->hasPrivilege('dosage_interval', 'can_delete')) {
            access_denied();
        }
        $this->medicine_dosage_model->delete_doseInterval($id);
    }

    public function delete_doseduration($id)
    {
        if (!$this->rbac->hasPrivilege('dosage_duration', 'can_delete')) {
            access_denied();
        }
        $this->medicine_dosage_model->delete_doseduration($id);
    }
}
