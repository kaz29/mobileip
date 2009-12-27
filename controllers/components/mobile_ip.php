<?php
App::import('Model', 'mobileip.MobileIp') ;
App::import('Vendor', 'Net_IPv4', array('file' => 'Net/IPv4.php'));

class MobileIpComponent extends Object
{
  var $settings = array();
  var $ranges = array() ;
  
	function initialize(&$controller, $settings=array())
	{
		$this->Controller = & $controller;
		
		$this->settings = $settings ;
		
		$modelname = Set::extract($this->settings, 'ModelName') ;
		if ( empty($modelname) ) {
		  $modelname = 'MobileIp' ;
    }
    
		$MobileIp = ClassRegistry::init($modelname);
		if ( !is_a($MobileIp, $modelname) )
		  return ;
		
    $this->ranges = $MobileIp->find('all', $this->settings) ;
  }
  
  function carrier($addr = null)
  {
    if (!$addr) {
      $addr = $this->addr();
    }

    $ip = (double)(sprintf('%u', ip2long($addr)));
    $ranges = $this->ranges;
    $min = -1;
    $max = count($ranges);
    while (true) {
      $center = (int)floor(($min+$max)/2);
      if ($center === $min) {
        return 'pc';
      }

      $range =& $ranges[$center];

      if ($ip < $range['network']) {
        $max = $center;
      } else if ($range['network'] <= $ip && $ip <= $range['broadcast']) {
        return $range['carrier'];
      } else {
        $min = $center;
      }
    }
  }

  function addr()
  {
    if (isset($_SERVER['REMOTE_ADDR'])) {
      return $_SERVER['REMOTE_ADDR'];
    }

    return '127.0.0.1';
  }
}