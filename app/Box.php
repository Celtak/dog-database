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

        $parameters['exception'];
        $parameters['line'];
        $parameters['file'];
        $parameters['withError'];
        $parameters['request'];


        if ($parameters['exception'] === null) {

            dump('WARNING: The exception is not specified');
        }

        if ($parameters['line'] === null) {

            dump('WARNING: The line number is not specified');
        }

        if ($parameters['file'] === null) {

            dump('WARNING: The file is not specified');
        }

        if ($parameters['withError'] === null) {

            $parameters['withError'] = false;
        }

        if ($parameters['request'] === null) {

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
