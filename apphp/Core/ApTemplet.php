<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2017/11/28
 * Time: 22:21
 */

namespace apphp\Core;


class ApTemplet
{

    // 模板路径
    protected $template_dir = ROOT_PATH.'resource/view/';
    protected $cache_dir = ROOT_PATH.'runtime/cache/';
    protected $compilers = [
        "JsEchos",
        "Echos",
        "EscapedEchos",
        "Statements",
    ];


    /**
     * @access protected 解析模板点字符串为路径
     * @param string $view 模板名称
     * @return string 路径
     * */
    protected function analyzeDot($view)
    {
        $view_name = str_replace('.','/',$view); // 把.替换成/

        return $view_name;
    }


    /**
     * @access protected 查看文件是否给修改过，若未修改过则使用缓存
     * @param string $path 文件路径
     * @return string 返回图片缓存的路径
     * */
    protected function isExpired($path)
    {
        $templet_file = $this->getCompiledPath($path);
        if(!file_exists($templet_file))
        {
            return true;
        }

        return filemtime($templet_file) >= filemtime($templet_file);
    }

    /**
     * @access protected 生成缓存文件
     * @param string $path 文件名
     * @return bool 是否生成成功
     * */
    protected function getCompiledPath($path)
    {
        return $this->cache_dir.md5($path);
    }

    /**
     * @access protected 模板引擎显示
     * @param string $view 模板名称
     * @param array $params 参数
     * @return bool
     * */
    public function show($view, $params = [])
    {
        $file = $this->template_dir . $this->analyzeDot($view) . '.html';

        // 将参数引入
        if(is_array($params))
        {
            extract($params);
        }

        // 判断是否有改动 无改动则使用缓存
        if(!$this->isExpired($file))
        {
            $cache_file = $this->getCompiledPath($file);

            require_once $cache_file;

            return true;
        }

        $file_content = file_get_contents($file);
        $result = '';
        foreach(token_get_all($file_content) as $token)
        {
            if(is_array($token))
            {
                list($id, $content) = $token;
                if($id == T_INLINE_HTML)
                {
                    foreach ($this->compilers as $type)
                    {
                        $content = $this->{"compile{$type}"}($content);
                    }
                }
                $result .= $content ;
            }
            else
            {
                $result .= $token;
            }
        }
        $cache_file = $this->getCompiledPath($file);
        file_put_contents($cache_file,$result);
        require $cache_file;

        return true;
    }

    /**
     * @access protected 用于处理Js的模板引擎
     * @param string $content 内容
     * @return bool
     * */
    protected function compileJsEchos($content)
    {
        return preg_replace('/@{{ (\S+) }}/' , '<?php echo chr(123) . chr(123) . " " . "$1"  . " " . chr(125) . chr(125); ?>', $content);
    }

    /**
     * @access protected 不处理内容进行输出
     * @param string $content 内容
     * @return bool
     * */
    protected function compileEchos($content)
    {
        return preg_replace('/{!! (\S+) !!}/' , '<?php echo $1 ?>', $content);
    }

    /**
     * @access protected 处理掉特殊字符串
     * @param string $content 内容
     * @return bool
     * */
    protected function compileEscapedEchos($content)
    {
        return preg_replace('/{{ (\S+) }}/' , '<?php echo htmlentities($1) ?>',$content);
    }

    /**
     * @access protected 正则表达式匹配@xx()中的语句
     * @param string $content 需要匹配的内容
     * @return bool
     * */
    protected function compileStatements($content)
    {
        return preg_replace_callback(
            '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', function ($match){
            return $this->compileStatement($match);
        }, $content
        );
    }

    /**
     *  查找符合的条件语句
     *
     *
     * @match 由compileStatements方法传来的
     * */
    protected function compileStatement($match)
    {
        if (strpos($match[1], '@') !== false)
        {
            $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
        }
        elseif (method_exists($this, $method = 'compile'.ucfirst($match[1])))
        {
            $match[0] = $this->$method(isset($match[3]) ? $match[3] : null);
        }

        return isset($match[3]) ? $match[0] : $match[0].$match[2];
    }

    /**
     * @access protected 模板引擎include中的应用
     * @param string $expression 文件名
     * @return bool
     * */
    protected function compileInclude($expression)
    {
        $file = "('" . ROOT_PATH . 'resource/view/' . substr($expression, 2);

        return "<?php include{$file} ?>";
    }

    /**
     * @access protected 模板引擎if中的应用
     * @param string $expression 条件
     * @return bool
     * */
    protected function compileIf($expression)
    {
        return "<?php if{$expression}: ?>";
    }

    /**
     * @access protected 模板引擎elseif中的应用
     * @param string $expression 条件
     * @return bool
     * */
    protected function compileElseif($expression)
    {
        return "<?php elseif{$expression}: ?>";
    }

    /**
     * @access protected 模板引擎else中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileElse($expression)
    {
        return "<?php else: ?>";
    }

    /**
     * @access protected 模板引擎endif中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileEndif($expression)
    {
        return "<?php endif; ?>";
    }

    /**
     * @access protected 模板引擎for中的应用
     * @param string $expression for的循环语句
     * @return bool
     * */
    protected function compileFor($expression)
    {
        return "<?php for{$expression}: ?>";
    }

    /**
     * @access protected 模板引擎endfor中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileEndfor($expression)
    {
        return "<?php endfor; ?>";
    }

    /**
     * @access protected 模板引擎foreach中的应用
     * @param string $expression foreach的循环语句
     * @return bool
     * */
    protected function compileForeach($expression)
    {
        return "<?php foreach${expression}: ?>";
    }

    /**
     * @access protected 模板引擎endforeach中的应用
     * @param string $expression 无
     * @return bool
     * */
    protected function compileEndforeach($expression)
    {
        return "<?php endforeach; ?>";
    }

    /**
     * @access protected 模板引擎while中的应用
     * @param string $expression while的循环语句
     * @return bool
     * */
    protected function compileWhile($expression)
    {
        return "<?php while{$expression}: ?>";
    }

    /**
     * @access protected 模板引擎endwhile中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileEndwhile($expression)
    {
        return "<?php endwhile; ?>";
    }

    /**
     * @access protected 模板引擎continue中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileContinue($expression)
    {
        return "<?php continue; ?>";
    }

    /**
     * @access protected 模板引擎break中的应用
     * @param string $expression 空
     * @return bool
     * */
    protected function compileBreak($expression)
    {
        return "<?php break; ?>";
    }
}