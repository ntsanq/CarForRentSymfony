<?php

namespace App\Command;

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

#[AsCommand(name: 'app:receive_message')]
class ReceiveMessageCommand extends Command
{
    private SqsClient $sqs;
    private ContainerBagInterface $containerBag;

    public function __construct(ContainerBagInterface $containerBag, SqsClient $sqs, string $name = null)
    {
        parent::__construct($name);
        $this->sqs = $sqs;
        $this->containerBag = $containerBag;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $queueUrl = $this->containerBag->get('sqsUrl');

        try {
            $result = $this->sqs->receiveMessage(
                [
                    'AttributeNames' => ['SentTimestamp'],
                    'MaxNumberOfMessages' => 1,
                    'MessageAttributeNames' => ['All'],
                    'QueueUrl' => $queueUrl,
                    'WaitTimeSeconds' => 0,
                ]
            );
            if (!empty($result->get('Messages'))) {
                $output->writeln($result->get('Messages')[0]['Body']);
                $result = $this->sqs->deleteMessage([
                    'QueueUrl' => $queueUrl,
                    'ReceiptHandle' => $result->get('Messages')[0]['ReceiptHandle']
                ]);
            } else {
                echo "No messages in queue. \n";
            }
        } catch (AwsException $e) {
            error_log($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
