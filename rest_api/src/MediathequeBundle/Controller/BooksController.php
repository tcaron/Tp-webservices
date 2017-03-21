<?php

namespace MediathequeBundle\Controller;

use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MediathequeBundle\Entity\Books;
use FOS\RestBundle\Controller\Annotations\Get as Get;
use FOS\RestBundle\Controller\Annotations\Post as Post;
use FOS\RestBundle\Controller\Annotations\Put as Put;
use FOS\RestBundle\Controller\Annotations as Rest;
use MediathequeBundle\Form\BooksType;

class BooksController extends Controller
{
    public function indexAction()
    {
        return $this->render('MediathequeBundle:Default:index.html.twig');
    }


    
    /**
     * Retourne tout les livres
     * @Get("/books")
     */
    public function getBooksAction(Request $request){

        $books = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MediathequeBundle:Books')
            ->findAll();

        if (empty($books)) {
            return new JsonResponse(['message' => 'No Books save in the database']);
        }
        $formatted = [];
        foreach ($books as $book) {
            $formatted[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'category' => $book->getCategory(),
            ];
        }

        return new JsonResponse($formatted);

    }

    /**
     * Retourne un seul livre, prends l'id de celui ci en paramètre
     * @Get("/books/{id}")
     */
    public function getBookAction(Request $request){

        $book = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MediathequeBundle:Books')
            ->find($request->get('id'));
        if (empty($book)) {
            return new JsonResponse(['message' => 'Book not found']);
        }
            $formatted[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'category' => $book->getCategory(),
            ];


        return new JsonResponse($formatted);

    }

    /**
     * Enregistre un nouveau livre
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Post("/books")
     */
    public function postBookAction(Request $request){

        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $book;
        } else {
            return $form;
        }

    }

    /**
     * Supprimer un livre en lui passant l'id de celui-ci en paramètre
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/books/{id}")
     */
    public function removeBookAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('MediathequeBundle:Books')
            ->find($request->get('id'));

        if($book) {
            $em->remove($book);
            $em->flush();
        }

    }

    /**
     * Met à jour un livre en lui passant l'id en paramètre
     * @Rest\View()
     * @Put("/books/{id}")
     */
    public function updateBookAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('MediathequeBundle:Books')
            ->find($request->get('id'));

        if (empty($book)) {
            return new JsonResponse(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(BooksType::class, $book);

        $form->submit($request->request->all());

        if($form->isValid()){

            $em->merge($book);
            $em->flush();
            return $book;
        }
        else { return $form; }

    }

}
