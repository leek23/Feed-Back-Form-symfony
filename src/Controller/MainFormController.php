<?php

namespace App\Controller;

use App\Manager\FormManager;
use App\Form\MessageForm;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MainFormController extends AbstractController
{
    private $formManager;
    private $session;

    /**
     * MainFormController constructor.
     * @param FormManager $formManager
     * @param SessionInterface $session
     */
    public function __construct(FormManager $formManager, SessionInterface $session)
    {
        $this->formManager = $formManager;
        $this->session = $session;
    }

    public function index(Request $request)
    {
        $n1       = rand(0 , 30);
        $n2       = rand(0 , 30);
        $answer = $n1 + $n2;

        $form = $this->createForm(MessageForm::class, $this->formManager->manageRequest($request), [
            'attr' =>
                [
                    'n1' => $n1,
                    'n2' => $n2
                ]
        ]);
        $form->handleRequest($request);

        $answerSession = $request->get('message_form')['captcha'];
        if ($answerSession != $this->session->get('answer') && $form->isSubmitted()) {
            $form->addError(new FormError('Wrong captcha'));
        }

        if (!$form->isSubmitted()) {
            $this->session->set('answer', $answer);
        }

        if (!$errorTime = $this->formManager->validateTime()) {
            $form->addError(new FormError('Limit exceeded, try in a minute'));
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $this->session->set('answer', $answer);
            $this->formManager->submit();
        }

        return new Response( $this->renderView('pages/form.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}