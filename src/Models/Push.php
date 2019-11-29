<?php

namespace Trax\Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trax_sync_push';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;    
    
}
