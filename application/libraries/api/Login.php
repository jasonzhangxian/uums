<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

Class Login {

	private $CI;
	private $_username;//用户名
	private $_password;//密码
	private $_sys_code;//系统编码

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('admin_user_model','admin_user');
		$this->CI->load->model('privilege_model','privilege');
		$this->CI->load->model('user_to_quarters_model','user_to_quarters');
		$this->CI->load->model('quarters_to_privilege_model','quarters_to_privilege');
		$this->CI->load->library(array('result','check','encryption'));
		$this->_username = $this->CI->input->post('username');
		$this->_password = $this->CI->input->post('password');
		$this->_sys_code = $this->CI->input->get('sys_code');
		$this->_username = trim($this->_username);
		$this->_password = trim($this->_password);
		$this->_sys_code = trim($this->_sys_code);
		$this->_password = md5($this->CI->encryption->decrypt($this->_password));
		//验证参数是否符合本API的规则
		$this->check_params();

	}
	public function execute()
	{
		//根据用户名和密码验证
		$user_info = $this->CI->admin_user->get_one(array('username'=>$this->_username,'password'=>$this->_password));
		//验证通过
		if(!$user_info)
		{
			$this->CI->result->set('请输入正确的用户名或密码！',21)->output();
		}
		if($user_info['is_deleted'] == 1)
		{
			$this->CI->result->set('该用户已被冻结！',21)->output();
		}
		$result = array('base_info'=>$user_info);
			//获取用户所在对应系统的岗位权限信息
		$result['privilege'] = $this->CI->admin_user->get_user_privilege($user_info['user_id'],$this->_sys_code);
		//To do list
			//下一步设计岗位权限结构获取以及职级部门信息构成
			//还要考虑下属系统该如何获取操作菜单
			//一些基础信息，需要各个系统自行冗余存储使用		
		//输出结果
		$this->CI->result->set($result)->output();
	}
	/**
	 * 验证参数
	 */
	private function check_params()
	{
		$this->CI->check->checkNotNull($this->_username,'username');
		$this->CI->check->checkMaxLength($this->_username,16,'username');
		$this->CI->check->checkMinLength($this->_username,4,'username');
		//密码直接发过来加密过后的字符串，就不需要过多验证了
		$this->CI->check->checkNotNull($this->_password,'password');
	}
}

/* End of file Login.php */
/* Location: ./application/libraries/api/Login.php */