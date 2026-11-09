<?php

namespace Modules\Employee\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Employee\Models\Role;
use Modules\Employee\Repositories\RoleRepository;

/**
 * @property RoleRepository repo
 */
class RoleService
{
    public function __construct(private readonly RoleRepository $repo) {}

    public function index(array $input = []): Collection
    {
        $guard = $input['guard'] ?? null;

        // show all except super admin
        return $this->repo->allQuery('', ['permissions'])
            ->when($guard, function ($query) use ($guard) {
                return $query->where('guard_name', $guard);
            })
            ->get();
    }

    public function store(array $input): Model
    {
        return $this->repo->create($input)->syncPermissions($input['permissions']);
    }

    public function update(array $input, int $id): Model
    {
        return $this->repo->update($input, $id)->syncPermissions($input['permission_id']);
    }

    public function show(int $id): ?Model
    {
        return $this->repo->find($id, ['permissions']);
    }

    public function destroy(int $id): bool
    {
        /** @var Role $role */
        $role = $this->repo->find($id);
        $errorMessage = 'You can not remove this role. it has %s assigned.';
        if ($role->employees()?->count()) {
            throw ValidationException::withMessages([sprintf($errorMessage, 'employees')]);
        }

        if ($role->users()?->count()) {
            throw ValidationException::withMessages([sprintf($errorMessage, 'users')]);
        }

        if ($role->plan()?->count()) {
            throw ValidationException::withMessages([sprintf($errorMessage, 'plans')]);
        }

        return $this->repo->delete($id);
    }

    public function find(int $id): ?Model
    {
        return $this->repo->findWithTrash($id);
    }

    public function getRoleGuard(int $id): ?string
    {
        return $this->repo->find($id, [], ['guard_name'])?->getOriginal('guard_name');
    }
}
