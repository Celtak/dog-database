<?php

namespace Celtak;

/**
 * Class Box
 * @package Celtak
 * @author Henrique Rodrigues <rodrigues.henrique.1984@gmail.com>
 */
class Box
{

    /**
     * @param $parameters
     *  ['exception']:  Exception http://php.net/manual/en/class.exception.php
     *  ['line']:       The line number that caused the error
     *  ['file']:       The file that caused the error
     *  ['withError']:  With or without error messages (Default: false)
     *  ['request']:    The request used (Default: false)
     */
    static function error($parameters)
    {

        /*
        $parameters['exception'];
        $parameters['line'];
        $parameters['file'];
        $parameters['withError'];
        $parameters['request'];
        */


        if (!isset($parameters['exception'])) {

            dump('WARNING: The exception is not specified');
        }

        if (!isset($parameters['line'])) {

            dump('WARNING: The line number is not specified');
        }

        if (!isset($parameters['file'])) {

            dump('WARNING: The file is not specified');
        }

        if (!isset($parameters['withError'])) {

            $parameters['withError'] = false;
        }

        if (!isset($parameters['request'])) {

            $parameters['request'] = false;
        }

        if ($parameters['withError']) {

            dump($parameters['exception']->getMessage());
            dump('Line: ' . $parameters['line'] . ' in file: ' . $parameters['file']);

            if ($parameters['request']) {

                dump('REQUEST: ' . $parameters['request']);

            }

            die();

        }
    }

}
