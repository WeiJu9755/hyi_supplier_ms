<?php

session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;


//載入公用函數
@include_once '/website/include/pub_function.php';

//連結資料
@include_once("/website/class/".$site_db."_info_class.php");

/* 使用xajax */
@include_once '/website/xajax/xajax_core/xajax.inc.php';
$xajax = new xajax();

$xajax->registerFunction("processform");
function processform($aFormValues){

	$objResponse = new xajaxResponse();
	
	$web_id				= trim($aFormValues['web_id']);
	$auto_seq			= trim($aFormValues['auto_seq']);
	
	
	if (trim($aFormValues['supplier_name']) == "")	{
		$objResponse->script("jAlert('警示', '請輸入廠商名稱', 'red', '', 2000);");
		return $objResponse;
		exit;
	}
	
	SaveValue($aFormValues);
	
	$objResponse->script("setSave();");
	$objResponse->script("parent.myDraw();");

	$objResponse->script("art.dialog.tips('已存檔!',1);");
	$objResponse->script("parent.$.fancybox.close();");
	$objResponse->script("parent.eModal.close();");
		
	
	return $objResponse;
}


$xajax->registerFunction("SaveValue");
function SaveValue($aFormValues){

	$objResponse = new xajaxResponse();
	
		//進行存檔動作
		$site_db				= trim($aFormValues['site_db']);
		$web_id					= trim($aFormValues['web_id']);
		$auto_seq				= trim($aFormValues['auto_seq']);
		$supplier_name 			= htmlspecialchars(trim($aFormValues['supplier_name']), ENT_QUOTES, 'UTF-8');
		$short_name 			= htmlspecialchars(trim($aFormValues['short_name']), ENT_QUOTES, 'UTF-8');
		$type 					= htmlspecialchars(trim($aFormValues['type']), ENT_QUOTES, 'UTF-8');
		$uniform_number 		= trim($aFormValues['uniform_number']);
		$bank_account 			= trim($aFormValues['bank_account']);
		$brief_intro 			= htmlspecialchars(trim($aFormValues['brief_intro']), ENT_QUOTES, 'UTF-8');
		$contact 				= trim($aFormValues['contact']);
		$gender					= trim($aFormValues['gender']);
		$title 					= htmlspecialchars(trim($aFormValues['title']), ENT_QUOTES, 'UTF-8');
		$tel					= trim($aFormValues['tel']);
		$tel_2					= trim($aFormValues['tel_2']);
		$fax					= trim($aFormValues['fax']);
		$email					= trim($aFormValues['email']);
		$county					= trim($aFormValues['county']);
		$town					= trim($aFormValues['town']);
		$zipcode				= trim($aFormValues['zipcode']);
		$address 				= htmlspecialchars(trim($aFormValues['address']), ENT_QUOTES, 'UTF-8');
		
		//存入info實體資料庫中
		$mDB = "";
		$mDB = new MywebDB();

		$Qry="UPDATE supplier set
				 supplier_name		= '$supplier_name'
				,short_name			= '$short_name'
				,type				= '$type'
				,uniform_number		= '$uniform_number'
				,bank_account		= '$bank_account'
				,brief_intro		= '$brief_intro'
				,contact			= '$contact'
				,gender				= '$gender'
				,title				= '$title'
				,tel				= '$tel'
				,tel_2				= '$tel_2'
				,fax				= '$fax'
				,email				= '$email'
				,county				= '$county'
				,town				= '$town'
				,zipcode			= '$zipcode'
				,address			= '$address'
				,last_modify		= now()
				where auto_seq = '$auto_seq'";
				
		$mDB->query($Qry);
        $mDB->remove();

		
	return $objResponse;
}

$xajax->processRequest();



$auto_seq = $_GET['auto_seq'];
$fm = $_GET['fm'];

$mess_title = $title;

$pro_id = "supplier";


