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
        'size',
        'thumbnail_path'
    ];

    public function getUrlAttribute(): ?string
    {
        if (!$this->stored_name) {
            return null;
        }
        return route('admin.file.uploaded_asset', ['stored_name' => $this->stored_name]);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail_path) {
            return null;
        }
        return route('admin.file.thumbnail', ['fileId' => $this->id]);
    }
}
