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
        $this->form_validation->set_rules('txtFname', 'First Name', 'required');
        $this->form_validation->set_rules('txtLname', 'Last Name', 'required');
        $this->form_validation->set_rules('txtEmail', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);

            $profile_pic = null;
            if ($this->upload->do_upload('profile_pic')) {
                $upload_data = $this->upload->data();
                $profile_pic = $upload_data['file_name'];
            }

            $this->load->view('add');
        } else {
            // File upload config
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048; // 2MB
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

            // ✅ Hash the password before saving
            $password = password_hash($this->input->post('txtPassword'), PASSWORD_BCRYPT);

            $arrData = [
                'first_name' => $this->input->post('txtFname'),
                'last_name' => $this->input->post('txtLname'),
                'address' => $this->input->post('txtAddress'),
                'email' => $this->input->post('txtEmail'),
                'password' => $password, // ✅ hashed password
                'mobile' => $this->input->post('txtMobile'),
                'profile_pic' => $profile_pic
            ];

            $insert = $this->Register_model->insert($arrData);
            if ($insert) {
                $this->session->set_flashdata('success', 'User added successfully!');
                redirect('register/add');
            } else {
                $this->load->view('add');
            }
        }
    }

    public function edit($id)
    {
        $user = $this->Register_model->getUserById($id);

        if (empty($user)) {
            show_error('User not found', 404);
            return;
        }

        $data['user'] = $user;
        $this->load->view('edit', $data);
    }

    public function update($id)
    {
        $this->load->library('upload');

        $data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'address'    => $this->input->post('address'),
            'email'      => $this->input->post('email'),
            'password'   => $this->input->post('password'),
            'mobile'     => $this->input->post('mobile')
        ];

        // Handle profile picture upload
        if (!empty($_FILES['profile_pic']['name'])) {
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('profile_pic')) {
                $uploadData = $this->upload->data();
                $data['profile_pic'] = $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('register/edit/' . $id);
                return;
            }
        } else {
            // If no new file is uploaded, keep the existing one
            $data['profile_pic'] = $this->input->post('existing_pic');
        }

        // Update user
        if ($this->Register_model->updateUser($id, $data)) {
            $this->session->set_flashdata('success', 'User updated successfully');
            redirect('register');
        } else {
            show_error('Update failed');
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
