<?php

namespace Src\Common;

use Src\Exception\AlreadyLoginException;

class SessionManager
{
    public function __construct()
    {
        @session_start();
    }

    public function create(string $userId, string $userName): void
    {
        if ($this->check()) {
            throw new AlreadyLoginException("既にログインされています。");
        }

        $_SESSION["user_id"] = $userId;
        $_SESSION["user_name"] = $userName;
    }

    /**
     * @return boolean
     */
    public function destroy(): bool
    {
        if (!$this->check()) {
            throw new \Exception("ログインされていません。");
        }

        return session_destroy();
    }

    public function check(): bool
    {
        return (isset($_SESSION["user_id"]) && isset($_SESSION["user_name"]));
    }

    public function getUserId(): string
    {
        if (!@$_SESSION["user_id"]) {
            return "";
        }
        return @$_SESSION["user_id"];
    }

    public function getUserName(): string
    {
        if (!@$_SESSION["user_name"]) {
            return "";
        }
        return @$_SESSION["user_name"];
    }
}
