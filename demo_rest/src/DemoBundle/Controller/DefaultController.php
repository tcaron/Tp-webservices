<?php

namespace DemoBundle\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request as Rq ;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * Affiche la page index du site
     * @return twig template
     */
    public function indexAction()
    {
        return $this->render('DemoBundle:Default:index.html.twig');
    }

    /**
     * Affiche la page contact
     * @return twig template
     */
    public function contactAction()
    {
        return $this->render('DemoBundle:Default:contact.html.twig');
    }

    /**
     * Affiche tout les livres
     * @return twig template
     */
    public function livresAction(){
        $client   = $this->get('guzzle.client.api_crm');
        $response = $client->get('books');

        $result = \GuzzleHttp\json_decode($response->getBody()->getContents());

        return $this->render('DemoBundle:Default:livres.html.twig',array('livres'=>$result));
    }

    /**
     * Affiche tout les membres
     * @return twig template
     */
    public function membresAction(){
        $client   = $this->get('guzzle.client.api_crm');
        $response = $client->get('members');
        $result = \GuzzleHttp\json_decode($response->getBody()->getContents());


        return $this->render('DemoBundle:Default:membres.html.twig',array('membres'=>$result));
    }

    /**
     * Affiche pour un membre, la liste des livres qu'il a empruntés
     * @param  Rq
     * @return [type]
     */
    public function membreAction(Rq $request){

        $client   = $this->get('guzzle.client.api_crm');
        $response = $client->get('members/'.$request->get('id').'/books'); 
        $result = \GuzzleHttp\json_decode($response->getBody()->getContents());
        return $this->render('DemoBundle:Default:membre.html.twig',array('emprunts'=>$result));
    }
    
    /**
     * Affiche le détail d'un livre, prend l'id de l'url pour afficher le livre correspondant
     * Formulaire de mise à jour du livre, en changeant le nom et/ou le categorie du livre
     * Formulaire de suppression d'un livre
     * @param  Rq
     * @return twig template
     */
    public function livreAction(Rq $request){

        $client   = $this->get('guzzle.client.api_crm');
        $response = $client->get('books/'.$request->get('id').'');
        $result = \GuzzleHttp\json_decode($response->getBody()->getContents());
        $data=[];
        $form_del = $this->createFormBuilder($data)
            ->setMethod("DELETE")
            ->add('id', HiddenType::class,array('data'=>$request->get('id') ))
            ->add('supprimer', SubmitType::class)
            ->getForm();

        $form_maj = $this->createFormBuilder($data)
            ->setMethod("PUT")
            ->add('name',TextType::class)
            ->add('category',TextType::class)
            ->add('Mettre a jour', SubmitType::class)
            ->getForm();

        if($request->isMethod('DELETE')) {
            $form_del->handleRequest($request);
            $client->request('DELETE', 'books/' . $request->get('id') . '');
            return $this->redirectToRoute('demo_livres');
        }

        if($request->isMethod('PUT')){

            $form_maj->handleRequest($request);
            $client->request('PUT','books/' . $request->get('id') . '',[
                'json'=>['name'=>  $form_maj['name']->getData(),
                    'category' =>  $form_maj['category']->getData()]
            ]);
            return $this->redirectToRoute('demo_livres');

        }

        return $this->render('DemoBundle:Default:livre.html.twig',array('livre'=>$result,'delete'=>$form_del->createView(),'maj'=>$form_maj->createView()));
    }

    /**
     * Formulaire pour la création d'un livre
     * @param  Rq
     * @return twig template
     */
    public function creerLivreAction(Rq $request){
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('name',TextType::class)
            ->add('category',TextType::class)
            ->add('valider',SubmitType::class)
        ->getForm();
        $client   = $this->get('guzzle.client.api_crm');
        if($request->isMethod("POST")){
          $form->handleRequest($request);
            $client->request('POST','books',[
            'json'=>['name'=> $form['name']->getData(),
                      'category' => $form['category']->getData()]
            ]);
            return $this->redirectToRoute('demo_livres');

        }
        return $this->render('DemoBundle:Default:creation_livre.html.twig',array('form'=>$form->createView()));
    }
}
