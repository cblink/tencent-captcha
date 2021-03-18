<?php

namespace Cblink\TencentCaptcha;

use GuzzleHttp\Client;

/**
 * Class Captcha
 */
class TencentCaptcha
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url = 'https://ssl.captcha.qq.com/ticket/verify';

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $secret;

    public function __construct(Client $client, string $appId = null, string $secret = null)
    {
        $this->client = $client;
        $this->appId = $appId ?? config('services.tencent_captcha.app_id', '');
        $this->secret = $secret ?? config('services.tencent_captcha.secret', '');
    }

    /**
     * 验证验证码
     *
     * @param string $ticket  票据，由前端提供
     * @param string $ip      ip，服务端获取
     * @param string $randStr 随机字符,有前端提供
     *
     * @return bool
     *
     * @throws CaptchaException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify(string $ticket, string $randStr, string $ip)
    {
        return $this->request([
            'UserIp' => $ip,
            'Ticket' => $ticket,
            'Randstr' => $randStr,
        ]);
    }

    /**
     * @return bool
     *
     * @throws CaptchaException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(array $params)
    {
        $option = [
            'http_errors' => false,
            'verify' => false,
            'query' => [
                'aid' => $this->appId,
                'AppSecretKey' => $this->secret,
            ],
        ];

        $option['query'] = array_merge($option['query'], $params);

        $res = $this->client->request('GET', $this->url, $option);

        if ($res->getStatusCode() === 200) {
            $data = json_decode($res->getBody()->getContents(), true);

            if ($data['response'] === '1') {
                return true;
            }

            info('tencent captcha', [
                'data' => $data,
                'appId' => $this->appId,
            ]);

            throw new CaptchaException('验证失败，请重新验证!');
        }

        throw new CaptchaException('验证失败，请重新验证!');
    }
}
