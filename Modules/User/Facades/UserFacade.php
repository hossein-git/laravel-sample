<?php

namespace Modules\User\Facades;

use Modules\Base\BaseFacade;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

/**
 * @method static User|null findByEmail(string $email)
 * @method static int|null getSuperUserId()
 *
 * @see UserService
 */
class UserFacade extends BaseFacade {}
