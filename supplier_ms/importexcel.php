<?php

session_start();
$memberID = $_SESSION['memberID'];

require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;

//include_once("/website/class/".$site_db."_info_class.php");


$fm = $_GET['fm'];
//$project_id = $_GET['project_id'];
//$auth_id = $_GET['auth_id'];

//$tb = "pjcustomer";



$closebtn = "<button class=\"btn btn-danger float-end\" type=\"button\" onclick=\"parent.location.reload(); parent.$.fancybox.close();\" style=\"margin-top:5px;\"><i class=\"bi bi-power\"></i>&nbsp;關閉</button>";


	
$timestamp = time();
$token = md5('unique_salt'.$timestamp);

$m_location		= "/smarty/templates/".$site_db."/".$templates;
$uploadScript = $m_location."/sub_modal/project/contract_ms/excel_uploadify.php";

//取得目錄
$files_dir0 = "/webdata/".$site_db."/".$web_id."/project/contract_ms/excel";
$files_dir1 = "/website".$files_dir0;
//檢查目錄是否存在，不存在則建立
if (!file_exists($files_dir1))
	mkdir_r($files_dir1,0777);
	
	
$show_center=<<<EOT
<script type="text/javascript" src="/os/uploadifive/Sample/jquery.uploadifive.js"></script>
<link rel="stylesheet" type="text/css" href="/os/uploadifive/Sample/uploadifive.css">

<style>

.card_full {
	width:100%;
	height:100vh;
}

#full {
	width: 100%;
	height: 100%;
}
</style>

<div class="card card_full">
	<div class="card-header text-bg-info">
		$closebtn
		<div class="float-start">
			<div class="inline vmiddle" style="cursor: pointer;">
				<input id="file_upload" name="file_upload" type="file" multiple="true">
			</div>
			<div class="inline vmiddle" style="margin-top:5px;">
				<button id="save" type="button" class="btn btn-success" onclick="Check_file_upload();"><i class="bi bi-check-circle"></i>&nbsp;送出</button>
			</div>
		</div>
	</div>
	<div id="full" class="card-body data-overlayscrollbars-initialize">
		<div class="size12 black weight">※僅接受特定內容之Excel檔案，單檔大小不可超過 50MB，且不可複選。</div>
		<div style="margin-left:10px;"><div id="uploading" class="display_none"><img src="/pub_images/loading36.gif">&nbsp;&nbsp;&nbsp;傳輸中請稍待...</div></div>
		<hr class="half-rule">
		<div id="file_queue" style="width:50%;padding:10px 0;"></div>
	</div>
</div>


<!--
<div style="width:100%;max-width:750px;margin: 10px auto;">
<div class="form_btn_div" style="position:fixed;top:0;right:0;">
	$closebtn
</div>
<div style="display : lnline;">
	<div class="clearfix" style="margin: 10px 0 0 10px;vertical-align: top;">
		<input id="file_upload" name="file_upload" type="file" multiple="true">
	</div>
	<div class="clearfix" style="margin: 23px 0 0 10px;">
		<span class="label_red">※僅接受特定內容之Excel檔案，單檔大小不可超過 50MB，且不可複選。</span>
	</div>
</div>
<hr class="half-rule">
<div id="file_queue"><div style="width:150px;margin: 10px auto 20px auto;text-align:center;color:#ccc;">檔案上傳佇列區</div></div>
</div>
-->


<script>


	$(function() {
		$('#file_upload').uploadifive({
			'auto'             : true,
			'buttonText'       : '<button type="button" class="btn btn-primary px-3"><i class="bi bi-plus-circle"></i>&nbsp;選擇檔案</button>',
			'width'            : 150,
			'height'           : 43,
			'fileSizeLimit'    : '50MB',
			'fileTypeDesc'     : 'Excel Files',
			'fileTypeExts'     : '*.xlsx; *.xls;',
			'formData'  	   : {'timestamp':'$timestamp','token':'$token','folder':'$files_dir0','site_db':'$site_db'},
			'uploadScript'     : '$uploadScript',
			'multi'            : false,
			'removeCompleted'  : true,
			'onSelect'         : function(file) {
				//alert('Starting to upload ' + file.name);
				$("#file_queue").html("<div class='w-100 m-auto text-center'>檔案處理中，請稍待...</div>");
			},
			'onUploadComplete' : function(file, response) {
				//$("#pre_image").html(response);
				$("#file_queue").html("<div class='w-100 m-auto text-center'><b>已匯入完成！</b></div>"+response);
				myDraw();
				
			}
		});
	});


var myDraw = function(){
	var oTable;
	oTable = parent.$('#db_table').dataTable();
	oTable.fnDraw(false);
}

</script>

EOT;

?>