<?php

namespace Core;

class Request {

    static function byType($inputType, $varName, $filter = FILTER_DEFAULT, $options = NULL) {
        switch ($inputType) {
            case INPUT_GET:
                return self::get($varName, $filter, $options);
            case INPUT_POST:
                return self::post($varName, $filter, $options);
            case INPUT_SERVER:
                return self::server($varName, $filter, $options);
        }
    }

    static function server($key = NULL, $filter = FILTER_DEFAULT, $options = NULL) {
        return self::filterBase(INPUT_SERVER, $key, $filter, $options);
    }

    static function post($key = NULL, $filter = FILTER_DEFAULT, $options = NULL) {
        return self::filterBase(INPUT_POST, $key, $filter, $options);
    }

    static function get($key = NULL, $filter = FILTER_DEFAULT, $options = NULL) {
        return self::filterBase(INPUT_GET, $key, $filter, $options);
    }

    static function isAjax() {
        return 'xmlhttprequest' === strtolower(self::server("HTTP_X_REQUESTED_WITH"));
    }

    static function isPost() {
        return 'POST' === strtoupper(self::server("REQUEST_METHOD"));
    }

    /**
     * @return int INPUT_POST or INPUT_GET
     */
    static function type() {
        return !self::isPost() ? INPUT_GET : INPUT_POST;
    }

    private static function filterBase($inputType, $varName = NULL, $filter = FILTER_DEFAULT, $options = NULL) {
        if (!is_null($varName)) {
            return filter_input($inputType, $varName, $filter, $options);
        }
        return filter_input_array($inputType, FILTER_DEFAULT);
    }

    private static function filterCustom($type, $varName, array $options) {
        $customRules = [
            "email"       => FILTER_VALIDATE_EMAIL,
            "int"         => FILTER_VALIDATE_INT,
            "float"       => FILTER_VALIDATE_FLOAT,
            "array"       => [
                'filter' => FILTER_DEFAULT,
                'flags'  => FILTER_REQUIRE_ARRAY
            ],
            "array-int"   => [
                'filter' => FILTER_VALIDATE_INT,
                'flags'  => FILTER_REQUIRE_ARRAY
            ],
            "array-email" => [
                'filter' => FILTER_VALIDATE_EMAIL,
                'flags'  => FILTER_REQUIRE_ARRAY
            ]
        ];
        foreach ($customRules as $ruleName => $rule) {
            if (in_array($ruleName, $options) || array_key_exists($ruleName, $options)) {
                return self::byType($type, $varName,
                    is_array($rule) ? $rule["filter"] : $rule,
                    is_array($rule) ? $rule["flags"] : NULL);
            }
        }
        return self::byType($type, $varName);
    }

    /**
     * @param int $type INPUT_POST or INPUT_GET
     * @param $variable string|array if array must be varName => filterOptions
     * @param array $options unuseful if $variable is an array
     * @return array|mixed
     */
    static function filter($type, $variable, array $options = []) {
        if (is_string($variable)) {
            return self::filterSingle($type, $variable, $options);
        } elseif (is_array($variable)) {
            $result = [];
            foreach ($variable as $varName => $filterOptions) {
                $result[$varName] = self::filterSingle($type, $varName, $filterOptions);
                if ((in_array("required", $filterOptions) || array_key_exists("required", $filterOptions))
                    && (($result[$varName] === FALSE) || is_null($result[$varName]))) {
                    return FALSE;
                }
            }
            return $result;
        }
        return FALSE;
    }

    /**
     * @param int $type INPUT_POST or INPUT_GET
     * @param string $varName
     * @param array $options
     * @return bool|mixed FALSE if filter fails
     */
    private static function filterSingle($type, $varName, array $options) {
        $variable = self::filterCustom($type, $varName, $options);
        foreach ($options as $filterName => $value) {
            if (is_int($filterName)) {
                $filterName = $value;
            }
            switch ($filterName) {
                case "max":
                    if (is_int($variable) || is_float($variable)) {
                        if ($variable > $value) {
                            return FALSE;
                        }
                    } elseif (is_string($variable)) {
                        if (strlen($variable) > $value) {
                            return FALSE;
                        }
                    }
                    break;
                case "min":
                    if (is_numeric($variable) || is_float($variable)) {
                        if ($variable < $value) {
                            return FALSE;
                        }
                    } elseif (is_string($variable)) {
                        if (strlen($variable) < $value) {
                            return FALSE;
                        }
                    }
                    break;
                case "pattern":
                    if (!preg_match($value, $variable)) {
                        return FALSE;
                    }
                    break;
                case "values":
                    if (is_array($value)) {
                        if (!in_array($variable, $value)) {
                            return FALSE;
                        }
                    } elseif ($value !== $variable) {
                        return FALSE;
                    }
                    break;
            }
        }
        return $variable;
    }

    static function upload($fieldName, $targetPath) {
        if (!isset($_FILES) || !array_key_exists($fieldName, $_FILES)) {
            return FALSE;
        }
        $file = $_FILES[$fieldName];
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($extension !== strtolower(pathinfo($targetPath, PATHINFO_EXTENSION))) {
            return FALSE;
        }
        if (!getimagesize($tmp = $file["tmp_name"])) {
            return FALSE;
        }
        return move_uploaded_file($tmp, $targetPath);
    }

}