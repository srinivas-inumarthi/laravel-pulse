<?php

namespace Goapptiv\Pulse;

class Constants
{

    public static $SLOW_REQUEST = 'slow_request';

    public static $FAILED_REQUEST = 'exception';

    public static $PENDING = 'pending';

    public static $PROCESSED = 'processed';

    public static $ACTIVE = 'active';

    public static $INACTIVE = 'inactive';

    public static $STATUSES = [];

    /**
     * Initialize all the Constants
     */
    public static function init()
    {
        self::$STATUSES = [
            self::$ACTIVE,
            self::$INACTIVE
        ];
    }
}

Constants::init();