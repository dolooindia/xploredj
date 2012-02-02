<?php
class DataMysql
{
	private $conId;	
	
	private $host;
	
	private $usr;
	
	private $password;
	
	private $database;
	
	const OPTIONS=4;	
	
	public function __construct($options=array()){

		if(count($options)!=self::OPTIONS){
			throw new Exception('Invalid number of connection parameters');
		}

		foreach($options as $parameter=>$value){
			
			if(!$value){
				throw new Exception('Invalid parameter '.$parameter);
			}
			$this->{$parameter}=$value;

		}
		$this->connectDB();
	}

	
	private function connectDB() {
		if(!$this->conId = mysql_connect($this->host, $this->usr, $this->password)) {
			throw new Exception ('Error connecting to server');
		}
		if(!mysql_select_db($this->database, $this->conId)){
			throw new Exception ('Error selecting database');
		}
	}
	
/**
 * This function executes a query; if error occurs, it displays a message and stops operation.
 *
 * @param string $query query string.
 */
public function executeQuery($query) {

	$resource = mysql_query($query) or die("Could not execute query !!! $query");
}

/**
 * This function returns a value from a table row with the given value.
 *
 * @param string  $findValue            value, tha is to be find.
 * @param string  $fromTable            table name.
 * @param string  $fromField            field name, that contains the value.
 * @param string  $returnValueFromField object value field name.
 * @param string  $customQuery          [optional] custom query.
 * @return string
 */
public function getReference($findValue, $fromTable, $fromField, $returnValueFromField, $customQuery = "") {

	if ($customQuery == "") {
		$customQuery = "select $returnValueFromField from $fromTable where $fromField = '$findValue'";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $query");
	$dataRow  = mysql_fetch_array($resource);

	return $dataRow[0];
}

/**
 * This function returns data rows in an array.
 *
 * @param string $fields           column names, those shall be collected from table(s), separated by 'comma' ','.
 * @param string $tables           table list, separated by 'comma'.
 * @param string $filterFields     left fields in filter expressions, separated by 'pipe' '|'.
 * @param string $expressionValues right fields in filter expressions, separated by 'pipe' '|'.
 * @param string $orderBy          order fields, separated by 'comma' ','.
 * @param string $customQuery      [optional] custom query.
 * @return array
 */
public function getDataRowsInArray($fields, $tables, $filterFields, $expressionValues, $orderBy, $customQuery = "") {

	if ($customQuery == "") {

		$customQuery = "select $fields from $tables ";

		if($filterFields != "") {

			$filterArray = explode("|", $filterFields);
			$expressionArray = explode("|", $expressionValues);

			for($i=0; $i<count($filterArray); $i++) {
				$filter[] = $filterArray[$i]."=".$expressionArray[$i];
			}

			$filterString = implode($filter, " and ");
			$customQuery .= " where $filterString";
		}

		if ($orderBy != "") $customQuery .= " order by $orderBy";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");

	while ($dataRow = mysql_fetch_row($resource)) {

		$fetchedDataArray[] = $dataRow;
	}

	return $fetchedDataArray;
}

/**
 * This function prepares script for combo list.
 *
 * @param string $primaryKeyFieldName primary key field name, it is for combo value.
 * @param string $valueFieldName      value field name, it is for combo text.
 * @param string $tableName           table name
 * @param string $orderBy             order by field name.
 * @param string $selectedValue       selected text value [0] or text.
 * @param string $defaultText         default text; combo text for index 0.
 * @param string $customQuery         [optional] custom query.
 * @return string                     html script.
 */
public function getComboList($primaryKeyFieldName, $valueFieldName, $tableName, $orderBy, $selectedValue, $defaultText, $customQuery = "") {

	if ($customQuery == "") {

		$customQuery = "select $primaryKeyFieldName,$valueFieldName from $tableName order by $orderBy";
	}

	if(!($resource = mysql_query($customQuery))) return ("Could not execute query !!! $customQuery");
//	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");

	if ($selectedValue == "0" || $selectedValue == "") {

		$script = "<option selected value='0'>$defaultText</option>\n";

		while ($dataRow = mysql_fetch_row($resource)) {

			$script .= "<option value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
		}
	} else {

		$script = "<option value='0'>$defaultText</option>\n";

		while ($dataRow = mysql_fetch_row($resource)) {

			if ($dataRow[1] == $selectedValue)	$script .= "<option selected value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
			else $script .= "<option value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
		}
	}

	return $script;
}

public function getListBoxList($primaryKeyFieldName, $valueFieldName, $tableName, $orderBy, $selectedValue, $defaultText, $customQuery = "") {

	if ($customQuery == "") {

		$customQuery = "select $primaryKeyFieldName,$valueFieldName from $tableName order by $orderBy";
	}

	if(!($resource = mysql_query($customQuery))) return ("Could not execute query !!! $customQuery");
//	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");

	if ($selectedValue == "0" ) {

		while ($dataRow = mysql_fetch_row($resource)) {

			$script = "<option value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
		}
	} else {

    		while ($dataRow = mysql_fetch_row($resource)) {

			if ($dataRow[1] == $selectedValue)	$script .= "<option selected value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
			else $script .= "<option value='".$dataRow[0]."'>".$dataRow[1]."</option>\n";
		}
	}

	return $script;
}
/**
 * This function is for database connection and selection.
 *
 */

/* function connectDB() {

	mysql_connect("localhost", "root", "rite123") or die("could not connect database.");
	mysql_select_db("cms") or die("Could not select database");
}*/



/**
 * this function checks a table whether a particular value is unique or not.
 *
 * @param string   $findValue         search value.
 * @param string   $findValueInColumn column name.
 * @param string   $tableName         table name.
 * @param string   $customQuery       [optional] custom query.
 * @return boolean
 */
public function isDuplicate($findValue, $findValueInColumn, $tableName, $customQuery) {

	if ($customQuery == "") {
		$customQuery = "select count(*) from $tableName where $findValueInColumn='$findValue'";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");
	$dataRow = mysql_fetch_row($resource);

	if($dataRow[0] == "0") return false;
	else return true;
}

/**
 * this function returns the occurence of a certain value in a table.
 *
 * @param string   $findValue         search value.
 * @param string   $findValueInColumn column name.
 * @param string   $tableName         table name.
 * @param string   $customQuery       [optional] custom query.
 * @return integer
 */
public function countRow($findValue, $findValueInColumn, $tableName, $customQuery) {

	if ($customQuery == "") {
		$customQuery = "select count(*) from $tableName where $findValueInColumn='$findValue'";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");
	$dataRow = mysql_fetch_row($resource);

	return $dataRow[0];
}

/**
 * This function gets max value from a field.
 *
 * @param string   $fieldname   field name.
 * @param string   $tableName   table name.
 * @param string   $customQuery [optional] custom query.
 * @return integer
 */
public function getMaxId($fieldName, $tableName, $customQuery) {

	if ($customQuery == "") {
		$customQuery = "select max($fieldName) from $tableName";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");
	$dataRow = mysql_fetch_array($resource);
//	echo "<hr>From Library: ".$dataRow[0];
	return $dataRow[0];
}

public function isDuplicate1($findValue, $findValueInColumn, $tableName, $customQuery) {

	if ($customQuery == "") {
		$customQuery = "select count(*) from $tableName where $findValueInColumn='$findValue'";
	}

	$resource = mysql_query($customQuery) or die("Could not execute query !!! $customQuery");
	$dataRow = mysql_fetch_row($resource);

	if($dataRow[0] == "0") return false;
	else return true;
}


}
?>