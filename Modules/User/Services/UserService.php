<?php

namespace Modules\User\Services;

use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

/**
 * @property UserRepository repo
 */
class UserService
{
    public function __construct(private readonly UserRepository $repo) {}

    public function findByEmail(string $email): ?User
    {
        return $this->repo->where('email', $email)->first();
    }

    public function getSuperUserId(): ?int
    {
        return $this->repo->where('email', User::SUPER_USER_EMAIL)->first(['id'])?->id;
    }
}
