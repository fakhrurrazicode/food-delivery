<?php 

require_once('AuthenticatedRestController.php');

class Product extends AuthenticatedRestController
{

    public function index_get()
    {
        $current_lat = $this->get('current_lat');
        $current_lng = $this->get('current_lng');
        $radius = 10; // km
        $keyword = $this->get('keyword');

        $sql = "SELECT
                    products.id, 
                    products.`name`, 
                    products.description, 
                    products.photo, 
                    products.store_id, 
                    products.`status`, 
                    products.created_at, 
                    products.updated_at, 
                    stores.lat AS store_lat, 
                    stores.lng AS store_lng
                FROM
                    products
                    INNER JOIN
                    stores
                    ON 
                        products.store_id = stores.id ";

        $query = $this->db->query($sql);
        $products = $query->result();

        $products_mapped = array_map(function($product){
            
            $product->store = null;

            $query = $this->db->query("SELECT * FROM stores WHERE stores.id = ?", [$product->store_id]);
            $store = $query->row();

            if($store){
                $product->store = $store;
            }

            $product->active_product_stock = null;
            $sql = "SELECT * FROM product_stocks where product_stocks.product_id = ? ORDER BY product_stocks.created_at ASC LIMIT 1";
            $query = $this->db->query($sql, [$product->id]);
            $active_product_stock = $query->row();
            $product->active_product_stock = $active_product_stock;

            return $product;
        }, $products);

        $this->response([
            'status' => true,
            'data' => [
                'products' => $products_mapped
            ]
        ], 200);
    }

    public function show_get($product_id)
    {
        $sql = "SELECT
                    products.id, 
                    products.`name`, 
                    products.description, 
                    products.photo, 
                    products.store_id, 
                    products.`status`, 
                    products.created_at, 
                    products.updated_at, 
                    stores.lat AS store_lat, 
                    stores.lng AS store_lng
                FROM
                    products
                    INNER JOIN
                    stores
                    ON 
                        products.store_id = stores.id 
                WHERE products.id = ? ";

        $query = $this->db->query($sql, [$product_id]);
        $product = $query->row();

        if(!$product){
            $this->response([
                'status' => false,
                'message' => 'Product not found'
            ], 200);
        }

        $product->store = null;

        $query = $this->db->query("SELECT * FROM stores WHERE stores.id = ?", [$product->store_id]);
        $store = $query->row();

        if($store){
            $product->store = $store;
        }

        $product->active_product_stock = null;
        $sql = "SELECT * FROM product_stocks where product_stocks.product_id = ? ORDER BY product_stocks.created_at ASC LIMIT 1";
        $query = $this->db->query($sql, [$product->id]);
        $active_product_stock = $query->row();
        $product->active_product_stock = $active_product_stock;

        $this->response([
            'status' => true,
            'data' => [
                'product' => $product
            ]
        ], 200);
    }
}