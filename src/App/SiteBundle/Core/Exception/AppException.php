<?php

namespace App\SiteBundle\Exception;

use Exception;

class AppException extends Exception
{
    const INTERNAL_ERROR         = 100; // Общая ошибка фреймворка.
    const APP_ERROR              = 101; // Ошибка игры.
    const APP_NOT_AVAIBLE        = 103; // Игра недоступна.

    const MISSING_PARAM          = 130; // Параметр отсутствует.
    const WRONG_PARAM            = 131; // Неправильный параметр.
    const WRONG_ACTION           = 132; // Недопустимое действие.

    const DB_ERROR               = 140; // Ошибка с кэшем.
    const DB_CONNECTION_ERROR    = 141; // Ошибка подключения к кэшу.
}
