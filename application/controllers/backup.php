<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(84);
		$this->load->model('data_model');
		$this->conf = $this->config->config;
		$this->name = str_no('jxc_').'.sql';
		$this->load->helper(array('number','directory','download')); 
    }
	
	public function index(){
		$this->load->view('backup/index');
	}
	
	//列表
	public function lists(){
	    $v = array();
	    $list = get_dir_file_info($this->conf['db_url']);
		$data['status'] = 200;
		$data['msg'] = 'success';
		$i = 0;
		foreach ($list as $arr=>$row) {
		    $v[$i]['fid'] = $row['name']; 
			$v[$i]['createTime'] = date("Y-m-d H:i:s", $row['date']); 
			$v[$i]['username'] = $row['date']; 
			$v[$i]['filename'] = $row['name']; 
			$v[$i]['dbid'] = 0; 
			$v[$i]['size'] = $row['size']; 
			$i++;
		}
		$data['data']['items'] = $v;
		$data['totalsize']     = 1;
		die(json_encode($data));
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 备份
     * @description 数据备份的接口
     * @method get
     * @url https://www.2midcm.com/backup/add
     * @param createTime 必选 string 创建时间
     * @param size 必选 string 文件大小
     * @param filename 必选 string 文件名称
     * @param fid 必选 int 文件ID
     * @return {"status":200,,"msg":"success","data":{"createTime":"2019-04-19 20:10",}}
     * @return_param status static 1：'200'备份成功;2："-1"参数错误
     * @remark 这里是备注信息
     * @number 5
     */
	public function add(){
	    $this->load->dbutil();
		$info = &$this->dbutil->backup(); 
		$path = $this->conf['db_url'].$this->name;
		if (write_file($path, $info)) {
			$this->data_model->logs('备份与恢复,备份文件名:'.$this->name);
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['createTime'] = $this->name;
			$data['filename'] = $this->name;
			$data['dbid'] = 0;
			$data['fid']  = $this->name;
			$data['size'] = filesize($path);
		    die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
		} else {
		    die('{"status":-1,"msg":"参数错误"}');
		}
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 备份删除
     * @description 备份删除的接口
     * @method get
     * @url https://www.2midcm.com/backup/del
     * @param name 必选 string 备份文件名称
     * @return {"status":200,"msg":"success","data":{"id":"1"}}
     * @return_param status static 1：'200'删除成功;2："-1"删除失败
     * @remark 这里是备注信息
     * @number 5
     */
    public function del() {
		$name = str_enhtml($this->input->get_post('name',TRUE));
		$path = $this->conf['db_url'].$name;
		if (@unlink($path)) {
		    $this->data_model->logs('备份与恢复,删除文件名:'.$name);
			die('{"status":200,"msg":"success","data":{"id":"1"}}');
		} else {
		    die('{"status":-1,"msg":"删除失败"}'); 
		}
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 备份下载
     * @description 备份下载的接口
     * @method get
     * @url https://www.2midcm.com/backup/down
     * @param name 必选 string 备份文件名称
     * @return {"status":200,"msg":"success","data":{"id":"1"}}
     * @return_param status static 1：'200'下载成功;2："-1"下载失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function down() {
		$name = str_enhtml($this->input->get_post('name',TRUE));
		$path = $this->conf['db_url'].$name;
		$info = read_file($path);
		if ($info) {
		    $this->data_model->logs('备份与恢复,下载文件名:'.$name);
			force_download($name, $info); 
		} else {
		    die('{"status":-1,"msg":"下载失败"}'); 
		}
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 备份恢复
     * @description 备份恢复的接口
     * @method get
     * @url https://www.2midcm.com/backup/recovery
     * @param name 必选 string 备份文件名称
     * @return {"status":200,"msg":"success"}
     * @return_param status static 1：'200'恢复成功;2："-1"恢复失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function recovery(){
	    $name = str_enhtml($this->input->get_post('name',TRUE));
		$path = $this->conf['db_url'].$name;
	    $info = read_file($path);
		if ($info) {
		    $this->db->trans_begin();
			$list = explode(";\n",$info);
			foreach ($list as $sql) {
				$this->db->query($sql);
			}
			if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
				die('{"status":-1,"msg":"恢复失败"}'); 
			} else {
			    $this->db->trans_commit();
				$this->data_model->logs('备份与恢复,恢复文件名:'.$name);
			    die('{"status":200,"msg":"success"}');
			}
		} else {
		    die('{"status":-1,"msg":"恢复失败"}'); 
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */