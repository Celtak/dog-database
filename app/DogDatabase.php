<?php

namespace Celtak;

/**
 * Class DogDatabase
 * @package Celtak
 * @author Henrique Rodrigues <rodrigues.henrique.1984@gmail.com>
 */
class DogDatabase extends Crud
{

    // ------------------------------------
    // DATABASE INFORMATION

    /**
     * @param array $parameters
     *  ['details']:    Get the details: Fields, Type, Null, Key, Default, Extra (Default: false)
     *  ['clean']:      Just the essential data with ['details']  (Default: true)
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     *
     * @return array
     */
    public function getColumns($parameters = [])
    {

        $parameters = [
            'details' => !isset($parameters['details']) ? false : $parameters['details'],
            'clean' => !isset($parameters['clean']) ? true : $parameters['clean'],
            'table' => !isset($parameters['table']) ? $this->table : $parameters['table']
        ];


        $res = $this->getCustomizedQuery('SHOW COLUMNS FROM ' . $parameters['table'], $parameters['clean'], __LINE__);

        $draft = $res;

        $draftLength = count($draft);

        if (!$parameters['details']) {

            for ($i = 0; $i < $draftLength; $i++) {

                $draft[$i] = $res[$i]['Field'];

            }

        }

        return $draft;

    }

    public function showColumns()
    {

        dump($this->getColumns());

    }


}