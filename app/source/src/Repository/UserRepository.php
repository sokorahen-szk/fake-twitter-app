<?php

namespace Src\Repository;

use Src\Model\User;
use Src\RepoInterface\IUserRepository;

class UserRepository implements IUserRepository
{
    /**
     * @var Src\Model\User
     */
    private $client;

    public function __construct()
    {
        $this->client = new User();
    }

    public function find(string $id)
    {
        $user = $this->client->find($id);
        if (!$user) {
            return null;
        }

        return $user;
    }

    public function create(string $id, string $name, string $password)
    {
        return $this->client->create([
            "id" => $id,
            "name" => $name,
            "password" => $password,
        ]);
    }
}
