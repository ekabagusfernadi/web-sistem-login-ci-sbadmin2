<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();  // panggil method __constructor yang ada id CI_Controller
        $this->load->library("form_validation");    // load form-validation untuk satu controller

        // load model
        $this->load->model("Auth_model");
    }

    public function index()
    {

        // cek login
        if( $this->session->userdata("email") ) {
            redirect("user");
        }

        // rules validasi
        $this->form_validation->set_rules("email", "Email", "required|trim|valid_email");
        $this->form_validation->set_rules("password", "Password", "required|trim");

        // validasi
        if( $this->form_validation->run() == false ) {
            $data["page_name"] = "Login";

            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/login");
            $this->load->view("templates/auth_footer");
        } else {
            // ketika validasi lolos
            // masuk ke method private _login()
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post("email");
        $password = $this->input->post("password");

        $user = $this->db->get_where("user", ["email" => $email])->row_array();

        // if ( $this->db->affected_rows() > 0 && password_verify($password, $user["password"])) { // password_verify() untuk cek password_hash
        //     echo "data ada";
        // } else {
        //     $this->session->set_flashdata("message", '
        //         <div class="alert alert-danger alert-dismissible fade show" role="alert">
        //             <strong>Warning!</strong> email or password is wrong.
        //             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        //                 <span aria-hidden="true">&times;</span>
        //             </button>
        //         </div>
        //     ');
        //     redirect("auth");
        // }

        if( $user ) {
            // email ada
            if( $user["is_active"] == 1 ) {
                // user sudah aktifasi
                // echo "user aktifasi sudah";

                if( password_verify($password, $user["password"]) ) {
                    // password user sesuai
                    // ambil 2 data dari $user untuk dijadikan session yaitu email, dan role

                    $data = [
                        "email" => $user["email"],
                        "role_id" => $user["role_id"]   // menentukan menu yang tampil
                    ];
                    // masukkan ke session
                    $this->session->set_userdata($data);
                    
                    // cek role_id
                    if( $this->session->userdata("role_id") == "1" ) {
                        redirect("admin");
                    } else {
                        redirect("user");
                    }

                } else {
                    // password salah
                    $this->session->set_flashdata("message", '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Wrong password!.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    ');
                    redirect("auth");
                }

            } else {
                // user belum aktifasi
                $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        This email has not been activated.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                ');
                redirect("auth");
            }
        } else {
            // email tidak ada
            $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Email is not registered!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ');
            redirect("auth");
        }
    }

    public function register()
    {
        // cek login
        if( $this->session->userdata("email") ) {
            redirect("user");
        }

        // rules form_validation
        $this->form_validation->set_rules("name", "Name", "required|trim"); // par1=name_form, par2=alias yang akan dimunculkan dipesan kesalahan, par3=rulesnya apa, jika lebih dari 1 pisah dengan | (trim = agar jika ada spasi didepan/dibelakang tidak akan dimasukkan)
        $this->form_validation->set_rules("email", "Email", "required|trim|valid_email|is_unique[user.email]", [
            "is_unique" => "This email has already registered!"
        ]); // is_unique[nama_tabel.nama_field] = agar input ini nilainya harus unique
        $this->form_validation->set_rules("password1", "Password", "required|trim|min_length[3]|matches[password2]", [
            "matches" => "Password not match!",  // par2 = ganti tulisan error
            "min_length" => "Password min 3 chars"
        ]);  // min_length[3] = min karakter password, matches[password2] harus sama valuenya dengan yang dikururng
        $this->form_validation->set_rules("password2", "Password", "required|trim|matches[password1]"); // min_length[] tidak perlu karena sudah ikut password1

        // kirim data ke value input, agar tidak tulis lagi saat ada error
        // $data["name"] = $this->input->post("name");
        // $data = [
        //     "name" => $this->input->post("name"),
        //     "email" => $this->input->post("email")
        // ];

        // kondisi jika form_validation gagal/berhasil
        if( $this->form_validation->run() == false ) {
            $data["page_name"] = "Register";

            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/register");
            $this->load->view("templates/auth_footer");
        } else {

            if( $this->Auth_model->create_user() > 0 ) {

                // flash message
                $this->session->set_flashdata("message", '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Congratulation!</strong> your account has been created, need verify. Please check your email.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                ');    // par2=yang mau ditampilkan berserta tag html&cssnya juga bisa

            } else {
                $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Registration failed.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                ');
            }
            redirect("auth");
        }

    }

    public function logout()
    {
        // unset session
        $this->session->unset_userdata("email");
        $this->session->unset_userdata("role_id");

        // tampilkan pesan logout
        $this->session->set_flashdata("message", '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                You have been logged out!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        '); 

        // redirect halaman login
        redirect("auth");

    }

    public function blocked()
    {
        $data["page_name"] = "Access Blocked";

        $this->load->view("auth/blocked", $data);

    }

    public function verify()
    {   
        //ambil email dan token
        $email = $this->input->get("email");
        $token = $this->input->get("token");

        $user = $this->db->get_where("user", ["email" => $email])->row_array();

        // cek user
        if( $user ) {
            $tokenDb = $this->db->get_where("user_token", ["token" => $token])->row_array();
            // var_dump($tokenDb); die;
            // cocokkan token
            if( $tokenDb ) {
                // cek waktu kadaluarsa token
                $dateCreated = $tokenDb["date_created"];
                if( time() - $dateCreated < (60*60*24) ) {    // apabila waktu waktu saat ini - waktu buat token kurang dari 24 jam maka true
                    
                    // update is_active
                    $this->db->where("email", $email);
                    $this->db->update("user", ["is_active" => 1]);
                    // delete token
                    $this->db->delete("user_token", ["email" => $email]);
    
                    $this->session->set_flashdata("message", '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $email . ' has been activated! Please login.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    ');
                } else {
                    $this->db->delete("user", ["email" => $email]);
                    $this->db->delete("user_token", ["email" => $email]);

                    $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Account activation failed! Expired token.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    ');
                }

            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Account activation failed! Wrong token.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ');
            }
        } else {
            $this->session->set_flashdata("message", '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Account activation failed! Wrong email.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ');
        }
        redirect("auth");
    }

    public function forgotPassword()
    {
        
        // set_rules
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");

        if( $this->form_validation->run() == false ) {
            $data["page_name"] = "Forgot Password";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/forgotPassword");
            $this->load->view("templates/auth_footer");
        } else {
            $email = htmlspecialchars($this->input->post("email", true));
            // cek email ada tidak didb
            $user = $this->db->get_where("user", ["email" => $email, "is_active" => 1])->row_array();
            
            if( $user ) {
                // cek apakah user telah punya token sebelumnya atau belum
                $userTokenDb = $this->db->get_where("user_token", ["email" => $email])->row_array();
                if( $userTokenDb ) {
                    // jika ada user yang sama di user_token maka kasih pesan error
                    $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Reset password link has been send to your email!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    '); 
                } else {
                    // jika tidak ada user / email tersebut di tabel user_token
                    // generate random token
                    $token = base64_encode(random_bytes(32));
                    // insert data token ke db
                    $user_token = [
                        "email" => $email,
                        "token" => $token,
                        "date_created" => time()
                    ];
                    $this->db->insert("user_token", $user_token);
    
                    // kirim email
                    $this->Auth_model->set_sendEmail($token, "forgotPassword");
    
                    // pesan
                    $this->session->set_flashdata("message", '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Please check your email to reset your password!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    ');
                }
            } else {
                $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Email is not registered or not activated!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                '); 
            }
            redirect("auth/forgotPassword");
        }
    }

    public function resetPassword()
    {
        // ambil email dan token
        $getEmail = htmlspecialchars($this->input->get("email", true));
        $getToken = htmlspecialchars($this->input->get("token", true));

        // cek email
        $user = $this->db->get_where("user", ["email" => $getEmail])->row_array();
        if( $user ) {
            //cek token
            $token = $this->db->get_where("user_token", ["token" => $getToken])->row_array();
            if( $token ) {
                // cek masa kadaluarsa token
                if( time() - $token["date_created"] < (60*60*24) ) {
                    // lolos semua pengecekan
                    // halaman reset password akan muncul hanya ketika session reset_email dibuat
                    $this->session->set_userdata("reset_email", $getEmail);
                    $this->changePassword();

                } else {
                    // delete token
                    $this->db->delete("user_token", ["token" => $getToken]);

                    // pesan
                    $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Reset password failed! Token expired.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    '); 
                    redirect("auth");
                }
            } else {
                $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Reset password failed! Wrong token.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                '); 
                redirect("auth");
            }
        } else {
            $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Reset password failed! Wrong email.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            '); 
            redirect("auth");
        }
    }

    public function changePassword()
    {
        // jika tidak ada session userdata("reset_email") maka redirect
        if( !$this->session->userdata("reset_email") ) {
            redirect("auth");
        }

        // set rules
        $this->form_validation->set_rules("password1", "Password", "trim|required|min_length[3]|matches[password2]");
        $this->form_validation->set_rules("password2", "Repeat Password", "trim|required|min_length[3]|matches[password1]");

        if( $this->form_validation->run() == false ) {
            $data["page_name"] = "Change Password";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/changePassword");
            $this->load->view("templates/auth_footer");
        } else {
            
            if( $this->Auth_model->changePasswordByEmail() > 0 ) {
                $this->session->set_flashdata("message", '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Change password success! Please login.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ');
            } else {
                $this->session->set_flashdata("message", '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Change password failed!.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                ');
            }
            redirect("auth");
        }
    }

}

?>