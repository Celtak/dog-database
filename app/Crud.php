<?php

namespace Celtak;


/**
 * Class Crud
 * @package Celtak
 * @author Henrique Rodrigues <rodrigues.henrique.1984@gmail.com>
 */
class Crud extends Init
{


    const EQUAL_TO = '=';
    const GREATER_THAN = '>';
    const LESS_THAN = '<';
    const GREATER_THAN_EQUAL_TO = '>=';
    const LESS_THAN_EQUAL_TO = '<=';
    const NOT_EQUAL_TO = '<>';

    /**
     * @var null
     */
    protected $request = null;

    /**
     * @return null
     */
    public function getRequest()
    {
        return $this->request;
    }





    // ------------------------------------
    // CREATE

    /**
     * @param array $data
     *  [
     *       ['field', 'value'],
     *       ['field', 'value'],
     *       ['field', 'value'],
     *      ...
     *   ]
     *
     * @param array $parameters
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     */
    public function insert($data, $parameters = [])
    {

        $parameters = [
            'table' => $parameters['table'] === null ? $this->table : $parameters['table']
        ];

        $prepare = 'INSERT INTO ' . $parameters['table'] . ' (';
        $draft = '';

        $dataLength = count($data);

        for ($i = 0; $i < $dataLength; $i++) {


            if ($i + 1 !== $dataLength) {

                $prepare .= $data[$i][0] . ', ';

            } else {

                $prepare .= $data[$i][0];
                $prepare .= ') VALUES (';

            }

            if ($i + 1 !== $dataLength) {

                $draft .= '"' . $data[$i][1] . '", ';

            } else {

                $draft .= '"' . $data[$i][1] . '"';
                $draft .= ')';

            }


        }

        $prepare = $prepare . $draft;

        $this->request = $prepare;

        $this->customizedExec($prepare, __LINE__);

    }





    // ------------------------------------
    // READ

    /**
     * @param $query
     * @param $parameters
     * @return array
     */
    private function get($query, $parameters)
    {

        $parameters = [
            'where' => $parameters['where'],
            'orderBy' => $parameters['orderBy'],
            'limit' => $parameters['limit'],
            'clean' => $parameters['clean'] === null ? true : $parameters['clean'],
            'table' => $parameters['table'] === null ? $this->table : $parameters['table']
        ];


        if ($parameters['where'] === null) {

            $this->request = $query . $parameters['table'];

        } else {

            if ($parameters['where'][2] === null) {

                $parameters['where'][2] = '=';

            }

            $this->request = $query . $parameters['table'] . ' WHERE ' . $parameters['where'][0] . ' ' . $parameters['where'][2] . ' ' . '"' . $parameters['where'][1] . '"';

        }


        if ($parameters['orderBy'] !== null) {

            if (is_array($parameters['orderBy'])) {

                if ($parameters['orderBy'][1] === null) {

                    $this->request .= ' ORDER BY ' . $parameters['orderBy'][0];

                } else {

                    $this->request .= ' ORDER BY ' . $parameters['orderBy'][0] . ' ' . $parameters['orderBy'][1];

                }

            } else {

                $this->request .= ' ORDER BY ' . $parameters['orderBy'];

            }


        }

        if ($parameters['limit'] !== null) {

            $this->request .= ' LIMIT ' . $parameters['limit'][0] . ', ' . $parameters['limit'][1];

        }

        $end = $this->getCustomizedQuery($this->request, $parameters['clean'], __LINE__);

        return $end;

    }


    /**
     * @param array $parameters
     *  ['where']:      Get the details: Fields, Type, Null, Key, Default, Extra (Default: false)
     *  ['orderBy']:    Just the essential data with ['details']  (Default: true)
     *  ['limit']:      Insert the name of the table used (Default: $this->table)
     *  ['clean']:      Just the essential data with ['details']  (Default: true)
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     *
     * @return array
     */
    public function getAll($parameters = [])
    {

        return $this->get('SELECT * FROM ', $parameters);

    }

    public function showAll($parameters = [])
    {

        dump($this->getAll($parameters));

    }


    /**
     * @param string $field
     *
     * @param array $parameters
     *  ['where']:      Get the details: Fields, Type, Null, Key, Default, Extra (Default: false)
     *  ['orderBy']:    Just the essential data with ['details']  (Default: true)
     *  ['limit']:      Insert the name of the table used (Default: $this->table)
     *  ['clean']:      Just the essential data with ['details']  (Default: true)
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     *
     * @return array
     */
    public function getField($field, $parameters = [])
    {

        $draft = $this->get('SELECT ' . $field . ' FROM ', $parameters);

        if (stristr($field, ',')) {

            if ($this->withError) {

                dump('You have inserted "," in the first parameter which is incorrect');
                dump('Check the first parameter: "' . $field . '"');
                dump('If you want to select multiple fields, use getFields instead of getField');

            }

            die();

        }

        $draftLength = count($draft);

        for ($i = 0; $i < $draftLength; $i++) {

            $draft[$i] = $draft[$i][$field];

        }


        return $draft;

    }

    public function showField($field, $parameters = [])
    {

        dump($this->getField($field, $parameters));

    }


    /**
     * @param array $fields
     *
     * @param array $parameters
     *  ['where']:      Get the details: Fields, Type, Null, Key, Default, Extra (Default: false)
     *  ['orderBy']:    Just the essential data with ['details']  (Default: true)
     *  ['limit']:      Insert the name of the table used (Default: $this->table)
     *  ['clean']:      Just the essential data with ['details']  (Default: true)
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     *
     * @return array
     */
    public function getFields($fields, $parameters = [])
    {

        $fieldsLength = count($fields);
        $prepare = '';

        for ($i = 0; $i < $fieldsLength; $i++) {

            if ($i + 1 !== $fieldsLength) {

                $prepare .= $fields[$i] . ', ';

            } else {

                $prepare .= $fields[$i];

            }

        }

        return $this->get('SELECT ' . $prepare . ' FROM ', $parameters);

    }

    public function showFields($field, $parameters = [])
    {

        dump($this->getFields($field, $parameters = []));

    }


    /**
     * @param int|string $id
     *
     * @param array $parameters
     *  ['clean']:      Just the essential data with ['details']  (Default: true)
     *  ['table']:      Insert the name of the table used (Default: $this->table)
     *
     * @return array
     */
    public function getId($id, $parameters = [])
    {

        $parameters['where'] = ['id', $id];
        $parameters['orderBy'] = null;
        $parameters['limit'] = null;

        $draft = $this->get('SELECT * FROM ', $parameters);

        return $draft[0];

    }

    public function showId($id, $parameters = [])
    {

        dump($this->getId($id, $parameters));

    }






    // ------------------------------------
    // UPDATE

    /**
     * @param array $data
     *  [
     *       ['field', 'value'],
     *       ['field', 'value'],
     *       ['field', 'value'],
     *      ...
     *   ]
     *
     *
     * @param array $parameters
     *  ['where']:  Obligatory
     *  ['table']:  Insert the name of the table used (Default: $this->table)
     */
    public function update($data, $parameters)
    {


        if($parameters['where'] === null) {


            if ($this->withError) {

                dump('$parameters[\'where\'] is required');
                dump('Line: ' . __LINE__ . ' in file: ' . __FILE__);

            }

            die();

        }


        $parameters = [
            'table' => $parameters['table'] === null ? $this->table : $parameters['table'],
            'where' => $parameters['where'],
        ];

        $prepare = 'UPDATE ' . $parameters['table'] . ' SET ';

        $dataLength = count($data);

        for ($i = 0; $i < $dataLength; $i++) {

            if($i + 1 === $dataLength){

                $prepare .= $data[$i][0] . " = '" . $data[$i][1] . "'";

            } else {

                $prepare .= $data[$i][0] . " = '" . $data[$i][1] . "', ";

            }

        }

        $prepare .= ' WHERE ' . $parameters['where'][0] . ' = "' . $parameters['where'][1]. '"';


        $this->request = $prepare;

        $this->customizedExec($prepare, __LINE__);

    }

    /**
     * @param int $id
     * @param array $data
     *  [
     *       ['field', 'value'],
     *       ['field', 'value'],
     *       ['field', 'value'],
     *      ...
     *   ]
     *
     *
     * @param array $parameters
     *  ['table']:  Insert the name of the table used (Default: $this->table)
     */
    public function updateId($id,$data, $parameters = []){

        $parameters = [
            'table' => $parameters['table'] === null ? $this->table : $parameters['table'],
            'where' => ['id', $id],
        ];

        $this->update($data, $parameters);

    }






    // ------------------------------------
    // DELETE

    /**
     * @param array $parameters
     *  ['where']:  Obligatory
     *  ['table']:  Insert the name of the table used (Default: $this->table)
     */
    public function delete($parameters = []){

        if($parameters['where'] === null) {


            if ($this->withError) {

                dump('$parameters[\'where\'] is required');
                dump('Line: ' . __LINE__ . ' in file: ' . __FILE__);

            }

            die();

        }

        $parameters = [
            'table' => $parameters['table'] === null ? $this->table : $parameters['table'],
            'where' => $parameters['where'],
        ];


        $prepare = 'DELETE FROM ' . $parameters['table'];

        $prepare .= ' WHERE ' . $parameters['where'][0] . ' = "' . $parameters['where'][1]. '"';

        $this->request = $prepare;

        $this->customizedExec($prepare, __LINE__);

    }

    /**
     * @param $id
     * @param array $parameters
     *  ['table']:  Insert the name of the table used (Default: $this->table)
     */
    public function deleteId($id, $parameters = []) {

        $parameters = [
            'table' => $parameters['table'] === null ? $this->table : $parameters['table'],
            'where' => ['id', $id],
        ];

        $this->delete($parameters);

    }





    // ------------------------------------
    // CUSTOMIZED

    /**
     * @param $exec
     * @param $line
     */
    public function customizedExec($exec, $line = null)
    {

        $this->request = $exec;

        try {

            $this->bdd->exec($this->request);

        } catch (\Exception $e) {

            Box::error([
                'exception' => $e,
                'file' => __FILE__,
                'line' => $line,
                'withError' => $this->withError,
                'request' => $this->request
            ]);
        }

    }

    /**
     * @param $query
     * @param bool $clean
     * @param $line
     * @return array
     */
    public function getCustomizedQuery($query, $clean = false, $line = null)
    {

        $end = [];
        $res = [];

        $this->request = $query;

        try {

            $res = $this->bdd->query($this->request);


        } catch (\Exception $e) {

            Box::error([
                'exception' => $e,
                'file' => __FILE__,
                'line' => $line,
                'withError' => $this->withError,
                'request' => $this->request
            ]);
        }

        while ($data = $res->fetch()) {

            $end[] = $data;

        }

        if ($clean) {

            $endLength = count($end);

            for ($i = 0; $i < $endLength; $i++) {

                $endDataLength = count($end[$i]);

                for ($j = 0; $j < $endDataLength; $j++) {

                    unset($end[$i][$j]);

                }

            }

        }

        $res->closeCursor();

        return $end;

    }


}
