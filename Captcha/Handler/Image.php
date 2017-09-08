<?php
/**
 * 图片验证码当前只支持英文字符
 */
namespace Base\Captcha\Handler;

class Image implements CaptchaInterface {

    const DEFAULT_WIDTH = 80;
    const DEFAULT_HEIGHT = 30;

    const CAPTCHA_FONT_ROOT = '/S/Captcha/Conf/Font/';

    public static $default_english_font = array('bookos.ttf', 'cour.ttf', 'georgia.ttf', 'gothic.ttf');

    public function show($args) {

        $width = $args['width'] ?: self::DEFAULT_WIDTH;
        $height = $args['height'] ?: self::DEFAULT_HEIGHT;
        $size = $args['size'] ?: $this->getRand(intval($width / 6), intval($width / 5));

        $font = self::$default_english_font;

        $img = imagecreate($width, $height);

        $colors[0] = imagecolorresolve($img, 255, 255, 255);    //white
        $colors[1] = imagecolorresolve($img, 0, 0, 0);    //black
        $colors[2] = imagecolorresolve($img, 9, 9, 53);
        $colors[3] = imagecolorresolve($img, 53, 9, 9);
        $colors[4] = imagecolorresolve($img, 10, 53, 10);
        $colors[5] = imagecolorresolve($img, 53, 52, 58);
        $colors[6] = imagecolorresolve($img, 41, 39, 29);
        $colors[7] = imagecolorresolve($img, 41, 44, 14);
        $colors[8] = imagecolorresolve($img, 16, 51, 54);
        $colors[9] = imagecolorresolve($img, 34, 54, 27);
        $colors[10] = imagecolorresolve($img, 71, 33, 16);

        $x = 2;
        $y = 20;
        $fake_img = imagecreate($width, $height);

        //画干扰线
        for ($i = 0; $i < mb_strlen($args['code'], 'utf-8'); $i++) {
            imagesetthickness($img, 3 * $i);
            $line_color = imagecolorallocate($img, rand(150, 255), rand(150, 255), rand(150, 255));
            imageline($img, rand() % $width, rand() % $height, rand() % $width, rand() % $height, $line_color);
        }

        //添加验证码文字
        for ($i = 0; $i < mb_strlen($args['code'], 'utf-8'); $i++) {
            $angle = $this->getRand(-1500, 1500) * M_PI / 180;
            $nFont = $this->getRand(0, count($font) - 1);
            $nColor = $this->getRand(1, count($colors) - 1); //不使用白色

            $last_pos = imagettftext($fake_img, $size, $angle, 0, 0, 0,
                PHPLIB . self::CAPTCHA_FONT_ROOT . $font[$nFont], mb_substr($args['code'], $i, 1, 'utf-8'));
            if ($last_pos[0] > $last_pos[6]) {
                $left_lean = true;
            } else {
                $left_lean = false;
            }

            $drift_x = $left_lean ? $last_pos[0] - $last_pos[6] : 0;
            $last_pos = imagettftext($img, $size, $angle,
                $x + $drift_x, $y, $colors[$nColor],
                PHPLIB . self::CAPTCHA_FONT_ROOT . $font[$nFont], mb_substr($args['code'], $i, 1, 'utf-8'));
            $x += $left_lean ? $last_pos[2] - $last_pos[6] : $last_pos[4] - $last_pos[0] + 1;
        }

        //画干扰点
        for ($i = 0; $i < intval($width * $height / 70); $i++) {
            imagesetpixel($img, rand() % $width, rand() % $height, $colors[1]);
        }

        ob_clean();

        header("Cache-Control: no-cache");
        header("Content-type: image/png;charset=utf-8");
        imagepng($img);
        imagedestroy($img);

        return true;
    }

    protected function getRand($min, $max) {
        $n = (double)rand();
        $n = $min + ((double)($max - $min + 1.0) * ($n / (getRandmax() + 1.0)));
        return (int)$n;
    }
}