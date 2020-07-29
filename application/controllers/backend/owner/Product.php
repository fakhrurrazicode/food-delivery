<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class Product extends Redirectifunauthenticated 
{

    public function __construct()
    {
        parent::__construct();
    }

    public function data()
    {

        header('Content-Type: application/json');

        $store_id = $this->session->logged_in_owner->store_id;

        $limit = $this->input->get('limit') ? $this->input->get('limit') : 10;
        $offset = $this->input->get('offset') ? $this->input->get('offset') : 0;
        $search = $this->input->get('search') ? $this->input->get('search') : '';
        $sort = $this->input->get('sort') ? $this->input->get('sort') : 'created_at';
        $order = $this->input->get('order') ? $this->input->get('order') : 'DESC';

        $sql = "SELECT
                    products.id,
                    products.name,
                    products.description,
                    products.photo,
                    products.store_id,
                    products.status,
                    products.created_at,
                    products.updated_at,
                    stores.name AS store_name
                FROM
                    products
                LEFT JOIN stores ON products.store_id = stores.id ";

        $where_array = [
            'products.id LIKE "%'. $search .'%"',
            'products.name LIKE "%'. $search .'%"',
            'products.photo LIKE "%'. $search .'%"',
            'products.description LIKE "%'. $search .'%"',
            'products.store_id LIKE "%'. $search .'%"',
            'products.created_at LIKE "%'. $search .'%"',
            'products.updated_at LIKE "%'. $search .'%"',
            'stores.name LIKE "%'. $search .'%"',
        ];

        $sql .= "WHERE (" . implode(" OR ", $where_array) . ") AND stores.id = ". $store_id . " ";

        

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
            'content' => $this->load->view('backend/owner/product/index', isset($data) ? $data : null, true)
        ]);
    }

    public function create()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/owner/product/form', isset($data) ? $data : null, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->create();
        }else{

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
            ];

            
            if (!empty($_FILES['photo']['name'])) {

                $array_file = explode('.', $_FILES['photo']['name']);
                $extension = end($array_file);

                $config['file_name']            = md5(rand()). '.'. $extension;
                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')){
                    $upload_data = $this->upload->data();
                    $data['photo'] = 'uploads/'. $upload_data['file_name'];
                }
            }
            

            // product to database 
            // $insert = $this->db->query("INSERT INTO products (name, photo, description, lat, lng) VALUES (?, ?, ?, ?, ?)", $data);
            $insert = $this->db->insert('products', $data);
            
            // logging in process
            redirect('backend/owner/product');
        }
    }

    public function edit($product_id)
    {
        $query = $this->db->query("SELECT * FROM products WHERE id = ?", [$product_id]);
        if($query){

            $data['product'] = $query->row();

            $this->parser->parse('templates/backend', [
                'content' => $this->load->view('backend/owner/product/form', isset($data) ? $data : null, true)
            ]);
        }

        
    }

    public function update($product_id)
    {
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->edit($product_id);
        }else{

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
            ];

            
            if (!empty($_FILES['photo']['name'])) {

                $array_file = explode('.', $_FILES['photo']['name']);
                $extension = end($array_file);

                $config['file_name']            = md5(rand()). '.'. $extension;
                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('photo')){
                    $upload_data = $this->upload->data();
                    $data['photo'] = 'uploads/'. $upload_data['file_name'];
                }
            }
            

            // product to database 
            $this->db->where('id', $product_id);
            $update = $this->db->update('products', $data);
            
            // logging in process
            redirect('backend/owner/product');
        }
    }

    public function delete($product_id)
    {
        $delete = $this->db->delete('products', ['id' => $product_id]);

        redirect('backend/owner/product');
    }

    
}