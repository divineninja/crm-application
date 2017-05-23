<?php
class export extends Controller {
	
	function __construct(){
		parent::__construct();
		
		require( dirname( dirname( __FILE__ ) ).'/libs/PHPExcel/Calculation.php' );
		require( dirname( dirname( __FILE__ ) ).'/libs/PHPExcel/Cell.php' );
		
		/** Error reporting */	
		error_reporting(E_ALL);
		
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Asia/Hong_Kong');
		
		
		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
		
	}
	
	function export( $id = '' ){	
		$this->{$id}($id);
	}
	
	function vendors($table_name){			
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()
			 ->setCreator("Administrator")
			 ->setLastModifiedBy("Administrator")
			 ->setTitle("Office 2007 XLSX Downloadable Document")
			 ->setSubject("Office 2007 XLSX Downloadable Document")
			 ->setDescription("Downloadable document for Office 2007 XLSX, generated using PHP classes.")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Downloadable result file");
							 
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');
			
		$objPHPExcel->getActiveSheet()->setTitle('sheet1');
		
		$objPHPExcel->setActiveSheetIndex(0);
		/* // Redirect output to a client’s web browser (Excel5) */
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$table_name.'.xls"');
		header('Cache-Control: max-age=0');
		/* // If you're serving to IE 9, then the following may be needed */
		header('Cache-Control: max-age=1');

		/* // If you're serving to IE over SSL, then the following may be needed */
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); /* // Date in the past */
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); /* // always modified */
		header ('Cache-Control: cache, must-revalidate'); /* // HTTP/1.1 */
		header ('Pragma: public'); /* // HTTP/1.0 */

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
}