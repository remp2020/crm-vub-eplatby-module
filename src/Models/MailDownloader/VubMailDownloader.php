<?php

namespace Crm\VubEplatbyModule\MailConfirmation;

use Crm\ApplicationModule\Config\ApplicationConfig;
use Crm\VubEplatbyModule\MailParser\VubMailParser;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;
use Tomaj\ImapMailDownloader\Downloader;
use Tomaj\ImapMailDownloader\Email;
use Tomaj\ImapMailDownloader\MailCriteria;
use Tracy\Debugger;
use Tracy\ILogger;

class VubMailDownloader
{
    private $tempDir;

    private $imapHost;

    private $imapPort;

    private $username;

    private $password;

    private $processedFolder;

    private $zipPassword;

    public function __construct($tempDir, ApplicationConfig $config)
    {
        $this->tempDir = $tempDir;

        $this->imapHost = $config->get('vub_confirmation_host');
        $this->imapPort = $config->get('vub_confirmation_port');
        $this->username = $config->get('vub_confirmation_username');
        $this->password = $config->get('vub_confirmation_password');
        $this->processedFolder = $config->get('vub_confirmation_processed_folder');
        $this->zipPassword = $config->get('vub_zip_password');
    }

    public function download($callback)
    {
        $downloader = new Downloader(
            $this->imapHost,
            $this->imapPort,
            $this->username,
            $this->password,
            $this->processedFolder
        );

        $criteria = new MailCriteria();
        $criteria->setFrom('nonstopbanking@vub.sk');
        $criteria->setUnseen(true);
        $downloader->fetch($criteria, function (Email $email) use ($callback) {
            $parser = new VubMailParser();

            $body = $this->getAttachmentBody($email);
            $mailContent = $parser->parse(quoted_printable_decode($body));
            if (!empty($mailContent) && $body) {
                return $callback($mailContent);
            }

            return false;
        });
    }

    public function validateAttachment(Email $email)
    {
        $attachments = $email->getAttachments();
        if (empty($attachments)) {
            $vs = '';
            $result = [];
            $body = $email->getBody();
            $vsPattern = '/č.ob. (\d{10})/m';

            $res = preg_match($vsPattern, $body, $result);
            if ($res) {
                $vs = $result[1];
            }

            Debugger::log(
                'missing VUB mail attachment for payment with variable symbol: ' . $vs,
                ILogger::WARNING
            );
            return false;
        }
        return true;
    }

    public function getAttachmentBody(Email $email)
    {
        if (!$this->validateAttachment($email)) {
            return false;
        }

        $filePath = $this->tempDir . DIRECTORY_SEPARATOR . 'payments-mail-parser/' . Random::generate() . '.zip';

        FileSystem::write($filePath, array_values($email->getAttachments())[0]['attachment']);

        $zip = new \ZipArchive();
        $zip->open($filePath);
        $zip->setPassword($this->zipPassword);

        $contents = $zip->getFromIndex(0);
        if (!$contents) {
            throw new \Exception('error opening VUB confirmation e-mail attachment: probably bad password in CRM configuration)');
        }

        $contents = iconv("UTF-8", "ISO-8859-1//IGNORE", $contents);

        FileSystem::delete($filePath);

        return $contents;
    }
}
