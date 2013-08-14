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
    
    public function testValidateAPIRequest() {
        require_once 'NG.php';
        $ng = new NG('7', '1234567890ABCDEF');
        
        $badSignedRequest = array(
            'uid' => '1',
            'time' => time(),
            'sig' => 'badsig'
        );
        $this->assertFalse($ng->validateAPIRequest($badSignedRequest));
        
        $overdueRequest = array(
            'uid' => '1',
            'time' => time() - (NG::API_REQEST_TIMEOUT + 1),
        );
        $overdueRequest['sig'] = $ng->sign($overdueRequest);
        $this->assertFalse($ng->validateAPIRequest($overdueRequest));
        
        $validRequest = array(
            'uid' => '1',
            'time' => time(),
        );
        $validRequest['sig'] = $ng->sign($validRequest);
        $this->assertTrue($ng->validateAPIRequest($validRequest));
    }
    
    public function testHandleAPIRequest() {
        require_once 'NG.php';
        $ng = new NG('7', '1234567890ABCDEF');
        
        $validRequest = array(
            'uid' => '1',
            'time' => time(),
        );
        $validRequest['sig'] = $ng->sign($validRequest);
        
        require_once 'NG/Response.php';
        $this->assertEquals(
            new \NG\Response(200),
            $ng->handleAPIRequest($validRequest)
        );
    }
    
}
