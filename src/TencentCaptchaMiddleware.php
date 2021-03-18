<?php

namespace Cblink\TencentCaptcha;

use Closure;

/**
 * Class TencentCaptcha
 * @package Contest\User
 */
class TencentCaptchaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (config('services.tencent_captcha.open', true)) {
            \Validator::validate($request->all(), [
                'captcha_str' => ['required'],
                'captcha_ticket' => ['required', new TencentCaptchaRule($request)],
            ]);
        }

        return $next($request);
    }
}
