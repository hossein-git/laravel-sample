<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Base\BaseRepository;
use Modules\User\Models\Broker;

class BrokerRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'name',
        'timezone',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Broker::class;
    }

    public function updateOrCreate(array $attributes, array $values = []): Broker
    {
        $this->clearCache();

        return $this->makeModel()->updateOrCreate($attributes, $values);
    }

    public function findOrCreate(array $attributes, array $values = []): Broker
    {
        $model = $this->makeModel()->firstOrCreate($attributes, $values);

        if ($model->wasRecentlyCreated) {
            $this->clearCache();
        }

        return $model;
    }

    /**
     * Brokers that have at least one account assigned.
     *
     * @return Collection<int, Broker>
     */
    public function withAccounts(): Collection
    {
        return $this->makeModel()->newQuery()->has('accounts')->get();
    }

    public function attachUser(Broker $broker, int $userId): void
    {
        $broker->users()->attach($userId);
    }

    public function detachUser(Broker $broker, int $userId): void
    {
        $broker->users()->detach($userId);
    }

    public function syncUsers(Broker $broker, array $userIds): void
    {
        $broker->users()->sync($userIds);
    }
}
