<?php
/** 
 * print at "use as php code" style
 * 2012.6.1
 * @author geerpm
 */


/** 
 * print 
 * @param mixed		$var
 * @param bool		$ret
 * @return string|null
 */
function print_v($var, $ret = false)
{
	$a = '$var = ' . _printSubVar($var, 1) . ';' . PHP_EOL;
	
	if ($ret) {
		return $a;
	}
	echo $a;
}

/** 
 * sub method
 * @param mixed		$var
 * @param int		$lv
 * @return string
 */
function _printSubVar($var, $lv)
{
	$a = '';
	if (is_array($var)) {
		// detect longest key
		$maxlen = 0;
		foreach ($var as $key => $value) {
			$len = strlen(is_int($key) ? $key : '"' . $key . '"');
			$maxlen = $len > $maxlen ? $len : $maxlen;
		}
		$holder = array();
		foreach ($var as $key => $value) {
			$key = is_int($key) ? $key : '"' . $key . '"';
			$key = str_pad($key, $maxlen + 1);
			$holder[] .= str_repeat(' ', $lv * 4) . $key . ' => ' . _printSubVar($value, $lv + 1);
		}
		$a .= 'array(';
		if (count($holder)) {
			$a .= PHP_EOL . join(',' . PHP_EOL, $holder) . PHP_EOL;
			$a .= str_repeat(' ', max(0, ($lv - 1) * 4));
		}
		$a .= ')';
	
	} else if ($var instanceof Closure) {
		$a .= '"Closure::__invoke()"'; // closure instance cannot serialize
		
	} else if (is_object($var)) {
		$a .= 'unserialize("' . serialize($var) . '")';
		
	} else if (is_string($var)) {
		$a .= '"' . $var . '"';
		
	} else if (is_null($var)) {
		$a .= 'null';
		
	} else {
		$a .= $var;
	}
	
	return $a;
}
