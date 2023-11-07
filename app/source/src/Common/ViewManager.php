<?php

namespace Src\Common;

class ViewManager
{
    public static function render(string $viewName, array $values = [])
    {
        extract($values, EXTR_OVERWRITE);
        include_once(__DIR__ . "/../View/" . $viewName . "-blade.php");
    }
}
