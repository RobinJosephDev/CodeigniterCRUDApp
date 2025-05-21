<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // ✅ Load the database here
    }

    public function validate_user($first_name, $password)
    {
        $query = $this->db->where('first_name', $first_name)
                          ->where('password', $password) // Note: not secure! See below.
                          ->get('register');

        return $query->row_array();
    }
}
