<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function getAllRole()
    {
        return $this->db->get("user_role")->result_array();
    }

    public function addNewRole()
    {
        $this->db->insert("user_role", ["role" => htmlspecialchars($this->input->post("role", true))]);
        return $this->db->affected_rows();
    }

    public function deleteRoleById($roleId)
    {
        $this->db->delete("user_role", ["role_id" => $roleId]);
        return $this->db->affected_rows();
    }

    public function getRoleById()
    {
        return $this->db->get_where("user_role", ["role_id" => htmlspecialchars($this->input->post("roleId", true))])->row_array();
    }

    public function updateRoleById()
    {
        $this->db->where("role_id", htmlspecialchars($this->input->post("role_id", true)));
        $this->db->update("user_role", ["role" => htmlspecialchars($this->input->post("role", true))]);
        return $this->db->affected_rows();
    }

    public function getRoleByIdUrl($roleId)
    {
        return $this->db->get_where("user_role", ["role_id" => htmlspecialchars($roleId)])->row_array();
    }

    public function getAllMenu()
    {
        return $this->db->get_where("user_menu", ["id_user_menu !=" => 1])->result_array();
    }

    public function getAccessByRoleIdAndIdUserMenu()
    {
        $data = $this->db->get_where("user_access_menu", ["role_id" => htmlspecialchars($this->input->post("roleId", true)), "id_user_menu" => htmlspecialchars($this->input->post("idUserMenu", true))]);
        return $data->num_rows();
    }

    public function addNewAccessMenu()
    {
        $this->db->insert("user_access_menu", ["role_id" => htmlspecialchars($this->input->post("roleId", true)), "id_user_menu" => htmlspecialchars($this->input->post("idUserMenu", true))]);
    }

    public function deleteAccessMenu()
    {
        $this->db->delete("user_access_menu", ["role_id" => htmlspecialchars($this->input->post("roleId", true)), "id_user_menu" => htmlspecialchars($this->input->post("idUserMenu", true))]);
    }


}

?>