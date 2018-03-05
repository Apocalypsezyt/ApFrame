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
     *
     *  保护方法
     *
     *  解析掉点
     *
     * @view 模板名带.
     * */
    protected function analyzeDot($view)
    {
        $view_name = str_replace('.','/',$view); // 把.替换成/

        return $view_name;
    }


    /**
     *
     *  查看文件是否给修改过，若未修改过则使用缓存
     *
     *  @path 文件路径
     *
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
     *
     *  生成缓存文件
     *
     *  @path 文件名
     *
     * */
    protected function getCompiledPath($path)
    {
        return $this->cache_dir.md5($path);
    }

    /**
     *
     *  模板引擎显示
     *
     *  @view 模板名称 @params 参数
     *
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
     *
     *  用于处理Js的模板引擎
     *
     * @content 内容
     *
     * */
    protected function compileJsEchos($content)
    {
        return preg_replace('/@{{ (\S+) }}/' , '<?php echo chr(123) . chr(123) . " " . "$1"  . " " . chr(125) . chr(125); ?>', $content);
    }

    /**
     *
     *  不处理地输出
     *
     * @content 内容
     *
     * */
    protected function compileEchos($content)
    {
        return preg_replace('/{!! (\S+) !!}/' , '<?php echo $1 ?>', $content);
    }

    /**
     *
     *  进行处理地输出
     *
     * @content 内容
     *
     * */
    protected function compileEscapedEchos($content)
    {
        return preg_replace('/{{ (\S+) }}/' , '<?php echo htmlentities($1) ?>',$content);
    }

    /**
     *
     *  正则表达式匹配
     *
     *  找到关于@xx()的语句
     *
     * @content 需要匹配的内容
     *
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
     *  INCLUDE 语句
     *
     *
     * @expression 文件名
     * */
    protected function compileInclude($expression)
    {
        $file = "('" . ROOT_PATH . 'resource/view/' . substr($expression, 2);

        return "<?php include{$file} ?>";
    }

    /**
     *  IF 语句
     *
     *
     * @expression 条件
     * */
    protected function compileIf($expression)
    {
        return "<?php if{$expression}: ?>";
    }

    /**
     *  ELSE IF 语句
     *
     *
     * @expression 条件
     * */
    protected function compileElseif($expression)
    {
        return "<?php elseif{$expression}: ?>";
    }

    /**
     *  ELSE 语句
     *
     *
     * @expression 条件
     * */
    protected function compileElse($expression)
    {
        return "<?php else: ?>";
    }

    /**
     *  结束IF 语句
     *
     *
     * @expression 条件
     * */
    protected function compileEndif($expression)
    {
        return "<?php endif; ?>";
    }

    /**
     *  For 语句
     *
     *
     * @expression 条件
     * */
    protected function compileFor($expression)
    {
        return "<?php for{$expression}: ?>";
    }

    /**
     *  Endfor 语句
     *
     *
     * @expression 条件
     * */
    protected function compileEndfor($expression)
    {
        return "<?php endfor; ?>";
    }

    /**
     *  Foreach 语句
     *
     *
     * @expression 条件
     * */
    protected function compileForeach($expression)
    {
        return "<?php foreach${expression}: ?>";
    }

    /**
     *  EndForeach 语句
     *
     *
     * @expression 条件
     * */
    protected function compileEndforeach($expression)
    {
        return "<?php endforeach; ?>";
    }

    /**
     *  While 语句
     *
     *
     * @expression 条件
     * */
    protected function compileWhile($expression)
    {
        return "<?php while{$expression}: ?>";
    }

    /**
     *  EndWhile 语句
     *
     *
     * @expression 条件
     * */
    protected function compileEndwhile($expression)
    {
        return "<?php endwhile; ?>";
    }

    /**
     *  continue 语句
     *
     *
     * @expression 条件
     * */
    protected function compileContinue($expression)
    {
        return "<?php continue; ?>";
    }

    /**
     *  break 语句
     *
     *
     * @expression 条件
     * */
    protected function compileBreak($expression)
    {
        return "<?php break; ?>";
    }
}