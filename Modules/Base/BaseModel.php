<?php

namespace Modules\Base;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasFactory;

    abstract public static function modelName(): string;

    /**
     * get created at in readable form.
     */
    public function formattedCreatedAt(): string
    {
        if (!array_key_exists('created_at', $this->attributes)) {
            return '';
        }

        return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']))->toDateTimeString();
    }

    /**
     * get updated at in readable form.
     */
    public function formattedUpdatedAt(): string
    {
        if (!array_key_exists('updated_at', $this->attributes)) {
            return '';
        }

        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->toDateTimeString();
    }

    /**
     * get deleted_at in readable form.
     */
    public function formattedDeletedAt(): string
    {
        if (!array_key_exists('deleted_at', $this->attributes)) {
            return '';
        }

        return Carbon::createFromTimeStamp(strtotime($this->attributes['deleted_at']))->toDateTimeString();
    }
}
