<?php

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');

require_once '/website/os/Mobile-Detect-2.8.34/Mobile_Detect.php';
$detect = new Mobile_Detect;

if (!($detect->isMobile() && !$detect->isTablet())) {
	$isMobile = "0";
} else {
	$isMobile = "1";
}


@include_once("/website/class/".$site_db."_info_class.php");

/* 使用xajax */
@include_once '/website/xajax/xajax_core/xajax.inc.php';
$xajax = new xajax();

$xajax->registerFunction("DeleteRow");
function DeleteRow($auto_seq,$site_db,$pro_id){

	$objResponse = new xajaxResponse();
	
	$mDB = "";
	$mDB = new MywebDB();
	
	//取得相關訊息
	$Qry = "select web_id from supplier where auto_seq = '$auto_seq'";
	$mDB->query($Qry);
	if ($mDB->rowCount() > 0) {
		//已找到符合資料
		$row=$mDB->fetchRow(2);
		$web_id = $row['web_id'];
		
		//取得檔案目錄
		$attach_path = "/website/webdata/".$site_db."/".$web_id."/supplier/".$pro_id."/attach".$auto_seq."/";

		//刪除檔案資料庫
		$Qry = "delete from file_caption where web_id = '$web_id' and pro_id = '$pro_id' and ftype ='supplier' and localpath = 'attach' and seq = '$auto_seq'";
		$mDB->query($Qry);
		
		//刪除關連附加檔案
		if (file_exists($attach_path)) {
			if (is_dir($attach_path)) {
				SureRemoveDir($attach_path,true);
			}
		}
		
	}

	//刪除主資料
	$Qry="delete from supplier where auto_seq = '$auto_seq'";
	$mDB->query($Qry);
	
	$mDB->remove();
	
    $objResponse->script("oTable = $('#db_table').dataTable();oTable.fnDraw(false)");
    $objResponse->script("art.dialog.tips('相關資料已全數刪除!',2)");

	return $objResponse;
	
}


$xajax->registerFunction("returnValue");
function returnValue($web_id,$auto_seq,$makeby,$site_db,$fm,$memberID,$pubweburl,$pro_id){
	$objResponse = new xajaxResponse();

	//取得圖片目錄
	$files_dir0 = "/webdata/".$site_db."/".$web_id."/supplier/".$pro_id."/attach".$auto_seq;
	$files_dir1 = "/website".$files_dir0."/";
	
	//從資料庫中讀取圖片資料
	$mDB = "";
	$mDB = new MywebDB();
	$Qry="select file_id,caption,orderby from file_caption where web_id = '$web_id' and pro_id = '$pro_id' and ftype ='supplier' and localpath = 'attach' and seq = '$auto_seq' order by orderby";
	$mDB->query($Qry);
	$files_list = "";
	$n = 0;
	$file_size_total = 0;
	
	if ($mDB->rowCount() > 0) {
		while ($row=$mDB->fetchRow(2)) {
			$o_file = $row['file_id'];
			$file_size = filesize($o_file);
			$file_size_total += $file_size;
			$n++;
		}
	}
	
	$mDB->remove();
	
	$show_file_size_total = "<span style=\"white-space: pre;\">(".byteConvert($file_size_total).")</span>";
	
	
	if ($n > 0)
		$show_files_total = "<i class=\"bi bi-file-earmark-medical blue01 me-1\" title=\"附加檔案\"></i><span class=\"badge text-bg-info me-1\">$n</span><span class=\"red weight me-2\">".$show_file_size_total."</span>";
	else 
		$show_files_total = "";
	
	
	$objResponse->assign("files_total".$auto_seq,"innerHTML",$show_files_total);
	
    return $objResponse;
}


$xajax->processRequest();

$fm = $_GET['fm'];
$t = $_GET['t'];
$mc = $_GET['mc'];
$sc = $_GET['sc'];
$project_id = $_GET['project_id'];
$auth_id = $_GET['auth_id'];

$tb = "supplier";

$pro_id = "supplier";

$m_t = urlencode($_GET['t']);

$mess_title = $t;


$today = date("Y-m-d");



$dataTable_de = getDataTable_de();
$Prompt = getlang("提示訊息");
$Confirm = getlang("確認");
$Cancel = getlang("取消");


$pubweburl = "//".$domainname;

