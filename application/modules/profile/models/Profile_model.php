<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Profile.Add');
        $this->ENABLE_MANAGE  = has_permission('Profile.Manage');
        $this->ENABLE_VIEW    = has_permission('Profile.View');
        $this->ENABLE_DELETE  = has_permission('Profile.Delete');
    }

    public function get_user_by_id($id_user)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->get('users')->row();
    }

    public function get_client_apps($id_user)
    {
        $this->db->select("GROUP_CONCAT(helpdesk_client.name_app SEPARATOR ', ') as client_apps");
        $this->db->from('helpdesk_user_client');
        $this->db->join(
            'helpdesk_client',
            'helpdesk_client.id = helpdesk_user_client.client_id 
             AND helpdesk_client.is_delete = 0',
            'left'
        );
        $this->db->where('helpdesk_user_client.id_user', $id_user);
        $this->db->where('helpdesk_user_client.is_active', 1);

        return $this->db->get()->row();
    }

    public function update_password($id_user, $password_hashed)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'password' => $password_hashed
            ]);
    }

    public function is_username_exist($username, $id_user)
    {
        return $this->db
            ->where('username', $username)
            ->where('id_user !=', $id_user)
            ->get('users')
            ->num_rows();
    }

    public function update_username($id_user, $username)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'username' => $username
            ]);
    }

    public function get_user_photo($id_user)
    {
        return $this->db
            ->select('photo')
            ->where('id_user', $id_user)
            ->get('users')
            ->row();
    }

    public function update_photo($id_user, $photo_name)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'photo' => $photo_name
            ]);
    }

    public function delete_photo($id_user)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'photo' => NULL
            ]);
    }
}
