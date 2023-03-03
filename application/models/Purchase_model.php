<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchase_model extends MY_Model
{    
   

    //FOR BOTH SAME
    // public function getTransactionBetweenDateLoginwise($start_date, $end_date,$id) {
    //     $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_supplier.supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,supplier_bill_basic.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date");
    //     $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id');
    //     $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id');
    //     $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
    //     $this->db->where('supplier_bill_basic.received_by', $id);
    //     $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
    //     $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
    //     $query = $this->db->get('pharmacy');
    //     // echo $this->db->last_query(); die;
    //     return $query->result();
    // } 

    public function getTransactionBetweenDateLoginwise($start_date, $end_date,$id) {
        $this->db->select("medicine_supplier.supplier,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,supplier_bill_basic.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,supplier_bill_basic.created_at as bill_date");
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        // echo $this->db->last_query(); die;
        return $query->result();
    }

    public function getTransactionBetweenDateLoginwisetoCp($start_date, $end_date) {
        $this->db->select("medicine_supplier.supplier,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,supplier_bill_basic.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,supplier_bill_basic.created_at as bill_date");
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        // $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('supplier_bill_basic');
        // echo $this->db->last_query(); die;
        return $query->result();
    }


    public function getProductwisePurchaseReport($start_date, $end_date,$id){
        $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_supplier.supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id");
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }

    public function getProductwisePurchaseReporttoCP($start_date, $end_date){
        $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_supplier.supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id");
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        // $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }


    public function getDisProductwiseSalesReport($start_date, $end_date,$id){
        $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_supplier.supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id,medicine_batch_details.quantity as mbd_qty,medicine_batch_details.available_quantity as mbd_availqty");
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }


    public function getCentralPharmaProductwiseSalesReport($start_date, $end_date){
        $this->db->select("pharmacy.medicine_name,pharmacy.id,pharmacy.medicine_category_id,medicine_supplier.supplier,medicine_batch_details.id as pur_id,supplier_bill_basic.id as bill_id,supplier_bill_basic.net_amount as bill_amount,medicine_batch_details.tax,supplier_bill_basic.tax_cgst,supplier_bill_basic.tax_sgst,medicine_batch_details.amount as purchase_amount,supplier_bill_basic.created_at as bill_date,medicine_batch_details.batch_no,medicine_batch_details.expiry,pharmacy.product_id,medicine_batch_details.quantity as mbd_qty,medicine_batch_details.available_quantity as mbd_availqty");
        $this->db->join('medicine_batch_details', 'medicine_batch_details.pharmacy_id = pharmacy.id');
        $this->db->join('supplier_bill_basic', 'supplier_bill_basic.id = medicine_batch_details.supplier_bill_basic_id');
        $this->db->join('medicine_supplier', 'supplier_bill_basic.supplier_id = medicine_supplier.id');
        // $this->db->where('supplier_bill_basic.received_by', $id);
        $this->db->where('DATE(supplier_bill_basic.created_at) >=', $start_date);
        $this->db->where('DATE(supplier_bill_basic.created_at) <=', $end_date);
        $query = $this->db->get('pharmacy');
        // echo $this->db->last_query(); die;
        return $query->result();
    }
    
    
}