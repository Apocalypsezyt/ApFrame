<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/9/10
 * Time: 11:30
 */

namespace apphp\Core\Error;


class Error
{
    public static function showError($errNo, $errMsg, $errFile, $errLine)
    {
        // 加载自定义错误页面
        require_once APPHP_PATH."/error/page/error.php";
    }

    /*
     * 错误类初始化
     * */
    public static function Init()
    {
        if(APPHP_ERROR_MODE == 'frame'){
            // 启用本库自定义错误
            set_error_handler("\\apphp\\error\\error::showError");
        }
        elseif(APPHP_ERROR_MODE == 'whoops'){
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
    }

    /*
     *  主动调用错误
     *
     *  $errorNo 错误编号
     * */
    public static function ActiveError($errorNo, $msg = null)
    {
        $errorNo = $errorNo;
        $errorMsg = $msg ?? self::getError($errorNo);
        // 加载自定义主动调用错误的页面
        require_once APPHP_PATH."/error/page/ActiveError.php";
        exit();
    }

    /*
     *
     *  返回自定义的错误
     *
     * */
    public static function getError($no)
    {
        if(is_null(APPHP_ERROR[$no]))
            return "配置内暂无该编码";
        return APPHP_ERROR[$no];
    }

}