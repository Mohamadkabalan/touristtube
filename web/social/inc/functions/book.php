<?php

/**
 * builds a book (pdf and 3d). options include:<br/>
 * <b>items</b>: array - an array of items to build into the book. required.<br/>
 * <b>cover</b>: string - path to the cover page. default null.<br/>
 * <b>default_item</b>: integer the defualt item to be shown on the cover page. default 0.<br/>
 * <b>background</b>: string. path to the backgorund image. default null<br/>
 * <b>output_path</b>: string - path to the output. required.<br/>
 * <b>output_name</b>: string - the name of the files be it pdf or images. required.<br/>
 * <b>template</b>: integer - which template to use. could be 0 => 2 (3by2) per page. default 0.<br/>
 * @param array $options.
 */

function bookBuild($in_options) {
	global $CONFIG;
	include_once('html2pdf/html2pdf.class.php');
	
	set_time_limit(0); 
	
	$default_opts = array(
		'items' => null,
		'cover_page' => null,
		'default_item' => 0,
		'background' => null,
		'output_path' => null,
		'template' => 0,
		'journalPdf' => 0,
		'journalFlash3d' => 0
	);
	
	$options = array_merge($default_opts, $in_options);
	
	$output_path = $CONFIG['server']['root'] . $options['output_path'];
	system('rm -rf ' . $output_path . 'full');
	system('rm -rf ' . $output_path . 'thumbs');

	$pdf_output_path =  $output_path . 'pdf/';
	$full_output_path = $output_path . 'full/';
	$thumb_output_path = $output_path . 'thumbs/';
	$xml_output_path = $output_path . 'xml/';
        
	@mkdir($pdf_output_path, 0777, true);
        @chmod($pdf_output_path, 0777);
	@mkdir($full_output_path, 0777, true);
        @chmod($full_output_path, 0777);
	@mkdir($thumb_output_path, 0777, true);
        @chmod($thumb_output_path, 0777);
	@mkdir($xml_output_path, 0777, true);
        @chmod($xml_output_path, 0777);

	$pdf_output = $pdf_output_path . $options['output_name'] . '.pdf';
	$full_output = $full_output_path . $options['output_name'] . '.jpg';
	$thumb_output = $thumb_output_path . $options['output_name'] . '.jpg';
	$xml_output = $xml_output_path . 'pages.xml';

	ob_start();
	if($options['template']==1){
    	include('../journalPdfTemplate/1.php');
	}else{
		include('../journalPdfTemplate/0.php');
	}
    $content = ob_get_clean();
	//exit($content);
	try{
        $html2pdf = new HTML2PDF('P', 'A4', 'en', $unicode=true, $encoding='UTF-8', $marges = array(0, 0, 0, 0));
        $html2pdf->writeHTML($content);
        $html2pdf->Output($pdf_output,'F');
    }catch(HTML2PDF_exception $e) {
		echo $e->getMessage();
        return false;
    }
	if($options['journalFlash3d'] == '1'){
		$full_cmd = "convert -density 128 $pdf_output -quality 100 $full_output";               
		system($full_cmd);
		$thumb_cmd = "convert -density 128 $pdf_output -quality 100 $thumb_output";                
		system($thumb_cmd);
	
		//ISO-8859-15
		$pages_xml = sprintf('<?xml version="1.0" encoding="UTF-8"?>
	<pages>
	<config>
		<pdf_file>%s</pdf_file>
		<page_cache>1</page_cache>
		<zoom_fix>20</zoom_fix>
		<page_height>20</page_height>
		<color_innen>333333</color_innen>
		<color_aussen>000000</color_aussen>
		<document_height>300</document_height>
	</config>',$options['output_path'] . 'pdf/' . $options['output_name'] . '.pdf' );
		$pages_xml .= "\r\n";
		///////////////////////
		//get all files
		$dir = opendir($thumb_output_path);
		$pages = array();
		while($file = readdir($dir) ){
			if($file == '.' || $file == '..') continue;
			$pages[] = $file;
		}
		closedir($dir);
		///////////////////////
	
		if(count($pages) % 2 != 0){
			copy($CONFIG['server']['root'] . 'media/images/journal_blank.jpg', $full_output_path . $options['output_name'] . '-' . count($pages) . '.jpg' );
			copy($CONFIG['server']['root'] . 'media/images/journal_blank.jpg', $thumb_output_path . $options['output_name'] . '-' . count($pages) . '.jpg' );
			$pages[] = $options['output_name'] . '-' . count($pages) . '.jpg';
		}
	
		$i = 0;
		while($i < count($pages) - 1){
			$front = $options['output_name'] . '-' . $i . '.jpg';
			$back = $options['output_name'] . '-' . ($i+1) . '.jpg';
			$pages_xml .= sprintf("<page><front>%s</front><back>%s</back></page>\r\n",$front,$back);
			$i+=2;
		}
	
		$pages_xml .= '</pages>';
	
		file_put_contents($xml_output, $pages_xml);
	}
	
}