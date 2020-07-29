<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_model
{    
    public function getByUserId($user_id)
    {
        $sql = "SELECT * FROM cart WHERE cart.user_id = ? ";

        $query = $this->db->query($sql, [$user_id]);
        $carts = $query->result();

        $mapped_carts = array_map(function($cart){
            
            $cart->product = $this->Product_model->find($cart->product_id);;
            return $cart;
        }, $carts);

        return $mapped_carts;
    }

    public function find($cart_id)
    {
        $sql = "SELECT * FROM cart WHERE cart.id = ? ";

        $query = $this->db->query($sql, [$cart_id]);
        $cart = $query->row();
        
        if($cart){
            $cart->product = $this->Product_model->find($cart->product_id);;
        }

        return $cart;
    }

    public function checkout($user_id, $address, $lat, $lng, $shipping_cost)
    {
        $carts = $this->getByUserId($user_id);

        $order_data = [
            'invoice_number' => $this->Order_model->generateInvoiceNumber(),
            'user_id' => $user_id,
            'address' => $address,
            'lat' => $lat,
            'lng' => $lng,
            'shipping_cost' => $shipping_cost
        ];
        
        $insert_order = $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        $order_detail_batch_data = [];

        $carts = $this->Cart_model->getByUserId($user_id);

        foreach($carts as $cart){
            $order_detail_batch_data[] = [
                'order_id' => $order_id,
                'product_stock_id' => $cart->product->active_product_stock->id,
                'price' => $cart->product->active_product_stock->price,
                'qty' => $cart->qty,
            ];
        }

        $insert_order_details = $this->db->insert_batch('order_details', $order_detail_batch_data); 

        if($insert_order_details){
            // kosong data cart 
            $this->kosong($user_id);
        }

        $order = $this->Order_model->find($order_id);

        return $order;

        
    }

    public function kosong($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('cart');
        return true;
    }
}