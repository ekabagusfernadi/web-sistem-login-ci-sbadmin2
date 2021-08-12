<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // cek login dan role_id
        is_logged_in();

        // $this->load->library("form_validation");
        $this->load->model("Menu_model");
    }

    public function index()
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "Menu Management";
        $data["menu"] = $this->Menu_model->getAllMenu();

        // rules validation
        $this->form_validation->set_rules("menu", "Menu", "trim|required|alpha",[
            "required" => "Menu field is empty!"
        ]);

        // gagal validasi
        if( $this->form_validation->run() == false ) {
            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("menu/index", $data);
            $this->load->view("templates/footer");
        } else {

            if( $this->Menu_model->addNewMenu() > 0 ) {
                $this->session->set_flashdata("message", '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    New menu added!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error added new menu!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            }
            redirect("menu");
        }
    }

    public function delete($id_user_menu)
    {
        if( $this->Menu_model->deleteMenu($id_user_menu) > 0 ) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success deleting menu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting menu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("menu");
    }

    public function getEdit()
    {
        $tes = $this->Menu_model->getMenuById();
        echo json_encode($tes);
    }

    public function edit()
    {

        if( $this->Menu_model->editData() > 0 ) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success editing menu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error editing menu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("menu");
    }

    public function submenu()
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();
        $data["page_name"] = "Submenu Management";
        $data["submenu"] = $this->Menu_model->getAllSubmenu();
        $data["menu"] = $this->Menu_model->getAllMenu();

        $this->form_validation->set_rules("title", "Title", "trim|required");
        $this->form_validation->set_rules("idUserMenu", "Menu", "trim|required");
        $this->form_validation->set_rules("url", "Url", "trim|required");
        $this->form_validation->set_rules("icon", "Icon", "trim|required");

        if( $this->form_validation->run() == false ) {
            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("menu/submenu", $data);
            $this->load->view("templates/footer");
        } else {
            if( $this->Menu_model->addNewSubmenu() > 0 ) {
                $this->session->set_flashdata("message", '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    New Submenu added!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error added new Submenu!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            }

            redirect("menu/submenu");
        }

    }

    public function deleteSubmenu($id_user_sub_menu)
    {
        if( $this->Menu_model->deleteSubmenu($id_user_sub_menu) > 0) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success deleting submenu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error deleting submenu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("menu/submenu");
    }

    public function getEditSubmenu()
    {
        $dataSubmenu = $this->Menu_model->getSubmenuById();
        echo json_encode($dataSubmenu);
    }

    public function editSubmenu()
    {
        if( $this->Menu_model->updateSubmenu() > 0 ) {
            $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Success editing submenu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error editing submenu!.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        ');
        }
        redirect("menu/submenu");
    }

}

?>