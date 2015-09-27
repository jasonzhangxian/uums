<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class New7_system extends REST_Controller {


  function __construct() 
  {
      parent::__construct();
      $this->load->model('new7_system_model','new7_system');
  }

 /**
   * 获取系统信息
   * 根据ID获取单条系统信息、获取列表
   * @access public
   * @return string
   */
  public function new7_system_get()
  {
    $action = $this->get('action');
    if(!empty($action))
    {
      $result = $this->{'new7_system_'.$action}();
    }
    else
    {
      $system_id = $this->get('system_id');

      $this->load->model('admin_user_model','admin_user');
      if($system_id)
      {
        $data = $this->new7_system->get_one(array('system_id'=>$system_id));
        $admin_info = $this->admin_user->get_one(array('user_id'=>$data['update_user_id']),'realname');
        $data['update_user_id'] = $admin_info['realname'];
        $result = array('success' => TRUE, 'data' => $data);
      }else
      {
        $start = $this->get('start') ? $this->get('start') : 0;
        $limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
        $search = $this->get('search');
        $where = array();
        $records = array();
        if(!empty($search))
        {
            $where['like'] = array('system_name'=>$search);
        }
        $new7_system = $this->new7_system->get_all($where,'*','system_id','desc', $limit,$start);

        $total = $this->new7_system->get_count($where);
        if($new7_system!==NULL)
        {
          foreach($new7_system as $n)
          {
            $admin_info = $this->admin_user->get_one(array('user_id'=>$n['update_user_id']),'realname');
            $records[] = array(
                            'system_id' => $n['system_id'],
                            'system_name' => $n['system_name'],
                            'sys_code'=>$n['sys_code'],
                            'system_url'=>$n['system_url'],
                            'update_time' => date("Y-m-d H:i",$n['update_time']),
                            'update_user_id' => $admin_info['realname'],
                            'secret_key' => $n['secret_key']
                          );     
          }
        }
        $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
      }
    }
    $this->response($result, REST_Controller::HTTP_OK);
  }

  /**
   * 保存系统信息（添加与修改）
   *
   * @access public
   * @return string
   */
  public function new7_system_post()
  {
    $this->load->library('form_validation');
    $system_id = $this->post('system_id');
    $system_name = $this->post('system_name');
    $sys_code = $this->post('sys_code');
    $system_url = $this->post('system_url');
    $update_time = time();
    $update_user_id = $this->admin->get_id();
    $secret_key = $this->post('secret_key');
    
    $error = FALSE;
    $feedback = array();
    
    $data = array('system_name'=>$system_name,
                  'sys_code'=>$sys_code,
                  'system_url'=>$system_url,
                  'update_time'=>$update_time,
                  'update_user_id'=>$update_user_id,
                  'secret_key'=>$secret_key
                 );
    $sys_code_error_message = array(
            'required'  => '%s不能为空.',
            'min_length' => '%s长度必须大于1位',
            'is_unique' => '您输入的%s系统中已存在.'
            );
    if(is_numeric($system_id))
    {
      $system_info = $this->new7_system->get_one(array('system_id'=>$system_id));
      $sys_code_rule = 'trim|required|min_length[2]'.($system_info['sys_code'] != $sys_code?'|is_unique[new7_system.sys_code]':'');
    }
    else
    {
      $sys_code_rule = 'trim|required|min_length[2]|is_unique[new7_system.sys_code]';
    }
    $this->form_validation->set_rules('system_name', '系统名称', 'trim|required|min_length[2]',array('required'  => '%s不能为空.','min_length' => '%s长度必须大于1位'));
    $this->form_validation->set_rules('sys_code', '系统代码', $sys_code_rule, $sys_code_error_message);
    $this->form_validation->set_rules('system_url', '系统地址', 'trim|required',array('required'  => '%s不能为空.'));
    $this->form_validation->set_rules('secret_key', '密钥', 'trim|required',array('required'  => '%s不能为空.'));

    if ($this->form_validation->run() == false) 
    {
        $error = TRUE;
        $feedback[] = validation_errors();
    }
    if ($error === FALSE)
    {
        if($this->new7_system->save((is_numeric($system_id) ? array('system_id'=>$system_id) : NULL),$data))
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功');
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
   * 删除系统
   *
   * @access public
   * @param $id
   * @return string
   */
  public function new7_system_delete()
  {
    $system_id = $this->delete('system_id');
    if ($this->new7_system->delete(array('system_id'=>$system_id)))
    {
        $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
    }
    else
    {
        $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
    }
    $this->response($response, REST_Controller::HTTP_OK);
  }
  /**
   * 导出系统信息
   *
   * @access public
   * @return string
   */
  public function new7_system_export()
  {
    $this->load->library('Excel');
    $this->excel->setExcelTitle('test');
    $this->excel->setExcelFields(array('system_id'=>'系统编号','system_name'=>'系统名称','sys_code'=>'系统编码','system_url'=>'系统主页','update_time'=>'修改时间','update_user_id'=>'修改人','secret_key'=>'密钥'));

    $search = $this->get('search');
    $where = array();
    $records = array();
    if(!empty($search))
    {
        $where['like'] = array('system_name'=>$search);
    }
    $new7_system = $this->new7_system->get_all($where,'*','system_id','desc');

    if($new7_system!==NULL)
    {
        foreach($new7_system as $n)
        {
            $records[] = array(
                            'system_id' => $n['system_id'],
                            'system_name' => $n['system_name'],
                            'sys_code'=>$n['sys_code'],
                            'system_url'=>$n['system_url'],
                            'update_time' => $n['update_time'],
                            'update_user_id' => $n['update_user_id'],
                            'secret_key' => $n['secret_key']
                          );     
        }
    }
    $file_url = $this->excel->extraExcel($records);
    return array('success' => TRUE, 'feedback' => $file_url);
  }
}
