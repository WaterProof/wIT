<?php

// src/Acme/DemoBundle/Command/GreetCommand.php
namespace WaterProof\IssueBundle\Command;

use eXorus\PhpMimeMailParser\Attachment;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WaterProof\IssueBundle\Entity\Issue;
use WaterProof\UserBundle\Entity\User;

class ImportEmailsCommand extends ContainerAwareCommand
{
    protected $EntityManager;

    protected function configure()
    {
        $this
            ->setName('wit:import-emails')
            ->addArgument('filename');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->EntityManager = $this->getContainer()->get('doctrine')->getManager();
        $this->createIssueFromEmail($input->getArgument('filename'), $output);
    }

    protected function createIssueFromEmail($filename, $output)
    {
        $Parser = new \eXorus\PhpMimeMailParser\Parser();

        $Parser->setPath($filename);
        //$Parser->setStream(fopen($path, "r"));
        //$Parser->setText(file_get_contents($path));
        $subject = $Parser->getHeader('subject');

        if ($issueId = $this->extractIssueId($subject)) {
            $issue = $this->EntityManager->getRepository('WaterProofIssueBundle:Issue')->find($issueId);

            $output->writeln(sprintf('New comment on issue #%06d %s', $issue->getId(), $issue->getTitle()));
        } else {
            $issue = new Issue();

            $issue->setOwner($this->extractSender($Parser));
            $issue->setPriority($this->extractPriority($Parser));
            $issue->setTitle($subject);
            $issue->setContent($this->extractContent($Parser));

            /** @var Attachment $attachment */
//        $attachments = $Parser->getAttachments();
//        if (count($attachments)) {
//            $output->writeln('Fichiers attachés:');
//            foreach ($attachments as $index => $attachment) {
//                $output->writeln(sprintf('- %02d %s', $index + 1, $attachment->getFilename()));
//            }
//        }
            // $output->writeln('');
            $this->EntityManager->persist($issue);
            $this->EntityManager->flush();
            $output->writeln(sprintf('#%06d %s', $issue->getId(), $issue->getTitle()));
        }

    }

    private function createUser($email, $name)
    {
        $user = new User();
        $user->setEmail($email);
        $result = preg_split('/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/', $name, -1, PREG_SPLIT_NO_EMPTY);

        $user->setFirstName(array_shift($result));
        $user->setLastName(implode(' ', $result));
        $user->setUsername($email);
        $user->setPassword('azerty');
        $this->EntityManager->persist($user);
        return $user;
    }

    private function extractPriority($parser)
    {
        // Infos from http://stackoverflow.com/questions/15568583/php-mail-priority-types
        $priority = $parser->getHeader('X-Priority');
        if (!empty($priority)) {
            switch ($priority) {
                case 1: // high
                case 2:
                    return Issue::PRIORITY_HIGH;
                case 3: // normal
                    return Issue::PRIORITY_NORMAL;
                case 4:
                case 5: // low
                    return Issue::PRIORITY_LOW;
                default:
                    return Issue::PRIORITY_NORMAL;
            }
        }
        $priority = $parser->getHeader('X-MSMail-Priority');
        switch ($priority) {
            case 'High':
                return Issue::PRIORITY_HIGH;
            case 'Normal':
                return Issue::PRIORITY_NORMAL;
            case 'Low':
                return Issue::PRIORITY_LOW;
            default:
                return Issue::PRIORITY_NORMAL;
        }
    }

    private function extractContent($parser)
    {
        //        $htmlEmbedded = $Parser->getMessageBody('htmlEmbedded'); //HTML Body included data
        $content = $parser->getMessageBody('html');
        if (empty($content)) {
            $content = $parser->getMessageBody('text');
            $content = nl2br($content);
        }
        return $content;

    }

    private function extractSender($parser)
    {
        $header_from = $parser->getHeader('from');
        $address_array = imap_rfc822_parse_adrlist($header_from, "");
        if (!is_array($address_array) || count($address_array) < 1) {
            throw new \Exception(sprintf('Unable to parse sender infos from header: %s', $header_from));
        }
        $sender = array_shift($address_array);
        $sender_name = @$sender->personal;
        $sender_email = strtolower(sprintf('%s@%s', $sender->mailbox, $sender->host));

        $queryBuilder = $this->EntityManager->getRepository('WaterProofUserBundle:User')->createQueryBuilder('u');
        $queryBuilder->where('u.email = :email')->setParameter('email', $sender_email);
        try {
            $user = $queryBuilder->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $user = $this->createUser($sender_email, $sender_name);
        }
        return $user;
    }
}