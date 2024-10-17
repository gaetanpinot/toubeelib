<?php

namespace toubeelib\infrastructure\repositories;

use DI\Container;
use Monolog\Logger;
use toubeelib\core\domain\entities\User;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PgAuthRepository implements AuthRepositoryInterface{
    protected \PDO $pdo;
    protected Logger $log;

    public function __construct(Container $co)
    {
        $this->pdo=$co->get('pdo.auth');
        $this->log = $co->get(
    }
    public function getUser(string $id): User
    {
        try{
        $query='select * from users where user.id=:id;';
        $rq=$this->pdo->prepare($query);
        $rq->execute(['id'=>$id]);
        $user = $rq->fetch();
            return new User($user['id'],$user['email'], $user['password'], $user['role']);
        }catch(\PDOException $e){

            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function createUser(User $user): User
    {

    }

    public function deletUser(string $id): void
    {
    }

}
