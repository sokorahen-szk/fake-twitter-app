<?php

namespace Src\Common;

class Validation
{
    private static $errors = [];

    public const ERROR_PREFIX = "error_";

    public static function validate(array $rules, array $values): bool
    {
        foreach ($rules as $key => $patterns) {
            $index = "";
            if (is_array($patterns)) {
                $index = $key;
            } else {
                $index = $patterns;
            }

            if (is_array($patterns)) {
                self::checkRules($index, $patterns, $values);
            }
        }

        if (count(self::$errors) > 0) {
            return false;
        }

        return true;
    }

    public static function errors(): array
    {
        return self::$errors;
    }

    private static function checkRules(string $index, array $patterns, array $values): void
    {
        if (!isset($values[$index])) {
            return;
        }

        $target = $values[$index];
        foreach ($patterns as $pattern) {
            $m = explode(":", $pattern);

            if (count($m) < 1) {
                continue;
            }

            $nullable = false;

            switch ($m[0]) {
                case "min":
                    if (mb_strlen($target) < $m[1]) {
                        self::$errors[self::ERROR_PREFIX . $index] = $index . "が短すぎます。";
                    }
                    break;
                case "max":
                    if (mb_strlen($target) > $m[1]) {
                        self::$errors[self::ERROR_PREFIX . $index] = $index . "が長すぎます。";
                    }
                    break;
                case "re_password":
                    // 再入力のチェックがない場合、処理をスキップさせる
                    if (!isset($values[$m[1]])) {
                        break;
                    }

                    if ($target !== $values[$m[1]]) {
                        self::$errors[self::ERROR_PREFIX . $index] = $index . "が一致しません。";
                    }

                    break;
                case "alpha_number":
                    if (self::nullableRule($target, $nullable)) {
                        break;
                    }

                    if (!preg_match("/^[0-9a-zA-Z]+$/", $target)) {
                        self::$errors[self::ERROR_PREFIX . $index] = $index . "は、英数字0-9a-zA-Zのみ利用できます。";
                    }

                    break;
                case "in":
                    $errorHandlingSkip = false;
                    $a = explode(",", $m[1]);
                    foreach ($a as $s) {
                        if ($target === $s) {
                            $errorHandlingSkip = true;
                            break;
                        }
                    }

                    if ($errorHandlingSkip) {
                        break;
                    }

                    self::$errors[self::ERROR_PREFIX . $index] = $index . "許容されていない文字が設定されています。";
                    break;
                case "nullable":
                    $nullable = true;
                    break;
                case "require":
                    if (strlen($target) < 1) {
                        self::$errors[self::ERROR_PREFIX . $index] = $index . "が正しく入力されていません。";
                    }
                    break;
            }
        }
    }

    private static function nullableRule($target, bool $nullable): bool
    {
        if ($nullable && (mb_strlen($target) === 0 || $target === "" || $target === null)) {
            return true;
        }

        return false;
    }
}
