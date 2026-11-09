<?php

namespace Modules\Base\Http\Responses;

use Illuminate\Http\JsonResponse;
use Modules\Base\Contracts\ResponseInterface;

class VueResponses implements ResponseInterface
{
    public function created(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('created successfully')]);
    }

    public function createdFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('create failed')], 500);
    }

    public function updated(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('updated successfully'), 201]);
    }

    public function updatedFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('update failed')], 500);
    }

    public function statusUpdated(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('status updated successfully'), 201]);
    }

    public function statusUpdatedFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('status update failed')], 500);
    }

    public function deleted(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('deleted')], 202);
    }

    public function deletedFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('deleted failed')], 500);
    }

    public function destroyed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('deleted for ever')], 202);
    }

    public function destroyedFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('deleted failed')], 500);
    }

    public function restored(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('has being restored')], 200);
    }

    public function restoredFailed(string $modelName): JsonResponse
    {
        return response()->json(['message' => $modelName . ': ' . __('restore failed')], 500);
    }

    public function sendCustomResponse(array|string $data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json(['data' => $data, 'message' => $message], $code);
    }
}
