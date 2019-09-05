<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data_model extends CI_Model{

	public function __construct(){
  		parent::__construct();
	}
	
	
	//商品采购明细表
	//作用于 invpu.php下
	public function invpu_info($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.* , 
					b.BOMModel AS BOMModel, b.BOMName AS BOMName, b.FK_UnitClass_ID AS FK_UnitClass_ID, b.PK_BOM_ID AS PK_BOM_ID,
					c.name AS unitName
		        from t_'.PURORDER_DETAIL.' as a 
				left join t_'.BOM_BASE.' as b
					on a.BOM_ID=b.PK_BOM_ID 
				left join t_'.UNIT.' as c
					on b.FK_UnitClass_ID=c.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(PURORDER_DETAIL,$sql,2);
	}

    //报价表
    //作用于 sheet.php下
    public function sheet_info($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'select a.* , 
					b.BOMModel AS BOMModel, b.BOMName AS BOMName, b.FK_UnitClass_ID AS FK_UnitClass_ID, b.PK_BOM_ID AS PK_BOM_ID,
					c.name AS unitName
		        from t_'.SALEORDER_DETAIL.' as a 
				left join t_'.BOM_BASE.' as b
					on a.BOM_ID=b.PK_BOM_ID 
				left join t_'.UNIT.' as c
					on b.FK_UnitClass_ID=c.id 
				'.$where.' 
				'.$order.'
				';
        return $this->cache_model->load_sql(SALEORDER_DETAIL,$sql,2);
    }


    //商品销售明细表
	//作用于 invsa.php下
	public function invsa_info($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'select a.* , 
					b.BOMModel AS BOMModel, b.BOMName AS BOMName, b.FK_UnitClass_ID AS FK_UnitClass_ID, b.PK_BOM_ID AS PK_BOM_ID,
					c.name AS unitName
		        from t_'.SALEORDER_DETAIL.' as a 
				left join t_'.BOM_BASE.' as b
					on a.BOM_ID=b.PK_BOM_ID 
				left join t_'.UNIT.' as c
					on b.FK_UnitClass_ID=c.id 
				'.$where.' 
				'.$order.'
				';
        return $this->cache_model->load_sql(SALEORDER_DETAIL,$sql,2);
	}	
	
	
	//其他入库明细
	//作用于 invoi.php下
	public function invoi_info($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.* , 
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname,b.unitid as unitid
		        from '.INVOI_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVOI_INFO,$sql,2);		
	}	
	
    //商品采购明细表
	//作用于报表下
	public function invpu_detail($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.* , 
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname
		        from '.INVPU_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVPU_INFO,$sql,2);		
	}	
	
	//采购汇总表（按商品）
	public function invpu_summary($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select 
					sum(a.qty) as qty ,
					sum(a.amount) as amount,
					a.goodsno as goodsno,
		            if(ifnull(a.qty,0)=0,"0",ifnull(a.amount,0)/ifnull(a.qty,0)) as price,
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname
		        from '.INVPU_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVPU_INFO,$sql,2);		
	}	
	
	//采购汇总表（按供应商）
	public function invpu_supply($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.*,
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname
		        from '.INVPU_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVPU_INFO,$sql,2);		
	}	
	
	
	//商品销售明细表
	public function invsa_list($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.* , 
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname,b.unitid as unitid
		        from '.INVSA_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVSA_INFO,$sql,2);		
	}	
	
	
	//商品销售明细表（按商品）
	public function invsa_summary($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select 
		            sum(a.qty) as qty ,
					sum(a.amount) as amount,
					a.goodsno as goodsno,
		            if(ifnull(a.qty,0)=0,"0",ifnull(a.amount,0)/ifnull(a.qty,0)) as price,
					b.number as number, b.spec as spec, 
					b.name as goodsname,b.unitname as unitname,
					b.unitid as unitid
		        from '.INVSA_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVSA_INFO,$sql,2);		
	}	
	
	//商品销售明细表（按客户）
	public function invsa_customer($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
	    $sql = 'select a.*,
					b.number as number, b.spec as spec, b.name as goodsname,b.unitname as unitname,b.unitid as unitid
		        from '.INVSA_INFO.' as a 
				left join '.GOODS.' as b
					on a.goodsid=b.id 
				'.$where.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVSA_INFO,$sql,2);		
	}	
	
	//商品销售明毛利 (获取商品成本单价)
	public function invsa_rate($where1='',$where2='',$order='') {
	    $where1 = $where1 ? 'where (1=1) '.$where1 : '';
		$where2 = $where2 ? 'where (1=1) '.$where2 : '';
	    $sql = 'select 
		            a.*,
					sum(a.amount) as sa_amount,
					sum(a.qty) as pu_qty,
					if(ifnull(b.pu_qty,0)=0,"0",ifnull(b.pu_amount,0)/ifnull(b.pu_qty,0))  as price
		        from '.INVSA_INFO.' as a 
				left join 
					(select goodsid, sum(amount) as pu_amount ,sum(qty) as pu_qty 
					from '.INVPU_INFO.' 
					'.$where1.' 
					group by goodsid) as b 
				on a.goodsid=b.goodsid  
				'.$where2.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(INVSA_INFO,$sql,2);		
	}	
	
	
	
	//往来单位欠款表
	public function vendor_arrears($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
		$sql = 'select a.*, 
		            (ifnull(b.arrear,0) + ifnull(a.amount,0) - ifnull(a.periodmoney,0)) as arrears
		        from '.CONTACT.' as a 
				left join 
				(select contactid, sum(arrears) as arrear from '.INVPU.' '.$where.' group by contactid) as b 
				on a.id=b.contactid
				where a.type=2
				'.$order.'
				';
		return $this->cache_model->load_sql(INVPU,$sql,2);		
	}	
	
	
	//往来单位欠款表
	public function customer_arrears($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
		$sql = 'select a.*, 
		            (ifnull(b.arrear,0) + ifnull(a.amount,0) - ifnull(a.periodmoney,0)) as arrears
		        from '.CONTACT.' as a 
				left join 
				(select contactid, sum(arrears) as arrear from '.INVSA.' '.$where.' group by contactid) as b 
				on a.id=b.contactid
				where a.type=1
				'.$order.'
				';
		return $this->cache_model->load_sql(INVSA,$sql,2);		
	}	
	
	
	
	//采购商品总类统计
	public function goodsnum() {
		$sql = 'SELECT COUNT(id) AS goodsnum
				FROM '.INVPU_INFO.'
				WHERE (1=1) and month(billdate)='.date('m').' group by goodsid';
		return $this->cache_model->load_sql(INVPU_INFO,$sql,3);		
	}	
	

	
	//盘点库存 
	public function inventory($where='',$order='') {
	    $where = $where ? 'where (1=1) '.$where : '';
/*	    $sql = 'select a.*,
					
					ifnull(a.quantity,0) * a.unitcost + (if(ifnull(b.puqty,0)=0,"0",ifnull(b.amount,0)/ifnull(b.puqty,0)) * (ifnull(b.puqty,0) - ifnull(c.saqty,0) + ifnull(d.oiqty,0))) as puamount,
					
		            (ifnull(a.quantity,0) + ifnull(b.puqty,0) - ifnull(c.saqty,0) + ifnull(d.oiqty,0)) as qty
		        from '.GOODS.' as a 
				left join 
				(select goodsid, sum(qty) as puqty , sum(amount) as amount from '.INVPU_INFO.' group by goodsid) as b
				on a.id=b.goodsid
				left join 
				(select goodsid, sum(qty) as saqty from '.INVSA_INFO.' group by goodsid) as c 
				on a.id=c.goodsid
				left join 
				(select goodsid, sum(qty) as oiqty from '.INVOI_INFO.' group by goodsid) as d
				on a.id=d.goodsid
				'.$where.' 
				'.$order.'
				';*/

	    $sql = 'SELECT a.*, 
                IFNULL(a.quantity,0) * a.unitcost + (IF(IFNULL(b.puqty,0)=0,"0",IFNULL(b.amount,0)/IFNULL(b.puqty,0)) * IFNULL(d.oiqty,0) ) AS puamount, 
                IFNULL(d.oiqty,0) AS qty 
                FROM 
                '. GOODS .' AS a 
                LEFT JOIN 
                (SELECT invpuinfo.goodsid AS goodsid, SUM(invpuinfo.qty) AS puqty , SUM(invpuinfo.amount) AS amount FROM '. INVPU_INFO .' invpuinfo 
                LEFT JOIN '. INVPU .' invpu ON invpuinfo.invpuid=invpu.id WHERE invpu.review =3 GROUP BY goodsid) AS b 
                ON a.id=b.goodsid 
                LEFT JOIN 
                (SELECT invsainfo.goodsid AS goodsid, SUM(invsainfo.qty) AS saqty FROM '. INVSA_INFO .' invsainfo 
                LEFT JOIN '. INVSA .' invsa ON invsainfo.invsaid=invsa.id GROUP BY goodsid) AS c 
                ON a.id=c.goodsid 
                LEFT JOIN 
                (SELECT bom_id, SUM(num) AS oiqty FROM '. BOM_STOCK .' GROUP BY bom_id) AS d 
                ON a.id=d.bom_id
                '.$where.' 
				'.$order.'
				';

		return $this->cache_model->load_sql(GOODS,$sql,2);		
	}	
	
	//商品收发汇总表
	public function goods_summary($where1='',$where2='',$order='') {
	    $where2 = $where2 ? 'where (1=1) '.$where2 : '';
	    $sql = 'select a.*,
		        ifnull(b1.puqty1,0) as  puqty1,
				ifnull(b2.puqty2,0) as  puqty2,
				ifnull(c1.saqty1,0) as  saqty1,
				ifnull(c2.saqty2,0) as  saqty2,
				ifnull(d1.oiqty1,0) as  oiqty1,
				ifnull(d2.oiqty2,0) as  oiqty2,
				ifnull(d3.oiqty3,0) as  oiqty3,
				ifnull(d4.oiqty4,0) as  oiqty4,
				
				ifnull(b1.puamount1,0) as  puamount1,
				
				if(ifnull(b1.puqty1,0)=0,"0",ifnull(b1.puamount1,0)/ifnull(b1.puqty1,0))  as price,
	
				(ifnull(b1.puqty1,0) + ifnull(d1.oiqty1,0) + ifnull(d2.oiqty2,0)) as puqty,
				(ifnull(d3.oiqty3,0) + ifnull(d4.oiqty4,0) + ifnull(b2.puqty2,0) - ifnull(c1.saqty1,0)) as saqty,
				(ifnull(a.quantity,0)+ifnull(b1.puqty1,0)+ifnull(b2.puqty2,0)-ifnull(c1.saqty1,0)-ifnull(c2.saqty2,0)+ifnull(d1.oiqty1,0)+ifnull(d2.oiqty2,0)+ifnull(d3.oiqty3,0)+ifnull(d4.oiqty4,0)) as qty  
		        from '.GOODS.' as a 
				left join 
				(select goodsid, sum(qty) as puqty1, sum(amount) as puamount1 from '.INVPU_INFO.' where type=1 '.$where1.' group by goodsid) as b1 
				on a.id=b1.goodsid
				left join 
				(select goodsid, sum(qty) as puqty2 from '.INVPU_INFO.' where type=2 '.$where1.' group by goodsid) as b2 
				on a.id=b1.goodsid
				left join 
				(select goodsid, sum(qty) as saqty1 from '.INVSA_INFO.' where type=1 '.$where1.' group by goodsid) as c1
				on a.id=c1.goodsid
				left join 
				(select goodsid, sum(qty) as saqty2 from '.INVSA_INFO.' where type=2 '.$where1.' group by goodsid) as c2 
				on a.id=c2.goodsid
				left join 
				(select goodsid, sum(qty) as oiqty1 from '.INVOI_INFO.' where type=1 '.$where1.' group by goodsid) as d1
				on a.id=d1.goodsid
				left join 
				(select goodsid, sum(qty) as oiqty2 from '.INVOI_INFO.' where type=2 '.$where1.' group by goodsid) as d2
				on a.id=d2.goodsid
				left join 
				(select goodsid, sum(qty) as oiqty3 from '.INVOI_INFO.' where type=3 '.$where1.' group by goodsid) as d3
				on a.id=d3.goodsid
				left join 
				(select goodsid, sum(qty) as oiqty4 from '.INVOI_INFO.' where type=4 '.$where1.' group by goodsid) as d4
				on a.id=d4.goodsid
				'.$where2.' 
				'.$order.'
				';
		return $this->cache_model->load_sql(GOODS,$sql,2);		
	}	
	
	//分类类别
	public function category_type() {
	    $data = array();
	    $list = $this->cache_model->load_data(CATEGORY_TYPE,'(1=1)');	
	    foreach ($list as $arr=>$row) {
		    $data[$row['number']] = $row['name'];
		}
		return $data;		
	}	
	
	
	//写入日志
	public function logs($info) {
	    $time     = date('Y-m-d H:i:s');
		$userid   = $this->session->userdata('uid');
		$data = '';
	    if (is_array($info)) {
		    foreach($info as $row) {
			    $data[] = array(
					'FK_Operator_ID'    =>$userid,
					'Action'       =>$row,
					'Log_Date'=>$time
				);
			}
		} else {
			$data['FK_Operator_ID']     =  $userid;
			$data['Action']        =  $info;
			$data['Log_Date']   =  $time;
		}
		if (is_array($data)) {
			$this->mysql_model->db_inst(LOGBOOK,$data);
			$this->cache_model->delsome(LOGBOOK);
		}
	}

    //物流信息列表
    public function logisticsList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'select a.*, b.contactname as contactname, c.name as operatorName 
		        from '.LOGISTICS_INFO.' as a 
				left join 
				'. INVSA .' as b
				on a.billno=b.billno
				left join
				'. ADMIN .' as c
				on a.operator=c.uid
				'.$where.' 
				'.$order.'
				';

        return $this->cache_model->load_sql(LOGISTICS_INFO,$sql,2);
    }

    //某个采购计划信息表
    public function purchasePlanInfo($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.id as id, a.planId AS planId, a.qty AS qty ,a.create_time AS create_time, a.extra_qty as extra_qty, a.total_qty as total_qty,a.reason as reason,
                b.id AS goodsid, b.name AS goodsName ,b.number AS goods_no, b.unitname AS unitName,
                b.unitid AS unitid, b.spec AS spec , b.purprice AS purprice  FROM 
                '.PURCHASE_PLAN.' as a 
                LEFT JOIN '
                .GOODS.' as b
                ON a.goodsid=b.id
                '.$where.'
                '.$order.'
                ';
        return $this->cache_model->load_sql(PURCHASE_PLAN,$sql,2);
    }

    //bom设计列表
    public function designList($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.BOMName AS UpBOM_Name , c.BOMName AS DownBOM_Name, d.`WC_Name` AS WC_Name
                FROM 
               t_'.BOM_DESIGN.' as a 
                LEFT JOIN 
                t_'.BOM_BASE.' as b
                ON a.UpBOM_ID=b.PK_BOM_ID
                LEFT JOIN 
               t_'.BOM_BASE.' as c
                ON a.DownBom_ID = c.PK_BOM_ID
                LEFT JOIN 
               t_'.WORK_CERTER.' as d
                ON a.WC_ID = d.PK_WC_ID
                '.$where.'
                '.$order.'
                ';
        return $this->cache_model->load_sql(BOM_DESIGN,$sql,2);
    }

    //bom列表
    public function bomBaseList($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.name AS unitName, c.Name as cat1Name, d.Name as cat2Name,if(a.IsVirt =0,"否", "是") AS IsVirtVal
                FROM 
                t_'.BOM_BASE.' as a 
                LEFT JOIN t_'
            .UNIT.' as b
                ON a.FK_UnitClass_ID=b.id 
                   LEFT JOIN t_'
            .BOM_CATEGORY1.' as c
                ON a.BOMCat_ID1=c.PK_BOMCat_ID1 
                                LEFT JOIN t_'
            .BOM_CATEGORY2.' as d
                ON a.BOMCat_ID2=d.PK_BOMCat_ID2 
                '.$where.'
                '.$order.'
                ';
        return $this->cache_model->load_sql(BOM_BASE,$sql,2);
    }

    //往来单位类别列表
    public function IndustryList($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.PK_Industry_ID AS PK_Industry_ID, a.Name AS Name, a.desc AS desc, a.Creator_ID AS Creator_ID,
                a.Create_Date AS Create_Date               
                FROM 
                t_'.INDUSTRY.' as a 
                LEFT JOIN t_'
            .BETWEENUNIT.' as b
                ON a.Name=b.Name        
                '.$where.'
                '.$order.'
                ';//var_dump($sql);exit;
        return $this->cache_model->load_sql(INDUSTRY,$sql,2);
    }




    //采购列表
    public function purOrderList($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.PK_BOM_Pur_ID AS PK_BOM_Pur_ID, b.Name AS Supplier_Name, a.Name AS orderName, c.Username AS Username,
                a.Create_Date AS Create_Date, a.PurOrder_Total AS PurOrder_Total, a.Status AS Status, a.Supplier_ID AS Supplier_ID,
                a.PurOrder_Total AS PurOrder_Total, a.PurOrder_Payment AS PurOrder_Payment, a.PurOrder_Payment AS PurOrder_Payment
                FROM 
                t_'.PURORDER.' as a 
                LEFT JOIN t_'
            .BETWEENUNIT.' as b
                ON a.Supplier_ID=b.PK_BU_ID
                LEFT JOIN t_'
            .USER.' as c
                ON a.Creator_ID=c.PK_User_ID
                '.$where.'
                '.$order.'
                ';//var_dump($sql);exit;
        return $this->cache_model->load_sql(PURORDER,$sql,2);
    }


    //报价列表、销售列表
    public function saleOrderList($where='',$order=''){
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*,c.Username AS Username, b.Name AS Customer_Name, a.Name AS orderName
                FROM 
                t_'.SALEORDER.' as a 
                LEFT JOIN t_'
            .BETWEENUNIT.' as b
                ON a.Customer_ID=b.PK_BU_ID
                LEFT JOIN t_'
            .USER.' as c
                ON a.Creator_ID=c.PK_User_ID
                '.$where.'
                '.$order.'
                ';
        return $this->cache_model->load_sql(SALEORDER,$sql,2);
    }


    //日志列表
    public function logList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Username as username  FROM 
            t_'.LOGBOOK.' as a 
            LEFT JOIN t_'
            .USER.' as b
            ON a.FK_Operator_ID=b.PK_User_ID
            '.$where.'
            '.$order.'
            ';
        return $this->cache_model->load_sql(LOGBOOK,$sql,2);
    }


    //调用存储过程更新成本，return:code 0失败，1成功
    public function updateCost($invsaid){
        try {
            $sql = "CALL P_CostEstimate($invsaid, @CostResult, @TotalCost)";
            if($this->db->query($sql) == false){
                return array('code' => 0);
            }
            $sql = 'select @CostResult';
            $result = $this->db_sql($sql,2);
            return  array('code' => 1, 'data' =>$result[0]);
        }catch (Exception $exception){
            return array('execute' => false, 'code' => $exception->getCode(), 'msg' => $exception->getMessage());
        }
    }

    //调用存储过程统计billDate前一天16:00-当天16:00销售订单商品所需物料数量
    public function purchasePlan($billDate){
        try {
            $sql = "CALL P_MPS($billDate,@PlanRes);";
            if($this->db->query($sql) == false){
                return array('code' => 0);
            }
            $sql = 'select @PlanRes';
            $result = $this->db_sql($sql,2);
            return  array('code' => 1, 'data' =>$result[0]);
        }catch (Exception $exception){
            return array('code' => 0, 'errorCode' => $exception->getCode(), 'msg' => $exception->getMessage());
        }
    }

    //工作中心列表
    public function workcenterList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Username as headName  FROM 
            t_'.WORK_CERTER.' as a 
            LEFT JOIN t_'
            .USER.' as b
            ON a.Head_ID=b.PK_User_ID
            '.$where.'
            '.$order.'
            ';//var_dump($sql);exit;
        return $this->cache_model->load_sql(WORK_CERTER,$sql,2);
    }

    //地区分类列表
    public function areaList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Name as upareaName  FROM 
            t_'.AREA.' as a 
            LEFT JOIN t_'
            .AREA.' as b
            ON a.UpArea_ID=b.PK_Area_ID
            '.$where.'
            '.$order.'
            ';
        return $this->cache_model->load_sql(AREA,$sql,2);
    }

    //部门列表
    public function departmentList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Username as headName  FROM 
            t_'.DEPARTMENT.' as a 
            LEFT JOIN t_'
            .USER.' as b
            ON a.Head_ID=b.PK_User_ID
            '.$where.'
            '.$order.'
            ';
        return $this->cache_model->load_sql(DEPARTMENT,$sql,2);
    }

    //往来单位列表
    public function betweenunitList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*,if(a.Industry_ID =0,"其他", c.Name) AS industry, b.name AS area
                FROM 
                t_'.BETWEENUNIT.' as a 
                LEFT JOIN t_'
            .AREA.' as b
                ON a.Area_ID=b.PK_Area_ID
                LEFT JOIN t_'
            .INDUSTRY.' as c
                ON a.Industry_ID=c.PK_Industry_ID
                '.$where.'
                '.$order.'
                ';
        return $this->cache_model->load_sql(BETWEENUNIT,$sql,2);
    }

    //仓库列表
    public function stockList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Username as headName  FROM 
            t_'.STOCK.' as a 
            LEFT JOIN t_'
            .USER.' as b
            ON a.Head_ID=b.PK_User_ID
            '.$where.'
            '.$order.'
            ';
        return $this->cache_model->load_sql(STOCK,$sql,2);
    }

    //用户列表
    public function userList($where='',$order='') {
        $where = $where ? 'where (1=1) '.$where : '';
        $sql = 'SELECT a.*, b.Name as deptName FROM 
            t_'.USER.' as a 
            LEFT JOIN t_'
            .DEPARTMENT.' as b
            ON a.Part_ID=b.PK_Dept_ID
            '.$where.'
            '.$order.'
            ';
        return $this->cache_model->load_sql(STOCK,$sql,2);
    }

}
?>