<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Redirectifunauthenticated extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();

        if(!$this->session->logged_in_administrator){
            redirect('/backend/admin/auth');
        }
    }
}