<?php

/**
 * Библиотека для добавления приложений и игр nextgame на сайт
 * @link http://nextgame.ru
 * 
 * <code>
 * <?php
 * 
 * require_once 'NG.php';
 *
 * const NG_SITE_ID = 123;
 * const NG_SECRET_KEY = '0123456789ABCDEF';
 * 
 * $ng = new NG(NG_SITE_ID, NG_SECRET_KEY);
 * 
 * // код каталога приложений и игр
 * echo $ng->code();
 *
 * // или отдельная игра
 * echo $ng->code()->setAppId('166');
 *
 * // или со сквозной авторизацией
 * $user = $ng->user('1', 'admin');
 * echo $ng->code()->setAppId('166')->setUser($user);
 *
 * // список приложений установленных пользователем
 * $apps = $user->getApps());
 * foreach ($apps as $app) {
 *     var_dump($app);
 * }
 * 
 * // удаление приложения пользователя
 * $user->uninstallApp('166');
 * 
 * ?>
 * </code>
 */

class NG {
    
    private $siteId;
    
    private $secretKey;
    
    /**
     * @param string $siteId номер сайта
     * @param string $secretKey секретный ключ сайта
     */
    public function __construct($siteId, $secretKey) {
        $this->siteId = $siteId;
        $this->secretKey = $secretKey;
    }
    
    public function getSiteId() {
        return $this->siteId;
    }
    
    public function code() {
        require_once 'NG/Code.php';
        
        return new \NG\Code($this);
    }
    
    public function user($userId, $userNickname) {
        require_once 'NG/User.php';
        
        return new \NG\User($this, $userId, $userNickname);
    }
    
    public function getToken() {
        return time();
    }
    
    public function sign(array $params) {
        // сортируем прараметры по ключу
        ksort($params, SORT_STRING);

        // объединяем пары имя=значение в строку
        $paramsStr = '';
        foreach ($params as $name => $value) {
            $paramsStr .= $name.'='.$value;
        }

        // добавляем секретный ключ и вычисляем MD5-хэш
        $signature = md5($paramsStr.$this->secretKey);

        return $signature;
    }
    
    public function request(array $params) {
        $url = 'http://api2.nextgame.ru/api';
        
        if (extension_loaded('http')) {
            $request = new HttpRequest($url, HttpRequest::METH_GET);
            $request->setOptions(array('connecttimeout' => 10, 'timeout' => 20));
            $request->addQueryData($params);
            try {
                $response = $request->send();
                $body = $response->getBody();
            } catch(\Exception $e) {
                throw $e;
            }
        } elseif (extension_loaded('curl')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($params));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            
            $body = curl_exec($ch);
            
            if (curl_errno($ch) !== 0) {
                throw new \Exception('Не удалось выполнить HTTP-запрос.');
            }
            
            curl_close($ch);
        } else {
            $context = stream_context_create(array(
                'http' => array(
                    'method'=>"GET"
                )
            ));
            $body = file_get_contents($url.'?'.http_build_query($params), false, $context);
        }
        
        return $body;
    }
    
}