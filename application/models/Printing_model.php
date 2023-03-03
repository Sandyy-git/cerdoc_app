<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Printing_model extends MY_Model
{

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("print_setting", $data);            
            $message = UPDATE_RECORD_CONSTANT . " On Print Setting id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            
        } else {
            $this->db->insert("print_setting", $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Print Setting id " . $insert_id;
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

    public function get($id = '', $setting_for = '')
    {
        // var_dump($id);
        // var_dump($setting_for); die;   //for pharma - 0,opdpre//

        $staffId          = $this->customlib->getStaffID();   //for pha-124//cp-121//dis-122//sa-1
        $role                        = $this->customlib->getStaffRole();
        $role_id                     = json_decode($role)->id;
        // var_dump($staffId); die;  
        if($staffId !=null){
            $print_setting = $this->getStaffIdfromprinting($staffId,$setting_for);
            // var_dump($print_setting); die;
            if(!empty($print_setting)){
                $id= $print_setting[0]->id;  //for pha-1117//cp-1087//dis-1097//sa-""
            }elseif ($this->getParentStaffId($staffId) !=null && $role_id != 4){
                $pid = $this->getParentStaffId($staffId);
                $printstaffId = $this->getStaffIdfromprinting($pid[0]->parent_staff_id,$setting_for);
                if(!empty($printstaffId)){
                    $staff = getStaffIdfromprinting($this->getParentStaffId($staffId));
                    $id = $staff[0]->id;
                }
            }
        }
        if (!empty($id)) {

            if(!empty($setting_for)){
                $query =  $this->db->where("id", $id)->where("setting_for", $setting_for)->get("print_setting");
                $retrun =  $query->row_array();
                // echo $this->db->last_query(); die;
                return  $retrun;
            }else{
                $query =  $this->db->where("id", $id)->get("print_setting");
                $retrun =  $query->row_array();
                // echo $this->db->last_query(); die;
                return  $retrun;
            }

        } else {
            $query = $this->db->where("setting_for", $setting_for)->where("staff_id", "0")->get("print_setting");
            $retrun = $query->row_array();
            // echo $this->db->last_query(); die;
            return $retrun;
        }
    }

    public function getheaderfooter($setting_for)
    {        
        $query = $this->db->where("setting_for", $setting_for)->get("print_setting");
        return $query->row_array();        
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete('print_setting');
        
        $message = DELETE_RECORD_CONSTANT . " On Print Setting id " . $id;
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

    public function  getStaffIdfromprinting($id,$setting_for){
        $this->db->select('print_setting.*');
        $this->db->from('print_setting');
        $this->db->where('print_setting.staff_id',$id);
        if(!empty($setting_for)){
            $this->db->where('print_setting.setting_for',$setting_for);
        }
        $query = $this->db->get();
        // echo $this->db->last_query(); die;
        return $query->result();


    }

    public function  getParentStaffId($id){
        $this->db->select('parent_child.parent_staff_id');
        $this->db->from('parent_child');
        $this->db->where('parent_child.child_staff_id',$id);
        $query = $this->db->get();
        return $query->result();


    }

    // public function getPatientBillDetails(){

    // }
}
