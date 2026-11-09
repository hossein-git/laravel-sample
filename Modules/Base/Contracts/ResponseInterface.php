<?php

namespace Modules\Base\Contracts;

use Illuminate\Http\JsonResponse;

interface ResponseInterface
{
    public function created(string $modelName): JsonResponse;

    public function createdFailed(string $modelName): JsonResponse;

    public function updated(string $modelName): JsonResponse;

    public function updatedFailed(string $modelName): JsonResponse;

    public function statusUpdated(string $modelName): JsonResponse;

    public function statusUpdatedFailed(string $modelName): JsonResponse;

    public function deleted(string $modelName): JsonResponse;

    public function deletedFailed(string $modelName): JsonResponse;

    public function destroyed(string $modelName): JsonResponse;

    public function destroyedFailed(string $modelName): JsonResponse;

    public function restored(string $modelName): JsonResponse;

    public function restoredFailed(string $modelName): JsonResponse;

    public function sendCustomResponse(array|string $data, string $message = '', int $code = 200): JsonResponse;
}
