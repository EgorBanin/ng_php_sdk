<?php

class NG_ResponseTest extends PHPUnit_Framework_TestCase {
    
    public function setUp() {
        parent::setUp();
        
        ob_start();
    }
    
    public function tearDown() {
        header_remove();
        ob_clean(); 
        
        parent::tearDown();
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testSend() {
        require_once 'NG/Response.php';
        $response = new \NG\Response(
            200,
            array('Content-Type: application/json;charset=utf-8'),
            'foo bar'
        );
        $response->send();
        
        $this->assertEquals(
            200,
            http_response_code()
        );
        
        $this->assertContains('Content-Type: application/json;charset=utf-8', xdebug_get_headers());
        
        $this->assertEquals(
            'foo bar',
            ob_get_contents()
        );
    }
    
}
