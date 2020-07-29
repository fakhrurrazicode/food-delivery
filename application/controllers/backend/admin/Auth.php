<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Administrator_model', 'admin');
    }    

    public function index()
    {
        redirect('backend/admin/auth/login');
    }

    public function login()
    {
        $this->parser->parse('templates/auth', [
            'content' => $this->load->view('backend/admin/auth/login', isset($data) ? $data : null, true)
        ]);
    }

    public function submit_login()
    {
        $this->form_validation->set_rules([
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required'],
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->login();
        }else{

            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // login attempt
            $admin = $this->admin->login($email, $password);

            // set session 
            $this->session->set_userdata('logged_in_administrator', $admin);
            
            // logging in process
            redirect('backend/admin/dashboard');
        }
    }
}