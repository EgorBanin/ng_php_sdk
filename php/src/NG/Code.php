<?php

namespace NG;

class Code {
    
    private $ng;
    
    private $appId = null;
    
    private $user;
    
    public function __construct(\NG $ng) {
        $this->ng = $ng;
    }
    
    public function setAppId($appId) {
        $this->appId = $appId;
        
        return $this;
    }
    
    public function setUser(User $user) {
        $this->user = $user;
        
        return $this;
    }
    
    public function __toString() {
        $url = 'http://api2.nextgame.ru/iframe/js';
        $params = array(
            'site_id' => $this->ng->getSiteId()
        );
        
        if ($this->appId) {
            $url .= '/link';
            $params['app_id'] = $this->appId;
            $params['linktype'] = 1;
            $template = '<script type="text/javascript" src="%s"></script>';
        } else {
            $url .= '/catalogue';
            $template = <<<EOT
<!-- NextGame.RU catalog -->
<script type="text/javascript" src="%s"></script>"></script>
<div id="ng_catalogue"></div>
<center><a href="http://www.nextgame.ru" target="_blank" title="Приложения от NextGame.RU">Приложения от NextGame.RU</a></center>
<script type="text/javascript">
    var ngc = NGCatalogue.getInstance();
    ngc.render();
</script>
<!--/ NextGame.RU catalog -->
EOT;
        }
        
        if ($this->user) {
            $params['user_id'] = $this->user->getId();
            $params['usr_nickname'] = $this->user->getNickname();
            $params['t'] = $this->ng->getToken();
            $params['sig'] = $this->ng->sign($params);
        }
        
        $url .= '?'.http_build_query($params);
        
        return sprintf($template, $url);
    }
    
}