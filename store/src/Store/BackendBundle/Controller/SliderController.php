<?php

// L'endroit où je déclare ma class SliderController
namespace Store\BackendBundle\Controller;


// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Store\BackendBundle\Entity\Slider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\SliderType;
use Symfony\Component\HttpFoundation\Request;



/**
 * Class SliderController
 * @package Store\BackendBundle\Controller
 */
class SliderController extends Controller {


    /**
     * Page liste des slides
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère l'utilisateur jeweler courant connecté (à la place du 1 dans getSliderByUser(1))
        $user = $this->getUser();

        // Je récupère tous les slides du jeweler connecté
        $slides = $em->getRepository('StoreBackendBundle:Slider')->getSliderByUser($user); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier Slider de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Slider:list.html.twig', array(
            'slides' => $slides
        ));
    }



    /**
     * Page view d'un seul slide
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 slide de ma BDD avec la méthode find()
        $slider = $em->getRepository('StoreBackendBundle:Slider')->find($id); // NomduBundle:Nomdel'entité

        // Je retourne la vue View contenue dans le dossier Slider de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:Slider:view.html.twig',
            array( // = tableau associatif = le transporteur
                'slider' => $slider, // Le nom de ma clef sera le nom de ma variable en vue
            )
        );
    }



    /**
     * Page Création d'un slide
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {

        // Je crée un nouvel objet entité Slider
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $slider = new Slider();

        // récupérer l'utilisateur courant connecté (à la place du 1 dans find(1))
        $user = $this->getUser();

//        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find($user); // Je récupère le jeweler connecté

        // Je crée un formulaire
        $form = $this->createForm(new SliderType($user), $slider, array(
            'validation_groups' => 'new',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour enlever la validation HTML5)
                'action' => $this->generateUrl('store_backend_slider_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier SliderType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Ajouter un nouveau slide',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request); // le formulaire lis la requête

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $slider->upload();

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($slider); // J'enregistre mon objet product dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table product

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre slide a bien été créé'
            );

            return $this->redirectToRoute('store_backend_slider_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Slider:new.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Page Édition d'un slide
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $slider = $em->getRepository('StoreBackendBundle:Slider')->find($id);

        // récupérer l'utilisateur courant connecté (à la place du 1 dans SliderType(1))
        $user = $this->getUser();

        // Je crée un formulaire
        $form = $this->createForm(new SliderType($user), $slider, array(
            'validation_groups' => 'edit',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate',
                'action' => $this->generateUrl('store_backend_slider_edit', array(
                        'id' => $id
                    ))
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier SliderType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Éditer le slide',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        $form->handleRequest($request);

        if($form->isValid()) {

            $slider->upload();

            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre slide a bien été mis à jour'
            );

            return $this->redirectToRoute('store_backend_slider_list');
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Slider:edit.html.twig', array(
            'form' => $form->createView(),
            'slider' => $slider
        ));
    }

}