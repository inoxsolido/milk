<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExportController
 *
 * @author Ball
 */
class ExportController extends Controller {

    public function actionT() {
        $abc = "ABCDEFGHIJKLMN";
        echo $abc[2];
    }

    public function actionDep() {/*
      if(!(isset($_POST['cid']))){
      //no department id sent
      echo 'Parameter fault!';
      return FALSE;
      } */
        Yii::import('application.extensions.PHPExcel');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->removeSheetByIndex(0);
        $worksheet1 = $objPHPExcel->createSheet();
        $worksheet2 = $objPHPExcel->createSheet();
        $worksheet1->setTitle("Sheet1 hola");
        $worksheet2->setTitle("Sheet2 holahola");
        $worksheet1->mergeCells("A5:B11");
        $worksheet2->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        /* @var $worksheet2 PHPExcel_Worksheet */
        $worksheet1->setCellValueByColumnAndRow(5, 6, "asdasdasd");
        $worksheet1->mergeCellsByColumnAndRow('0', '1', '0', '4');
        $worksheet1->setCellValueByColumnAndRow(0, 1, "Test merge!");
        $worksheet1->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->set;
        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        return FALSE;
    }

    /*public function actionD() {
        $did = Yii::app()->request->getParam("id", NULL);
        $year = Yii::app()->request->getParam("y", NULL);
        $round = Yii::app()->request->getParam('ro', NULL);
        if ($did == NULL) {
            echo 'no id select';
            return;
        } else if ($year == NULL) {
            echo 'no year select';
            return;
        } else if ($round == NULL) {
            echo 'no round select';
            return;
        }
        //check exist
        $checkdiv = TbApprove::model()->find("year = $year AND division_id = $did AND approve_lv = 1");
        $checkinfo = TbMonthGoal::model()->find("division_id = $did AND year = $year");
        if (!($checkdiv && $checkinfo)) {
            echo 'no data';
            return;
        }
        //ok
        $col = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        Yii::import('application.extensions.PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->removeSheetByIndex(0);
        $sheet1 = $objPHPExcel->createSheet();
        $dinfo = TbDivision::model()->findByPk($did);
        $yearw = $year + 543;
        $yearwb = $yearw - 1;
        $sheet1->setTitle($dinfo->division_name);
        //set header
        $sheet1->setCellValue('A1', "เป้าหมาย รายเดือน งบประมาณปี $yearw")->getStyle()->getFont()->setBold(TRUE);
        $sheet1->getCell('A1')->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("A2", "$dinfo->division_name")->getStyle()->getFont()->setBold(TRUE);
        $sheet1->getCell('A2')->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("A4", "รายการ")->mergeCells("A4:A5")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("B4", "อนุมัติ")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("B5", "ทั้งปี")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("C4", "$yearwb")->mergeCells("C4:F4")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("G4", "$yearw")->mergeCells("G4:R4")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("S4", "รวมทั้งสิ้น")->mergeCells("S4:S5")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("C5", "ต.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("D5", "พ.ย.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("E5", "ธ.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("F5", "รวมไตรมาส 1")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("G5", "ม.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("H5", "ก.พ.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("I5", "มี.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("J5", "รวมไตรมาศ 2")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("K5", "เม.ย.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("L5", "พ.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("M5", "มิ.ย.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("N5", "รวมไตรมาศ 3")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("O5", "ก.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("P5", "ส.ค.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("Q5", "ก.ย.")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->setCellValue("R5", "รวมไตรมาศ 4")->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        foreach (range('C', 'S') as $col_id) {
            $sheet1->getColumnDimension($col_id)->setWidth(15);
        }
        $sheet1->getColumnDimension("A")->setWidth(43.38);
        $sheet1->getColumnDimension("B")->setWidth(16.63);
        //setdata
        try {
            $lastrow = 5;
            $sumtype = [];
            $sumgroup = [];
            $suma1 = [];
            $type = TbType::model()->findAll();
            foreach ($type as $t) {
                $t1 = ++$lastrow;
                $sheet1->setCellValue("A$t1", $t->type_name);
                $sheet1->getStyle("A$t1")->getAlignment()->setHorizontal('left');
                $sheet1->getStyle("A$t1")->getFont()->setUnderline('single')->setBold(TRUE);
                $group = TbGroup::model()->findAll("type_id = $t->type_id");
                foreach ($group as $g) {
                    $g1 = $lastrow;
                    if($g->group_id != 1){
                        $g1 = ++$lastrow;
                        $sheet1->setCellValue("A$g1", $g->group_name);
                        $sheet1->getStyle("A$g1")->getAlignment()->setHorizontal('left');
                    }
                    $accs_lv1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id IS NULL ORDER BY `order`, `acc_name`")->queryAll();
                    foreach ($accs_lv1 as $a1) {
                        $a1 = (object) $a1;
                        $s1 = ++$lastrow;
                        $sheet1->setCellValue("A$s1", $a1->acc_name);
                        //check value
//            $vls1 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a1->acc_id ORDER BY quarter, month_id");
                        $valm1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a1->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                        $lim1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a1->acc_id AND round = $round")->queryRow();
                        if (count((array) $valm1)) {//มีค่าให้ใส่ค่า
                            if (!isset($lim1->year_target))
                                die('1');
                            $sheet1->setCellValue("B$lastrow", $lim1->year_target);
                            $sheet1->getStyle("B$lastrow")->getAlignment()->setHorizontal('left');
                            //ไตรมาศ
                            $trin = 0;
                            $tri = array();
                            $ci = 2;
                            foreach ($valm1 as $vm1) {
                                //12 month
                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm1['value']);
                                if (($ci - 1) % 4 == 0) {
                                    //sum
                                    $trin+=1;
                                    $left = $ci - 3;
                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                    array_push($tri, "$col[$ci]$lastrow");
                                    if ($trin == 4) {
                                        $sum = "";
                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                            $sum .= "+$tri[$i]";
                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                        }
                                    }


                                    $ci+=1;
                                }
                            }
                        } else {//หาระดับถัดไป 
                            $accs_lv2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a1->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                            foreach ($accs_lv2 as $a2) {
                                $a2 = (object) $a2;
                                $s2 = ++$lastrow;
                                $sheet1->setCellValue("A$s2", $a2->acc_name);
                                //check value
//            $vls2 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a2->acc_id ORDER BY quarter, month_id");
                                $valm2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a2->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                $lim2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a2->acc_id AND round = $round")->queryRow();
                                if (count((array) $valm2)) {//มีค่าให้ใส่ค่า
                                    if (!isset($lim2->year_target))
                                        die(print_r($lim2));
                                    $sheet1->setCellValue("B$lastrow", $lim2->year_target);
                                    //ไตรมาศ
                                    $trin = 0;
                                    $tri = array();
                                    $ci = 2;
                                    foreach ($valm2 as $vm2) {
                                        //12 month
                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm2['value']);
                                        if (($ci - 1) % 4 == 0) {
                                            //sum
                                            $trin+=1;
                                            $left = $ci - 3;
                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                            array_push($tri, "$col[$ci]$lastrow");
                                            if ($trin == 4) {
                                                $sum = "";
                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                    $sum .= "+$tri[$i]";
                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                }
                                            }
                                            $ci+=1;
                                        }
                                    }
                                } else {//หาระดับถัดไป 
                                    $accs_lv3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a2->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                    foreach ($accs_lv3 as $a3) {
                                        $a3 = (object) $a3;
                                        $s3 = ++$lastrow;
                                        $sheet1->setCellValue("A$s3", $a3->acc_name);
                                        //check value
//            $vls3 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a3->acc_id ORDER BY quarter, month_id");
                                        $valm3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a3->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                        $lim3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a3->acc_id AND round = $round")->queryRow();
                                        if (count((array) $valm3)) {//มีค่าให้ใส่ค่า
                                            if (!isset($lim3->year_target))
                                                die('3');
                                            $sheet1->setCellValue("B$lastrow", $lim3->year_target);
                                            //ไตรมาศ
                                            $trin = 0;
                                            $tri = array();
                                            $ci = 2;
                                            foreach ($valm3 as $vm3) {
                                                //12 month
                                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm3['value']);
                                                if (($ci - 1) % 4 == 0) {
                                                    //sum
                                                    $trin+=1;
                                                    $left = $ci - 3;
                                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                    array_push($tri, "$col[$ci]$lastrow");
                                                    if ($trin == 4) {
                                                        $sum = "";
                                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                                            $sum .= "+$tri[$i]";
                                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                        }
                                                    }
                                                    $ci+=1;
                                                }
                                            }
                                        } else {//หาระดับถัดไป 
                                            $accs_lv4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a3->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                            foreach ($accs_lv4 as $a4) {
                                                $a4 = (object) $a4;
                                                $s4 = ++$lastrow;
                                                $sheet1->setCellValue("A$s4", $a4->acc_name);
                                                //check value
//            $vls4 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a4->acc_id ORDER BY quarter, month_id");
                                                $valm4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a4->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                                $lim4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a4->acc_id AND round = $round")->queryRow();
                                                if (count((array) $valm4)) {//มีค่าให้ใส่ค่า
                                                    if (!isset($lim4->year_target))
                                                        die('4');
                                                    $sheet1->setCellValue("B$lastrow", $lim4->year_target);
                                                    //ไตรมาศ
                                                    $trin = 0;
                                                    $tri = array();
                                                    $ci = 2;
                                                    foreach ($valm4 as $vm4) {
                                                        //12 month
                                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm4['value']);
                                                        if (($ci - 1) % 4 == 0) {
                                                            //sum
                                                            $trin+=1;
                                                            $left = $ci - 3;
                                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                            array_push($tri, "$col[$ci]$lastrow");
                                                            if ($trin == 4) {
                                                                $sum = "";
                                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                                    $sum .= "+$tri[$i]";
                                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                                }
                                                            }
                                                            $ci+=1;
                                                        }
                                                    }
                                                } else {//หาระดับถัดไป 
                                                }
                                                if ($a4->hasSum) {//sum คอลัม ทั้งแถว
                                                    $e = $lastrow + 1;
                                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a4->acc_name);
                                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                                    for ($i = 1; $i <= 18; $i++) {
                                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s4:$col[$i]$lastrow)");
                                                    }
                                                    $lastrow+=1;
                                                }
                                            }
                                        }
                                        if ($a3->hasSum) {//sum คอลัม ทั้งแถว
                                            $e = $lastrow + 1;
                                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a3->acc_name);
                                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                            for ($i = 1; $i <= 18; $i++) {
                                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s3:$col[$i]$lastrow)");
                                            }
                                            $lastrow+=1;
                                        }
                                    }
                                }
                                if ($a2->hasSum) {//sum คอลัม ทั้งแถว
                                    $e = $lastrow + 1;
                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a2->acc_name);
                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                    for ($i = 1; $i <= 18; $i++) {
                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s2:$col[$i]$lastrow)");
                                    }
                                    $lastrow+=1;
                                }
                            }
                        }
                        if ($a1->hasSum) {//sum คอลัม ทั้งแถว
                            $e = $lastrow + 1;
                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a1->acc_name);
                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                            for ($i = 1; $i <= 18; $i++) {
                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s1:$col[$i]$lastrow)");
                            }
                            $lastrow+=1;
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            die(print_r($ex));
        }
        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }*/
    
