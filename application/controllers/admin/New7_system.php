<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class New7_system extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
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
        $system_id = $this->get('system_id');
        if($system_id)
        {
            $data = $this->new7_system->get_one(array('system_id'=>$system_id));
            $result = array('success' => TRUE, 'data' => $data);
        }else
        {
            $start = $this->get('start') ? $this->get('start') : 0;
            $limit = $this->get('limit') ? $this->get('limit') : MAX_DISPLAY_SEARCH_RESULTS;
            $search = $this->get('search');
            $where = array();
            $records = array();
            if(!empty($search)){
                $where['like'] = array('system_name'=>$search);
            }
            $new7_system = $this->new7_system->get_all($where,'*','system_id','desc', $limit,$start);

            $this->db->from('admin_user');
            $this->db->select('username');
            $this->db->where('user_id',$new7_system[0]['update_user_id']);
            $query = $this->db->get()->result_array();

            $total = $this->new7_system->get_count($where);
            if($new7_system!==NULL){
                foreach($new7_system as $n)
                {
                    $records[] = array(
                                    'system_id' => $n['system_id'],
                                    'system_name' => $n['system_name'],
                                    'sys_code'=>$n['sys_code'],
                                    'system_url'=>$n['system_url'],
                                    'update_time' => $n['update_time'],
                                    'update_user_id' => $query[0]['username'],
                                    'secret_key' => $n['secret_key']
                                  );     
                }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
    }
    //后添加
    /**
     * 保存系统信息（添加与修改）
     *
     * @access public
     * @return string
     */
    public function new7_system_post(){
        $system_id = $this->post('system_id');
        $system_name = $this->post('system_name');
        $sys_code = $this->post('sys_code');
        $system_url = $this->post('system_url');
        $update_time = date('Y-m-d H:i:s',time());
        $update_user_id = $this->admin->get_id();
        $secret_key = $this->post('secret_key');
        
        $error = FALSE;
        $feedback = array();
        
        $data = array('system_id'=>$system_id,
                      'system_name'=>$system_name,
                      'sys_code'=>$sys_code,
                      'system_url'=>$system_url,
                      'update_time'=>$update_time,
                      'update_user_id'=>$update_user_id,
                      'secret_key'=>$secret_key
                     );
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
    public function new7_system_delete(){
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
}
