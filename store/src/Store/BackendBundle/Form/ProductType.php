<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Store\BackendBundle\Repository\CategoryRepository;
use Store\BackendBundle\Repository\CmsRepository;

/**
 * Le suffixe Type est obligatoire pour les classes Formulaire
 * Class ProductType => Formulaire de création de produits
 * @package Store\BackendBundle\Form
 */
class ProductType extends AbstractType {



    /**
     * @var $user
     */
    protected $user;

    /**
     * User param
     * @param int $user
     */
    public function __construct($user = null) {
        $this->user = $user;
    }



    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        // Ajoute un champ dans mon formulaire
        // Le nom de mes champs sont les attributs de l'entité Product
        // Le 2ème argument à ma fonction add() est le type de mon champ
        // Le 3ème argument à ma fonction add() est l'option de mon champ

        $builder->add('title', null, array(
            'label'    => 'Titre de mon bijou', // label de mon champ
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mettre un titre soigné',
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('ref', null, array(
            'label'    => 'Référence du produit',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'AAXX',
                'pattern'     => '[A-Z]{2}[0-9]{2,}'
            )
        ));



        $builder->add('category', null, array(
            'label' => 'Catégorie(s) associée(s)',
            'attr'  => array(
                'class' => 'form-control'
            ),
            // Méthode numéro 1 pour afficher les catégories d'un jeweler spécifique dans le formulaire
            'class' => 'StoreBackendBundle:Category',
            'property' => 'title',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.jeweler = :user')
                        ->orderBy('c.title', 'ASC')
                        ->setParameter('user', $this->user);
                },
        ));


        $builder->add('category', 'entity', array(
            'label' => 'Catégorie(s) associée(s)',
            'attr'  => array(
                'class' => 'form-control'
            ),
            // Méthode numéro 2 pour afficher les catégories d'un jeweler spécifique dans le formulaire
            'class' => 'StoreBackendBundle:Category',
            'property' => 'title',
            'multiple' => true, // choix multiple
            // 'expanded' => true, // checkbox à choix multiple
            'query_builder' => function(CategoryRepository $er) {
                   return $er->getCategoryByUserBuilder($this->user);
                },
        ));



        $builder->add('summary', null, array(
            'label'    => 'Petit résumé',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Résumé de votre bijou'
            )
        ));

        $builder->add('description', null, array(
            'label'    => 'Description',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Description longue du bijou'
            )
        ));

        $builder->add('price', 'money', array(
            'label'    => 'Prix HT en €',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Prix en euros',
            )
        ));

        $builder->add('taxe', 'choice', array(
            'choices' => array('5' => '5 %', '19.6' => '19,6 %', '20' => '20 %'),
            'required' => true, // liste déroulante obligatoire
            'preferred_choices' => array('20'), // champ choisi par défaut
            'label' => 'Taxe appliquée',
            'attr'  => array(
                'class' => 'form-control',
            )
        ));

        $builder->add('quantity', 'number', array(
            'required' => true,
            'label' => 'Quantité du produit',
            'attr'  => array(
                'class' => 'form-control',
                'pattern' => '[0-9]{1,4}'
            )
        ));

        $builder->add('dateActive', 'date', array(
            'label' => 'Date active',
            'pattern' => '{{ day }}-{{ month }}-{{ year }}',
        ));

        $builder->add('active', null, array(
            'label' => 'Produit activé dans la boutique ?',
            'attr'  => array(
                'class' => 'checkbox'
            )
        ));

        $builder->add('cover', null, array(
            'label' => "Produit mis en page d'accueil dans la boutique ?",
            'attr'  => array(
                'class' => 'checkbox'
            )
        ));

        $builder->add('composition', null, array(
            'label'    => 'Composition de votre bijou',
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Composition du bijou'
            )
        ));

        $builder->add('cms', 'entity', array(
            'label' => 'Page(s) associée(s) au produit',
            'attr'  => array(
                'class' => 'form-control',
            ),
            // Méthode numéro 2 pour afficher les pages cms d'un jeweler spécifique dans le formulaire
            'class' => 'StoreBackendBundle:Cms',
            'property' => 'title',
            'multiple' => true, // choix multiple
            // 'expanded' => true, // checkbox à choix multiple
            'query_builder' => function(CmsRepository $er) {
                    return $er->getCmsByUserBuilder($this->user);
                },
        ));

        $builder->add('supplier', null, array(
            'label' => 'Fournisseur(s) associé(s) au produit',
            'attr'  => array(
                'class' => 'form-control',
            )
        ));

        $builder->add('tag', null, array(
            'label' => 'Tag(s) associé(s) au produit',
            'attr'  => array(
                'class' => 'form-control',
            )
        ));

        $builder->add('envoyer', 'submit', array(
            'attr'  => array(
            'class' => 'btn btn-primary btn-sm'
            )
        ));

    }


    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Product
     * car mon formulaire enregistre un produit dans la table product
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Product
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Product',
        ));
    }

    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Product',
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_product";
    }

}