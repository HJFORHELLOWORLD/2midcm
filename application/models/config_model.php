<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Config_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
	}

    public function set_config($data=array(),$dir){
		if (strlen($dir)>0) {
		    $str = "<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); \n";
			foreach ($data as $arr=>$vale) {
				$str .= 'define(\''.strtoupper($arr).'\',\''.$vale.'\');';
				$str .= "\n";
			}
			if (write_file($dir,$str,'w+')) {
			    return true;
			} else {
			    return false;
			}
		} else {
		    return false;
		}
	}

}