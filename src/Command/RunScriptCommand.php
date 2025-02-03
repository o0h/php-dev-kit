<?php

declare(strict_types=1);

namespace O0h\DevKit\Command;

use Composer\Command\BaseCommand;
use Composer\Command\ExecCommand;
use Composer\Composer;
use Composer\Config;
use Composer\Console\Input\InputArgument;
use Composer\Console\Input\InputOption;
use Composer\Factory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

class RunScriptCommand extends BaseCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('dev-kit')
            ->setDefinition([
                new InputArgument(
                    'binary',
                    InputArgument::REQUIRED,
                ),
                new InputArgument(
                    'args',
                    InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                ),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $p = \Composer\InstalledVersions::getInstallPath('o0h/php-dev-kit');
        $localComposer = Factory::create($this->getIO(), $p . '/composer.json');
        $scripts = $localComposer->getPackage()->getScripts();
        $input->getArgument('args');
        
        $cmd = 'dev-kit:' . $input->getArgument('binary');
        if ($cmd === 'dev-kit:help') {
            foreach ($scripts as $script => $_) {
                if (str_starts_with($script, 'dev-kit') === false) {
                    continue;
                }
                
                $output->writeln($script . "\t" . implode( ' && ' , $_));
            }
            return 0;
        } elseif (array_key_exists($cmd, $scripts)) {
            [$binary, $args] = explode(' ', $scripts[$cmd][0], 2);
            return $this
                ->getApplication()
                ->doRun(
                    new ArrayInput([
                        'command' => 'exec',
                        'binary' => $binary,
                        'args' => explode(' ', $args),
                    ]),
                    $output,
                );
        }
        
        $this->getIO()->error('invalid');
        return 1;
    }

}
