<?php

namespace Modules\User\Repositories;

use Illuminate\Http\Request;
use Modules\Base\BaseRepository;
use Modules\User\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class UserRepository.
 *
 * @version June 5, 2020, 10:41 pm +0330
 */
class UserRepository extends BaseRepository
{
    protected array $fieldSearchable = [
        'name',
        'email',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }

    /**
     * update user avatar.
     */
    public function userUpdate(Request $request, int $id): bool
    {
        /** @var User $user */
        $user = $this->find($id);

        return (bool) $this->updateMedia($request, $user);
    }

    private function updateMedia(Request $request, User $user): ?Media
    {
        $result = null;
        if ($request->hasFile('cover')) {
            $result = $user->addMedia($request->file('cover')?->getRealPath())
                ->usingName('cover')
                ->toMediaCollection('covers');
        }

        return $result;
    }
}
