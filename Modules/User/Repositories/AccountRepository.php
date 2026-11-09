<?php

namespace Modules\User\Repositories;

use Modules\Base\BaseRepository;
use Modules\User\Models\Account;

class AccountRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'status',
        'account_number',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Account::class;
    }

    public function updateOrCreate(array $attributes, array $values = []): Account
    {
        /** @var Account $model */
        $model = $this->makeModel()->updateOrCreate($attributes, $values);
        if ($model->wasRecentlyCreated) {
            $this->clearCache();
        }

        return $model;
    }

    public function findOrCreate(array $attributes, array $values): Account
    {
        $model = $this->makeModel()->firstOrCreate($attributes, $values);
        if ($model->wasRecentlyCreated) {
            $this->clearCache();
        }

        return $model;
    }
}
