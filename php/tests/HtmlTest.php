<?php

class HtmlTest extends PHPUnit_Framework_TestCase {
    
    private $ng;
    
    public function setUp() {
        require_once 'NG.php';
        
        $this->ng = new NG('7', '');
    }
    
    public function testCatalogHtmlCode() {
        $this->assertEquals(<<<EOT
<!-- NextGame.RU catalog -->
<script type="text/javascript" src="http://api2.nextgame.ru/iframe/js/catalogue?site_id=7"></script>"></script>
<div id="ng_catalogue"></div>
<center><a href="http://www.nextgame.ru" target="_blank" title="Приложения от NextGame.RU">Приложения от NextGame.RU</a></center>
<script type="text/javascript">
    var ngc = NGCatalogue.getInstance();
    ngc.render();
</script>
<!--/ NextGame.RU catalog -->
EOT
            , (string) $this->ng->code()
        );
    }
    
    public function testOneAppHtmlCode() {
        $this->assertEquals(
            '<script type="text/javascript" src="http://api2.nextgame.ru/iframe/js?site_id=7&app_id=166"></script>',
            (string) $this->ng->code()->setAppId('166')
        );
    }
    
    public function testAuthOneAppHtmlCode() {
        $ng = $this->getMock('NG', array('getToken'), array('7', '0123456789ABCDEF'));
        $ng
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue('token'));
        
        require_once 'NG/User.php';
        $user = $ng->user('123', 'admin');
        
        $this->assertEquals(
            '<script type="text/javascript" src="http://api2.nextgame.ru/iframe/js?site_id=7&app_id=166&user_id=123&usr_nickname=admin&t=token&sig='
                .md5('app_id=166site_id=7t=tokenuser_id=123usr_nickname=admin0123456789ABCDEF')
                .'"></script>',
            (string) $ng->code()->setAppId('166')->setUser($user)
        );
    }
    
}
