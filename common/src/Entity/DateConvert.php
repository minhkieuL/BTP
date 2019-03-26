<?php
class DateConvert{
	public static function SqlToText($dateSql){
		return utf8_encode(strftime("%d %B %Y", strtotime($dateSql)));
	}
	public static function SqlToFullDate($dateSql){
		return self::SqlToText($dateSql)." à ".date("H:i:s",strtotime($dateSql)) ;
	}
}

?>
