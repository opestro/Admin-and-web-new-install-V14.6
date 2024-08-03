<?php

namespace App\Traits;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Session;

trait RecaptchaTrait
{
    protected function isGoogleRecaptchaValid(string $reCaptchaValue): bool
    {
        $secret_key = getWebConfig(name: 'recaptcha')['secret_key'];
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $reCaptchaValue;
        $response = json_decode(file_get_contents($url));
        if ($response->success) {
            return true;
        }
        return false;
    }

    public function generateDefaultReCaptcha(int $captureLength): CaptchaBuilder
    {
        $phrase = new PhraseBuilder;
        $code = $phrase->build($captureLength);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        return $builder;
    }


    public function saveRecaptchaValueInSession(string $sessionKey, string $sessionValue):void{
        if (Session::has($sessionKey)) {
            Session::forget($sessionKey);
        }
        Session::put($sessionKey, $sessionValue);
    }
}
