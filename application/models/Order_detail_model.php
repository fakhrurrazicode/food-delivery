<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_detail_model extends CI_model
{    
    public function find($order_detail_id)
    {
        $sql = "SELECT * FROM order_details WHERE order_detail.id = ? ";

        $query = $this->db->query($sql, [$order_detail_id]);
        $order_detail = $query->row();

        if($order_detail){
            $order_detail->order_detail_details = [];
            
        }

        return $order_detail;
    }

    public function getByOrderId($order_id)
    {
        $query = $this->db->query("SELECT * FROM order_details WHERE order_details.order_id = ?", [$order_id]);

        $order_details = $query->result();

        return $order_details;
    }
}
