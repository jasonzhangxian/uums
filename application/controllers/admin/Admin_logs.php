<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Admin_logs extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('admin_logs_model','admin_logs');
    }

    public function index()
    {
        //$this->twig->render('admin_logs',$this->admin_logs->get_one());
        $admin_logs = $this->admin_logs->get_one();
        $this->twig->assign('user_name',$admin_logs['user_name']);
        $this->twig->render('admin_logs');
    }
     //后添加
    /**
     * 获取日志信息
     * 根据ID获取单条职位信息、获取列表
     * @access public
     * @return string
     */
     public function admin_logs_get(){
        $log_id = $this->get('log_id');
        if($log_id)
        {
            $data = $this->admin_logs->get_one(array('log_id'=>$log_id));
            $result = array('success' => TRUE, 'data' => $data);
        }
        else
        {
            $start = $this->get('start') ? $this->get('start') : 0;
            $limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
            $search = $this->get('search');
            $where = array();
            $records = array();
            if(!empty($search)){
                $where['like'] = array('log_info'=>$search);
            }
            $admin_logs = $this->admin_logs->get_all($where,'*','log_id','desc', $limit,$start);
            $total = $this->admin_logs->get_count($where);
            if($admin_logs){
                $this->load->model('admin_user_model','admin_user');
                foreach($admin_logs as $log)
                {
                    $admin_info = $this->admin_user->get_one(array('user_id'=>$log['admin_id']),'realname');
                    $records[] = array( 'log_id' => $log['log_id'],
                                        'admin_id' => $admin_info['realname'],
                                        'log_info' => $log['log_info'],
                                        'log_time' => date("Y-m-d H:i",$log['log_time'])
                            );     
                }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
     }
       /**
     * 保存日志信息（添加与修改）
     *
     * @access public
     * @return string
     */
//     public function admin_logs_post(){
//        $log_id = $this->post('log_id');
//        $admin_id = $this->admin->get_id();
//        $log_info = $this->post('log_info');
//        $error = FALSE;
//        $feedback = array();
//        $data =array('log_id'=>$log_id,
//                     'admin_id'=>$admin_id,
//                     'log_info'=>$log_info
//                    );
//        if ($error === FALSE)
//        {
//            if($this->admin_logs->save((is_numeric($log_id) ? array('log_id'=>$log_id) : NULL),$data))
//            {
//                $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功');
//            }
//            else
//            {
//                $response = array('success' => FALSE, 'feedback' => '错误： 操作失败');
//            }
//        } 
//        else
//        {
//            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败' . '<br />' . implode('<br />', $feedback));
//        }
//        $this->response($response, REST_Controller::HTTP_OK);
//     }
      /**
     * 删除日志
     *
     * @access public
     * @param $id
     * @return string
     */
     public function admin_logs_delete(){
        $log_id = $this->delete('log_id');
        if ($this->admin_logs->delete(array('log_id'=>$log_id)))
        {
            $response = array('success' => TRUE, 'feedback' => '成功： 此项操作成功。');
        }
        else
        {
            $response = array('success' => FALSE, 'feedback' => '错误： 操作失败。');
        }

	$this->response($response, REST_Controller::HTTP_OK);
    }
}
