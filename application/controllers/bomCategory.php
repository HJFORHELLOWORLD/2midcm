<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class bomCategory extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->purview_model->checkpurview(82);
        $this->uid = $this->session->userdata('uid');
    }

    public function index()
    {

        $this->load->view('bomCategory/index');
    }
//    public function add(){
//        $this->load->view('bomCategory/add');
//    }

    /**
     * showdoc
     * @catalog 开发文档/用户
     * @title  物料类别
     * @description bom类别保存的接口
     * @method get
     * @url https://www.2midcm.com/bomCategory/add
     * @param PK_BOMCat_ID1 必选 string 类别编码
     * @param Name 必选 string BOM类别名称
     * @param Desc 可选 string 描述
     * @param  head_id 必选 string 负责人
     * @param  creator_id 必选  创建人
     * @return {"status":200,"msg":"success,"share":"true","userid":1,"name":"小阳","username":"admin"}
     * @return_param status static 1：'200'注册成功;2："-1"注册失败
     * @remark 这里是备注信息
     * @number 1
     */
    private function add()
    {
        $this->purview_model->checkpurview(74);
        $data['name'] = str_enhtml($this->input->post('name', TRUE));
        $data['pid'] = intval($this->input->post('parentId', TRUE));
        $data['type'] = str_enhtml($this->input->get_post('typeNumber', TRUE));
        $this->mysql_model->db_count(BOM_CATEGORY1, '(name="' . $data['name'] . '") and type="' . $data['type'] . '"') > 0 && die('{"status":-1,"msg":"辅助资料名称重复"}');
        if ($data['pid'] == 0) {
            $datas['path'] = $this->mysql_model->db_inst(BOM_CATEGORY1, $data);
            $sql = $this->mysql_model->db_upd(BOM_CATEGORY1, $datas, '(id=' . $datas['path'] . ')');
        } else {
            $info = $this->mysql_model->db_one(BOM_CATEGORY1, '(id=' . $data['pid'] . ')');
            count($info) < 1 && die('{"status":-1,"msg":"参数错误"}');
            $data['depth'] = $info['depth'] + 1;
            $lastid = $this->mysql_model->db_inst(BOM_CATEGORY1, $data);
            $datas['path'] = $info['path'] . ',' . $lastid;
            $sql = $this->mysql_model->db_upd(BOM_CATEGORY1, $datas, '(id=' . $lastid . ')');
        }
        if ($sql) {
            $cate = $this->data_model->category_type();
            $this->data_model->logs('新增' . $cate[$data['type']] . ':' . $data['name']);
            $this->cache_model->delsome(BOM_CATEGORY1);
            die('{"status":200,"msg":"success","data":{"id":' . $sql . ',"name":"' . $data['name'] . '","parentId":' . $data['pid'] . '}}');
        } else {
            die('{"status":-1,"msg":"添加失败"}');
        }
    }

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 商品类别修改
     * @description 类别修改的接口
     * @method get
     * @url https://www.2midcm.com/category/edit
     * @param id 可选 int 类别ID
     * @return "{"status":200,"msg":"success","data":'.json_encode($info).'}
     * @return_param status int 1：'200'修改成功;2："-1"修改失败
     * @remark 这里是备注信息
     * @number 3
     */
    private function edit()
    {
        $this->purview_model->checkpurview(75);
        $id = intval($this->input->post('id', TRUE));
        $name = str_enhtml($this->input->post('name', TRUE));
        $pid = intval($this->input->post('parentId', TRUE));
        $type = str_enhtml($this->input->post('typeNumber', TRUE));
        if ($id > 0) {
            strlen($name) < 1 && die('{"status":-1,"msg":"类别不能为空"}');
            $this->mysql_model->db_count(BOM_CATEGORY1, '(id<>' . $id . ') and (name="' . $name . '") and type="' . $type . '"') > 0 && die('{"status":-1,"msg":"辅助资料名称重复"}');
            $data = $this->mysql_model->db_one(BOM_CATEGORY1, '(id=' . $id . ')');                                          //获取原ID数据
            count($data) < 1 && die('{"status":-1,"msg":"参数错误"}');
            $old_pid = $data['pid'];
            $old_path = $data['path'];
            $pid_list = $this->mysql_model->db_select(BOM_CATEGORY1, '(id<>' . $id . ') and find_in_set(' . $id . ',path)');    //是否有子栏目
            $old_pid_num = count($pid_list);    //是否有子栏目
            //$pid == $old_pid && alert('没有移动');
            $pid == $id && die('{"status":-1,"msg":"当前分类和上级分类不能相同"}');
            if ($pid == 0) {                     //多级转顶级
                $pare_depth = 1;
                if ($old_pid_num == 0) {         //ID不存在子栏目
                    $this->mysql_model->db_upd(BOM_CATEGORY1, array('pid' => 0, 'path' => $id, 'depth' => 1, 'name' => $name), '(id=' . $id . ')');
                } else {                       //ID存在子栏目
                    $this->mysql_model->db_upd(BOM_CATEGORY1, array('pid' => 0, 'path' => $id, 'depth' => 1, 'name' => $name), '(id=' . $id . ')');
                    foreach ($pid_list as $arr => $row) {
                        $path = str_replace($id, '', $old_path);
                        $path = str_replace('' . $path . '', '', '' . $row['path'] . '');
                        $pare_depth = substr_count($path, ',') + 1;
                        $datas[] = array('id' => $row['id'], 'path' => $path, 'depth' => $pare_depth);
                    }
                    $this->mysql_model->db_upd(BOM_CATEGORY1, $datas, 'id');
                }
            } else {                       //pid<>0时，顶级转多级  多级转多级
                $data = $this->mysql_model->db_one(BOM_CATEGORY1, '(id=' . $pid . ')');     //获取原PID数据
                count($data) < 1 && die('{"status":-1,"msg":"参数错误"}');
                $pare_pid = $data['pid'];
                $pare_path = $data['path'];
                $pare_depth = $data['depth'];
                if ($old_pid == 0) {            //顶级转多级
                    if ($old_pid_num == 0) {    //ID不存在子栏目
                        $this->mysql_model->db_upd(BOM_CATEGORY1, array('name' => $name, 'pid' => $pid, 'path' => $pare_path . ',' . $id, 'depth' => $pare_depth + 1), '(id=' . $id . ')');
                    } else {                  //ID存在子栏目
                        $this->mysql_model->db_upd(BOM_CATEGORY1, array('name' => $name, 'pid' => $pid, 'path' => $pare_path . ',' . $id, 'depth' => $pare_depth + 1), '(id=' . $id . ')');
                        foreach ($pid_list as $arr => $row) {
                            $path = $pare_path . ',' . $row['path'];
                            $pare_depth = substr_count($path, ',') + 1;
                            $datas[] = array('id' => $row['id'], 'path' => $path, 'depth' => $pare_depth);
                        }
                        $this->mysql_model->db_upd(BOM_CATEGORY1, $datas, 'id');
                    }

                } else {                      //多级转多级
                    if ($old_pid_num == 0) {    //ID不存在子栏目
                        $this->mysql_model->db_upd(BOM_CATEGORY1, array('name' => $name, 'pid' => $pid, 'path' => $pare_path . ',' . $id, 'depth' => $pare_depth + 1), '(id=' . $id . ')');
                    } else {                  //ID存在子栏目
                        $this->mysql_model->db_upd(BOM_CATEGORY1, array('name' => $name, 'pid' => $pid, 'path' => $pare_path . ',' . $id, 'depth' => $pare_depth + 1), '(id=' . $id . ')');
                        foreach ($pid_list as $arr => $row) {
                            $path = str_replace($id, '', $old_path);
                            $path = str_replace($path, '', $row['path']);
                            $path = $pare_path . ',' . $path;
                            $pare_depth = substr_count($path, ',') + 1;
                            $datas[] = array('id' => $row['id'], 'path' => $path, 'depth' => $pare_depth + 1);
                        }
                        $this->mysql_model->db_upd(BOM_CATEGORY1, $datas, 'id');
                    }
                }
            }
            $cate = $this->data_model->category_type();
            $this->data_model->logs('修改' . $cate[$type] . ':' . $name);
            $this->cache_model->delsome(BOM_CATEGORY1);
            $info['id'] = intval($id);
            $info['level'] = intval($pare_depth);
            $info['name'] = $name;
            $info['parentId'] = intval($pid);
            die('{"status":200,"msg":"success","data":' . json_encode($info) . '}');
        } else {
            die('{"status":-1,"msg":"参数错误"}');
        }
    }

    //分类新增修改
    public function save()
    {
        $act = str_enhtml($this->input->get('act', TRUE));
        if ($act == 'add') {          //新增
            $this->add();
        } elseif ($act == 'update') { //修改
            $this->edit();
        }
    }

    /**
     * showdoc
     * @catalog 开发文档/仓库
     * @title 商品类别删除
     * @description 类别删除的接口
     * @method get
     * @url https://www.2midcm.com/category/del
     * @param id 可选 int 类别ID
     * @return "{"status":200,"msg":"success","data":'.json_encode($info).'}
     * @return_param status int 1：'200'删除成功;2："-1"删除失败
     * @remark 这里是备注信息
     * @number 3
     */
    public function del()
    {
        $this->purview_model->checkpurview(76);
        $id = intval($this->input->post('id', TRUE));
        $type = str_enhtml($this->input->post('typeNumber', TRUE));
        $data = $this->mysql_model->db_one(BOM_CATEGORY1, '(id=' . $id . ')');
        if (count($data) > 0) {
            $this->mysql_model->db_count(BOM_CATEGORY1, '(1=1) and (find_in_set(' . $id . ',path))') > 1 && die('{"status":500,"msg":"操作的对象包含了下级类别，请先删除下级类别"}');
            $this->mysql_model->db_count(BOM_BASE, '(categoryid=' . $id . ')') > 0 && die('{"status":500,"msg":"发生业务不可删除"}');
            $this->mysql_model->db_count(BETWEENUNIT, '(categoryid=' . $id . ')') > 0 && die('{"status":500,"msg":"发生业务不可删除"}');
            $sql = $this->mysql_model->db_del(BOM_CATEGORY1, '(id=' . $id . ')');
            if ($sql) {
                $cate = $this->data_model->category_type();
                $this->data_model->logs('删除' . $cate[$data['type']] . ':ID=' . $id . ' 名称：' . $data['name']);
                $this->cache_model->delsome(BOM_CATEGORY1);
                die('{"status":200,"msg":"success"}');
            } else {
                die('{"status":-1,"msg":"删除失败"}');
            }
        }
    }































    //    public function save(){
