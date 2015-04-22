<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class JewelerSubscribeType extends AbstractType {


    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder->add('title', null, array(
            'label'    => 'Nom de votre bijouterie', // label de mon champ
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Nom / Marque du bijoutier',
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('username', null, array(
            'label'    => "Nom d'utilisateur", // label de mon champ
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => "Votre nom d'utilisateur",
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('email', 'email', array(
            'label'    => "E-mail de l'utilisateur", // label de mon champ
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => "Votre e-mail"
            )
        ));

        $builder->add('password', 'repeated', array(
            'required' => true,
            'attr' => array('autocomplete', 'off'),
            'type' => 'password',
            'first_name'  => 'mdp',
            'second_name' => 'mdp_conf',
            'invalid_message' => 'Les mots de passe doivent correspondre',

            'first_options'  => array(
                'label' => 'Mot de passe',
                'attr' => array('value' => '',
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'placeholder' => 'Au moins 6 caractères',
                                'pattern' => '.{6,}')),

            'second_options' => array(
                'label' => 'Confirmation du mot de passe',
                'attr' => array('value' => '',
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'placeholder' => 'Retaper votre mot de passe',
                                'pattern' => '.{6,}'))
        ));


        $builder->add('envoyer', 'submit', array(
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

    }



    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Jeweler
     * car mon formulaire enregistre un nouvel utilisateur jeweler dans la table jeweler
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

        return "store_backend_jeweler_subscribe";
    }
}