<?php

namespace Duffleman\DLN;

use Duffleman\DLN\Exceptions\DLNInputException;

/**
 * Class DLN
 *
 * @package Duffleman\DLN
 */
class DLN
{

    /**
     * Validate a DLN as a single string or against a specific user.
     *
     * @param       $completeDLN
     * @param array $user
     * @return bool
     */
    public static function validate($completeDLN, array $user = [])
    {
        $matcher = preg_match("/^(?=.{16}$)[A-Za-z]{1,5}9{0,4}[0-9](?:[05][1-9]|[16][0-2])(?:[0][1-9]|[12][0-9]|3[01])[0-9](?:99|[A-Za-z][A-Za-z9])(?![IOQYZioqyz01_])\\w[A-Za-z]{2}$/",
            $completeDLN, $output_array);

        if (!$matcher) {
            return false;
        }

        if (!empty($user)) {
            $userDLN = self::generate($user);
            if ($userDLN !== substr($completeDLN, 0, -3)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a DLN for a user.
     *
     * @param array  $user
     * @param string $finalChars
     * @return string
     * @throws DLNInputException
     */
    public static function generate(array $user, $finalChars = "")
    {
        self::validateFieldsExists($user);

        $sections = [];
        $sections['a'] = self::generateSectionA($user['familyName']);
        $sections['b'] = self::generateSectionB($user['birthDate'], $user['sex']);
        $sections['c'] = self::generateSectionC($user['personalName']);

        $finalString = implode("", $sections);

        return $finalString . $finalChars;
    }

    /**
     * Validate the fields exist for a passed in user array.
     *
     * @param $user
     * @throws DLNInputException
     */
    private static function validateFieldsExists($user)
    {
        $needed_keys = ['familyName', 'personalName', 'birthDate', 'sex'];
        foreach ($needed_keys as $key) {
            if (!array_key_exists($key, $user)) {
                throw new DLNInputException("You need to enter `{$key}` to the array.");
            }
        }

        if ($user['sex'] !== 'M' && $user['sex'] !== 'F') {
            throw new DLNInputException("Sex needs to be 'M' or 'F'.");
        }
    }

    /**
     * Generate section A of the DLN.
     *
     * @param $familyName
     * @return mixed
     */
    private static function generateSectionA($familyName)
    {
        $familyName = self::clean($familyName);
        $familyName = preg_replace("/^MAC/", "MC", $familyName);
        $familyName = substr($familyName, 0, 5);
        $familyName = str_pad($familyName, 5, "9");

        return $familyName;
    }

    /**
     * Clean the string, upper case it, remove non A-Z characters.
     *
     * @param $string
     * @return mixed
     */
    public static function clean($string)
    {
        $string = strtoupper($string);
        $string = preg_replace("/[^A-Z]/", "", $string);

        return $string;
    }

    /**
     * Generate section B of the DLN.
     *
     * @param $birthDate
     * @param $sex
     * @return string
     * @throws DLNInputException
     */
    private static function generateSectionB($birthDate, $sex)
    {
        list($original, $year, $month, $day) = self::validateBirthDateFormat($birthDate);
        if ($sex === 'F') {
            $month += 50;
        }

        return $year[2] . $month . $day . $year[3];
    }

    /**
     * Validate the birthday input string and if it matches, split it up.
     *
     * @param $birthDate
     * @return mixed
     * @throws DLNInputException
     */
    private static function validateBirthDateFormat($birthDate)
    {
        $matched = preg_match("/^(\\d{4})-(\\d{2})-(\\d{2})$/", $birthDate, $output_array);
        if (!$matched) {
            throw new DLNInputException("The format of birthDate needs to be YYYY-MM-DD.");
        } else {
            return $output_array;
        }
    }

    /**
     * Generate section C for the DLN.
     *
     * @param $personalName
     * @return array
     */
    private static function generateSectionC($personalName)
    {
        $names = explode(' ', $personalName);
        $chars = [];
        foreach ($names as $name) {
            $name = self::clean($name);
            $chars[] = substr($name, 0, 1);
        }

        $chars = implode('', $chars);
        $chars = substr($chars, 0, 2);
        $chars = str_pad($chars, 2, "9");

        return $chars;
    }
}