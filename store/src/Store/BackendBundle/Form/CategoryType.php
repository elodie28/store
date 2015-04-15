<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CategoryType
 * @package Store\BackendBundle\Form
 */
class CategoryType extends AbstractType {


    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', null, array(
            'label'    => 'Titre de ma catégorie',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mettre un titre de catégorie',
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('description', null, array(
            'label'    => 'Description de ma catégorie',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Description longue de la catégorie',
                'pattern'     => '[a-zA-Z0-9- ]{10,}'
            )
        ));

        $builder->add('position', null, array(
            'label'    => "Ordre d'affichage",
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
            )
        ));

        $builder->add('envoyer', 'submit', array(
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));
    }


    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Category
     * car mon formulaire enregistre une catégorie dans la table category
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Product
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Category',
        ));
    }


    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Category',
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_category";
    }

}