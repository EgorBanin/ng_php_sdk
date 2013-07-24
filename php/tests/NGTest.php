<?php

class NGTest extends PHPUnit_Framework_TestCase {
    
    public function testSign() {
        require_once 'NG.php';
        
        $ng = new NG('7', '1234567890ABCDEF');
        
        $params = array(
            'foo' => '123',
            'bar' => 'test',
            '123' => ''
        );
        
        $this->assertEquals(
            md5('123=bar=testfoo=1231234567890ABCDEF'),
            $ng->sign($params)
        );
    }
    
}
