<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class User extends Redirectifunauthenticated 
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
                    users.id,
                    users.name,
                    users.email,
                    users.email_verified_at,
                    users.password,
                    users.remember_token,
                    users.created_at,
                    users.updated_at
                FROM
                    users
                    ";

        $where_array = [
            'users.id LIKE "%'. $search .'%"',
            'users.name LIKE "%'. $search .'%"',
            'users.email LIKE "%'. $search .'%"',
            'users.email_verified_at LIKE "%'. $search .'%"',
            'users.password LIKE "%'. $search .'%"',
            'users.remember_token LIKE "%'. $search .'%"',
            'users.created_at LIKE "%'. $search .'%"',
            'users.updated_at LIKE "%'. $search .'%"',
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
            'content' => $this->load->view('backend/admin/user/index', isset($data) ? $data : null, true)
        ]);
    }

    public function create()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/user/form', isset($data) ? $data : null, true)
        ]);
    }

    public function store()
    {
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->create();
        }else{

            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'password' => md5($this->input->post('password')),
            ];

            $insert = $this->db->insert('users', $data);
            
            // logging in process
            redirect('backend/admin/user');
        }
    }

    public function edit($user_id)
    {
        $query = $this->db->query("SELECT * FROM users WHERE id = ?", [$user_id]);
        if($query){

            $data['user'] = $query->row();

            $this->parser->parse('templates/backend', [
                'content' => $this->load->view('backend/admin/user/form', isset($data) ? $data : null, true)
            ]);
        }

        
    }

    public function update($user_id)
    {
        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
            // ['field' => 'password', 'label' => 'Password', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->edit($user_id);
        }else{

            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                // 'password' => $this->input->post('password'),
            ];
            

            // user to database 
            $this->db->where('id', $user_id);
            $update = $this->db->update('users', $data);
            
            // logging in process
            redirect('backend/admin/user');
        }
    }

    public function delete($user_id)
    {
        $delete = $this->db->delete('users', ['id' => $user_id]);

        redirect('backend/admin/user');
    }

    
}