<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Resource
 *
 * @author Ball
 */
class Resource extends CApplicationComponent
{

    public function getYearResource()
    {
        $sql = "SELECT ay.`year`, AVG(IFNULL(approve_lv,0)) as approve "
                . "FROM tb_acc_year ay "
                . "LEFT JOIN tb_approve ap ON ay.`year` = ap.`year` "
                . "LEFT JOIN tb_division dp ON ap.division_id = dp.division_id AND dp.division_level < 3 "
                . "GROUP BY ay.`year` "
                . "HAVING `year` IS NOT NULL AND approve != 9 "
                . "ORDER BY `year` ASC";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        if (!empty($result))
        {
            //calc Round
            $approve = $result['approve'];
            $round = 1;
            if ($approve < 4)
                $round = 1;
            else if ($approve < 8)
                $round = 2;
            else if ($approve == 8)
                $round = 3;
            else
                $round = 4;
            $result['round'] = $round;
        }
        return $result;
    }

    public function getResourceOfYear($year)
    {
        $sql = "SELECT ay.`year`, AVG(IFNULL(approve_lv,0)) as approve "
                . "FROM tb_acc_year ay "
                . "LEFT JOIN tb_approve ap ON ay.`year` = ap.`year` "
                . "LEFT JOIN tb_division dp ON ap.division_id = dp.division_id AND dp.division_level < 3 "
                . "GROUP BY ay.`year` "
                . "HAVING `year` = $year "
                . "ORDER BY `year` ASC";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        return $result;
    }

    public function getApproveOfDep($depid, $year)
    {
        /*$sql = "SELECT approve_lv as approve "
                . "FROM tb_acc_year ay "
                . "JOIN tb_approve ap ON ay.`year` = ap.`year` "
                . "JOIN tb_division dp ON ap.division_id = dp.division_id AND dp.division_level < 3 AND dp.division_id = $depid "
                . "WHERE ay.`year` = $year AND approve_lv != 9 "
                . "ORDER BY ay.`year` ASC";*/
        $sql = "SELECT approve_lv as approve FROM tb_approve ap WHERE ap.`year` = $year AND division_id = $depid";
        return Yii::app()->db->createCommand($sql)->queryScalar();
    }

    public function getVersionOfDep($depid, $year)
    {
        $sql = "SELECT MAX(v.version) as maxver\n"
                . "FROM `tb_month_goal` mg\n"
                . "INNER JOIN tb_version v ON v.month_goal_id = mg.month_goal_id\n"
                . "WHERE division_id = $depid AND mg.`year` = $year";
        return Yii::app()->db->createCommand($sql)->queryScalar();
    }
    public function getAllVersionOfDep($depid, $year){
        $sql = "SELECT DISTINCT v.version\n"
                . "FROM `tb_month_goal` mg\n"
                . "INNER JOIN tb_version v ON v.month_goal_id = mg.month_goal_id\n"
                . "WHERE division_id = $depid AND mg.`year` = $year";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

}
