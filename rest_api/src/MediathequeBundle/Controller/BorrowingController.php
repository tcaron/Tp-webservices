<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 13/03/2017
 * Time: 14:04
 */

namespace MediathequeBundle\Controller;


use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MediathequeBundle\Entity\Borrowing;
use FOS\RestBundle\Controller\Annotations\Get as Get;
use FOS\RestBundle\Controller\Annotations\Post as Post;
use FOS\RestBundle\Controller\Annotations\Put as Put;
use FOS\RestBundle\Controller\Annotations as Rest;

class BorrowingController extends Controller
{
    /**
     * Retourne les livres empruntés d'un membre
     * Un seul livre peut être dans la table emprunt, un utilisateur peut emprunter plusieurs livres 
     * @Get("/members/{id}/books/")
     */
    public function getBorrowingAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $borrowing = $em->getRepository("MediathequeBundle:Borrowing")
        ->findBy(array('member'=>$request->get('id')));
        $member = $em->getRepository("MediathequeBundle:Members")->findOneBy(array('id'=>$request->get('id')));

        if (empty($borrowing)) {
            return new JsonResponse(['message' => 'No borrow for this member']);
        }

       $formated = [];
       foreach ($borrowing as $b){
        $formated[]=[
           'livre' => $b->getBook()->getName()
        ];
       }


        return new JsonResponse($formated);

    }

    /**
     * Retourne tous les livres qui sont empruntés
     * @Get("/borrowing")
     */
    public function getBorrowedBookAction(Request $request)
    {
       $em = $this->getDoctrine()->getManager();
       $borrowing = $em->getRepository("MediathequeBundle:Borrowing")->findAll();
         if (empty($borrowing)) {
            return new JsonResponse(['message' => 'No books are borrowed'], Response::HTTP_NOT_FOUND);
        }

         $formated = [];
       foreach ($borrowing as $b){
        $formated[]=[
           'livre' => $b->getBook()->getName()
        ];
       }

       return new JsonResponse($formated);
    }

}