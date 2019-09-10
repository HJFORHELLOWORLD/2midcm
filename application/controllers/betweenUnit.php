<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BetweenUnit extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->purview_model->checkpurview(58);
		$this->load->model('data_model');
        $this->uid   = $this->session->userdata('uid');

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
        $act  = str_enhtml($this->input->get('act',TRUE));
        $id = intval($this->input->post('id',TRUE));

        // $data['linkmans']    = $this->input->post('linkMans',TRUE);
        $data['Name']      = str_enhtml($this->input->post('name',TRUE));
        strlen($data['Name']) < 1 && die('{"status":-1,"msg":"客户名称不能为空"}');
        $data['BU_Cat']   = str_enhtml($this->input->post('BU_Cat',TRUE));
        $data['Industry_ID']  = intval($this->input->post('Industry_ID',TRUE));
        $data['Area_ID']        = str_enhtml($this->input->post('Area_ID',TRUE));
        $data['Taxrate']      = (float)str_enhtml($this->input->post('Taxrate',TRUE))/100;
        $data['Desc']      = str_enhtml($this->input->post('desc',TRUE));
        $data['Status'] = intval(str_enhtml($this->input->post('status',TRUE)));
        $data['Modify_ID'] = $this->uid;
        $data['Modify_Date'] = date('Y-m-d H:i:s',time());
        $links = array();
        $phone = str_enhtml($this->input->post('phone',TRUE));
        if (strlen($phone)>0) {
            //  $list = (array)json_decode($data['linkmans']);
            // if (count($list)>0) {
            // foreach ($list as $arr=>$row) {
            //if ($row->linkFirst==1) {
            $links[0]['linkPhone']       = $phone;
            $data['Linkmans'] = json_encode($links);
            //}
            //  }
            //}
        }
        if ($act=='add') {
            $this->purview_model->checkpurview(59);
            $this->mysql_model->db_count(BETWEENUNIT,'(Name="'.$data['Name'].'")') > 0 && die('{"status":-1,"msg":"已存在该往来单位名称"}');
            $name = $data['Name'];
            $id = $this->mysql_model->db_inst(BETWEENUNIT, $data);
            $this->cache_model->delsome(BETWEENUNIT);
            $this->data_model->logs('操作人：ID_' . $name . '新增往来单位信息');
            //回传数据
            $data = array('id' => $id,'name' => $data['Name'],'remark' => $data['Desc'],
                'BU_Cat_Name' => $data['BU_Cat'] == 1 ? '客户' : ($data['BU_Cat'] == 2 ? '厂家' : ($data['BU_Cat'] == 3 ? '客户兼厂家' : '第三方')),
                'Industry_ID'  => $data['Industry_ID'], 'Area_ID' => $data['Area_ID'],
                'Industry' => str_enhtml($this->input->post('industryname',TRUE)),'Area' => str_enhtml($this->input->post('areaname',TRUE)),
                'Taxrate' => $data['Taxrate'] * 100,
                'telephone' => $phone,'StatusName' =>  $data['Status'] == 0 ? '不正常' : '正常',
                'Status' => $data['Status'],'BU_Cat' => $data['BU_Cat']);
            die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
        } elseif ($act=='update') {
            $this->purview_model->checkpurview(60);

            //$name = $this->mysql_model->db_one(BETWEENUNIT,'(PK_BU_ID='.$id.')','name');
            //$sql = $this->mysql_model->db_upd(BETWEENUNIT,array_filter($data),'(PK_BU_ID='.$id.')');
            $sql = $this->mysql_model->db_upd(BETWEENUNIT,$data,'(PK_BU_ID='.$id.')');
            if ($sql) {
                $this->cache_model->delsome(PURORDER);
                $this->cache_model->delsome(BETWEENUNIT);
                $this->cache_model->delsome(SALEORDER);
                $this->data_model->logs('修改了往来单位:'.$id);
                //回传数据
                $data = array('id' => $id,'name' => $data['Name'],'remark' => $data['Desc'],
                    'BU_Cat_Name' => $data['BU_Cat'] == 1 ? '客户' : ($data['BU_Cat'] == 2 ? '厂家' : ($data['BU_Cat'] == 3 ? '客户兼厂家' : '第三方')),
                    'Industry_ID'  => $data['Industry_ID'], 'Area_ID' => $data['Area_ID'],
                    'Industry' => str_enhtml($this->input->post('industryname',TRUE)),'Area' => str_enhtml($this->input->post('areaname',TRUE)),
                    'Taxrate' => $data['Taxrate'] * 100,
                    'telephone' => $phone,'StatusName' =>  $data['Status'] == 0 ? '不正常' : '正常',
                    'Status' => $data['Status'],'BU_Cat' => $data['BU_Cat']);
                die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
            } else {
                die('{"status":-1,"msg":"修改失败"}');
            }
        }
    }

    public function modify(){
        $this->purview_model->checkpurview(60);

        $id = intval($this->input->post('id',TRUE));

       // $data['linkmans']    = $this->input->post('linkMans',TRUE);
        $data['Name']      = str_enhtml($this->input->post('name',TRUE));
        strlen($data['Name']) < 1 && die('{"status":-1,"msg":"客户名称不能为空"}');

        $data['BU_Cat']   = str_enhtml($this->input->post('BU_Cat',TRUE));
        $data['Industry_ID']  = intval($this->input->post('Industry_ID',TRUE));
        $data['Area_ID']        = str_enhtml($this->input->post('Area_ID',TRUE));
        $data['Taxrate']      = (float)str_enhtml($this->input->post('Taxrate',TRUE));
        $data['Desc']      = str_enhtml($this->input->post('desc',TRUE));
        $data['Status'] = intval(str_enhtml($this->input->post('status',TRUE)));
        $data['Modify_ID'] = $this->uid;
        $data['Modify_Date'] = date('Y-m-d H:i:s',time());
        $links = array();
        $phone = str_enhtml($this->input->post('phone',TRUE));
        if (strlen($phone)>0) {
          //  $list = (array)json_decode($data['linkmans']);
           // if (count($list)>0) {
               // foreach ($list as $arr=>$row) {
                    //if ($row->linkFirst==1) {
            $links[0]['linkPhone']       = $phone;
            $data['Linkmans'] = json_encode($links);
                    //}
              //  }
            //}
        }
        //$name = $this->mysql_model->db_one(BETWEENUNIT,'(PK_BU_ID='.$id.')','name');
        //$sql = $this->mysql_model->db_upd(BETWEENUNIT,array_filter($data),'(PK_BU_ID='.$id.')');
        $sql = $this->mysql_model->db_upd(BETWEENUNIT,$data,'(PK_BU_ID='.$id.')');
        if ($sql) {
            $this->cache_model->delsome(PURORDER);
            $this->cache_model->delsome(BETWEENUNIT);
            $this->cache_model->delsome(SALEORDER);
            $this->data_model->logs('修改了往来单位:'.$id);
            //回传数据
            $data = array('id' => $id,'name' => $data['Name'],'remark' => $data['Desc'],
                'BU_Cat_Name' => $data['BU_Cat'] == 1 ? '客户' : ($data['BU_Cat'] == 2 ? '厂家' : ($data['BU_Cat'] == 3 ? '客户兼厂家' : '第三方')),
                'Industry_ID'  => $data['Industry_ID'], 'Area_ID' => $data['Area_ID'],
                'Industry' => str_enhtml($this->input->post('industryname',TRUE)),'Area' => str_enhtml($this->input->post('areaname',TRUE)),
                'Taxrate' => $data['Taxrate'],
                'telephone' => $phone,'StatusName' =>  $data['Status'] == 0 ? '不正常' : '正常',
                'Status' => $data['Status'],'BU_Cat' => $data['BU_Cat']);
            die('{"status":200,"msg":"success","data":'.json_encode($data).'}');
        } else {
            die('{"status":-1,"msg":"修改失败"}');
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
		    $this->mysql_model->db_count(SALEORDER,'(Customer_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有客户发生业务不可删除"}');
            $this->mysql_model->db_count(PURORDER,'(Supplier_ID in('.$id.'))')>0 && die('{"status":-1,"msg":"其中有客户发生业务不可删除"}');
			$name = $this->mysql_model->db_select(BETWEENUNIT,'(PK_BU_ID in('.$id.'))','name');
			if (count($name)>0) {
			    $name = join(',',$name);
			}
		    $sql = $this->mysql_model->db_del(BETWEENUNIT,'(PK_BU_ID in('.$id.'))');
		    if ($sql) {
			    $this->cache_model->delsome(BETWEENUNIT);
				$this->data_model->logs('删除往来单位:PK_BU_ID='.$id.' 名称:'.$name);
				die('{"status":200,"msg":"success","data":{"msg":"","id":['.$id.']}}');
			} else {
			    die('{"status":-1,"msg":"删除失败"}');
			}
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */