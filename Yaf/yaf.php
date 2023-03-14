<?php
/**
 * Yaf自动补全类(基于最新的3.3.3版本)
 * @author shixinke(http://www.shixinke.com)
 * @modified 2021/12/01
 */

/**
 *
 */
/**
 * php.ini配置选项:
 *
 * yaf.library=
 *
 * yaf.environ=product
 *
 * yaf.forward_limit=5
 *
 * yaf.use_namespace=0
 *
 * yaf.action_prefer=0
 *
 * yaf.lowcase_path=0
 *
 * yaf.use_spl_autoload=0
 *
 * yaf.name_suffix=1
 *
 * yaf.name_separator=
 */
/**
 */
const YAF_VERSION = '3.3.3';
/**
 */
const YAF_ENVIRON = 'product';
/**
 */
const YAF_ERR_STARTUP_FAILED = 512;
/**
 */
const YAF_ERR_ROUTE_FAILED = 513;
/**
 */
const YAF_ERR_DISPATCH_FAILED = 514;
/**
 */
const YAF_ERR_AUTOLOAD_FAILED = 520;
/**
 */
const YAF_ERR_NOTFOUND_MODULE = 515;
/**
 */
const YAF_ERR_NOTFOUND_CONTROLLER = 516;
/**
 */
const YAF_ERR_NOTFOUND_ACTION = 517;
/**
 */
const YAF_ERR_NOTFOUND_VIEW = 518;
/**
 */
const YAF_ERR_CALL_FAILED = 519;
/**
 */
const YAF_ERR_TYPE_ERROR = 521;
/**
 */
const YAF_ERR_ACCESS_ERROR = 522;
