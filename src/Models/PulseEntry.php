<?php

namespace Goapptiv\Pulse\Models;

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
            now()->subMinutes(100)->getTimestamp(),
            now()->getTimestamp(),
        ])
        ->orderBy('timestamp');
    }
}