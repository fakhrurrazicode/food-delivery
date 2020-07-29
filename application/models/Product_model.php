<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_model
{    
    public function find($product_id)
    {
        $sql = "SELECT * FROM products WHERE products.id = ? ";
        $query = $this->db->query($sql, [$product_id]);

        $product = $query->row();

        if($product){

            $sql = "SELECT * FROM product_stocks where product_stocks.product_id = ? AND product_stocks.qty > 0 ORDER BY product_stocks.created_at ASC LIMIT 1";
            $query = $this->db->query($sql, [$product->id]);
            $active_product_stock = $query->row();
            $product->active_product_stock = $active_product_stock;

            $sql = "SELECT * FROM stores WHERE stores.id = ? ";
            $query = $this->db->query($sql, [$product->store_id]);
            $store = $query->row();

            $product->store = $store ? $store : null;

            
        }

        return $product;
    }

    public function hasStocks($product_id)
    {
        $sql = "SELECT * FROM product_stocks WHERE product_id = ?";
        $query = $this->db->query($sql, [$product_id]);
        return (boolean) $query->num_rows();
    }
}