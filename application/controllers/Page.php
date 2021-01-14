<?php

class Page extends CI_Controller
{
    public function __construct() 
    {
        parent::__construct();

        $this->load->model("UserModel");
    }

    public function about()
    {
        $this->load->view("about");
    }

    public function login()
    {
        $this->load->view("login");
    }

    public function login_action()
    {
        $this->load->library("form_validation");
        $this->load->library("session");

        $ff = $this->form_validation;
        $ss = $this->session;

        $ff->set_rules("username", "Username", "required");
        $ff->set_rules("password", "Password", "required");

        if (!$ff->run()) {
            $ss->set_flashdata("msg_username", "Username required");
            $ss->set_flashdata("msg_password", "Password required");
            redirect(site_url("login"));
            return;
        }

        $data = $this->input->post();

        if (!$this->UserModel->has_username($data["username"])) {
            $ss->set_flashdata("msg_username", "Username doesn't exist");
            redirect(site_url("login"));
            return;
        }

        $user = $this->UserModel->get($data["username"]);

        if (!password_verify($data["password"], $user->password)) {
            $ss->set_flashdata("msg_password", "Password doesn't match");
            redirect(site_url("login"));
            return;
        }

        redirect(site_url("dashboard"));
    }
}