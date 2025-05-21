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
        if ($this->session->userdata('logged_in')) {
            redirect('register');
        }

        $this->load->view('login');
    }

    public function process()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('login');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->user_model->validate_user($username, $password);

            if ($user) {
                $this->session->sess_regenerate(TRUE); // Prevent session fixation

                $session_data = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                redirect('register');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('login');
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
