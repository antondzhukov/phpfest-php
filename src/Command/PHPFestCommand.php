<?php
declare(strict_types=1);

namespace App\Command;

use Grpc\ChannelCredentials;
use Phpfestproto\GetInfoRequest;
use Phpfestproto\GetInfoResponse;
use Phpfestproto\PHPFestInfoServiceClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PHPFestCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'phpfest';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new PHPFestInfoServiceClient(
            '127.0.0.1:8282',
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ]
        );

        $request = new GetInfoRequest();
        $request->setRequestId(md5(microtime()));

        /** @var $reply GetInfoResponse */
        [$reply, $status] = $client->GetInfo($request)->wait();
        if ($status->code !== 0) {
            $output->writeln('Error in GetInfo request. ' . $status->details);

            return Command::FAILURE;
        }

        $output->writeln('Title: ' . $reply->getTitle());

        return Command::SUCCESS;
    }
}
