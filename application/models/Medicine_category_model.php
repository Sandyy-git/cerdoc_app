<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Medicine_category_model extends MY_model
{

    public function valid_medicine_category($str)
    {

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        if( $userRoleIn[0]['created_by'] == 7){
            $userLoggedIn = $userRoleIn[0]['staff_id'];
        }elseif( $userRoleIn[0]['created_by'] == 73){
            $userLoggedIn = $userRoleIn[0]['staff_id'];
        }else{
        $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        $userLoggedIn = $userGetAdmin[0]['staff_id'];
        }

        $medicine_category = $this->input->post('medicine_category');
        $id                = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_category_exists($medicine_category, $id,$userLoggedIn)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }


    public function valid_medicine_search_type($str)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        if( $userRoleIn[0]['created_by'] == 7){
            $userLoggedIn = $userRoleIn[0]['staff_id'];
        }elseif( $userRoleIn[0]['created_by'] == 73){
            $userLoggedIn = $userRoleIn[0]['staff_id'];
        }else{
        $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        $userLoggedIn = $userGetAdmin[0]['staff_id'];
        }

        $medicine_category = $this->input->post('search_type');
        $id                = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_search_type_exists($medicine_category, $id,$userLoggedIn)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function valid_medicine_name($str)
    {
        $medicine_name = $this->input->post('medicine_name');
        $id            = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_name_exists($medicine_name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_name_exists($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'medicine_name' => $name);
            $query = $this->db->where($data)->get('pharmacy');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('medicine_name', $name);
            $query = $this->db->get('pharmacy');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function valid_supplier_category($str)
    {
        $supplier_category = $this->input->post('supplier_category');
        $id                = $this->input->post('suppliercategoryid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_category_existssupplier($supplier_category, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }


    //chnages to fetch med cat
    public function getMedicineCategory($id = null)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
       
        if( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
            // $userLoggedIn = $userRoleIn[0]['staff_id'];  //PREV
            $getfromcp = $this->role_model->getfromcp();
            $userLoggedIn = $getfromcp[0]['id'];
           
        }elseif( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            // $userLoggedIn = $userRoleIn[0]['staff_id'];  //PREV
            $getfromcp = $this->role_model->getfromcp();
            $userLoggedIn = $getfromcp[0]['id'];
           
        }
        elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Doctor'){
            $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
            $userLoggedIn = $userGetAdmin[0]['staff_id'];
        }elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Pharmacist'){
            $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
            $userLoggedIn = $userGetAdmin[0]['staff_id'];
        }else{
            $userLoggedIn = $userLoggedInFirst;
        }

        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('medicine_category');
            return $query->row_array();
        } else {

            if($userLoggedIn == 7){
                // $this->db->where('medicine_category.added_by',$userLoggedIn);
            }else{
                // $this->db->where('medicine_category.added_by',$userLoggedIn);
                // $this->db->or_where('medicine_category.is_central_login','yes');
            }
            $query = $this->db->get("medicine_category");

            return $query->result_array();
        }
    }

      public function getSearchtype($id = null)
      {
          $userLoggedInFirst = $this->customlib->getLoggedInUserId();
          $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        //   var_dump($userRoleIn); die;
         
          if( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
              // $userLoggedIn = $userRoleIn[0]['staff_id'];  //PREV
              $getfromcp = $this->role_model->getfromcp();
              $userLoggedIn = $getfromcp[0]['id'];
             
          }elseif( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
              // $userLoggedIn = $userRoleIn[0]['staff_id'];  //PREV
              $getfromcp = $this->role_model->getfromcp();
              $userLoggedIn = $getfromcp[0]['id'];
             
          }elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Doctor'){
              $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
              $userLoggedIn = $userGetAdmin[0]['staff_id'];
          }elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Pharmacist'){
              $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
              $userLoggedIn = $userGetAdmin[0]['staff_id'];
          }else{
              $userLoggedIn = $userLoggedInFirst;
          }
  
          if (!empty($id)) {
              $query = $this->db->where("id", $id)->get('medicine_search_type');
              return $query->row_array();
          } else {
  
              if($userLoggedIn == 7){
                //   $this->db->where('medicine_search_type.added_by',$userLoggedIn);
              }else{
                //   $this->db->where('medicine_search_type.added_by',$userLoggedIn);
                  // $this->db->or_where('medicine_category.is_central_login','yes');
              }
              $query = $this->db->get("medicine_search_type");
  
              return $query->result_array();
          }
      }



    public function getSupplierCategory($id = null)
    {
        // $uLogin = $this->customlib->getLoggedInUserID(); 
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('medicine_supplier');
            return $query->row_array();
        } else {
            // $query = $this->db->where("medicine_supplier.added_by", $uLogin)->get("medicine_supplier");
            $query = $this->db->where("medicine_supplier.active", 1)->get("medicine_supplier");

            return $query->result_array();
        }
    }

    public function getDistributors($id = null)
    {
         $login_user = $this->customlib->getUserData();
         $login_user_id = $login_user['created_by'];

        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('staff');
            return $query->row_array();
        } else {
            $query = $this->db->select('staff.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
           ->join('staff_roles', 'staff.id = staff_roles.staff_id')
            ->where("staff.is_active",1)
            ->where("staff_roles.role_id",$login_user_id)
            ->get("staff");
            return $query->result_array();
        }
    }

    public function getMedicineCategoryPat($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('medicine_category');
            return $query->row_array();
        } else {
            $query = $this->db->get("medicine_category");
            return $query->result_array();
        }
    }

    public function getSupplierCategoryPat($id = null)
    {
        $userLoggedIn = $this->customlib->getLoggedInUserId();
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('medicine_supplier');
            return $query->row_array();
        } else {

            if($userLoggedIn == 7){
                $this->db->where('medicine_supplier.added_by',$userLoggedIn);
            }else{
                $this->db->where('medicine_supplier.added_by',$userLoggedIn);
                $this->db->or_where('medicine_supplier.is_central_login','yes');
            }
            $query = $this->db->get("medicine_supplier");
            return $query->result_array();
        }
    }

    public function check_category_exists($name, $id,$userLoggedIn)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'medicine_category' => $name);
            $query = $this->db->where($data)->get('medicine_category');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('medicine_category', $name);
            $this->db->where('added_by', $userLoggedIn);

            $query = $this->db->get('medicine_category');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function check_search_type_exists($name, $id,$userLoggedIn)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'search_type' => $name);
            $query = $this->db->where($data)->get('medicine_search_type');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('search_type', $name);
            $this->db->where('added_by', $userLoggedIn);

            $query = $this->db->get('medicine_search_type');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function check_category_existssupplier($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'supplier' => $name);
            $query = $this->db->where($data)->get('medicine_supplier');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('supplier', $name);
            $query = $this->db->get('medicine_supplier');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
 
    public function addMedicineCategory($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('medicine_category', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Medicine Category id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('medicine_category', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Medicine Category id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }


    
    public function addSearchtype($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('medicine_search_type', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Medicine Category id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('medicine_search_type', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Medicine Category id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function addSupplierCategory($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('medicine_supplier', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Medicine Supplier id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('medicine_supplier', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Medicine Supplier id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function getall()
    {
        $this->datatables->select('id,medicine_category');
        $this->datatables->from('medicine_category');
        $this->datatables->add_column('view', '<a href="' . site_url('admin/medicinecategory/edit/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit"> <i class="fa fa-pencil"></i></a><a href="' . site_url('admin/medicinecategory/delete/$1') . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i>
                                                    </a>', 'id');
        return $this->datatables->generate();
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_category");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Category id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function deletesearchtype($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_search_type");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Category id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

 
    public function deletesupplier($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_supplier");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Category id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }


    // public function assignsupplier(){
    //     $query = $this->db->get("staff")->where();
    //     return $query->result_array();
    // }

    public function assignsupplier($searchterm, $active,$role_id)
    {
        $i                         = 1;
        $custom_fields             = $this->customfield_model->get_custom_fields('staff', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'staff.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }
        $field_variable      = implode(',', $field_var_array);
        $this->db->select('staff.*, staff_designation.designation as designation`, department.department_name as department,roles.name as user_type,roles.id as role_id,assign_supplier.status as assign_status'.$field_variable)->from('staff');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', "LEFT");
        $this->db->join('staff_designation', "staff_designation.id=staff.staff_designation_id", "LEFT");
        $this->db->join('roles', 'roles.id = staff_roles.role_id', "LEFT");
        $this->db->join('department', 'department.id = staff.department_id', "LEFT");
        $this->db->join('assign_supplier', 'assign_supplier.staff_id = staff.id', "LEFT");

        $this->db->where('staff.is_active',$active);
        if($role_id!=7) {
            $this->db->where('staff.created_by', $role_id);
        }

        $this->db->group_start();
        $this->db->like('staff.employee_id', $searchterm);
        $this->db->or_like('staff.email', $searchterm);
        $this->db->or_like('staff.name', $searchterm);
        $this->db->or_like('staff.gender', $searchterm);
        $this->db->or_like('staff.surname', $searchterm);
        $this->db->or_like('staff_designation.designation', $searchterm);
        $this->db->or_like('department.department_name', $searchterm);       
        $this->db->or_like('staff.blood_group', $searchterm);  
        $this->db->or_like('staff.local_address', $searchterm);
        $this->db->or_like('staff.contact_no', $searchterm);
        $this->db->or_like('roles.name', $searchterm);
        $this->db->group_end();
        $query  = $this->db->get();
        $result = $query->result_array();
        if ($this->session->has_userdata('hospitaladmin')) {
            $superadmin_rest = $this->session->userdata['hospitaladmin']['superadmin_restriction'];
            if ($superadmin_rest == 'disabled') {
                $search     = in_array(7, array_column($result, 'role_id'));
                $search_key = array_search(7, array_column($result, 'role_id'));
                if (!empty($search)) {
                    unset($result[$search_key]);
                }
            }
        }
        return $result;
    } 


    public function editAssignSupplier($insert_data, $delete_data)
    {
        if (!empty($insert_data)) {
            $this->db->insert("assign_supplier", $insert_data);
        }
        if (!empty($delete_data)) {
            $this->db->where("staff_id", $delete_data["staff_id"]);
            $this->db->delete("assign_supplier");
        }
    }


    public function addApproveProduct($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('approve_product', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Medicine Supplier id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('approve_product', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Medicine Supplier id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }

    public function valid_approve_product($str)
    {
        $product_name = $this->input->post('product_name');
        $id                = $this->input->post('approveproductid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_approve_existsproduct($product_name, $id)) {
            $this->form_validation->set_message('check_exists', 'Product Name already exists');
            return false;
        } else {
            return true;
        }
    }


    public function check_approve_existsproduct($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'product_name' => $name);
            $query = $this->db->where($data)->get('approve_product');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('product_name', $name);
            $query = $this->db->get('approve_product');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    
    public function valid_approve_product_composition($str)
    {
        $product_composition = $this->input->post('product_composition');
        $id                = $this->input->post('approveproductid');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_approve_existsproductcompo($product_composition, $id)) {
            $this->form_validation->set_message('check_exists', 'Product Composition already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_approve_existsproductcompo($name, $id)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'product_composition' => $name);
            $query = $this->db->where($data)->get('approve_product');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('product_composition', $name);
            $query = $this->db->get('approve_product');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function getComposition($data){
        $query = $this->db->where("approve_product.product_composition", $data['product_composition'])->get("approve_product");
        return $query->result_array();
    }

    public function getProName($data){
        $query = $this->db->where("approve_product.product_name", $data['product_name'])->get("approve_product");
        return $query->result_array();
    }


    public function getProductapproved($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('approve_product');
            return $query->row_array();
        } else {
            $query = $this->db->get("approve_product");
            return $query->result_array();
        }
    }

    public function getApproveProduct($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('approve_product');
            return $query->row_array();
        } else {
            $query = $this->db->get("approve_product");
            return $query->result_array();
        }
    }


    public function deleteapproveproduct($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("approve_product");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Category id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }


    public function updateapproveproduct($data){
            $this->db->where('id', $data['id']);
            $this->db->update('approve_product', $data);
            $record_id = $data['id'];
            return $record_id;
    }

    public function updatemedsupplier($data){
        $this->db->where('id', $data['id']);
        $this->db->update('medicine_supplier', $data);
        $record_id = $data['id'];
        return $record_id;
}

    
}
