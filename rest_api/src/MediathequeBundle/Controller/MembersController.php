<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 13/03/2017
 * Time: 13:13
 */

namespace MediathequeBundle\Controller;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MediathequeBundle\Entity\Members;
use FOS\RestBundle\Controller\Annotations\Get as Get;
use FOS\RestBundle\Controller\Annotations\Post as Post;
use FOS\RestBundle\Controller\Annotations\Put as Put;
use FOS\RestBundle\Controller\Annotations as Rest;
use MediathequeBundle\Form\MembersType;

class MembersController extends Controller
{
    /**
     * Retourne tous les membres de la médiathéque
     * @Get("/members")
     */
    public function getMembersAction(Request $request){

        $members = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MediathequeBundle:Members')
            ->findAll();

        if (empty($members)) {
            return new JsonResponse(['message' => 'No Members save in the database'], Response::HTTP_NOT_FOUND);
        }
        $formatted = [];
        foreach ($members as $member) {
            $formatted[] = [
                'id' => $member->getId(),
                'name' => $member->getName(),
            ];
        }

        return new JsonResponse($formatted);

    }

    /**
     * Retourne un membre en lui passant l'id de celui ci en paramètre
     * @Get("/members/{id}")
     */
    public function getMemberAction(Request $request){

        $member = $this->get('doctrine.orm.entity_manager')
            ->getRepository('MediathequeBundle:Members')
            ->find($request->get('id'));
        if (empty($member)) {
            return new JsonResponse(['message' => 'Member not found'], Response::HTTP_NOT_FOUND);
        }
        $formatted[] = [
            'id' => $member->getId(),
            'name' => $member->getName(),
        ];


        return new JsonResponse($formatted);

    }

    /**
     * Creer un nouveau membre
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Post("/members")
     */
    public function postMemberAction(Request $request){

        $member = new Members();
        $form = $this->createForm(MembersType::class, $member);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();
            return $member;
        } else {
            return $form;
        }

    }

    /**
     * Supprime un membre, en lui passant l'id de celui-ci en paramètre
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/members/{id}")
     */
    public function removeMemberAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('MediathequeBundle:Members')
            ->find($request->get('id'));

        if($member) {
            $em->remove($member);
            $em->flush();
        }

    }

    /**
     * Met à jour un membre en lui passant l'id de celui-ci en paramètre
     * @Rest\View()
     * @Put("/members/{id}")
     */
    public function updateMemberAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('MediathequeBundle:Members')
            ->find($request->get('id'));

        if (empty($member)) {
            return new JsonResponse(['message' => 'Member not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(MembersType::class, $member);

        $form->submit($request->request->all());

        if($form->isValid()){

            $em->merge($member);
            $em->flush();
            return $member;
        }
        else { return $form; }

    }

}