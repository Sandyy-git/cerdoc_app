<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Site extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->check_installation();
        if ($this->config->item('installed') == true) {
            $this->db->reconnect();
        }
        $this->load->model(array('onlineappointment_model', 'prefix_model'));
        $this->load->library('Auth');
        $this->load->library('Enc_lib');
        $this->load->library('mailer');
        $this->load->config('ci-blog');
        $this->load->library('captchalib');
        $this->mailer;


        // $this->config->load("payroll");
        $this->config->load("image_valid");
        $this->load->library('system_notification');
        $this->load->library('datatables');
        $this->load->model(array('conference_model', 'transaction_model', 'casereference_model', 'patient_model', 'notificationsetting_model'));
        $this->load->model('finding_model');
        $this->patient_login_prefix = "pat";
        $this->load->helper('customfield_helper');
        $this->load->helper('custom','form');
    }

    private function check_installation()
    {
        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

    public function login()
    {
        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }

        $data               = array();
        $data['title']      = 'Login';
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $is_captcha         = $this->captchalib->is_captcha('login');
        $data["is_captcha"] = $is_captcha;
        $setting_result        = $this->setting_model->get();
        $data['sch_name']=$setting_result[0]['name'];
        if ($is_captcha) {
            $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_check_captcha');
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            if($is_captcha){
                $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            }
            $this->load->view('admin/login', $data);
        } else {

            $login_post = array(
                'email'    => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );


            $result                = $this->staff_model->checkLogin($login_post);
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];

            if (!empty($result->language_id)) {
                $lang_array = array('lang_id' => $result->language_id, 'language' => $result->language);
            } else {
                $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
            }
            if (!empty($result->language_id)) {
                $lang_data   = $this->language_model->get($result->lang_id);
            }else{
                $lang_data   = $this->language_model->get(4);
            }
            if ($result) {

                $prefix_array = $this->prefix_model->getPrefixArray();

                if ($result->is_active) {
                    $time_format = $setting_result[0]['time_format'];
                    if ($time_format == '12-hour') {
                        $check_time_format = false;
                    } else {
                        $check_time_format = true;
                    }

                    $session_data = array(
                        'id'                     => $result->id,
                        'username'               => $result->name . ' ' . $result->surname,
                        'email'                  => $result->email,
                        'roles'                  => $result->roles,
                        'date_format'            => $setting_result[0]['date_format'],
                        'currency_symbol'        => $setting_result[0]['currency_symbol'],
                        'start_month'            => $setting_result[0]['start_month'],
                        'timezone'               => $setting_result[0]['timezone'],
                        'sch_name'               => $setting_result[0]['name'],
                        'language'               => $lang_array,
                        'is_rtl'                 => $lang_data['is_rtl'],
                        'doctor_restriction'     => $setting_result[0]['doctor_restriction'],
                        'superadmin_restriction' => $setting_result[0]['superadmin_restriction'],
                        'theme'                  => $setting_result[0]['theme'],
                        'base_url'              => $setting_result[0]['base_url'],
                        'folder_path'            => $setting_result[0]['folder_path'],
                        'time_format'            => $check_time_format,
                        'prefix'                 => $prefix_array,
                    );

                    $this->session->set_userdata('hospitaladmin', $session_data);
                    $role      = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);

                    if (isset($_SESSION['redirect_to'])) {
                        redirect($_SESSION['redirect_to']);
                    } else {
                        redirect('admin/admin/dashboard');
                    }
                } else {
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');
                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('admin/login', $data);
            }
        }
    }

    public function logout()
    {
        $admin_session   = $this->session->userdata('hospitaladmin');
        $patient_session = $this->session->userdata('patient');
        $this->auth->logout();
        if ($admin_session) {
            redirect('site/login');
        } else if ($patient_session) {
            redirect('site/mpinlogin');
        } else {
            redirect('site/mpinlogin');
        }
    }

    public function forgotpassword()
    {
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('admin/forgotpassword');
        } else {
            $email  = $this->input->post('email');
            $result = $this->staff_model->getByEmail($email);
            if ($result && $result->email != "") {
                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->id, 'verification_code' => $verification_code);
                $this->staff_model->add($update_record);
                $name           = $result->name;
                $resetPassLink  = base_url('admin/resetpassword') . "/" . $verification_code;
                $send_for       = 'forgot_password';
                $usertype       = 'staff';
                $chk_mail_sms   = $this->customlib->sendMailSMS($send_for);
                $sender_details = array('id' => $result->id, 'email' => $email);
                $body           = $this->forgotPasswordBody($usertype, $sender_details, $resetPassLink, $chk_mail_sms['template']);

                if ($chk_mail_sms['mail']) {
                    $result_new = $this->mailer->send_mail($result->email, $chk_mail_sms['subject'], $body);
                }
                $this->session->set_flashdata('message', $this->lang->line('recover_message'));
                redirect('site/login', 'refresh');
            } else {
                $data = array(
                    'error_message' => $this->lang->line('invalid_email'),
                );
            }
            $this->load->view('admin/forgotpassword', $data);
        }
    }

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null)
    {
        if (!$verification_code) {
            show_404();
        }
        $user = $this->staff_model->getByVerificationCode($verification_code);
        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {
                // finally change the password
                $password      = $this->input->post('password');
                $update_record = array(
                    'id'                => $user->id,
                    'password'          => $this->enc_lib->passHashEnc($password),
                    'verification_code' => "",
                );

                $change = $this->staff_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('reset_message'));
                    redirect('site/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('worning_message'));
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/forgotpassword", 'refresh');
        }
    }

    //reset password - final step for forgotten password
    public function resetpassword($role = null, $verification_code = null)
    {
        if (!$role || !$verification_code) {
            show_404();
        }

        if($role=='refresh'){
            $user = $this->user_model->getUserByUserName($verification_code); //here verifcation code is username
        }else {
            $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);
        }
        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                $data['role']              = $role;
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('resetpassword', $data);
            } else {

                // finally change the password

                $update_record = array(
                    'id'                => $user->user_tbl_id,
                    'password'          => $this->input->post('password'),
                    'verification_code' => "",
                    'is_active' => 'yes',
                );

                $change = $this->user_model->changeStatus($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('reset_message'));
                    redirect('site/userlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('worning_message'));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }
    function alpha_num($input)
    {

        if(strlen($input) > 6){
            $this->form_validation->set_message('alpha_num', "mpin can't be greater than 6 AlphaNumeric characters.");
            return false;
        }
        if(!ctype_alnum($input)){
            $this->form_validation->set_message('alpha_num', "mpin can only be 6 AlphaNumeric characters.");

            return false;
        }

        if($this->user_model->isMpinExist($input)){
            $this->form_validation->set_message('alpha_num', "mpin already associated with other user.please re-try with another.");
            return false;
        }

        return true;
    }
    public function resetmpin($role = null, $verification_code = null)
    {
        // var_dump($role); die;
        // $toCheck_mpin = $this->session->set_userdata('check_mpin_uname', $verification_code);
        
        if (!$role || !$verification_code) {
            show_404();
        }

        if($role=='refresh'){
            $user = $this->user_model->getUserByUserName($verification_code); //here verifcation code is username
        }else {
            $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);
        }
        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('mpin', 'mpin', 'trim|required|callback_alpha_num');
            if ($this->form_validation->run() == false) {
                $data['role']              = $role;
                $data['verification_code'] = $verification_code;
                //render
                //$this->session->set_flashdata('message', $this->lang->line('worning_message'));
                $this->load->view('mpin', $data);
            } else {
                // finally change the password
                $update_record = array(
                    'id'                => $user->user_tbl_id,
                    'mpin'          => $this->input->post('mpin'),
                    'verification_code' => "",
                    'is_active' => 'yes',
                );

                $update_record2 = array(
                    'id'                => $user->user_tbl_id,
                    'is_active' => 'yes',
                );


                $change = $this->user_model->changeStatus($update_record);
                $change_pateint = $this->patient_model->changeStatus($update_record2);
                if ($change && $change_pateint) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('reset_message'));
                    redirect('site/mpinlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line('worning_message'));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
        }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }


    public function ufpassword()
    {
        // $this->form_validation->set_rules('username', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', $this->lang->line('mobile_no'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('user', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('ufpassword');
        } else {
            // $email    = $this->input->post('username');
            $mobileno    = $this->input->post('username');
            $usertype = $this->input->post('user');
            // $result   = $this->user_model->forgotPassword($usertype, $email);
            $result   = $this->user_model->forgotPasswordMobno($usertype, $mobileno);

            if ($result && $result->mobileno != "") {
                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->changeStatus($update_record);
                if ($usertype == "patient") {
                    $name = $result->patient_name;
                } else {
                    $name = $result->patient_name;
                }
                $resetPassLink  = site_url('user/resetpassword') . '/' . $usertype . "/" . $verification_code;
                // var_dump($resetPassLink); die;
                $send_for       = 'forgot_password';
                $chk_mail_sms   = $this->customlib->sendMailSMS($send_for);
                // $sender_details = array('id' => $result->id, 'email' => $email);
                $sender_details = array('id' => $result->id, 'email' => $mobileno);

                $body           = $this->forgotPasswordBody($usertype, $sender_details, $resetPassLink, $chk_mail_sms['template']);

                if ($chk_mail_sms['mail']) {
                    // $result = $this->mailer->send_mail($result->email, $chk_mail_sms['subject'], $body);
                    $result = $this->smsgateway->sendSMS($result->mobileno, $body, $body);

                }
                $this->session->set_flashdata('message', $this->lang->line('recover_message'));
                redirect('site/userlogin', 'refresh');
            } else {
                $data = array(
                    'error_message' => $this->lang->line('invalid_user_email'),
                );
            }
            $this->load->view('ufpassword', $data);
        }
    }

    public function patsignup(){
        // $this->load->view('layout/header');
        $this->load->view('patientsignup');
        // $this->load->view('layout/footer');

    }
    public function mpinforgot()
    {
        $this->form_validation->set_rules('username', $this->lang->line('mobile_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('mpinforgot');
        } else {
            // $email    = $this->input->post('username');
            $mobileno    = $this->input->post('username');

            $usertype = $this->input->post('user');
            // $result   = $this->user_model->forgotPassword($usertype, $email);
            $result   = $this->user_model->forgotPasswordMobno($usertype, $mobileno);

            if ($result && $result->mobileno != "") {
                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->changeStatus($update_record);
                if ($usertype == "patient") {
                    $name = $result->patient_name;
                } else {
                    $name = $result->patient_name;
                }
                $resetPassLink  = site_url('site/resetmpin') . '/' . $usertype . "/" . $verification_code;
                $send_for       = 'forgot_password';
                $chk_mail_sms   = $this->customlib->sendMailSMS($send_for);
                $sender_details = array('id' => $result->id, 'mobileno' => $mobileno);
                $body           = $this->forgotPasswordBody($usertype, $sender_details, $resetPassLink, $chk_mail_sms['template']);
                // var_dump($chk_mail_sms['mail']); die;

                if ($chk_mail_sms['mail']) {
                    // $result = $this->mailer->send_mail($result->email, $chk_mail_sms['subject'], $body);
                    $result = $this->smsgateway->sendSMS($result->mobileno, $body, $body);
                }
                $this->session->set_flashdata('message', $this->lang->line('recover_message'));
                redirect('site/mpinlogin', 'refresh');
            } else {
                $data = array(
                    'error_message' => $this->lang->line('invalid_user_email'),
                );
            }
            $data = array(
                'error_message' => "Reset mpin instruction has been sent to your email.",
            );
            $this->load->view('mpinforgot', $data);
        }
    }

    public function forgotPasswordBody($usertype, $sender_details, $resetPassLink, $template)
    {
        if ($usertype == "patient") {
            $patient = $this->patient_model->patientProfileDetails($sender_details['id']);
            $sender_details['resetpasslink'] = $resetPassLink;
            $sender_details['display_name']  = $patient['patient_name'];
        }
        if ($usertype == "staff") {
            $staff = $this->staff_model->get($sender_details['id']);
            $sender_details['resetpasslink'] = $resetPassLink;
            $sender_details['display_name']  = $staff['name'] . " " . $staff['surname'];
        }

        foreach ($sender_details as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public function getpatientDetails()
    {
        $id     = $this->input->post("patient_id");
        $result = $this->appointment_model->getpatientDetails($id);
        $array  = array('status' => 0, 'result' => array());

        if ($result) {
            $array = array('status' => 1, 'result' => $result);
        }
        echo json_encode($array);
    }

    public function getdoctor()
    {
        $spec_id       = $this->input->post('id');
        $active        = $this->input->post('active');
        $result        = $this->staff_model->getdoctorbyspecilist($spec_id);
        $doctors_array = array();
        foreach ($result as $doctor) {
            $doctor_array = array(
                "id"   => $doctor['id'],
                "name" => composeStaffNameByString($doctor['name'], $doctor['surname'], $doctor['employee_id']),
            );
            array_push($doctors_array, $doctor_array);
        }
        echo json_encode($doctors_array);
    }

    public function userlogin()
    {
        $patientpanel = $this->customlib->patientpanel();
        $setting_result        = $this->setting_model->get();
        if ($patientpanel == 'disabled') {
            redirect('site/login');
        }

        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data           = array();
        $data['title']  = 'Login';
        $notice_content = $this->config->item('ci_front_notice_content');
        $notices        = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['name'] = $setting_result[0]['name'];

        $data['notice'] = $notices;
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        if ($is_captcha) {
            $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_check_captcha');
        }

        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            if($is_captcha){
                $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            }
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $login_details         = $this->user_model->checkLogin($login_post);
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes" && $user->is_active != 0) {
                    if ($user->role == "patient") {
                        $result = $this->user_model->read_user_information($user->id);

                    }

                    if ($result[0]->lang_id != 0) {
                        $lang_array = array('lang_id' => $result['0']->lang_id, 'language' => $result['0']->language);
                    } else {
                        $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                    }

                    if (!empty($result[0]->lang_id)) {
                        $lang_data   = $this->language_model->get($result[0]->lang_id);
                    }else{
                        $lang_data   = $this->language_model->get(4);
                    }

                    $prefix_array = $this->prefix_model->getPrefixArray();
                    if ($result != false) {

                        if ($result[0]->role == "patient") {

                            $time_format = $setting_result[0]['time_format'];
                            if ($time_format == '12-hour') {
                                $check_time_format = false;
                            } else {
                                $check_time_format = true;
                            }

                            $session_data = array(
                                'id'              => $result[0]->id,
                                'patient_id'      => $result[0]->user_id,
                                'patient_type'    => $result[0]->patient_type,
                                'role'            => $result[0]->role,
                                'username'        => $result[0]->username,
                                'name'            => $result[0]->patient_name,
                                'gender'          => $result[0]->gender,
                                'email'           => $result[0]->email,
                                'mobileno'        => $result[0]->mobileno,
                                'date_format'     => $setting_result[0]['date_format'],
                                'currency_symbol' => $setting_result[0]['currency_symbol'],
                                'timezone'        => $setting_result[0]['timezone'],
                                'sch_name'        => $setting_result[0]['name'],
                                'language'        => array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']),
                                'is_rtl'          => $lang_data['is_rtl'],
                                'theme'           => $setting_result[0]['theme'],
                                'time_format'     => $check_time_format,
                                'image'           => $result[0]->image,
                                'prefix'          => $prefix_array,
                            );

                            $this->session->set_userdata('patient', $session_data);
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                            redirect('patient/dashboard');
                        }
                    } else {
                        $data['error_message'] = $this->lang->line('account_suspended');
                        $this->load->view('userlogin', $data);
                    }
                } else {
                    $check_mpinset = $this->user_model->checkmpinset($this->input->post('username'));
                    if($check_mpinset->user_mpin != 0){
                     $this->load->view('mpinlogin');
                    }else{
                    redirect('user/mpin/' . 'refresh' . '/' . $user->username, 'refresh');
                    }
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('userlogin', $data);
            }
        }
    }

    public function mpinlogin()
    {
        $patientpanel = $this->customlib->patientpanel();
        $setting_result        = $this->setting_model->get();
        if ($patientpanel == 'disabled') {
            redirect('site/login');
        }

        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        $data           = array();
        $data['title']  = 'Login';
        $notice_content = $this->config->item('ci_front_notice_content');
        $notices        = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['name'] = $setting_result[0]['name'];

        $data['notice'] = $notices;
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        if ($is_captcha) {
            $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_check_captcha');
        }

        $this->form_validation->set_rules('mpin', 'mpin', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            if($is_captcha){
                $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            }
            $this->load->view('mpinlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'mpin' => $this->input->post('mpin'),
            );
            $login_details         = $this->user_model->checkMpinLogin($login_post);
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];
                if ($user->is_active == "yes") {
                    if ($user->role == "patient") {
                        $result = $this->user_model->read_user_information($user->id);

                    }

                    if ($result[0]->lang_id != 0) {
                        $lang_array = array('lang_id' => $result['0']->lang_id, 'language' => $result['0']->language);
                    } else {
                        $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                    }

                    if (!empty($result[0]->lang_id)) {
                        $lang_data   = $this->language_model->get($result[0]->lang_id);
                    }else{
                        $lang_data   = $this->language_model->get(4);
                    }

                    $prefix_array = $this->prefix_model->getPrefixArray();
                    if ($result != false) {

                        if ($result[0]->role == "patient") {

                            $time_format = $setting_result[0]['time_format'];
                            if ($time_format == '12-hour') {
                                $check_time_format = false;
                            } else {
                                $check_time_format = true;
                            }

                            $session_data = array(
                                'id'              => $result[0]->id,
                                'patient_id'      => $result[0]->user_id,
                                'patient_type'    => $result[0]->patient_type,
                                'role'            => $result[0]->role,
                                'username'        => $result[0]->username,
                                'name'            => $result[0]->patient_name,
                                'gender'          => $result[0]->gender,
                                'email'           => $result[0]->email,
                                'mobileno'        => $result[0]->mobileno,
                                'mpin'           =>  $result[0]->mpin,
                                'date_format'     => $setting_result[0]['date_format'],
                                'currency_symbol' => $setting_result[0]['currency_symbol'],
                                'timezone'        => $setting_result[0]['timezone'],
                                'sch_name'        => $setting_result[0]['name'],
                                'language'        => array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']),
                                'is_rtl'          => $lang_data['is_rtl'],
                                'theme'           => $setting_result[0]['theme'],
                                'time_format'     => $check_time_format,
                                'image'           => $result[0]->image,
                                'prefix'          => $prefix_array,
                            );

                            $this->session->set_userdata('patient', $session_data);
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                            redirect('patient/dashboard');
                        }
                    } else {
                        $data['error_message'] = $this->lang->line('account_suspended');
                        $this->load->view('mpinlogin', $data);
                    }
                } else {

                    redirect('user/mpin/' . 'refresh' . '/' . $user->username, 'refresh');
                }
            } else {
                $data['error_message'] = 'Invalid Mpin';
                $this->load->view('mpinlogin', $data);
            }
        }
    }
    public function check_captcha($captcha)
    {
        if ($captcha != $this->session->userdata('captchaCode')):
            $this->form_validation->set_message('check_captcha', $this->lang->line("incorrect_captcha"));
            return false;
        else:
            return true;
        endif;
    }

    public function refreshCaptcha()
    {
        $captcha = $this->captchalib->generate_captcha();
        echo $captcha['image'];
    }

    public function getDoctorShift()
    {
        $shift_data = array();
        $doctor     = $this->input->post("doctor");
        $shift      = $this->onlineappointment_model->getShiftByDoctor($doctor);
        $days       = $this->customlib->getDaysname();

        foreach ($days as $day) {
            $i = 0;
            foreach ($shift as $shift_key => $shift_value) {
                if ($day == $shift_value->day) {
                    $shift_data[$day][$i]["start_time"] = $shift_value->start_time;
                    $shift_data[$day][$i]["end_time"]   = $shift_value->end_time;
                    $i++;
                }
            }
        }
        echo json_encode($shift_data);
    }

    public function getShift()
    {
        $dates        = $this->input->post("date");
        $date         = $this->customlib->dateFormatToYYYYMMDD($dates);
        $doctor       = $this->input->post("doctor");
        $global_shift = $this->input->post("global_shift");
        $doctor_clinics = $this->input->post("doctor_clinics");

        $day          = date("l", strtotime($date));
        //ORIGINAL
        // $shift        = $this->onlineappointment_model->getShiftdata($doctor, $day, $global_shift);
        $shift        = $this->onlineappointment_model->getShiftdataPatSide($doctor, $day, $global_shift,$doctor_clinics);
        echo json_encode($shift);
    }

    public function getSlotByShift()
    {
        $data           = array();
        $data["result"] = array();
        $shift          = $this->input->post("shift");
        $doctor_id      = $this->input->post("doctor");
        $global_shift   = $this->input->post("global_shift");
        $date           = $this->customlib->dateFormatToYYYYMMDD($this->input->post("date"));
        $doctor_clinics_id   = $this->input->post("doctor_clinics_id");
        $appointments   = $this->onlineappointment_model->getAppointments($doctor_id, $shift, $date);
        $array_of_time  = $this->customlib->getSlotByDoctorShift($doctor_id, $shift,$doctor_clinics_id);
        // var_dump($array_of_time); die;
        $this->load->model("charge_model");
        $class = "";
        foreach ($array_of_time as $time) {
            if (!empty($appointments)) {
                foreach ($appointments as $appointment) {
                    if ($appointment->time == date("H:i:s", strtotime($time))) {
                        $class  = "row badge badge-pill badge-danger-soft";
                        $filled = "filled";
                        break;
                    } else {
                        $class  = "row badge badge-pill badge-success-soft";
                        $filled = "";
                    }
                }

                array_push($data["result"], array("time" => $this->customlib->getHospitalTime_FormatFrontCMS($time), "class" => $class, "filled" => $filled));
            } else {
                array_push($data["result"], array("time" => $this->customlib->getHospitalTime_FormatFrontCMS($time), "class" => "row badge badge-pill badge-success-soft"));
            }
        }
        $doctor_data               = $this->staff_model->getProfile($doctor_id);
        $data["doctor_name"]       = $doctor_data["name"] . " " . $doctor_data["surname"];
        $data["doctor_speciality"] = $this->staff_model->getStaffSpeciality($doctor_id);
        $shift_details             = $this->onlineappointment_model->getShiftDetails($doctor_id,$doctor_clinics_id);
        $charge_details            = $this->charge_model->getChargeDetailsById($shift_details['charge_id']);
        $currency_symbol           = $this->setting_model->get()[0]["currency_symbol"];
        $data["fees"]              = isset($charge_details->standard_charge) ? $currency_symbol . $charge_details->standard_charge : "";
        $data["duration"]          = $shift_details["consult_duration"];
        if (!empty($doctor_data['image'])) {
            $data['image'] = base_url("uploads/staff_images/" . $doctor_data['image']);
        } else {
            $data['image'] = base_url("uploads/staff_images/no_image.png");
        }

        echo json_encode($data);
    }

    public function getGlobalShift($id)
    {
        $shift = $this->onlineappointment_model->globalShift();
        if ($status == false) {
            echo json_encode($shift);
        }
    }

    public function doctorShiftById()
    {
        $doctor_id = $this->input->post("doctor_id");
        $shift     = $this->onlineappointment_model->doctorShiftById($doctor_id);
        echo json_encode($shift);
    }

    public function doctorclinicbyid()
    {
        $doctor_id = $this->input->post("doctor_id");
        $shift     = $this->onlineappointment_model->doctorClinicsById($doctor_id);
        echo json_encode($shift);
    }

    public function autoSignin(){
       
        $this->load->view("layout/patient/header");
        $this->load->view("autosignup");
        $this->load->view("layout/patient/footer");
    }

    public function getpatientage()
    {
        $birth_date = $_REQUEST['birth_date'];
        $dob        = $this->customlib->dateFormatToYYYYMMDD($birth_date);
        $agr_array  = array();
        if (!empty($dob)) {
            $birthdate          = new DateTime($dob);
            $today              = new DateTime('today');
            $age                = "";
            $agr_array['year']  = $birthdate->diff($today)->y;
            $agr_array['month'] = $birthdate->diff($today)->m;
            $agr_array['day']   = $birthdate->diff($today)->d;

        }
        echo json_encode($agr_array);
    }


    /*
This Function is used to Add Patient
 */

