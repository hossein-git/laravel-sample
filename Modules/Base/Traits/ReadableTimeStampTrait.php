<?php

namespace Modules\Base\Traits;

use Carbon\Carbon;

trait ReadableTimeStampTrait
{
    public function getFormattedCreatedAt(): ?string
    {
        if (!array_key_exists('created_at', $this->attributes)) {
            return null;
        }

        return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']))->toDateTimeString();
    }

    public function getFormattedUpdatedAt(): ?string
    {
        if (!array_key_exists('updated_at', $this->attributes)) {
            return null;
        }

        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->toDateTimeString();
    }
}
