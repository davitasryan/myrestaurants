<?php
namespace Application\Utility;

use Zend\Crypt\Password\Bcrypt;

class UserPassword
{
    public $salt = 'aUJGgadjasdgdj';
    public $method = 'bcrypt';
    public function __construct($method = null)
    {
        if (! is_null($method)) {
            $this->method = $method;
        }
    }
    public function create($password)
    {
        if ($this->method == 'md5') {
            return md5($this->salt . $password);
        } elseif ($this->method == 'sha1') {
            return sha1($this->salt . $password);
        } elseif ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
			
            return $bcrypt->create($password);
        }
    }
    public function verify($password, $hash)
    {
        if ($this->method == 'md5') {
            return $hash == md5($this->salt . $password);
        } elseif ($this->method == 'sha1') {
            return $hash == sha1($this->salt . $password);
        } elseif ($this->method == 'bcrypt') {
            $bcrypt = new Bcrypt();
            
            return $bcrypt->verify($password, $hash);
        }
    }
}