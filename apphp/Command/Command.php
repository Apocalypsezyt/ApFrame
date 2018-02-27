<?php
/**
 * Created by PhpStorm.
 * User: APOCALYPSE
 * Date: 2/27/2018
 * Time: 11:51
 */

namespace apphp\Command;

use apphp\Core\Command as C;

class Command extends C
{
    public function fire($argv)
    {
        $command = $argv[0];
        switch ($command)
        {
            case 'make':
                        $name = $argv[1];
                        $this->make($name);
                        $this->line("Create Command Line Successfully!");
                        break;
            default:
        }
    }

    protected function make($name)
    {
        $content = <<<EOF
<?php

namespace apphp\Command;

use apphp\Core\Command;

class ${name} extends Command
{
    public function fire(\$argv)
    {
        
    }
}

EOF;
        $filename = APPHP_PATH . "/Command/${name}.php";
        if(!file_exists($filename))
        {
            $phpfile = fopen($filename,"w");
            fwrite($phpfile,$content);
        }

        return true;
    }
}