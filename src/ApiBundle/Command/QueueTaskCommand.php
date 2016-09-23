<?php
namespace ApiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Add queue task command
 */
class QueueTaskCommand extends Command implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('api:queue:add')
            ->setDescription('Add queue task')
            ->addArgument('name', InputArgument::REQUIRED, 'Sets the task name', null)
            ->addArgument('payload', InputArgument::REQUIRED, 'Sets the task payload', null)
        ;

        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $json = $input->getArgument('payload');

        $arrayPayload = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $arrayPayload = $json;
        }

        $this
            ->container
            ->get("rs_queue.producer")
            ->produce(
                $input->getArgument('name'),
                $arrayPayload
            );
    }

    protected function isJson($string) {

    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}