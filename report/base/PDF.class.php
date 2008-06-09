<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
require('fpdf.php');

class PDF extends FPDF
{
	public $B;
	public $I;
	public $U;
	public $HREF;
	
	public function PDF($orientation='P',$unit='mm',$format='A4') {
		//Call parent constructor
		$this->FPDF($orientation,$unit,$format);
		//Initialization
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
	}
	
	public function WriteHTML($html) {
		//HTML parser
		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				//Tag
				if($e{0}=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract attributes
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
						if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}
	
	public function OpenTag($tag,$attr) {
		//Opening tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}
	
	public function CloseTag($tag) {
		//Closing tag
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
	}
	
	public function SetStyle($tag,$enable) {
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
				$style.=$s;
		$this->SetFont('',$style);
	}
	
	public function PutLink($URL,$txt) {
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

	public function FancyTable($header,$data,$widths) {
		//Colors, line width and bold font
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//Header

		for($i=0;$i<count($header);$i++) {
			$this->Cell($widths[$i],8,$header[$i],1,0,'C',1);
		}
		$this->Ln();
		//Color and font restoration
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Data
		$fill=0;
		foreach($data as $row) {
			for ($i=0;$i<count($row);$i++) {
				$this->Cell($widths[$i],8,$row[$i],'LR',0,'L',$fill);
			}
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($widths),0,'','T');
	}
}

?>