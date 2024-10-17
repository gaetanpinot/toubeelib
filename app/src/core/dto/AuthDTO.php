<?php

namespace toubeelib\core\dto;

class AuthDTO extends DTO{
    protected string $id;
    protected int $role;
    protected string $atoken;
    protected string $refreshToken;

    public function __construct(string $id, int $role){
        $this->id=$id;
        $this->role=$role;
    }

    public function setAtoken(string $tok):void {
        $this->atoken = $tok;
    }

    public function setId(string $id){
        $this->id = $id;
        }
    public function setRole(int $role){
        $this->role = $role;
    }

}
