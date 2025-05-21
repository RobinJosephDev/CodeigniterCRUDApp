<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function validate_user($username, $password)
    {
        // Fetch user by username
        $query = $this->db->where('username', $username)
            ->get('register'); // Replace 'register' with actual table if different

        $user = $query->row_array();

        // Verify password if user exists
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return full user array
        }

        return false; // Invalid credentials
    }
}
