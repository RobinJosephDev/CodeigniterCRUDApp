<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
        $this->load->model('Register_model');
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->library('pagination');
        $this->load->model('Register_model');

        // Pagination configuration
        $config = [
            'base_url' => base_url('register/index'),
            'total_rows' => $this->Register_model->count_users(),
            'per_page' => 5,
            'uri_segment' => 3,
            'full_tag_open' => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'prev_tag_open' => '<li>',
            'prev_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a>',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
        ];

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['register_detail'] = $this->Register_model->get_users_paginated($config['per_page'], $page);
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('list', $data);
    }


    public function add()
    {
        // Set validation rules
        $this->form_validation->set_rules('txtFname', 'First Name', 'required');
        $this->form_validation->set_rules('txtLname', 'Last Name', 'required');
        $this->form_validation->set_rules('txtEmail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('txtPassword', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('txtMobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('add');
        } else {
            // Upload config
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            $config['detect_mime']   = TRUE;
            $config['overwrite']     = FALSE;
            $config['encrypt_name']  = TRUE;
            $this->load->library('upload', $config);

            $profile_pic = '';
            if (!empty($_FILES['profile_pic']['name'])) {
                if ($this->upload->do_upload('profile_pic')) {
                    $uploadData = $this->upload->data();
                    $profile_pic = $uploadData['file_name'];
                } else {
                    $data['upload_error'] = $this->upload->display_errors();
                    $this->load->view('add', $data);
                    return;
                }
            }

            // Hash password
            $password = password_hash($this->input->post('txtPassword'), PASSWORD_BCRYPT);

            // Prepare data
            $arrData = [
                'first_name'   => $this->input->post('txtFname'),
                'last_name'    => $this->input->post('txtLname'),
                'address'      => $this->input->post('txtAddress'),
                'email'        => $this->input->post('txtEmail'),
                'password'     => $password,
                'mobile'       => $this->input->post('txtMobile'),
                'profile_pic'  => $profile_pic
            ];

            // Save to DB
            if ($this->Register_model->insert($arrData)) {
                $this->session->set_flashdata('success', 'User added successfully!');
                redirect('register/add');
            } else {
                $data['error'] = 'Failed to add user.';
                $this->load->view('add', $data);
            }
        }
    }

    public function edit($id)
    {
        $user = $this->Register_model->getUserById($id);
        if (empty($user)) {
            show_error('User not found', 404);
        }

        $data['user'] = $user;
        $this->load->view('edit', $data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');

        if ($this->form_validation->run() == FALSE) {
            $data['user'] = $this->Register_model->getUserById($id);
            $this->load->view('edit', $data);
            return;
        }

        // File upload config
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;
        $config['detect_mime']   = TRUE;
        $config['overwrite']     = FALSE;
        $this->load->library('upload', $config);

        $profile_pic = $this->input->post('existing_pic'); // default to existing

        if (!empty($_FILES['profile_pic']['name'])) {
            if ($this->upload->do_upload('profile_pic')) {
                $uploadData = $this->upload->data();
                $profile_pic = $uploadData['file_name'];
            } else {
                $data['user'] = $this->Register_model->getUserById($id);
                $data['upload_error'] = $this->upload->display_errors();
                $this->load->view('edit', $data);
                return;
            }
        }

        $password = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

        $arrData = [
            'first_name'   => $this->input->post('first_name'),
            'last_name'    => $this->input->post('last_name'),
            'address'      => $this->input->post('address'),
            'email'        => $this->input->post('email'),
            'password'     => $password,
            'mobile'       => $this->input->post('mobile'),
            'profile_pic'  => $profile_pic
        ];

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
        $delete = $this->Register_model->delete($id);
        if ($delete) {
            redirect('register');
        }
    }
}
