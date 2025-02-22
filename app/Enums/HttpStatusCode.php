<?php

namespace app\Enums;

final class HttpStatusCode
{
    public const OK                     = 200;
    public const BAD_REQUEST            = 400;
    public const UNAUTHORIZED           = 401;
    public const NOT_FOUND              = 404;
    public const METHOD_NOT_ALLOWED     = 405;
    public const UNPROCESSABLE_ENTITY   = 422;
    public const INTERNAL_SERVER_ERROR  = 500;
    public const FORBIDDEN              = 403;
    public const EXPIRED                = 410;

    public static function all()
    {
        return [
            self::OK,
            self::BAD_REQUEST,
            self::UNAUTHORIZED,
            self::NOT_FOUND,
            self::METHOD_NOT_ALLOWED,
            self::UNPROCESSABLE_ENTITY,
            self::INTERNAL_SERVER_ERROR,
            self::FORBIDDEN,
            self::EXPIRED
        ];
    }
}
