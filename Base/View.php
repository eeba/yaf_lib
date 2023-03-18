<?php

namespace Base;

use Smarty;
use SmartyException;


class View implements \Yaf_View_Interface
{
    protected ?Smarty $_smarty = null;

    public function __construct()
    {
        $smarty = new Smarty();
        $smarty->left_delimiter = '{{';
        $smarty->right_delimiter = '}}';

        $smarty->setTemplateDir($this->getScriptPath());
        $smarty->setCompileDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/compile');
        $smarty->setCacheDir(ROOT_PATH . DIRECTORY_SEPARATOR . 'data/cache');

        $this->_smarty = $smarty;
    }

    public function assign($name, $value = NULL)
    {
        $this->_smarty->assign($name, $value);
    }

    /**
     * @throws SmartyException
     */
    public function display($tpl, $tpl_vars = NULL)
    {
        $this->render($tpl, $tpl_vars);
    }

    /**
     * @throws SmartyException
     */
    public function render($tpl, $tpl_vars = NULL): bool|string
    {
        if (!str_contains($tpl, 'error')) {
            $uri = \Yaf_Dispatcher::getInstance()->getRequest()->getRequestUri();
            $tpl = $uri == '/' ? 'index.html' : $uri . '.html';
        } else {
            $tpl = 'error.html';
        }

        //移动模板
        if (defined('M') && M) {
            $tpl = '/m' . $tpl;
        }

        if ($tpl_vars) {
            foreach ($tpl_vars as $name => $value) {
                $this->assign($name, $value);
            }
        }
        return $this->_smarty->fetch(trim($tpl, '/'));
    }

    public function setScriptPath($template_dir)
    {

    }

    public function getScriptPath($request = NULL): string
    {
        return APP_PATH . DIRECTORY_SEPARATOR . 'views';
    }
}