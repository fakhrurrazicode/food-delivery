<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Owner_model extends CI_model
{    
    public function login($email, $password)
    {
        $password_hash = md5($password);
        $query = $this->db->query("SELECT *, NULL AS `password` FROM users a WHERE a.email = '$email' AND a.password = '$password_hash' AND a.store_id IS NOT NULL ");
        
        if($query->num_rows() > 0){
            return $query->result()[0];
        }else{
            return false;
        }

    }
}