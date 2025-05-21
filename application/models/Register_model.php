<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // 'default' is loaded by default, so no need to specify
    }

    /**
     * Get all register records.
     */
    public function get_all_register_detail()
    {
        return $this->db->get('register')->result_array();
    }
    // Count all users
    public function count_users()
    {
        return $this->db->count_all('register');
    }

    // Fetch paginated users
    public function get_users_paginated($limit, $start)
    {
        return $this->db
            ->limit($limit, $start)
            ->order_by('id', 'DESC')
            ->get('register')
            ->result_array();
    }

    /**
     * Get a single register record by ID.
     */
    public function get_id_wise_register_detail($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('register');
        return $query->row_array(); // ✅ returns a single associative array
    }


    /**
     * Insert a new record into the register table.
     */
    public function insert($data)
    {
        return $this->db->insert('register', $data);
    }

    /**
     * Update a record in the register table by ID.
     */
    public function getUserById($id)
    {
        // Return a single associative array
        return $this->db->get_where('register', ['id' => $id])->row_array();
    }

    public function updateUser($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('register', $data);
    }

    /**
     * Delete a record from the register table by ID.
     */
    public function delete($id)
    {
        return $this->db->delete('register', ['id' => $id]);
    }
}
