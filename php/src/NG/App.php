<?php

namespace NG;

class App {
    
    const AGE_LIMIT_0 = '0+';
    const AGE_LIMIT_18 = '18+';
    
    const ICON_SIZE_SMALL = '80x80';
    const ICON_SIZE_MEDIUM = '128x128';
    
    private $ng;
    
    private $id;
    
    private $name;
    
    private $description;
    
    private $ageLimit;
    
    public function __construct(\NG $ng, $id, $name, $description, $ageLimit = self::AGE_LIMIT_0) {
        $this->ng = $ng;
        $this->id = $id;
        $this->name = $name;
        $this->description=  $description;
        $this->ageLimit = $ageLimit;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
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
    
    public function getDescription() {
        return $this->description;
    }
}