<?php

namespace Modules\User\Repositories;

use Modules\Base\BaseRepository;
use Modules\User\Models\NotificationConfig;

class NotificationConfigRepository extends BaseRepository
{
    public function getFieldsSearchable(): array
    {
        return [];
    }

    public function model(): string
    {
        return NotificationConfig::class;
    }
}
