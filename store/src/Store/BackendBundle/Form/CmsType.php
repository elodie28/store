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
class CmsType extends AbstractType {


    /**
     * Méthode qui va construire mon formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', null, array(
            'label'    => 'cms.form.title',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mettre un titre',
                'pattern'     => '[a-zA-Z0-9- ]{5,}'
            )
        ));

        $builder->add('summary', null, array(
            'label'    => 'cms.form.summary',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Résumé de ma page CMS',
                'pattern'     => '[a-zA-Z0-9- ]{10,}'
            )
        ));

        $builder->add('description', null, array(
            'label'    => 'cms.form.description',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Description longue de ma page CMS',
                'pattern'     => '[a-zA-Z0-9- ]{15,}'
            )
        ));

        $builder->add('image', 'text', array(
            'label'    => 'cms.form.image',
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'http://',
            )
        ));

        $builder->add('file', 'file', array(
            'label'    => 'cms.form.image',
            'required' => false,
            'attr'     => array(
                'class' => 'form-control',
                'accept' => 'image/*',
                'capture' => 'capture'
            )
        ));

        $builder->add('dateActive', 'date', array(
            'label'    => 'cms.form.date',
            //'pattern' => '{{ day }}-{{ month }}-{{ year }}',
            'format' => 'dd/MM/yyyy',
            'widget' => 'single_text',
            'attr'  => array(
                'class' => 'date form-control'
            )
        ));

        $builder->add('video', null, array(
            'label'    => 'cms.form.video',
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => '<iframe src = "...">'
            )
        ));

        $builder->add('state', 'choice', array(
            'choices' => array('0' => 'cms.form.statelabel.unread', '1' => 'cms.form.statelabel.inwaiting', '2' => 'cms.form.statelabel.read'),
            'required' => true,
            'preferred_choices' => array('2'),
            'label' => 'cms.form.state',
            'attr'  => array(
                'class' => 'form-control',
            )
        ));

    }


    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Cms
     * car mon formulaire enregistre un CMS dans la table cms
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Product
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Cms',
        ));
    }


    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Cms',
            'translation_domain' => 'cms'
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_cms";
    }

}