<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class ProductStock extends Redirectifunauthenticated 
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
                    product_stocks.id,
                    product_stocks.qty,
                    product_stocks.price,
                    product_stocks.product_id,
                    product_stocks.created_at,
                    product_stocks.updated_at,
                    products.name AS product_name,
                    stores.name AS store_name
                FROM
                    product_stocks
                LEFT JOIN products ON product_stocks.product_id = products.id
                LEFT JOIN stores ON products.store_id = stores.id ";

        $where_array = [
            'product_stocks.id LIKE "%'. $search .'%"',
            'product_stocks.qty LIKE "%'. $search .'%"',
            'product_stocks.price LIKE "%'. $search .'%"',
            'product_stocks.product_id LIKE "%'. $search .'%"',
            'product_stocks.created_at LIKE "%'. $search .'%"',
            'product_stocks.updated_at LIKE "%'. $search .'%"',
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

    public function index()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/product_stock/index', isset($data) ? $data : null, true)
        ]);
    }

    public function create()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/product_stock/form', isset($data) ? $data : null, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules([
            ['field' => 'qty', 'label' => 'Qty', 'rules' => 'required'],
            ['field' => 'price', 'label' => 'Price', 'rules' => 'required'],
            ['field' => 'product_id', 'label' => 'Product', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->create();
        }else{

            $data = [
                'qty' => $this->input->post('qty'),
                'price' => $this->input->post('price'),
                'product_id' => $this->input->post('product_id'),
            ];

            $insert = $this->db->insert('product_stocks', $data);
            
            // logging in process
            redirect('backend/admin/productstock');
        }
    }

    public function edit($product_stock_id)
    {
        $query = $this->db->query("SELECT * FROM product_stocks WHERE id = ?", [$product_stock_id]);
        if($query){

            $data['product_stock'] = $query->row();

            $this->parser->parse('templates/backend', [
                'content' => $this->load->view('backend/admin/product_stock/form', isset($data) ? $data : null, true)
            ]);
        }

        
    }

    public function update($product_stock_id)
    {
        $this->form_validation->set_rules([
            ['field' => 'qty', 'label' => 'Qty', 'rules' => 'required'],
            ['field' => 'price', 'label' => 'Price', 'rules' => 'required'],
            ['field' => 'product_id', 'label' => 'Product', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->edit($product_stock_id);
        }else{

            $data = [
                'qty' => $this->input->post('qty'),
                'price' => $this->input->post('price'),
                'product_id' => $this->input->post('product_id'),
            ];
            

            // product to database 
            $this->db->where('id', $product_stock_id);
            $update = $this->db->update('product_stocks', $data);
            
            // logging in process
            redirect('backend/admin/productstock');
        }
    }

    public function delete($product_stock_id)
    {
        $delete = $this->db->delete('product_stocks', ['id' => $product_stock_id]);

        redirect('backend/admin/productstock');
    }

    
}