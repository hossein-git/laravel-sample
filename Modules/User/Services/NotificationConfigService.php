<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\User\Models\NotificationConfig;
use Modules\User\Repositories\NotificationConfigRepository;
use Illuminate\Http\Request;

class NotificationConfigService
{
    public function __construct(private readonly NotificationConfigRepository $notificationConfigRepository) { }

    public function updateOrCreate(array $input): NotificationConfig
    {
        return $this->notificationConfigRepository->makeModel()
            ->updateOrCreate(
                [
                    'type' => $input['type'],
                    'user_id' => $input['strategy_id'],
                    'strategy_id' => $input['strategy_id'],
                    'chart_namespace' => $input['chart_namespace'],
                ],
                [
                    'user_id' => $input['user_id'],
                    'strategy_id' => $input['strategy_id'],
                    'chart_namespace' => $input['chart_namespace'],
                    'status' => $input['status'],
                    'type' => $input['type'],
                ]
            );
    }

    public function userNotificationConfigs(int $userId): Collection
    {
        return $this->notificationConfigRepository->where('user_id', $userId)->get();
    }
}
