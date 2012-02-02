<?php
// The Class is for the File handling

class DFile
{
	/**
	Funtion to read file
	*/
	public function readFile($filename)
	{
		$file = fopen("$filename", "r") or exit("Unable to open file!");
		
		while(!feof($file))
  		{
  			echo fgets($file). "<br />";
  		}
		fclose($file);	
	}	
	
}
?>