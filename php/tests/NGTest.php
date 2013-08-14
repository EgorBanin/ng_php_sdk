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
    
    public function testGetApps() {
        require_once 'NG.php';
        
        $ng = $this->getMock('NG', array('request'), array('7', '0123456789ABCDEF'));
        $ng
            ->expects($this->any())
            ->method('request')
            ->will($this->returnValue('{
                "result": true,
                "data": {
                    "100500": {
                        "id": "100500",
                        "title": "testApp",
                        "description": "foo bar"
                    }
                }
            }'));
        
        require_once 'NG/App.php';
        
        $this->assertEquals(
            array(
                '100500' => new \NG\App($ng, '100500', 'testApp', 'foo bar')
            ),
            $ng->getApps()
        );
    }
    
}
