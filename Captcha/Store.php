<?php
namespace Base\Captcha;

use Base\Cache\Cache;

/**
 * Class Store
 *
 * @package S\Captcha
 * @description 验证码存储服务
 */
class Store {
	protected static $prefix = 'captcha_';

	public static function get($id) {
		return Cache::get(self::key($id));
	}

	public static function set($id, $code, $ttl) {
		return Cache::set(self::key($id), $code, $ttl);
	}

	public static function clear($id) {
		return Cache::del(self::key($id));
	}

	private static function key($id) {
		return self::$prefix.$id;
	}
}