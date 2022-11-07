<?php

// src/AppBundle/Form/Type/AddRestaurantType.php

namespace TTBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class AddRestaurantType extends AbstractType {

    private $cuisineData;

    public function __construct($rtData) {
        //print_r($rtData);
        $this->cuisineData = $rtData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('cuisine', 'choice', array(
                    'label' => "Type of cuisines",
                    'choices' => $this->cuisineData['cuisines'],
                    'placeholder' => 'Select Cuisine',
                    'attr' => array(
                        'class' => 'AddRTSelect Cuisine',
                        'multiple' => '',
                        'required' => true,
                        'empty_data' => null
                    )
                ))
                ->add('names_rest', 'text', array(
                    'label' => 'Name',
                    'attr' => array(
                        'class' => 'AddRTText',
                        'required' => true,
                    )
                ))
                ->add('price_rest', 'text', array(
                    'label' => 'Average price (in Dollars)',
                    'required' => false,
                    'attr' => array(
                        'class' => 'AddRTText'
                    )
                ))
                ->add('email_rest', 'email', array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'AddRTText',
                        'required' => true,
                    )
                ))
                ->add('url_rest', 'text', array(
                    'label' => 'Website',
                    'required' => false,
                    'attr' => array(
                        'class' => 'AddRTText'
                    )
                ))
                ->add('phone_rest', 'text', array(
                    'label' => 'Phone',
                    'attr' => array(
                        'class' => 'AddRTText',
                        'required' => true,
                    )
        ));

        $builder
                ->add('country', 'choice', array(
                    'label' => "Country",
                    'choices' => $this->cuisineData['country'],
                    'placeholder' => 'Select Country..',
                    'attr' => array(
                        'class' => 'AddRTSelect CountryRST',
                        'required' => true,
                        'empty_data' => null
                    )
                ))
                ->add('city', 'text', array(
                    'label' => 'City',
                    'attr' => array(
                        'class' => 'AddRTText',
                        'required' => true
                    )
                ))
                ->add('address_rest', 'text', array(
                    'label' => 'Address',
                    'attr' => array(
                        'class' => 'AddRTText',
                        'required' => true
                    )
                ))
                ->add('latitude', 'hidden', array(
                    'required' => false
                ))
                ->add('longitude', 'hidden', array(
                    'required' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        /* $collectionConstraint = new Collection(array(
          'email' => array(
          new Email(array('message' => 'This is invalid email address.'))
          )
          )); */
    }

    public function getName() {
        return 'AddRestaurant';
    }

}
