<?php 

require_once('AuthenticatedRestController.php');

class Order extends AuthenticatedRestController{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("api_helper");
        $this->load->model('Cart_model');
    }

    public function index_get()
    {
        $user = getUserAPI();
        $query = $this->db->query("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC ", [$user->id]);
        $orders = $query->result();

        if($orders){

            $mapped_orders = array_map(function($order){
                
                $order->order_details = [];

                $query = $this->db->query("SELECT * FROM order_details WHERE order_id = ?", [$order->id]);
                $order->order_details = $query->num_rows() ? $query->result() : [];

                if($order->order_details){
                    $order->order_details = array_map(function($order_detail){
                        $order_detail->sub_total = $order_detail->qty * $order_detail->price;
                        
                        $order_detail->product_stock = null;
                        $query = $this->db->query("SELECT * FROM product_stocks WHERE id = ? ", [$order_detail->product_stock_id]);
                        if($query->num_rows() > 0){
                            $order_detail->product_stock = $query->row();
                        }

                        if($order_detail->product_stock){
                            $order_detail->product_stock->product = null;
                            $query = $this->db->query("SELECT * FROM products WHERE id = ? ", [$order_detail->product_stock->product_id]);
                            if($query->num_rows() > 0){
                                $order_detail->product_stock->product = $query->row();
                            }
                        }

                        return $order_detail;
                    }, $order->order_details);
                }

                // cari item_count, total & grand_total
                $order->item_count = count($order->order_details);
                $order->total = 0;
                foreach($order->order_details as $order_detail) $order->total += $order_detail->sub_total;
                $order->grand_total = $order->shipping_cost + $order->total;
                

                return $order;
            }, $orders);

            $mapped_orders = $this->Order_model->generateStatusMessage($mapped_orders);

            $this->response([
                'status' => (boolean) $orders,
                'data' => [
                    'orders' => $mapped_orders
                ]
            ], 200);
        }else{
            $this->response([
                'status' => (boolean) $orders,
                'data' => [
                    'orders' => []
                ]
            ], 200);
        }

        
    }

    public function show_get($order_id)
    {
        $query = $this->db->query("SELECT * FROM orders WHERE id = ?", [$order_id]);
        $order = $query->row();

        if($order){

            $order->order_details = [];

            $query = $this->db->query("SELECT * FROM order_details WHERE order_id = ?", [$order->id]);
            $order->order_details = $query->num_rows() ? $query->result() : [];

            if($order->order_details){
                $order->order_details = array_map(function($order_detail){
                    $order_detail->sub_total = $order_detail->qty * $order_detail->price;
                    
                    $order_detail->product_stock = null;
                    $query = $this->db->query("SELECT * FROM product_stocks WHERE id = ? ", [$order_detail->product_stock_id]);
                    if($query->num_rows() > 0){
                        $order_detail->product_stock = $query->row();
                    }

                    if($order_detail->product_stock){
                        $order_detail->product_stock->product = null;
                        $query = $this->db->query("SELECT * FROM products WHERE id = ? ", [$order_detail->product_stock->product_id]);
                        if($query->num_rows() > 0){
                            $order_detail->product_stock->product = $query->row();
                        }
                    }

                    return $order_detail;
                }, $order->order_details);
            }

            // cari item_count, total & grand_total
            $order->item_count = count($order->order_details);
            $order->total = 0;
            foreach($order->order_details as $order_detail) $order->total += $order_detail->sub_total;
            $order->grand_total = $order->shipping_cost + $order->total;
        }

        $this->response([
            'status' => (boolean) $order,
            'data' => [
                'order' => $order
            ]
        ], 200);
        
    }

    public function cancel_order_post($order_id)
    {
        // echo $order_id;
        // die();
        $update = $this->db->query("UPDATE orders SET `status` = 'cancelled' WHERE id = ? ", [$order_id]);

        $this->response([
            'status' => (boolean) $update,
            'message' => 'Order cancelled'
        ], 200);
    }

    public function payment_verification_post($order_id)
    {

        // echo json_encode($_FILES);
        // die();

        $query = $this->db->query("SELECT * FROM orders WHERE id = ? ", [$order_id]);

        if($query->num_rows() > 0){

            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('payment_verification_photo')){
                $this->response([
                    'status' => false,
                    'message' => 'Payment confirmation failed',
                    'error' => $this->upload->display_errors(),
                ], 200);

            }else{
                
                $file_name = $this->upload->data('file_name');

                $this->db->query("UPDATE orders SET status = 'payment-verification', payment_verification_photo = ? WHERE id = ? ", [base_url('/uploads/'. $file_name), $order_id]);

                $query = $this->db->query("SELECT * FROM orders WHERE id = ? ", [$order_id]);
                $order = $query->row(); 
                
                $this->response([
                    'status' => true,
                    'message' => 'Payment under verification by admin, please waiting a few minutes',
                    'data' => [
                        'order' => $order
                    ]
                ], 200);
            }

        }else{
            $this->response([
                'status' => (boolean) $query->num_rows(),
                'message' => 'Order does not exists'
            ], 200);
        }

        
    }
}