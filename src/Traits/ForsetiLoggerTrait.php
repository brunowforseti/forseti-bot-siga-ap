<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 14/03/19
 * Time: 11:31
 */

namespace Forseti\Bot\Name\Traits;

use Forseti\Logger\Logger;
use Psr\Log\LoggerTrait;

trait ForsetiLoggerTrait
{
    use LoggerTrait;
    public function log($level, $message, array $context = array())
    {
        return (new Logger(get_class($this)))->log($level, $message, $context);
    }
}