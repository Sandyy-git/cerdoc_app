<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pharmacy_model extends MY_Model
{
    public function add($pharmacy)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('pharmacy', $pharmacy);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On Pharmacy id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
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

    public function addImport($medicine_data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('pharmacy', $medicine_data);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On Pharmacy id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
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

//ORIGINAL FOR STOCK QUANTITY

    // public function get_medicine_stockinfo($pharmacy_id)
    // {
    //     return $this->db->select('medicine_batch_details.available_quantity,`pharmacy`.`min_level`, (SELECT sum(available_quantity) FROM `medicine_batch_details` WHERE pharmacy_id=pharmacy.id) as `total_qty`,IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity')->from('medicine_batch_details')->join('pharmacy', 'pharmacy.id=medicine_batch_details.pharmacy_id', 'inner')->where('pharmacy.id', $pharmacy_id)->get()->row_array();
    // }
    
    //IN CASE OF ADMIN WISE MED SUPPLY
    public function get_medicine_stockinfo($pharmacy_id)
    {

        $visit_details_id = $this->input->post("visit_details_id");
        $getPatLocality = $this->role_model->getPatientDataByVdId($visit_details_id);
            

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
      
        
        if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            //By Passing Doctor locality
            // $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
           
            //By Passing Patient Locality
           $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($getPatLocality[0]['locality_id']);
            
           $staff_items = array();
             foreach($getPurchasedMedlistofDisbyDoclocation as $username) {
             $staff_items[] = $username['id'];
             }
            //  var_dump($staff_items); die;

            //  return $this->db->select('medicine_batch_details.available_quantity,`pharmacy`.`min_level`, `medicine_batch_details`.`available_quantity` as `total_qty`,IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity')
            return $this->db->select('medicine_batch_details.available_quantity,`pharmacy`.`min_level`, (SELECT sum(available_quantity) FROM `medicine_batch_details` WHERE pharmacy_id=pharmacy.id) as `total_qty`,IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity') 
            ->from('medicine_batch_details')
             ->join('pharmacy', 'pharmacy.id=medicine_batch_details.pharmacy_id', 'inner')
             ->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left')
            //  ->where('pharmacy.id', $pharmacy_id)   
            ->where('medicine_batch_details.pharmacy_id', $pharmacy_id)   
             ->where_in('supplier_bill_basic.received_by', $staff_items)
             ->get()->result_array();
         }else{

        return $this->db->select('medicine_batch_details.available_quantity,`pharmacy`.`min_level`, (SELECT sum(available_quantity) FROM `medicine_batch_details` WHERE pharmacy_id=pharmacy.id) as `total_qty`,IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity')
        ->from('medicine_batch_details')
        ->join('pharmacy', 'pharmacy.id=medicine_batch_details.pharmacy_id', 'inner')
        ->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left')
        ->where('pharmacy.id', $pharmacy_id)        
        ->get()->row_array();
         }
    }

    //IN CASE OF CHEMIST WISE MED SUPPLYING
    // public function get_medicine_stockinfo($pharmacy_id)
    // {
    //     $userLoggedInFirst = $this->customlib->getLoggedInUserId();
    //     $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);

    //     if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
    //         $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
    //          $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left');
    //      }
    //     $query =  $this->db->select('medicine_batch_details.available_quantity,`pharmacy`.`min_level`, `medicine_batch_details`.`available_quantity` as `total_qty`,IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity')
    //     ->from('medicine_batch_details')
    //     ->join('pharmacy', 'pharmacy.id=medicine_batch_details.pharmacy_id', 'inner')
    //     ->where('medicine_batch_details.id', $pharmacy_id)
    //     ->get()->row_array();
    // //    echo $this->db->last_query(); die;
    //     return $query;
    // }
    
    public function getAllpharmacyRecord()
    {
         //na pharmacy
        $login_user = $this->customlib->getUserData();
        $login_user_id = $login_user['id'];
        // var_dump($login_user_id ); die;
//ne
                //PREV
        $this->datatables
            ->select('pharmacy.*,medicine_category.id as medicine_categoryid,medicine_category.medicine_category,medicine_search_type.search_type as sea_type,(SELECT sum(available_quantity) FROM `medicine_batch_details` WHERE pharmacy_id=pharmacy.id) as `total_qty`')
            ->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'left')
            ->join('medicine_batch_details', 'pharmacy.id = medicine_batch_details.pharmacy_id', 'left')
            ->join('pharmacy_bill_detail', 'pharmacy_bill_detail.medicine_batch_detail_id = medicine_batch_details.id', 'left')
            
            ->join('medicine_search_type', 'medicine_search_type.id = pharmacy.search_type', 'left')

            
            ->searchable('pharmacy.medicine_name,pharmacy.medicine_company,pharmacy. medicine_composition,pharmacy.medicine_category_id,pharmacy.medicine_group')
            ->orderable('pharmacy.id,pharmacy.medicine_name,pharmacy.medicine_company,pharmacy. medicine_composition,pharmacy.medicine_category_id,pharmacy.medicine_group,pharmacy.unit')
            ->group_by('pharmacy.id')
            ->sort('pharmacy.id', 'desc')
            ->where('`pharmacy`.`medicine_category_id`=`medicine_category`.`id`') //this line comm while adding below line pharmacy med sep
           //na
                    ->where('pharmacy.added_by', $login_user_id)
                    // ->or_where('pharmacy.is_central_pharm', 'yes')

                    //ne
            ->from('pharmacy');
        return $this->datatables->generate('json');
    }


    public function getAllpharmacyRecordtoDownload()
    {
         //na pharmacy
        $login_user = $this->customlib->getUserData();
        $login_user_id = $login_user['id'];
        // var_dump($login_user_id ); die;
//ne
                //PREV
        $this->datatables
            ->select('pharmacy.*,medicine_category.id as medicine_categoryid,medicine_category.medicine_category,medicine_search_type.search_type as sea_type,(SELECT sum(available_quantity) FROM `medicine_batch_details` WHERE pharmacy_id=pharmacy.id) as `total_qty`')
            ->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'left')
            ->join('medicine_batch_details', 'pharmacy.id = medicine_batch_details.pharmacy_id', 'left')
            ->join('pharmacy_bill_detail', 'pharmacy_bill_detail.medicine_batch_detail_id = medicine_batch_details.id', 'left')
            
            ->join('medicine_search_type', 'medicine_search_type.id = pharmacy.search_type', 'left')

            
            ->searchable('pharmacy.medicine_name,pharmacy.medicine_company,pharmacy. medicine_composition,pharmacy.medicine_category_id,pharmacy.medicine_group')
            ->orderable('pharmacy.id,pharmacy.medicine_name,pharmacy.medicine_company,pharmacy. medicine_composition,pharmacy.medicine_category_id,pharmacy.medicine_group,pharmacy.unit')
            ->group_by('pharmacy.id')
            ->sort('pharmacy.id', 'desc')
            ->where('`pharmacy`.`medicine_category_id`=`medicine_category`.`id`') //this line comm while adding below line pharmacy med sep
           //na
                    ->where('pharmacy.added_by', 121)
                    ->where('pharmacy.active', 1)
                    // ->or_where('pharmacy.is_central_pharm', 'yes')

                    //ne
            ->from('pharmacy');
        return $this->datatables->generate('json');
    }
    
    public function getAvailableQuantity($pharmacy_id)
    {
        $this->db->select('sum(pharmacy_bill_detail.quantity) as used_quantity');
        $this->db->join('pharmacy_bill_detail', 'pharmacy_bill_detail.medicine_batch_detail_id = medicine_batch_details.id');
        $this->db->where('`medicine_batch_details`.`pharmacy_id`', $pharmacy_id); 
        $query = $this->db->get('medicine_batch_details');
        if ($query->num_rows() == 1) {
            return $query->num_rows();
        } else {
            return false;
        }
    }
    
    public function searchFullText()
    {
        $this->db->select('pharmacy.*,medicine_category.id as medicine_category_id,medicine_category.medicine_category');
        $this->db->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'left');
        $this->db->where('`pharmacy`.`medicine_category_id`=`medicine_category`.`id`');
        $this->db->order_by('pharmacy.medicine_name');
        $query = $this->db->get('pharmacy');
        return $query->result_array();
    }

    public function searchtestdata()
    {
        $this->db->select('pharmacy.*');
        $this->db->order_by('pharmacy.medicine_name');
        $query = $this->db->get('pharmacy');
        return $query->result_array();
    }


    public function getpatientPharmacyYearCounter($patient_id,$year)
    {
    $sql= "SELECT count(*) as `total_visits`,Year(date) as `year` FROM `pharmacy_bill_basic` WHERE YEAR(date) >= ".$this->db->escape($year)." AND patient_id=".$this->db->escape($patient_id)." GROUP BY  YEAR(date)";

      $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function check_medicine_exists($medicine_name,$added_by,$med_cat_check)
    {
        //PREV
        //$this->db->where(array('medicine_category_id' => $medicine_category_id, 'medicine_name' => $medicine_name));
       //na pharmacy

       //WITH CAT ID
        // $this->db->where(array('medicine_category_id' => $medicine_category_id, 'medicine_name' => $medicine_name,'pharmacy.added_by' => $added_by));

        //WITHOUT CAT ID
        $this->db->where(array('medicine_name' => $medicine_name,'pharmacy.added_by' => $added_by,'pharmacy.medicine_category_id' => $med_cat_check));
        $query = $this->db->join("medicine_category", "medicine_category.id = pharmacy.medicine_category_id")->get('pharmacy');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // public function check_distributor_stocks($medicine_name,$added_by,$med_cat_check)
    // {
    //     //PREV
    //     //$this->db->where(array('medicine_category_id' => $medicine_category_id, 'medicine_name' => $medicine_name));
    //    //na pharmacy

    //    //WITH CAT ID
    //     // $this->db->where(array('medicine_category_id' => $medicine_category_id, 'medicine_name' => $medicine_name,'pharmacy.added_by' => $added_by));

    //     //WITHOUT CAT ID
    //     $this->db->where(array('medicine_name' => $medicine_name,'pharmacy.added_by' => $added_by,'pharmacy.medicine_category_id' => $med_cat_check));
    //     $query = $this->db->join("medicine_category", "medicine_category.id = pharmacy.medicine_category_id")->get('pharmacy');
    //     if ($query->num_rows() > 0) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function check_medicine_exists_approved($medicine_name,$added_by,$med_cat_check)
    {
        $this->db->where(array('medicine_name' => $medicine_name,'pharmacy.added_by' => $added_by,'pharmacy.medicine_category_id' => $med_cat_check));
        $query = $this->db->join("medicine_category", "medicine_category.id = pharmacy.medicine_category_id")->get('pharmacy');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_medicine_exists_productId($productId)
    {
        $this->db->where(array('pharmacy.product_id' => $productId));
        $query = $this->db->join("medicine_category", "medicine_category.id = pharmacy.medicine_category_id")->get('pharmacy');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    

    public function bulkdelete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($id)) {
            $this->db->where_in('id', $id);
            $this->db->delete('pharmacy');
            $message = DELETE_RECORD_CONSTANT . " On Pharmacy id " . $id;
            $action = "Delete";
            $record_id = $id;
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

    public function searchFullTextPurchase()
    {
        $this->db->select('supplier_bill_detail.*,supplier_bill_basic.supplier_id,supplier_bill_basic.supplier_name,supplier_bill_basic.total,supplier_bill_basic.net_amount,medicine_supplier.medicine_supplier,medicine_supplier.supplier_person,medicine_supplier.supplier_person,medicine_supplier.contact,medicine_supplier.supplier_person_contact,medicine_supplier.address,medicine_category,pharmacy.medicine_name');
        $this->db->join('supplier_bill_basic', 'supplier_bill_detail.supplier_bill_basic_id=supplier_bill_basic.id');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id=medicine_supplier.id');
        $this->db->join('medicine_category', 'supplier_bill_detail.medicine_category_id = medicine_category.id', 'left');
        $this->db->join('pharmacy', 'supplier_bill_detail.medicine_name = pharmacy.id', 'left');
        $query = $this->db->get('supplier_bill_detail');
        return $query->result_array();
    }

    public function getDetails($id)
    {
        $this->db->select('pharmacy.*,medicine_category.id as medicine_category_id,medicine_category.medicine_category,pharmacy.added_by');
        $this->db->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id', 'inner');
        $this->db->where('pharmacy.id', $id);
        $this->db->order_by('pharmacy.id', 'desc');
        $query = $this->db->get('pharmacy');
        return $query->row_array();
    }

    public function update($pharmacy)
    {
        $query = $this->db->where('id', $pharmacy['id'])
            ->update('pharmacy', $pharmacy);
    }

    public function qtyupdatetombdtable($mbdupdate)
    {
        $query = $this->db->where('id', $mbdupdate['id'])
            ->update('medicine_batch_details', $mbdupdate);
    }

    

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete('pharmacy');
        $message = DELETE_RECORD_CONSTANT . " On Pharmacy id " . $id;
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

    public function getPharmacy($id = null)
    {
//PREV
        $query = $this->db->get('pharmacy');
        return $query->result_array();
    }

    public function medicineDetail($medicine_batch)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('medicine_batch_details', $medicine_batch);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On Medicine Batch Details id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
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

    public function getMedicineBatch($pharm_id)
    {
        $this->db->select('medicine_batch_details.*, pharmacy.id as pharmacy_id, pharmacy.medicine_name,pharmacy.added_by');
        $this->db->join('pharmacy', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'inner');
        $this->db->where('pharmacy.id', $pharm_id);
        $query = $this->db->get('medicine_batch_details');
        return $query->result();
    }

    public function getMedicineName()
    {
        $query = $this->db->select('pharmacy.id,pharmacy.medicine_name')->get('pharmacy');
        return $query->result_array();
    }


    public function getMedicineforRefWind()
    {

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        
      
        if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
            $staff_items = array();
             foreach($getPurchasedMedlistofDisbyDoclocation as $username) {
             $staff_items[] = $username['id'];
             }
            //  var_dump($staff_items); die;
 
             $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'inner');
             $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'inner');
             $this->db->where_in('supplier_bill_basic.received_by', $staff_items);

         }

         if($userRoleIn[0]['created_by'] != 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
            // $col_dis = $this->db->select('staff.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
            //  ->join('staff_roles', 'staff.id = staff_roles.staff_id')
            //  ->where("staff.is_active",1)
            //  ->where("staff_roles.role_id",$userRoleIn[0]['created_by'])
            //  ->get("staff");
            //  $dis_id = $col_dis->row_array();
            //  $disId = $dis_id['id'];

            $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'right');
            $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'right');
            // $this->db->where('supplier_bill_basic.received_by', $disId);
            $this->db->where('supplier_bill_basic.received_by', $userLoggedInFirst);
         }


            if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){

                

                //IN CASE OF ADMIN WISE MED SUPPLY
                // $this->db->select('medicine_batch_details.id as id,pharmacy.medicine_name as medicine_name,pharmacy.medicine_composition as medicine_composition');
                //IN CASE OF CHEMIST WISE MED SUPPLYING
                $this->db->select('pharmacy.*');
                $this->db->group_by("pharmacy.medicine_name");
            }
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        
    }

    public function getMedicineNamePat()
    {
        $query = $this->db->select('pharmacy.id,pharmacy.medicine_name')->get('pharmacy');
        return $query->result_array();
    }

    public function addBill($data, $insert_array, $update_array, $delete_array, $payment_array)
    {    
        // echo "<pre>";
        // print_r($data);
        // print_r($insert_array);
        // print_r($update_array);
        // print_r($delete_array);
        // print_r($payment_array); die;
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        if (isset($data['id']) && $data['id'] != 0) {
            $insert_id = $data['id'];
            $this->db->where('id', $data['id'])
                ->update('pharmacy_bill_basic', $data);
                
            $message = UPDATE_RECORD_CONSTANT . " On Pharmacy Bill Basic id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            
        } else {

            $this->db->insert('pharmacy_bill_basic', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Pharmacy Bill Basic id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if (!empty($delete_array)) {

            $this->db->where_in('id', $delete_array);
            $this->db->delete('pharmacy_bill_detail');
        }

        if (isset($update_array) && !empty($update_array)) {

            $this->db->update_batch('pharmacy_bill_detail', $update_array, 'id');
        }

        if (isset($insert_array) && !empty($insert_array)) {

            $total_rec = count($insert_array);
            for ($i = 0; $i < $total_rec; $i++) {
                $insert_array[$i]['pharmacy_bill_basic_id'] = $insert_id;
            }
            $this->db->insert_batch('pharmacy_bill_detail', $insert_array);
        }

        if (isset($payment_array) && !empty($payment_array)) {
            $role                        = $this->customlib->getStaffRole();
            $role_id                     = json_decode($role)->id;
            $staffid = $this->customlib->getStaffID();
            $parentid = 1;
            if($role_id!=7) { //means it is not super admin, so take parent id of staff id
                $parentid = $this->transaction_model->getParentStaffId($staffid);
            }else{
                $parentid = $staffid; // super admin is it's own parent

            }
            $payment_array['parent'] = $parentid[0]->parent_staff_id;
            $payment_array['pharmacy_bill_basic_id'] = $insert_id;
            $payment_array['case_reference_id']      = $data['case_reference_id'];
            $this->db->insert("transactions", $payment_array);
        }

        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $insert_id;
        }
    }
 
    public function addBillSupplier($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("supplier_bill_basic", $data);
            $message = UPDATE_RECORD_CONSTANT . " On Supplier Bill Basic id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert("supplier_bill_basic", $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Supplier Bill Basic id " . $insert_id;
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

    public function addBillBatch($data)
    {
        $query = $this->db->insert_batch('pharmacy_bill_detail', $data);
    }

    public function addBillBatchSupplier($data)
    {
        $query = $this->db->insert_batch('supplier_bill_detail', $data);
    }

    public function addBillMedicineBatchSupplier($data1)
    {
        $query = $this->db->insert_batch('medicine_batch_details', $data1);
    }

    public function updateBillBatch($data)
    {    
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('pharmacy_bill_basic_id', $data['id'])->update('pharmacy_bill_detail');         
        $message = UPDATE_RECORD_CONSTANT . " On Pharmacy Bill Basic_id id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function updateBillBatchSupplier($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('supplier_bill_basic_id', $data['id'])->update('supplier_bill_basic_id');
        $message = UPDATE_RECORD_CONSTANT . " On Pharmacy Bill Basic_id id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function updateBillDetail($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id'])->update('pharmacy_bill_detail', $data);
        $message = UPDATE_RECORD_CONSTANT . " On Pharmacy Bill Detail id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function updateBillSupplierDetail($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id'])->update('supplier_bill_detail', $data);
        $message = UPDATE_RECORD_CONSTANT . " On Supplier Bill Detail id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function updateMedicineBatchDetail($data1)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $query = $this->db->where('id', $data1['id'])->update('medicine_batch_details', $data1);        
        // $this->db->last_query();
        $message = UPDATE_RECORD_CONSTANT . " On Medicine Batch Details id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function deletePharmacyBill($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $query = $this->db->where("pharmacy_bill_basic_id", $id)->delete("pharmacy_bill_detail");
        if ($query) {
            $this->db->where("id", $id)->delete("pharmacy_bill_basic");
            $this->customfield_model->delete_custom_fieldRecord($id, 'pharmacy');
        }
        
        $message = DELETE_RECORD_CONSTANT . " On Pharmacy Bill Detail id " . $id;
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

    public function deleteSupplierBill($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $query = $this->db->where("supplier_bill_basic_id", $id)->delete("medicine_batch_details");
        if ($query) {
            $this->db->where("id", $id)->delete("supplier_bill_basic");
        }
        
        $message = DELETE_RECORD_CONSTANT . " On Medicine Batch Details id " . $id;
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

    public function getMaxId()
    {
        $query  = $this->db->select('max(id) as purchase_no')->get("supplier_bill_basic");
        $result = $query->row_array();
        return $result["purchase_no"];
    }
    
    public function getindate($purchase_id)
    {
        $query = $this->db->select('supplier_bill_basic.*,')
            ->where('supplier_bill_basic.id', $purchase_id)
            ->get('supplier_bill_basic');
        return $query->row_array();
    }

    public function getdate($id)
    {
        $query = $this->db->select('pharmacy_bill_basic.*,')
            ->where('pharmacy_bill_basic.id', $id)
            ->get('pharmacy_bill_basic');
        return $query->row_array();
    }
    
    public function getSupplier()
    {
        $query = $this->db->select('supplier_bill_basic.*,medicine_supplier.supplier')
            ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
            ->order_by('id', 'desc')
            ->get('supplier_bill_basic');
        return $query->result_array();
    }

    public function getAllpharmacypurchaseRecord()
    {
         //na pharmacy
         $login_user = $this->customlib->getUserData();
         $login_user_id = $login_user['created_by'];
        //  var_dump($login_user_id); die;
         
         if($login_user_id == 7){
            $staffRoles_id = $login_user['id'];

            $this->datatables
            ->select('supplier_bill_basic.*,medicine_supplier.supplier')
            ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
            ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
            ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
            ->sort('supplier_bill_basic.id', 'desc')
            ->where('supplier_bill_basic.received_by', $staffRoles_id)
            ->from('supplier_bill_basic');
        return $this->datatables->generate('json');

         }elseif($login_user_id == 73){
            $staffRoles_id = $login_user['id'];

            $this->datatables
            ->select('supplier_bill_basic.*,medicine_supplier.supplier')
            ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
            ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
            ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
            ->sort('supplier_bill_basic.id', 'desc')
            ->where('supplier_bill_basic.received_by', $staffRoles_id)
            ->from('supplier_bill_basic');
        return $this->datatables->generate('json');

         }else{

         $staffRoles_id = $login_user['id'];
         $this->datatables
        
        ->select('supplier_bill_basic.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
        // ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
        ->join('staff', 'staff.id = supplier_bill_basic.received_by')
        ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,staff.name as supplier')
        ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,staff.name as supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
        ->sort('supplier_bill_basic.id', 'desc')
        ->where('supplier_bill_basic.received_by', $staffRoles_id)
        ->from('supplier_bill_basic');

     return $this->datatables->generate('json');

         }
         //na e
        //  echo "<pre>";

        //  $this->datatables
        //     ->select('supplier_bill_basic.*,medicine_supplier.supplier')
        //     ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
        //     ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
        //     ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
        //     ->sort('supplier_bill_basic.id', 'desc')
        //     ->where('supplier_bill_basic.received_by', $staffRoles_id)
        //     ->from('supplier_bill_basic');
        // return $this->datatables->generate('json');

       
    }


    public function getAllpharmacypurchaseRecordStockPush($arry_pha)
    {
         //na pharmacy
         $login_user = $this->customlib->getUserData();
         $login_user_id = $login_user['created_by'];
         
         if($login_user_id == 7){
            $staffRoles_id = $login_user['id'];

            $this->datatables
            ->select('supplier_bill_basic.*,medicine_supplier.supplier')
            ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
            ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
            ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
            ->sort('supplier_bill_basic.id', 'desc')
            ->where('supplier_bill_basic.received_by', $staffRoles_id)
            ->from('supplier_bill_basic');
        return $this->datatables->generate('json');

         }elseif($login_user_id == 73){
            $staffRoles_id = $login_user['id'];

            $this->datatables
            ->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
            ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id','left')
            ->join('staff', 'staff.id = supplier_bill_basic.received_by','left')
            ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
            ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
            ->sort('supplier_bill_basic.id', 'desc')
            ->where_in('supplier_bill_basic.received_by', $arry_pha)
            ->from('supplier_bill_basic');
        return $this->datatables->generate('json');

         }else{

         $staffRoles_id = $login_user['id'];
         $this->datatables
        
        ->select('supplier_bill_basic.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
        // ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
        ->join('staff', 'staff.id = supplier_bill_basic.received_by')
        ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,staff.name as supplier')
        ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,staff.name as supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
        ->sort('supplier_bill_basic.id', 'desc')
        ->where('supplier_bill_basic.received_by', $staffRoles_id)
        ->from('supplier_bill_basic');

     return $this->datatables->generate('json');

         }
         //na e
        //  echo "<pre>";

        //  $this->datatables
        //     ->select('supplier_bill_basic.*,medicine_supplier.supplier')
        //     ->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id')
        //     ->searchable('supplier_bill_basic.id,supplier_bill_basic.invoice_no,supplier')
        //     ->orderable('supplier_bill_basic.id,supplier_bill_basic.date,supplier_bill_basic.invoice_no,supplier,supplier_bill_basic.total,supplier_bill_basic.tax,supplier_bill_basic.discount,supplier_bill_basic.net_amount')
        //     ->sort('supplier_bill_basic.id', 'desc')
        //     ->where('supplier_bill_basic.received_by', $staffRoles_id)
        //     ->from('supplier_bill_basic');
        // return $this->datatables->generate('json');

       
    }

    public function getBillBasic($limit = "", $start = "")
    {
        $query = $this->db->select('pharmacy_bill_basic.*,patients.patient_name')
            ->order_by('pharmacy_bill_basic.id', 'desc')
            ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id')
            ->where("patients.is_active", "yes")->limit($limit, $start)
            ->get('pharmacy_bill_basic');
        return $query->result_array();
    }

    //WITHOUT SHOWING BILLING IN LINKED ADMINS
//     public function getAllpharmacybillRecord()
//     {

//          //na pharmacy
//          $login_user_id = $this->customlib->getLoggedInUserID();
//          //na e

//         $custom_fields             = $this->customfield_model->get_custom_fields('pharmacy', 1);
//         $custom_field_column_array = array();

//         $field_var_array = array();
//         $i               = 1;
//         if (!empty($custom_fields)) {
//             foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
//                 $tb_counter = "table_custom_" . $i;
//                 array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
//                 array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
//                 $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
//                 $i++;
//             }
//         }

//         $field_variable      = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
//         $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
//         $this->datatables
//             ->select('pharmacy_bill_basic.*,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount, patients.patient_name,patients.id as pid' . $field_variable)
//             ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
          
//             ->searchable('pharmacy_bill_basic.id,pharmacy_bill_basic.discount,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name' . $custom_field_column . ',pharmacy_bill_basic.doctor_name')
//             ->orderable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name' . $custom_field_column . ',pharmacy_bill_basic.discount,pharmacy_bill_basic.net_amount,paid_amount')
//             ->sort('pharmacy_bill_basic.id', 'desc')
//             //na
//             ->where('pharmacy_bill_basic.generated_by', $login_user_id)
// //na e
//             ->from('pharmacy_bill_basic');
             
//         return $this->datatables->generate('json');
//     }




    public function getAllpharmacybillRecord()
    {
        //na pharmacy
        $login_user_id = $this->customlib->getLoggedInUserID();
        //na e

           //
           $login_user_data = $this->customlib->getUserData();
        
           if($login_user_data['created_by'] == 7){
               $staff_collect = $this->notificationsetting_model->getStaffChild($login_user_id);
                $coll_array = array();
           foreach($staff_collect as $sc_value){
               $coll_array[] = $sc_value['child_staff_id'];
           }
           // $check_pharm_billgenby = $this->notificationsetting_model->checkPharmBillgenby($coll_array);
           
   
           $custom_fields             = $this->customfield_model->get_custom_fields('pharmacy', 1);
           $custom_field_column_array = array();
   
           $field_var_array = array();
           $i               = 1;
           if (!empty($custom_fields)) {
               foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                   $tb_counter = "table_custom_" . $i;
                   array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                   array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                   $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                   $i++;
               }
           }
   
           $field_variable      = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
           $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
           $this->datatables
               ->select('pharmacy_bill_basic.*,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount, patients.patient_name,patients.id as pid' . $field_variable)
               ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
             
               ->searchable('pharmacy_bill_basic.id,pharmacy_bill_basic.discount,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name' . $custom_field_column . ',pharmacy_bill_basic.doctor_name')
               ->orderable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name' . $custom_field_column . ',pharmacy_bill_basic.discount,pharmacy_bill_basic.net_amount,paid_amount')
               ->sort('pharmacy_bill_basic.id', 'desc')
               
               ->where_in('pharmacy_bill_basic.generated_by', $coll_array)
               
               ->from('pharmacy_bill_basic');
              
           return $this->datatables->generate('json');
            
    }elseif($login_user_data['created_by'] != 7 && $login_user_data['created_by'] != 0){

        $custom_fields             = $this->customfield_model->get_custom_fields('pharmacy', 1);
        $custom_field_column_array = array();

        $field_var_array = array();
        $i               = 1;
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable      = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
        $this->datatables
            ->select('pharmacy_bill_basic.*,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount, patients.patient_name,patients.id as pid' . $field_variable)
            ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
        
            ->searchable('pharmacy_bill_basic.id,pharmacy_bill_basic.discount,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name' . $custom_field_column . ',pharmacy_bill_basic.doctor_name')
            ->orderable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name' . $custom_field_column . ',pharmacy_bill_basic.discount,pharmacy_bill_basic.net_amount,paid_amount')
            ->sort('pharmacy_bill_basic.id', 'desc')
            //na
            ->where('pharmacy_bill_basic.generated_by', $login_user_id)
    //na e
            ->from('pharmacy_bill_basic');
            
        return $this->datatables->generate('json');
    }elseif($login_user_data['created_by'] == 0){
            
        $custom_fields             = $this->customfield_model->get_custom_fields('pharmacy', 1);
        $custom_field_column_array = array();

        $field_var_array = array();
        $i               = 1;
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable      = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
        $this->datatables
            ->select('pharmacy_bill_basic.*,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount, patients.patient_name,patients.id as pid' . $field_variable)
            ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
            
            ->searchable('pharmacy_bill_basic.id,pharmacy_bill_basic.discount,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name' . $custom_field_column . ',pharmacy_bill_basic.doctor_name')
            ->orderable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name' . $custom_field_column . ',pharmacy_bill_basic.discount,pharmacy_bill_basic.net_amount,paid_amount')
            ->sort('pharmacy_bill_basic.id', 'desc')
            ->from('pharmacy_bill_basic');
        return $this->datatables->generate('json');
   }
    }



    public function getpharmacybillByCaseId($case_id)
    {
        $query=$this->db->select('pharmacy_bill_basic.*,IFNULL((SELECT sum(transactions.amount) from transactions WHERE transactions.pharmacy_bill_basic_id=pharmacy_bill_basic.id),0) as `amount_paid`,patients.patient_name,patients.id as patient_id')
            ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
            ->where('pharmacy_bill_basic.case_reference_id', $case_id)           
        ->get('pharmacy_bill_basic');
        return $query->result();
    }

    public function getAllpharmacybillByCaseId($case_id)
    {
        $this->datatables
            ->select('pharmacy_bill_basic.*,sum(transactions.amount) as paid_amount,patients.patient_name,patients.id as patient_unique_id')
            ->join('patients', 'patients.id = pharmacy_bill_basic.patient_id', 'left')
            ->join('transactions', 'transactions.pharmacy_bill_basic_id = pharmacy_bill_basic.id', 'left')
            ->searchable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name')
            ->orderable('pharmacy_bill_basic.id,pharmacy_bill_basic.case_reference_id,pharmacy_bill_basic.date,patients.patient_name,pharmacy_bill_basic.doctor_name,pharmacy_bill_basic.net_amount,paid_amount')
            ->group_by('transactions.pharmacy_bill_basic_id')
            ->where('pharmacy_bill_basic.case_reference_id', $case_id)
            ->sort('pharmacy_bill_basic.id', 'desc')
            ->from('pharmacy_bill_basic');
        return $this->datatables->generate('json');
    }




    public function totalPatientPharmacy($patient_id)
    {
        $query = $this->db->select('count(pharmacy_bill_basic.patient_id) as total')
            ->where('patient_id', $patient_id)
            ->get('pharmacy_bill_basic');
        return $query->row_array();
    }


    public function getBillBasicPatient($patient_id)
    {
        $i                         = 1;
        $custom_fields             = $this->customfield_model->get_custom_fields('pharmacy', '','','', 1);
        $custom_field_column_array = array();

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable      = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
        $this->db->select('pharmacy_bill_basic.*,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount,patients.patient_name,patients.id as pid' . $field_variable);
        $this->db->join('patients', 'patients.id = pharmacy_bill_basic.patient_id');
        $this->db->where('pharmacy_bill_basic.patient_id', $patient_id);
        $query = $this->db->get('pharmacy_bill_basic');
        return $query->result_array();
    }            

    // public function get_medicine_name($medicine_category_id)
    // {
    //     $this->db->select('pharmacy.*');
    //     $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
    //     $this->db->order_by("pharmacy.valuep desc");
    //     $query = $this->db->get('pharmacy');
    //     return $query->result_array();

    // }

    //MED CAT ID
    // public function get_medicine_name($medicine_category_id)
    // {
    //     $userLoggedInFirst = $this->customlib->getLoggedInUserId();
    //     $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
    //     if( $userRoleIn[0]['created_by'] == 7){
    //         $userLoggedIn = $userRoleIn[0]['staff_id'];
    //     }else{
    //     $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
    //     $userLoggedIn = $userGetAdmin[0]['staff_id'];
    //     }

    //     $this->db->select('medicine_category.*');
    //     $this->db->where('medicine_category.id', $medicine_category_id);
    //     $query1 = $this->db->get('medicine_category');
    //     $collectMedCatName = $query1->result_array();
    //     if($collectMedCatName[0]['medicine_category'] == "Generic"){
    //         $this->db->select('pharmacy.*');
    //         $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
    //          $this->db->where('pharmacy.added_by', $userLoggedIn);
    //         $this->db->order_by("pharmacy.valuep desc");
    //         $query = $this->db->get('pharmacy');
    //         return $query->result_array();
    //     }elseif($collectMedCatName[0]['medicine_category'] == "Branded"){
    //         $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id');
    //          $this->db->where('pharmacy.added_by', $userLoggedIn);  
    //         $this->db->order_by("pharmacy.valuep desc");
    //         $query = $this->db->get('pharmacy');
    //         return $query->result_array();
    //     }
    // }

    public function get_medicine_name($medicine_category_id,$search_type)
    {
        $visit_details_id = $this->input->post("visit_details_id");
        $getPatLocality = $this->role_model->getPatientDataByVdId($visit_details_id);
        // var_dump($getPatLocality[0]['locality_id']); die;

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        // echo "<pre>";
        // print_r($userRoleIn); die;
        
        $this->db->select('medicine_search_type.*');
        $this->db->where('medicine_search_type.id', $search_type);
        $query1 = $this->db->get('medicine_search_type');
        $collectMedCatName = $query1->result_array();

        // var_dump($userRoleIn[0]['created_by']);
        // var_dump($userRoleIn[0]['name']); die;

        if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            //By Passing Doctor Id
            // $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
          
            //By Passing Patient visit_details_id
            $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($getPatLocality[0]['locality_id']);

            $staff_items = array();
             foreach($getPurchasedMedlistofDisbyDoclocation as $username) {
             $staff_items[] = $username['id'];
             }
            //  var_dump($staff_items); die;
 
             $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'inner');
             $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'inner');
             $this->db->where_in('supplier_bill_basic.received_by', $staff_items);

         }

         if($userRoleIn[0]['created_by'] != 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
            // $col_dis = $this->db->select('staff.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
            //  ->join('staff_roles', 'staff.id = staff_roles.staff_id')
            //  ->where("staff.is_active",1)
            //  ->where("staff_roles.role_id",$userRoleIn[0]['created_by'])
            //  ->get("staff");
            //  $dis_id = $col_dis->row_array();
            //  $disId = $dis_id['id'];

            $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'right');
            $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'right');
            // $this->db->where('supplier_bill_basic.received_by', $disId);
            $this->db->where('supplier_bill_basic.received_by', $userLoggedInFirst);
         }

        if($collectMedCatName[0]['search_type'] == "Composition"){

            if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
                //IN CASE OF ADMIN WISE MED SUPPLY
                // $this->db->select('medicine_batch_details.id as id,pharmacy.medicine_name as medicine_name,pharmacy.medicine_composition as medicine_composition');
                //IN CASE OF CHEMIST WISE MED SUPPLYING
                // $this->db->select('pharmacy.*');
                $this->db->select('pharmacy.medicine_composition,pharmacy.id,pharmacy.medicine_category_id');
            }else{
            $this->db->select('pharmacy.*');
            $this->db->group_by("pharmacy.medicine_name");
            }
            $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }elseif($collectMedCatName[0]['search_type'] == "Branded"){

            if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
                //IN CASE OF ADMIN WISE MED SUPPLY
                // $this->db->select('medicine_batch_details.id as id,pharmacy.medicine_name as medicine_name,pharmacy.medicine_composition as medicine_composition');
               //IN CASE OF CHEMIST WISE MED SUPPLYING
                $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id');
                $this->db->group_by("pharmacy.medicine_name");

            }else{
                $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id');
                $this->db->group_by("pharmacy.medicine_name");
            }
            $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }
    }



    public function get_medicine_brand($medicine_category_id,$search_type,$medicine_name)
    {
        $visit_details_id = $this->input->post("visit_details_id");
        $getPatLocality = $this->role_model->getPatientDataByVdId($visit_details_id);

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        
        $this->db->select('medicine_search_type.*');
        $this->db->where('medicine_search_type.id', $search_type);
        $query1 = $this->db->get('medicine_search_type');
        $collectMedCatName = $query1->result_array();

        $getCompositionofId = $this->db->select('pharmacy.*')->where('pharmacy.id', $medicine_name)->get('pharmacy')->result_array();
        $med_comp_by_id = $getCompositionofId[0]['medicine_composition'];

        if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            //By Passing Doc Id
            //$getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
            
            //By Passing Patient visit_details_id
            $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($getPatLocality[0]['locality_id']);

            $staff_items = array();
             foreach($getPurchasedMedlistofDisbyDoclocation as $username) {
             $staff_items[] = $username['id'];
             }
            //  var_dump($staff_items); die;
 
             $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'inner');
             $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'inner');
             $this->db->where_in('supplier_bill_basic.received_by', $staff_items);

         }

         if($userRoleIn[0]['created_by'] != 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
            // $col_dis = $this->db->select('staff.*, CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier')
            //  ->join('staff_roles', 'staff.id = staff_roles.staff_id')
            //  ->where("staff.is_active",1)
            //  ->where("staff_roles.role_id",$userRoleIn[0]['created_by'])
            //  ->get("staff");
            //  $dis_id = $col_dis->row_array();
            //  $disId = $dis_id['id'];

            $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'right');
            $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'right');
            // $this->db->where('supplier_bill_basic.received_by', $disId);
            $this->db->where('supplier_bill_basic.received_by', $userLoggedInFirst);
         }

        if($collectMedCatName[0]['search_type'] == "Composition"){

            if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){

                //IN CASE OF ADMIN WISE MED SUPPLY
                // $this->db->select('medicine_batch_details.id as id,pharmacy.medicine_name as medicine_name,pharmacy.medicine_composition as medicine_composition');
                //IN CASE OF CHEMIST WISE MED SUPPLYING
                $this->db->select('pharmacy.*');
                $this->db->where('pharmacy.medicine_composition', $med_comp_by_id);
                $this->db->group_by("pharmacy.medicine_name");
            }else{
            $this->db->select('pharmacy.*');
            }
            $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }elseif($collectMedCatName[0]['search_type'] == "Branded"){

            if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
                //IN CASE OF ADMIN WISE MED SUPPLY
                // $this->db->select('medicine_batch_details.id as id,pharmacy.medicine_name as medicine_name,pharmacy.medicine_composition as medicine_composition');
               //IN CASE OF CHEMIST WISE MED SUPPLYING

                $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id");
                $this->db->where('pharmacy.medicine_composition', $med_comp_by_id);
                $this->db->group_by("pharmacy.medicine_name");

            }else{
                $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id');
            }
             $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }
    }


    public function get_medicine_name_without_st($medicine_category_id)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);

        if($userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
            $getPurchasedMedlistofDisbyDoclocation = $this->role_model->getPurdMedofDisbyDocLoc($userRoleIn[0]['locality_id']);
            $staff_items = array();
             foreach($getPurchasedMedlistofDisbyDoclocation as $username) {
             $staff_items[] = $username['id'];
             }
 
             $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'left');
             $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left');
             $this->db->where_in('supplier_bill_basic.received_by', $staff_items);
         }
            $this->db->select('pharmacy.*');
            $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            $this->db->where('pharmacy.active', 1);

            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            return $query->result_array();
    }


    public function get_medicine_name_to_push_stock($medicine_category_id,$search_type)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        

        // if( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Pharmacist'){
        //     // $userLoggedIn = $userRoleIn[0]['staff_id'];  //PREV
        //     $getfromcp = $this->role_model->getfromcp();
        //     $userLoggedIn = $getfromcp[0]['id'];
           
        // }elseif( $userRoleIn[0]['created_by'] == 7 && $userRoleIn[0]['name'] == 'Doctor'){
        //     $getfromcp = $this->role_model->getfromcp();
        //     $userLoggedIn = $getfromcp[0]['id'];
           
        // }elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Doctor'){
        //     $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        //     $userLoggedIn = $userGetAdmin[0]['staff_id'];
        // }elseif($userRoleIn[0]['created_by'] != 7  && $userRoleIn[0]['name'] == 'Pharmacist'){
        //     $userGetAdmin = $this->role_model->getAdminUsingCreatedById($userRoleIn[0]['created_by']);
        //     $userLoggedIn = $userGetAdmin[0]['staff_id'];
        // }else{
        //     $userLoggedIn = $userLoggedInFirst;
        // }


        $this->db->select('medicine_search_type.*');
        $this->db->where('medicine_search_type.id', $search_type);
        $query1 = $this->db->get('medicine_search_type');
        $collectMedCatName = $query1->result_array();

        
 
             $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'left');
             $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left');
             $this->db->where('supplier_bill_basic.received_by', $userLoggedInFirst);
             $this->db->group_by('medicine_batch_details.pharmacy_id');


         
        if($collectMedCatName[0]['search_type'] == "Composition"){
            $this->db->select('pharmacy.*,medicine_batch_details.id as mbdId,supplier_bill_basic.id as sbbId,supplier_bill_basic.received_by');
            $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            //  $this->db->where('pharmacy.added_by', $userLoggedIn);
            $this->db->where('pharmacy.active', 1);

            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }elseif($collectMedCatName[0]['search_type'] == "Branded"){
            $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_batch_details.id as mbdId,supplier_bill_basic.id as sbbId,supplier_bill_basic.received_by');
             $this->db->where('pharmacy.medicine_category_id', $medicine_category_id);
            //  $this->db->where('pharmacy.added_by', $userLoggedIn);  
            $this->db->where('pharmacy.active', 1);
            $this->db->order_by("pharmacy.valuep desc");
            $query = $this->db->get('pharmacy');
            // echo $this->db->last_query(); die;
            return $query->result_array();
        }
    }


    // public function get_medicine_searchBy_name($medicine_category_id)
    // {
       
    //     $this->db->select('pharmacy.*');
    //     $this->db->like('pharmacy.medicine_name', $medicine_category_id);
    //     $this->db->order_by("pharmacy.valuep desc");
    //     $query1 = $this->db->get('pharmacy');

    //     $collectMedGenName = $query1->result_array();
    //     if($collectMedGenName[0]['generic_name'] != ""){
    //         $this->db->select('pharmacy.*');
    //         $this->db->join('medicine_category', 'medicine_category.id = pharmacy.medicine_category_id');

    //         $this->db->like('pharmacy.generic_name', $collectMedGenName[0]['generic_name']);
    //         $this->db->order_by("pharmacy.valuep desc");
    //         $this->db->where('medicine_category.medicine_category', "Branded");

    //         $query = $this->db->get('pharmacy');
    //         return $query->result_array();
    //     }else{
    //         echo "no med";
    //     }

    // }

    public function get_medicine_searchBy_name($medicine_category_id)
    {

        $this->db->select('pharmacy.*');
        $this->db->like('pharmacy.generic_name', $medicine_category_id);
        $this->db->order_by("pharmacy.valuep desc");
        $chk_gname = $this->db->get('pharmacy');
        $collgname = $chk_gname->result_array();
        // var_dump($collgname); die;

        if(($collgname[0]['generic_name'] != "")){
            $this->db->select('pharmacy.*');
            $this->db->join('medicine_category', 'medicine_category.id = pharmacy.medicine_category_id');

            $this->db->like('pharmacy.generic_name', $medicine_category_id);
            $this->db->order_by("pharmacy.valuep desc");
            $this->db->where('medicine_category.medicine_category', "Generic");

            $chk_gname1 = $this->db->get('pharmacy');

            return $chk_gname1->result_array();
        }else{


        $this->db->select('pharmacy.*');
        $this->db->like('pharmacy.medicine_name', $medicine_category_id);
        $this->db->order_by("pharmacy.valuep desc");
        $query1 = $this->db->get('pharmacy');

        $collectMedGenName = $query1->result_array();
        if($collectMedGenName[0]['generic_name'] != ""){
            $this->db->select('pharmacy.id,pharmacy.medicine_name');
            $this->db->join('medicine_category', 'medicine_category.id = pharmacy.medicine_category_id');

            $this->db->like('pharmacy.generic_name', $collectMedGenName[0]['generic_name']);
            $this->db->order_by("pharmacy.valuep desc");
            $this->db->where('medicine_category.medicine_category', "Branded");

            $query = $this->db->get('pharmacy');
            return $query->result_array();
        }else{
            echo "no med";
        }
    }

    }

    

    public function get_medicine_dosage($medicine_category_id)
    {
        $this->db->select('medicine_dosage.dosage,charge_units.unit,medicine_dosage.id')->join('charge_units', 'charge_units.id=medicine_dosage.charge_units_id');
        $this->db->where('medicine_dosage.medicine_category_id', $medicine_category_id);
        $query = $this->db->get('medicine_dosage');
        return $query->result_array();
    }

    public function get_dosagename($id)
    {
        $this->db->select('medicine_dosage.dosage,charge_units.unit,medicine_dosage.id')->join('charge_units', 'charge_units.id=medicine_dosage.charge_units_id');
        $this->db->where('medicine_dosage.id', $id);
        $query = $this->db->get('medicine_dosage');
        return $query->row_array();
    }

    public function get_supplier_name($supplier_category_id)
    {
        $query = $this->db->where("id", $supplier_category_id)->get("medicine_supplier");
        return $query->result_array();
    }

    public function getBillDetails($id, $check_print = NULL)
    {
        if($check_print == 'print'){
            $custom_fields = $this->customfield_model->get_custom_fields('pharmacy', '', 1);
        }else{
            $custom_fields = $this->customfield_model->get_custom_fields('pharmacy');
        }

        $custom_field_column_array = array();
        $field_var_array = array();
        $i               = 1;
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'pharmacy_bill_basic.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }
        $field_variable = implode(',', $field_var_array);       
        $this->db->select('pharmacy_bill_basic.*,print_setting.print_header,sch_settings.name as clinic_name,staff.drug_license_number,staff.gst_in,staff.local_address,staff.permanent_address,IFNULL((select sum(amount) as amount_paid from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="payment" ),0) as paid_amount, IFNULL((select sum(amount) as refund from transactions WHERE transactions.pharmacy_bill_basic_id =pharmacy_bill_basic.id and transactions.type="refund" ),0) as refund_amount,staff.name,staff.surname,staff.id as staff_id,staff.employee_id,patients.patient_name,patients.id as patientid,patients.id as patient_unique_id,patients.mobileno,patients.age,' . $field_variable);
        $this->db->join('patients', 'pharmacy_bill_basic.patient_id = patients.id');
        $this->db->join('staff', 'pharmacy_bill_basic.generated_by = staff.id');
        $this->db->join('print_setting', 'print_setting.staff_id = staff.id');
        $this->db->join('sch_settings', 'sch_settings.staff_id = pharmacy_bill_basic.generated_by');
        $this->db->where('pharmacy_bill_basic.id', $id);
        $query = $this->db->get('pharmacy_bill_basic');
        return $query->row_array();
    }

    public function getAllBillDetails($id)
    {
        //ORIGINAL
        $sql = "SELECT pharmacy_bill_detail.*,medicine_batch_details.expiry,medicine_batch_details.pharmacy_id,medicine_batch_details.batch_no,medicine_batch_details.tax,pharmacy.medicine_name,pharmacy.unit,pharmacy.id as `medicine_id`,pharmacy.medicine_category_id,medicine_category.medicine_category,pharmacy.medicine_composition FROM `pharmacy_bill_detail` INNER JOIN medicine_batch_details on medicine_batch_details.id=pharmacy_bill_detail.medicine_batch_detail_id INNER JOIN pharmacy on pharmacy.id= medicine_batch_details.pharmacy_id INNER JOIN medicine_category on medicine_category.id= pharmacy.medicine_category_id WHERE pharmacy_bill_basic_id =" . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getSupplierDetails($id)
    {

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        if($userRoleIn[0]['created_by'] == 73){
            $this->db->select('supplier_bill_basic.*,medicine_supplier.supplier,medicine_supplier.contact,medicine_supplier.address,medicine_supplier.supplier,medicine_supplier.supplier_drug_licence,medicine_supplier.gst_in');
            $this->db->join('medicine_supplier', 'medicine_supplier.id=supplier_bill_basic.supplier_id');
            $this->db->where('supplier_bill_basic.id', $id);
            $query = $this->db->get('supplier_bill_basic');
            return $query->row_array();
        }else{
            $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,staff.contact_no as contact,staff.permanent_address as address');
            $this->db->join('staff', 'staff.id=supplier_bill_basic.received_by');

            // $this->db->join('medicine_supplier', 'medicine_supplier.id=supplier_bill_basic.supplier_id');
            $this->db->where('supplier_bill_basic.id', $id);
            $query = $this->db->get('supplier_bill_basic');
            return $query->row_array();
        }
    }

    public function getSupplierDetailsStockPush($id)
    {

       
            $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,staff.contact_no as contact,staff.permanent_address as address,staff.drug_license_number,staff.gst_in');
            $this->db->join('staff', 'staff.id=supplier_bill_basic.received_by');

            $this->db->where('supplier_bill_basic.id', $id);
            $query = $this->db->get('supplier_bill_basic');
            return $query->row_array();
        
    }

    public function getSupplierDetailsSuppliedBy()
    {

        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $this->db->select('CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,staff.contact_no as contact,staff.permanent_address as address,staff.drug_license_number,staff.gst_in');
            $this->db->where('staff.id', $userLoggedInFirst);
            $query = $this->db->get('staff');
            return $query->row_array();
        
    }

    public function getAllSupplierDetails($id)
    {
        $query = $this->db->select('medicine_batch_details.*,pharmacy.medicine_name,pharmacy.unit,pharmacy.id as medicine_id,medicine_category.medicine_category,medicine_category.id as medicine_category_id,pharmacy.medicine_composition')
            ->join('pharmacy', 'medicine_batch_details.pharmacy_id = pharmacy.id')
            ->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id')
            ->where('medicine_batch_details.supplier_bill_basic_id', $id)
            ->get('medicine_batch_details');
        return $query->result_array();
    }

    public function getAllSupplierDetailsStockPush($id)
    {
        $query = $this->db->select('medicine_batch_details.*,pharmacy.medicine_name,pharmacy.unit,pharmacy.id as medicine_id,medicine_category.medicine_category,medicine_category.id as medicine_category_id,pharmacy.medicine_composition')
            ->join('pharmacy', 'medicine_batch_details.pharmacy_id = pharmacy.id')
            ->join('medicine_category', 'pharmacy.medicine_category_id = medicine_category.id')
            ->where('medicine_batch_details.supplier_bill_basic_id', $id)
            ->get('medicine_batch_details');
        return $query->result_array();
    }


    

    public function getBillDetailsPharma($id)
    {
        $this->db->select('pharmacy_bill_basic.*,patients.patient_name');
        $this->db->join('patients', 'patients.id = pharmacy_bill_basic.patient_id');
        $this->db->where('pharmacy_bill_basic.id', $id);
        $query = $this->db->get('pharmacy_bill_basic');
        return $query->row_array();
    }

    public function getAllBillDetailsPharma($id)
    {
        $query = $this->db->select('pharmacy_bill_detail.*,pharmacy.medicine_name,pharmacy.unit,pharmacy.id as medicine_id')
            ->join('pharmacy', 'pharmacy_bill_detail.medicine_name = pharmacy.id')
            ->where('pharmacy_bill_basic_id', $id)
            ->get('pharmacy_bill_detail');
        return $query->result_array();
    }

    public function getQuantity($batch_no, $med_id)
    {
        $query = $this->db->select('medicine_batch_details.id,medicine_batch_details.available_quantity,medicine_batch_details.quantity,medicine_batch_details.purchase_price,medicine_batch_details.sale_rate')
            ->where('batch_no', $batch_no)
            ->where('pharmacy_id', $med_id)
            ->get('medicine_batch_details');
        return $query->row_array();
    }
    public function getQuantityedit($batch_no)
    {
        $query = $this->db->select('medicine_batch_details.id,medicine_batch_details.available_quantity,medicine_batch_details.quantity,medicine_batch_details.purchase_price,medicine_batch_details.sale_rate')
            ->where('batch_no', $batch_no)
            ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function checkvalid_medicine_exists($str)
    {
        $medicine_name = $this->input->post('medicine_name');
        if ($this->check_medicie_exists($medicine_name)) {
            $this->form_validation->set_message('check_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_medicie_exists($name, $id)
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

    public function availableQty($update_quantity)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $query = $this->db->where('id', $update_quantity['id'])
            ->update('medicine_batch_details', $update_quantity);
        $message = UPDATE_RECORD_CONSTANT . " On Medicine Batch Details id " . $update_quantity['id'];
        $action = "Update";
        $record_id = $update_quantity['id'];
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

    public function getsingleMedicineBatchdetails($medicine_batch_id)
    {
        $query = $this->db->select('available_quantity')
            ->where('id', $medicine_batch_id)
            ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function totalQuantity($pharmacy_id)
    {
        $query = $this->db->select('sum(available_quantity) as total_qty')
            ->where('pharmacy_id', $pharmacy_id)
            ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function searchBillReport($date_from, $date_to)
    {
        $this->db->select('pharmacy_bill_basic.*');
        $this->db->where('date >=', $date_from);
        $this->db->where('date <=', $date_to);
        $query = $this->db->get("pharmacy_bill_basic");
        return $query->result_array();
    }

    public function delete_medicine_batch($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_batch_details");        
        $message = DELETE_RECORD_CONSTANT . " On Medicine Batch Details id " . $id;
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

    public function delete_bill_detail($delete_arr)
    {       
        foreach ($delete_arr as $key => $value) {
            $id = $value["id"];
            $this->db->where("id", $id)->delete("prescription");
        }
    }

    public function getBillNo()
    {
        $query = $this->db->select("max(id) as id")->get('pharmacy_bill_basic');
        return $query->row_array();
    }

    public function getExpiryDate($medicine_batch_detail_id)
    {
        $query = $this->db->where("id", $medicine_batch_detail_id)
            ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function getMedicineBatchByID($medicine_batch_detail_id)
    {
        $sql   = "SELECT medicine_batch_details.*, IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0) as used_quantity FROM `medicine_batch_details` WHERE medicine_batch_details.id=" . $this->db->escape($medicine_batch_detail_id);
     
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getExpireDate($batch_no)
    {
        $query = $this->db->where("batch_no", $batch_no)
            ->get('medicine_batch_details');
        return $query->row_array();
    }

    public function getmedicinedetailsbyid($id)
    {
        $query = $this->db->where("pharmacy.id", $id)
            ->get('pharmacy');
        return $query->row_array();
    }

    public function getmedicinedetailsbyidtopushstocks($id)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        
             

        $query = $this->db->select('pharmacy.*,medicine_batch_details.id as mbdId,medicine_batch_details.available_quantity')
        
       ->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'left')
        ->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left')
        ->where('supplier_bill_basic.received_by', $userLoggedInFirst)
        // ->where("medicine_batch_details.id", $id)
        ->where("medicine_batch_details.batch_no", $id)

        ->get('pharmacy');
            // echo $this->db->last_query(); die;
        return $query->row_array();
    }

   
    public function getBatchNoList($pharmacy_id)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        
        if($userRoleIn[0]['created_by'] != 7 && $userRoleIn[0]['name'] == 'Pharmacist'){

            $sql = "SELECT medicine_batch_details.*, (medicine_batch_details.available_quantity-IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0)) as remaining_quantity FROM `medicine_batch_details` LEFT JOIN  supplier_bill_basic on supplier_bill_basic.id=medicine_batch_details.supplier_bill_basic_id WHERE supplier_bill_basic.received_by = $userLoggedInFirst AND medicine_batch_details.pharmacy_id=".$this->db->escape($pharmacy_id)." HAVING remaining_quantity > 0";
            $query = $this->db->query($sql);
            return $query->result_array();
         }else{

            $sql = "SELECT medicine_batch_details.*, (medicine_batch_details.available_quantity-IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0)) as remaining_quantity FROM `medicine_batch_details` WHERE medicine_batch_details.pharmacy_id=".$this->db->escape($pharmacy_id)." HAVING remaining_quantity > 0";
            $query = $this->db->query($sql);
            return $query->result_array();

         }

    //  $sql = "SELECT medicine_batch_details.*, (medicine_batch_details.available_quantity-IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0)) as remaining_quantity FROM `medicine_batch_details` WHERE medicine_batch_details.pharmacy_id=".$this->db->escape($pharmacy_id)." HAVING remaining_quantity > 0";
    //  $query = $this->db->query($sql);
    //  return $query->result_array();
    }

    public function getBatchNoListtopushstocks($pharmacy_id)
    {
        $userLoggedInFirst = $this->customlib->getLoggedInUserId();
        $userRoleIn = $this->role_model->getRoleFromStaffUsingLid($userLoggedInFirst);
        
        if($userRoleIn[0]['created_by'] != 7 && $userRoleIn[0]['name'] == 'Pharmacist'){

            $sql = "SELECT medicine_batch_details.*, (medicine_batch_details.available_quantity-IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0)) as remaining_quantity FROM `medicine_batch_details` LEFT JOIN  supplier_bill_basic on supplier_bill_basic.id=medicine_batch_details.supplier_bill_basic_id WHERE supplier_bill_basic.received_by = $userLoggedInFirst AND medicine_batch_details.pharmacy_id=".$this->db->escape($pharmacy_id)." HAVING remaining_quantity > 0";
            $query = $this->db->query($sql);
            return $query->result_array();
         }else{

        $query = $this->db->select('pharmacy.*,medicine_batch_details.id as mbdId,medicine_batch_details.available_quantity,medicine_batch_details.batch_no')
        
       ->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id', 'left')
        ->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id', 'left')
        ->where('supplier_bill_basic.received_by', $userLoggedInFirst)
        ->where("medicine_batch_details.pharmacy_id", $pharmacy_id)
        ->get('pharmacy');
            // echo $this->db->last_query(); die;
        return $query->result_array();

         }
    }


    // public function getBatchNoListSt($pharmacy_id)
    // {
    //  $sql = "SELECT medicine_batch_details.*, (medicine_batch_details.available_quantity-IFNULL((SELECT SUM(quantity) FROM `pharmacy_bill_detail` WHERE medicine_batch_detail_id=medicine_batch_details.id),0)) as remaining_quantity FROM `medicine_batch_details` WHERE medicine_batch_details.pharmacy_id=".$this->db->escape($pharmacy_id)." HAVING remaining_quantity > 0";
    //        $query = $this->db->query($sql);
    //        echo $this->db->last_query(); die;
    //     return $query->result_array();

    // }


    public function addBadStock($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert("medicine_bad_stock", $data);
        $insert_id = $this->db->insert_id();
        $message = INSERT_RECORD_CONSTANT . " On Medicine Bad Stock id " . $insert_id;
        $action = "Insert";
        $record_id = $insert_id;
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

    public function updateMedicineBatch($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $data["id"])->update("medicine_batch_details", $data);
        $message = UPDATE_RECORD_CONSTANT . " On Medicine Batch Details id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
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

    public function getMedicineBadStock($id)
    {
        $query = $this->db->where("pharmacy_id", $id)->get("medicine_bad_stock");
        return $query->result();
    }

    public function getsingleMedicineBadStock($id)
    {
        $query = $this->db->where("id", $id)->get("medicine_bad_stock");
        return $query->row_array();
    }

    public function deleteBadStock($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("medicine_bad_stock");        
        $message = DELETE_RECORD_CONSTANT . " On Medicine Bad Stock id " . $id;
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

    public function searchNameLike($category, $value)
    {
        $query = $this->db->where("medicine_category_id", $category)->like("medicine_name", $value)->get("pharmacy");
        return $query->result_array();
    }
    
    public function validate_paymentamount()
    {
        $final_amount=0 ;
        $amount = $this->input->post('amount');
        $payment_amount = $this->input->post('payment_amount');
        if(!empty($amount)){
            $final_amount = $amount;
        }else if(!empty($payment_amount)){
            $final_amount = $payment_amount;
        }
     
        $net_amount    = $this->input->post('net_amount') ;
        if($final_amount > $net_amount ){
        
            $this->form_validation->set_message('check_exists', $this->lang->line('amount_should_not_be_greater_than_balance').' '. $net_amount );
            return false;
        }else{        
            return true;
        }        
    }
    
    public function getIpdPrescriptionBasic($ipd_prescription_basic_id)
    {
        $this->db->select('ipd_prescription_basic.*');
        $this->db->where('ipd_prescription_basic.id', $ipd_prescription_basic_id);
        $query = $this->db->get('ipd_prescription_basic');
        return $query->row();
    }

    public function updateactivepharmacy($data){
        $this->db->where('id', $data['id']);
        $this->db->update('pharmacy', $data);
        $record_id = $data['id'];
        return $record_id;
    }

    public function check_consultdoctor_exists()
    {
        $prescription_no    = $this->input->post('prescription_no');
        $consultant_doctor    = $this->input->post('consultant_doctor');
        $split_prescription_no     = splitPrefixID($prescription_no);
        if($consultant_doctor != ''){
        if ($this->check_presc_doc_for_pres($split_prescription_no,$consultant_doctor)) {
            return true;
        } else {
            $this->form_validation->set_message('check_exists', "Prescribed doctor name can't be changed");
            return false;
        }
    }else{
        $this->form_validation->set_message('check_exists', 'Please attach the prescribed doctor');
    }
    }

    public function check_presc_doc_for_pres($split_prescription_no,$consultant_doctor)
    {
            $data  = array('id' => $split_prescription_no, 'prescribe_by' => $consultant_doctor);
            $query = $this->db->where($data)->get('ipd_prescription_basic');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
    }

    public function checkPresBilled($id){
        $data  = array('ipd_prescription_basic_id' => $id);
        $query = $this->db->where($data)->get('pharmacy_bill_basic');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }



}
