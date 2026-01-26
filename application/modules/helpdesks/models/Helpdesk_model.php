<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Helpdesk_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Helpdesk.Add');
        $this->ENABLE_MANAGE  = has_permission('Helpdesk.Manage');
        $this->ENABLE_VIEW    = has_permission('Helpdesk.View');
        $this->ENABLE_DELETE  = has_permission('Helpdesk.Delete');
    }

    public function get_all_ticket()
    {
        $user_id = $this->auth->user_id();

        $this->db->order_by('id', 'DESC');
        $this->db->where('is_delete', 0);
        // $this->db->where('status !=', 3);
        // $this->db->where('is_approve !=', 1);

        if ($user_id != 7) { // 7 = admin
            $this->db->group_start()
                ->where('create_by_id', $user_id)
                ->or_where('pic_id', $user_id)
                ->or_where('approval_by_id', $user_id)
                ->group_end();
        }

        return $this->db->get('helpdesk')->result_array();
    }

    public function get_approved_ticket()
    {
        $user_id = $this->auth->user_id();

        $this->db->order_by('id', 'DESC');
        $this->db->where('is_delete', 0);
        $this->db->where('is_approve', 1);

        if ($user_id != 7) { // 7 = admin
            $this->db->group_start()
                ->where('create_by_id', $user_id)
                ->or_where('pic_id', $user_id)
                ->or_where('approval_by_id', $user_id)
                ->group_end();
        }

        return $this->db->get('helpdesk')->result_array();
    }

    public function get_cancel_ticket()
    {
        $user_id = $this->auth->user_id();

        $this->db->order_by('id', 'DESC');
        $this->db->where('is_delete', 0);
        $this->db->where('status', 3);

        if ($user_id != 7) { // 7 = admin
            $this->db->group_start()
                ->where('create_by_id', $user_id)
                ->or_where('pic_id', $user_id)
                ->or_where('approval_by_id', $user_id)
                ->group_end();
        }

        return $this->db->get('helpdesk')->result_array();
    }

    // ADD TICKET FUNCTION
    public function get_categories()
    {
        $this->db->select('*');
        $this->db->from('helpdesk_category');
        $this->db->where('is_delete', 0);
        $this->db->order_by('category_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_sub_categories($category_id)
    {
        $this->db->select('*');
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $category_id);
        $this->db->where('is_delete', 0);
        $this->db->order_by('sub_name', 'ASC');
        return $this->db->get()->result();
    }

    public function insert_ticket($data)
    {
        return $this->db->insert('helpdesk', $data);
    }

    public function update_ticket($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('helpdesk', $data);
    }

    public function get_last_ticket_number($yearMonth)
    {
        $this->db->select('no_ticket');
        $this->db->from('helpdesk');
        $this->db->like('no_ticket', 'HD' . $yearMonth, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function get_category_by_id($id)
    {
        $this->db->from('helpdesk_category');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_sub_category_by_id($id)
    {
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_users()
    {
        $this->db->select('id_user, username, nm_lengkap, email');
        $this->db->from('users');
        $this->db->where('st_aktif', 1);
        $this->db->where('deleted', 0);
        $this->db->order_by('nm_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('id_user, username, nm_lengkap, email, status');
        $this->db->from('users');
        $this->db->where('id_user', $id);
        return $this->db->get()->row();
    }

    public function get_user_clients($user_id)
    {
        $this->db->select('hc.id, hc.name_app, hc.remark');
        $this->db->from('helpdesk_user_client huc');
        $this->db->join('helpdesk_client hc', 'hc.id = huc.client_id');
        $this->db->where('huc.id_user', $user_id);
        $this->db->where('huc.is_active', 1);
        $this->db->where('hc.is_delete', 0);
        $this->db->order_by('hc.name_app', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_clients()
    {
        $this->db->select('id, name_app, remark');
        $this->db->from('helpdesk_client');
        $this->db->where('is_delete', 0);
        $this->db->order_by('name_app', 'ASC');
        return $this->db->get()->result();
    }

    public function get_client_by_id($id)
    {
        $this->db->select('id, name_app, remark');
        $this->db->from('helpdesk_client');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_ticket_detail($id)
    {
        $this->db->select('h.*, hc.category_name, hsc.sub_name, 
                       u_pic.nm_lengkap as pic_name, u_pic.username as pic_username,
                       u_approval.nm_lengkap as approval_name, u_approval.username as approval_username,
                       client.name_app as client_name');
        $this->db->from('helpdesk h');
        $this->db->join('helpdesk_category hc', 'hc.id = h.category_id', 'left');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left');
        $this->db->join('users u_pic', 'u_pic.id_user = h.pic_id', 'left');
        $this->db->join('users u_approval', 'u_approval.id_user = h.approval_by_id', 'left');
        $this->db->join('helpdesk_client client', 'client.id = h.client_id', 'left');
        $this->db->where('h.id', $id);
        return $this->db->get()->row();
    }

    public function get_ticket_by_id($id)
    {
        $this->db->select('h.*, hc.category_name, hsc.sub_name');
        $this->db->from('helpdesk h');
        $this->db->join('helpdesk_category hc', 'hc.id = h.category_id', 'left');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left');
        $this->db->where('h.id', $id);
        $this->db->where('h.is_delete', 0);
        return $this->db->get()->row();
    }

    public function update_ticket_status($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->update('helpdesk', $data);
    }

    public function check_ticket_exists($id)
    {
        $this->db->select('id');
        $this->db->from('helpdesk');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();
        return ($query->num_rows() > 0);
    }

    public function update_ticket_approval($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->update('helpdesk', $data);
    }
}