$fellow_count = 0;
//取得指定管理人數(小隊長)
$pjmyfellow_row = getkeyvalue2($site_db."_info","pjmyfellow","web_id = '$web_id' and project_id = '$project_id' and auth_id = '$auth_id' and pro_id = '$pro_id'","count(*) as fellow_count");
$fellow_count =$pjmyfellow_row['fellow_count'];
if ($fellow_count == 0)
	$fellow_count = "";

$pjItemManager = false;
//檢查是否為指定管理人(小隊長)
$pjmyfellow_row = getkeyvalue2($site_db."_info","pjmyfellow","web_id = '$web_id' and project_id = '$project_id' and auth_id = '$auth_id' and pro_id = '$pro_id' and member_no = '$memberID'","count(*) as enable_count");
$enable_count =$pjmyfellow_row['enable_count'];
if ($enable_count > 0)
	$pjItemManager = true;

//設定權限
$cando = "N";
if ($powerkey=="A") {
	$cando = "Y";
} else if ($super_admin=="Y") {
	if ($admin_readonly <> "Y") {
		$cando = "Y";
	}
} else if ($super_advanced=="Y") {
	if ($advanced_readonly <> "Y") {
		$cando = "Y";
	}
}
// 指定管理人 → 只顯示按鈕，不顯示 admin list
$show_admin_list = "";
if ($super_admin=="Y") {
    $show_admin_list=<<<EOT
<div class="text-center">
	<div class="btn-group me-2 mb-2" role="group">
		<a role="button" class="btn btn-light" href="javascript:void(0);" onclick="openfancybox_edit('/index.php?ch=fellowlist&project_id=$project_id&auth_id=$auth_id&pro_id=$pro_id&t=指定管理人&fm=base',850,'96%',true);" title="指定管理人">
			<i class="bi bi-shield-fill-check size14 red inline me-2 vmiddle"></i>
			<div class="inline size12 me-2">指定管理人</div>
			<div class="inline red weight vmiddle">$fellow_count</div>
		</a>
	</div>
</div>
EOT;
}


if ($cando=="Y" || $pjItemManager) {

	if (($super_admin=="Y" && $admin_readonly <> "Y") || $pjItemManager) {

$show_modify_btn=<<<EOT
<div class="text-center my-2">
	<div class="btn-group" role="group" style="margin:0;">
	 	<button type="button" class="btn btn-primary text-nowrap" onclick="window.location.href='/index.php?ch=exportexcel&t=匯出廠商Excel資料檔&fm=$fm';">
            <i class="bi bi-filetype-xls"></i>&nbsp;匯出Excel
        </button>
		 <button type="button" class="btn btn-primary text-nowrap" onclick="openfancybox_edit('/index.php?ch=importexcel&t=匯入廠商Excel資料檔&fm=$fm',850,350,'true');">
            <i class="bi bi-filetype-xls"></i>&nbsp;匯入Excel
        </button>

		<button type="button" class="btn btn-danger text-nowrap" onclick="openfancybox_edit('/index.php?ch=add&t=$t&fm=$fm',800,'96%','');">
            <i class="bi bi-plus-circle"></i>&nbsp;新增資料
        </button>
		<button type="button" class="btn btn-success text-nowrap" onclick="myDraw();">
            <i class="bi bi-arrow-repeat"></i>&nbsp;重整
        </button>
	</div>


</div>
$show_admin_list
EOT;

	}
} else {

$show_modify_btn=<<<EOT
<div class="size14 red m-auto text-center my-2 px-2 py-1 border border-danger" style="width:100px;">唯讀</div>
EOT;

}


$list_view=<<<EOT
<div class="w-100 m-auto p-1 mb-5 bg-white" style="max-width:1240px;">
	<div class="size20 pt-3 text-center">廠商管理</div>
	$show_modify_btn
	<table class="table table-bordered border-dark w-100" id="db_table" style="min-width:1000px;">
		<thead class="table-light border-dark">
			<tr style="border-bottom: 1px solid #000;">
				<th scope="col" class="text-start text-nowrap" style="width:25%;">廠商代號/名稱</th>
				<th scope="col" class="text-start" style="width:30%;">主要連絡人</th>
				<th scope="col" class="text-start" style="width:40%;">地址/附檔</th>
				<th scope="col" class="text-center text-nowrap" style="width:5%;">處理</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
			<tr>
				<td colspan="4" class="dataTables_empty">資料載入中...</td>
			</tr>
		</tbody>
	</table>
</div>
EOT;

	
$scroll = true;
if (!($detect->isMobile() && !$detect->isTablet())) {
	$scroll = false;
}

	
$show_center=<<<EOT
<style type="text/css">
#db_table {
	width: 100% !Important;
	margin: 5px 0 0 0 !Important;
}

