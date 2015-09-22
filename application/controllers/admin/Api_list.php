<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api_list extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('api_list_model','api_list');
    }

    public function index()
    {
        //$this->twig->render('api_list',$this->api_list->get_one());
	$api_list = $this->api_list->get_one();
	$this->twig->assign('user_name',$api_list['user_name']);
	$this->twig->render('api_list');
    }
    
    
    //后添加
    
     /**
     * 获取api信息
     * 根据ID获取单条api信息、获取列表
     * @access public
     * @return string
     */
    public function api_list_get(){
        $id = $this->get('id');
        if($id)
        {
            $data = $this->api_list->get_one(array('id'=>$id));
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
                $where['like'] = array('api_string'=>$search);
            }
            $api_list = $this->api_list->get_all($where,'*','id','desc', $limit,$start);
            $total = $this->api_list->get_count($where);
            if($api_list!==NULL){
                foreach($api_list as $api)
                {
                    $records[] = array(
                                        'id' => $api['id'],
                                        'api_string' => $api['api_string'],
                                        'api_name' => $api['api_name'],
                                        'is_closed' => $api['is_closed']);     
                }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
    }
     /**
     * 保存api信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function api_list_post(){
        $this->load->library('form_validation');
        $id = $this->post('id');
        $api_string = $this->post('api_string');
        $api_name = $this->post('api_name');
        $is_closed = $this->post('is_closed');
        
        $api_string_error_message = array('required'  => '%s不能为空.',
                                          'min_length' => '%s长度必须大于2位',
                                          'is_unique' => '您输入的%s系统中已存在.'
                                    );
        $api_string_rule = 'trim|required|min_length[3]|is_unique[api_list.api_string]';
        $this->form_validation->set_rules('api_string','接口名称',$api_string_rule,$api_string_error_message);
        
        $error = FALSE;
        $feedback = array();
        if ($this->form_validation->run() == false) 
        {
            $error = TRUE;
            $feedback[] = validation_errors();
        }
        $data = array('api_string' => $api_string,
		       'api_name' => $api_name,
		       'is_closed' => ( ! empty($is_closed) && ($is_closed == 'on') ? '1' : '0') 
                );
        //保存api数据
        if ($error === FALSE)
        {
            if($this->api_list->save((is_numeric($id) ? array('id'=>$id) : NULL),$data))
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
     * 修改接口状态
     *
     * @access public
     * @return string
     */
    public function api_list_put(){
        $flag = $this->put('flag');
        $id = $this->put('id');
        if ($this->api_list->update(array('is_closed'=>$flag),array('id'=>$id)))
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
     * 删除接口
     *
     * @access public
     * @param $id
     * @return string
     */
    public function api_list_delete(){
        $id = $this->delete('id');
        if ($this->api_list->delete(array('id'=>$id)))
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
