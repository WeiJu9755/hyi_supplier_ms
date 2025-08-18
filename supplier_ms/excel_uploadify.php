<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

//載入公用函數
@include_once '/website/include/pub_function.php';

$web_id = $_POST['web_id'];
$site_db = $_POST['site_db'];


/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . '/website/os/PHPExcel-1.8.1/Classes/');

/** PHPExcel_IOFactory */
include '/website/os/PHPExcel-1.8.1/Classes/PHPExcel/IOFactory.php';


// /website/webdata/eshop/mybrand.eshop/emulation/excel

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';

	$sfile = $_FILES['Filedata']['name'];

	$filetype = strtolower(pathinfo($sfile, PATHINFO_EXTENSION));

	//檔名處理
	$sfilename = validfilename($sfile);

	//產生唯一值
	$tfile = strtoupper(uuid());

	$targetFile = str_replace('//', '/', $targetPath) . $tfile . "." . $filetype;

	if (move_uploaded_file($tempFile, $targetFile)) {
		//		echo "檔案：".str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile)."已上傳並完成匯入!";

		//		echo $targetFile;

		//匯入資料庫中
		include_once("/website/class/" . $site_db . "_info_class.php");
		$mDB = "";
		$mDB = new MyWebDB();

		$retval = "";


		$inputFileName = $targetFile;
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

		//先選擇Excel第1頁工作表「主要資料表」存入至 contract_details
		$objPHPExcel->setActiveSheetIndex(0);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


		foreach ($sheetData as $sheetIndex => $data) {
			if ($sheetIndex > 1) {

				$contract_id = htmlentities(trim($data["A"]), ENT_QUOTES, 'UTF-8');
				$seq = htmlentities(trim($data["B"]), ENT_QUOTES, 'UTF-8');
				$work_project = htmlentities(trim($data["C"]), ENT_QUOTES, 'UTF-8');
				$unit = htmlentities(trim($data["D"]), ENT_QUOTES, 'UTF-8');
				$unit_price = htmlentities(trim($data["E"]), ENT_QUOTES, 'UTF-8');
				$contracts_qty = htmlentities(trim($data["F"]), ENT_QUOTES, 'UTF-8');


				//檢查是否重複
				$contract_row = getkeyvalue2($site_db . "_info", "contract_details", "contract_id = '$contract_id'", "count(*) as c_count");
				$contract_count = $contract_row['c_count'];
				if ($e_count <= 0) {
					$Qry = "INSERT INTO contract_details (contract_id, seq, work_project, unit, unit_price, contracts_qty) 
        					VALUES ('$contract_id', '$seq', '$work_project', '$unit', '$unit_price', '$contracts_qty')";
					$mDB->query($Qry);
				}

				//更新 contract_details
				$Qry = "UPDATE contract_details SET
					seq = '$seq'
					,work_project = '$work_project'
					,unit = '$unit'
					,unit_price = '$unit_price'
					,contracts_qty = '$contracts_qty'
					WHERE contract_id = '$contract_id' AND seq = '$seq'";

				$mDB->query($Qry);
				
				$retval .= "<div>已建立更新 合約代號：" . $contract_id . "</div>";
				

			}

		}


		$mDB->remove();


		if ($retval <> "")
			echo $retval;

		//結束程序，刪除上傳的檔案
		if (file_exists($targetFile)) {
			if (is_file($targetFile)) {
				recursiveDelete($targetFile);
			}
		}


	}

	//	sleep(10);

}

?>