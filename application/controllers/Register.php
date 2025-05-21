<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session', 'pagination', 'upload']);
        $this->load->model('Register_model');

        // ✅ Redirect to login if not logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        // Pagination configuration
        $config = [
            'base_url'        => base_url('register/index'),
            'total_rows'      => $this->Register_model->count_users(),
            'per_page'        => 5,
            'uri_segment'     => 3,
            'full_tag_open'   => '<ul class="pagination">',
            'full_tag_close'  => '</ul>',
            'first_tag_open'  => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open'   => '<li>',
            'last_tag_close'  => '</li>',
            'next_tag_open'   => '<li>',
            'next_tag_close'  => '</li>',
            'prev_tag_open'   => '<li>',
            'prev_tag_close'  => '</li>',
            'cur_tag_open'    => '<li class="active"><a>',
            'cur_tag_close'   => '</a></li>',
            'num_tag_open'    => '<li>',
            'num_tag_close'   => '</li>',
        ];

        $this->pagination->initialize($config);
        $page = $this->uri->segment(3) ?? 0;

        $data['register_detail'] = $this->Register_model->get_users_paginated($config['per_page'], $page);
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('list', $data);
    }

    public function add()
    {
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('add');
            return;
        }

        $profile_pic = $this->_handle_file_upload();
        if ($profile_pic === false) {
            $data['upload_error'] = $this->upload->display_errors();
            $this->load->view('add', $data);
            return;
        }

        $arrData = $this->_collect_post_data($profile_pic);

        if ($this->Register_model->insert($arrData)) {
            $this->session->set_flashdata('success', 'User added successfully!');
            redirect('register/add');
        } else {
            $data['error'] = 'Failed to add user.';
            $this->load->view('add', $data);
        }
    }

    public function edit($id)
    {
        $user = $this->Register_model->getUserById($id);
        if (!$user) {
            show_error('User not found', 404);
        }

        $data['user'] = $user;
        $this->load->view('edit', $data);
    }

    public function update($id)
    {
        $this->_set_validation_rules(true);

        if ($this->form_validation->run() === FALSE) {
            $data['user'] = $this->Register_model->getUserById($id);
            $this->load->view('edit', $data);
            return;
        }

        $existing_user = $this->Register_model->getUserById($id);
        $existing_pic = $existing_user->profile_pic;

        $new_upload = $this->_handle_file_upload();

        if ($new_upload === false && !empty($_FILES['profile_pic']['name'])) {
            $data['user'] = $existing_user;
            $data['upload_error'] = $this->upload->display_errors();
            $this->load->view('edit', $data);
            return;
        }

        if ($new_upload) {
            $profile_pic = $new_upload;

            if (!empty($existing_pic)) {
                $old_file_path = FCPATH . 'uploads/' . $existing_pic;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        } else {
            $profile_pic = $existing_pic;
        }

        $arrData = $this->_collect_post_data($profile_pic, true);

        if ($this->Register_model->updateUser($id, $arrData)) {
            $this->session->set_flashdata('success', 'User updated successfully!');
            redirect('register');
        } else {
            $data['user'] = $this->Register_model->getUserById($id);
            $data['error'] = 'Failed to update user.';
            $this->load->view('edit', $data);
        }
    }

    public function delete($id)
    {
        if ($this->Register_model->delete($id)) {
            $this->session->set_flashdata('success', 'User deleted.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('register');
    }

    // ---------------- Helper Methods ----------------

    private function _set_validation_rules($is_update = false)
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');

        $password_rule = $is_update ? 'min_length[6]' : 'required|min_length[6]';
        $this->form_validation->set_rules('password', 'Password', $password_rule);
    }

    private function _handle_file_upload()
    {
        if (empty($_FILES['profile_pic']['name'])) return '';

        $config = [
            'upload_path'   => './uploads/',
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE,
            'detect_mime'   => TRUE,
            'overwrite'     => FALSE,
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('profile_pic')) {
            return false;
        }

        $uploadData = $this->upload->data();
        return $uploadData['file_name'];
    }

    private function _collect_post_data($profile_pic, $is_update = false)
    {
        $password = $this->input->post('password');
        $hashed_password = $is_update && empty($password) ? null : password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'username'     => $this->input->post('username'),
            'email'        => $this->input->post('email'),
            'mobile'       => $this->input->post('mobile'),
            'profile_pic'  => $profile_pic,
        ];

        if ($hashed_password) {
            $data['password'] = $hashed_password;
        }

        return $data;
    }
}
