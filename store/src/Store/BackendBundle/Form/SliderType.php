<?php

namespace Store\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Store\BackendBundle\Repository\ProductRepository;


/**
 * Class SliderType
 * @package Store\BackendBundle\Form
 */
class SliderType extends AbstractType {


    /**
     * @var $user
     */
    protected $user;

    /**
     * On crée un constructeur pour récupérer des paramètres (ici l'utilisateur)
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

        $builder->add('caption', null, array(
            'label'    => 'Légende du slide',
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
                'placeholder' => 'Mettre une légende soignée',
                'pattern'     => '[a-zA-Z0-9- ]{4,}'
            )
        ));

        $builder->add('file', 'file', array(
            'label'    => 'Mon slide',
            'required' => true,
            'attr'     => array(
                'class' => 'form-control',
                'accept' => 'image/*',
                'capture' => 'capture'
            )
        ));

        $builder->add('product', 'entity', array(
            'label' => 'Produit associé au slide',
            'attr'  => array(
                'class' => 'form-control'
            ),
            // Méthode numéro 2 pour afficher les produits d'un jeweler spécifique dans le formulaire
            'class' => 'StoreBackendBundle:Product',
            'property' => 'title',
            'multiple' => false, // choix multiple
            // 'expanded' => true, // checkbox à choix multiple
            'query_builder' => function(ProductRepository $er) {
                    return $er->getProductByUserBuilder($this->user);
                }
        ));

        $builder->add('position', null, array(
            'label'    => "Ordre d'affichage de votre slide",
            'required' => true,
            'attr'     => array(
                'class'       => 'form-control',
            )
        ));

        $builder->add('active', null, array(
            'label' => 'Slide activé ?',
            'attr'  => array(
                'class' => 'checkbox'
            )
        ));

    }



    /**
     * Cette méthode me permet de lier mon formulaire à mon entité Slider
     * car mon formulaire enregistre un slider dans la table slider
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {

        // Je lie le formulaire à l'entité Slider
        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Slider',
        ));
    }


    /**
     * Méthode dépréciée pour lier un formulaire à une entité
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'Store\BackendBundle\Entity\Slider',
        ));
    }


    /**
     * Nom du formulaire
     * @return string
     */
    public function getName() {

        return "store_backend_slider";
    }

}