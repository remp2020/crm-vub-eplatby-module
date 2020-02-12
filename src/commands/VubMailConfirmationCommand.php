<?php

namespace Crm\VubEplatbyModule\Commands;

use Crm\PaymentsModule\MailConfirmation\MailProcessor;
use Crm\VubEplatbyModule\MailConfirmation\VubMailDownloader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VubMailConfirmationCommand extends Command
{
    private $mailDownloader;

    private $mailProcessor;

    public function __construct(
        VubMailDownloader $mailDownloader,
        MailProcessor $mailProcessor
    ) {
        parent::__construct();
        $this->mailDownloader = $mailDownloader;
        $this->mailProcessor = $mailProcessor;
    }

    protected function configure()
    {
        $this->setName('vub:mail_confirmation')
            ->setDescription('Check notification emails and confirm payments based on VUB emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mailDownloader->download(function ($mailContent) use ($output) {
            return $this->markMailProcessed($mailContent, $output);
        });

        return 0;
    }

    private function markMailProcessed($mailContent, $output)
    {
        return !$this->mailProcessor->processMail($mailContent, $output);
    }
}
