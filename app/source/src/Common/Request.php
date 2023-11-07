<?php

namespace Src\Common;

class Request
{
    private $inputs = [];

    /**
     * @return object
     */
    public function get(): object
    {
        $this->inputs["GET"] = (object) $this->escape($_GET);
        return $this->inputs["GET"];
    }

    /**
     * @return object
     */
    public function post(): object
    {
        $this->inputs["POST"] = (object) $this->escape($_POST);
        return $this->inputs["POST"];
    }

    /**
     * @param string $key
     * @param string $method
     * @return string|null
     */
    public function input(string $key, string $method = "GET"): ?string
    {
        if (!isset($this->inputs["GET"])) {
            $this->inputs["GET"] = (object) $this->escape($_GET);
        }

        if (!isset($this->inputs["POST"])) {
            $this->inputs["POST"] = (object) $this->escape($_POST);
        }

        if (!isset($this->inputs[$method]) || !isset($this->inputs[$method]->{$key})) {
            return null;
        }

        return $this->inputs[$method]->{$key};
    }

    private function escape(array $data)
    {
        $escapedData = [];
        foreach ($data as $k => $plain) {
            $escapedData[$k] = h($plain);
        }

        return $escapedData;
    }
}
