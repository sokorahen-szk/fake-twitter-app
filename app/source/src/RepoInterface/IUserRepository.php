<?php

namespace Src\RepoInterface;

interface IUserRepository
{
    /**
     * @param string $id
     * @return void
     */
    public function find(string $id);

    /**
     * @param string $id
     * @param string $name
     * @param string $password
     * @return void
     */
    public function create(string $id, string $name, string $password);
}
