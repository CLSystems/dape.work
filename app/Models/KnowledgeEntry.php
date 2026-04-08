<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeEntry extends Model
{
    use HasFactory;

    protected $table = 'knowledge_entries';

    protected $fillable = [
        'slug',
        'system',
        'category',
        'title',
        'structured_payload',
        'status',
        'version',
        'last_verified_at',
    ];

    protected $casts = [
        'structured_payload' => 'array',
        'last_verified_at'   => 'datetime',
    ];

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_REVIEWED  = 'reviewed';
    public const STATUS_PUBLISHED = 'published';
    public const CATEGORY_ALERT        = 'alerts';
    public const CATEGORY_ERROR        = 'errors';
    public const CATEGORY_PERFORMANCE  = 'performance';

    public static function validCategories(): array
    {
        return [
            self::CATEGORY_ALERT,
            self::CATEGORY_ERROR,
            self::CATEGORY_PERFORMANCE,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($entry) {
            if (!in_array($entry->category, self::validCategories(), true)) {
                throw new \InvalidArgumentException(
                    "Invalid category: {$entry->category}"
                );
            }
        });
    }

    public function getCanonicalUrlAttribute(): string
    {
        return sprintf(
            'https://dape.work/%s/%s/%s.html',
            $this->system,
            $this->category,
            $this->slug
        );
    }
}
