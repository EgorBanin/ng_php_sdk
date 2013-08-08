<?php

namespace NG;

class Response {
    
    private $code;
    
    private $messages = array(
        200 => 'OK',
        403 => 'Forbidden',
        404 => 'Not found'
    );
    
    private $headers;
    
    private $body;
    
    public function __construct($code, array $headers = array(), $body = '') {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }
    
    public function send() {
        $message = isset($this->messages[$this->code])? $this->messages[$this->code] : 'Unknown message';
        header('HTTP/1.1 '.$this->code.' '.$message);
        
        foreach ($this->headers as $header) {
            header($header);
        }
        
        echo $this->body;
    }
    
}