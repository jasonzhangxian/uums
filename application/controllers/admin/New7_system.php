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
     * 获取岗位信息
     * 根据ID获取单条岗位信息、获取列表
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

			$records = $this->new7_system->get_all($where,'*','system_id','desc', $limit,$start);
			$total = $this->new7_system->get_count($where);

			$result = array(EXT_JSON_READER_TOTAL => $total, EXT_JSON_READER_ROOT => $records);
		}
		$this->response($result, REST_Controller::HTTP_OK);
    }

}
