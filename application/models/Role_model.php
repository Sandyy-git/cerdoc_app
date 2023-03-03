<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Role_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        // var_dump($id); die;
        $this->db->select()->from('roles');
        if ($id != null && $id != 'cw' && $id != 'lab') {
            $this->db->where('roles.id', $id);
        }elseif($id == 'cw'){
            $this->db->where('roles.is_system', 0);
            $this->db->or_where('roles.id=', 4);
            $this->db->order_by('roles.id');
        }elseif($id == 'lab'){
            $this->db->where('roles.is_system', 0);
            $this->db->or_where('roles.id=', 5);
            $this->db->order_by('roles.id');
        }else {
            $this->db->where('roles.is_system', 1);
            $this->db->where('roles.id!=', 4);

            $this->db->order_by('roles.id');
        }

        $query = $this->db->get();
        if ($id != null && $id != 'cw' && $id != 'lab') {
            $result = $query->row_array();
        }elseif($id == 'cw'){

            $result = $query->result_array();
            // var_dump($result); die;
            if ($this->session->has_userdata('hospitaladmin')) {
                $superadmin_rest = $this->session->userdata['hospitaladmin']['superadmin_restriction'];
                if ($superadmin_rest == 'disabled') {
                    $search     = in_array(7, array_column($result, 'id'));
                    $search_key = array_search(7, array_column($result, 'id'));

                    if (!empty($search)) {
                        unset($result[$search_key]);
                        $result = array_values($result);
                    }
                }
            }
            
        }elseif($id == 'lab'){

            $result = $query->result_array();
            // var_dump($result); die;
            if ($this->session->has_userdata('hospitaladmin')) {
                $superadmin_rest = $this->session->userdata['hospitaladmin']['superadmin_restriction'];
                if ($superadmin_rest == 'disabled') {
                    $search     = in_array(7, array_column($result, 'id'));
                    $search_key = array_search(7, array_column($result, 'id'));

                    if (!empty($search)) {
                        unset($result[$search_key]);
                        $result = array_values($result);
                    }
                }
            }
            
        }else {
            $result = $query->result_array();
            // var_dump($result); die;
            if ($this->session->has_userdata('hospitaladmin')) {
                $superadmin_rest = $this->session->userdata['hospitaladmin']['superadmin_restriction'];
                if ($superadmin_rest == 'disabled') {
                    $search     = in_array(7, array_column($result, 'id'));
                    $search_key = array_search(7, array_column($result, 'id'));

                    if (!empty($search)) {
                        unset($result[$search_key]);
                        $result = array_values($result);
                    }
                }
            }
        }
        // echo $this->db->last_query(); die;
        return $result;
    }



    public function getSystemRoles()
    {
        $role                        = $this->customlib->getStaffRole();
        $role_id                     = json_decode($role)->id;
        if($role_id == 73){

        $this->db->select()->from('roles');
        $this->db->where('is_system', 1);
        $query = $this->db->get();
        $result = $query->result_array();

        return $result;
        }elseif($role_id == 76){

            $this->db->select()->from('roles');
            $this->db->where('is_system', 1);
            $query = $this->db->get();
            $result = $query->result_array();
    
            return $result;
        }else{
            return '';
        }
    }
    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('roles');

        $message = DELETE_RECORD_CONSTANT . " On Roles id " . $id;
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

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('roles', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Roles id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $role  = array(
                'name' => $data['name'],
            );
            $this->db->insert('roles', $role);
            $insert_id = $this->db->insert_id();

            //insert admin himself as a role
            $adminsrole  = array(
                'admin_role_id' => $insert_id,
                'staff_role_id' => $insert_id,
            );
            $this->db->insert('admin_staff',$adminsrole);
            //inserting into staffroles
            foreach ($data['staff_roles'] as $key => $value) {
                $adminrole  = array(
                    'admin_role_id' => $insert_id,
                    'staff_role_id' => $value["id"],
                );
                $this->db->insert('admin_staff',$adminrole);
            }

            $message = INSERT_RECORD_CONSTANT . " On Roles id " . $insert_id;
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

    public function valid_check_exists($str)
    {
        $name = $this->input->post('name');
        $id   = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($name, $id)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($name, $id)
    {
        $this->db->where('name', $name);
        $this->db->where('id !=', $id);
        $query = $this->db->get('roles');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

///======================

    public function find($role_id = null)
    {
        $this->db->select()->from('permission_group');
        $this->db->order_by('permission_group.sort_order');
        $query  = $this->db->get();
        $result = $query->result();
        foreach ($result as $key => $value) {           //get permissions from permission_category & roles_permissions using id and role_id from permission_group
            $value->permission_category = $this->getPermissions($value->id, $role_id);
        }
        return $result;
    }

    public function getPermissions($group_id, $role_id)
    {
        $sql   = "SELECT permission_category.*,IFNULL(roles_permissions.id,0) as `roles_permissions_id`,roles_permissions.can_view,roles_permissions.can_add ,roles_permissions.can_edit ,roles_permissions.can_delete FROM `permission_category` LEFT JOIN roles_permissions on permission_category.id = roles_permissions.perm_cat_id and roles_permissions.role_id= $role_id WHERE permission_category.perm_group_id = $group_id ORDER BY `permission_category`.`id`";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getInsertBatch($role_id, $to_be_insert = array(), $to_be_update = array(), $to_be_delete = array())
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        if (!empty($to_be_insert)) {
            $this->db->insert_batch('roles_permissions', $to_be_insert);
        }
        if (!empty($to_be_update)) {
            $this->db->update_batch('roles_permissions', $to_be_update, 'id');
        }

        // # Updating data
        if (!empty($to_be_delete)) {
            $this->db->where('role_id', $role_id);
            $this->db->where_in('id', $to_be_delete);
            $this->db->delete('roles_permissions');
            
            $message = DELETE_RECORD_CONSTANT . " On roles Permissions where Role id  " . $role_id;
            $action = "Delete";
            $record_id = $role_id;
            $this->log($message, $record_id, $action);

        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function count_roles($id,$role_id)
    {
        if($role_id!=7) {
            $query = $this->db->select("*")->join("staff", "staff.id = staff_roles.staff_id")->where("staff_roles.role_id", $id)->where("staff.created_by", $role_id)->where("staff.is_active", 1)->get("staff_roles");

        }else
        {
            $query = $this->db->select("*")->join("staff", "staff.id = staff_roles.staff_id")->where("staff_roles.role_id", $id)->where("staff.is_active", 1)->get("staff_roles");
           
        }
        return $query->num_rows();
    }

    public function getRolefromid($id)
    {
        $query = $this->db->select("roles.*,staff.id as staff_id")->join("roles", "roles.id = staff_roles.role_id")->join("staff", "staff.id = staff_roles.staff_id")->where("staff_roles.role_id", $id)->get("staff_roles");
        return $query->result_array();
    }

    public function  getRoleFromStaffIf($id){
        $query = $this->db->select("roles.*,staff.id as staff_id")->join("roles", "roles.id = staff_roles.role_id")->join("staff", "staff.id = staff_roles.staff_id")->where("staff.id", $id)->get("staff_roles");
        return $query->result_array();
    }


    public function  getRoleFromStaffid($id){
        $this->db->select('*')->from('roles');
        $query = $this->db->where('`id`  IN (SELECT `staff_role_id` FROM `admin_staff` where admin_role_id ='.$id.')', NULL, FALSE);
        $query = $this->db->get();
        return $query->result_array();
    }   

    public function  getStaffIdfromrole($id){
        $this->db->select('staff_roles.id');
        $this->db->from('staff_roles');
        $this->db->where('staff_roles.role_id',$id);
        $query = $this->db->get();
        return $query->result();


    }

    //NEWLY ADDED TO COLLECT ADMIN DATA IN LOGINS 
    public function  getRoleFromStaffUsingLid($id){
        $query = $this->db->select("roles.*,staff.id as staff_id,staff.created_by as created_by,staff_locality.locality,staff.city,staff.locality_id")
        ->join("roles", "roles.id = staff_roles.role_id",'left')
        ->join("staff", "staff.id = staff_roles.staff_id",'left')
        ->join("staff_locality", "staff_locality.id = staff.locality_id",'left')
        ->where("staff.id", $id)->get("staff_roles");
        // echo $this->db->last_query(); die;
        return $query->result_array();
    }

   
    public function getPurdMedofDisbyDocLoc($localityId){
        $query = $this->db->select("staff.*")
        ->join("staff_roles", "staff_roles.staff_id = staff.id")
        ->where("staff.created_by", 73)
        ->where("staff.locality_id", $localityId)
        ->get("staff");
        return $query->result_array();
    }


    public function  getAdminUsingCreatedById($created_by){
        $query = $this->db->select("roles.*,staff.id as staff_id,staff.created_by as created_by")->join("roles", "roles.id = staff_roles.role_id")->join("staff", "staff.id = staff_roles.staff_id")->where("staff.created_by", 73)->where("staff_roles.role_id", $created_by)->get("staff_roles");
        return $query->result_array();
    }

    public function getfromcp(){
        $this->db->select('staff.id');
        
        $this->db->join('staff_roles','staff_roles.staff_id=staff.id');
        $this->db->where('staff.created_by',7);
        $this->db->where('staff_roles.role_id',4);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getfromcpath(){
        $this->db->select('staff.id');
        
        $this->db->join('staff_roles','staff_roles.staff_id=staff.id');
        $this->db->where('staff.created_by',7);
        $this->db->where('staff_roles.role_id',5);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getfromcpradio(){
        $this->db->select('staff.id');
        
        $this->db->join('staff_roles','staff_roles.staff_id=staff.id');
        $this->db->where('staff.created_by',7);
        $this->db->where('staff_roles.role_id',6);
        $this->db->from('staff');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPatientDataByVdId($id){
        $this->db->select('patients.locality_id');
        
        $this->db->join('appointment','visit_details.id=appointment.visit_details_id','left');
        $this->db->join('patients','appointment.patient_id=patients.id','left');
        $this->db->where('visit_details.id',$id);
      
        $this->db->from('visit_details');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    


}
