<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Class JewelerMetaType
 * @package Store\BackendBundle\Form
 */
class JewelerMetaType extends AbstractType {

    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('phone', null, array(
            'label'    => 'Mon Téléphone',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => '06-XX-XX-XX-XX',
                //'pattern'     => '[A-Z]{2}[0-9]{2,}'
            )
        ));

        $builder->add('website', null, array(
            'label'    => 'Mon Site Internet',
            'required' => false,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'http://',
            )
        ));

        $builder->add('city', null, array(
            'label'    => 'Ma Ville',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Paris',
                'pattern'     => '[a-zA-Z0-9- ]{3,}'
            )
        ));

        $builder->add('address', null, array(
            'label'    => 'Mon Adresse',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => '10 rue de la paix'
            )
        ));

        $builder->add('retour', null, array(
            'label'    => 'Retour de produit',
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mes conditions de retour de produit'
            )
        ));

        $builder->add('propos', null, array(
            'label'    => 'À propos du créateur',
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mon histoire'
            )
        ));

        $builder->add('delai', 'text', array(
            'label'    => "Mon Délai d'expédition",
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'En heure (h.)'
            )
        ));

        $builder->add('expedition', null, array(
            'label'    => "Mes expéditions de colis",
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Processus de transport de colis'
            )
        ));

        $builder->add('mention', null, array(
            'label'    => 'Mes Mentions Légales',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Siège social et statut'
            )
        ));

        $builder->add('optin', null, array(
            'label' => "J'accepte de recevoir la newsletter",
            'attr'  => array(
                'class' => 'checkbox'
            )
        ));
    }



    /**
     * Cette méthode me permet de lier mon formulaire à mon entité JewelerMeta
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Jeweler
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\JewelerMeta',
        ));
    }


    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\JewelerMeta',
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_jeweler_meta";
    }

}