    public function actionMg(){
        $did = Yii::app()->request->getParam("id", NULL);
        $year = Yii::app()->request->getParam("y", NULL);
        $round = Yii::app()->request->getParam('ro', NULL);
        if ($did == NULL) {
            echo 'no id select';
            return;
        } else if ($year == NULL) {
            echo 'no year select';
            return;
        } else if ($round == NULL) {
            echo 'no round select';
            return;
        }
        
        //check exist
        $checkdiv = TbApprove::model()->find("year = $year AND division_id = $did AND approve_lv > 0");
        $checkinfo = TbMonthGoal::model()->find("division_id = $did AND year = $year");
        if (!($checkdiv && $checkinfo)) {
            echo 'no data';
            return;
        }
        //ok
        $col = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        
        Yii::import('application.extensions.PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(join(DIRECTORY_SEPARATOR, array(Yii::app()->basePath,'extensions','xlstemplates','general_monthgoal.xls')));

        $style_text_total = [
            "alignment"=>["horizontal"=>'center',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_num_total = [
            "alignment"=>["horizontal"=>'right',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_num_gen = [
            "alignment"=>["horizontal"=>'right',"vertical"=>'center'],
            //"font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>[
                'bottom'=> PHPExcel_Style_Border::BORDER_DOTTED,
                'left'=>PHPExcel_Style_Border::BORDER_MEDIUM,
                'right'=>PHPExcel_Style_Border::BORDER_MEDIUM
                ]
        ];
        $style_text_left = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            //"font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_text_left_bold = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_text_left_bold_underline = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,'underline'=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $sheet1 = $objPHPExcel->getSheet(0);
        $sheet_temp = clone $sheet1;
        
        $sheet1->setTitle(TbDivision::model()->findByPk($did));
        //setdata
        try {
            $lastrow = 5;
            $sumtype = [];
            $sumgroup = [];
            $suma1 = [];
            $type = TbType::model()->findAll();
            foreach ($type as $t) {
                $t1 = ++$lastrow;
                $sheet1->setCellValue("A$t1", $t->type_name);
                $sheet1->getStyle("A$t1")->getAlignment()->setHorizontal('left');
                $sheet1->getStyle("A$t1")->getFont()->setUnderline('single')->setBold(TRUE);
                $group = TbGroup::model()->findAll("type_id = $t->type_id");
                foreach ($group as $g) {
                    $g1 = $lastrow;
                    if($g->group_id != 1){
                        $g1 = ++$lastrow;
                        $sheet1->setCellValue("A$g1", $g->group_name);
                        $sheet1->getStyle("A$g1")->applyFromArray($style_text_left_bold);
                    }
                    $accs_lv1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id IS NULL ORDER BY `order`, `acc_name`")->queryAll();
                    foreach ($accs_lv1 as $a1) {
                        $a1 = (object) $a1;
                        
                        $s1 = ++$lastrow;
                        $sheet1->setCellValue("A$s1", $a1->acc_name);
                        $sheet1->getStyle("A$s1")->applyFromArray($style_text_left_bold);
                        //check value
//            $vls1 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a1->acc_id ORDER BY quarter, month_id");
                        $valm1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a1->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                        $lim1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a1->acc_id AND round = $round")->queryRow();
                        if (count((array) $valm1)) {//มีค่าให้ใส่ค่า
                            if (!isset($lim1->year_target))
                                die('1');
                            $sheet1->setCellValue("B$lastrow", $lim1->year_target);
                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                            //ไตรมาศ
                            $trin = 0;
                            $tri = array();
                            $ci = 2;
                            foreach ($valm1 as $vm1) {
                                //12 month
                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm1['value']);
                                if (($ci - 1) % 4 == 0) {
                                    //sum
                                    $trin+=1;
                                    $left = $ci - 3;
                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                    array_push($tri, "$col[$ci]$lastrow");
                                    if ($trin == 4) {
                                        $sum = "";
                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                            $sum .= "+$tri[$i]";
                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                        }
                                    }
                                    $ci+=1;
                                }
                            }
                        } else {//หาระดับถัดไป 
                            $accs_lv2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a1->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                            foreach ($accs_lv2 as $a2) {
                                $a2 = (object) $a2;
                                $s2 = ++$lastrow;
                                $sheet1->setCellValue("A$s2", "   $a2->acc_name");
                                $sheet1->getStyle("A$s2")->applyFromArray($style_text_left);
                                //check value
//            $vls2 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a2->acc_id ORDER BY quarter, month_id");
                                $valm2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a2->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                $lim2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a2->acc_id AND round = $round")->queryRow();
                                if (count((array) $valm2)) {//มีค่าให้ใส่ค่า
                                    if (!isset($lim2->year_target))
                                        die(print_r($lim2));
                                    $sheet1->setCellValue("B$lastrow", $lim2->year_target);
                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                    //ไตรมาศ
                                    $trin = 0;
                                    $tri = array();
                                    $ci = 2;
                                    foreach ($valm2 as $vm2) {
                                        //12 month
                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm2['value']);
                                        if (($ci - 1) % 4 == 0) {
                                            //sum
                                            $trin+=1;
                                            $left = $ci - 3;
                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                            array_push($tri, "$col[$ci]$lastrow");
                                            if ($trin == 4) {
                                                $sum = "";
                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                    $sum .= "+$tri[$i]";
                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                }
                                            }
                                            $ci+=1;
                                        }
                                    }
                                } else {//หาระดับถัดไป 
                                    $accs_lv3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a2->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                    foreach ($accs_lv3 as $a3) {
                                        $a3 = (object) $a3;
                                        $s3 = ++$lastrow;
                                        $sheet1->setCellValue("A$s3", "      $a3->acc_name");
                                        $sheet1->getStyle("A$s3")->applyFromArray($style_text_left);
                                        //check value
//            $vls3 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a3->acc_id ORDER BY quarter, month_id");
                                        $valm3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a3->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                        $lim3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a3->acc_id AND round = $round")->queryRow();
                                        if (count((array) $valm3)) {//มีค่าให้ใส่ค่า
                                            if (!isset($lim3->year_target))
                                                die('3');
                                            $sheet1->setCellValue("B$lastrow", $lim3->year_target);
                                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                            //ไตรมาศ
                                            $trin = 0;
                                            $tri = array();
                                            $ci = 2;
                                            foreach ($valm3 as $vm3) {
                                                //12 month
                                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm3['value']);
                                                if (($ci - 1) % 4 == 0) {
                                                    //sum
                                                    $trin+=1;
                                                    $left = $ci - 3;
                                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                    array_push($tri, "$col[$ci]$lastrow");
                                                    if ($trin == 4) {
                                                        $sum = "";
                                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                                            $sum .= "+$tri[$i]";
                                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                        }
                                                    }
                                                    $ci+=1;
                                                }
                                            }
                                        } else {//หาระดับถัดไป 
                                            $accs_lv4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a3->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                            foreach ($accs_lv4 as $a4) {
                                                $a4 = (object) $a4;
                                                $s4 = ++$lastrow;
                                                $sheet1->setCellValue("A$s4", "         $a4->acc_name");
                                                $sheet1->getStyle("A$s4")->applyFromArray($style_text_left);
                                                //check value
//            $vls4 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a4->acc_id ORDER BY quarter, month_id");
                                                $valm4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a4->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                                $lim4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a4->acc_id AND round = $round")->queryRow();
                                                if (count((array) $valm4)) {//มีค่าให้ใส่ค่า
                                                    if (!isset($lim4->year_target))
                                                        die('4');
                                                    
                                                    $sheet1->setCellValue("B$lastrow", $lim4->year_target);
                                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                                    //ไตรมาศ
                                                    $trin = 0;
                                                    $tri = array();
                                                    $ci = 2;
                                                    foreach ($valm4 as $vm4) {
                                                        //12 month
                                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm4['value']);
                                                        if (($ci - 1) % 4 == 0) {
                                                            //sum
                                                            $trin+=1;
                                                            $left = $ci - 3;
                                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                            array_push($tri, "$col[$ci]$lastrow");
                                                            if ($trin == 4) {
                                                                $sum = "";
                                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                                    $sum .= "+$tri[$i]";
                                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                                }
                                                            }
                                                            $ci+=1;
                                                        }
                                                    }
                                                } else {//หาระดับถัดไป 
                                                }
                                                if ($a4->hasSum) {//sum คอลัม ทั้งแถว
                                                    $e = $lastrow + 1;
                                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a4->acc_name);
                                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                                    for ($i = 1; $i <= 18; $i++) {
                                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s4:$col[$i]$lastrow)");
                                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                                    }
                                                    $lastrow+=1;
                                                }
                                            }
                                        }
                                        if ($a3->hasSum) {//sum คอลัม ทั้งแถว
                                            $e = $lastrow + 1;
                                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a3->acc_name);
                                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                            for ($i = 1; $i <= 18; $i++) {
                                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s3:$col[$i]$lastrow)");
                                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                            }
                                            $lastrow+=1;
                                        }
                                    }
                                }
                                if ($a2->hasSum) {//sum คอลัม ทั้งแถว
                                    $e = $lastrow + 1;
                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a2->acc_name);
                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                    for ($i = 1; $i <= 18; $i++) {
                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s2:$col[$i]$lastrow)");
                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                    }
                                    $lastrow+=1;
                                }
                            }
                        }
                        if ($a1->hasSum) {//sum คอลัม ทั้งแถว
                            $e = $lastrow + 1;
                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a1->acc_name);
                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                            $cola1 = [];
                            for ($i = 1; $i <= 18; $i++) {
                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s1:$col[$i]$lastrow)");
                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                array_push($cola1, "$col[$i]$e");
                            }
                            array_push($suma1,$cola1);
                            $lastrow+=1;
                        }
                    }
                    //รวมแต่ละงบ
                    if($g->group_id != 1){
                        //ข้ามรายได้ไป เพราะรายได้เป็นประเภท
                        $e = $lastrow + 1;
                        $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $g->group_name);
                        $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                        $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                        $value=['','','','','','','','','','','','','','','','','','',];
                        foreach($suma1 as $sa1){
                            for($i=0;$i<18;$i++){
                                $value[$i] .= "+".$sa1[$i];
                                
                            }
                        }
                        //print_r($value);return;
                        $colg1 = [];
                        for ($i = 1; $i <= 18; $i++) {
                            $sheet1->setCellValue("$col[$i]$e", '='.empty($value[$i-1])?intval(0):$value[$i-1]);
                            $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                            array_push($colg1, "$col[$i]$e");
                        }
                        array_push($sumgroup, $colg1);
                        $suma1 = [];
                        $lastrow+=1;
                        
                    }
                }
                //รวมแต่ละประเภท (รายได้/รายจ่าย)
                if($t->type_id == 1){
                    //ถ้าเป็นรายได้
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม".$namewithoutnumber."ทั้งสิ้น");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($suma1 as $sa1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= "+".$sa1[$i];

                        }
                    }
                    $colt = [];
                    
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }else{
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($sumgroup as $sg1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= '+'.$sg1[$i];
                        }
                    }
                    $colt = [];
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }
            }
            //รายได้-รายจ่าย
            $e = $lastrow + 1;
            $sheet1->setCellValue("A$e", "รายได้-รายจ่าย");
            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
            $value=['','','','','','','','','','','','','','','','','','',];
            foreach($sumtype as $st1){
                for($i=0;$i<18;$i++){
                    $value[$i] .= '+'.$st1[$i];
                }
            }
            for ($i = 1; $i <= 18; $i++) {
                $sheet1->setCellValue("$col[$i]$e", "=".$sumtype[0][$i-1].'-'.$sumtype[1][$i-1]);
                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
            }
            $lastrow+=1;
        } catch (Exception $ex) {
            die('<pre>'.print_r($ex).'</pre>');
        }
        

        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function actionDiv(){
        $did = Yii::app()->request->getParam("id", NULL);
        $year = Yii::app()->request->getParam("y", NULL);
        $round = Yii::app()->request->getParam('ro', NULL);
        
        if ($did == NULL) {
            echo 'no id select';
            return;
        } else if ($year == NULL) {
            echo 'no year select';
            return;
        } else if ($round == NULL) {
            echo 'no round select';
            return;
        }
        
        //check exist
        
        $checkdiv = Yii::app()->db->createCommand("SELECT * FROM tb_approve ap "
                . "INNER JOIN tb_division d ON ap.division_id = d.division_id "
                . "WHERE d.parent_division = $did AND `year` = $year")->queryAll();
        
        $checkinfo = Yii::app()->db->createCommand("SELECT ay.`year`, dc.division_id as cid, dc.division_name as cname, approve_lv as approve, MAX(month_goal_id) as monthgoal, erp_id 
FROM tb_division dc  
JOIN tb_approve ap ON ap.division_id = dc.division_id 
JOIN tb_acc_year ay ON ay.`year` = ap.`year` 
INNER JOIN tb_month_goal mg ON mg.division_id = dc.division_id AND mg.acc_id = ay.acc_id 
WHERE ay.`year` = $year AND dc.parent_division = $did 
GROUP BY dc.division_id 
ORDER BY erp_id ASC")->queryAll();
        if (!($checkdiv && $checkinfo)) {
            echo 'no data';
            return;
        }
        unset($checkdiv);unset($checkinfo);
        
        $col = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        
        Yii::import('application.extensions.PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(join(DIRECTORY_SEPARATOR, array(Yii::app()->basePath,'extensions','xlstemplates','general_monthgoal.xls')));

        $style_text_total = [
            "alignment"=>["horizontal"=>'center',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_num_total = [
            "alignment"=>["horizontal"=>'right',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_num_gen = [
            "alignment"=>["horizontal"=>'right',"vertical"=>'center'],
            //"font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            "border"=>[
                'bottom'=> PHPExcel_Style_Border::BORDER_DOTTED,
                'left'=>PHPExcel_Style_Border::BORDER_MEDIUM,
                'right'=>PHPExcel_Style_Border::BORDER_MEDIUM
                ]
        ];
        $style_text_left = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            //"font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_text_left_bold = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $style_text_left_bold_underline = [
            "alignment"=>["horizontal"=>'left',"vertical"=>'center'],
            "font"=>["bold"=>TRUE,'underline'=>TRUE,"size"=>16,"name"=>'Browallia New'],
            //"borders"=>['allborders'=>  PHPExcel_Style_Border::BORDER_MEDIUM]
        ];
        $sheet1 = $objPHPExcel->getSheet(0);
        $sheet_temp = clone $sheet1;
        
        //ภาพรวมของฝ่าย
        try {
            $lastrow = 5;
            $sumtype = [];
            $sumgroup = [];
            $suma1 = [];
            $type = TbType::model()->findAll();
            foreach ($type as $t) {
                $t1 = ++$lastrow;
                $sheet1->setCellValue("A$t1", $t->type_name);
                $sheet1->getStyle("A$t1")->getAlignment()->setHorizontal('left');
                $sheet1->getStyle("A$t1")->getFont()->setUnderline('single')->setBold(TRUE);
                $group = TbGroup::model()->findAll("type_id = $t->type_id");
                foreach ($group as $g) {
                    $g1 = $lastrow;
                    if($g->group_id != 1){
                        $g1 = ++$lastrow;
                        $sheet1->setCellValue("A$g1", $g->group_name);
                        $sheet1->getStyle("A$g1")->applyFromArray($style_text_left_bold);
                    }
                    $accs_lv1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id IS NULL ORDER BY `order`, `acc_name`")->queryAll();
                    foreach ($accs_lv1 as $a1) {
                        $a1 = (object) $a1;
                        
                        $s1 = ++$lastrow;
                        $sheet1->setCellValue("A$s1", $a1->acc_name);
                        $sheet1->getStyle("A$s1")->applyFromArray($style_text_left_bold);
                        //check value
//            $vls1 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a1->acc_id ORDER BY quarter, month_id");
                        $valm1 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(`value`,0)) AS `value`, mg.month_id, quarter 
                            FROM tb_month_goal mg 
                            JOIN tb_month m ON m.month_id = mg.month_id 
                            JOIN tb_division d ON d.division_id = mg.division_id 
                            WHERE parent_division = $did AND `year` = $year AND acc_id = $a1->acc_id 
                            GROUP BY mg.month_id 
                            ORDER BY quarter, mg.month_id")->queryAll();
                        $lim1 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(year_target,0)) as year_target 
                            FROM tb_mg_limit mgl 
                            JOIN tb_division d ON d.division_id = mgl.division 
                            WHERE d.parent_division AND `year` = $year AND acc_id = $a1->acc_id AND round = $round")->queryRow();
                        if (count((array) $valm1)) {//มีค่าให้ใส่ค่า
                            if (!isset($lim1->year_target))
                                die('1');
                            $sheet1->setCellValue("B$lastrow", $lim1->year_target);
                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                            //ไตรมาศ
                            $trin = 0;
                            $tri = array();
                            $ci = 2;
                            foreach ($valm1 as $vm1) {
                                //12 month
                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm1['value']);
                                if (($ci - 1) % 4 == 0) {
                                    //sum
                                    $trin+=1;
                                    $left = $ci - 3;
                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                    array_push($tri, "$col[$ci]$lastrow");
                                    if ($trin == 4) {
                                        $sum = "";
                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                            $sum .= "+$tri[$i]";
                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                        }
                                    }
                                    $ci+=1;
                                }
                            }
                        } else {//หาระดับถัดไป 
                            $accs_lv2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a1->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                            foreach ($accs_lv2 as $a2) {
                                $a2 = (object) $a2;
                                $s2 = ++$lastrow;
                                $sheet1->setCellValue("A$s2", "   $a2->acc_name");
                                $sheet1->getStyle("A$s2")->applyFromArray($style_text_left);
                                //check value
//            $vls2 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a2->acc_id ORDER BY quarter, month_id");
                                $valm2 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(`value`,0)) AS `value`, mg.month_id, quarter 
                            FROM tb_month_goal mg 
                            JOIN tb_month m ON m.month_id = mg.month_id 
                            JOIN tb_division d ON d.division_id = mg.division_id 
                            WHERE parent_division = $did AND `year` = $year AND acc_id = $a2->acc_id 
                            GROUP BY mg.month_id 
                            ORDER BY quarter, mg.month_id")->queryAll();
                                $lim2 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(year_target,0)) as year_target 
                            FROM tb_mg_limit mgl 
                            JOIN tb_division d ON d.division_id = mgl.division 
                            WHERE d.parent_division AND `year` = $year AND acc_id = $a2->acc_id AND round = $round")->queryRow();
                                if (count((array) $valm2)) {//มีค่าให้ใส่ค่า
                                    if (!isset($lim2->year_target))
                                        die(print_r($lim2));
                                    $sheet1->setCellValue("B$lastrow", $lim2->year_target);
                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                    //ไตรมาศ
                                    $trin = 0;
                                    $tri = array();
                                    $ci = 2;
                                    foreach ($valm2 as $vm2) {
                                        //12 month
                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm2['value']);
                                        if (($ci - 1) % 4 == 0) {
                                            //sum
                                            $trin+=1;
                                            $left = $ci - 3;
                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                            array_push($tri, "$col[$ci]$lastrow");
                                            if ($trin == 4) {
                                                $sum = "";
                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                    $sum .= "+$tri[$i]";
                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                }
                                            }
                                            $ci+=1;
                                        }
                                    }
                                } else {//หาระดับถัดไป 
                                    $accs_lv3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a2->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                    foreach ($accs_lv3 as $a3) {
                                        $a3 = (object) $a3;
                                        $s3 = ++$lastrow;
                                        $sheet1->setCellValue("A$s3", "      $a3->acc_name");
                                        $sheet1->getStyle("A$s3")->applyFromArray($style_text_left);
                                        //check value
//            $vls3 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a3->acc_id ORDER BY quarter, month_id");
                                        $valm3 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(`value`,0)) AS `value`, mg.month_id, quarter 
                            FROM tb_month_goal mg 
                            JOIN tb_month m ON m.month_id = mg.month_id 
                            JOIN tb_division d ON d.division_id = mg.division_id 
                            WHERE parent_division = $did AND `year` = $year AND acc_id = $a3->acc_id 
                            GROUP BY mg.month_id 
                            ORDER BY quarter, mg.month_id")->queryAll();
                                        $lim3 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(year_target,0)) as year_target 
                            FROM tb_mg_limit mgl 
                            JOIN tb_division d ON d.division_id = mgl.division 
                            WHERE d.parent_division AND `year` = $year AND acc_id = $a3->acc_id AND round = $round")->queryRow();
                                        if (count((array) $valm3)) {//มีค่าให้ใส่ค่า
                                            if (!isset($lim3->year_target))
                                                die('3');
                                            $sheet1->setCellValue("B$lastrow", $lim3->year_target);
                                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                            //ไตรมาศ
                                            $trin = 0;
                                            $tri = array();
                                            $ci = 2;
                                            foreach ($valm3 as $vm3) {
                                                //12 month
                                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm3['value']);
                                                if (($ci - 1) % 4 == 0) {
                                                    //sum
                                                    $trin+=1;
                                                    $left = $ci - 3;
                                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                    array_push($tri, "$col[$ci]$lastrow");
                                                    if ($trin == 4) {
                                                        $sum = "";
                                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                                            $sum .= "+$tri[$i]";
                                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                        }
                                                    }
                                                    $ci+=1;
                                                }
                                            }
                                        } else {//หาระดับถัดไป 
                                            $accs_lv4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a3->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                            foreach ($accs_lv4 as $a4) {
                                                $a4 = (object) $a4;
                                                $s4 = ++$lastrow;
                                                $sheet1->setCellValue("A$s4", "         $a4->acc_name");
                                                $sheet1->getStyle("A$s4")->applyFromArray($style_text_left);
                                                //check value
//            $vls4 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a4->acc_id ORDER BY quarter, month_id");
                                                $valm4 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(`value`,0)) AS `value`, mg.month_id, quarter 
                            FROM tb_month_goal mg 
                            JOIN tb_month m ON m.month_id = mg.month_id 
                            JOIN tb_division d ON d.division_id = mg.division_id 
                            WHERE parent_division = $did AND `year` = $year AND acc_id = $a4->acc_id 
                            GROUP BY mg.month_id 
                            ORDER BY quarter, mg.month_id")->queryAll();
                                                $lim4 = (object) Yii::app()->db->createCommand("SELECT SUM(IFNULL(year_target,0)) as year_target 
                            FROM tb_mg_limit mgl 
                            JOIN tb_division d ON d.division_id = mgl.division 
                            WHERE d.parent_division AND `year` = $year AND acc_id = $a4->acc_id AND round = $round")->queryRow();
                                                if (count((array) $valm4)) {//มีค่าให้ใส่ค่า
                                                    if (!isset($lim4->year_target))
                                                        die('4');
                                                    
                                                    $sheet1->setCellValue("B$lastrow", $lim4->year_target);
                                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                                    //ไตรมาศ
                                                    $trin = 0;
                                                    $tri = array();
                                                    $ci = 2;
                                                    foreach ($valm4 as $vm4) {
                                                        //12 month
                                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm4['value']);
                                                        if (($ci - 1) % 4 == 0) {
                                                            //sum
                                                            $trin+=1;
                                                            $left = $ci - 3;
                                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                            array_push($tri, "$col[$ci]$lastrow");
                                                            if ($trin == 4) {
                                                                $sum = "";
                                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                                    $sum .= "+$tri[$i]";
                                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                                }
                                                            }
                                                            $ci+=1;
                                                        }
                                                    }
                                                } else {//หาระดับถัดไป 
                                                }
                                                if ($a4->hasSum) {//sum คอลัม ทั้งแถว
                                                    $e = $lastrow + 1;
                                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a4->acc_name);
                                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                                    for ($i = 1; $i <= 18; $i++) {
                                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s4:$col[$i]$lastrow)");
                                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                                    }
                                                    $lastrow+=1;
                                                }
                                            }
                                        }
                                        if ($a3->hasSum) {//sum คอลัม ทั้งแถว
                                            $e = $lastrow + 1;
                                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a3->acc_name);
                                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                            for ($i = 1; $i <= 18; $i++) {
                                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s3:$col[$i]$lastrow)");
                                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                            }
                                            $lastrow+=1;
                                        }
                                    }
                                }
                                if ($a2->hasSum) {//sum คอลัม ทั้งแถว
                                    $e = $lastrow + 1;
                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a2->acc_name);
                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                    for ($i = 1; $i <= 18; $i++) {
                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s2:$col[$i]$lastrow)");
                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                    }
                                    $lastrow+=1;
                                }
                            }
                        }
                        if ($a1->hasSum) {//sum คอลัม ทั้งแถว
                            $e = $lastrow + 1;
                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a1->acc_name);
                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                            $cola1 = [];
                            for ($i = 1; $i <= 18; $i++) {
                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s1:$col[$i]$lastrow)");
                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                array_push($cola1, "$col[$i]$e");
                            }
                            array_push($suma1,$cola1);
                            $lastrow+=1;
                        }
                    }
                    //รวมแต่ละงบ
                    if($g->group_id != 1){
                        //ข้ามรายได้ไป เพราะรายได้เป็นประเภท
                        $e = $lastrow + 1;
                        $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $g->group_name);
                        $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                        $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                        $value=['','','','','','','','','','','','','','','','','','',];
                        foreach($suma1 as $sa1){
                            for($i=0;$i<18;$i++){
                                $value[$i] .= "+".$sa1[$i];
                                
                            }
                        }
                        //print_r($value);return;
                        $colg1 = [];
                        for ($i = 1; $i <= 18; $i++) {
                            $sheet1->setCellValue("$col[$i]$e", '='.empty($value[$i-1])?intval(0):$value[$i-1]);
                            $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                            array_push($colg1, "$col[$i]$e");
                        }
                        array_push($sumgroup, $colg1);
                        $suma1 = [];
                        $lastrow+=1;
                        
                    }
                }
                //รวมแต่ละประเภท (รายได้/รายจ่าย)
                if($t->type_id == 1){
                    //ถ้าเป็นรายได้
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม".$namewithoutnumber."ทั้งสิ้น");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($suma1 as $sa1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= "+".$sa1[$i];

                        }
                    }
                    $colt = [];
                    
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }else{
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($sumgroup as $sg1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= '+'.$sg1[$i];
                        }
                    }
                    $colt = [];
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }
            }
            //รายได้-รายจ่าย
            $e = $lastrow + 1;
            $sheet1->setCellValue("A$e", "รายได้-รายจ่าย");
            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
            $value=['','','','','','','','','','','','','','','','','','',];
            foreach($sumtype as $st1){
                for($i=0;$i<18;$i++){
                    $value[$i] .= '+'.$st1[$i];
                }
            }
            for ($i = 1; $i <= 18; $i++) {
                $sheet1->setCellValue("$col[$i]$e", "=".$sumtype[0][$i-1].'-'.$sumtype[1][$i-1]);
                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
            }
            $lastrow+=1;
        } catch (Exception $ex) {
            die('<pre>'.print_r($ex).'</pre>');
        }
        
        //สร้าง sheet ของแผนกในฝ่าย
        try {
            
            //เขียนชื่อ title ของชีต
            
            
            $lastrow = 5;
            $sumtype = [];
            $sumgroup = [];
            $suma1 = [];
            $type = TbType::model()->findAll();
            foreach ($type as $t) {
                $t1 = ++$lastrow;
                $sheet1->setCellValue("A$t1", $t->type_name);
                $sheet1->getStyle("A$t1")->getAlignment()->setHorizontal('left');
                $sheet1->getStyle("A$t1")->getFont()->setUnderline('single')->setBold(TRUE);
                $group = TbGroup::model()->findAll("type_id = $t->type_id");
                foreach ($group as $g) {
                    $g1 = $lastrow;
                    if($g->group_id != 1){
                        $g1 = ++$lastrow;
                        $sheet1->setCellValue("A$g1", $g->group_name);
                        $sheet1->getStyle("A$g1")->applyFromArray($style_text_left_bold);
                    }
                    $accs_lv1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id IS NULL ORDER BY `order`, `acc_name`")->queryAll();
                    foreach ($accs_lv1 as $a1) {
                        $a1 = (object) $a1;
                        
                        $s1 = ++$lastrow;
                        $sheet1->setCellValue("A$s1", $a1->acc_name);
                        $sheet1->getStyle("A$s1")->applyFromArray($style_text_left_bold);
                        //check value
//            $vls1 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a1->acc_id ORDER BY quarter, month_id");
                        $valm1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a1->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                        $lim1 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a1->acc_id AND round = $round")->queryRow();
                        if (count((array) $valm1)) {//มีค่าให้ใส่ค่า
                            if (!isset($lim1->year_target))
                                die('1');
                            $sheet1->setCellValue("B$lastrow", $lim1->year_target);
                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                            //ไตรมาศ
                            $trin = 0;
                            $tri = array();
                            $ci = 2;
                            foreach ($valm1 as $vm1) {
                                //12 month
                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm1['value']);
                                if (($ci - 1) % 4 == 0) {
                                    //sum
                                    $trin+=1;
                                    $left = $ci - 3;
                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                    array_push($tri, "$col[$ci]$lastrow");
                                    if ($trin == 4) {
                                        $sum = "";
                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                            $sum .= "+$tri[$i]";
                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                        }
                                    }
                                    $ci+=1;
                                }
                            }
                        } else {//หาระดับถัดไป 
                            $accs_lv2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a1->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                            foreach ($accs_lv2 as $a2) {
                                $a2 = (object) $a2;
                                $s2 = ++$lastrow;
                                $sheet1->setCellValue("A$s2", "   $a2->acc_name");
                                $sheet1->getStyle("A$s2")->applyFromArray($style_text_left);
                                //check value
//            $vls2 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a2->acc_id ORDER BY quarter, month_id");
                                $valm2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a2->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                $lim2 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a2->acc_id AND round = $round")->queryRow();
                                if (count((array) $valm2)) {//มีค่าให้ใส่ค่า
                                    if (!isset($lim2->year_target))
                                        die(print_r($lim2));
                                    $sheet1->setCellValue("B$lastrow", $lim2->year_target);
                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                    //ไตรมาศ
                                    $trin = 0;
                                    $tri = array();
                                    $ci = 2;
                                    foreach ($valm2 as $vm2) {
                                        //12 month
                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm2['value']);
                                        if (($ci - 1) % 4 == 0) {
                                            //sum
                                            $trin+=1;
                                            $left = $ci - 3;
                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                            array_push($tri, "$col[$ci]$lastrow");
                                            if ($trin == 4) {
                                                $sum = "";
                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                    $sum .= "+$tri[$i]";
                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                }
                                            }
                                            $ci+=1;
                                        }
                                    }
                                } else {//หาระดับถัดไป 
                                    $accs_lv3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a2->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                    foreach ($accs_lv3 as $a3) {
                                        $a3 = (object) $a3;
                                        $s3 = ++$lastrow;
                                        $sheet1->setCellValue("A$s3", "      $a3->acc_name");
                                        $sheet1->getStyle("A$s3")->applyFromArray($style_text_left);
                                        //check value
//            $vls3 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a3->acc_id ORDER BY quarter, month_id");
                                        $valm3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a3->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                        $lim3 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a3->acc_id AND round = $round")->queryRow();
                                        if (count((array) $valm3)) {//มีค่าให้ใส่ค่า
                                            if (!isset($lim3->year_target))
                                                die('3');
                                            $sheet1->setCellValue("B$lastrow", $lim3->year_target);
                                            $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                            //ไตรมาศ
                                            $trin = 0;
                                            $tri = array();
                                            $ci = 2;
                                            foreach ($valm3 as $vm3) {
                                                //12 month
                                                $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm3['value']);
                                                if (($ci - 1) % 4 == 0) {
                                                    //sum
                                                    $trin+=1;
                                                    $left = $ci - 3;
                                                    $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                    $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                    array_push($tri, "$col[$ci]$lastrow");
                                                    if ($trin == 4) {
                                                        $sum = "";
                                                        for ($i = 0; $i < sizeof($tri); $i++) {
                                                            $sum .= "+$tri[$i]";
                                                            $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                        }
                                                    }
                                                    $ci+=1;
                                                }
                                            }
                                        } else {//หาระดับถัดไป 
                                            $accs_lv4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_acc_year ay JOIN tb_account ac ON ay.acc_id = ac.acc_id WHERE ac.group_id = $g->group_id AND `year` = $year AND ac.parent_acc_id = $a3->acc_id ORDER BY `order`, `acc_name`")->queryAll();
                                            foreach ($accs_lv4 as $a4) {
                                                $a4 = (object) $a4;
                                                $s4 = ++$lastrow;
                                                $sheet1->setCellValue("A$s4", "         $a4->acc_name");
                                                $sheet1->getStyle("A$s4")->applyFromArray($style_text_left);
                                                //check value
//            $vls4 = TbMonthGoal::model()->findAll("year = $year AND acc_id = $a4->acc_id ORDER BY quarter, month_id");
                                                $valm4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_month_goal mg JOIN tb_month m ON m.month_id = mg.month_id WHERE year = $year AND acc_id = $a4->acc_id ORDER BY quarter, mg.month_id")->queryAll();
                                                $lim4 = (object) Yii::app()->db->createCommand("SELECT * FROM tb_mg_limit mgl WHERE `year` = $year AND acc_id = $a4->acc_id AND round = $round")->queryRow();
                                                if (count((array) $valm4)) {//มีค่าให้ใส่ค่า
                                                    if (!isset($lim4->year_target))
                                                        die('4');
                                                    
                                                    $sheet1->setCellValue("B$lastrow", $lim4->year_target);
                                                    $sheet1->getStyle("B$lastrow")->applyFromArray($style_num_gen);
                                                    //ไตรมาศ
                                                    $trin = 0;
                                                    $tri = array();
                                                    $ci = 2;
                                                    foreach ($valm4 as $vm4) {
                                                        //12 month
                                                        $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                        $sheet1->setCellValueByColumnAndRow($ci++, $lastrow, $vm4['value']);
                                                        if (($ci - 1) % 4 == 0) {
                                                            //sum
                                                            $trin+=1;
                                                            $left = $ci - 3;
                                                            $sheet1->getStyleByColumnAndRow($ci, $lastrow)->applyFromArray($style_num_gen);
                                                            $sheet1->setCellValueByColumnAndRow($ci, $lastrow, "=SUM(" . $col[$left] . $lastrow . ':' . $col[$ci - 1] . $lastrow . ")");
                                                            array_push($tri, "$col[$ci]$lastrow");
                                                            if ($trin == 4) {
                                                                $sum = "";
                                                                for ($i = 0; $i < sizeof($tri); $i++) {
                                                                    $sum .= "+$tri[$i]";
                                                                    $sheet1->setCellValue('S' . $lastrow, "=$sum");
                                                                }
                                                            }
                                                            $ci+=1;
                                                        }
                                                    }
                                                } else {//หาระดับถัดไป 
                                                }
                                                if ($a4->hasSum) {//sum คอลัม ทั้งแถว
                                                    $e = $lastrow + 1;
                                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a4->acc_name);
                                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                                    for ($i = 1; $i <= 18; $i++) {
                                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s4:$col[$i]$lastrow)");
                                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                                    }
                                                    $lastrow+=1;
                                                }
                                            }
                                        }
                                        if ($a3->hasSum) {//sum คอลัม ทั้งแถว
                                            $e = $lastrow + 1;
                                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a3->acc_name);
                                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                            for ($i = 1; $i <= 18; $i++) {
                                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s3:$col[$i]$lastrow)");
                                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                            }
                                            $lastrow+=1;
                                        }
                                    }
                                }
                                if ($a2->hasSum) {//sum คอลัม ทั้งแถว
                                    $e = $lastrow + 1;
                                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a2->acc_name);
                                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                                    for ($i = 1; $i <= 18; $i++) {
                                        $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s2:$col[$i]$lastrow)");
                                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                    }
                                    $lastrow+=1;
                                }
                            }
                        }
                        if ($a1->hasSum) {//sum คอลัม ทั้งแถว
                            $e = $lastrow + 1;
                            $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $a1->acc_name);
                            $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                            $cola1 = [];
                            for ($i = 1; $i <= 18; $i++) {
                                $sheet1->setCellValue("$col[$i]$e", "=SUM($col[$i]$s1:$col[$i]$lastrow)");
                                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                                array_push($cola1, "$col[$i]$e");
                            }
                            array_push($suma1,$cola1);
                            $lastrow+=1;
                        }
                    }
                    //รวมแต่ละงบ
                    if($g->group_id != 1){
                        //ข้ามรายได้ไป เพราะรายได้เป็นประเภท
                        $e = $lastrow + 1;
                        $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $g->group_name);
                        $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                        $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                        $value=['','','','','','','','','','','','','','','','','','',];
                        foreach($suma1 as $sa1){
                            for($i=0;$i<18;$i++){
                                $value[$i] .= "+".$sa1[$i];
                                
                            }
                        }
                        //print_r($value);return;
                        $colg1 = [];
                        for ($i = 1; $i <= 18; $i++) {
                            $sheet1->setCellValue("$col[$i]$e", '='.empty($value[$i-1])?intval(0):$value[$i-1]);
                            $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                            array_push($colg1, "$col[$i]$e");
                        }
                        array_push($sumgroup, $colg1);
                        $suma1 = [];
                        $lastrow+=1;
                        
                    }
                }
                //รวมแต่ละประเภท (รายได้/รายจ่าย)
                if($t->type_id == 1){
                    //ถ้าเป็นรายได้
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม".$namewithoutnumber."ทั้งสิ้น");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($suma1 as $sa1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= "+".$sa1[$i];

                        }
                    }
                    $colt = [];
                    
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }else{
                    $e = $lastrow + 1;
                    $namewithoutnumber = preg_replace('/^[\d+\.]+/', '', $t->type_name);
                    $sheet1->setCellValue("A$e", "รวม$namewithoutnumber");
                    $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
                    $value=['','','','','','','','','','','','','','','','','','',];
                    foreach($sumgroup as $sg1){
                        for($i=0;$i<18;$i++){
                            $value[$i] .= '+'.$sg1[$i];
                        }
                    }
                    $colt = [];
                    for ($i = 1; $i <= 18; $i++) {
                        $sheet1->setCellValue("$col[$i]$e", "=".$value[$i-1]);
                        $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
                        array_push($colt, "$col[$i]$e");
                    }
                    array_push($sumtype, $colt);
                    $suma1 = [];
                    $lastrow+=1;
                    
                }
            }
            //รายได้-รายจ่าย
            $e = $lastrow + 1;
            $sheet1->setCellValue("A$e", "รายได้-รายจ่าย");
            $sheet1->getStyle("A$e")->applyFromArray($style_text_total);
            $value=['','','','','','','','','','','','','','','','','','',];
            foreach($sumtype as $st1){
                for($i=0;$i<18;$i++){
                    $value[$i] .= '+'.$st1[$i];
                }
            }
            for ($i = 1; $i <= 18; $i++) {
                $sheet1->setCellValue("$col[$i]$e", "=".$sumtype[0][$i-1].'-'.$sumtype[1][$i-1]);
                $sheet1->getStyle("$col[$i]$e")->applyFromArray($style_num_total);
            }
            $lastrow+=1;
        } catch (Exception $ex) {
            die('<pre>'.print_r($ex).'</pre>');
        }
        
        
        ob_end_clean();
        ob_start();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        
        
    }
    

}
