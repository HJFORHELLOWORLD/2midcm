<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BetweenUnit extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(58);
		$this->load->model('data_model');

    }

    public function index(){
		$this->load->view('betweenUnit/index');

	}

    public function add(){
        $this->load->view('betweenUnit/add');

    }

	public function init(){
        $this->load->view('betweenUnit/export');
    }




    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 客户管理新增修改
     * @description 添加修改的接口
     * @method get
     * @url https://www.2midcm.com/customer/save
     * @param name 可选 string 客户名称
     * @param number 可选 string 客户编号
     * @param categoryid 可选 string 客户类别
     * @param categoryname 必选 string 分类名称
     * @param linkmans 必选 string 客户联系方式
     * @return {"status":200,"msg":"success","data":'.json_encode($info).'}
     * @return_param status static 1：'200'新增或修改成功;2："-1"新增或修改失败
     * @remark 这里是备注信息
     * @number 5
     */
    public function save() {
        $this->purview_model->checkpurview(90);
        $data = $this->input->post('postData', TRUE);
        $act = $this->input->get('act', TRUE);
        if (strlen($data) > 0) {
            $data = (array)json_decode($data);
            if (is_array($data['entries'])) {
                foreach ($data['entries'] as $arr => $row) {
                    $v[$arr]['PK_BU_ID'] = $row->PK_BU_ID;
                    $v[$arr]['Name'] = $row->Name;
                    $v[$arr]['desc'] = $row->desc;
                    $v[$arr]['Area_ID'] = $row->Area_ID;
                    $v[$arr]['BU_Cat'] = $row->BU_Cat;
                    $v[$arr]['Industry_ID'] = $row->Industry_ID;
                    $v[$arr]['Taxrate'] = $row->Taxrate;
                    $v[$arr]['LinkMans'] = $row->LinkMans;
                    $v[$arr]['Status'] = $row->Status;
                    $v[$arr]['Creator_ID'] = $row->Creator_ID;
                    $v[$arr]['Create_Date'] = date('Y-m-d H:i:s', time());
                    $v[$arr]['Modify_id'] = $row->modify_id;
                    $v[$arr]['Modify_Date'] = date('Y-m-d H:i:s', time());
                }
                $name= $v[$arr]['Name'];
                $this->mysql_model->db_inst(BETWEENUNIT, $v);
                $this->cache_model->delsome(BETWEENUNIT);
                $this->data_model->logs('操作人：ID_' . $name.'新增物流信息');
                die('{"status":200,"msg":"success"}');
            }
        } else {
            $this->load->view('betweenUnit/add', $data);
        }
    }






    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 客户管理导出
     * @description 添加导出的接口
     * @method get
     * @url https://www.2midcm.com/customer/export
     * @param skey 必选 array 客户ID数组
     * @return {"status":200,"msg":"success","data":'.json_encode($skey).'}
     * @return_param status static 1：'200'导出成功;2："-1"导出失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function export(){
	    $this->purview_model->checkpurview(62);
	    sys_xls('客户.xls');
		$skey   = str_enhtml($this->input->get('skey',TRUE));
		$where  = ' and type=1';
		if ($skey) {
			$where .= ' and contact like "%'.$skey.'%"';
		}
		$this->data_model->logs('导出客户');
		$data['list'] = $this->cache_model->load_data(BETWEENUNIT,'(status=1) '.$where.' order by id desc');
		$this->load->view('betweenUnit/export',$data);
	}

    /**
     * showdoc
     * @catalog 开发文档/设置
     * @title 客户管理删除
     * @description 添加删除的接口
     * @method get
     * @url https://www.2midcm.com/customer/del
     * @param id 必选 int 客户ID
     * @return {"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}
     * @return_param status static 1：'200'删除成功;2："-1"删除失败
     * @remark 这里是备注信息
     * @number 5
     */
	public function del(){
	    $this->purview_model->checkpurview(61);
	    $id = str_enhtml($this->input->post('id',TRUE));
		if (strlen($id) > 0) {
		    $this->mysql_model->db_count(SALEORDER,'(contactid in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有客户发生业务不可删除"}');
			$name = $this->mysql_model->db_select(BETWEENUNIT,'(id in('.$id.'))','name');
			if (count($name)>0) {
			    $name = join(',',$name);
			}
		    $sql = $this->mysql_model->db_del(BETWEENUNIT,'(id in('.$id.'))');
		    if ($sql) {
			    $this->cache_model->delsome(BETWEENUNIT);
				$this->data_model->logs('删除客户:ID='.$id.' 名称:'.$name);
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			} else {
			    die('{"status":-1,"msg":"删除失败"}');
			}
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */