<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_client_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Master_client.Add');
        $this->ENABLE_MANAGE  = has_permission('Master_client.Manage');
        $this->ENABLE_VIEW    = has_permission('Master_client.View');
        $this->ENABLE_DELETE  = has_permission('Master_client.Delete');
    }

    public function get_all_client()
    {
        $this->db->from('helpdesk_client');
        $this->db->where('is_delete', 0);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_client_by_id($id)
    {
        $this->db->from('helpdesk_client');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function insert_client($data)
    {
        $this->db->insert('helpdesk_client', $data);
        return $this->db->insert_id();
    }

    public function update_client($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('helpdesk_client', $data);
    }

    public function delete_client($id, $user_id)
    {
        $data = [
            'is_delete'   => 1,
            'create_by'   => $user_id,
            'create_date' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        return $this->db->update('helpdesk_client', $data);
    }

    public function check_client_exists($name_app, $exclude_id = null)
    {
        $this->db->select('id');
        $this->db->from('helpdesk_client');
        $this->db->where('name_app', $name_app);
        $this->db->where('is_delete', 0);

        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->get()->num_rows() > 0;
    }
}