<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api_logs extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('api_logs_model','api_logs');
    }

    public function index()
    {
        //$this->twig->render('api_logs',$this->api_logs->get_one());
        $api_logs = $this->api_logs->get_one();
        $this->twig->assign('user_name',$api_logs['user_name']);
        $this->twig->render('api_logs');
    }
    //后添加
    /**
     * 获取日志信息
     * 根据ID获取单条职位信息、获取列表
     * @access public
     * @return string
     */
     public function api_logs_get(){
        $lid = $this->get('lid');
        if($lid)
        {
            $data = $this->api_logs->get_one(array('lid'=>$lid));
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
                $where['like'] = array('api'=>$search);
            }
            $api_logs = $this->api_logs->get_all($where,'*','lid','desc', $limit,$start);
            $total = $this->api_logs->get_count($where);
            if($api_logs!==NULL){
                foreach($api_logs as $a)
                {
                    $records[] = array(
                                        'lid' => $a['lid'],
                                        'api' => $a['api'],
                                        'log_time' => $a['log_time'],
                                        'ip_address'=>$a['ip_address'],
                                        'system_code'=>$a['system_code']
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
//     public function api_logs_post(){
//        $lid = $this->post('lid');
//        $api = $this->post('api');
//        $log_time = date('Y-m-d H:i:s',time());
//        $ip_address = $this->post('ip_address');
//        $system_code = $this->post('system_code');
//        $error = FALSE;
//        $feedback = array();
//        $data =array('lid'=>$lid,
//                     'api'=>$api,
//                     'log_time'=>$log_time,
//                     'ip_address'=>$ip_address,
//                     'system_code'=>$system_code
//                    );
//        if ($error === FALSE)
//        {
//            if($this->api_logs->save((is_numeric($lid) ? array('lid'=>$lid) : NULL),$data))
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
     public function api_logs_delete(){
        $lid = $this->delete('lid');
        if ($this->api_logs->delete(array('lid'=>$lid)))
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
