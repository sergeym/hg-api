<?php
namespace ApiBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mmoreram\RSQueueBundle\Command\ConsumerCommand;
use TelegramHandler\TelegramHandler;

/**
 * Queue worker start command
 */
class QueueWorkerCommand extends ConsumerCommand
{

    /**
     * Configuration method
     */
    protected function configure()
    {
        $this
            ->setName('api:queue:worker')
            ->setDescription('Start queue for long pooling process');
        ;

        parent::configure();
    }

    /**
     * Relates queue name with appropiated method
     */
    public function define()
    {
        $this->addQueue('activity', 'consumeActivity');
    }

    /**
     * If many queues are defined, as Redis respects order of queues, you can shuffle them
     * just overwritting method shuffleQueues() and returning true
     *
     * @return boolean Shuffle before passing to Gearman
     */
    public function shuffleQueues()
    {
        return true;
    }

    /**
     * Consume method with retrieved queue value
     *
     * @param InputInterface  $input   An InputInterface instance
     * @param OutputInterface $output  An OutputInterface instance
     * @param Mixed           $payload Data retrieved and unserialized from queue
     */
    protected function consumeActivity(InputInterface $input, OutputInterface $output, $payload)
    {
        $this->info('Activity arrived', $payload);
        $output->writeln(print_r($payload, true));
    }

    protected function info(string $message, array $data = null)
    {
        $logger = $this
            ->getContainer()
            ->get('monolog.logger.notification');

        $logger->info($message, $data);
    }
}