//        $v=array();
//        $this->purview_model->checkpurview(90);
//        $data = $this->input->post('data', TRUE);
//        if (strlen($data) > 0) {
//            $data = (array)json_decode($data);
////            var_dump($data);
//            if (is_array($data)) {
////                $data=trim($data);
//                foreach ($data as $arr => $row) {
//                    $v[$arr]['PK_BOMCat_ID1'] = $row['PK_BOMCat_ID1'];
//                    $v[$arr]['PK_BOMCat_ID2'] = $row['PK_BOMCat_ID2'];
//                    $v[$arr]['Name'] = $row['Name'];
//                    $v[$arr]['bom_id'] = $row['bom_id'];
//                    $v[$arr]['pid'] = $row['pid'];
//                    $v[$arr]['Desc'] = $row['Desc'];
//                }
//                $name= $v[$arr]['Name'];
//                $this->mysql_model->db_inst(BOM_CATEGORY1, $v);
//                $this->cache_model->delsome(BOM_CATEGORY1) ;
//                $this->data_model->logs('操作人：ID_' . $name .'新增物料类别');
//                die('{"status":200,"msg":"success"}');
//            }
//        }
//        else {
//            $this->load->view('logistics/add', $data);
//        }
//    }
//
//    //bom类别列表
//    public function lists() {
//        $v = '';
//        $data['status'] = 200;
//        $data['msg']    = 'success';
//        $page = max(intval($this->input->get_post('page',TRUE)),1);
//        $rows = max(intval($this->input->get_post('rows',TRUE)),100);
//        $key  = str_enhtml($this->input->get_post('matchCon',TRUE));
//        $stt  = str_enhtml($this->input->get_post('beginDate',TRUE));
//        $ett  = str_enhtml($this->input->get_post('endDate',TRUE));
//        $where = '';
//        if (strlen($key)>0) {
//            $where .= ' and (a.PK_BOMCat_ID1 like "%'.$key.'%" or a.Name like "%' . $key .'%" )';
//        }
//        if (strlen($stt)>0) {
//            $where .= ' and Create_Date>="'.$stt.'"';
//        }
//        if (strlen($ett)>0) {
//            $where .= ' and Create_Date<="'.$ett.' 23:59:59"';
//        }
//
//        $offset = $rows * ($page-1);
//        $data['data']['page']= $page;
//        //$list = $this->cache_model->load_data(LOGISTICS_INFO,'(1=1) '.$where.' order by id desc limit '.$offset.','.$rows.'');
//        $list = $this->data_model->bomCategoryList($where,' order by PK_BOMCat_ID1 desc limit '.$offset.','.$rows.'');
//        foreach ($list as $arr=>$row) {
//            $v[$arr]['PK_BOMCat_ID1'] = $row['PK_BOMCat_ID1'];
//            $v[$arr]['PK_BOMCat_ID2'] = $row['PK_BOMCat_ID2'];
//            $v[$arr]['Name'] = $row['Name'];
//            $v[$arr]['Desc'] = $row['Desc'];
//            $v[$arr]['bom_id'] = $row['bom_id'];
//            $v[$arr]['pid'] = $row['pid'];
//            $v[$arr]['creator'] = $row['creator'];
//        }
//        $data['data']['records']   = count($list);   //总条数
//        $data['data']['total']     = ceil($data['data']['records']/$rows);    //总分页数
//        $data['data']['rows']      = is_array($v) ? $v : '';
//        die(json_encode($data));
//    }
//
//





}

