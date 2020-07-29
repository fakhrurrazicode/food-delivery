<?php 


function getUserAPI()
{
    $CI =& get_instance();
    $request_headers = $CI->input->request_headers();

    // print_r($request_headers);

    $access_token = $request_headers['Authorization'];
    $user = getUserByAccessToken($access_token);

    return $user;
}


function getUserByAccessToken($access_token)
{
    $CI =& get_instance();
    $query = $CI->db->query("SELECT * FROM api_token WHERE token = ?", [$access_token]);
    $api_token = $query->row();

    if($api_token){
        $query = $CI->db->query("SELECT * FROM users WHERE id = ?", [$api_token->user_id]);
        $user = $query->row();
        return $user;
    }else{
        return false;
    }
}
