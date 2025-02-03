<?php

declare(strict_types=1);

namespace O0h\DevKit;

use Composer\Plugin\Capability\CommandProvider as CommandCapability;
use O0h\DevKit\Command\RunScriptCommand;

class CommandProvider implements CommandCapability
{

    public function getCommands()
    {
        return [
            new RunScriptCommand()
        ];
    }
}
