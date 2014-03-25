<?php

namespace App\SiteBundle\Core\Config;

/**
 * @file
 * Содержит статический сласс для хранения настроек приложения.
 * 
 * @autor Azamat Bogotov azamat@e-magic.org
 */

/**
 * Статический класс для хранения опций и настроек приложения.
 * 
 * Пример вызова: Config::get('paramName').
 * 
 * @autor Azamat Bogotov azamat@e-magic.org
 * @version 0.5
 */
class Config
{
    /**
     * Ассоциативный массив для хранения настроек
     */
    protected static $_container = array(
        'testMode'     => 1,
        'supportMail'  => '',
        'secretKey'    => 'df5876asd=)J_@k56fR#$SJ_#2<=cs32', // секретный ключ для различных нужд
        
        'cookieDomain' => '',
        'cookiePath'   => '/',

        'dbHost'         => 'localhost',
        'dbUserName'     => 'root',
        'dbUserPassword' => '',
        'dbName'         => 'test',
    );

    /**
     * Приватный конструктор.
     * 
     * @return self
     */
    private function __construct()
    {
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
     * Возвращает значение опции из массива по указанному имени(ключу).
     * 
     * @param string $param имя параметра
     *
     * @return mixed
     */
    public static function get($param)
    {
        return self::$_container[$param];
    }
}
