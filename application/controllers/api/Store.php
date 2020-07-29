<?php 

require_once('AuthenticatedRestController.php');

class Store extends AuthenticatedRestController{

    public function index_get()
    {

        $current_lat = $this->input->get('current_lat');
        $current_lng = $this->input->get('current_lng');
        $radius = 10; // km
        $keyword = $this->input->get('keyword');

        $sql = "SELECT * FROM stores";
        
        /* 
        TODO
        -- Query for keyword search
        -- Query for location search with radius
         */

        $query = $this->db->query($sql);
        $stores = $query->result();

        $this->response([
            'status' => true,
            'data' => [
                'stores' => $stores
            ]
        ], 200);
    }

    public function show_get($store_id)
    {
        $sql = "SELECT
                    stores.id,
                    stores.name,
                    stores.banner_image,
                    stores.address,
                    stores.lat,
                    stores.lng,
                    stores.user_id,
                    stores.created_at,
                    stores.updated_at,
                    users.email AS user_email,
                    users.name AS user_name
                FROM
                    stores
                LEFT JOIN users ON stores.user_id = users.id 
                WHERE stores.id = ? ";

        $query = $this->db->query($sql, [$store_id]);
        
        $store = $query->row();

        if($store){

            $store->products = [];

            $sql = "SELECT
                        products.id,
                        products.`name`,
                        products.description,
                        products.photo,
                        products.store_id,
                        products.`status`,
                        products.created_at,
                        products.updated_at
                    FROM
                        products
                    
                    WHERE store_id = ? ";
            $query = $this->db->query($sql, [$store->id]);

            $products = $query->result();

            if($products){
                
                for($i = 0; $i < count($products); $i++){
                    $products[$i]->active_product_stock = null;

                    $sql = "SELECT * FROM product_stocks where product_stocks.product_id = ? ORDER BY product_stocks.created_at ASC LIMIT 1";
                    $query = $this->db->query($sql, [$products[$i]->id]);

                    $active_product_stock = $query->row();
                    $products[$i]->active_product_stock = $active_product_stock;
                }
            }

            $store->products = $products;
        }

        $this->response([
            'status' => (boolean) $store,
            'message' => $store ? 'Store found' : 'Store not found',
            'data' => [
                'store' => $store
            ]
        ], 200);
    }

}