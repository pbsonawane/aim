<?php
namespace App\Libraries;
use View;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
class Emlib {

	public function __construct()
    {
        //$this->myiam = $myiam;
      
    }
	function emgridtop($options = [], $advview = "", $advsearch_options = [],$advsearch_setting = [])
	{
		$gridadvsearch = isset($options['gridadvsearch']) ? $options['gridadvsearch'] : false;
		$data['gridadvsearch'] = $gridadvsearch;
		$data['jsfunction'] = isset($options['jsfunction']) ? $options['jsfunction'] : '';
		$data['advsearchform'] = $this->emadvsearch($advview, $advsearch_options,$data['jsfunction'],$advsearch_setting);
		$data['gridsearch'] = isset($options['gridsearch']) ? $options['gridsearch'] : false;
		$data['gridpdf'] = isset($options['gridpdf']) ? $options['gridpdf'] : false;
		$data['gridcsv'] = isset($options['gridcsv']) ? $options['gridcsv'] : false;
		$data['gridprint'] = isset($options['gridprint']) ? $options['gridprint'] : false;
		$data['gridibsearch'] = isset($options['gridibsearch']) ? $options['gridibsearch'] : true;
		$data['gridcollspase'] = isset($options['gridcollspase']) ? $options['gridcollspase'] : false;
		$data['gridexpand'] = isset($options['gridexpand']) ? $options['gridexpand'] : false;
		$data['importdevices'] = isset($options['importdevices']) ? $options['importdevices'] : false;
		$data['extradata'] = isset($options['extradata']) ? $options['extradata'] : [];
		return view('emgridtop',$data);
		//$view = View::make('emgridtop', $data);
		//$contents = $view->render();
		//return $contents;
	}
	function emadvsearch($advview = "emadvsearch", $options = [],$jsfunction = '',$advsearch_setting = [])
	{
		$default_options = ["bv", "dc","deptype"];
		$element_options = is_array($options) && count($options) > 0 ? $options : $default_options;
		$data['element_options'] = $element_options;
		// for default parameters masters
		$limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        $options = ['form_params' => $form_params];
        if (in_array("usertypes", $element_options))
		{	
			 $data['usertypes'] =['staff' => 'staff' , 'client' => 'client'];
		}	
		if (in_array("roles", $element_options))
		{	
			 $this->myiam = new IamService;
			 $roles  =  $this->myiam->getRoles($options);
			 $data['roles'] = _isset(_isset($roles, 'content'), 'records');
		}	
		if (in_array("departments", $element_options))
		{	
			 $this->myiam = new IamService;
			 $roles  =  $this->myiam->getDepartment($options);
			 $data['departments'] = _isset(_isset($roles, 'content'), 'records');
		}	
		if (in_array("designations", $element_options))
		{	
			 $this->myiam = new IamService;
			 $roles  =  $this->myiam->getDesignations($options);
			 $data['designations'] = _isset(_isset($roles, 'content'), 'records');
		}	
		if (in_array("organizations", $element_options))
		{	
			 $this->myiam = new IamService;
			 $roles  =  $this->myiam->getOrg($options);
			 $data['organizations'] = _isset(_isset($roles, 'content'), 'records');
		}	
        /*ITAM */
        if (in_array("contract_type", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $contract_type  =  $this->myitam->getcontracttype($options);
			 $data['contract_type'] = _isset(_isset($contract_type, 'content'), 'records');
        }	
        
        if (in_array("contract_status", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $contract_status  =  $this->myitam->getcontract($options);
			 $data['contract_status'] = _isset(_isset($contract_status, 'content'), 'records');
		}

		if (in_array("template_category", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $template_category  =  $this->myitam->getetemplatecategory($options);
			 $data['template_category'] = _isset(_isset($template_category, 'content'), 'records');
		}	
		if (in_array("software_type", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $software_type  =  $this->myitam->getsoftwaretype($options);
			 $data['software_type'] = _isset(_isset($software_type, 'content'), 'records');
		}	
		if (in_array("software_category", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $software_category  =  $this->myitam->getsoftwarecategory($options);
			 $data['software_category'] = _isset(_isset($software_category, 'content'), 'records');
		}	

		if (in_array("software_manufacturer", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $software_manufacturer  =  $this->myitam->getsoftwaremanufacturer($options);
			 $data['software_manufacturer'] = _isset(_isset($software_manufacturer, 'content'), 'records');
		}	
        if (in_array("users", $element_options))
		{	
			 $this->myiam = new IamService;
			 $roles  =  $this->myiam->getUsers($options);
			 $data['users'] = _isset(_isset($roles, 'content'), 'records');
		}
		if (in_array("vendors", $element_options))
		{	
			 $this->myitam = new ItamService;
			 $roles  =  $this->myitam->getvendors($options);
			 $data['vendors'] = _isset(_isset($roles, 'content'), 'records');
		}
               
		/*if (in_array("bv", $element_options))
			$data['bus'] = $this->cm->master->getbubv(array('status' => 'y'));
		if (in_array("probe", $element_options))
			$data['probes'] = $this->cm->master->probes(array('status' => 'y'));
		if (in_array("os", $element_options))
			$data['oses'] = $this->cm->master->os(array('status' => 'y'));
		if (in_array("dc", $element_options))
			$data['dcs'] = $this->cm->master->datacenters(array('status' => 'y'));
		if (in_array("mon_teplates", $element_options))
			$data['mon_teplates'] = $this->cm->master->mon_templats();

		if (in_array("deptype", $element_options))
		{
			if(count($advsearch_setting['deptype']) > 0)
			{
				$depids = '';
				if($advsearch_setting['deptype']['id'] != '')
					$depids = $advsearch_setting['deptype']['id'];
			}
			$data['deptypes'] = $this->cm->master->deptypes(array('status' => 'y','dep_type_id'=>$depids));
		}
		if (in_array("ownership", $element_options))
			$data['ownerships'] = $this->cm->master->ownership(array('status' => 'y'));
		if (in_array("status", $element_options))
		{
			if(count($advsearch_setting) > 0)
			{
				$statusids = '1,3,4,15,20,21,23';
				if($advsearch_setting['status']['id'] != '')
					$statusids = $advsearch_setting['status']['id'];
			}
			$data['statuses'] = $this->cm->master->statuses(array('status' => 'y','status_id'=>$statusids));
		}


		//echo $this->cm->db->last_query(); die();*/
		$data['advsearch_setting'] = $advsearch_setting;
		$data['jsfunction'] = $jsfunction;
		$advview = $advview != '' ? $advview : "emadvsearch";
        //$advsearch = $this->cm->load->view($advview, $data, TRUE);
        return view($advview, $data);
		//return $advsearch;
	}
	function emgrid($dbdata, $view="", $columns = [], $paging = [],$show_all="y")
	{


		// $pr_id_arrays = array_column($pr_id_status,'difference_prpo_asset_count','pr_id');

		$view_data = $return_array = [];
		$showpagination = isset($paging['showpagination']) ? $paging['showpagination'] : false;
		$showserial = isset($paging['showserial']) ? $paging['showserial'] : false;
		$limit = $paging['limit'];
		if ($showpagination == true)
		{
			$total_rows = $paging['total_rows'];
			$page = $paging['page'];
			$offset = $paging['offset'];
			$jsfunction = $paging['jsfunction'];
		}
		$column_cnt = count($columns);
		$data_column_cnt = isset($dbdata[0]) ? count($dbdata[0]) : 0;
		$data_cnt = $dbdata ? count($dbdata) : 0;
		if ($data_column_cnt > 0 && trim($view) == '')
		{
			if ($column_cnt != $data_column_cnt)
				return '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Count mismatch between table column and data</div>';
			else if($column_cnt <= 0)
				return '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Table columns should not be empty"</div>';
		}
		$finaldata['total_rows'] = isset($total_rows) ? $total_rows : 0;
		$finaldata['limit'] = isset($limit) ? $limit : 0;
		$finaldata['offset'] = isset($offset) ? $offset : 0;
		if ($data_cnt > 0)
		{
			ini_set("memory_limit","-1");
			if ($showpagination == true)
			{
				$noOfPages = is_numeric($total_rows) > 0 && $limit > 0 ? ceil($total_rows / $limit) : 1;
				$page = (in_array($page + 1, range(1, $noOfPages)) ? $page : 0);
				$paging['offset'] = $offset;
				$paging['noOfPages'] = $noOfPages;
				$paginglink = View::make('empagination', $paging)->render();
				$finaldata['paginglink'] = $paginglink;
				$finaldata['page'] = $page;
			}
		}
		$finaldata['_scroll_id'] = _isset($paging,'_scroll_id');
		$view_data['columns'] = $columns;
		$view_data['column_cnt'] = $column_cnt;
		$view_data['data_cnt'] = $data_cnt;
		$view_data['showserial'] = $showserial;
		$view_data['page'] = isset($page) ? $page : 0;
		$view_data['limit'] = $limit;
		$view_data['dbdata'] = $dbdata;
		// $view_data['pr_id_status'] = $pr_id_arrays;
		$view_data['offset'] = isset($offset) ? $offset : 0;
		$view = $view != '' ? $view : "emgriddata";
		$tabledata = View::make($view, $view_data)->render();
		$finaldata['tabledata'] = $tabledata;
		$finaldata['showpagination'] = $showpagination;
		$finaldata['jsfunction'] = $paging['jsfunction'];
		$finaldata['show_all'] = $show_all;
		$datagrid = View::make("emgrid", $finaldata)->render();
		return $datagrid;
	}
	function tblstart()
	{
		$tbl = '';
		$tbl = '<table cellspacing="0" cellpadding="2" width="100%" style="border-collapse:collapse;">';
		return $tbl;
	}
	function tblend()
	{
		$tbl = '';
		$tbl = '</table>';
		return $tbl;
	}
	function tblhead($columns, $pdfconfig = [])
	{
		$showserial = isset($pdfconfig['showserial']) ? $pdfconfig['showserial'] : false;
		$header = '<thead><tr bgcolor="#CCCCCC">';
		if ($showserial == true)
			$header .= '<td align="center" width="4%" style="border:0px solid #999999;"><strong>Sr.No.</strong></td>';
		if (is_array($columns) && count($columns) > 0)
		{
			foreach($columns as $key => $name)
			{
				if ($key != 'rowoperations')
					$header .= '<td style="border:0px solid #999999;"><strong>'.$name.'</strong></td>';
			}
		}
		$header .= '</tr></thead>';
		return $header;
	}
	function tblheadprint($columns, $pdfconfig = [])
	{
		$showserial = isset($pdfconfig['showserial']) ? $pdfconfig['showserial'] : false;
		$header = '<thead><tr>';
		if ($showserial == true)
			$header .= '<th align="center" width="4%">Sr.No.</th>';
		if (is_array($columns) && count($columns) > 0)
		{
			foreach($columns as $key => $name)
			{
				if ($key != 'rowoperations')
					$header .= '<th>'.$name.'</th>';
			}
		}
		$header .= '</tr></thead>';
		return $header;
	}
	function emprint($columns, $dbdata, $prnconfig = [])
	{
		$html = '';
		$showserial = isset($prnconfig['showserial']) ? $prnconfig['showserial'] : false;
		$page = isset($prnconfig['page']) ? $prnconfig['page'] : false;
		$limit = isset($prnconfig['limit']) ? $prnconfig['limit'] : false;
		$rowsperpage = isset($prnconfig['rowsperpage']) ? $prnconfig['rowsperpage'] : 25;
		$data_cnt = count($dbdata);
		if ($data_cnt > 0)
		{
			$sr_no = $page * $limit;
			$html .= '<table class="table table-striped table-bordered" width="100%">';
			$html .= $this->tblheadprint($columns, $prnconfig);
			$html .= '<tbody>';
			//for($i=0,$j=1;$i<$data_cnt;$i++,$j++)
			foreach($dbdata as $data)
			{
				$sr_no = $sr_no + 1;
				$html .= '<tr>';
				if ($showserial == true)
					$html .= '<td align="center">'.$sr_no.'.</td>';
				if (is_array($columns) && count($columns) > 0)
				{
					foreach($columns as $tblfield => $name)
					{
						if ($tblfield != 'rowoperations' && isset($data[$tblfield]))
							$html .= '<td>'.$data[$tblfield].'</td>';
					}
				}
				$html .= '</tr>';
			}
			$html .= '</tbody>';
			$html .= $this->tblend();
		}
		else
		{
			$html = 'No Data';
		}
		return $html;
		exit;
	}
	function empdf($title, $columns = [], $dbdata = [], $pdfconfig = [], $coverpage = [], $html = '')
	{
		$this->cm->load->helper('tcpdf');
		$obj_pdf = initpdf($title);
		$filename = str_ireplace(' ', "_", strtolower($title)).'_'.date('dmY_Hi');
		$coverpage['obj_pdf'] = $title;
		$coverpage['reporthead'] = $title;
		$coverpage['obj_pdf'] = $obj_pdf;
		add_cover_page($coverpage);

		if($html == '')
		{
			$showserial = isset($pdfconfig['showserial']) ? $pdfconfig['showserial'] : false;
			$page = isset($pdfconfig['page']) ? $pdfconfig['page'] : false;
			$limit = isset($pdfconfig['limit']) ? $pdfconfig['limit'] : false;
			$rowsperpage = isset($pdfconfig['rowsperpage']) ? $pdfconfig['rowsperpage'] : 25;
			$column_cnt = count($columns);
			$data_column_cnt = isset($dbdata[0]) ? count($dbdata[0]) : 0;
			$data_cnt = count($dbdata);
			if ($data_cnt > 0)
			{
				$html = '';
				$data_page = array_chunk($dbdata, $rowsperpage);
				$data_page_cnt = count($data_page);
				$obj_pdf->AddPage();
				if ($data_page_cnt > 0 && $html == '')
				{
					$sr_no = $page * $limit;
					for($k=0;$k<$data_page_cnt;$k++)
					{
						$html .= $this->tblstart();
						$page_records = $data_page[$k];
						if ($k == 0)
							$html .= $this->tblhead($columns, $pdfconfig);
						$html .= '<tbody>';
						$cn = count($page_records);
						for($i=0,$j=1;$i<$cn;$i++,$j++)
						{
							$sr_no = $sr_no + 1;
							$html .= '<tr>';
							if ($showserial == true)
								$html .= '<td align="center" width="4%" style="border:0px solid #999999;">'.$sr_no.'.</td>';
							if (is_array($columns) && count($columns) > 0)
							{
								foreach($columns as $tblfield => $name)
								{
									if ($tblfield != 'rowoperations' && isset($page_records[$i][$tblfield]))
										$html .= '<td style="border:0px solid #999999;">'.$page_records[$i][$tblfield].'</td>';
									else
										$html .= '<td style="border:0px solid #999999;">--</td>';
								}
							}
							$html .= '</tr>';
						}
						$html .= '</tbody>';
						$html .= $this->tblend();
						$objpdf = generatepdf($obj_pdf, $html);
						$html == '';
					}
				}
			}
		}
		else
		{
			$obj_pdf->AddPage();
			if ($html == '')
				$html = 'No Data';
			$objpdf = generatepdf($obj_pdf, $html);
			$html = '';
		}
		printpdf($objpdf, $filename);
		exit;
	}
	function emcsv($title, $filename, $columns, $dbdata, $csvconfig = [])
	{
		ob_start();
		$filename = str_ireplace(' ', "_", strtolower($filename)).'_'.date('dmY_Hi');
		$csv_top = $title."\n\n";
		$csv_data = '';
		$column_cnt = count($columns);
		$showserial = isset($csvconfig['showserial']) ? $csvconfig['showserial'] : false;
		$page = isset($csvconfig['page']) ? $csvconfig['page'] : false;
		$limit = isset($csvconfig['limit']) ? $csvconfig['limit'] : false;
		$data_column_cnt = isset($dbdata[0]) ? count($dbdata[0]) : 0;
		$data_cnt = count($dbdata);
		if ($data_cnt > 0 && $csv_data == '')
		{
			if ($showserial == true)
				$csv_top .= 'Sr.No.,';
			if (array_key_exists('rowoperations',$columns))
				$columns['rowoperations'] = '';
			$columns_vals = array_values($columns);
			$csv_top .= implode(",", $columns_vals);
			$csv_top .= "\n";
			$sr_no = $page * $limit;
			$csv_data = $csv_top;
			foreach($dbdata as $row)
			{
				$sr_no = $sr_no + 1;
				if ($showserial == true)
					$csv_data .= $sr_no.",";
				if (is_array($columns) && count($columns) > 0)
				{
					foreach($columns as $tblfield => $name)
					{
						if ($tblfield != 'rowoperations' && isset($row[$tblfield]))
							$csv_data .= strip_tags($row[$tblfield]).",";
						else
							$csv_data .= "--,";
					}
				}
				$csv_data .= "\n";
			}
		}
		else
		{
			if ($csv_data == '')
				$csv_data = 'No Data';
		}
		$this->cm->load->helper('download_helper');
		force_download($filename.'.csv', trim($csv_data));
		exit;
	}
	function emexcel($title, $filename, $columns, $dbdata,$excelconfig = [],$from_date = '',$to_date = '')
	{
		$showserial = isset($excelconfig['showserial']) ? $excelconfig['showserial'] : false;
		$page = isset($excelconfig['page']) ? $excelconfig['page'] : false;
		$limit = isset($excelconfig['limit']) ? $excelconfig['limit'] : false;
		$data_column_cnt = isset($dbdata[0]) ? count($dbdata[0]) : 0;

		$objPHPExcel = $this->cm->excel;
		$objPHPExcel->getProperties()->setCreator("AIM - Asset Inventory Manager") //AIM - Asset Inventory Manager Enlight360
					 ->setLastModifiedBy("AIM - Asset Inventory Manager")
					 ->setTitle($title)
					 ->setSubject($title)
					 ->setDescription($title)
					 ->setKeywords("office 2007 openxml php")
					 ->setCategory("Security");

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('AIM - Asset Inventory Manager'); //Enlight360 Logo
		$objDrawing->setDescription('AIM - Asset Inventory Manager'); //Enlight360 Logo
		$objDrawing->setPath('./emagic_app/images/emagic-logo-white.png');
		$objDrawing->setHeight(50);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		$objPHPExcel->setActiveSheetIndex(0);
		//name the worksheet

		$objPHPExcel->getActiveSheet()->setTitle($title);
		//set cell E1 content with some text
		$objPHPExcel->getActiveSheet()->mergeCells('C1:F1');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $title);
		//$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//make the font become bold
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('E1')->getFill()->getStartColor()->setARGB('#FFF');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', $this->datetime_display);
		$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray(
			[
				'font'  => [
					'bold'  => true,
					'color' => ['rgb' => '000000'],
				]
			]
		);
		$objPHPExcel->getActiveSheet()->mergeCells('C2:F2');
			if($from_date != '' && $to_date != '')
				$objPHPExcel->getActiveSheet()->setCellValue('B2', 'From Date: '.$from_date."   To Date: ".$to_date);
			//$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$row = 4; // 1-based index
			$col = 0;
			$col_rel = 1;

			// columns creation
			if ($showserial == true)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, 'Sr.No.');
				$xcol = columnLetter($col_rel++);
				$excelcol[] = $xcol;
				formatcell($objPHPExcel,$xcol.$col_rel);
			}
			foreach($columns as $rep)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, $rep);
				$xcol = columnLetter($col_rel++);
				$excelcol[] = $xcol;
				formatcell($objPHPExcel,$xcol.$col_rel);
			}
			$fieldrow = $row;
			$col=0;
			$col_rel=1;
			$row++;

			//*****************************************************
			$data_cnt = count($dbdata);
			if ($data_cnt > 0 && $csv_data == '')
			{
				$columns_vals = array_values($columns);
				$csv_top .= implode(",", $columns_vals);
				$csv_top .= "\n";
				$sr_no = $page * $limit;
				$csv_data = $csv_top;
				foreach($dbdata as $da)
				{
					$sr_no = $sr_no + 1;
					if ($showserial == true)
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, trim($sr_no));
					}

					if (is_array($columns) && count($columns) > 0)
					{
						foreach($columns as $tblfield => $name)
						{

							$val_ = $da[$tblfield];
							if($val_ == '')
								$val_ = '--';
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, trim($val_));
						}
					}
					$row++;
					$col=0;

				}
			}
			else
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row, "No Data");
			}
			$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objPHPExcel->getActiveSheet()->getStyle(current($excelcol).$fieldrow.":".end($excelcol).$fieldrow)->applyFromArray(
						[
							'fill' => [
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => ['rgb' => '538DD5']
							],
							'font'  => [
								'bold'  => true,
								'color' => ['rgb' => 'FFFFFF'],
							]
						]
			);
			$filename=$filename.date("Y-m-d h:i:s").".xlsx"; //save our workbook as this file name
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$file = "./emagic_app/uploads/schedulereports/".$filename;
			$objWriter->save($file);
			$content = file_get_contents($file);
			$this->cm->load->helper('download_helper');
			force_download($filename, $content);
			exit;
	}
}
?>
