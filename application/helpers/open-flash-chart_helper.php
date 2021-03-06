<?php

function open_flash_chart_object_str( $width, $height, $url, $use_swfobject=true, $base='' )
{
    //
    // return the HTML as a string
    //
    return _ofc( $width, $height, $url, $use_swfobject, $base );
}

function open_flash_chart_object( $width, $height, $url, $use_swfobject=true, $base='' )
{
    //
    // stream the HTML into the page
    //
    echo _ofc( $width, $height, $url, $use_swfobject, $base );
}

function _ofc( $width, $height, $url, $use_swfobject, $base )
{
    //
    // I think we may use swfobject for all browsers,
    // not JUST for IE...
    //
    //$ie = strstr(getenv('HTTP_USER_AGENT'), 'MSIE');
    
    //
    // escape the & and stuff:
    //
    $url = urlencode($url);
    
    //
    // output buffer
    //
    $out = array();
    
    //
    // check for http or https:
    //
    if (isset ($_SERVER['HTTPS']))
    {
        if (strtoupper ($_SERVER['HTTPS']) == 'ON')
        {
            $protocol = 'https';
        }
        else
        {
            $protocol = 'http';
        }
    }
    else
    {
        $protocol = 'http';
    }
    
    //
    // if there are more than one charts on the
    // page, give each a different ID
    //
    global $open_flash_chart_seqno;
    $obj_id = 'chart';
    $div_name = 'flashcontent';
    
    
    
    if( !isset( $open_flash_chart_seqno ) )
    {
        $open_flash_chart_seqno = 1;
       
    }
    else
    {
        $open_flash_chart_seqno++;
        $obj_id .= '_'. $open_flash_chart_seqno;
        $div_name .= '_'. $open_flash_chart_seqno;
    }
    
	
    
	
		// Using library for auto-enabling Flash object on IE, disabled-Javascript proof 
		//$out[] = array(); 
		$out[] = '<script type="text/javascript" src="'. $base .'js/swfobject.js"></script>';
		$out[] = '<div id="flashcontent"></div>';
		$out[] = '<script type="text/javascript">';
		$out[] = 'swfobject.embedSWF("'.base_url().'open-flash-chart.swf", "flashcontent", "'.$width.'", "'.$height.'", "9.0.0", "expressInstall.swf", {"data-file":"'.$url.'"} );';

		$out[] = '</script>';
		$out[] = '<noscript>Flash + Javascript is required to view the graphs</noscript>';
  
	
    
	
    if ( $use_swfobject ) {
		$out[] = '</noscript>';
    }
    
    return implode("\n",$out);
}
?>