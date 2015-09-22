<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
class Grade extends REST_Controller {

	/**
	 * 管理员管理界面
	 */

    function __construct() {
        parent::__construct();
        $this->load->model('grade_model','grade');
    }

    public function index()
    {
        //$this->twig->render('grade',$this->grade->get_one());
        $grade = $this->grade->get_one();
        $this->twig->assign('user_name',$grade['user_name']);
        $this->twig->render('grade');
    }
    //后添加
    /**
     * 获取职位信息
     * 根据ID获取单条职位信息、获取列表
     * @access public
     * @return string
     */
     public function grade_get(){
        $grade_id = $this->get('grade_id');
        if($grade_id)
        {
            $data = $this->grade->get_one(array('grade_id'=>$grade_id));
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
                $where['like'] = array('grade_name'=>$search);
            }
            $grade = $this->grade->get_join_all($where,'grade_name,department.department_name,grade_level,grade.parent_id','grade_id','desc', $limit,$start);
            $total = $this->grade->get_count($where);
           
//            if($grade!==NULL){
//            $records=array();
//                foreach($grade as $key=>$g)
//                {
//                   $records[$key]['grade_id'] = $g['grade_id'];
//                   $records[$key]['grade_name'] = $g['grade_name'];
//                   $records[$key]['department_id'] =$this->get_department_name($g['department_id']);
//                   $records[$key]['grade_level'] = $g['grade_level'];
//                   $records[$key]['parent_id'] = $g['parent_id'];
//                }
//            }
             if($grade!==NULL){
                foreach($grade as $g)
                {
                    $records[] = array(
                                        'grade_name' => $g['grade_name'],
                                        'department_id' => $g['department_name'],
                                        'grade_level' => $g['grade_level'],
                                        'parent_id'=>$g['parent_id']
                                        
                            );     
                }
            }
            $result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
        }
        $this->response($result, REST_Controller::HTTP_OK);
     }
//    public function get_department_name($department_id)
//    {
//        if(!empty($department_id)){
//            $this->db->from('department');
//            $this->db->select('department_name');
//            $this->db->where('department_id',$department_id);
//            $query = $this->db->get()->result_array();
//            $department_name = $query[0][department_name];
//            return  $department_name;
//        } 
//    }

     /**
     * 保存职位信息（添加与修改）
     *
     * @access public
     * @return string
     */
     public function grade_post(){
        $grade_id = $this->post('grade_id');
        $grade_name = $this->post('grade_name');
        $department_id = $this->post('department_id');
        $grade_level = $this->post('grade_level');
        $parent_id = $this->post('parent_id');
        $error = FALSE;
        $feedback = array();
        $data =array('grade_id'=>$grade_id,
                     'grade_name'=>$grade_name,
                     'department_id'=>$department_id,
                     'grade_level'=>$grade_level,
                     'parent_id'=>$parent_id
                    );
        if ($error === FALSE)
        {
            if($this->grade->save((is_numeric($grade_id) ? array('grade_id'=>$grade_id) : NULL),$data))
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
     * 删除职位
     *
     * @access public
     * @param $id
     * @return string
     */
     public function grade_delete(){
        $grade_id = $this->delete('grade_id');
        if ($this->grade->delete(array('grade_id'=>$grade_id)))
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
