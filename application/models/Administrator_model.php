<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator_model extends CI_model
{    
    public function login($email, $password)
    {
        $password_hash = md5($password);
        $query = $this->db->query("SELECT *, NULL AS `password` FROM administrators a WHERE a.email = '$email' AND a.password = '$password_hash'");
        
        if($query->num_rows() > 0){
            return $query->result()[0];
        }else{
            return false;
        }

    }
}