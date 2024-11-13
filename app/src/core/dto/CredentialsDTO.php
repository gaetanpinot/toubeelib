<?php
namespace toubeelib\core\dto;

class CredentialsDTO extends DTO{

    protected string $id,$password;

    public function __construct(string $id, string $password)
    {
        $this->password=$password;
        $this->id=$id;
    }

}
