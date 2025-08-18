<?php


require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;


@include_once("/website/class/".$site_db."_info_class.php");

/* 使用xajax */
@include_once '/website/xajax/xajax_core/xajax.inc.php';
$xajax = new xajax();

$xajax->registerFunction("processform");

function processform($aFormValues){

	$objResponse = new xajaxResponse();
	
	if (trim($aFormValues['supplier_id']) == "")	{
		$objResponse->script("jAlert('警示', '請輸入廠商代號', 'red', '', 2000);");
		return $objResponse;
		exit;
	}
	if (trim($aFormValues['supplier_name']) == "")	{
		$objResponse->script("jAlert('警示', '請輸入廠商名稱', 'red', '', 2000);");
		return $objResponse;
		exit;
	}
	
	$fm					= trim($aFormValues['fm']);
	$site_db			= trim($aFormValues['site_db']);
	$templates			= trim($aFormValues['templates']);
	$web_id				= trim($aFormValues['web_id']);
	$supplier_id		= trim($aFormValues['supplier_id']);
	$supplier_name 		= htmlspecialchars(trim($aFormValues['supplier_name']), ENT_QUOTES, 'utf8');
	

	
	//存入實體資料庫中
	$mDB = "";
	$mDB = new MywebDB();
	
	//檢查帳號是否重複
	$Qry="select supplier_id from supplier where web_id = '$web_id' and supplier_id = '$supplier_id'";
	$mDB->query($Qry);
	$total = $mDB->rowCount();
	if ($total > 0) {
		$mDB->remove();
		$objResponse->script("jAlert('警示', '您輸入的廠商代號已重複，請重新輸入新的', 'red', '', 2000);");
		return $objResponse;
		exit;
	}
	
	
	$Qry="insert into supplier (web_id,supplier_id,supplier_name,create_date,last_modify) values ('$web_id','$supplier_id','$supplier_name',now(),now())";
	$mDB->query($Qry);
	//再取出auto_seq
	$Qry="select auto_seq from supplier where web_id = '$web_id' and supplier_id = '$supplier_id' order by auto_seq desc limit 0,1";
	$mDB->query($Qry);
	if ($mDB->rowCount() > 0) {
		//已找到符合資料
		$row=$mDB->fetchRow(2);
		$auto_seq = $row['auto_seq'];
	}
	$mDB->remove();
	if (!empty($auto_seq)) {
		$objResponse->script("myDraw();");
		$objResponse->script("art.dialog.tips('已新增，請繼續輸入其他資料...',2);");
		$objResponse->script("window.location='/?ch=edit&auto_seq=$auto_seq&fm=$fm';");
	} else {
		$objResponse->script("jAlert('警示', '發生不明原因的錯誤，資料未新增，請再試一次!', 'red', '', 2000);");
		$objResponse->script("parent.$.fancybox.close();");
	}
	
	return $objResponse;	
}

$xajax->processRequest();

$fm = $_GET['fm'];
$t = $_GET['t'];

$mess_title = $title;


if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = 0;

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
	width: 800px !Important;
	margin: 0 auto !Important;
}

.field_div1 {width:150px;display: none;font-size:18px;color:#000;text-align:right;font-weight:700;padding:15px 10px 0 0;vertical-align: top;display:inline-block;zoom: 1;*display: inline;}
.field_div2 {width:100%;max-width:600px;display: none;font-size:18px;color:#000;text-align:left;font-weight:700;padding:8px 0 0 0;vertical-align: top;display:inline-block;zoom: 1;*display: inline;}

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

</style>
EOT;

}
	


$show_center=<<<EOT
$style_css
<div class="card card_full">
	<div class="card-header text-bg-info">
		<div class="size14 weight float-start">
			$mess_title
		</div>
	</div>
	<div id="full" class="card-body data-overlayscrollbars-initialize">
		<div id="info_container">
			<form method="post" id="addForm" name="addForm" enctype="multipart/form-data" action="javascript:void(null);">
				<div class="field_container3">
					<div>
						<div class="field_div1">廠商代號:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="supplier_id" name="supplier_id" size="50" maxlength="50" style="width:100%;max-width:250px;"/>
						</div> 
					</div>
					<div>
						<div class="field_div1">廠商名稱:</div> 
						<div class="field_div2">
							<input type="text" class="inputtext" id="supplier_name" name="supplier_name" size="80" maxlength="160" style="width:100%;max-width:450px;"/>
						</div> 
					</div>
				</div>
				<div class="form_btn_div mt-5">
					<input type="hidden" name="fm" value="$fm" />
					<input type="hidden" name="site_db" value="$site_db" />
					<input type="hidden" name="templates" value="$templates" />
					<input type="hidden" name="web_id" value="$web_id" />
					<button class="btn btn-primary" type="button" onclick="CheckValue(this.form);" style="padding: 10px;margin-right: 10px;"><i class="bi bi-check-lg green"></i>&nbsp;確定新增</button>
					<button class="btn btn-danger" type="button" onclick="parent.myDraw();parent.$.fancybox.close();" style="padding: 10px;"><i class="bi bi-power"></i>&nbsp;關閉</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>

function CheckValue(thisform) {
	xajax_processform(xajax.getFormValues('addForm'));
	thisform.submit();
}

var myDraw = function(){
	var oTable;
	oTable = parent.$('#db_table').dataTable();
	oTable.fnDraw(false);
}

$(document).ready(async function() {
	//等待其他資源載入完成，此方式適用大部份瀏覽器
	await new Promise(resolve => setTimeout(resolve, 100));
	$('#supplier_id').focus();
});

</script>
EOT;

?>