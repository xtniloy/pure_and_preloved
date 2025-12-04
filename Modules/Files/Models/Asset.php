<?php

namespace Modules\Files\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'stored_name',
        'directory',
        'path',
        'fileable_type',
        'fileable_id',
        'metadata',
        'mime_type',
        'order',
        'group',
        'status',
        'size'
    ];

}
