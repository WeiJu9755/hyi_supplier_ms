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
							 ->setCategory("廠商資料");



			//設置對齊
			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('S')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('T')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('U')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			//匯出主要資料表
			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A1', '廠商代號')
							->setCellValue('B1', '廠商名稱')
							->setCellValue('C1', '簡稱')
							->setCellValue('D1', '營業類別')
							->setCellValue('E1', '統編')
							->setCellValue('F1', '匯款帳戶')
							->setCellValue('G1', '簡介')
							->setCellValue('H1', '連絡電話')
							->setCellValue('I1', '連絡電話2')
							->setCellValue('J1', '傳真')
							->setCellValue('K1', '城市')
							->setCellValue('L1', '行政區')
							->setCellValue('M1', '郵遞區號')
							->setCellValue('N1', '地址')
							->setCellValue('O1', '主要連絡人')
							->setCellValue('P1', '性別(先生1/小姐2)')
							->setCellValue('Q1', '職稱')
							->setCellValue('R1', 'E-Mail')
							->setCellValue('S1', '匯款帳號')
							->setCellValue('T1', '匯款戶名')
							->setCellValue('U1', '匯款代碼');
						
						;
			// ====== 套用樣式（黑底白字，置中，粗體） ======
				$sheet = $objPHPExcel->getActiveSheet();
				$sheet->getStyle('A1:U1')->applyFromArray([
					'font' => [
						'bold' => true,
						'color' => ['rgb' => 'FFFFFF'] // 白字
					],
					'fill' => [
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => ['rgb' => '000000'] // 黑底
					],
					'alignment' => [
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					],
				]);
				
				// ====== 設定 G 欄（簡介）自動換行 ======
				$sheet->getStyle('G')->getAlignment()->setWrapText(true);


			//設置寬度
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(40);
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
			
			


$mDB = "";
$mDB = new MywebDB();

$contract_id = $_GET['contract_id'];

$Qry="SELECT * FROM supplier  ORDER BY supplier_id,auto_seq";

$mDB->query($Qry);
$total = $mDB->rowCount();

$line = 1;

if ($total > 0) {
    while ($row=$mDB->fetchRow(2)) {

		$supplier_id    = $row['supplier_id'];
		$supplier_name  = $row['supplier_name'];
		$short_name     = $row['short_name'];
		$type           = $row['type'];
		$uniform_number = $row['uniform_number'];
		$bank_account   = $row['bank_account'];
		$bank_account_no = $row['bank_account_no'];
		$bank_account_name = $row['bank_account_name'];
		$bank_remit_code = $row['bank_remit_code'];
		$brief_intro    = $row['brief_intro'];
		$tel            = $row['tel'];
		$tel_2          = $row['tel_2'];
		$fax            = $row['fax'];
		$county         = $row['county'];
		$town           = $row['town'];
		$zipcode        = $row['zipcode'];
		$address        = $row['address'];
		$contact        = $row['contact'];
		$gender         = $row['gender'];
		$title          = $row['title'];
		$email          = $row['email'];
		
		
		
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
		$k = 'K'.$line;
		$l = 'L'.$line;
		$m = 'M'.$line;
		$n = 'N'.$line;
		$o = 'O'.$line;
		$p = 'P'.$line;
		$q = 'Q'.$line;
		$r = 'R'.$line;
		$s = 'S'.$line;
		$t = 'T'.$line;
		$u = 'U'.$line;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($a, $supplier_id)
					->setCellValue($b, $supplier_name)
					->setCellValue($c, $short_name)
					->setCellValue($d, $type)
					->setCellValue($e, $uniform_number)
					->setCellValue($f, $bank_account)
					->setCellValue($g, $brief_intro)
					->setCellValue($h, $tel)
					->setCellValue($i, $tel_2)
					->setCellValue($j, $fax)
					->setCellValue($k, $county)
					->setCellValue($l, $town)
					->setCellValue($m, $zipcode)
					->setCellValue($n, $address)
					->setCellValue($o, $contact)
					->setCellValue($p, $gender)
					->setCellValue($q, $title)
					->setCellValue($r, $email)
					->setCellValue($s, $bank_account_no)
					->setCellValue($t, $bank_account_name)
					->setCellValue($u, $bank_remit_code)

		
		
		
					
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
$objPHPExcel->getActiveSheet()->setTitle("廠商資料檔");




// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//$objPHPExcel->setActiveSheetIndex(0);


$xlsx_filename = "廠商資料檔.xls";


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