$mDB = "";
$mDB = new MywebDB();
$Qry="select * from supplier where auto_seq = '$auto_seq'";
$mDB->query($Qry);
$total = $mDB->rowCount();
if ($total > 0) {
    //已找到符合資料
	$row=$mDB->fetchRow(2);
	$supplier_id = $row['supplier_id'];
	$supplier_name = $row['supplier_name'];
	$short_name = $row['short_name'];
	$type = $row['type'];
	$uniform_number = $row['uniform_number'];
	$bank_account = $row['bank_account'];
	$brief_intro = $row['brief_intro'];
	$contact = $row['contact'];
	$gender = $row['gender'];
	$title = $row['title'];
	$tel = $row['tel'];
	$tel_2 = $row['tel_2'];
	$fax = $row['fax'];
	$email = $row['email'];
	$county = $row['county'];
	$town = $row['town'];
	$zipcode = $row['zipcode'];
	$address = $row['address'];
	$makeby = $row['makeby'];
	$create_date = $row['create_date'];
	$last_modify = $row['last_modify'];
	$OAI_Rate = $row['OAI_Rate'];
	$APF_Rate = $row['APF_Rate'];

	$m_gender = "";
	$m_gender .=  "<option value='' ".mySelect('',$gender)."></option>";
	$m_gender .=  "<option value='1' ".mySelect('1',$gender).">小姐</option>";
	$m_gender .=  "<option value='2' ".mySelect('2',$gender).">先生</option>";	
	
	$tw_county = array('台北市','基隆市','新北市','宜蘭縣','新竹市','新竹縣','桃園市','苗栗縣','台中市','彰化縣','南投縣','嘉義市','嘉義縣','雲林縣','台南市','高雄市','屏東縣','台東縣','花蓮縣','澎湖縣','金門縣','連江縣');
	$m_county = "";
	$m_county .=  "<option value=''>請選擇</option>";
	$count_len = sizeof($tw_county);
	for ( $i = 0; $i <= $count_len-1; $i++ ) {
		$m_county .=  "<option value=\"$tw_county[$i]\" ".mySelect($tw_county[$i],$county).">$tw_county[$i]</option>";
	}
	
}

$mDB->remove();


$show_savebtn=<<<EOT
<div class="btn-group vbottom" role="group" style="margin-top:5px;">
	<button id="save" class="btn btn-primary" type="button" onclick="CheckValue(this.form);" style="padding: 5px 15px;"><i class="bi bi-check-circle"></i>&nbsp;存檔</button>
	<button id="cancel" class="btn btn-secondary display_none" type="button" onclick="setCancel();" style="padding: 5px 15px;"><i class="bi bi-x-circle"></i>&nbsp;取消</button>
	<button id="close" class="btn btn-danger" type="button" onclick="parent.myDraw();parent.$.fancybox.close();" style="padding: 5px 15px;"><i class="bi bi-power"></i>&nbsp;關閉</button>
</div>
EOT;


if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = 0;
	
$style_css=<<<EOT
<style>

.card_full {
    width: 100%;
	height: 100vh;
}

#full {
    width: 100%;
	height: 100vh;
}

#info_container {
	width: 800px !Important;
	margin: 0 auto !Important;
}

.field_div1 {width:150px;display: none;font-size:18px;color:#000;text-align:right;font-weight:700;padding:15px 10px 0 0;vertical-align: top;display:inline-block;zoom: 1;*display: inline;}
.field_div2 {width:100%;max-width:600px;display: none;font-size:18px;color:#000;text-align:left;font-weight:700;padding:8px 0 0 0;vertical-align: top;display:inline-block;zoom: 1;*display: inline;}

.code_class {
	width:150px;
	text-align:right;
	padding:0 10px 0 0;
}

</style>

EOT;

} else {
	$isMobile = 1;

$style_css=<<<EOT
<style>

.card_full {
    width: 100vw;
	height: 100vh;
}

#full {
    width: 100vw;
	height: 100vh;
}

#info_container {
	width: 100% !Important;
	margin: 0 auto !Important;
}

