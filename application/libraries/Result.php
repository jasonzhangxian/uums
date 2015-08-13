<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

Class Result {

	public $result;

	function __construct()
	{
		
	}

	public function set($data = array(),$err_no = 0)
	{
		if($err_no)
			$this->result = array('err_no'=>$err_no,'msg'=>$data);
		else
			$this->result = $data;
		return $this;
	}

	public function output()
	{
		echo json_encode($this->result);
		die();
	}
}

/* End of file Result.php */
/* Location: ./application/libraries/Result.php */