<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    public function getAllMenu()
    {
        return $this->db->get("user_menu")->result_array();
    }

    public function addNewMenu()
    {
        $newMenu = htmlspecialchars($this->input->post("menu", true));
        $this->db->insert("user_menu", ["menu" => $newMenu]);
        return $this->db->affected_rows();
    }

    public function deleteMenu($id_user_menu)
    {
        $this->db->delete("user_menu", ["id_user_menu" => $id_user_menu]);
        return $this->db->affected_rows();
    }

    public function getMenuById()
    {
        return $this->db->get_where("user_menu", ["id_user_menu" => $this->input->post("id")])->row_array();
    }

    public function editData()
    {
        $data = [
            "menu" => htmlspecialchars($this->input->post("menu", true))
        ];
        $this->db->where("id_user_menu", $this->input->post("id-user-menu"));
        $this->db->update("user_menu", $data);
        return $this->db->affected_rows();
    }

    public function getAllSubmenu()
    {
        // return $this->db->get("user_sub_menu")->result_array();
        return $this->db->query("SELECT user_sub_menu.*, user_menu.menu FROM user_sub_menu JOIN user_menu ON(user_sub_menu.id_user_menu = user_menu.id_user_menu)")->result_array();
    }

    public function addNewSubmenu()
    {
        $dataNewSubmenu = [
            "id_user_menu" => htmlspecialchars($this->input->post("idUserMenu", true)),
            "title" => htmlspecialchars($this->input->post("title", true)),
            "url" => htmlspecialchars($this->input->post("url", true)),
            "icon" => htmlspecialchars($this->input->post("icon", true)),
            "is_active" => htmlspecialchars($this->input->post("isActive", true))
        ];
        $this->db->insert("user_sub_menu", $dataNewSubmenu);
        return $this->db->affected_rows();
    }

    public function deleteSubmenu($id_user_sub_menu)
    {
        $this->db->delete("user_sub_menu", ["id_user_sub_menu" => $id_user_sub_menu]);
        return $this->db->affected_rows();
    }

    public function getSubmenuById()
    {
        return $this->db->get_where("user_sub_menu", ["id_user_sub_menu" => $this->input->post("idUserSubmenu", true)])->row_array();
    }

    public function updateSubmenu()
    {
        $data = [
            "title" => htmlspecialchars($this->input->post("title", true)),
            "id_user_menu" => htmlspecialchars($this->input->post("idUserMenu", true)),
            "url" => htmlspecialchars($this->input->post("url", true)),
            "icon" => htmlspecialchars($this->input->post("icon", true)),
            "is_active" => htmlspecialchars($this->input->post("isActive", true))
        ];
        $this->db->where("id_user_sub_menu", $this->input->post("id-user-sub-menu", true));
        $this->db->update("user_sub_menu", $data);
        return $this->db->affected_rows();
    }
}

?>