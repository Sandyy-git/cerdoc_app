<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Medicine_dosage_model extends MY_model
{

    public function addMedicineDosage($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('medicine_dosage', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Medicine Dosage id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('medicine_dosage', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Medicine Dosage id " . $insert_id;
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

    public function add_interval($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id']!=='') {
            $this->db->where('id', $data['id']);
            $this->db->update('dose_interval', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Dose Interval id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('dose_interval', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Dose Interval id " . $insert_id;
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

    public function add_duration($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id']!=='') {
            $this->db->where('id', $data['id']);
            $this->db->update('dose_duration', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Dose Duration id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('dose_duration', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Dose Duration id " . $insert_id;
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

    //  public function getIntervalDosage($id = null)
    // {
    //     $userLoggedIn = $this->customlib->getLoggedInUserId();
       
    //     $parent_child = $this->notificationsetting_model->getstaffidtosendSysNotification($userLoggedIn);
    //     if (!empty($id)) {
    //         $query = $this->db->where("id", $id)->get('dose_interval');
    //         return $query->row_array();
    //     } else {
    //         if($parent_child == 7){
    //             $this->db->where('dose_interval.added_by',$parent_child[0]['id']);
    //         }else{
    //             $this->db->where('dose_interval.added_by',$parent_child[0]['id']);
    //             // $this->db->or_where('dose_interval.is_central_login','yes');
    //         }

    //         $query = $this->db->get("dose_interval");
    //         return $query->result_array();
    //     }
    // }

    public function getIntervalDosage($id = null)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        // var_dump($userRoleIn); die;

        // if( $userRoleIn[0]['created_by'] == 7){
        //     $getfromcp = $this->role_model->getfromcp();
        //     $userLoggedIn = $getfromcp[0]['id'];

        // }else{
        // $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        // $userLoggedIn = $userGetAdmin[0]['staff_id'];
        // }

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
        }else{
            $userLoggedIn = $userLoggedInFirst;
        }
        

        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('dose_interval');
            return $query->row_array();
        } else {
            if($parent_child == 7){
                $this->db->where('dose_interval.added_by',$userLoggedIn);
            }else{
                $this->db->where('dose_interval.added_by',$userLoggedIn);
                // $this->db->or_where('dose_interval.is_central_login','yes');
            }

            $query = $this->db->get("dose_interval");
            return $query->result_array();
        }

    }


    public function getDurationDosage($id = null)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        // var_dump($userRoleIn); die;

        // if( $userRoleIn[0]['created_by'] == 7){
        //     $getfromcp = $this->role_model->getfromcp();
        //     $userLoggedIn = $getfromcp[0]['id'];

        // }else{
        // $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        // $userLoggedIn = $userGetAdmin[0]['staff_id'];
        // }

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
        }else{
            $userLoggedIn = $userLoggedInFirst;
        }
        

        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get('dose_duration');
            return $query->row_array();
        } else {

            if($parent_child == 7){
                $this->db->where('dose_duration.added_by',$userLoggedIn);
            }else{
                $this->db->where('dose_duration.added_by',$userLoggedIn);
                // $this->db->or_where('dose_duration.is_central_login','yes');
            }

            $query = $this->db->get("dose_duration");
            return $query->result_array();
        }
    }
    
    public function getMedicineDosage($id = null)
    {
        $userLoggedIn = $this->customlib->getLoggedInUserId();
        if (!empty($id)) {
            $query = $this->db->select('medicine_dosage.*,medicine_category.medicine_category')
                ->join('medicine_category', 'medicine_dosage.medicine_category_id = medicine_category.id')
                ->where('medicine_dosage.id', $id)
                ->get('medicine_dosage');
            return $query->row_array();
        } else {

            if($userLoggedIn == 7){
                $this->db->where('medicine_dosage.added_by',$userLoggedIn);
            }else{
                $this->db->where('medicine_dosage.added_by',$userLoggedIn);
                $this->db->or_where('medicine_dosage.is_central_login','yes');
            }

            $query = $this->db->select('medicine_dosage.*,medicine_category.medicine_category,charge_units.unit')
                ->join('medicine_category', 'medicine_dosage.medicine_category_id = medicine_category.id')
                 ->join('charge_units', 'charge_units.id = medicine_dosage.charge_units_id')
                ->get('medicine_dosage');
            return $query->result_array();
        }
    }

    public function getCategoryDosages()
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        if( $userRoleIn[0]['created_by'] == 7){
            $userLoggedIn = $userRoleIn[0]['staff_id'];
        }else{
        $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        $userLoggedIn = $userGetAdmin[0]['staff_id'];
        }

        if($userLoggedIn == 7){
            $this->db->where('medicine_dosage.added_by',$userLoggedIn);
        }else{
            $this->db->where('medicine_dosage.added_by',$userLoggedIn);
            $this->db->or_where('medicine_dosage.is_central_login','yes');
        }

      $query = $this->db->select('medicine_dosage.*,medicine_category.medicine_category,charge_units.unit')
                ->join('medicine_category', 'medicine_dosage.medicine_category_id = medicine_category.id')
                ->join('charge_units', 'charge_units.id = medicine_dosage.charge_units_id','left')
                ->get('medicine_dosage');
            $result=$query->result();
            $medicine_array=array();
            if(!empty($result)){
foreach ($result as $result_key => $result_value) {
  $medicine_array[$result_value->medicine_category_id][]=$result_value;
}
            }
        return $medicine_array;
        
    }

    public function getDosageByMedicine($medicine)
    {

    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_dosage");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Dosage id " . $id;
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

    public function deletemedicationdosage($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medication_report");
        $message = DELETE_RECORD_CONSTANT . " On Medicine Report id " . $id;
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


    public function get_doseIntervallist()
    {
        $userLoggedIn = $this->customlib->getLoggedInUserId();
        if($userLoggedIn == 7){
            $this->datatables->where('dose_interval.added_by',$userLoggedIn);
        }else{
            $this->datatables->where('dose_interval.added_by',$userLoggedIn);
            $this->datatables->or_where('dose_interval.is_central_login','yes');
        }
        $this->datatables
            ->select('*')
            ->searchable('name')
            ->orderable('name')
            ->sort('id', 'desc')
            ->from('dose_interval');
        return $this->datatables->generate('json');
    }
    
    public function get_intervalbyid($id){
        return $this->db->select('*')->from('dose_interval')->where('id',$id)->get()->row_array();

    }

    public function delete_doseInterval($id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("dose_interval");
        $message = DELETE_RECORD_CONSTANT . " On Dose Interval id " . $id;
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

    public function get_dosedurationlist()
    {
        $userLoggedIn = $this->customlib->getLoggedInUserId();
        if($userLoggedIn == 7){
            $this->datatables->where('dose_duration.added_by',$userLoggedIn);
        }else{
            $this->datatables->where('dose_duration.added_by',$userLoggedIn);
            $this->datatables->or_where('dose_duration.is_central_login','yes');
        }
        $this->datatables
            ->select('*')
            ->searchable('name')
            ->orderable('name')
            ->sort('id', 'desc')
            ->from('dose_duration');
        return $this->datatables->generate('json');
    }
    
    public function get_durationbyid($id){
        return $this->db->select('*')->from('dose_duration')->where('id',$id)->get()->row_array();

    }

    public function delete_doseduration($id){
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("dose_duration");
        $message = DELETE_RECORD_CONSTANT . " On Dose Duration id " . $id;
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


    public function valid_medicine_interval($str)
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

        $dose_interval = $this->input->post('name');
        $id                = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_interval_exists($dose_interval, $id,$userLoggedIn)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_interval_exists($name, $id,$userLoggedIn)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'name' => $name);
            $query = $this->db->where($data)->get('dose_interval');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('name', $name);
            $this->db->where('added_by', $userLoggedIn);

            $query = $this->db->get('dose_interval');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function valid_medicine_duration($str)
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

        $dose_duration = $this->input->post('name');
        $id                = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_duration_exists($dose_duration, $id,$userLoggedIn)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_duration_exists($name, $id,$userLoggedIn)
    {
        if ($id != 0) {
            $data  = array('id != ' => $id, 'name' => $name);
            $query = $this->db->where($data)->get('dose_duration');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('name', $name);
            $this->db->where('added_by', $userLoggedIn);

            $query = $this->db->get('dose_duration');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }


}
