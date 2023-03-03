<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Medicinecategory extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->state      = $this->config->item('state');
    }


    public function index()
    {
        if (!$this->rbac->hasPrivilege('medicine', 'can_view')) {
            access_denied();
        }
        if ($this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            redirect('admin/medicinecategory/medicine');
        } else
        if ($this->rbac->hasPrivilege('medicine_supplier', 'can_view')) {
            redirect('admin/medicinecategory/supplier');
        } else
        if ($this->rbac->hasPrivilege('medicine_dosage', 'can_view')) {
            redirect('admin/medicinedosage');
        }

        $this->medicine();
    }

    public function medicine()
    {

        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinecategory/medicine');
        $medicinecategoryid       = $this->input->post("medicinecategoryid");
        $data["title"]            = $this->lang->line('add_medicine_category');
        $medicineCategory         = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->form_validation->set_rules(
            'medicine_category', $this->lang->line('medicine_category'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_medicine_category')),
            )
        );
        $uLogin = $this->customlib->getLoggedInUserID();
        $data['can_edit'] = $uLogin;
        
        if ($this->form_validation->run()) {
            $medicineCategory   = $this->input->post("medicine_category");
            $medicinecategoryid = $this->input->post("id");
            if (empty($medicinecategoryid)) {
                if (!$this->rbac->hasPrivilege('medicine_category', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('medicine_category', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($medicinecategoryid)) {
                $data = array('medicine_category' => $medicineCategory, 'id' => $medicinecategoryid);
            } else {
                $data = array('medicine_category' => $medicineCategory);
            }

            $insert_id = $this->medicine_category_model->addMedicineCategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added_successfully') . '</div>');
            redirect("admin/medicinecategory/medicine");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/medicine_category", $data);
            $this->load->view("layout/footer");
        }
    }


    public function searchtype()
    {

        if (!$this->rbac->hasPrivilege('search_type', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinecategory/searchtype');
        $medicinecategoryid       = $this->input->post("medicinecategoryid");
        $data["title"]            = $this->lang->line('add_search_type');
        $medicineCategory         = $this->medicine_category_model->getSearchtype();
        $data["medicineCategory"] = $medicineCategory;
        $this->form_validation->set_rules(
            'search_type', $this->lang->line('search_type'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_medicine_search_type')),
            )
        );
        $uLogin = $this->customlib->getLoggedInUserID();
        $data['can_edit'] = $uLogin;
        
        if ($this->form_validation->run()) {
            $medicineCategory   = $this->input->post("search_type");
            $medicinecategoryid = $this->input->post("id");
            if (empty($medicinecategoryid)) {
                if (!$this->rbac->hasPrivilege('search_type', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('search_type', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($medicinecategoryid)) {
                $data = array('search_type' => $medicineCategory, 'id' => $medicinecategoryid);
            } else {
                $data = array('search_type' => $medicineCategory);
            }

            $insert_id = $this->medicine_category_model->addSearchtype($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added_successfully') . '</div>');
            redirect("admin/medicinecategory/searchtype");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/search_type", $data);
            $this->load->view("layout/footer");
        }
    }

    public function supplier()
    {
        if (!$this->rbac->hasPrivilege('medicine_supplier', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinecategory/supplier');
        $medicinecategoryid       = $this->input->post("medicinecategoryid");
        $data["title"]            = $this->lang->line('add_supplier');
        $supplierCategory         = $this->medicine_category_model->getSupplierCategoryPat();
        $data['state'] = $this->state;
        $data["supplierCategory"] = $supplierCategory;
        $this->form_validation->set_rules(
            'supplier_category', $this->lang->line('supplier_name'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_supplier_category')),
            )
        );
        if ($this->form_validation->run()) {
            $supplierCategory   = $this->input->post("supplier_category");
            $suppliercategoryid = $this->input->post("id");
            if (empty($suppliercategoryid)) {
                if (!$this->rbac->hasPrivilege('supplier_category', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('supplier_category', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($suppliercategoryid)) {
                $data = array('supplier_category' => $supplierCategory, 'id' => $suppliercategoryid);
            } else {

                $data = array('supplier_category' => $supplierCategory);
            }

            $insert_id = $this->medicine_category_model->addMedicineCategory($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added_successfully') . '</div>');
            redirect("admin/medicinecategory/supplier");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/supplier_category", $data);
            $this->load->view("layout/footer");
        }
    }

    public function add()
    {
        if ((!$this->rbac->hasPrivilege('medicine_category', 'can_add')) || (!$this->rbac->hasPrivilege('medicine_category', 'can_edit'))) {
            access_denied();
        }
        $medicinecategoryid = $this->input->post("medicinecategoryid");
        $this->form_validation->set_rules(
            'medicine_category', $this->lang->line('medicine_category'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_medicine_category')),
            )
        );

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        // var_Dump($dep_uploaded_role_id ); die;
        
        if($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }elseif($dep_uploaded_role_id == 4){
            $is_central_login = "yes";
        }else{
            $is_central_login = "no";

        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('medicine_category'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $medicineCategory = $this->input->post("medicine_category");
            if (!empty($medicinecategoryid)) {
                $data  = array('medicine_category' => $medicineCategory, 'id' => $medicinecategoryid);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $data  = array('medicine_category' => $medicineCategory, 'added_by' => $added_by,
                'is_central_login' => $is_central_login);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
            $insert_id = $this->medicine_category_model->addMedicineCategory($data);
        }
        echo json_encode($array);
    }

    public function addSearchtype()
    {
        if ((!$this->rbac->hasPrivilege('search_type', 'can_add')) || (!$this->rbac->hasPrivilege('search_type', 'can_edit'))) {
            access_denied();
        }
        $medicinecategoryid = $this->input->post("searchtypeid");
        $this->form_validation->set_rules(
            'search_type', $this->lang->line('search_type'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_medicine_search_type')),
            )
        );

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 

        
        // if($dep_uploaded_role_id == 7){
        //     $is_central_login = "yes";
        // }elseif($dep_uploaded_role_id == 4){
        //     $is_central_login = "yes";
        // }else{
        //     $is_central_login = "no";
        // }

        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];
        if ($this->form_validation->run() == false) {
            $msg = array(
                'search_type' => form_error('search_type'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $medicineCategory = $this->input->post("search_type");
            if (!empty($medicinecategoryid)) {
                $data  = array('search_type' => $medicineCategory, 'id' => $medicinecategoryid);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $data  = array('search_type' => $medicineCategory, 'added_by' => $added_by);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
            $insert_id = $this->medicine_category_model->addSearchtype($data);
        }
        echo json_encode($array);
    }


    public function addsupplier()
    {

        if ((!$this->rbac->hasPrivilege('medicine_supplier', 'can_add')) || (!$this->rbac->hasPrivilege('medicine_supplier', 'can_edit'))) {
            access_denied();
        }
        $suppliercategoryid = $this->input->post("suppliercategoryid");
        $this->form_validation->set_rules(
            'supplier_category', $this->lang->line('supplier_name'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_supplier_category')),
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

        if ($this->form_validation->run() == false) {
            $msg = array(
                'supplier_category'       => form_error('supplier_category'),
                'contact'                 => form_error('contact'),
                'pincode'         => form_error('pincode'),
                'state' => form_error('state'),
                'address'                 => form_error('address'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $supplierCategory      = $this->input->post("supplier_category");
            $contact               = $this->input->post('contact');
            $pincode        = $this->input->post('pincode');
            $state = $this->input->post('state');
            $supplierdruglicence   = $this->input->post('supplier_drug_licence');
            $address               = $this->input->post('address');
            $gst_in               = $this->input->post('gst_in');
            if (!empty($suppliercategoryid)) {
                $data = array('supplier'  => $supplierCategory,
                    'id'                      => $suppliercategoryid,
                    'contact'                 => $contact,
                    'pincode'         => $pincode,
                    'state' => $state,
                    'supplier_drug_licence'   => $supplierdruglicence,
                    'address'                 => $address,
                    'gst_in'             => $gst_in,
                );
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $data = array('supplier'  => $supplierCategory,
                    'contact'                 => $contact,
                    'pincode'         => $pincode,
                    'state'           => $state,
                    'address'         => $address,
                    'supplier_drug_licence'   => $supplierdruglicence,
                    'added_by'        => $added_by,
                    'is_central_login' => $is_central_login,
                    'gst_in'             => $gst_in,
                    'active' =>1
                );
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
            $insert_id = $this->medicine_category_model->addSupplierCategory($data);
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
        $result                   = $this->medicine_category_model->getMedicineCategory($id);
        $data["result"]           = $result;
        $data["title"]            = $this->lang->line('edit_category');
        $medicineCategory         = $this->medicine_category_model->getMedicineCategory();
        $data["medicineCategory"] = $medicineCategory;
        $this->load->view("layout/header");
        $this->load->view("admin/pharmacy/medicine_category", $data);
        $this->load->view("layout/footer");
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_delete')) {
            access_denied();
        }
        $this->medicine_category_model->delete($id);
        redirect('admin/medicinecategory/medicine');
    }

    public function deleteSerachtype($id)
    {
        if (!$this->rbac->hasPrivilege('search_type', 'can_delete')) {
            access_denied();
        }
        $this->medicine_category_model->deletesearchtype($id);
        redirect('admin/medicinecategory/searchtype');
    }

    public function deletesupplier($id)
    {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_delete')) {
            access_denied();
        }

        $this->medicine_category_model->deletesupplier($id);
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('delete_message')));
    }

    public function get_data($id)
    {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getMedicineCategory($id);
        echo json_encode($result);
    }

    public function get_dataSearchtype($id)
    {
        if (!$this->rbac->hasPrivilege('search_type', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getSearchtype($id);
        echo json_encode($result);
    }

    public function get_datasupplier($id)
    {
        if (!$this->rbac->hasPrivilege('medicine_category', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getSupplierCategory($id);
        echo json_encode($result);
    }

    public function assignsupplier(){
        if (!$this->rbac->hasPrivilege('assign_supplier', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinecategory/assignsupplier');
        $assignsupplierid       = $this->input->post("assignsupplierid");
        $data["title"]            = $this->lang->line('assign_supplier');

        $role                        = $this->customlib->getStaffRole();
        $role_id                     = json_decode($role)->id;
        $current_user_role_id = $role_id;

        // $shift = $this->medicine_category_model->assignsupplier("", 1,$role_id);//DOCTORS OVER
        // echo "<pre>";
        // print_r($shift); die;
        // foreach ($shift as $shift_key => $shift_value) {
        //     $shift[$shift_key]["doctor_shift"] = $this->onlineappointment_model->getGlobalDoctorShift($shift_value["id"]);//DOCTORS OVER
        // }

        $resultlist         = $this->medicine_category_model->assignsupplier("", 1,$role_id);
        // echo "<pre>";
        // print_r($resultlist );
        // die;
        $data['shift'] = $resultlist;
       
        
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/assign_supplier", $data);
            $this->load->view("layout/footer");
       

    }

    public function editAssignPermissions()
    {
       
        $doctor_id    = $this->input->post("doctor_id");
        $status       = $this->input->post("status");
        $insert_array = array();
        $delete_array = array();
        if ($status == 1) {
            $insert_array = array(
                "staff_id"        => $doctor_id,
                "status" => $status,
            );
        } elseif ($status == 0) {
            $delete_array = array(
                "staff_id"        => $doctor_id,
                "status" => $status,
            );
        }
        $insert_array = $this->security->xss_clean($insert_array);
        $this->medicine_category_model->editAssignSupplier($insert_array, $delete_array);
        echo json_encode(array("status" => "success", "message" => $this->lang->line('doctor_shift_updated_successfully')));
    }


    public function approveproduct(){
        if (!$this->rbac->hasPrivilege('approve_product', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'medicine/index');
        $this->session->set_userdata('sub_sidebar_menu', 'admin/medicinecategory/supplier');
        $approveproductid       = $this->input->post("approveproductid");
        $data["title"]            = $this->lang->line('add_supplier');
        $supplierCategory         = $this->medicine_category_model->getProductapproved();
        $data["supplierCategory"] = $supplierCategory;
        // $this->form_validation->set_rules(
        //     'supplier_category', $this->lang->line('supplier_name'), array('required',
        //         array('check_exists', array($this->medicine_category_model, 'valid_supplier_category')),
        //     )
        // );

        $this->form_validation->set_rules(
            'product_name', $this->lang->line('product_name'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_approve_product')),
            )  
        );

        $this->form_validation->set_rules(
            'product_composition', $this->lang->line('product_composition'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_approve_product_composition')),
        )
    );

     $this->form_validation->set_rules('hsn_code', $this->lang->line('hsn_code'), array('required'));
     $this->form_validation->set_rules('product_unit_packing', $this->lang->line('product_unit_packing'), array('required'));
     $this->form_validation->set_rules('pts', $this->lang->line('pts'), array('required'));
     $this->form_validation->set_rules('ptr', $this->lang->line('ptr'), array('required'));
     $this->form_validation->set_rules('patient_billing_rate', $this->lang->line('patient_billing_rate'), array('required'));
     $this->form_validation->set_rules('product_mrp', $this->lang->line('product_mrp'), array('required'));
     $this->form_validation->set_rules('product_discount', $this->lang->line('product_discount'), array('required'));
     $this->form_validation->set_rules('product_gst', $this->lang->line('product_gst'), array('required'));
     $this->form_validation->set_rules('product_vp', $this->lang->line('product_vp'), array('required'));
     $this->form_validation->set_rules('product_lp', $this->lang->line('product_lp'), array('required'));

        if ($this->form_validation->run()) {
            // $supplierCategory   = $this->input->post("supplier_category");

            $product_name               = $this->input->post('product_name');
            $hsn_code        = $this->input->post('hsn_code');
            $product_unit_packing = $this->input->post('product_unit_packing');
            $product_composition   = $this->input->post('product_composition');
            $pts               = $this->input->post('pts');
            $ptr               = $this->input->post('ptr');
            $patient_billing_rate               = $this->input->post('patient_billing_rate');
            $product_mrp               = $this->input->post('product_mrp');
            $product_discount               = $this->input->post('product_discount');
            $product_gst               = $this->input->post('product_gst');
            $product_vp               = $this->input->post('product_vp');
            $product_lp               = $this->input->post('product_lp');

            $suppliercategoryid = $this->input->post("id");
            if (empty($suppliercategoryid)) {
                if (!$this->rbac->hasPrivilege('approve_product', 'can_add')) {
                    access_denied();
                }
            } else {
                if (!$this->rbac->hasPrivilege('approve_product', 'can_edit')) {
                    access_denied();
                }
            }


            $maxlength = 1;
            $chary = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9","a","s"
                            );
            $return_str = "";
            for ( $x=0; $x<=$maxlength; $x++ ) {
                $return_str .= $chary[rand(0, count($chary)-1)];
            }
             $product_id = 'CPA'.'00'.$return_str; 

            if (!empty($suppliercategoryid)) {
                $data = array('id' => $suppliercategoryid,
                'product_name'                 => $product_name,
                'hsn_code'         => $hsn_code,
                'product_unit_packing' => $product_unit_packing,
                'product_composition'   => $product_composition,
                'pts'                 => $pts,
                'ptr'             => $ptr,
                'patient_billing_rate'             => $patient_billing_rate,
                'product_mrp'             => $product_mrp,
                'product_discount'             => $product_discount,
                'product_gst'             => $product_gst,
                'product_vp'             => $product_vp,
                'product_lp'             => $product_lp,
            );
            } else {

                $data = array('product_id'  => $product_id,
                'product_name'                 => $product_name,
                'hsn_code'         => $hsn_code,
                'product_unit_packing' => $product_unit_packing,
                'product_composition'   => $product_composition,
                'pts'                 => $pts,
                'ptr'             => $ptr,
                'patient_billing_rate'             => $patient_billing_rate,
                'product_mrp'             => $product_mrp,
                'product_discount'             => $product_discount,
                'product_gst'             => $product_gst,
                'product_vp'             => $product_vp,
                'product_lp'             => $product_lp,);
            }

            $insert_id = $this->medicine_category_model->addApproveProduct($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('record_added_successfully') . '</div>');
            redirect("admin/medicinecategory/supplier");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/pharmacy/approve_product", $data);
            $this->load->view("layout/footer");
        }
    }


    
    public function addproduct()
    {

        if ((!$this->rbac->hasPrivilege('approve_product', 'can_add')) || (!$this->rbac->hasPrivilege('approve_product', 'can_edit'))) {
            access_denied();
        }
        $approveproductid = $this->input->post("approveproductid");
        $this->form_validation->set_rules(
            'product_name', $this->lang->line('product_name'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_approve_product')),
            )  
        );

        $this->form_validation->set_rules(
            'product_composition', $this->lang->line('product_composition'), array('required',
                array('check_exists', array($this->medicine_category_model, 'valid_approve_product_composition')),
            )  
        );

        // $this->form_validation->set_rules('product_name', $this->lang->line('product_name'), array('required'));
        $this->form_validation->set_rules('hsn_code', $this->lang->line('hsn_code'), array('required'));
        $this->form_validation->set_rules('product_unit_packing', $this->lang->line('product_unit_packing'), array('required'));
        // $this->form_validation->set_rules('product_composition', $this->lang->line('product_composition'), array('required'));
        $this->form_validation->set_rules('pts', $this->lang->line('pts'), array('required'));
        $this->form_validation->set_rules('ptr', $this->lang->line('ptr'), array('required'));
        $this->form_validation->set_rules('patient_billing_rate', $this->lang->line('patient_billing_rate'), array('required'));
        $this->form_validation->set_rules('product_mrp', $this->lang->line('product_mrp'), array('required'));
        $this->form_validation->set_rules('product_discount', $this->lang->line('product_discount'), array('required'));
        $this->form_validation->set_rules('product_gst', $this->lang->line('product_gst'), array('required'));
        $this->form_validation->set_rules('product_vp', $this->lang->line('product_vp'), array('required'));
        $this->form_validation->set_rules('product_lp', $this->lang->line('product_lp'), array('required'));
        $this->form_validation->set_rules('company_name', $this->lang->line('company_name'), array('required'));

            $maxlength = 1;
            $chary = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9","a","s"
                            );
            $return_str = "";
            for ( $x=0; $x<=$maxlength; $x++ ) {
                $return_str .= $chary[rand(0, count($chary)-1)];
            }
             $product_id = 'CPA'.'00'.$return_str; 
        

        $dep_uploaded_role = $this->customlib->getStaffRole();  //to get Staff Role
        $dep_uploaded_role_id = json_decode($dep_uploaded_role)->id; 
        if($dep_uploaded_role_id != 7){
            $is_central_login = "no";
        }elseif($dep_uploaded_role_id == 7){
            $is_central_login = "yes";
        }
        $dep_added_by = $this->customlib->getUserData();
        $added_by = $dep_added_by['id'];

        if ($this->form_validation->run() == false) {
            $msg = array(
                'product_id'       => form_error('product_id'),
                'product_name'       => form_error('product_name'),
                'hsn_code'                 => form_error('hsn_code'),
                'product_unit_packing'         => form_error('product_unit_packing'),
                'product_composition' => form_error('product_composition'),
                'pts'                 => form_error('pts'),
                'ptr'                 => form_error('ptr'),
                'patient_billing_rate'                 => form_error('patient_billing_rate'),
                'product_mrp'                 => form_error('product_mrp'),
                'product_discount'                 => form_error('product_discount'),
                'product_gst'                 => form_error('product_gst'),
                'product_vp'                 => form_error('product_vp'),
                'product_lp'                 => form_error('product_lp'),
                'company_name'                 => form_error('company_name'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            // $product_id      = $this->input->post("product_id");
            $product_name               = $this->input->post('product_name');
            $hsn_code        = $this->input->post('hsn_code');
            $product_unit_packing = $this->input->post('product_unit_packing');
            $product_composition   = $this->input->post('product_composition');
            $pts               = $this->input->post('pts');
            $ptr               = $this->input->post('ptr');
            $patient_billing_rate               = $this->input->post('patient_billing_rate');
            $product_mrp               = $this->input->post('product_mrp');
            $product_discount               = $this->input->post('product_discount');
            $product_gst               = $this->input->post('product_gst');
            $product_vp               = $this->input->post('product_vp');
            $product_lp               = $this->input->post('product_lp');
            $company_name               = $this->input->post('company_name');


            if (!empty($approveproductid)) {
                $data = array(
                    'id'                      => $approveproductid,
                    'product_name'                 => $product_name,
                    'hsn_code'         => $hsn_code,
                    'product_unit_packing' => $product_unit_packing,
                    'product_composition'   => $product_composition,
                    'pts'                 => $pts,
                    'ptr'             => $ptr,
                    'patient_billing_rate'             => $patient_billing_rate,
                    'product_mrp'             => $product_mrp,
                    'product_discount'             => $product_discount,
                    'product_gst'             => $product_gst,
                    'product_vp'             => $product_vp,
                    'product_lp'             => $product_lp,
                    'company_name'             => $company_name,
                    // 'active'             => 1

                );
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            } else {
                $data = array('product_id'  => $product_id,
                'product_name'                 => $product_name,
                'hsn_code'         => $hsn_code,
                'product_unit_packing' => $product_unit_packing,
                'product_composition'   => $product_composition,
                'pts'                 => $pts,
                'ptr'             => $ptr,
                'patient_billing_rate'             => $patient_billing_rate,
                'product_mrp'             => $product_mrp,
                'product_discount'             => $product_discount,
                'product_gst'             => $product_gst,
                'product_vp'             => $product_vp,
                'product_lp'             => $product_lp,
                'company_name'             => $company_name,
                'active'             => 1
                );
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
            $insert_id = $this->medicine_category_model->addApproveProduct($data);
        }
        echo json_encode($array);
    }

    public function getComposition(){
        $pro_com = $this->input->post('product_composition');
        $data = array("product_composition"=>$pro_com);
        $getComposition = $this->medicine_category_model->getComposition($data);
        echo json_encode($getComposition);

    }

    public function getProductName(){
        $pro_name = $this->input->post('product_name');
        $data = array("product_name"=>$pro_name);
        $getProName = $this->medicine_category_model->getProName($data);
        echo json_encode($getProName);

    }

    public function deletapproveproduct($id)
    {
        if (!$this->rbac->hasPrivilege('approve_product', 'can_delete')) {
            access_denied();
        }

        $this->medicine_category_model->deleteapproveproduct($id);
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('delete_message')));
    }


    public function get_dataapprovesupplier($id)
    {
        if (!$this->rbac->hasPrivilege('approve_product', 'can_view')) {
            access_denied();
        }
        $result = $this->medicine_category_model->getApproveProduct($id);
        echo json_encode($result);
    }


    public function statusUpdateapproveproduct()
    {
       
        $doctor_id    = $this->input->post("doctor_id");
        $status       = $this->input->post("status");
        $update_array = array();

            $update_array = array(
                "id"        => $doctor_id,
                "active" => $status,
            );
           
    
        $this->medicine_category_model->updateapproveproduct($update_array);
        echo json_encode(array("status" => "success", "message" => $this->lang->line('product_approve_updated_successfully')));
    }

    public function statusUpdatemedicinesupplier()
    {
       
        $doctor_id    = $this->input->post("doctor_id");
        $status       = $this->input->post("status");
        $update_array = array();

            $update_array = array(
                "id"        => $doctor_id,
                "active" => $status,
            );
           
    
        $this->medicine_category_model->updatemedsupplier($update_array);
        echo json_encode(array("status" => "success", "message" => $this->lang->line('medicine_supplier_updated_successfully')));
    }

    

}
