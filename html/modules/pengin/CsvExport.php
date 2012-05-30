<?php
/*
	Example: 
	$csv = new Arbeit_Component_CsvExport;
	$csv->fileName = "Penguin.csv";
	$csv->sendHeader();
	$csv->addRow("Name", "Height", "Weight");
	$csv->addRow("Emperor Penguin", "120cm", "23 - 45kg");
	$csv->addRow("Humboldt Penguin", "68cm", "3.5kg");
	$csv->addRow("Cape Penguin", "63cm", "3.4kg");
	$csv->addRow("Rockhopper Penguin", "57 - 65cm", "3.5 - 4kg");
	
	// OR
	
	$csv->addRow(array("Name", "Height", "Weight"));
	$csv->addRow(array("Emperor Penguin", "120cm", "23 - 45kg"));
	$csv->addRow(array("Humboldt Penguin", "68cm", "3.5kg"));
	$csv->addRow(array("Cape Penguin", "63cm", "3.4kg"));
	$csv->addRow(array("Rockhopper Penguin", "57 - 65cm", "3.5 - 4kg"));
*/

class Pengin_CsvExport
{
	public $fileName  = 'export.csv';
	public $delimiter = ','; 
	public $enclosure = '"'; 
	public $outputEncode = 'sjis-win';
	public $inputEncode  = 'UTF-8';

	public $isConvertEncoding = true;
	
	protected $outstream = null;

	public function __construct()
	{
		$this->startRender();
	}

	public function __destruct()
	{
		$this->endRender();
	}

	public function clearObFilter()
	{
		while ( ob_get_level() > 0 )
		{
			ob_end_clean();
		}
	}

	public function sendHeader()
	{
		header('Content-Type: application/octet-stream');
//		header("Content-type:application/vnd.ms-excel"); 
		header('Content-Disposition: attachment; filename='.$this->fileName);
		header('Cache-Control: public');
		header('Pragma: public');
	}

	public function addRow()
	{
		$length = func_num_args();
		$row    = func_get_args();

		if ( $length === 1 and is_array(reset($row)) )
		{
			$row = reset($row);
		}

		$this->_outputRow($row);
	}

	public function startRender()
	{
		$this->outstream = fopen('php://output', 'w');
	}

	public function endRender()
	{
		fclose($this->outstream);
	}

	protected function _outputRow(array $row)
	{
		if ( $this->isConvertEncoding === true )
		{
			$this->_convertEncoding($row);
		}

		fputcsv($this->outstream, $row, $this->delimiter, $this->enclosure);
	}
	
	protected function _convertEncoding(array &$row)
	{
		foreach ( $row as &$column )
		{
			$column = mb_convert_encoding($column, $this->outputEncode, $this->inputEncode);
		}
	}
}
