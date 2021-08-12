<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // cek login dan role_id
        is_logged_in();
        $this->load->model("User_model");
    }

    public function index()
    {

        // $email = $this->session->userdata("email"); // ambil data dari session
        // $user = $this->db->get_where("user", ["email" => $email])->row_array(); // ambil data dari database
        // // echo "Selamat datang " . $user["name"];

        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "My Profile";

        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar", $data);
        $this->load->view("templates/topbar", $data);
        $this->load->view("user/index", $data);
        $this->load->view("templates/footer");
    }

    public function editProfile()
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "Edit Profile";

        // set rules form-validation
        $this->form_validation->set_rules("name", "Name", "trim|required");

        if( $this->form_validation->run() == false ) {
            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("user/editProfile", $data);
            $this->load->view("templates/footer");
        } else {

            if( $this->User_model->updateProfile($data) > 0 ) { // kirim $data untuk cari tahu nama gambar lama
                $this->session->set_flashdata("message", '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Success editing profile!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error editing profile!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            }
            redirect("user");
        }
    }

    public function changePassword()
    {
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        $data["page_name"] = "Change Password";

        // set rules
        $this->form_validation->set_rules("currentPassword", "Current Password", "trim|required");
        $this->form_validation->set_rules("newPassword1", "New Password", "trim|required|min_length[3]|matches[newPassword2]", [
            "matches" => "Password does not match!",
            "min_length" => "Must be at least 3 characters!"
        ]);
        $this->form_validation->set_rules("newPassword2", "Confirm New Password", "trim|required|min_length[3]|matches[newPassword1]", [
            "matches" => "Password does not match!",
            "min_length" => "Must be at least 3 characters!"
        ]);

        if( $this->form_validation->run() == false ) {
            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("user/changePassword", $data);
            $this->load->view("templates/footer");
        } else {
            $passwordDb = $data["user"]["password"];
            $currentPassword = htmlspecialchars($this->input->post("currentPassword", true));

            // cek pasword lama input dengan db
            if( password_verify($currentPassword, $passwordDb) ) {

                // cek password lama dan baru tidak boleh sama
                $newPass = htmlspecialchars($this->input->post("newPassword1", true));
                if( $currentPassword != $newPass ) {

                    // update password
                    if( $this->User_model->updatePassword() > 0 ) {
                        $this->session->set_flashdata("message", '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Success change password!.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    ');
                    } else {
                        $this->session->set_flashdata("message", '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Error change password!.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    ');
                    }
                } else {
                    $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        New password cannot by the same with current password!.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                ');
                }

            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Wrong Current Password!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            }
            redirect("user/changePassword");
        }
    }

}

?>