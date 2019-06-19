<?php
namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MessagesController extends AbstractController
{
     public function index($slug) {

         $list = $this->getDoctrine()
             ->getRepository(Message::class)
             ->findByUserSlug($slug);


         return new Response( $this->renderView('pages/list.html.twig', [
             'list' => $list,
         ]));
     }
}