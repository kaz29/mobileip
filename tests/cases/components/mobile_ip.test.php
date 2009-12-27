<?php
App::import('Component', 'mobileip.MobileIp');
App::import('Vendor', 'Spyc', array('file' => 'spyc/spyc.php')) ;

define('RANGEFILE', ROOT.'/app/plugins/mobileip/config/mobile_ips.yml') ;

class MobileIPTestCase extends CakeTestCase
{  
  function startTest()
  {
    $this->controller = new Controller() ;
    $this->mobile_ip = new MobileIpComponent() ;

    $this->ranges =& Spyc::YAMLLoad(RANGEFILE);
  }

  function endTest()
  {
    unset($this->controller) ;
    unset($this->mobile_ip) ;
  }
  
  function testPC()
  {
    $this->mobile_ip->initialize($this->controller) ;
    
    $this->assertEqual($this->mobile_ip->carrier(), 'pc') ;
    $this->assertEqual($this->mobile_ip->carrier("192.168.1.1"), 'pc') ;
    
    unset($_SERVER['REMOTE_ADDR']) ;
    $this->assertEqual($this->mobile_ip->carrier(), 'pc') ;
  }
  
  function testDocomo()
  {
    $this->mobile_ip->initialize($this->controller) ;
    
    $ranges = $this->ranges['docomo'];
    foreach( $ranges as $cidr ) {
      $cidr = @Net_IPv4::parseAddress($cidr);
      $network = explode('.', $cidr->network) ;
      $broadcast = explode('.', $cidr->broadcast) ;
      $ip = "{$network[0]}.{$network[1]}.{$network[2]}" ;
      
      for( $i = $network[3]; $i<=$broadcast[3]; $i++ ) {
        $this->assertEqual($this->mobile_ip->carrier("{$ip}.{$i}"), 'docomo') ;
      }
    }
  }
  
  function testEzweb()
  {
    $this->mobile_ip->initialize($this->controller) ;
    
    $ranges = $this->ranges['ezweb'];
    foreach( $ranges as $cidr ) {
      $cidr = @Net_IPv4::parseAddress($cidr);
      $network = explode('.', $cidr->network) ;
      $broadcast = explode('.', $cidr->broadcast) ;
      $ip = "{$network[0]}.{$network[1]}.{$network[2]}" ;
      
      for( $i = $network[3]; $i<=$broadcast[3]; $i++ ) {
        $this->assertEqual($this->mobile_ip->carrier("{$ip}.{$i}"), 'ezweb') ;
      }
    }
  }
  
  function testSoftbank()
  {
    $this->mobile_ip->initialize($this->controller) ;
    
    $ranges = $this->ranges['softbank'];
    foreach( $ranges as $cidr ) {
      $cidr = @Net_IPv4::parseAddress($cidr);
      $network = explode('.', $cidr->network) ;
      $broadcast = explode('.', $cidr->broadcast) ;
      $ip = "{$network[0]}.{$network[1]}.{$network[2]}" ;
      
      for( $i = $network[3]; $i<=$broadcast[3]; $i++ ) {
        $this->assertEqual($this->mobile_ip->carrier("{$ip}.{$i}"), 'softbank') ;
      }
    }
  }
  
  function testWillcom()
  {
    $this->mobile_ip->initialize($this->controller) ;
    
    $ranges = $this->ranges['willcom'];
    foreach( $ranges as $cidr ) {
      $cidr = @Net_IPv4::parseAddress($cidr);
      $network = explode('.', $cidr->network) ;
      $broadcast = explode('.', $cidr->broadcast) ;
      $ip = "{$network[0]}.{$network[1]}.{$network[2]}" ;
      
      for( $i = $network[3]; $i<=$broadcast[3]; $i++ ) {
        $this->assertEqual($this->mobile_ip->carrier("{$ip}.{$i}"), 'willcom') ;
      }
    }
  }
}