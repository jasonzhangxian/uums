<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Ip_white_list extends REST_Controller { 

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('ip_white_list_model','ip_white_list');
    }

    public function index()
    {
        //$this->twig->render('ip_white_list',$this->ip_white_list->get_one());
        $ip_white_list = $this->ip_white_list->get_one();
        $this->twig->assign('user_name',$ip_white_list['user_name']);
        $this->twig->render('ip_white_list');
    }
    //后添加
    
     /**
     * 获取api信息
     * 根据ID获取单条ip信息、获取列表
     * @access public
     * @return string
     */
    public function ip_white_list_get(){
         $id = $this->get('id');
        if($id)
        {
            $data = $this->ip_white_list->get_one(array('id'=>$id));
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
                $where['like'] = array('ip_address'=>$search);
            }
            $ip_white_list = $this->ip_white_list->get_all($where,'*','id','desc', $limit,$start);
            
            $total = $this->ip_white_list->get_count($where);
            if($ip_white_list!==NULL)
            {
              $this->load->model('admin_user_model','admin_user');
              foreach($ip_white_list as $ip)
              {
                $admin_info = $this->admin_user->get_one(array('user_id'=>$ip['update_user_id']));
                $records[] = array(
                                    'id' => $ip['id'],
                                    'ip_address' => $ip['ip_address'],
                                    'update_time' => $ip['update_time'],
                                    'update_user_id' => $admin_info['realname']
                                  );     
              }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
    }
     /**
     * 保存ip信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function ip_white_list_post(){
        $id = $this->post('id');
        $ip_address = $this->post('ip_address');
        $update_time = date('Y-m-d H:i:s',time());
        $update_user_id = $this->admin->get_id();

        $error = FALSE;
        $feedback = array();
        
        $data = array('id'=>$id,
                      'ip_address'=>$ip_address,
                      'update_time'=>$update_time,
                      'update_user_id'=>$update_user_id
                     );
        if ($error === FALSE)
        {
            if($this->ip_white_list->save((is_numeric($id) ? array('id'=>$id) : NULL),$data))
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
     * 删除ip
     *
     * @access public
     * @param $id
     * @return string
     */
    public function ip_white_list_delete(){
        $id = $this->delete('id');
        if ($this->ip_white_list->delete(array('id'=>$id)))
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
