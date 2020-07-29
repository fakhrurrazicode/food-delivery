<?php 

use chriskacerguis\RestServer\RestController;

class Auth extends RestController{

    public function __construct()
    {
        parent::__construct();
    }

    public function register_post()
    {

        $this->form_validation->set_data([
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'phone_number' => $this->post('phone_number'),
            'password' => $this->post('password'),
            'password_confirmation' => $this->post('password_confirmation'),
        ]);

        $this->form_validation->set_rules([
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'phone_number', 'label' => 'Email', 'rules' => 'required'],
            ['field' => 'password', 'label' => 'Passsword', 'rules' => 'required'],
            ['field' => 'password_confirmation', 'label' => 'Passsword Confirmation', 'rules' => 'required'],
        ]);

        if($this->form_validation->run() == false){
            $this->response([
                'status' => false,
                'error' => $this->form_validation->error_array(),
            ], 200);
        }

        
        $query = $this->db->query("SELECT * FROM users WHERE email = ?", [$this->post('email')]);

        if($query->num_rows() > 0){
            return $this->response([
                'status' => false,
                'error' => [
                    'email' => 'The email is already used by someone else'
                ],
            ], 200);
        }

        $query = $this->db->query("INSERT INTO users (name, email, phone_number, password) VALUES (?, ?, ?, ?)", [
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'phone_number' => $this->post('phone_number'),
            'password' => md5($this->post('password')),
        ]);

        $id = $this->db->insert_id();

        $query = $this->db->query("SELECT * FROM users WHERE id = ? ", [$id]);
        $user = $query->row();

        $this->db->query("INSERT INTO api_token (token, user_id, expired_at) VALUES (?, ?, ?)", [
            md5(rand(1, 9999)),
            $user->id,
            'expired_at' => date('Y-m-d H:i:s', strtotime('+1 day', time()))
        ]);

        $api_token_id = $this->db->insert_id();
        $query = $this->db->query("SELECT * FROM api_token WHERE id = ?", [$api_token_id]);
        $user->api_token = $query->result();

        $this->response([
            'status' => true,
            'data' => [
                'user' => $user
            ],
        ], 200);
    }

    public function login_post()
    {

        $this->form_validation->set_data([
            'email' => $this->post('email'),
            'password' => $this->post('password'),
        ]);

        $this->form_validation->set_rules([
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'password', 'label' => 'Passsword', 'rules' => 'required'],
        ]);
        
        if($this->form_validation->run() == false){
            $this->response([
                'status' => false,
                'error' => $this->form_validation->error_array(),
            ], 200);
        }

        $query = $this->db->query("SELECT * FROM users WHERE email = ? AND password = ? ", [
            'email' => $this->post('email'),
            'password' => md5($this->post('password')),
        ]);

        if($query->num_rows() > 0){

            $user = $query->row();

            $this->db->query("INSERT INTO api_token (token, user_id, expired_at) VALUES (?, ?, ?)", [
                md5(rand(1, 9999)),
                $user->id,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+1 day', time()))
            ]);

            $api_token_id = $this->db->insert_id();
            $query = $this->db->query("SELECT * FROM api_token WHERE id = ?", [$api_token_id]);
            $user->api_token = $query->result();

            // create api_token
            $this->response([
                'status' => true,
                'data' => [
                    'user' => $user
                ]
            ], 200);

        }else{

            $this->response([
                'status' => false,
                'error' => [
                    'email' => 'Incorrect email and password combination'
                ]
            ], 200);
        }
    }

    public function logout_post(){

        $this->form_validation->set_rules([
            ['field' => 'token', 'label' => 'Token', 'rules' => 'required'],
        ]);

        if($this->form_validation->run() == false){
            $this->response([
                'status' => false,
                'error' => $this->form_validation->error_array(),
            ], 200);
        }

        $query = $this->db->query("SELECT * FROM api_token WHERE token = ?", [
            $this->post('token')
        ]);

        if($query->num_rows() === 0){
            $this->response([
                'status' => false,
                'error' => [
                    'token' => 'Token invalid, failed to logout'
                ]
            ], 200);
        }

        $this->response([
            'status' => true,
            'message' => 'Logged out succesfully',
        ], 200);

    }
}