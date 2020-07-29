<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class Store extends Redirectifunauthenticated 
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
                    stores.id,
                    stores.name,
                    stores.banner_image,
                    stores.address,
                    stores.lat,
                    stores.lng,
                    stores.user_id,
                    stores.created_at,
                    stores.updated_at,
                    users.name as user_name,
                    users.email as user_email
                FROM
                    stores
                LEFT JOIN users ON stores.user_id = users.id ";

        $where_array = [
            'stores.id LIKE "%'. $search .'%"',
            'stores.name LIKE "%'. $search .'%"',
            'stores.banner_image LIKE "%'. $search .'%"',
            'stores.address LIKE "%'. $search .'%"',
            'stores.lat LIKE "%'. $search .'%"',
            'stores.lng LIKE "%'. $search .'%"',
            'stores.user_id LIKE "%'. $search .'%"',
            'stores.created_at LIKE "%'. $search .'%"',
            'stores.updated_at LIKE "%'. $search .'%"',
            'users.name LIKE "%'. $search .'%"',
            'users.email LIKE "%'. $search .'%"',
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
        redirect('backend/owner/store/edit/'. $this->session->logged_in_owner->store_id);
    }

    public function create()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/owner/store/form', isset($data) ? $data : null, true)
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
                'address' => $this->input->post('address'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'user_id' => $this->input->post('user_id'),
            ];

            
            if (!empty($_FILES['banner_image']['name'])) {

                $array_file = explode('.', $_FILES['banner_image']['name']);
                $extension = end($array_file);

                $config['file_name']            = md5(rand()). '.'. $extension;
                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('banner_image')){
                    $upload_data = $this->upload->data();
                    $data['banner_image'] = 'uploads/'. $upload_data['file_name'];
                }
            }
            

            // store to database 
            // $insert = $this->db->query("INSERT INTO stores (name, banner_image, address, lat, lng) VALUES (?, ?, ?, ?, ?)", $data);
            $insert = $this->db->insert('stores', $data);
            
            // logging in process
            redirect('backend/owner/store');
        }
    }

    public function edit($store_id)
    {
        $query = $this->db->query("SELECT * FROM stores WHERE id = ?", [$store_id]);
        if($query){

            $data['store'] = $query->row();
            $this->parser->parse('templates/backend', [
                'content' => $this->load->view('backend/owner/store/form', isset($data) ? $data : null, true)
            ]);
        }

        
    }

    public function update($store_id)
    {
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->create();
        }else{

            $data = [
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
            ];

            
            if (!empty($_FILES['banner_image']['name'])) {

                $array_file = explode('.', $_FILES['banner_image']['name']);
                $extension = end($array_file);

                $config['file_name']            = md5(rand()). '.'. $extension;
                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('banner_image')){
                    $upload_data = $this->upload->data();
                    $data['banner_image'] = 'uploads/'. $upload_data['file_name'];
                }
            }
            

            // store to database 
            $this->db->where('id', $store_id);
            $update = $this->db->update('stores', $data);
            
            // logging in process
            redirect('backend/owner/store');
        }
    }

    public function delete($store_id)
    {
        $delete = $this->db->delete('stores', ['id' => $store_id]);

        redirect('backend/owner/store');
    }

    
}