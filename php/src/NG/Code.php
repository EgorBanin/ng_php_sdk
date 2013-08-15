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
            $params['app_id'] = $this->appId;
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
            if ($this->user->getSex()) {
                $params['usr_sex'] = $this->user->getSex();
            }
            if ($this->user->getBirthday()) {
                $params['usr_birthday'] = $this->user->getBirthday()->format('Y-m-d');
            }
            if ($this->user->getAvatar()) {
                $params['usr_avatar_url'] = $this->user->getAvatar();
            }
            $params['t'] = $this->ng->getToken();
            $params['sig'] = $this->ng->sign($params);
        }
        
        $url .= '?'.http_build_query($params);
        
        return sprintf($template, $url);
    }
    
}