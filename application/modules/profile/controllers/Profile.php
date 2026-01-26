<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile Controller
 * Manage user profile, photo, username, and password
 */
class Profile extends Admin_Controller
{
  protected $viewPermission = "Profile.View";
  protected $managePermission = "Profile.Manage";

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Profile_model');
    $this->load->library('upload');
    $this->template->page_icon('fa fa-user');
  }

  public function index()
  {
    // $this->auth->restrict($this->viewPermission);

    $id_user = $this->auth->user_id();
    $data['user'] = $this->Profile_model->get_user_by_id($id_user);
    $client_data = $this->Profile_model->get_client_apps($id_user);
    $data['client_apps'] = $client_data ? $client_data->client_apps : '-';

    history("View profile");

    $this->template->title('My Profile');
    $this->template->render('index', $data);
  }


  public function update_photo()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();

    if (empty($_FILES['photo']['name'])) {
      echo json_encode([
        'status'  => 0,
        'message' => 'Tidak ada file yang diupload'
      ]);
      return;
    }

    $config['upload_path']   = './assets/images/users/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size']      = 2048;
    $config['encrypt_name']  = TRUE;

    // buat folder jika belum ada
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('photo')) {
      echo json_encode([
        'status'  => 0,
        'message' => $this->upload->display_errors('', '')
      ]);
      return;
    }


    $upload_data = $this->upload->data();
    $photo_name  = $upload_data['file_name'];
    $old_photo = $this->Profile_model->get_user_photo($id_user);

    if ($old_photo && !empty($old_photo->photo)) {
      $old_path = './assets/images/users/' . $old_photo->photo;
      if (file_exists($old_path)) {
        unlink($old_path);
      }
    }

    $result = $this->Profile_model->update_photo($id_user, $photo_name);

    if ($result) {

      $keterangan = "SUKSES, update foto profile user ID: $id_user";
      $status = 1;

      $response = [
        'status'    => 1,
        'message'   => 'Foto profile berhasil diupdate',
        'photo_url' => base_url('assets/images/users/' . $photo_name)
      ];
    } else {

      $keterangan = "GAGAL, update foto profile user ID: $id_user";
      $status = 0;

      $response = [
        'status'  => 0,
        'message' => 'Gagal mengupdate foto profile'
      ];
    }

    $sql = $this->db->last_query();
    simpan_aktifitas(
      $this->managePermission,
      $id_user,
      $keterangan,
      1,
      $sql,
      $status
    );

    echo json_encode($response);
  }


  public function update_username()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();
    $new_username = $this->input->post('username');

    // Validation
    $this->form_validation->set_rules(
      'username',
      'Username',
      'required|min_length[4]|max_length[30]|alpha_dash'
    );

    if ($this->form_validation->run() == FALSE) {

      $response = [
        'status'  => 0,
        'message' => validation_errors()
      ];
    } else {



      // cek username
      $check = $this->Profile_model->is_username_exist(
        $new_username,
        $id_user
      );

      if ($check > 0) {

        $response = [
          'status'  => 0,
          'message' => 'Username sudah digunakan'
        ];
      } else {

        $result = $this->Profile_model->update_username(
          $id_user,
          $new_username
        );

        if ($result) {

          $keterangan = "SUKSES, update username user ID: $id_user menjadi $new_username";
          $status = 1;

          $response = [
            'status'  => 1,
            'message' => 'Username berhasil diupdate'
          ];
        } else {

          $keterangan = "GAGAL, update username user ID: $id_user";
          $status = 0;

          $response = [
            'status'  => 0,
            'message' => 'Gagal mengupdate username'
          ];
        }

        $sql = $this->db->last_query();
        simpan_aktifitas(
          $this->managePermission,
          $id_user,
          $keterangan,
          1,
          $sql,
          $status
        );
      }
    }

    echo json_encode($response);
  }


  public function update_password()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();

    $current_password = $this->input->post('current_password');
    $new_password     = $this->input->post('new_password');

    // Validation
    $this->form_validation->set_rules('current_password', 'Password Lama', 'required');
    $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[5]');
    $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');

    if ($this->form_validation->run() == FALSE) {

      $response = [
        'status'  => 0,
        'message' => validation_errors()
      ];
    } else {



      // ambil user
      $user = $this->Profile_model->get_user_by_id($id_user);

      // cek password lama
      if (!password_verify($current_password, $user->password)) {

        $response = [
          'status'  => 0,
          'message' => 'Password lama tidak sesuai'
        ];
      } else {

        /**
         * Generate cost bcrypt otomatis
         */
        $timeTarget = 0.05;
        $cost = 8;

        do {
          $cost++;
          $start = microtime(true);
          password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
          $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        $password_hashed = password_hash(
          $new_password,
          PASSWORD_BCRYPT,
          ['cost' => $cost]
        );

        // update password
        $result = $this->Profile_model->update_password(
          $id_user,
          $password_hashed
        );

        if ($result) {

          $keterangan = "SUKSES, update password user ID: $id_user";
          $status = 1;

          $response = [
            'status'  => 1,
            'message' => 'Password berhasil diupdate'
          ];
        } else {

          $keterangan = "GAGAL, update password user ID: $id_user";
          $status = 0;

          $response = [
            'status'  => 0,
            'message' => 'Gagal mengupdate password'
          ];
        }

        $sql = $this->db->last_query();
        simpan_aktifitas(
          $this->managePermission,
          $id_user,
          $keterangan,
          1,
          $sql,
          $status
        );
      }
    }

    echo json_encode($response);
  }

  public function update_info()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();

    $this->form_validation->set_rules('nm_lengkap', 'Nama Lengkap', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('hp', 'No. HP', 'required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required');
    $this->form_validation->set_rules('kota', 'Kota', 'required');

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 0,
        'message' => validation_errors()
      ];
    } else {
      $this->db->where('email', $this->input->post('email'));
      $this->db->where('id_user !=', $id_user);
      $check = $this->db->get('users')->num_rows();

      if ($check > 0) {
        $response = [
          'status' => 0,
          'message' => 'Email sudah digunakan'
        ];
      } else {
        $data = [
          'nm_lengkap' => $this->input->post('nm_lengkap'),
          'email' => $this->input->post('email'),
          'hp' => $this->input->post('hp'),
          'alamat' => $this->input->post('alamat'),
          'kota' => $this->input->post('kota')
        ];

        $this->db->where('id_user', $id_user);
        $result = $this->db->update('users', $data);

        if ($result) {
          $keterangan = "SUKSES, update informasi profile user ID: $id_user";
          $status = 1;

          $response = [
            'status' => 1,
            'message' => 'Informasi profile berhasil diupdate'
          ];
        } else {
          $keterangan = "GAGAL, update informasi profile user ID: $id_user";
          $status = 0;

          $response = [
            'status' => 0,
            'message' => 'Gagal mengupdate informasi profile'
          ];
        }

        $sql = $this->db->last_query();
        simpan_aktifitas($this->managePermission, $id_user, $keterangan, 1, $sql, $status);
      }
    }

    echo json_encode($response);
  }

  public function delete_photo()
  {
    if (!$this->input->is_ajax_request()) {
      show_404();
      return;
    }

    $id_user = $this->auth->user_id();

    if (!$id_user) {
      echo json_encode([
        'status'  => 0,
        'message' => 'User tidak terautentikasi'
      ]);
      return;
    }

    $this->load->model('Profile_model');

    $user = $this->Profile_model->get_user_photo($id_user);

    if (!$user || empty($user->photo)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'Tidak ada foto yang dapat dihapus'
      ]);
      return;
    }

    // hapus file fisik
    $file_path = './assets/images/users/' . $user->photo;
    if (file_exists($file_path)) {
      unlink($file_path);
    }

    $result = $this->Profile_model->delete_photo($id_user);

    if ($result !== FALSE) {

      $keterangan = "SUKSES, hapus foto profile user ID: $id_user";
      $status = 1;

      $response = [
        'status'         => 1,
        'message'        => 'Foto profile berhasil dihapus',
        'default_photo'  => base_url('assets/images/male-def.png')
      ];

      $sql = $this->db->last_query();
      simpan_aktifitas(
        $this->managePermission,
        $id_user,
        $keterangan,
        1,
        $sql,
        $status
      );
    } else {

      $response = [
        'status'  => 0,
        'message' => 'Gagal menghapus foto profile dari database'
      ];
    }

    echo json_encode($response);
  }
}
