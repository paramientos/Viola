<?php

class Validate
{
    /**
     * https://github.com/ldawkes/RequestHelper
     * Validates that $_GET variables adhere to given ruleset
     *
     * @param  array $rules Ruleset containing input names, and associated rules (separated by pipe ('|') character)
     * @param  array $customMessages Custom messages to display instead of automatically modified input name (for user-friendly output)
     *
     * @return boolean|array    Returns true if validation passed, returns array of errors (input => errors[]) if validation failed
     */
    public static function validateGet($rules = array(), $customMessages = array())
    {
        return static::validateArray($_GET, $rules, $customMessages);
    }

    /**
     * Validates that $_POST variables adhere to given ruleset
     *
     * @param  array $rules Ruleset containing input names, and associated rules (separated by pipe ('|') character)
     * @param  array $customMessages Custom messages to display instead of automatically modified input name (for user-friendly output)
     *
     * @return boolean|array    Returns true if validation passed, returns array of errors (input => errors[]) if validation failed
     */
    public static function validatePost($rules = array(), $customMessages = array())
    {
        return static::validateArray($_POST, $rules, $customMessages);
    }

    /**
     * Validates a given array by the given rules
     * @param  array $array The array to validate
     * @param  array $rules Ruleset containing input names, and associated rules (separated by pipe ('|') character)
     * @param  array $customMessages Custom messages to display instead of automatically modified input name (for user-friendly output)
     *
     * @return boolean|array    Returns true if validation passed, returns array of errors (input => errors[]) if validation failed
     */
    private static function validateArray($array = array(), $rules = array(), $customMessages = array())
    {
        if (empty($rules)) {
            $errors = true;
        } else {
            $errors = array();

            foreach ($rules as $inputName => $ruleset) {
                $ruleset = explode("|", $ruleset);
                $currentErrors = array();

                if (in_array("null", $ruleset) && (isset($array[$inputName]) && !empty($array[$inputName]))) {
                    array_push($currentErrors, "must be null");
                } else {
                    if (in_array("required", $ruleset) && !isset($array[$inputName])) {
                        array_push($currentErrors, "is required");
                    }

                    if ($entries = preg_grep("/(required_without)/", $ruleset)) {
                        $entry = current(array_filter($entries));
                        $without = substr($entry, strrpos($entry, ":") + 1);

                        if (empty($array[$without]) && empty($array[$inputName])) {
                            $without = self::userFriendlyInputName($without);

                            array_push($currentErrors, "is required when $without is not provided");
                        }
                    }

                    if (isset($array[$inputName])) {
                        $input = $array[$inputName];

                        if (in_array("string", $ruleset) && !is_string($input)) {
                            array_push($currentErrors, "must be letters and/or numbers");
                        }

                        if (in_array("bool", $ruleset) && !is_bool($input)) {
                            array_push($currentErrors, "must represent true/false");
                        }

                        if (in_array("numeric", $ruleset) && !is_numeric($input)) {
                            array_push($currentErrors, "must be numeric");
                        }

                        if (in_array("email", $ruleset) && !filter_var($input, FILTER_VALIDATE_EMAIL)) {
                            array_push($currentErrors, "must be a valid email");
                        }

                        if (in_array("decimal", $ruleset) && (!is_float($input) && !is_numeric($input))) {
                            array_push($currentErrors, "must be a valid number/decimal number");
                        }

                        if (in_array("phone", $ruleset)) {
                            $input = preg_replace("/[^0-9]+/", "", trim($input));

                            if (!is_numeric($input)) {
                                array_push($currentErrors, "must be a valid phone number");
                            }
                        }

                        if (in_array("array", $ruleset) && !is_array($input)) {
                            array_push($currentErrors, "must be a valid array of choices");
                        }

                        if ($entries = preg_grep("/(same_as)/", $ruleset)) {
                            $entry = current(array_filter($entries));
                            $matchAgainst = substr($entry, strpos($entry, ":") + 1, strlen($entry));

                            if (!isset($array[$matchAgainst]) || $input != $array[$matchAgainst]) {
                                $matchAgainst = self::userFriendlyInputName($matchAgainst);

                                array_push($currentErrors, "must be the same as $matchAgainst");
                            }
                        }
                    }
                }

                if (!empty($currentErrors)) {
                    if (!empty($customMessages) && array_key_exists($inputName, $customMessages)) {
                        $friendlyInput = $customMessages[$inputName];
                    } else {
                        $friendlyInput = self::userFriendlyInputName($inputName);
                    }

                    $errors[$inputName] = array(
                        "friendlyName" => $friendlyInput,
                        "errors" => $currentErrors
                    );
                }
            }

            if (empty($errors)) {
                $errors = true;
            }
        }

        return $errors;
    }

    /**
     * Attempt to convert given string to a user-friendly string
     *
     * @param  string $toClean The string to clean
     * @return string   The cleaned string
     */
    private static function userFriendlyInputName($toClean)
    {
        return ucwords(self::pregReplaceValue($toClean, function ($matches) {
            return str_replace("_", " ", $matches[0]);
        }));
    }

    /**
     * Attempt to convert string to view-friendly variable (camelCase)
     *
     * @param  string $toClean The string to clean
     * @return string   The cleaned string
     */
    private static function viewFriendlyInputName($toClean)
    {
        return self::pregReplaceValue($toClean, function ($matches) {
            return ucfirst(str_replace("_", "", $matches[0]));
        });
    }

    /**
     * Wrapper for preg_replace_callback
     * @param  string $toClean The string to pass to preg_replace_callback
     * @param  callback $callback The callback to run on matched values
     * @return string       Result of preg_replace_callback
     */
    private static function pregReplaceValue($toClean, $callback)
    {
        return preg_replace_callback(
            "/_[a-z]/",
            $callback,
            $toClean
        );
    }
}