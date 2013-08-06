<?php

class AppTest extends PHPUnit_Framework_TestCase {
    
    public function testGetUrl() {
        require_once 'NG.php';
        $ng = new NG('7', '0123456789ABCDE');
        
        require_once 'NG/App.php';
        $app = new \NG\App($ng, '100500', 'testApp', 'foo bar');
        
        $testSig = $ng->sign(array(
            'app_id' => '100500',
            'site_id' => '7'
        ));
        
        $this->assertEquals(
            'http://api2.nextgame.ru/iframe?app_id=100500&site_id=7&sig='.$testSig,
            $app->getUrl()
        );
    }
    
}
