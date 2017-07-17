<?php
function arr_to_xml($arr, $dom = 0, $item = 0) {
	if (! $dom) {
		$dom = new DOMDocument ("1.0","UTF-8");
	}
	
	if (! $item) {
		$ccc = array_keys ( $arr );
		if ($ccc [0] == 'envelope') {
			$str_head = 'request';
		} else {
			$str_head = 'envelope';
		}
		$item = $dom->createElement ( $str_head );
		$dom->appendChild ( $item );
	}
	foreach ( $arr as $key => $val ) {		
		$itemx = $dom->createElement ( is_string ( $key ) ? $key : "record" );
		$item->appendChild ( $itemx );
		if (! is_array ( $val )) {
			$text = $dom->createTextNode ( $val );
			$itemx->appendChild ( $text );
		} else {
			arr_to_xml ( $val, $dom, $itemx );
		}
	}
	
	return $dom->saveXML ();
}

function xml_to_array($xml) {
	$reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
	if (preg_match_all ( $reg, $xml, $matches )) {
		$count = count ( $matches [0] );
		$arr = array ();
		for($i = 0; $i < $count; $i ++) {
			
			$key = $matches [1] [$i];	
			
			$val = xml_to_array ( $matches [2] [$i] ); // 递归
			if (array_key_exists ( $key, $arr )) {
				if (is_array ( $arr [$key] )) {
					if (! array_key_exists ( 0, $arr [$key] )) {
						$arr [$key] = array (
								$arr [$key] 
						);
					}
				} else {
					
					$arr [$key] = array (
							$arr [$key] 
					);
				}
				$arr [$key] [] = $val;
			} else {
				
				$arr [$key] = $val;
			}
		}
		return $arr;
	} else {
		return $xml;
	}
}
?>