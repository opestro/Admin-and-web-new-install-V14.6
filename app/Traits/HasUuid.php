<?php

namespace App\Traits;

use Ramsey\Uuid\Nonstandard\Uuid;

trait HasUuid
{
    public function initializeHasUuid(): void
    {
        $this->setKeyType('string');
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4();
            if (!isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;
                $uuid = Uuid::uuid4();
                $model->attributes[$model->getKeyName()] = $uuid->toString();
            }
        }, 0);
    }
}
