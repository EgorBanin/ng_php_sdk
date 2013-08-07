<?php

class UserTest extends PHPUnit_Framework_TestCase {
    
    public function testGetApps() {
        require_once 'NG.php';
        
        $ng = $this->getMock('NG', array('request'), array('7', '0123456789ABCDEF'));
        $ng
            ->expects($this->any())
            ->method('request')
            ->will($this->returnValue('{
                "result": true,
                "data": [{
                    "id": "100500",
                    "title": "testApp",
                    "description": "foo bar"
                }]
            }'));
        $user = $ng->user('123', 'admin');
        
        
        require_once 'NG/App.php';
        
        $this->assertEquals(
            array(
                '100500' => new \NG\App($ng, '100500', 'testApp', 'foo bar')
            ),
            $user->getApps()
        );
    }
    
    public function testUninstallApp() {
        require_once 'NG.php';
        
        $ng = $this->getMock('NG', array('request'), array('7', '0123456789ABCDEF'));
        $ng
            ->expects($this->any())
            ->method('request')
            ->will($this->onConsecutiveCalls('{
                "result": true,
                "data": [{
                    "id": "100500",
                    "title": "testApp",
                    "description": "foo bar"
                }]
            }', '{
                "result": true
            }'));
        $user = $ng->user('123', 'admin');
        
        
        require_once 'NG/App.php';
        
        $this->assertEquals(
            array(
                '100500'=> new \NG\App($ng, '100500', 'testApp', 'foo bar')
            ),
            $user->getApps()
        );
        
        $this->assertEquals(
            true,
            $user->uninstallApp('100500')
        );
        
        $this->assertEquals(
            array(),
            $user->getApps()
        );
    }
    
    public function testAsXML() {
        require_once 'NG.php';
        $ng = new NG('7', '0123456789ABCDEF');
        
        require_once 'NG/User.php';
        $user = new \NG\User($ng, '100500', 'admin');
        
        $this->assertXmlStringEqualsXmlString(
<<<XML
<profiles>
    <user>
        <uid>100500</uid>
        <nickname><![CDATA[admin]]></nickname>
    </user>
</profiles>
XML
            , $user->asXML()
        );
    }
    
}