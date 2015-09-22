<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
class Department extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('department_model','department');
    }

    public function index()
    {
            //$this->twig->render('department',$this->department->get_one());
            $department = $this->department->get_one();
            $this->twig->assign('user_name',$department['user_name']);
            $this->twig->render('department');
    }
     //后添加
    
     /**
     * 获取部门信息
     * 根据ID获取单条部门信息、获取列表
     * @access public
     * @return string
     */
     public function department_get(){
         $department_id = $this->get('department_id');
         if($department_id)
        {
            $data = $this->department->get_one(array('department_id'=>$department_id));
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
                $where['like'] = array('department_name'=>$search);
            }
            $department = $this->department->get_all($where,'*','department_id','desc', $limit,$start);
            $total = $this->department->get_count($where);
            if($department!==NULL){
                foreach($department as $d)
                {
                    $records[] = array(
                                        'department_id' => $d['department_id'],
                                        'department_name' => $d['department_name'],
                                        'departmnt_level' => $d['departmnt_level'],
                                        'parent_id' => $d['parent_id']);     
                }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
     }
        /**
     * 保存部门信息（添加与修改）
     *
     * @access public
     * @return string
     */
     public function department_post(){
        $department_id = $this->post('department_id');
        $department_name = $this->post('department_name');
        $departmnt_level = $this->post('departmnt_level');
        $parent_id = $this->post('parent_id');
        $error = FALSE;
        $feedback = array();
        $data = array('department_id'=>$department_id,
                      'department_name'=>$department_name,
                      'departmnt_level'=>$departmnt_level,
                      'parent_id'=>$parent_id
                     );
        if ($error === FALSE)
        {
            if($this->department->save((is_numeric($department_id) ? array('department_id'=>$department_id) : NULL),$data))
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
     * 删除部门
     *
     * @access public
     * @param $id
     * @return string
     */
     public function department_delete(){
        $department_id = $this->delete('department_id');
        if ($this->department->delete(array('department_id'=>$department_id)))
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
