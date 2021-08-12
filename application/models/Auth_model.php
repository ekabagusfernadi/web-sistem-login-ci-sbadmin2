<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function create_user()
    {
        $data = [
            "name" => htmlspecialchars($this->input->post("name", true)),
            "email" => htmlspecialchars($this->input->post("email", true)),
            "image" => "default.jpg",    // semua user kalau baru daftar gambarnya sama
            "password" => password_hash($this->input->post("password1", true), PASSWORD_DEFAULT),    // enkripsi passwordnya pakai password_hash metode PASSWORD_DEFAULT agar dipilihkan metode yg terbaik
            "role_id" => 2,
            "is_active" => 0,
            "date_created" => time()    // mengambil detik saat daftar
        ];

        // siapkan token
        $token = base64_encode(random_bytes(32));  // generate bilangan random 32 bytes(random sekali karakternya sampai tidak bisa dibaca wkwk) // agar bisa dibaca oleh mysql beri function base64_encode(berubah jadi karakter yg bisa dibaca) // keduanya merupakan function milik php
        // var_dump($token);
        $user_token = [
            "email" => htmlspecialchars($this->input->post("email", true)),
            "token" => $token,
            "date_created" => time(), // buat masa aktif token
        ];

        // masukkan ke db
        $this->db->insert("user", $data);
        $this->db->insert("user_token", $user_token);

        $this->_sendEmail($token, "verify");    // _ = private method biar tau saja // kirimi $token buat dikirim ke email user, par 2 yaitu untuk fitur2nya bisa verify account atau forgot password misalnya

        // cek data masuk/tidak
        return $this->db->affected_rows();
    }

    private function _sendEmail($token, $type)
    {
        // atur konfigurasi
        $config = [
            "protocol" => "smtp",   // protocol simple mail transfer protocol
            "smtp_host" => "ssl://smtp.googlemail.com",  // provider email pengirim : google
            "smtp_user" => "kijadmn@gmail.com",  // email pengirim
            "smtp_pass" => "wersatiyu22487",  // password google pengirim
            "smtp_port" => 465,  // port 465 = port smtp google
            "mailtype" => "html",    // type email(karena ada linknya jadi html)
            "charset" => "utf-8",    // mau ditulis menggunakan karakter apa
            "newline" => "\r\n" // harus ditulis agar jalan pastikan tulis dengan "" = "\r\n"
        ];

        // panggil library email dicodeigniternya
        $this->load->library("email", $config);
        $this->email->initialize($config);  //tambahkan baris ini agar terhindar dari masalah error port

        // siapkan emailnya
        // $this->email disini adalah library email yang telah diload
        $this->email->from("kijadmn@gmail.com", "Kija Admin");  // dari siapa(par1 = emial pengirim, par2 = Nama Alias)
        $this->email->to(htmlspecialchars($this->input->post("email", true))); // dikirim ke siapa

        // cek tipe
        if( $type == "verify" ) {
            $this->email->subject("Account Verification");  // subject email
            $this->email->message('Click this link to verify your account : <a href="' . base_url("auth/verify?email=") . htmlspecialchars($this->input->post("email", true)) . '&token=' . urlencode($token) . '">Activate</a>');  // body/isi emailnya
        } else if( $type == "forgotPassword" ) {
            $this->email->subject("Reset Password");
            $this->email->message('Click this link to reset your account password : <a href="' . base_url("auth/resetPassword?email=") . htmlspecialchars($this->input->post("email", true)) . '&token=' . urlencode($token) . '">Reset Password</a>');
        }


        if( $this->email->send() ) {    // jika email dikirim = true
            return true;
        } else {
            echo $this->email->print_debugger();    // print errornya
            die;
        }

    }

    // method setter untuk panggil method private _sendEmail()
    public function set_sendEmail($token, $type)
    {
        $this->_sendEmail($token, $type);
    }

    // method change password by email
    public function changePasswordByEmail()
    {
        // ambil data
        $password = password_hash(htmlspecialchars($this->input->post("password1", true)), PASSWORD_DEFAULT);
        $email = $this->session->userdata("reset_email");

        // updata database
        $this->db->set("password", $password);
        $this->db->where("email", $email);
        $this->db->update("user");

        // unset session
        $this->session->unset_userdata("reset_email");

        // delete token
        $this->db->delete("user_token", ["email" => $email]);

        return $this->db->affected_rows();
    }

}

?>