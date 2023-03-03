<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sales_model extends MY_Model
{    
   

    public function getTransactionBetweenDateLoginwise($start_date, $end_date,$arry_pha) {
        $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier');
        $this->db->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where_in('supplier_bill_basic.received_by', $arry_pha);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        return $query->result();
    }

    public function getTransactionBetweenDateLoginwisetoCp($start_date, $end_date) {
        $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier');
        $this->db->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        // $this->db->where_in('supplier_bill_basic.received_by', $arry_pha);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        return $query->result();
    }

    public function getProductwiseSalesReport($start_date, $end_date,$arry_pha){
        $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id');
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id','left');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id','left');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where_in('supplier_bill_basic.received_by', $arry_pha);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }

    public function getProductwiseSalesReporttoCP($start_date, $end_date){
        $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id');
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id','left');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id');
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $this->db->where('staff_roles.role_id', 4);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }



    public function getChemistPurchaseReport($start_date, $end_date,$id){
        $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier');
        $this->db->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        return $query->result();
    }

    public function getChemistPurchaseReporttoCP($start_date, $end_date){
        $this->db->select('supplier_bill_basic.*,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier');
        $this->db->join('medicine_supplier', 'medicine_supplier.id = supplier_bill_basic.supplier_id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        // $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        return $query->result();
    }

    public function getChemistProductwiseSalesReport($start_date, $end_date,$id){
        $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id');
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id','left');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id','left');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }


    public function getChemistProductwiseSalesReporttoCP($start_date, $end_date){
        $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id');
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id','left');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id','left');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }
    

    public function getChemistProductwiseStockReport($start_date, $end_date,$id){
        $this->db->select('pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,CONCAT("",staff.name,staff.surname," (",staff.employee_id,")") as supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id,medicine_batch_details.quantity as mbd_qty,medicine_batch_details.available_quantity as mbd_availqty');
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id','left');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id','left');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id','left');
        $this->db->join('staff', 'staff.id = supplier_bill_basic.received_by','left');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }
    
    
}