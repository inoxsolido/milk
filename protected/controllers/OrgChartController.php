<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrgChartController
 *
 * @author Ball
 */
class OrgChartController extends Controller {
    //put your code here
    
    public function actionJSONDivision(){
        if(isset($_POST['year'])){
            $year = $_POST['year']-543;
        
            //check year duplicate
            $result = Yii::app()->db->createCommand("SELECT `year` FROM tb_org_struct WHERE `year` = $year")->queryScalar();
            if($result){
                echo json_encode(['error'=>'dup']);
            }else{
                $output = [
                    'error' => 'none'
                ];
                $alldiv = TbDivision::model()->findAll(["condition" => "division_level <= 3", "order" => "erp_id ASC" ]);
                $i[0] = intval(0);
                $i[1] = intval(0);
                $i[2] = intval(0);
                $rec = [];
                foreach ($alldiv as $div){
                    /* @var $div TbDivision */
                    $rec[$div->division_level][$i[$div->division_level-1]]['id'] = $div->division_id;
                    $rec[$div->division_level][$i[$div->division_level-1]++]['name'] = $div->division_name;
                    
                }
                $output['divs'] = $rec;
                echo json_encode($output);
            }
        }
    }
    
    public function actionJSONDivRelate(){
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            $result = Yii::app()->db->createCommand(
                    "SELECT dc.division_id AS child, dc.division_level AS child_lv, 
                        dp.division_id AS parent, dp.division_level AS parent_lv 
                        FROM tb_org_struct os 
                        INNER JOIN tb_division dp ON os.parent_division_id = dp.division_id  
                        INNER JOIN tb_division dc ON os.child_division_id = dc.division_id  
                        WHERE `year` = $year 
                        ORDER BY parent_lv DESC, dc.erp_id"
                    )->queryAll();
            if(!count($result)){
                echo json_encode(['error' => 'no year']);
            }else{
                $output['error']='no none';
            $rec = [];
                foreach($result AS $row){
                    $rec[] = (["child" => ["id"=>$row['child'], "lv"=>$row['child_lv']],
                        "parent" => ["id"=>$row['parent'], "lv"=>$row['parent_lv']]
                        ]);
                }
                $output["old"] = $rec;
                echo json_encode($output);
            }
        }
    }
    
    public function actionFillOrgYear(){
        $result = Yii::app()->db->createCommand("SELECT `year`, AVG(IFNULL(approve_lv,0)) AS approve_lv 
            FROM tb_approve GROUP BY `year`")->queryAll();
        if(!count($result)){//if not found
            ?><tr class="danger"><td colspan="3"><hr4 class="text-danger">ยังไม่มีการเพิ่มปีงบประมาณ</hr4></td></tr><?php
        }else{
            foreach ($result as $r){
                //ตรวจสอบว่าเพิ่มโครงสร้างองค์กรไปหรือยัง
                $check = (TbOrgStruct::model()->find(['condition'=>"year = ".$r['year']]));
                //$check = TRUE;
                ?><tr>
                    <td><?=$r['year']+543?></td>
                    <td><?=$r['approve_lv']!=9?'ยังไม่แล้วเสร็จ':'เสร็จสิ้นแล้ว'?></td>
                    <td><div class="btn-group-sm" style="width:100%" year="<?=$r['year']?>">
                            <?php if(!$check): ?>
                            <button class="btn btn-success assign" style="width:100%; float:left;">กำหนด <i class="glyphicon glyphicon-plus"></i></button>
                            <?php else: ?>
                            <button class="btn btn-warning edit" style="width:50%; float:left;">แก้ไข <i class="glyphicon glyphicon-edit"></i></button><?php
                            ?><button class="btn btn-danger cancel" style="width:50%; float:left;">ยกเลิก <i class="glyphicon glyphicon-trash"></i></button>
                            <?php endif; ?>
                        </div></td>
                </tr><?php
            }
        }
    }
    
    public function actionSaveOrgChart(){
        if(isset($_POST['year'])&&isset($_POST['fdata'])){
            $year = $_POST['year'];
            $fdata = $_POST['fdata'];
            $del = FALSE;
            if(Yii::app()->db->createCommand("SELECT `year` FROM tb_org_struct WHERE `year` = $year")->queryScalar()){
                // if found year then delete them all
                $del = TRUE;
            }
            
            $transaction = Yii::app()->db->beginTransaction();
            try{
                if($del) 
                    TbOrgStruct::model()->deleteAll("year = $year");
                foreach($fdata AS $row){
                    $model = new TbOrgStruct();
                    if($model->isNewRecord){
                        $model->year = $year;
                        $model->parent_division_id = $row['par'];
                        $model->child_division_id = $row['id'];
                        $model->save(true);
                    }else{
                        throw new Exception("model error");
                    }
                }
                $transaction->commit();
                echo 'ok';
            } catch (Exception $ex) {
                $error = $ex->getMessage();
                $transaction->rollback();
                echo "error: $error";
            }
            
        }
    }
    
    public function actionDeleteOrgChart(){
        if(isset($_POST['year'])){
            $year = $_POST['year'];
            $result = TbOrgStruct::model()->deleteAll("year = $year");
            echo $result?'ok':'not';
        }
    }
    
    
}