public function addpatient()
{
    // var_dump($_FILES["file"]); die;
    $custom_fields = $this->customfield_model->getByBelong('patient');

    if ((int) $_POST['age']['day'] == 0 && (int) $_POST['age']['month'] == 0 && (int) $_POST['age']['year'] == 0) {
        $this->form_validation->set_rules('age', $this->lang->line('age'), 'trim|required|xss_clean|');
    }

    foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
        if ($custom_fields_value['validation']) {
            $custom_fields_id   = $custom_fields_value['id'];
            $custom_fields_name = $custom_fields_value['name'];
            $this->form_validation->set_rules("custom_fields[patient][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
        }
    }

    $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|xss_clean');
    $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'trim|numeric|xss_clean|required');
    $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
    $this->form_validation->set_rules('age[year]', $this->lang->line('year'), 'trim|required|xss_clean|numeric');
    $this->form_validation->set_rules('age[month]', $this->lang->line('month'), 'trim|required|xss_clean|numeric');
    $this->form_validation->set_rules('age[day]', $this->lang->line('day'), 'trim|required|xss_clean|numeric');
    $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

    if ($this->form_validation->run() == false) {

        $msg = array(
            'name'       => form_error('name'),
            'age'        => form_error('age'),
            'age[year]'  => form_error('age[year]'),
            'age[month]' => form_error('age[month]'),
            'age[day]'   => form_error('age[day]'),
            // 'email'      => form_error('email'),
            'mobileno'   => form_error('mobileno'),
            'file'       => form_error('file'),
        );

        // if (!empty($custom_fields)) {
        //     foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
        //         if ($custom_fields_value['validation']) {
        //             $custom_fields_id                                                = $custom_fields_value['id'];
        //             $custom_fields_name                                              = $custom_fields_value['name'];
        //             $error_msg2["custom_fields[patient][" . $custom_fields_id . "]"] = form_error("custom_fields[patient][" . $custom_fields_id . "]");
        //         }
        //     }
        // }
        // if (!empty($error_msg2)) {
        //     $error_msg = array_merge($msg, $error_msg2);
        // } else {
        //     $error_msg = $msg;
        // }

        $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        echo json_encode($array);

    } else {
       
        $dobdate = $this->input->post('dob');
        if ($dobdate == "") {
            $dob = null;
        } else {
            $dob = $this->customlib->dateFormatToYYYYMMDD($dobdate);
        }

        $email    = $this->input->post('email');
        $mobileno = $this->input->post('mobileno');

        if (($mobileno != "") && ($email != "")) {
            $result = $this->patient_model->checkmobileemail($mobileno, $email);

            if ($result == 1) {
                $msg   = array('numberemail' => $this->lang->line('mobile_number_and_email_already_exist'));
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
                // echo json_encode($array);
            }
        }

        // if ($mobileno != "") {
        //     $result = $this->patient_model->checkmobilenumber($mobileno);

        //     if ($result == 1) {
        //         $msg   = array('number' => $this->lang->line('mobile_number_already_exist'));
        //         $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        //         // echo json_encode($array);
        //     }
        // }

        // if ($email != "") {
        //     $result = $this->patient_model->checkemail($email);

        //     if ($result == 1) {
        //         $msg   = array('email' => $this->lang->line('email_already_exist'));
        //         $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        //         // echo json_encode($array);
        //     }
        // }

        // $validity = $this->input->post("validity");
        // if (!empty($validity)) {
        //     $validity = $this->customlib->dateFormatToYYYYMMDD($validity);
        // } else {
        //     $validity = null;
        // }
        // $blood_bank_product_id = $this->input->post('blood_group');
        // if (!empty($blood_bank_product_id)) {
        //     $blood_group = $blood_bank_product_id;
        // } else {
        //     $blood_group = null;
        // }
        if($result == 0){
        $patient_data = array(
            'patient_name'          => $this->input->post('name'),
            'mobileno'              => $this->input->post('mobileno'),
            'email'                 => $this->input->post('email'),
            'gender'                => $this->input->post('gender'),
            'dob'                   => $dob,
            'age'                   => $this->input->post('age[year]'),
            'month'                 => $this->input->post('age[month]'),
            'day'                   => $this->input->post('age[day]'),
            'is_active'             => 'yes',
            'latitude'              => $this->input->post('latitude'),
            'longitude'              => $this->input->post('longitude')
        );
        
        $custom_field_post  = $this->input->post("custom_fields[patient]");
        $custom_value_array = array();
        if (!empty($custom_field_post)) {
            foreach ($custom_field_post as $key => $value) {
                $check_field_type = $this->input->post("custom_fields[patient][" . $key . "]");
                $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                $array_custom     = array(
                    'belong_table_id' => 0,
                    'custom_field_id' => $key,
                    'field_value'     => $field_value,
                );
                $custom_value_array[] = $array_custom;
            }
        }
        $insert_id = $this->patient_model->add_patient($patient_data);

        if (!empty($custom_value_array)) {
            $this->customfield_model->insertRecord($custom_value_array, $insert_id);
        }
        if ($this->session->has_userdata("appointment_id")) {
            $appointment_id = $this->session->userdata("appointment_id");
            $updateData     = array('id' => $appointment_id, 'patient_id' => $insert_id);
            $this->appointment_model->update($updateData);
            $this->session->unset_userdata('appointment_id');
        }
        $user_password      = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
        $data_patient_login = array(
            'username' => $this->patient_login_prefix . $insert_id,
            'password' => $user_password,
            'user_id'  => $insert_id,
            'role'     => 'patient',
        );

        $user_insert_id = $this->user_model->add($data_patient_login);
        if($insert_id && $user_insert_id){
            $array = array('status' => 'success', 'error' => '', 'message' => 'patient registration success');
        }

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $fileInfo = pathinfo($_FILES["file"]["name"]);
            $img_name = $insert_id . '.' . $fileInfo['extension'];
            move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
            $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/' . $img_name);
        } else {
            $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/no_image.png');
        }
        $this->patient_model->add($data_img);

        // $sender_details = array('id' => $insert_id, 'credential_for' => 'patient', 'username' => $this->patient_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'));

        // $this->mailsmsconf->mailsms('login_credential', $sender_details);

    }
    echo json_encode($array);

    }

}



/*
This Function is used to File Validation For Image
*/

public function handle_upload()
{

    $image_validate = $this->config->item('image_validate');

    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

        $file_type         = $_FILES["file"]['type'];
        $file_size         = $_FILES["file"]["size"];
        $file_name         = $_FILES["file"]["name"];
        $allowed_extension = $image_validate['allowed_extension'];
        // var_Dump($allowed_extension); die;
        $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_mime_type = $image_validate['allowed_mime_type'];
        if ($files = @getimagesize($_FILES['file']['tmp_name'])) {

            if (!in_array($files['mime'], $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array(strtolower($ext), $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_extension_not_allowed'));
                return false;
            }
            if ($file_size > $image_validate['upload_size']) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_not_allowed'));
            return false;
        }

        return true;
    }
    return true;
}


    

}
?>
