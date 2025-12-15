<?php

namespace Goapptiv\Pulse\Models;

use Goapptiv\Pulse\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PulseEntry extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    public static $TABLE = 'pulse_entries';

    /**
     * Remove timestamp
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the base query for a given type and key path
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @param  string  $keyJsonPath
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForFilterByTypeAndKey($query, $type, $keyAlias)
    {
        $interval = (int) env('PULSE_CRON_INTERVAL', 10);
        
        return $query->select([
            'id',
            'timestamp',
            'type',
            'key',
            'value',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`key`, '$[1]')) AS {$keyAlias}")
        ])
        ->where('type', $type)
        ->whereBetween('timestamp', [
            now()->subMinutes($interval)->getTimestamp(),
            now()->getTimestamp(),
        ])
        ->where('status',Constants::$PENDING)
        ->orderBy('timestamp');
    }
}