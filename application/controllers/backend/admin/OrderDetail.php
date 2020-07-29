<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class OrderDetail extends Redirectifunauthenticated 
{

    public function __construct()
    {
        parent::__construct();
    }

    public function data($order_id)
    {

        header('Content-Type: application/json');

        $limit = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'created_at';
        $order = $this->input->get('order') ? $this->input->get('order') : 'DESC';

        $sql = "SELECT
                    order_details.id,
                    order_details.order_id,
                    order_details.product_stock_id,
                    order_details.qty,
                    order_details.price,
                    order_details.created_at,
                    order_details.updated_at,
                    orders.invoice_number,
                    users.`name` AS customer_name,
                    users.email AS customer_email,
                    product_stocks.qty AS product_stock_qty,
                    product_stocks.price AS product_stock_price,
                    products.`name` AS product_name,
                    stores.`name` AS store_name
                FROM
                    order_details
                LEFT JOIN orders ON order_details.order_id = orders.id
                LEFT JOIN users ON orders.user_id = users.id
                LEFT JOIN product_stocks ON order_details.product_stock_id = product_stocks.id
                LEFT JOIN products ON product_stocks.product_id = products.id
                LEFT JOIN stores ON products.store_id = stores.id ";

        $where_array = [
            'orders.id = '. $order_id,
            'order_details.id LIKE "%'. $search .'%"',
            'order_details.order_id LIKE "%'. $search .'%"',
            'order_details.product_stock_id LIKE "%'. $search .'%"',
            'order_details.qty LIKE "%'. $search .'%"',
            'order_details.price LIKE "%'. $search .'%"',
            'order_details.created_at LIKE "%'. $search .'%"',
            'order_details.updated_at LIKE "%'. $search .'%"',
            'orders.invoice_number LIKE "%'. $search .'%"',
            'users.name LIKE "%'. $search .'%"',
            'users.email LIKE "%'. $search .'%"',
            'product_stocks.qty LIKE "%'. $search .'%"',
            'product_stocks.price LIKE "%'. $search .'%"',
            'products.name LIKE "%'. $search .'%"',
            'stores.name LIKE "%'. $search .'%"',
        ];

        $sql .= "WHERE " . implode(" OR ", $where_array);

        $query = $this->db->query($sql);
        $data['count'] = $query->num_rows();

        $sql .= "ORDER BY $sort $order ";
        $sql .= "LIMIT $limit OFFSET $offset ";

        $query = $this->db->query($sql);
        $data['rows'] = $query->result();

        echo json_encode($data);
    }

    public function index($order_id)
    {
        $query = $this->db->query("SELECT
                                        orders.id,
                                        orders.invoice_number,
                                        orders.user_id,
                                        orders.address,
                                        orders.lat,
                                        orders.lng,
                                        orders.shipping_cost,
                                        orders.`status`,
                                        orders.created_at,
                                        orders.updated_at,
                                        users.`name` AS user_name,
                                        users.email AS user_email
                                    FROM
                                        orders
                                    INNER JOIN users ON orders.user_id = users.id
                                    WHERE orders.id = ?", [$order_id]);
        $data['order'] = $query->row();

       
        $query = $this->db->query("SELECT
                                        order_details.id,
                                        order_details.order_id,
                                        order_details.product_stock_id,
                                        order_details.qty,
                                        order_details.price,
                                        order_details.created_at,
                                        order_details.updated_at,
                                        orders.invoice_number,
                                        users.`name` AS customer_name,
                                        users.email AS customer_email,
                                        product_stocks.qty AS product_stock_qty,
                                        products.`name` AS product_name,
                                        stores.`name` AS store_name
                                    FROM
                                        order_details
                                    LEFT JOIN orders ON order_details.order_id = orders.id
                                    LEFT JOIN users ON orders.user_id = users.id
                                    LEFT JOIN product_stocks ON order_details.product_stock_id = product_stocks.id
                                    LEFT JOIN products ON product_stocks.product_id = products.id
                                    LEFT JOIN stores ON products.store_id = stores.id 
                                    WHERE orders.id = $order_id ");

        $data['order_details'] = $query->result();

        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/order_detail/index', isset($data) ? $data : null, true)
        ]);
    }

    public function create()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/order/form', isset($data) ? $data : null, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules([
            ['field' => 'user_id', 'label' => 'User', 'rules' => 'required'],
            ['field' => 'address', 'label' => 'Address', 'rules' => 'required'],
            ['field' => 'lat', 'label' => 'Lat', 'rules' => 'required'],
            ['field' => 'lng', 'label' => 'Lng', 'rules' => 'required'],
            ['field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->create();
        }else{

            $data = [
                'invoice_number' => $this->input->post('invoice_number'),
                'user_id' => $this->input->post('user_id'),
                'address' => $this->input->post('address'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'lng' => $this->input->post('lat'),
            ];
            
            $insert = $this->db->insert('orders', $data);
            
            // logging in process
            redirect('backend/admin/order');
        }
    }

    public function edit($order_id)
    {
        $query = $this->db->query("SELECT * FROM orders WHERE id = ?", [$order_id]);
        if($query){

            $data['order'] = $query->row();

            $this->parser->parse('templates/backend', [
                'content' => $this->load->view('backend/admin/order/form', isset($data) ? $data : null, true)
            ]);
        }

        
    }

    public function update($order_id)
    {
        $this->form_validation->set_rules([
            ['field' => 'user_id', 'label' => 'User', 'rules' => 'required'],
            ['field' => 'address', 'label' => 'Address', 'rules' => 'required'],
            ['field' => 'lat', 'label' => 'Lat', 'rules' => 'required'],
            ['field' => 'lng', 'label' => 'Lng', 'rules' => 'required'],
            ['field' => 'shipping_cost', 'label' => 'Shipping Cost', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->edit($order_id);
        }else{

            $data = [
                'invoice_number' => $this->input->post('invoice_number'),
                'user_id' => $this->input->post('user_id'),
                'address' => $this->input->post('address'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'lng' => $this->input->post('lat'),
            ];

            $this->db->where('id', $order_id);
            $update = $this->db->update('orders', $data);
            
            // logging in process
            redirect('backend/admin/order');
        }
    }

    public function delete($order_id)
    {
        $delete = $this->db->delete('orders', ['id' => $order_id]);

        redirect('backend/admin/order');
    }

    
}