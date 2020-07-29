<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class Order extends Redirectifunauthenticated 
{

    public function __construct()
    {
        parent::__construct();
    }

    public function data()
    {

        header('Content-Type: application/json');

        $limit = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'created_at';
        $order = $this->input->get('order') ? $this->input->get('order') : 'DESC';

        $sql = "SELECT
                    orders.id,
                    orders.invoice_number,
                    orders.user_id,
                    orders.address,
                    orders.lat,
                    orders.lng,
                    orders.shipping_cost,
                    orders.status,
                    orders.created_at,
                    orders.updated_at,
                    users.name as user_name,
                    users.email as user_email
                FROM
                    orders
                INNER JOIN users ON orders.user_id = users.id ";

        $where_array = [
            'orders.id LIKE "%'. $search .'%"',
            'orders.invoice_number LIKE "%'. $search .'%"',
            'orders.user_id LIKE "%'. $search .'%"',
            'orders.address LIKE "%'. $search .'%"',
            'orders.lat LIKE "%'. $search .'%"',
            'orders.lng LIKE "%'. $search .'%"',
            'orders.shipping_cost LIKE "%'. $search .'%"',
            'orders.status LIKE "%'. $search .'%"',
            'orders.created_at LIKE "%'. $search .'%"',
            'orders.updated_at LIKE "%'. $search .'%"',
            'users.name LIKE "%'. $search .'%"',
            'users.email LIKE "%'. $search .'%"',
        ];

        $sql .= "WHERE " . implode(" OR ", $where_array);

        $query = $this->db->query($sql);
        $data['count'] = $query->num_rows();

        $sql .= "ORDER BY $sort $order ";
        $sql .= "LIMIT $limit OFFSET $offset ";

        $query = $this->db->query($sql);
        $rows = $query->result();
        
        $mapped_rows = array_map(function($order){
            $order->sub_total = 0;
            $order->order_detail = [];
            $query = $this->db->query("SELECT * FROM order_details WHERE order_id = ?", [$order->id]);
            foreach($query->result() as $order_detail){
                $order->sub_total += $order_detail->qty * $order_detail->price;
                $order->order_detail[] = $order_detail;
            }

            $order->grand_total = $order->sub_total + $order->shipping_cost;
            return $order;

        }, $rows);

        $data['rows'] = $mapped_rows;
        

        echo json_encode($data);
    }

    public function index()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/order/index', isset($data) ? $data : null, true)
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
                'shipping_cost' => $this->input->post('shipping_cost'),
            ];
            
            $insert = $this->db->insert('orders', $data);

            $order_id = $this->db->insert_id();
            
            // logging in process
            redirect('backend/admin/orderdetail/index/'. $order_id);
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
                'shipping_cost' => $this->input->post('shipping_cost'),
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