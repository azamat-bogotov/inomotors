<?php

namespace App\SiteBundle\Core\Log;

/**
 * @file
 * Содержит класс для работы с логированием.
 * 
 * @author Azamat Bogotov azamat@e-magic.org
 */

/**
 * Статический класс для логирования.
 * 
 * Используется для ведения лога ошибок, предупреждений в приложении или для отладки.
 * Данные лога записываются в файл в html формате, для более наглядного дальнейшего просмотра.
 * 
 * Пример использования:<br/>
 * @code
 * Log::add("error read file in method readUserParams()", Log::ERROR);
 * Log::d("debug");
 * Log::i("info");
 * Log::w("warning");
 * Log::e("error");
 * @endcode
 * 
 * @author Azamat Bogotov azamat@e-magic.org
 * @version 0.5
 */
class Log
{
    /**
     * @name Уровни логирования.
     * @{
     */
    const DEBUG   = 0; ///< режим отладки
    const INFO    = 1; ///< информационные сообщения
    const WARNING = 2; ///< предупреждения
    const ERROR   = 3; ///< фатальные ошибки
    /** @} */
    
    /**
     * Массив цветов для каждого уровня логирования
     */
    private static $_logColors = array(
        '#CCEECC', // debug
        '#EEEECC', // info
        '#CCCCEE', // warning
        '#EECCCC'  // error
    );
    
    /**
     * Флаг активности(работы) логирования.
     */
    private static $_enabled = true;

    /**
     * Имя сохраняемого файла для лога.
     */
    public static $fileName = 'log.html';

    /**
     * Приватный конструктор.
     */
    private function __construct()
    {
    }

    /**
     * Добавляет новую запись в лог-файл.
     * 
     * @param string $msg  текст сообщения
     * @param int    $type тип логирования
     * 
     * @return void
     */
    public static function add($msg, $type = Log::DEBUG)
    {
        if (!self::$_enabled) {
            return;
        }
        
        if (!isset(self::$_logColors[$type])) {
            return;
        }
        
        if ($type == self::ERROR || $type == self::WARNING) {
            $trace = debug_backtrace();
            $msg .= '<br/>';
            
            foreach ($trace as $key => $info) {
                $msg .= '<br/>' . $info['file'] . '::' . $info['line'];
                
                if (isset($info['args'])) {
                    $msg .= ', args: ' . implode('|', array_values($info['args']));
                }
            }
        }
        
        $msg = '<div style="background: ' . self::$_logColors[$type] 
             . '; margin-top: 2px; border: #888 1px solid;">['
             . date("d/m_H:i:s") . ']&nbsp;&nbsp;' . $msg . '</div>';
        
        // запись в файл
        $fp = fopen(self::$fileName, "a+");
        fwrite($fp, $msg);
        fclose($fp);
    }
    
    /**
     * Добавляет отладочную запись в лог-файл.
     * 
     * @param string $msg текст сообщения
     * 
     * @see Log::add()
     * @return void
     */
    public static function d($msg) {
        self::add($msg, self::DEBUG);
    }
    
    /**
     * Добавляет информационную запись в лог-файл.
     * 
     * @param string $msg текст сообщения
     * 
     * @see Log::add()
     * @return void
     */
    public static function i($msg) {
        self::add($msg, self::INFO);
    }
    
    /**
     * Добавляет запись о предупреждении в лог-файл.
     * 
     * @param string $msg текст сообщения
     * 
     * @see Log::add()
     * @return void
     */
    public static function w($msg) {
        self::add($msg, self::WARNING);
    }

    /**
     * Добавляет запись об ошибке в лог-файл.
     * 
     * @param string $msg текст сообщения
     * 
     * @see Log::add()
     * @return void
     */
    public static function e($msg) {
        self::add($msg, self::ERROR);
    }
    
    /**
     * Очистка лог-файла.
     *
     * @return void
     */
    public static function clear()
    {
        $fp = fopen(self::$fileName, "w");
        fwrite($fp, "");
        fclose($fp);
    }
}
