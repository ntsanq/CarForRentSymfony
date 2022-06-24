<?php

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Aws\Sqs;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(name: 'app:send_message')]
class SendMessageCommand extends Command
{
    private SqsClient $sqs;
    private ContainerBagInterface $containerBag;

    public function __construct(ContainerBagInterface $containerBag, SqsClient $sqs, string $name = null)
    {
        parent::__construct($name);
        $this->sqs = $sqs;
        $this->containerBag = $containerBag;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = [
            'DelaySeconds' => 10,
            'MessageBody' => "Hello baby!5",
            'QueueUrl' => $this->containerBag->get('sqsUrl')
        ];

        try {
            $this->sqs->sendMessage($params);
        } catch (AwsException $e) {
            error_log($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
