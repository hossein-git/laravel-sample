<?php

namespace Modules\Employee\Repositories;

use Modules\Base\BaseRepository;
use Modules\Employee\Models\Role;

/**
 * Class RoleRepository.
 *
 * @version June 05, 2020,
 */
class RoleRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Role::class;
    }
}
