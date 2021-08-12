<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Admin_model");

        // cek login & role_id
        is_logged_in();
    }

    public function index()
    {

        // $email = $this->session->userdata("email"); // ambil data dari session
        // $user = $this->db->get_where("user", ["email" => $email])->row_array(); // ambil data dari database
        // // echo "Selamat datang " . $user["name"];

        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "Dashboard";

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar", $data);
        $this->load->view("templates/topbar", $data);
        $this->load->view("admin/index", $data);
        $this->load->view("templates/footer");
    }

    public function role()
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "Role";
        $data["role"] = $this->Admin_model->getAllRole();
        
        $this->form_validation->set_rules("role", "Role", "trim|required");

        if( $this->form_validation->run() == false ) {
            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("admin/role", $data);
            $this->load->view("templates/footer");
        } else {
            if( $this->Admin_model->addNewRole() > 0 ) {
                $this->session->set_flashdata("message", '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    New role added!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error added new role!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            }
            redirect("admin/role");
        }

    }

    public function deleteRole($roleId)
    {
        if( $this->Admin_model->deleteRoleById($roleId) > 0 ) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success deleting role!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting role!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("admin/role");
    }

    public function getEditRole()
    {
        $data = $this->Admin_model->getRoleById();
        echo json_encode($data);
    }

    public function editRole()
    {
        if( $this->Admin_model->updateRoleById() > 0 ) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success editing role!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error editing role!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("admin/role");
    }

    public function roleAccess($roleId)
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();
        
        $data["page_name"] = "Role Access";
        $data["role"] = $this->Admin_model->getRoleByIdUrl($roleId);
        $data["menu"] = $this->Admin_model->getAllMenu();
        
        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar", $data);
        $this->load->view("templates/topbar", $data);
        $this->load->view("admin/role-access", $data);
        $this->load->view("templates/footer");
        
    }

    public function changeAccessMenu()
    {
        // ketika data ada, hapus kalau tidak ada maka insert
        if( $this->Admin_model->getAccessByRoleIdAndIdUserMenu() > 0 ) {
            $this->Admin_model->deleteAccessMenu();
        } else {
            $this->Admin_model->addNewAccessMenu();
        }

        $this->session->set_flashdata("message", '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Access Changed!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    ');
    }

}

?>