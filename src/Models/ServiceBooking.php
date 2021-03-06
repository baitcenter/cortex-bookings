<?php

declare(strict_types=1);

namespace Cortex\Bookings\Models;

use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Bookings\Models\BookableBooking;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceBooking extends BookableBooking
{
    use Auditable;
    use HashidsTrait;
    use LogsActivity;

    /**
     * Indicates whether to log only dirty attributes or all.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are logged on change.
     *
     * @var array
     */
    protected static $logFillable = true;

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
