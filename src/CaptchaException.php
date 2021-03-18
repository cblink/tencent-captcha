<?php
namespace Cblink\TencentCaptcha;

use InvalidArgumentException;

/**
 * Class CaptchaException
 * @package App\Sms
 */
class CaptchaException extends InvalidArgumentException
{
    protected $statusCode = 400;

}
