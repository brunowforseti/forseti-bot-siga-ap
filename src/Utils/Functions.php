<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:25
 */

namespace Forseti\Bot\Name\Utils;


class Functions
{
    public static function convertDate($data, $format = 'd/m/Y H:i:s')
    {
        $date = \DateTime::createFromFormat($format, $data);

        return $date->format('Y-m-d H:i:s');
    }
}