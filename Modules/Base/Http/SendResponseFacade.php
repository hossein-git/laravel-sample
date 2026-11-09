<?php

namespace Modules\Base\Http;

use Illuminate\Http\JsonResponse;
use Modules\Base\BaseFacade;
use Modules\Base\Http\Responses\VueResponses;

/**
 * @method static JsonResponse created(string $model_name)
 * @method static JsonResponse createdFailed(string $model_name)
 * @method static JsonResponse updated(string $model_name)
 * @method static JsonResponse updatedFailed(string $model_name)
 * @method static JsonResponse statusUpdated(string $model_name)
 * @method static JsonResponse statusUpdatedFailed(string $model_name)
 * @method static JsonResponse deleted(string $model_name)
 * @method static JsonResponse deletedFailed(string $model_name)
 * @method static JsonResponse destroyed(string $model_name)
 * @method static JsonResponse destroyedFailed(string $model_name)
 * @method static JsonResponse restored(string $model_name)
 * @method static JsonResponse restoredFailed(string $model_name)
 * @method static JsonResponse sendCustomResponse(array|string $data, string $message = '', int $code = 200)
 *
 * @see \Modules\Base\Contracts\ResponseInterface
 */
class SendResponseFacade extends BaseFacade
{
    protected static function getFacadeAccessor(): string
    {
        // here we can change the response according different devices/requests
//        if (request('client') == 'android') {
//            return AndroidResponses::class;
//        }
        // TODO: add response for user panel

        return VueResponses::class;
    }
}
