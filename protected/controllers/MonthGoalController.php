<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MonthGoalController
 *
 * @author Ball
 */
class MonthGoalController extends Controller {
    //put your code here
    public function actionFillYear(){
        //หาปีที่สามารถใช้กรอกข้อมูลได้
        $sql = "SELECT ap.year, ap.round, os.child_division_id,  ap.approve_lv
            FROM tb_approve ap 
            INNER JOIN tb_acc_year ay ON ap.year = ay.year 
            INNER JOIN tb_org_struct os ON ap.year = os.year  
            WHERE (ap.year, ap.round) in (SELECT `year`, MAX(round) FROM tb_approve GROUP BY `year`) 
            GROUP BY ap.year, ap.round 
            HAVING approve_lv < 9
            ORDER BY ap.year
            ";
        $yearlist = Yii::app()->db->createCommand($sql)->queryAll();
        print_r($yearlist);
        if(!empty($yearlist)){
            foreach($yearlist as $year){
                ?><option year='<?=$year['year']?>' round='<?=$year['round']?>' value='<?=$year['year']?>'><?=$year['year']+543?> รอบที่ <?=$year['round']?></option><?php
            }
            $output['error']="none";
            $output['data'] = $yearlist;
            echo json_encode($output);
        }else{
            $output['error']="empty";
            echo json_encode($output);
        }
        
        
    }
}
