<?php

namespace Modules\Base\Contracts;

use Modules\Base\BaseRepository;

abstract class AbstractBaseService implements BaseServiceInterface
{
    protected BaseRepository $repo;

    public function forceDelete(int $id): bool
    {
        return $this->repo->forceDelete($id);
    }

    public function restore(int $id): bool
    {
        return $this->repo->restore($id);
    }
}
