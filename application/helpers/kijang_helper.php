<?php

function is_logged_in()
{
    // library instansiasi object milik ci
    $ci = get_instance();   // $ci adalah object yang diinstansiasi oleh ci
    if( !$ci->session->userdata("email") ) {    // jadi tidak pakai $this, pakai $ci sebagai instansiasi object dari ci
        redirect("auth");
    } else {    // user access
        // ambil role id
        $role_id = $ci->session->userdata("role_id");
        // ambil sekarang ada dimenu/method apa
        $menu = $ci->uri->segment(1);   // ambil url segment 1 = paling kiri setelah index.php
        $status = 0;

        // ambil dari database cara sendiri
        // $tes = $ci->db->query("SELECT user_access_menu.*, user_menu.menu FROM user_access_menu JOIN user_menu ON(user_access_menu.id_user_menu = user_menu.id_user_menu) WHERE role_id = $role_id")->result_array();

        // foreach( $tes as $t ) {
        //     if( $menu == strtolower($t["menu"]) ) {
        //         $status = 1;
        //     }
        // }
        // if( $status == 0 ) {
        //     if( $tes[0]["role_id"] == 1 ) {
        //         redirect("admin");
        //     }
        //     if( $tes[0]["role_id"] == 2 ) {
        //         redirect("user");
        //     }
        // }
        
        // ambil user_menu_id
        $queryMenu = $ci->db->get_where("user_menu", ["menu" => $menu])->row_array();
        $menu_id = $queryMenu["id_user_menu"];

        // query user_acces_menu jika ada baris maka user tersebut bisa akses
        $userAccess = $ci->db->get_where("user_access_menu", [
            "role_id" => $role_id,
            "id_user_menu" => $menu_id
        ]);
        
        // cek num_rowsnya
        if( $userAccess->num_rows() < 1 ) {
            // $tes = $ci->db->get_where("user_menu", ["id_user_menu" => $role_id])->row_array();
            // redirect(strtolower($tes["menu"]));
            
            // buat halaman block dicontroller auth
            redirect("auth/blocked");
        }
    }
}

function check_access($roleId, $idUserMenu)
{
    $ci = get_instance();
    // cari data
    $data = $ci->db->get_where("user_access_menu", [
        "role_id" => $roleId,
        "id_user_menu" => $idUserMenu
    ]); // jangan di row_array() atau result_array() karena num_rows() tidak bisa baca
    
    // jika data ada maka centang
    if( $data->num_rows() > 0 ) {
        return "checked=checked";
    }
}

?>