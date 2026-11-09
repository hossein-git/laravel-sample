<?php

namespace Modules\User\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Trade\Models\AccountStrategyChart;
use Modules\User\Models\Account;
use Modules\User\Repositories\AccountRepository;

readonly class AccountService
{
    private const ACTIVE_ACCOUNTS_CACHE_PREFIX = 'accounts:active:broker:';

    private const ACCOUNT_RESTRICTIONS_CACHE_PREFIX = 'accounts:restrictions:broker:';

    public function __construct(private AccountRepository $repository) {}

    public function index(array $queries = []): LengthAwarePaginator
    {
        $userId = $queries['user_id'] ?? null;

        return $this->repository->allQuery($queries['search'] ?? '')
            ->when($userId, fn ($query) => $query->where('id', $userId))
            ->paginate($queries['per_page'] ?? 15);
    }

    public function show(int $id): ?Model
    {
        return $this->repository->find($id, ['broker']);
    }

    /**
     * Active accounts owned by the given user, across every broker, main account first then by
     * account number, with the broker name eager-loaded. Used by the ManualOrderTrader EA's
     * in-chart account picker (see MqlManualOrderController::accounts).
     *
     * @return Collection<int, Account>
     */
    public function activeAccountsForUser(int $userId): Collection
    {
        return $this->repository->where('user_id', $userId)
            ->where('status', Account::STATUS_ACTIVE)
            ->with('broker:id,name')
            ->orderByDesc('is_main')
            ->orderBy('account_number')
            ->get();
    }

    public function create(array $input): Model
    {
        $account = $this->repository->create($input);
        if (isset($input['broker_id'])) {
            $this->forgetCachedActiveAccounts((int) $input['broker_id']);
        }

        return $account;
    }

    public function updateOrCreate(array $attributes, array $values = []): Account
    {
        $account = $this->repository->updateOrCreate($attributes, $values);
        $brokerId = (int) ($account->broker_id ?? $attributes['broker_id'] ?? $values['broker_id'] ?? 0);
        if ($brokerId > 0) {
            $this->forgetCachedActiveAccounts($brokerId);
        }

        return $account;
    }

    public function delete(int $id): ?bool
    {
        $account = $this->repository->find($id);
        $result = $this->repository->delete($id);
        if ($result && $account) {
            $this->forgetCachedActiveAccounts((int) $account->broker_id);
        }

        return $result;
    }

    public function findOrCreateAndCache(string $accountNumber, array $values = []): Account
    {
        $userId = (int) $values['user_id'];
        $brokerId = (int) $values['broker_id'];

        $cacheKey = sprintf('accounts:id:%d:%d:%s', $userId, $brokerId, $accountNumber);

        return Cache::remember($cacheKey, now()->addDays(6), function () use ($accountNumber, $values, $userId): Account {
            return $this->repository->findOrCreate(
                ['account_number' => $accountNumber, 'user_id' => $userId],
                $values
            );
        });
    }

    public function deleteUserAccount(int $id, int $userID): ?bool
    {
        return $this->repository->where('id', $id)->where('user_id', $userID)->delete();
    }

    public function update(array $input, int $id): Model
    {
        $account = $this->repository->where('id', $id)
            ->where('user_id', $input['user_id'])
            ->firstOrFail();

        $account->fill($input);
        $account->save();

        $this->forgetCachedActiveAccounts((int) $account->broker_id);

        return $account;
    }

    public function forgetCachedActiveAccounts(int $brokerId): void
    {
        Cache::forget(self::ACTIVE_ACCOUNTS_CACHE_PREFIX.$brokerId);
        Cache::forget(self::ACCOUNT_RESTRICTIONS_CACHE_PREFIX.$brokerId);
    }
}
