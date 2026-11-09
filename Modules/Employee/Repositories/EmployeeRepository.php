<?php

namespace Modules\Employee\Repositories;

use Modules\Base\BaseRepository;
use Modules\Employee\Models\Employee;

/**
 * Class EmployeeRepository.
 *
 * @version June 5, 2020, 10:20 pm +0330
 */
class EmployeeRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'email',
        'phone',
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Employee::class;
    }
}