.supplier-contact {
	display: flex;
	flex-direction: column;
	gap: 4px;
	line-height: 1.35;
}

.supplier-contact__name {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 4px 8px;
}

.supplier-contact__person {
	font-size: 15px;
	font-weight: 700;
	color: #0d6efd;
}

.supplier-contact__meta {
	font-size: 12px;
	color: #6c757d;
}

.supplier-contact__title {
	display: inline-block;
	max-width: 120px;
	padding: 1px 6px;
	border: 1px solid #d8dee4;
	border-radius: 4px;
	background: #f8f9fa;
	color: #495057;
	font-size: 12px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	vertical-align: middle;
}

.supplier-contact__row {
	display: flex;
	align-items: center;
	gap: 6px;
	min-height: 20px;
	color: #212529;
	font-size: 13px;
	word-break: break-word;
}

.supplier-contact__row i {
	width: 16px;
	color: #6c757d;
	text-align: center;
}

.supplier-contact__empty {
	color: #adb5bd;
	font-size: 13px;
}

</style>

$list_view

<script>
	var oTable;
	$(document).ready(function() {
		$('#db_table').dataTable( {
			"processing": true,
			"serverSide": true,
			"responsive":  {
				details: true
			},//RWD響應式
			"scrollX": '$scroll',
			"paging": true,
			"pageLength": 50,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"pagingType": "full_numbers",  //分页样式： simple,simple_numbers,full,full_numbers
			"searching": true,  //禁用原生搜索
			"ordering": false,
			"ajaxSource": "/smarty/templates/$site_db/$templates/sub_modal/base/supplier_ms/server_supplier.php?site_db=$site_db&web_id=$web_id",
			"language": {
						"sUrl": "$dataTable_de"
					},
			"fnRowCallback": function( nRow, aData, iDisplayIndex ) { 

			
				//預覽連結
				var preview = "openfancybox_edit('/?ch=view&auto_seq="+aData[7]+"&fm=$fm',800,'96%',false);";
				
				var m_short_name = '';
				if (aData[2] != null && aData[2] != '')
					m_short_name = '<div class="inline text-nowrap">簡稱：<span class="blue02 weight ">'+aData[2]+'</span></div>';

				var m_supplier = '';
				if (aData[1] != null && aData[1] != '') {
					m_supplier = '<div class="text-nowrap"><a href="javascript:void(0);" onclick="'+preview+'" class="blue02 size14 weight">'+aData[1]+'</a></div>'
							+'<div><div class="inline me-2">'+aData[0]+'</div>'+m_short_name+'</div>';
				}

					
				var m_gender = '';
				if (aData[11] == "1")
					m_gender = "小姐";
				else if (aData[11] == "2")
					m_gender = "先生";
				
				var contact_name = '';
				if (aData[10] != null && aData[10] != '')
					contact_name = '<span class="supplier-contact__person">'+aData[10]+'</span>';

				var contact_gender = '';
				if (m_gender != '')
					contact_gender = '<span class="supplier-contact__meta">'+m_gender+'</span>';

				var contact_title = '';
				if (aData[12] != null && aData[12] != '')
					contact_title = '<span class="supplier-contact__title" title="'+aData[12]+'">'+aData[12]+'</span>';

				var tel = '';
				if (aData[13] != null && aData[13] != '')
					tel = '<div class="supplier-contact__row"><i class="bi bi-telephone"></i><span>'+aData[13]+'</span></div>';

				var email = '';
				if (aData[14] != null && aData[14] != '')
					email = '<div class="supplier-contact__row"><i class="bi bi-envelope"></i><span>'+aData[14]+'</span></div>';

				var m_contact = '<div class="supplier-contact">';
				if (contact_name != '' || contact_gender != '' || contact_title != '')
					m_contact += '<div class="supplier-contact__name">'+contact_name+contact_gender+contact_title+'</div>';
				if (tel != '' || email != '')
					m_contact += tel+email;
				if (contact_name == '' && contact_gender == '' && contact_title == '' && tel == '' && email == '')
					m_contact += '<div class="supplier-contact__empty">未填寫</div>';
				m_contact += '</div>';

				var bank_account = '';
				if (aData[17] != null && aData[17] != '')
					bank_account = '<div class="inline me-3 text-nowrap">匯款帳戶：<span class="blue02 weight">'+aData[17]+'</span></div>';

				var bank_account_no = '';
				if (aData[18] != null && aData[18] != '')
					bank_account_no = '<div class="inline me-3 text-nowrap">匯款帳號：<span class="blue02 weight">'+aData[18]+'</span></div>';

				var bank_account_name = '';
				if (aData[19] != null && aData[19] != '')
					bank_account_name = '<div class="inline me-3 text-nowrap">匯款戶名：<span class="blue02 weight">'+aData[19]+'</span></div>';

				var bank_remit_code = '';
				if (aData[20] != null && aData[20] != '')
					bank_remit_code = '<div class="inline text-nowrap">匯款代碼：<span class="blue02 weight">'+aData[20]+'</span></div>';
					
				var zipcode = '';
				if (aData[3] != null && aData[3] != '')
					zipcode = aData[3];
					
				var county = '';
				if (aData[4] != null && aData[4] != '')
					county = aData[4];

				var town = '';
				if (aData[5] != null && aData[5] != '')
					town = aData[5];

				var address = '';
				if (aData[6] != null && aData[6] != '')
					address = aData[6];
					
					
				var files_total = '<span class="text-nowrap" id="files_total'+aData[7]+'"></span>';
				xajax_returnValue('$web_id',aData[7],aData[11],'$site_db','$fm','$memberID','$pubweburl','$pro_id');
					
					
				var m_info = '<div><span class="size12 weight text-nowrap me-1 vbottom">'+zipcode+'</span> <span class="size12 weight text-nowrap vbottom">'+county+'</span><span class="size12 weight text-nowrap vbottom">'+town+'</span> <span class="weight">'+address+'</span></div>'
							+'<div>'+bank_account+bank_account_no+bank_account_name+bank_remit_code+'</div>'
							+'<div class="inline">'+files_total+'</div>';

				$('td:eq(0)', nRow).html( m_supplier );
				$('td:eq(1)', nRow).html( m_contact );
				$('td:eq(2)', nRow).html( m_info );

				var show_btn = '';
				
				if ('$cando'=="Y") {

					var url1 = "openfancybox_edit('/index.php?ch=edit&auto_seq="+aData[7]+"&fm=$fm',800,'96%','');";
					var url3 = "openfancybox_edit('/index.php?tb=$tb&pro_id=$pro_id&auto_seq="+aData[7]+"&fm=attach02','96%','96%','');";

					if (('$super_admin' != "Y") && ('$super_advanced' == "Y")) {
						show_btn = '<div class="btn-group text-nowrap">'
								+'<button type="button" class="btn btn-light" onclick="'+url1+'" title="修改"><i class="bi bi-pencil-square"></i></button>'
								+'<button type="button" class="btn btn-light" onclick="'+url3+'" title="上傳檔案"><i class="bi bi-file-arrow-up"></i></button>'
								+'</div>';
					} else {
						var mdel = "myDel("+aData[7]+",'$site_db','$pro_id');";
						show_btn = '<div class="btn-group text-nowrap">'
								+'<button type="button" class="btn btn-light" onclick="'+url1+'" title="修改"><i class="bi bi-pencil-square"></i></button>'
								+'<button type="button" class="btn btn-light" onclick="'+url3+'" title="上傳檔案"><i class="bi bi-file-arrow-up"></i></button>'
								+'<button type="button" class="btn btn-light" onclick="'+mdel+'" title="刪除"><i class="bi bi-trash"></i></button>'
								+'</div>';
					}
				} else {
					show_btn = '<button type="button" class="btn btn-light py-0 my-0 gray"><i class="bi bi-ban"></i></button>';
				}

				$('td:eq(3)', nRow).html( '<div class="text-center">'+show_btn+'</div>' );
				
				return nRow;
			
			}
			
		});
	
		/* Init the table */
		oTable = $('#db_table').dataTable();
		
	} );
	
var myDel = function(auto_seq,site_db,pro_id){			

	Swal.fire({
	title: '您確定要刪除此筆資料嗎?',
	text: "",
	icon: "question",
	showCancelButton: true,
	confirmButtonColor: "#3085d6",
	cancelButtonColor: "#d33",
	cancelButtonText: "取消",
	confirmButtonText: "刪除"
	}).then((result) => {
		if (result.isConfirmed) {
			xajax_DeleteRow(auto_seq,site_db,pro_id);
		}
	});

};


var myDraw = function(){
	var oTable;
	oTable = $('#db_table').dataTable();
	oTable.fnDraw(false);
}

</script>

EOT;



?>
