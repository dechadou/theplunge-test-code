<?php

namespace App\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;

/**
 * Class DeployCommand
 * @package App\CoreBundle\Command
 */
class DeployCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('abre:deploy')
            ->setDescription('Deploy tool');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process("echo   ------  Run composer  ------ ");
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}