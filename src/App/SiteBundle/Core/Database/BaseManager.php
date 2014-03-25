<?php

namespace App\SiteBundle\Core\Database;

use App\SiteBundle\Exception\AppException;

/**
 * @file
 * Содержит класс-надстройку над стандартными функциями mysqli для работы с БД.
 * 
 * @autor Azamat Bogotov azamat@e-magic.org
 */

/**
 * Класс-надстройка над стандартными функциями mysqli для работы с БД.
 * 
 * @autor Azamat Bogotov azamat@e-magic.org
 * @version 0.7
 */
class BaseManager
{
    /**
     * Экземпляр класса.
     */
    private static $_instance = null;
    
    /**
     * Экземпляр текущего соединения.
     */
    private $_connection = null;
    
    /**
     * Число запросов в текщий момент времени.
     * 
     * Используется для подсчета количества запросов в скрипте.
     */
    public $numOfQueries;
    
    /**
     * Конструктор, создает подключеник к базе данных.
     * 
     * @param string $dbName   имя базы данных
     * @param string $hostName адрес/хост расположения БД
     * @param string $username имя пользователя
     * @param string $userPass пароль доступа
     * 
     * @return void
     */
    private function __construct($dbName, $hostName, $userName, $userPass)
    {
        $this->numOfQueries = 0;
        $this->_connection  = $this->connect($hostName, $userName, $userPass);
        
        if (!$this->_connection) {
            throw new AppException("MySQL connection error", AppException::DB_CONNECTION_ERROR);
        } else {
            $this->select_db($dbName);
            
            $this->query("set names utf8");
            $this->query("set session character_set_server=utf8;");
            $this->query("set session character_set_database=utf8;");
            $this->query("set session character_set_connection=utf8;");
            $this->query("set session character_set_results=utf8;");
            $this->query("set session character_set_client=utf8;");
        }
    }
    
    /**
     * Деструктор, разрывает соединение с БД.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
    
    /**
     * Переопределение метода для предотвращение клонирования.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Предотвращение десериализации.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Создает и возвращает экземпляр класса.
     * 
     * @return Engine
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance) || is_null(self::$_instance)) {
            $class = __CLASS__;
            self::$_instance = new $class(
                Config::get('dbName'),
                Config::get('dbHost'),
                Config::get('dbUserName'),
                Config::get('dbUserPassword')
            );
        }
        
        return self::$_instance;
    }

    /**
     * Создает подключение к БД
     * 
     * @param string $hostName адрес/хост расположения БД
     * @param string $userName имя пользователя
     * @param string $userPass пароль доступа
     * 
     * @return resource|false
     */
    public function connect($hostName, $userName, $userPass)
    {
        return mysqli_connect($hostName, $userName, $userPass);
    }

    /**
     * Подключается к указанной БД.
     *
     * @param string $dbName имя БД
     *
     * @return bool результат подключения
     */
    public function select_db($dbName)
    {
        if (!$this->_connection) {
            return false;
        }
        
        return mysqli_select_db($this->_connection, $dbName);
    }

    /**
     * Выполняет запрос к БД.
     * 
     * Если запрос не смог выполниться, то в лог заносится запись об ошибке.
     * Также, здесь подсчитывается количество запросов к БД.
     * 
     * @param string $request  SQL-стока запроса
     * @param bool   $errorLog флаг, определяющий записывать ли в лог, в случае ошибки
     * 
     * @return результат запроса или false, в случае ошибки
     */
    public function query($request, $errorLog = true)
    {
        if (!$this->_connection) {
            return false;
        }
        
        //$dt = microtime(true);
        
        $requestRes = mysqli_query($this->_connection, $request);
        $this->numOfQueries++;
        
        //$dt = microtime(true) - $dt;
        
        if (!$requestRes && $errorLog) {
            Log::add($request . "<br>" . mysqli_error($this->_connection), Log::ERROR);
            return false;
        }
        // elseif ($DELTA_TIME >= 0.1) {
            //Log::add($_SERVER['PHP_SELF'] . '[' . $DELTA_TIME . ']<br/>' . $request, Log::WARNING);
        //}

        return $requestRes;
    }

    /**
     * Возвращает количество записей, после запроса на выборку.
     * 
     * @param resource $requestResult результат запроса
     * Например:
     * @code
     * $queryRes = $base->query("
     *     SELECT *
     *     FROM users
     *     WHERE (userAge < 18)
     * ");
     * 
     * $yangUserCount = $base->num_rows($queryRes);
     * @endcode
     * 
     * @return int|false
     */
    public function num_rows($requestResult)
    {
        return mysqli_num_rows($requestResult);
    }

    /**
     * Возвращает следующую запись результата запроса как массив значений по ключу и 
     * порядковому индексу. 
     * 
     * Например:
     * @code
     * $queryRes = $base->query("
     *     SELECT userId, userAge
     *     FROM users
     *     WHERE (userAge <= 18)
     * ");
     * 
     * while ($info = $base->fetch_assoc($queryRes)) {
     *     if ($info['userAge'] == 18) {
     *         // do something
     *     }
     * 
     *     if ($info[1] == 18) {
     *         // do something
     *     }
     * }
     * @endcode
     * 
     * @param resource $requestResult результат запроса
     *
     * @return mixed
    */
    public function fetch_array($requestResult)
    {
        return mysqli_fetch_array($requestResult);
    }

    /**
     * Возвращает следующую запись результата запроса как ассоциативный массив значений.
     * 
     * Например:
     * @code
     * $queryRes = $base->query("
     *     SELECT userId, userAge
     *     FROM users
     *     WHERE (userAge <= 18)
     * ");
     * 
     * while ($info = $base->fetch_assoc($queryRes)) {
     *     if ($info['userAge'] == 18) {
     *         // do something
     *     }
     * }
     * @endcode
     * 
     * @param resource $requestResult результат запроса
     *
     * @return mixed
    */
    public function fetch_assoc($requestResult)
    {
        return mysqli_fetch_assoc($requestResult);
    }

    /**
     * Число затронутых записей после запроса.
     * 
     * @code
     * $base->query("
     *     UPDATE users 
     *     SET userStatus = 5 
     *     WHERE (userAge > 18)
     * ");
     * 
     * if ($base->affected_rows() <= 0) {
     *     echo "нет пользователей, старше 18 лет";
     * }
     * @endcode
     * 
     * @return int|false
     */
    public function affected_rows()
    {
        if (!$this->_connection) {
            return false;
        }
        
        return mysqli_affected_rows($this->_connection);
    }
    
    /**
     * Смещает ссылку в результате запроса на указанную позицию.
     * 
     * @param resource $requestResult
     * @param int      $row
     * 
     * @return void
     */
    public function data_seek($requestResult, $row)
    {
        return mysqli_data_seek($requestResult, $row);
    }
    
    /**
     * Закрывает соединение с сервером БД.
     * 
     * @return void
     */
    public function close()
    {
        if ($this->_connection) {
            mysqli_close($this->_connection);
            $this->_connection = null;
        }
    }
}

?>