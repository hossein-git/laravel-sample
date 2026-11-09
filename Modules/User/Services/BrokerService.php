<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\Broker;
use Modules\User\Repositories\BrokerRepository;

readonly class BrokerService
{
    public function __construct(private BrokerRepository $repository) {}

    public function index(array $queries): LengthAwarePaginator
    {
        $userId = $queries['user_id'] ?? null;

        return $this->repository->allQuery($queries['search'] ?? '', ['accounts'])
            ->when($userId, fn ($query) => $query->whereHas('users', fn ($query) => $query->where('id', $userId)))
            ->paginate($queries['per_page'] ?? 15);
    }

    public function show(int $id, ?int $userId = null): ?Model
    {
        /** @var Broker $broker */
        $broker = $this->repository->find($id);

        // If a user is authenticated, ensure they can only view brokers they are associated with
        if ($userId !== null && ! $broker->users->contains('id', $userId)) {
            return null;
        }

        return $broker;
    }

    public function updateOrCreate(array $attributes, array $values = []): Broker
    {
        $broker = $this->repository->updateOrCreate($attributes, $values);

        $userId = $values['user_id'] ?? null;
        if ($userId) {
            $this->repository->attachUser($broker, $userId);
        }

        return $broker;
    }

    public function findOrCreate(string $name, array $values = []): Broker
    {
        if (! array_key_exists('timezone', $values)) {
            $values['timezone'] = \Config::get('forex.broker_time_zone');
        }

        $broker = $this->repository->findOrCreate(['name' => $name], $values);

        $userId = $values['user_id'] ?? null;
        if ($userId && $broker->wasRecentlyCreated) {
            $this->repository->attachUser($broker, $userId);
        }

        return $broker;
    }

    /**
     * Brokers that have at least one account assigned.
     *
     * @return Collection<int, Broker>
     */
    public function getBrokersWithAccounts(): Collection
    {
        return $this->repository->withAccounts();
    }

    public function delete(int $id): ?bool
    {
        return $this->repository->delete($id);
    }

    public function attachUser(int $brokerId, int $userId): void
    {
        $broker = $this->repository->find($brokerId);
        $this->repository->attachUser($broker, $userId);
    }

    public function detachUser(int $brokerId, int $userId): void
    {
        $broker = $this->repository->find($brokerId);
        $this->repository->detachUser($broker, $userId);
    }

    public function syncUsers(int $brokerId, array $userIds): void
    {
        $broker = $this->repository->find($brokerId);

        $this->repository->syncUsers($broker, $userIds);
    }
}
