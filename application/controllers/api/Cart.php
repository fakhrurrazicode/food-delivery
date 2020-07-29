<?php 

require_once('AuthenticatedRestController.php');

class Cart extends AuthenticatedRestController{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("api_helper");
        $this->load->model('Cart_model');
    }

    public function index_get()
    {
        
        $user = getUserAPI();
        
        $carts = $this->Cart_model->getByUserId($user->id);

        $this->response([
            'status' => (boolean) $carts,
            'data' => [
                'carts' => $carts
            ]
        ], 200);

    }

    public function add_post()
    {
        $user = getUserAPI();

        // check apakah product memiliki stock atau tidak
        $hasStocks = $this->Product_model->hasStocks($this->post('product_id'));

        if(!$hasStocks){
            $this->response([
                'status' => $hasStocks,
                'message' => 'Product stocks is not available',
            ], 200);
        }

        $data = array(
            'product_id' => $this->post('product_id'),
            'qty' => $this->post('qty'),
            'user_id' => $user->id
        );
        
        $insert = $this->db->insert('cart', $data);

        $cart_id = $this->db->insert_id();

        $cart = $this->Cart_model->find($cart_id);
        
        $this->response([
            'status' => $insert,
            'message' => 'Product added to cart',
            'data' => [
                'cart' => $cart
            ]
        ], 200);
    }

    public function update_post($cart_id)
    {
        $cart = $this->Cart_model->find($cart_id);

        if(!$cart){
            $this->response([
                'status' => (boolean) $cart,
                'message' => 'Cart not found',
            ], 200);
        }
        
        $qty = $cart->qty + $this->post('qty');
        $this->db->query("UPDATE cart SET qty = ? WHERE cart.id = ? ", [$qty, $cart_id]);

        $cart = $this->Cart_model->find($cart_id);
        

        $this->response([
            'status' => (boolean) $cart,
            'message' => 'Cart updated',
            'data' => [
                'cart' => $cart
            ]
        ], 200);
    }

    public function remove_post($cart_id)
    {

        $cart = $this->Cart_model->find($cart_id);

        if(!$cart){
            $this->response([
                'status' => (boolean) $cart,
                'message' => 'Cart not found',
            ], 200);
        }
        
        $this->db->where('id', $cart->id);
        $this->db->delete('cart');

        $this->response([
            'status' => (boolean) $cart,
            'message' => 'Cart removed',
            'data' => [
                'cart' => $cart
            ]
        ], 200);
    }

    

    public function checkout_post()
    {
        $user = getUserAPI();

        $carts = $this->Cart_model->getByUserId($user->id);

        if(!$carts){
            $this->response([
                'status' => (boolean) $carts,
                'message' => 'Cart Empty',
            ], 200);
        }

        $address = $this->post('address');
        $lat = $this->post('lat');
        $lng = $this->post('lng');
        $shipping_cost = $this->post('shipping_cost');

        $order = $this->Cart_model->checkout($user->id, $address, $lat, $lng, $shipping_cost);

        $this->response([
            'status' => (boolean) $carts,
            'message' => 'Checkout success',
            'data' => [
                'order' => $order
            ]
        ], 200);

    }

}