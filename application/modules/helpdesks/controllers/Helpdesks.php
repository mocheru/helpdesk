<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Helpdesk.View';
  protected $addPermission    = 'Helpdesk.Add';
  protected $managePermission = 'Helpdesk.Manage';
  protected $deletePermission = 'Helpdesk.Delete';

  protected $id_user;
  protected $datetime;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Helpdesk/Helpdesk_model'
    ));
    $this->template->title('Manage Helpdesk');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    // print_r('cek');
    // exit;
    $this->auth->restrict($this->viewPermission);

    $this->template->title('Manage Helpdesk');
    $this->template->page_icon('fa fa-table');
    $this->template->render('index');
  }

  public function get_list_ticket()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Helpdesk_model->get_all_ticket();
    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_helpdesk', $data);
  }

  public function get_list_approved()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Helpdesk_model->get_approved_ticket();
    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_approved', $data);
  }

  public function get_list_cancel()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Helpdesk_model->get_cancel_ticket();
    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_cancel', $data);
  }

  // ADD TICKET FUNCTION
  public function add_ticket()
  {
    $this->auth->restrict($this->addPermission);

    $current_user_id = $this->auth->user_id();
    $current_user = $this->Helpdesk_model->get_user_by_id($current_user_id);
    $user_clients = $this->Helpdesk_model->get_user_clients($current_user_id);

    $data = [
      'categories' => $this->Helpdesk_model->get_categories(),
      'users' => $this->Helpdesk_model->get_users(),
      'clients' => $user_clients,
      'helpdesk' => null,
      'is_external' => ($current_user && $current_user->status == 1),
      'view_mode' => false
    ];

    $this->template->title('Add Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-plus');
    $this->template->render('form_ticket', $data);
  }

  public function edit_ticket($id)
  {
    $this->auth->restrict($this->managePermission);

    $helpdesk = $this->Helpdesk_model->get_ticket_by_id($id);

    if (!$helpdesk) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('helpdesk');
    }

    $current_user_id = $this->auth->user_id();
    $current_user = $this->Helpdesk_model->get_user_by_id($current_user_id);
    $user_clients = $this->Helpdesk_model->get_user_clients($current_user_id);

    $data = [
      'categories' => $this->Helpdesk_model->get_categories(),
      'users' => $this->Helpdesk_model->get_users(),
      'clients' => $user_clients,
      'helpdesk' => $helpdesk,
      'is_external' => ($current_user && $current_user->status == 1),
      'view_mode' => false
    ];

    $this->template->title('Edit Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-pen-to-square');
    $this->template->render('form_ticket', $data);  // ✅ Unified form
  }

  public function view_ticket($id)
  {
    $this->auth->restrict($this->viewPermission);

    $helpdesk = $this->Helpdesk_model->get_ticket_detail($id);

    if (!$helpdesk) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('helpdesk');
    }

    $data = [
      'categories' => $this->Helpdesk_model->get_categories(),
      'users' => $this->Helpdesk_model->get_users(),
      'clients' => [],
      'helpdesk' => $helpdesk,
      'view_mode' => true
    ];

    $this->template->title('View Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-eye');
    $this->template->render('form_ticket', $data);
  }

  public function get_sub_categories_select()
  {
    $category_id = $this->input->post('category_id');

    $sub_categories = $this->Helpdesk_model->get_sub_categories($category_id);

    if ($sub_categories) {
      $response = [
        'status' => 1,
        'data' => $sub_categories
      ];
    } else {
      $response = [
        'status' => 0,
        'data' => [],
        'message' => 'Sub category tidak ditemukan'
      ];
    }

    echo json_encode($response);
  }

  public function save_ticket()
  {
    $id = $this->input->post('id');
    if ($id) {
      $this->auth->restrict($this->managePermission);
    } else {
      $this->auth->restrict($this->addPermission);
    }

    $pic_id = $this->input->post('pic_id');
    $user_pic = $this->Helpdesk_model->get_user_by_id($pic_id);
    $pic_name = $user_pic ? $user_pic->nm_lengkap : '';
    $approval_id = $this->input->post('approval_by_id');
    $approval_name = '';

    if ($approval_id) {
      $user_approval = $this->Helpdesk_model->get_user_by_id($approval_id);
      $approval_name = $user_approval ? $user_approval->nm_lengkap : '';
    }

    $client_id = $this->input->post('client_id');
    $client_name = '';
    if ($client_id) {
      $client = $this->Helpdesk_model->get_client_by_id($client_id);
      $client_name = $client ? $client->name_app : '';
    }

    $category_id = $this->input->post('category_id');
    $category = $this->Helpdesk_model->get_category_by_id($category_id);
    $category_name = $category ? $category->category_name : '';

    $sub_category_id = $this->input->post('sub_category_id');
    $sub_category = $this->Helpdesk_model->get_sub_category_by_id($sub_category_id);
    $sub_category_name = $sub_category ? $sub_category->sub_name : '';

    $data = [
      'report' => $this->input->post('report'),
      'category_id' => $category_id,
      'category_name' => $category_name,
      'sub_category_id' => $sub_category_id,
      'sub_category_name' => $sub_category_name,
      'causes' => $this->input->post('causes'),
      'action_plan' => $this->input->post('action_plan'),
      'due_date' => $this->input->post('due_date'),
      'pic_id' => $pic_id,
      'pic' => $pic_name,
      'client_id' => $client_id,
      'client_name' => $client_name,
      'approval_by_id' => $approval_id,
      'approval_by' => $approval_name,
      'update_date' => date('Y-m-d H:i:s'),
      'update_by' => $this->auth->user_id()
    ];

    if ($id) {
      $result = $this->Helpdesk_model->update_ticket($id, $data);
      $message = 'Data berhasil diupdate';
    } else {
      $data['no_ticket'] = $this->generate_ticket_number();
      $data['status'] = 0;
      $data['create_by_id'] = $this->auth->user_id();
      $data['create_by'] = $this->auth->user_name();
      $data['create_date'] = date('Y-m-d H:i:s');
      $data['is_delete'] = 0;

      $result = $this->Helpdesk_model->insert_ticket($data);
      $message = 'Data berhasil disimpan';
    }

    if ($result) {
      $response = [
        'status' => 1,
        'message' => $message
      ];
    } else {
      $response = [
        'status' => 0,
        'message' => 'Gagal menyimpan data'
      ];
    }

    echo json_encode($response);
  }

  private function generate_ticket_number()
  {
    // Format: HDYYMMXXXX
    $prefix = 'HD';
    $year = date('y');
    $month = date('m');
    $yearMonth = $year . $month;
    $last_ticket = $this->Helpdesk_model->get_last_ticket_number($yearMonth);

    if ($last_ticket) {
      $last_number = (int) substr($last_ticket->no_ticket, -4);
      $new_number = $last_number + 1;
    } else {
      $new_number = 1;
    }

    // Format: HDYYMMXXXX
    $ticket_number = $prefix . $yearMonth . str_pad($new_number, 4, '0', STR_PAD_LEFT);

    return $ticket_number;
  }

  public function update_status()
  {
    if (!has_permission('Helpdesk.Manage')) {
      echo json_encode(['status' => 0, 'message' => 'Anda tidak memiliki izin untuk mengubah status ticket']);
      return;
    }

    $this->load->model('helpdesk_model');

    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $userId = $this->session->userdata('user_id');
    $userName = $this->session->userdata('full_name');

    // Validasi input
    if (empty($id) || !is_numeric($status)) {
      echo json_encode(['status' => 0, 'message' => 'Data tidak valid']);
      return;
    }

    $statusText = [
      0 => 'Open',
      1 => 'Process',
      2 => 'Pending',
      3 => 'Cancel',
      4 => 'Done'
    ];

    $statusName = isset($statusText[$status]) ? $statusText[$status] : 'Unknown';

    $data = [
      'status' => $status,
      'update_date' => date('Y-m-d H:i:s'),
      'update_by' => $userId,
      'update_by_id' => $userId
    ];

    if ($status == 4) {
      $data['approval_by'] = $userName;
      $data['approval_by_id'] = $userId;
      $data['approval_date'] = date('Y-m-d H:i:s');
    }

    if ($status == 3 && $this->input->post('cancel_reason')) {
      $data['cancel_reason'] = $this->input->post('cancel_reason');
    }

    try {
      $result = $this->helpdesk_model->update_ticket_status($id, $data);

      if ($result) {
        echo json_encode([
          'status' => 1,
          'message' => "Status ticket berhasil diubah menjadi {$statusName}"
        ]);
      } else {
        echo json_encode([
          'status' => 0,
          'message' => 'Gagal mengubah status ticket'
        ]);
      }
    } catch (Exception $e) {
      echo json_encode([
        'status' => 0,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }

  public function get_ticket_details($id)
  {
    if (!has_permission('Helpdesk.View')) {
      echo json_encode(['status' => 0, 'message' => 'Access denied']);
      return;
    }

    $this->load->model('helpdesk_model');
    $ticket = $this->helpdesk_model->get_ticket_by_id($id);

    if ($ticket) {
      echo json_encode([
        'status' => 1,
        'data' => [
          'no_ticket' => $ticket->no_ticket,
          'report' => $ticket->report,
          'pic' => $ticket->pic,
          'current_status' => $ticket->status
        ]
      ]);
    } else {
      echo json_encode(['status' => 0, 'message' => 'Ticket tidak ditemukan']);
    }
  }

  public function update_approval()
  {
    $id = $this->input->post('id');
    $is_approve = $this->input->post('is_approve'); // 1=approve, 2=reject
    $approval_reason = $this->input->post('approval_reason');
    $userId = $this->session->userdata('user_id');
    $userId = $this->id_user  = $this->auth->user_id();
    $userName = $this->id_user  = $this->auth->nama();

    $data = [
      'is_approve' => $is_approve,
      'approval_reason' => $approval_reason,
      'approval_by' => $userName,
      'approval_by_id' => $userId,
      'approval_date' => date('Y-m-d H:i:s'),
    ];

    if ($is_approve == 1) {
      $data['status'] = 5; // Closed
    }

    try {
      $result = $this->Helpdesk_model->update_ticket_approval($id, $data);

      if ($result) {
        $actionText = ($is_approve == 1) ? 'approve' : 'reject';

        echo json_encode([
          'status' => 1,
          'message' => "Ticket berhasil di-{$actionText}"
        ]);
      } else {
        echo json_encode([
          'status' => 0,
          'message' => 'Gagal mengupdate approval ticket'
        ]);
      }
    } catch (Exception $e) {
      echo json_encode([
        'status' => 0,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ]);
    }
  }
}
