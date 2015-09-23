<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Admin_user extends REST_Controller {


    function __construct() {
        parent::__construct();
        $this->load->model('admin_user_model','admin_user');
    }

	/**
     * 获取用户信息
     * 根据ID获取单条用户信息、获取列表
     * @access public
     * @return string
     */
    public function admin_user_get()
    {
        $user_id = $this->get('user_id');
		if($user_id)
		{
			$data = $this->admin_user->get_one(array('user_id'=>$user_id));
			unset($data['password']);
			$result = array('success' => TRUE, 'data' => $data);
		}else
		{
			$start = $this->get('start') ? $this->get('start') : 0;
			$limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
			$search = $this->get('search');
            $department_id = $this->get('department_id');
			$where = array();
			$records = array();
			if(!empty($search)){
				$where['like'] = array('username'=>$search,'realname'=>$search);
			}
            $department_id = intval($department_id);
            if($department_id)
                $where['department_id'] = $department_id;
			$admin_user = $this->admin_user->get_all($where,'*','user_id','desc', $limit,$start);
			$total = $this->admin_user->get_count($where);
			if ($admin_user !== NULL)
			{
				foreach($admin_user as $q)
				{
					$records[] = array(
						'user_id' => $q['user_id'],
						'username' => $q['username'],
						'realname' => $q['realname'],
						'new7_code' => $q['new7_code'],
						'sex' => $q['sex'],
						'mobile' => $q['mobile'],
						'weixin_no' => $q['weixin_no'],
						'email' => $q['email'],
						'entry_time' => $q['entry_time'],
                        'department_id' => $q['department_id'],
                        'grade_id' => $q['grade_id'],
						'is_deleted' => $q['is_deleted']
						);     
				}
			}
			$result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
		}
		$this->response($result, REST_Controller::HTTP_OK);
    }

    /**
     * 保存用户信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function admin_user_post()
    {
        $this->load->library('form_validation');
        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $realname = $this->input->post('realname');
        $department_id = $this->input->post('department_id');
        $grade_id = $this->input->post('grade_id');
        $sex = $this->input->post('sex');
        $mobile = $this->input->post('mobile');
        $weixin_no = $this->input->post('weixin_no');
        $email = $this->input->post('email');
        $new7_code = $this->input->post('new7_code');
        $entry_time = $this->input->post('entry_time');
        $is_deleted = $this->input->post('is_deleted');
        $quarters = $this->input->post('quarters');

        $data = array('username' => $username,
					'realname' => $realname,
                    'department_id' => $department_id,
                    'grade_id' => $grade_id,
					'sex' => intval($sex),
					'mobile' => $mobile,
					'weixin_no' => $weixin_no,
					'email' => $email,
					'new7_code' => $new7_code,
					'entry_time' => $entry_time,
					'is_deleted' => intval($is_deleted)
						 );
        if($password)
        	$data['password'] = md5($password);
        $error = FALSE;
        $feedback = array();
    	$username_error_message = array(
		        'required'  => '%s不能为空.',
		        'min_length' => '%s长度必须大于3位',
		        'is_unique' => '您输入的%s系统中已存在.'
		        );
        if(is_numeric($user_id))
        {
        	$user_info = $this->admin_user->get_one(array('user_id'=>$user_id));
        	$username_rule = 'trim|required|min_length[4]'.($user_info['username'] != $username?'|is_unique[admin_user.username]':'');
        }else{
        	$username_rule = 'trim|required|min_length[4]|is_unique[admin_user.username]';
        }
        $this->form_validation->set_rules('username', '用户账号', $username_rule, $username_error_message);
        $this->form_validation->set_rules('realname', '真实姓名', 'trim|required',array('required'  => '%s不能为空.'));
        $this->form_validation->set_rules('mobile', '手机号', 'trim|required',array('required'  => '%s不能为空.'));
        $this->form_validation->set_rules('email', '邮箱', 'trim|valid_email',array('valid_email'  => '请输入正确的%s.'));

        if ($this->form_validation->run() == false) 
        {
            $error = TRUE;
            $feedback[] = validation_errors();
        }
        //save customer data
        if ($error === FALSE)
        {
            if ($insert_id = $this->admin_user->save((is_numeric($user_id) ? array('user_id'=>$user_id) : NULL),$data))
            {
		        //存储用户
		        $quarters = json_decode($quarters,TRUE);
		        if($quarters)
		        {
		        	//先删除再创建
		        	$user_id = is_numeric($user_id) ? $user_id : $insert_id;
		        	$this->load->model('User_to_quarters_model','user_to_quarters');
		        	$this->user_to_quarters->delete(array('user_id'=>$user_id));
		        	foreach($quarters as $quarters_id)
		        	{
		        		$this->user_to_quarters->insert(array('user_id'=>$user_id,'quarters_id'=>$quarters_id));
		        	}
		        }
                $this->admin_logs->set('编辑用户：'.$username);
                $response = array('success' => TRUE, 'feedback' => '成功： 操作成功');
            }
            else
            {
                $response = array('success' => FALSE, 'feedback' => '错误： 操作失败');
            }
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败' . '<br />' . implode('<br />', $feedback));
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }
    /**
     * 修改用户状态
     *
     * @access public
     * @return string
     */
    public function admin_user_put()
    {
        $flag = $this->put('flag');
        $user_id = $this->put('user_id');

        if ($this->admin_user->update(array('is_deleted'=>$flag),array('user_id'=>$user_id)))
        {
            $this->admin_logs->set('修改用户状态：用户ID为'.$user_id."，修改后状态为".$flag);
            $response = array('success' => TRUE, 'feedback' => '成功： 操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * 删除用户
     *
     * @access public
     * @param $user_id
     * @return string
     */
    public function admin_user_delete($user_id = '')
    {
        $user_id = $this->delete('user_id');
		//验证是否可删。。
		
        if ($this->admin_user->delete(array('user_id'=>$user_id)))
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

		$this->response($response, REST_Controller::HTTP_OK);
    }
}
