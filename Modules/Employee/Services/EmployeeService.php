<?php

namespace Modules\Employee\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Models\Employee;
use Modules\Employee\Repositories\EmployeeRepository;

class EmployeeService
{
    public function __construct(private readonly EmployeeRepository $repo) {}

    public function index(?string $search = null, int $paginate = 15): LengthAwarePaginator
    {
        return $this->repo->paginate(
            $search,
            $paginate,
            ['roles']
        );
    }

    public function update(array $input, int $id): Model
    {
        $this->checkSuperAdmin($id);

        return $this->repo->update($input, $id)->syncRoles($input['roles']);
    }

    public function checkSuperAdmin(int $id): void
    {
        if ($this->repo->find($id)?->hasRole('super-admin')) {
            throw new Exception(__('messages.cantCreateAdmin'), 403);
        }
    }

    public function show(int $id): ?Model
    {
        return $this->repo->find($id, ['roles']);
    }

    public function store(array $input): Model
    {
        $employee = $this->repo->create($input);
        $employee->assignRole($input['roles']);
        if (isset($input['media'])) {
            $employee->addMedia($input['media'])
                ->usingName($employee->name)
                ->toMediaCollection(Employee::MEDIA_COLLECTION);
        }

        return $employee;
    }

    public function destroy(int $id): ?bool
    {
        $this->checkSuperAdmin($id);

        return $this->repo->deleteWithoutMedia($id);
    }

    public function recycleBin(): Collection
    {
        return $this->repo->trash();
    }
}
