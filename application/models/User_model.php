<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function updateProfile($data)
    {
        // cek ada gambar yang diubah atau tidak
        // var_dump($_FILES["picture"]);
        $uploadPicture = $_FILES["picture"]["name"];
        if( $uploadPicture ) {
            // cek file yang diupload
            // gambar boleh apa saja
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']     = '2048';    // satuan kb jika 2 mega = 2048kb karena 1 mb = 1024kb 
            $config['upload_path'] = './assets/img/profile'; // tempat simpan gambar, (.) disini seperti base_url

            // setelah itu jalankan librarynya
            $this->load->library('upload', $config);

            if( $this->upload->do_upload("picture") ) { // method upload ke directori

                // hapus gambar lama didirectory kecuali gambar default
                $oldImage = $data["user"]["image"];
                if( $oldImage != "default.jpg" ) { // jika gambar bukan defaul maka hapus
                    unlink(FCPATH . "assets/img/profile/" . $oldImage);  // method unlink() tidak bisa pakai base_url() harus pakai FCPATH atau Front Controller
                }

                $new_image = $this->upload->data("file_name");  // method yang menyimapn data file yang sudah diupload
                $this->db->set("image", $new_image);    // set update nama gambar baru, jika ada gambar yang diupload
            } else {
                $this->session->set_flashdata("message", '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $this->upload->display_errors() . '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                ');
                redirect("user");
            }

        } 

        $this->db->set("name", htmlspecialchars($this->input->post("name", true))); // set update secara terpisah agar bisa dibuat kondisi
        $this->db->where("email", $this->input->post("email", true));
        $this->db->update("user");
        return $this->db->affected_rows();
    }

    public function updatePassword()
    {
        $newPassword = password_hash( htmlspecialchars($this->input->post("newPassword1", true)), PASSWORD_DEFAULT);
        $this->db->where("email", $this->session->userdata("email"));
        $this->db->update("user", ["password" => $newPassword]);
        return $this->db->affected_rows();
    }

}

?>