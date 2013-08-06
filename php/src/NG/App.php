<?php

namespace NG;

class App {
    
    const AGE_LIMIT_0 = '0+';
    const AGE_LIMIT_18 = '18+';
    
    const ICON_SIZE_SMALL = '80x80';
    
    private $ng;
    
    private $id;
    
    private $title;
    
    private $desc;
    
    private $ageLimit;
    
    public function __construct(\NG $ng, $id, $title, $desc, $ageLimit = self::AGE_LIMIT_0) {
        $this->ng = $ng;
        $this->id = $id;
        $this->title = $title;
        $this->desc = $desc;
        $this->ageLimit = $ageLimit;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function getIcon($size = self::ICON_SIZE_SMALL) {
        return 'http://api2.nextgame.ru/service/picture/app/?app_id='.$this->id.'&size='.$size;
    }
    
    public function getUrl() {
        $params = array(
            'app_id' => $this->id,
            'site_id' => $this->ng->getSiteId()
        );
        $params['sig'] = $this->ng->sign($params);
        
        return 'http://api2.nextgame.ru/iframe?'.http_build_query($params);
    }
}