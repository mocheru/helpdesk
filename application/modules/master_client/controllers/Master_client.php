<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_client extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Master_client.View';
  protected $addPermission    = 'Master_client.Add';
  protected $managePermission = 'Master_client.Manage';
  protected $deletePermission = 'Master_client.Delete';

  protected $id_user;
  protected $datetime;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Master_client/Master_client_model'
    ));
    $this->template->title('Manage Master Client');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);

    $this->template->title('Manage Helpdesk Client');
    $this->template->page_icon('fa fa-table');
    $this->template->render('index');
  }

  public function get_list_client()
  {
    $this->auth->restrict($this->viewPermission);
    $clients = $this->Master_client_model->get_all_client();

    $data['clients'] = $clients;
    $this->template->render('table/list_client', $data);
  }

  public function add_client()
  {
    $this->auth->restrict($this->addPermission);

    if ($this->input->post()) {
      $name_app = $this->input->post('name_app');
      $remark = $this->input->post('remark');

      // Validasi
      if (empty($name_app)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama aplikasi wajib diisi!'
        ]);
        return;
      }

      // Cek duplikat
      if ($this->Master_client_model->check_client_exists($name_app)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama aplikasi sudah terdaftar!'
        ]);
        return;
      }

      $data = [
        'name_app'      => $name_app,
        'remark'        => $remark,
        'create_date'   => $this->datetime,
        'create_by_id'  => $this->id_user,
        'create_by'     => $this->session->app_session['nm_lengkap'],
        'is_delete'     => 0
      ];

      $insert = $this->Master_client_model->insert_client($data);

      if ($insert) {
        $result = [
          'status'  => 1,
          'message' => 'Client berhasil ditambahkan.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal menambahkan client.'
        ];
      }

      echo json_encode($result);
    } else {
      $this->template->render('table/form_client');
    }
  }

  public function edit_client($id)
  {
    $this->auth->restrict($this->managePermission);

    if ($this->input->post()) {
      $name_app = $this->input->post('name_app');
      $remark = $this->input->post('remark');

      // Validasi
      if (empty($name_app)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama aplikasi wajib diisi!'
        ]);
        return;
      }

      // Cek duplikat (kecuali data yang sedang diedit)
      if ($this->Master_client_model->check_client_exists($name_app, $id)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama aplikasi sudah digunakan!'
        ]);
        return;
      }

      $data = [
        'name_app'      => $name_app,
        'remark'        => $remark,
        'update_date'   => $this->datetime,
        'update_by_id'  => $this->id_user,
        'update_by'     => $this->session->app_session['nm_lengkap'],
      ];

      $update = $this->Master_client_model->update_client($id, $data);

      if ($update) {
        $result = [
          'status'  => 1,
          'message' => 'Client berhasil diperbarui.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal memperbarui client.'
        ];
      }

      echo json_encode($result);
    } else {
      $client = $this->Master_client_model->get_client_by_id($id);
      $data['client'] = $client;
      $this->template->render('table/form_client', $data);
    }
  }

  public function delete_client()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');

    if (empty($id)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'ID client wajib diisi!'
      ]);
      return;
    }

    $delete = $this->Master_client_model->delete_client($id, $this->session->app_session['nm_lengkap']);

    if ($delete) {
      $result = [
        'status'  => 1,
        'message' => 'Client berhasil dihapus.'
      ];
    } else {
      $result = [
        'status'  => 0,
        'message' => 'Gagal menghapus client.'
      ];
    }

    echo json_encode($result);
  }
}