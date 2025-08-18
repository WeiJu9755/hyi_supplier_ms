<?php

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2012 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2012 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.8, 2012-10-12
 */

/** Error reporting */
/*
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("Asia/Taipei");
*/
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
date_default_timezone_set("Asia/Taipei");



$site_db = "eshop";
$web_id = "sales.eshop";


//載入公用函數
@include_once '/website/include/pub_function.php';

@include_once("/website/class/".$site_db."_info_class.php");


if (PHP_SAPI == 'cli')
	die('This programe should only be run from a Web Browser');

/** Include PHPExcel */
require_once '/website/os/PHPExcel-1.8.1/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("PowerSales")
							 ->setLastModifiedBy("PowerSales")
							 ->setTitle("Office 2007 XLSX Document")
							 ->setSubject("Office 2007 XLSX Document")
							 ->setDescription("The document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("合約項次表");



			//設置對齊
			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


//匯出主要資料表
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '合約代號')
            ->setCellValue('B1', '項次')
            ->setCellValue('C1', '工作項目')
            ->setCellValue('D1', '單位')
            ->setCellValue('E1', '單價')
            ->setCellValue('F1', '契約數量')
            
			;


			//設置寬度
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(73);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
			
			


$mDB = "";
$mDB = new MywebDB();

$contract_id = $_GET['contract_id'];

$Qry="SELECT * FROM contract_details WHERE contract_id = '$contract_id' ORDER BY contract_id";

$mDB->query($Qry);
$total = $mDB->rowCount();

$line = 1;

if ($total > 0) {
    while ($row=$mDB->fetchRow(2)) {

		$contract_id = $row['contract_id'];
		$seq = $row['seq'];
		$work_project = $row['work_project'];
		$unit = $row['unit'];
		$unit_price = $row['unit_price'];	
		$contracts_qty = $row['contracts_qty'];
		
		
		$line++;

		$a = 'A'.$line;
		$b = 'B'.$line;
		$c = 'C'.$line;
		$d = 'D'.$line;
		$e = 'E'.$line;
		$f = 'F'.$line;
		
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($a, $contract_id)
					->setCellValue($b, $seq)
					->setCellValue($c, $work_project)
					->setCellValue($d, $unit)
					->setCellValue($e, $unit_price)
					->setCellValue($f, $contracts_qty)
					
					;
	
	}

}

/*
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("主要資料表");



$objPHPExcel->createSheet();

//匯出聯絡人資料表
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', '客戶代號')
            ->setCellValue('B1', '業務窗口')
            ->setCellValue('C1', '連絡人姓名')
            ->setCellValue('D1', '性別')
            ->setCellValue('E1', '職稱')
            ->setCellValue('F1', '連絡電話')
            ->setCellValue('G1', '傳真')
            ->setCellValue('H1', '行動電話')
            ->setCellValue('I1', 'E-Mail')
            ->setCellValue('J1', '主要連絡人')
			;


$Qry="SELECT * FROM mycustomer_contact WHERE web_id = '$web_id' and crm_id = '$crm_id' and auth_id = '$auth_id'";

$mDB->query($Qry);
$total = $mDB->rowCount();

$line = 1;

if ($total > 0) {
    while ($row=$mDB->fetchRow(2)) {

		$customer_id = $row['customer_id'];
		$business_kind = $row['business_kind'];
		$contact = $row['contact'];
		$gender = $row['gender'];
		$title = $row['title'];
		$tel = $row['tel'];
		$fax = $row['fax'];
		$mobile_no = $row['mobile_no'];
		$email = $row['email'];
		$main = $row['main'];

		
		$line++;

		$a = 'A'.$line;
		$b = 'B'.$line;
		$c = 'C'.$line;
		$d = 'D'.$line;
		$e = 'E'.$line;
		$f = 'F'.$line;
		$g = 'G'.$line;
		$h = 'H'.$line;
		$i = 'I'.$line;
		$j = 'J'.$line;
		
		
		$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue($a, $customer_id)
					->setCellValue($b, $business_kind)
					->setCellValue($c, $contact)
					->setCellValue($d, $gender)
					->setCellValue($e, $title)
					->setCellValue($f, $tel)
					->setCellValue($g, $fax)
					->setCellValue($h, $mobile_no)
					->setCellValue($i, $email)
					->setCellValue($j, $main)
					;
	
	}

}
*/


$mDB->remove();


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("合約項次表");




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(0);


$xlsx_filename = "合約項次表.xls";


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$xlsx_filename);
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;




?>
