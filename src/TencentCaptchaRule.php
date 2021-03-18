<?php

namespace Cblink\TencentCaptcha;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class TencentCaptchaRule implements Rule
{
    protected $request;

    /**
     * Create a new rule instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function passes($attribute, $value)
    {
        try {
            /** @var TencentCaptcha * */
            $captcha = app(TencentCaptcha::class);

            return $captcha->verify($value, $this->request->input('captcha_str'), $this->request->ip());
        } catch (CaptchaException $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '验证码验证失败，请重试!';
    }
}
