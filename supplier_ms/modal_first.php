<?php

//error_reporting(E_ALL); 
//ini_set('display_errors', '1');


session_start();

$memberID = $_SESSION['memberID'];
$powerkey = $_SESSION['powerkey'];


@include_once("/website/class/".$site_db."_info_class.php");

//載入公用函數
@include_once '/website/include/pub_function.php';


$m_location		= "/website/smarty/templates/".$site_db."/".$templates;
$m_pub_modal	= "/website/smarty/templates/".$site_db."/pub_modal";


$sid = "";
if (isset($_GET['sid']))
	$sid = $_GET['sid'];

	//程式分類
	$ch = empty($_GET['ch']) ? 'default' : $_GET['ch'];
	switch($ch) {
		case 'add':
			$title = "新增資料";
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_add.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'edit':
			$title = "資料編輯";
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_modify.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'mview':
		case 'view':
			$title = "資料瀏覽";
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_view.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			//$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'attach':
			$title = "上傳附加檔案";
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/attach.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;		
		case 'cpm':
			$title = "修改標題";
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/caption_modify.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'attach_reply':
			$title = "上傳附加檔案";
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/attach_reply.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;		
		case 'replyadd':
			$title = "新增資料".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/sendreply.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'readed':
			$title = "選取清單".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_readed.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
		case 'jointlist':
			$title = "協同人員名單".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/projectjoint_list.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
		case 'contactadd':
			$title = "新增資料".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_contact_add.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'contactedit':
			$title = "資料編輯".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_contact_modify.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
		case 'importexcel':
			$title = "匯入客戶Excel資料檔".$mt;
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/importexcel.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
		case 'exportexcel':
			$title = "匯入客戶Excel資料檔".$mt;
			$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/exportexcel.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
		case 'report':
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_report.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
			/*
		case 'excelreport':
			$title = "匯出Excel報表".$mt;
			if (empty($sid))
				$sid = "view01";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier_report_excel.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			break;
			*/
		default:
			if (empty($sid))
				$sid = "pjcenter";
			$modal = $m_location."/sub_modal/base/supplier_ms/supplier.php";
			include $modal;
			$smarty->assign('show_center',$show_center);
			$smarty->assign('xajax_javascript', $xajax->getJavascript('/xajax/'));
			break;
	};

?>