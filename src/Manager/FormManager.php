<?php

namespace App\Manager;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use \Twig\Environment;

use Swift_Mailer;

class FormManager
{
    private $mailer;
    private $em;
    private $message;
    private $templating;

    /**
     * FormManager constructor.
     * @param $mailer
     * @param $em
     * @param $templating
     */
    public function __construct(EntityManagerInterface $em, Swift_Mailer $mailer, Environment $templating)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->message = new Message();
        $this->templating = $templating;
    }

    public function manageRequest(Request $request) {
        $this->message->setIp($request->getClientIp());
        $email = $request->get('message_form')['user']['email'];
        $user = $this->em->getRepository(User::class)->findOneBy(
            ['email' => $email]
        );

        if (!is_null($user)) {
            $this->message->setUser($user);
        }

        return $this->message;
    }

    public function submit() {
        $slug = random_int ( 100000000, 1000000000 );
        $this->message->getUser()->setSlug($slug);
        $this->em->persist($this->message);
        $this->em->flush();
        $this->send();
    }

    public function validateTime() {
        $id = '';
        if ($this->message->getUser())
            $id = $this->message->getUser()->getId();
        if (!$this->em->getRepository(Message::class)->findRangeMessages($this->message->getIp(), $id)) {
            return false;
        }

        return true;
    }

    public function send() {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('testform232234@gmail.com')
            ->setTo('info@awardwallet.com')
            ->setBody(
                $this->templating->render(
                    'emails/form.html.twig',
                    ['main'=>true, 'slug' => $this->message->getUser()->getSlug()]
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
        $message = $message
            ->setTo($this->message->getUser()->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/form.html.twig',
                    ['main'=>false]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);

    }
}