<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class department extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->purview_model->checkpurview(82);
        $this->uid   = $this->session->userdata('uid');
    }

    public function index(){
    /*        $this->load->model('mysql_model');
            $this->mysql_model->countQty();
            exit;*/
       $this->load->view('department/index');
}
    public function add(){

        $this->load->view('department/add');
    }

    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户注册
     * @description 用户注册的接口
     * @method get
     * @url https://www.2midcm.com/workcenter/add
     * @param pk_dept_id 必选 string 部门编号
     * @param wc_name 必选 string 工作中心名称
     * @param desc 可选 string 描述
     * @param  head_id 必选 string 负责人
     * @param  creator_id 必选  创建人
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param status static 1：'200'注册成功;2："-1"注册失败
     * @remark 这里是备注信息
     * @number 1
     */
    public function save(){
        $act = str_enhtml($this->input->get('act',TRUE));
        $data = $this->input->post('data',TRUE);
        $data = json_decode($data,true);
        $info['pk_dept_id'] = $data['pk_dept_id'] ;
        $info['name'] = $data['name'] ;
        $info['desc'] = $data['desc'] ;
        $info['head_id'] = $data['head_id'] ;
        $info['creator_id'] = $data['creator_id'];
        if ($act == 'add') {
//            $this->preview_model->checkpurview(55);
            !isset($data['name'])  && die('{"status":-1,"msg":"部门名不能为空"}');
            !isset($data['head_id'])  && die('{"status":-1,"msg":"负责人不能为空"}');
            $this->mysql_model->db_count(DEPARTMENT,'(name="'.$data['name'].'")')>0 && die('{"status":-1,"msg":"部门已经存在"}');
            $sql = $this->mysql_model->db_inst(DEPARTMENT,$data);
            if ($sql) {
                $this->cache_model->delsome(DEPARTMENT);
                die('{"status":200,"msg":"注册成功","creator_id":"'.$data['creator_id'].'"}');
            } else {
                die('{"status":-1,"msg":"添加失败"}');

            }

        }
    }


    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title 用户部门信息
     * @description 用户部门信息回显的接口
     * @method get
     * @url https://www.2midcm.com/login/index
     * @param username 必选 string 用户名
     * @param userpwd 必选 string 密码
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param data array 用户回显数据
     * @remark 这里是备注信息
     * @number 1
     */
    //部门列表
    public function lists() {
        $v = '';
        $data['status'] = 200;
        $data['msg']    = 'success';
        $page = max(intval($this->input->get_post('page',TRUE)),1);
        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
        $where = '';
        if (strlen($key)>0) {
            $where .= ' and (a.PK_Dept_ID like "%'.$key.'%" or a.Head_ID like "%' . $key .'%" )';
        }
        if (strlen($stt)>0) {
            $where .= ' and Create_Date>="'.$stt.'"';
        }
        if (strlen($ett)>0) {
            $where .= ' and Create_Date<="'.$ett.' 23:59:59"';
        }

        $offset = $rows * ($page-1);
        $data['data']['page']= $page;
        //$list = $this->cache_model->load_data(LOGISTICS_INFO,'(1=1) '.$where.' order by id desc limit '.$offset.','.$rows.'');
        $list = $this->data_model->departmentList($where,' order by PK_Dept_ID desc limit '.$offset.','.$rows.'');
        var_dump($list);
        foreach ($list as $arr=>$row) {
                    $v[$arr]['PK_Dept_ID'] = $row['PK_Dept_ID'];
                    $v[$arr]['Name'] = $row['Name'];
                    $v[$arr]['Desc'] = $row['Desc'];
                    $v[$arr]['Header'] = $row['Header'];
                    $v[$arr]['Status'] = $row['Status'];
                    $v[$arr]['Creator_ID'] = $row['Creator_ID'];
                    $v[$arr]['Create_Date'] = $row['Create_Date'];
//            $v[$arr]['modify_id'] =  $row['modify_id'];
//            $v[$arr]['modify_date'] = date('Y-m-d H:i:s', time());
        }
        $data['data']['records']   = count($list);   //总条数
        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
        $data['data']['rows']      = is_array($v) ? $v : '';
        die(json_encode($data));
    }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */