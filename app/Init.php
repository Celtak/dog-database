<?php

namespace Celtak;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Init
 * @package Celtak
 * @author Henrique Rodrigues <rodrigues.henrique.1984@gmail.com>
 */
class Init
{

    /**
     * @var array|string
     */
    private $params = [
        'host',
        'dbname',
        'username',
        'password',
        'charset'
    ];

    /**
     * @var string
     */
    protected $table;

    /**
     * @var \PDO
     */
    protected $bdd;

    /**
     * @var bool|null
     */
    protected $withError = false;


    /**
     * dogDatabase constructor.
     * @param $params
     * @param bool $withError
     */
    public function __construct($params = [], $withError = null)
    {

        if ($params === []) {

            $parametersValues = Yaml::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'parameters.yaml');

            $this->$params['host'] = $parametersValues['database']['host'];
            $this->$params['dbname'] = $parametersValues['database']['dbname'];
            $this->$params['username'] = $parametersValues['database']['username'];
            $this->$params['password'] = $parametersValues['database']['password'];
            $this->$params['charset'] = $parametersValues['database']['charset'];


        } else {

            $this->$params['host'] = $params['host'];

            $this->$params['dbname'] = $params['dbname'];

            $this->$params['username'] = $params['username'];

            $this->$params['password'] = $params['password'];

            if ($params['charset'] === null) {

                $params['charset'] = 'utf8';

            }

            $this->$params['charset'] = $params['charset'];
        }

        if($withError === null) {

            $parametersValues = Yaml::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'parameters.yaml');
            $this->withError = $parametersValues['withError'];

        } else {

            $this->withError = $withError;

        }




        try {

            $this->bdd = new \PDO('mysql:host=' . $this->$params['host'] . ';dbname=' . $this->$params['dbname'] . ';charset=' . $this->$params['charset'], $this->$params['username'], $this->$params['password'], [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);

        } catch (\Exception $e) {

            Box::error([
                'exception' => $e,
                'file' => __FILE__,
                'line' => __LINE__,
                'withError' => $this->withError
            ]);
        }


    }

    /**
     * @param $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }


}