.field_div1 {width:100%;display: block;font-size:18px;color:#000;text-align:left;font-weight:700;padding:15px 10px 0 0;vertical-align: top;}
.field_div2 {width:100%;display: block;font-size:18px;color:#000;text-align:left;font-weight:700;padding:8px 10px 0 0;vertical-align: top;}

.code_class {
	width:auto;
	text-align:left;
	padding:0 10px 0 0;
}

</style>
EOT;

}



$show_center=<<<EOT
<script src="/os/aj-address/js/aj-address.js" type="text/javascript"></script>

$style_css

<div class="card card_full">
	<div class="card-header text-bg-info">
		<div class="size14 weight float-start" style="margin-top: 5px;">
			$mess_title
		</div>
		<div class="float-end" style="margin-top: -5px;">
			$show_savebtn
		</div>
	</div>
	<div id="full" class="card-body data-overlayscrollbars-initialize">
		<div id="info_container">
			<form method="post" id="modifyForm" name="modifyForm" enctype="multipart/form-data" action="javascript:void(null);">
			<div class="w-100 mb-5">
				<div class="field_container3">
					<div>
						<div class="field_div2">
							<div class="inline code_class">廠商代號:</div>
							<div class="inline" style="padding:8px 0;font-size:18px;color:blue;text-align:left;font-weight:700;">$supplier_id</div>
						</div> 
					</div>
					<div>
						<div class="field_div1">廠商名稱:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="supplier_name" name="supplier_name" size="80" maxlength="160" style="width:100%;max-width:500px;" value="$supplier_name" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">簡稱:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="short_name" name="short_name" size="20" maxlength="20" style="width:100%;max-width:200px;" value="$short_name" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">營業類別:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="type" name="type" size="20" maxlength="20" style="width:100%;max-width:200px;" value="$type" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">統編:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext mb-2" id="uniform_number" name="uniform_number" size="20" maxlength="50" style="width:100%;max-width:200px;" value="$uniform_number" onchange="setEdit();"/>
							&nbsp;
							<div class="inline mylabel">公司帳戶:</div>
							<input type="text" class="inputtext" id="bank_account" name="bank_account" size="20" maxlength="50" style="width:100%;max-width:200px;" value="$bank_account" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">簡介:</div> 
						<div class="field_div2">
							<textarea class="inputtext" id="brief_intro" name="brief_intro" cols="80" rows="3" style="width:100%;max-width:500px;padding:6px;" onchange="setEdit();">$brief_intro</textarea>
						</div> 
					</div>
					<div>
						<div class="field_div1">主要連絡人:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext mb-2" id="contact" name="contact" size="20" maxlength="50" style="width:100%;max-width:220px;" value="$contact" onchange="setEdit();"/>
							&nbsp;
							<select name="gender" id="gender" class="input_button mb-2" onchange="setEdit();" style="margin-right:10px;">
								$m_gender
							</select>
							<div class="inline mylabel">職稱:</div>
							<input type="text" class="inputtext" id="title" name="title" size="20" maxlength="50" style="width:100%;max-width:180px;" value="$title" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">連絡電話:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="tel" name="tel" size="30" maxlength="80" style="width:100%;max-width:220px;" value="$tel" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">連絡電話2:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="tel_2" name="tel_2" size="30" maxlength="80" style="width:100%;max-width:220px;" value="$tel_2" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">傳真:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="fax" name="fax" size="30" maxlength="80" style="width:100%;max-width:220px;" value="$fax" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">E-Mail:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="email" name="email" size="60" maxlength="80" style="width:100%;max-width:400px;" value="$email" onchange="setEdit();"/><br>
						</div> 
					</div>
					<div>
						<div class="field_div1">廠商地址:</div> 
						<div class="field_div2">
							<select class="input_button" id="county" name="county">$m_county</select>
							<select class="input_button" id="town" name="town"></select>
							<input readonly type="text" class="inputtext" id="zipcode" name="zipcode" style="width:100%;max-width: 80px;" value="$zipcode"/>
						</div> 
					</div>
					<div>
						<div class="field_div1"></div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="address" name="address" size="80" maxlength="240" style="width:100%;max-width:500px;" value="$address" onchange="setEdit();"/>
						</div> 
					</div>
					<div>
						<input type="hidden" name="fm" value="$fm" />
						<input type="hidden" name="site_db" value="$site_db" />
						<input type="hidden" name="web_id" value="$web_id" />
						<input type="hidden" name="auto_seq" value="$auto_seq" />
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<script>

function CheckValue(thisform) {
//	$('#full_content').val(CKEDITOR.instances.full_content.getData());
	xajax_processform(xajax.getFormValues('modifyForm'));
	thisform.submit();
}

function SaveValue(thisform) {
//	$('#full_content').val(CKEDITOR.instances.full_content.getData());
	xajax_SaveValue(xajax.getFormValues('modifyForm'));
	thisform.submit();
}

function setEdit() {
	$('#close', window.document).addClass("display_none");
	$('#cancel', window.document).removeClass("display_none");
}

function setCancel() {
	$('#close', window.document).removeClass("display_none");
	$('#cancel', window.document).addClass("display_none");
	document.forms[0].reset();
}

function setSave() {
	$('#close', window.document).removeClass("display_none");
	$('#cancel', window.document).addClass("display_none");
}

init_address();
set_address('$county','$town');

$(document).ready(async function() {
	//等待其他資源載入完成，此方式適用大部份瀏覽器
	await new Promise(resolve => setTimeout(resolve, 100));
	$('#supplier_name').focus();
});

</script>

EOT;

?>