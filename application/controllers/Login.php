<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('user_model');
    }

    public function index()
    {
        // If already logged in, redirect to register page
        if ($this->session->userdata('logged_in')) {
            redirect('register');
        }

        $this->load->view('login');
    }

    public function process()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('login');
            return;
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->user_model->validate_user($username, $password);

        if ($user) {
            // Secure session regeneration
            $this->session->sess_regenerate(TRUE);

            // Set session data
            $session_data = [
                'user_id'   => $user['id'],
                'username'  => $user['username'],
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_data);

            redirect('register');
        } else {
            // Set error message and reload login
            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
