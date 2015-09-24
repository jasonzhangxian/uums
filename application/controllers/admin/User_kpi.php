<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class User_kpi extends REST_Controller {


  function __construct() 
  {
    parent::__construct();
    $this->load->model('user_kpi_model','user_kpi');
  }

  /**
   * 获取用户信息
   * 根据ID获取单条用户信息、获取列表
   * @access public
   * @return string
   */
  public function user_kpi_get()
  {
    $action = $this->get('action');
    if(!empty($action))
    {
        $result = $this->{'user_kpi_'.$action}();
      }else{
      $user_id = $this->get('user_id');
      $this->load->model('admin_user_model','admin_user');
      $this->load->model('department_model','department');
      $this->load->model('grade_model','grade');
      if($user_id)
      {
        $data = $this->user_kpi->get_one(array('user_id'=>$user_id));
        if(!empty($data))
        {
          $user_info = $this->admin_user->get_one(array('user_id'=>$data['user_id']));
          $data['realname'] = $user_info['realname'];
        }
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
          $where['like'] = array('admin_user.username'=>$search,'admin_user.realname'=>$search);
        }
        $department_id = intval($department_id);
        if($department_id){
          $all_children = $this->department->get_all_children($department_id);
          $all_children[] = $department_id;
          $where['in admin_user.department_id'] = $all_children;
        }
        $user_kpi = $this->user_kpi->get_all_info($where,'*','admin_user.user_id','desc', $limit,$start);
        if ($user_kpi !== NULL)
        {
          $total = $user_kpi['total'];
          foreach($user_kpi['data'] as $q)
          {
            $records[] = array(
            'user_id' => $q['user_id'],
            'realname' => $q['realname'],
            'department_id' => $q['department_id'],
            'department_name' => $q['department_name'],
            'grade_id' => $q['grade_id'],
            'grade_name' => $q['grade_name'],
            'month' => date('Y-m',strtotime($q['month'])),
            'kpi1' => $q['kpi1'],
            'kpi2' => $q['kpi2'],
            'salary' => $q['salary'],
            'performance_pay' => $q['performance_pay']
            );     
          }
        }
        $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
      }
    }
    $this->response($result, REST_Controller::HTTP_OK);
  }

  /**
  * 保存用户信息（添加与修改）
  *
  * @access public
  * @return string
  */
  public function user_kpi_post()
  {
    $user_id = $this->input->post('user_id');
    $kpi1 = $this->input->post('kpi1');
    $kpi2 = $this->input->post('kpi2');
    $salary = $this->input->post('salary');
    $performance_pay = $this->input->post('performance_pay');
    $month = $this->input->post('month');

    $data = array('user_id'=>$user_id,
                  'kpi1' => $kpi1,
                  'kpi2' => $kpi2,
                  'salary' => $salary,
                  'performance_pay' => $performance_pay,
                  'month' => $month."-01"
    );
    $error = FALSE;
    $feedback = array();
    //save customer data
    if ($error === FALSE)
    {
      $this->user_kpi->replace($data);
      
      $response = array('success' => TRUE, 'feedback' => '成功： 操作成功');
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => '错误： 操作失败' . '<br />' . implode('<br />', $feedback));
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
  public function user_kpi_delete($user_id = '')
  {
    $user_id = $this->delete('user_id');
    //验证是否可删。。

    if ($this->user_kpi->delete(array('user_id'=>$user_id)))
    {
      $response = array('success' => TRUE, 'feedback' => '成功： 操作成功。');
    }
    else
    {
      $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
    }

    $this->response($response, REST_Controller::HTTP_OK);
  }

  public function user_kpi_export()
  {
    $this->load->library('Excel');
    $this->excel->setExcelTitle('test');
    $this->excel->setExcelFields(array('realname'=>'用户姓名','department_name'=>'所属部门','grade_name'=>'职级','month'=>'月份','kpi1'=>'KPI1','kpi2'=>'KPI2','salary'=>'基本工资','performance_pay'=>'绩效工资'));

    $search = $this->get('search');
    $department_id = $this->get('department_id');
    $where = array();
    $records = array();
    if(!empty($search)){
      $where['like'] = array('admin_user.username'=>$search,'admin_user.realname'=>$search);
    }
    $department_id = intval($department_id);
    if($department_id){
      $all_children = $this->department->get_all_children($department_id);
      $all_children[] = $department_id;
      $where['in admin_user.department_id'] = $all_children;
    }
    $user_kpi = $this->user_kpi->get_all_info($where,'*','admin_user.user_id','desc');
    if ($user_kpi !== NULL)
    {
      $total = $user_kpi['total'];
      foreach($user_kpi['data'] as $q)
      {
        $records[] = array(
        'realname' => $q['realname'],
        'department_name' => $q['department_name'],
        'grade_name' => $q['grade_name'],
        'month' => date('Y-m',strtotime($q['month'])),
        'kpi1' => $q['kpi1'],
        'kpi2' => $q['kpi2'],
        'salary' => $q['salary'],
        'performance_pay' => $q['performance_pay']
        );     
      }
    }
    $file_url = $this->excel->extraExcel($records);
    return array('success' => TRUE, 'feedback' => $file_url);
  }
}
