<?php 

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // echo base_url();
        $this->parser->parse('templates/auth', [
            'content' => $this->load->view('home', isset($data) ? $data : null, true)
        ]);
    }
}