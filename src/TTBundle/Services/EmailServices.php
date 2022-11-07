<?php

namespace TTBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use TTBundle\Entity\CmsEmails;
use Symfony\Bridge\Monolog\Logger;

class EmailServices
{
    private $templating;
    protected $em;
    protected $logger;
    protected $container;

    public function __construct(ContainerInterface $container, EntityManager $em,Logger $logger)
    {
        $this->templating = $container->get('templating');
        $this->em         = $em;
        $this->logger     = $logger;
        $this->container  = $container;

    }

    public function addEmailData($recipient, $msg = '', $subject = '', $title = '', $priority = 1)
    {
        $on_production_server = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
        if (empty($recipient)) {
            return false;
        } else {
            if ($recipient == 'user@touristtube.com') {
                return true;
            }
            $emails = new CmsEmails();
            $emails->setToEmail($recipient);
            $emails->setMsg($msg);
            $emails->setSubject($subject);
            $emails->setTitle($title);
            $emails->setPriority($priority);
            
            if (!$on_production_server && ( $recipient == 'ms@touristtube.com' || $recipient == 'accounting@touristtube.com' ) ) 
            {
                $emails->setSent(1);
                $emails->setNumTry(-1);
            }
            
            $emails->setCreateTs(new \DateTime("now"));
            $this->em->persist($emails);
            $this->em->flush();
            return true;
        }
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        //return $this->templating->renderResponse($view, $parameters, $response);
        return $this->templating->render($view, $parameters, $response);
    }

    public function send($emailObj)
    {
        $send = false;
        if (null != $emailObj->getFrom() && !empty($emailObj->getFrom()) && null != $emailObj->getTo() && !empty($emailObj->getTo()) && null != $emailObj->getMessage() && !empty($emailObj->getMessage())
        ) {
            $message = \Swift_Message::newInstance($emailObj->getSubject())
                ->setFrom($emailObj->getFrom())
                ->setTo($emailObj->getTo())
                ->setBody($emailObj->getMessage(), 'text/html');

            if (null != $emailObj->getAttachmentPath()) {
                $message->attach(\Swift_Attachment::fromPath($emailObj->getAttachmentPath()));
            }

            if (null != $emailObj->getData()) {
                $data     = $emailObj->getData();
                $type     = $emailObj->getDataType();
                $filename = $emailObj->getDataFileName();

                try {
                    $message->attach(\Swift_Attachment::newInstance($data, $filename, $type));
                } catch (\Swift_Message_MimeException $e) {
                    $logger->error("Swift_Message_MimeException: An attachment failed to attach: ".$e->getMessage());
                    return false;
                } catch (\Swift_FileException $e) {
                    $logger->error("Swift_FileException: An attachment failed to attach: ".$e->getMessage());
                    return false;
                }
            }

            $send = $this->container->get('mailer')->send($message);

            if ($send) return true;
            else return false;
        }
    }
}
