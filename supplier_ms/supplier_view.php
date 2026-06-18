<?php

session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;

if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = "0";
} else {
	$isMobile = "1";
}


//載入公用函數
@include_once '/website/include/pub_function.php';


include_once("/website/class/".$site_db."_info_class.php");

	
	
if (!isset($fm))
	$fm = $_GET['fm'];
	
if (!empty($fm))
	$pfm = "&fm=$fm";

$tb = "supplier";
$pro_id = "supplier";



//讀取資料
$mDB = "";
$mDB=new MywebDB();

if (isset($_GET['auto_seq'])) {
	$auto_seq = $_GET['auto_seq'];
	$Qry="select * from ".$tb." where auto_seq = '$auto_seq'";
} else if (isset($_GET['supplier_id'])) {
	$supplier_id = $_GET['supplier_id'];
	$Qry="select * from ".$tb." where web_id = '$web_id' and supplier_id = '$supplier_id'";
}

$mDB->query($Qry);
$total = $mDB->rowCount();
if ($total > 0) {
    //已找到符合資料
	$row=$mDB->fetchRow(2);

//	$auto_seq = $row['auto_seq'];
	$supplier_id = $row['supplier_id'];
	$supplier_name = htmlspecialchars_decode($row['supplier_name']);
	$type = $row['type'];
	$brief_intro = str_replace("\n","<br>",htmlspecialchars_decode($row['brief_intro']));
	$uniform_number = $row['uniform_number'];
	$bank_account = $row['bank_account'];
	$bank_account_no = $row['bank_account_no'];
	$bank_account_name = $row['bank_account_name'];
	$bank_remit_code = $row['bank_remit_code'];
	$contact = $row['contact'];
	$gender = $row['gender'];
	$title = $row['title'];
	$tel = $row['tel'];
	$email = $row['email'];
	$county = $row['county'];
	$town = $row['town'];
	$zipcode = $row['zipcode'];
	$address = $row['address'];

	$supplier_address = 	$zipcode.$county.$town.$address;

	//性別
	if ($gender=="1")
		$m_gender = "小姐";
	else if ($gender=="2")
		$m_gender = "先生";
	else
		$m_gender = "";
	
	
} else {

	$mDB->remove();
	$show_view = mywarning("未發現符合條件的資料。");
	return;
	
}
$mDB->remove();


$create_date = date2before(strtotime($create_date));
$last_modify = date2before(strtotime($last_modify));



//網頁標題
$page_title = $supplier_name;
$page_description = trim(strip_tags($supplier_name));
$page_keywords = $supplier_name;


//開始設定導覽列連結
$mess_title = "";


$mc = $sc = "";
if (isset($_GET['mc']) && !empty($_GET['mc'])) {
	$mc = urlencode($_GET['mc']);
	$mess_title .= "&nbsp;>&nbsp;"."<a href=\"/index.php?mc=$mc&fm=$fm#mytop\">".$_GET['mc']."</a>";	
}

if (isset($_GET['sc']) && !empty($_GET['sc'])) {
	$sc = urlencode($_GET['sc']);
	$mess_title .= "&nbsp;>&nbsp;"."<a href=\"/index.php?mc=$mc&sc=$sc&fm=$fm#mytop\">".$_GET['sc']."</a>";	
}


$today = date("Y-m-d");

$diff = strtotime($today) - strtotime($completion_date);
$days = floor($diff/(60*60*24));



//圖文檔案列表
$show_attach_list = myfile_list($isMobile,$site_db,$web_id,$tb,$pro_id,'attach',$auto_seq);


$show_close_btn=<<<EOT
	<button id="close" class="btn btn-danger float-end" type="button" onclick="parent.$.fancybox.close();" style="padding: 5px 15px;"><i class="bi bi-power"></i>&nbsp;關閉</button>
EOT;



if (empty($map_address))
	$map_address = 	$county.$town.$address;

if (!empty($map_address)) {
//顯示地圖
$show_address_map=<<<EOT
<style>
#map {
	width:100%;
	max-width:800px;
	height:450px;
	margin: 0 auto;
}
</style>
<div id="map"></div>
<script>
function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16,
    center: {lat: -34.397, lng: 150.644}
  });
  var geocoder = new google.maps.Geocoder();
  
  geocodeAddress(geocoder, map);
  
}

function geocodeAddress(geocoder, resultsMap) {
  var address = '$map_address';
  geocoder.geocode({'address': address}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
		resultsMap.setCenter(results[0].geometry.location);
		var marker = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location
      });
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOfPMLDC0MYmGGrlrW_jrE1HErWh_Qb-4&signed_in=true&callback=initMap"
	async defer></script>
EOT;
}


$show_details=<<<EOT
	<table class="table table-borderless w-100 m-auto mb-3">
		<tr>
			<td style="width:150px;"><div class="mylabel_right">廠商代號：</div></td>
			<td><div class="mylabel_left label_blue"><b>$supplier_id</b></div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">廠商名稱：</div></td>
			<td><div class="mylabel_left">$supplier_name</div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">營業類別：</div></td>
			<td><div class="mylabel_left">$type</div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">統編:</div></td>
			<td>
				<div class="mylabel_left">
					<div class="inline" style="min-width:150px;margin-right:10px;">$uniform_number</div>
					<div class="inline me-3">匯款帳戶 $bank_account</div>
					<div class="inline">匯款帳號 $bank_account_no</div>
					<div class="inline me-3">匯款戶名 $bank_account_name</div>
					<div class="inline">匯款代碼 $bank_remit_code</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><div class="mylabel_right">簡介：</div></td>
			<td><div class="mylabel_left">$brief_intro</div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">主要連絡人：</div></td>
			<td>
				<div class="mylabel_left">
					<div class="inline" style="min-width:150px;margin-right:10px;">$contact &nbsp; $m_gender</div>
					<div class="inline">職稱： $title</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><div class="mylabel_right">連絡電話：</div></td>
			<td><div class="mylabel_left">$tel</div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">E-Mail：</div></td>
			<td><div class="mylabel_left">$email</div></td>
		</tr>
		<tr>
			<td><div class="mylabel_right">廠商地址：</div></td>
			<td><div class="mylabel_left">$supplier_address</div></td>
		</tr>
	</table>
	$show_address_map
EOT;


//顯示結果
$show_center=<<<EOT
<style>

.mylabel_right{font-size:1.4em;text-align:right;}
.mylabel_left{font-size:1.4em;text-align:left;}

@media print{
  .print {display: none;}
}

.card_full {
    width: 100vw;
	height: 100vh;
}

#full {
    width: 100vw;
	height: 100vh;
}

</style>

<div class="card card_full">
	<div class="card-header text-bg-info">
		<div class="size14 weight float-start" style="margin-top: 5px;">
			$supplier_name
		</div>
		<div class="float-end">
			$show_close_btn
		</div>
	</div>
	<div id="full" class="card-body data-overlayscrollbars-initialize">
		<div class="p-3">
			$show_details
		</div>
		<div class="p-3">
			$show_attach_list
		</div>
	</div>
</div>
EOT;


?>
