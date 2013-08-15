<?php

namespace NG;

class User {
    
    const SEX_M = 'm';
    const SEX_F = 'f';
    
    private $ng;
    
    private $id;
    
    private $nickname;
    
    private $sex;
    
    private $birthday;
    
    private $avatar;
    
    private $apps = null;

    public function __construct(\NG $ng, $id, $nickname) {
        $this->ng = $ng;
        $this->id = $id;
        $this->nickname = $nickname;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNickname() {
        return $this->nickname;
    }
    
    public function getApps() {
        if ($this->apps !== null) {
            return $this->apps;
        }
        
        $params = array(
            'method' => 'apps.getUserApps',
            'format' => 'json',
            'site_id' => $this->ng->getSiteId(),
            'user_id' => $this->id
        );
        $params['sig'] = $this->ng->sign($params);
        
        $response = $this->ng->request($params);
        $data = json_decode($response, true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Не удалось распарсить ответ.');
        }
        
        $this->apps = array();
        
        if ($data['result'] === true) {
            require_once 'NG/App.php';
            
            foreach ($data['data'] as $appData) {
                $this->apps[$appData['id']] = new \NG\App($this->ng, $appData['id'], $appData['title'], $appData['description']);
            }
        } elseif ($data['errno'] !== 200) {
            throw new \Exception('Ошибка при обращении к API');
        }
        
        return $this->apps;
    }
    
    public function uninstallApp($appId) {
        if (isset($this->apps[$appId])) {
            unset($this->apps[$appId]);
        }
        
        $params = array(
            'method' => 'apps.deleteUser',
            'format' => 'json',
            'site_id' => $this->ng->getSiteId(),
            'user_id' => $this->id,
            'app_id' => $appId
        );
        $params['sig'] = $this->ng->sign($params);
        
        $response = $this->ng->request($params);
        $data = json_decode($response, true);
        
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Не удалось распарсить ответ.');
        }
        
        if ($data['result'] === true) {
            return true;
        } else {
            throw new \Exception('Ошибка при обращении к API');
        }
    }
    
    public function setSex($sex) {
        $this->sex = $sex;
    }
    
    public function getSex() {
        return $this->sex;
    }
    
    public function setBirthday(\DateTime $birthday) {
        $this->birthday = $birthday;
    }
    
    public function getBirthday() {
        return $this->birthday;
    }
    
    public function setAvatar($url) {
        $this->avatar = $url;
    }
    
    public function getAvatar() {
        return $this->avatar;
    }
    
    /**
     * @return string XML
     */
    public function asXML() {
        return <<<XML
<profiles>
    <user>
        <uid>$this->id</uid>
        <nickname><![CDATA[$this->nickname]]></nickname>
    </user>
</profiles>
XML;
    }
    
}
