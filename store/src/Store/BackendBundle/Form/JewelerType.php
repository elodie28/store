<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;




/**
 * Class JewelerType
 * @package Store\BackendBundle\Form
 */
class JewelerType extends AbstractType {


    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', null, array(
            'label'    => 'Nom de ma boutique',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mettre un titre soigné',
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('type', 'choice', array(
            'choices' => array('0' => 'Aucun', '1' => 'SA', '2' => 'SARL', '3' => 'SAS', '4' => 'EURL', '5' => 'Freelance'),
            'required' => true, // liste déroulante obligatoire
            'preferred_choices' => array('0'), // champ choisi par défaut
            'label' => 'Type de société',
            'attr'  => array(
                'class' => 'form-control',
            )
        ));

        $builder->add('email', 'email', array(
            'label'    => "Mon E-mail",
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => "E-mail pro/perso"
            )
        ));

        $builder->add('file', 'file', array(
            'label'    => 'Mon Logo / Mon Image',
            'required' => false,
            'attr'     => array(
                'class' => 'form-control',
                'accept' => 'image/*',
                'capture' => 'capture'
            )
        ));

        //$builder->add('meta', 'collection', array(
        //    'type' => new jewelerMetaType(),
        //    'label'    => 'Mon téléphone',
        //    'required' => true,
        //    'attr'     => array(
        //        'class'       => 'form-control',
        //        'placeholder' => '06-XX-XX-XX-XX',
                //'pattern'     => '[A-Z]{2}[0-9]{2,}'
         //   )
        //));



        $builder->add('description', null, array(
            'label'    => 'Description de ma boutique',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Une belle description soignée'
            )
        ));


    }



    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Jeweler
     * car mon formulaire enregistre un bijoutier dans la table jeweler
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Jeweler
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Jeweler',
        ));
    }


    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Jeweler',
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_jeweler";
    }


}