<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Redirectifunauthenticated.php';

class Dashboard extends Redirectifunauthenticated 
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->parser->parse('templates/backend', [
            'content' => $this->load->view('backend/admin/dashboard/index', isset($data) ? $data : null, true)
        ]);
    }
}