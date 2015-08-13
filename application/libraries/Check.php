<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

Class Check {

	private $CI;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->library('result');
	}

	/**
	 * 校验字段 fieldName 的值$value非空
	 *
	 **/
	public function checkNotNull($value,$fieldName) {
		
		if(self::checkEmpty($value)){
			$this->CI->result->set("server-check-error:Missing Required Arguments: " .$fieldName , 40)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value 的长度
	 *
	 **/
	public function checkMaxLength($value,$maxLength,$fieldName){		
		if(!self::checkEmpty($value) && mb_strlen($value , "UTF-8") > $maxLength){
			$this->CI->result->set("server-check-error:Invalid Arguments:the length of " .$fieldName . " can not be larger than " . $maxLength . "." , 41)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value 的长度
	 *
	 **/
	public function checkMinLength($value,$minLength,$fieldName){		
		if(!self::checkEmpty($value) && mb_strlen($value , "UTF-8") < $minLength){
			$this->CI->result->set("server-check-error:Invalid Arguments:the length of " .$fieldName . " can not be shorter than " . $minLength . "." , 41)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value的最大列表长度
	 *
	 **/
	public function checkMaxListSize($value,$maxSize,$fieldName) {	

		if(self::checkEmpty($value))
			return ;

		$list=preg_split("/,/",$value);
		if(count($list) > $maxSize){
			$this->CI->result->set("server-check-error:Invalid Arguments:the listsize(the string split by \",\") of ". $fieldName . " must be less than " . $maxSize . " ." , 41)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value 的最大值
	 *
	 **/
	public function checkMaxValue($value,$maxValue,$fieldName){	

		if(self::checkEmpty($value))
			return ;

		self::checkNumeric($value,$fieldName);

		if($value > $maxValue){
			$this->CI->result->set("server-check-error:Invalid Arguments:the value of " . $fieldName . " can not be larger than " . $maxValue ." ." , 41)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value 的最小值
	 *
	 **/
	public function checkMinValue($value,$minValue,$fieldName) {
		
		if(self::checkEmpty($value))
			return ;

		self::checkNumeric($value,$fieldName);
		
		if($value < $minValue){
			$this->CI->result->set("server-check-error:Invalid Arguments:the value of " . $fieldName . " can not be less than " . $minValue . " ." , 41)->output();
		}
	}

	/**
	 * 检验字段fieldName的值value是否是number
	 *
	 **/
	protected function checkNumeric($value,$fieldName) {
		if(!is_numeric($value))
			$this->CI->result->set("server-check-error:Invalid Arguments:the value of " . $fieldName . " is not number : " . $value . " ." , 41)->output();
	}

	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *	if is null , return true;
	 *	
	 *
	 **/
	public function checkEmpty($value) {
		if(!isset($value))
			return true ;
		if($value === null )
			return true;
		if(trim($value) === "")
			return true;
		
		return false;
	}
}

/* End of file Check.php */
/* Location: ./application/libraries/Check.php */