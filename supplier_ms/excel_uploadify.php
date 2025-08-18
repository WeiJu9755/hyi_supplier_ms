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

        $supplier_id    = htmlentities(trim($data["A"]), ENT_QUOTES, 'UTF-8'); // 廠商代號
		if ($supplier_id === '') { continue; } // 沒有廠商代號就跳過

		$supplier_name  = htmlentities(trim($data["B"]), ENT_QUOTES, 'UTF-8'); // 廠商名稱
		$short_name     = htmlentities(trim($data["C"]), ENT_QUOTES, 'UTF-8'); // 簡稱
		$type           = htmlentities(trim($data["D"]), ENT_QUOTES, 'UTF-8'); // 營業類別
		$uniform_number = htmlentities(trim($data["E"]), ENT_QUOTES, 'UTF-8'); // 統編
		$bank_account   = htmlentities(trim($data["F"]), ENT_QUOTES, 'UTF-8'); // 公司帳戶
		$brief_intro    = htmlentities(trim($data["G"]), ENT_QUOTES, 'UTF-8'); // 簡介
		$tel            = htmlentities(trim($data["H"]), ENT_QUOTES, 'UTF-8'); // 電話
		$tel_2          = htmlentities(trim($data["I"]), ENT_QUOTES, 'UTF-8'); // 電話2
		$fax            = htmlentities(trim($data["J"]), ENT_QUOTES, 'UTF-8'); // 傳真
		$county         = htmlentities(trim($data["K"]), ENT_QUOTES, 'UTF-8'); // 城市
		$town           = htmlentities(trim($data["L"]), ENT_QUOTES, 'UTF-8'); // 行政區
		$zipcode        = htmlentities(trim($data["M"]), ENT_QUOTES, 'UTF-8'); // 郵遞區號
		$address        = htmlentities(trim($data["N"]), ENT_QUOTES, 'UTF-8'); // 地址
		$contact        = htmlentities(trim($data["O"]), ENT_QUOTES, 'UTF-8'); // 主要連絡人
		$gender         = htmlentities(trim($data["P"]), ENT_QUOTES, 'UTF-8'); // 性別
		$title          = htmlentities(trim($data["Q"]), ENT_QUOTES, 'UTF-8'); // 職稱
		$email          = htmlentities(trim($data["R"]), ENT_QUOTES, 'UTF-8'); // Email

        // 檢查是否重複
        $supplier_row = getkeyvalue2($site_db . "_info", "supplier", "supplier_id = '$supplier_id'", "count(*) as c_count");
        $supplier_count = (int)($supplier_row['c_count'] ?? 0);

        if ($supplier_count <= 0) {
            // INSERT：不放 OAI_Rate / APF_Rate，使用資料表預設值；注意移除最後的逗號
            $Qry = "INSERT INTO `supplier` (
                        web_id,
                        supplier_id,
                        supplier_name,
                        short_name,
                        type,
                        uniform_number,
                        bank_account,
                        brief_intro,
                        tel,
                        tel_2,
                        fax,
                        county,
                        town,
                        zipcode,
                        address,
                        contact,
                        gender,
                        title,
                        email,
                        makeby,
                        create_date,
                        last_modify
                    ) VALUES (
                        'sales.eshop',
                        '$supplier_id',
                        '$supplier_name',
                        '$short_name',
                        '$type',
                        '$uniform_number',
                        '$bank_account',
                        '$brief_intro',
                        '$tel',
                        '$tel_2',
                        '$fax',
                        '$county',
                        '$town',
                        '$zipcode',
                        '$address',
                        '$contact',
                        '$gender',
                        '$title',
                        '$email',
                        'system',
                        NOW(),
                        NOW()
                    )";
            $mDB->query($Qry);
        }

        // UPDATE：移除 WHERE 前多餘逗號
        $Qry = "UPDATE `supplier` SET
                    supplier_name   = '$supplier_name',
                    short_name      = '$short_name',
                    type            = '$type',
                    uniform_number  = '$uniform_number',
                    bank_account    = '$bank_account',
                    brief_intro     = '$brief_intro',
                    tel             = '$tel',
                    tel_2           = '$tel_2',
                    fax             = '$fax',
                    county          = '$county',
                    town            = '$town',
                    zipcode         = '$zipcode',
                    address         = '$address',
                    contact         = '$contact',
                    gender          = '$gender',
                    title           = '$title',
                    email           = '$email',
                    makeby          = 'system',
                    last_modify     = NOW()
                WHERE supplier_id = '$supplier_id'";
        $mDB->query($Qry);

        $retval .= "<div>已建立更新廠商資料：$supplier_id</div>";
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