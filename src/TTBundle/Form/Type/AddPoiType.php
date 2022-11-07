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

class AddPoiType extends AbstractType
{
	private $poiData;
	public function __construct($poiDt){
		$this->poiData = $poiDt;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->add('cat', 'choice', array(
                'label'       => "Select Categories",
				'choices' => $this->poiData['category'], 
				'placeholder' => 'Select',
				'attr' => array(
                    'class'       => 'AddPoiSelect Cuisine',
					'multiple'    => '',
					'required'    => true,
                    'empty_data'  => null			
                )
            ))
            ->add('names_poi', 'text', array(
                'label'       => 'Name',
                'attr' => array(
				    'class'       => 'features_input',
                    'required'    => true,
                )
            ))
			/*->add('op-hr', 'text', array(
                'label'       => 'Opening hour',
				'required'    => false,
                'attr' => array(
				    'class'       => 'features_input'
                )
            ))
			->add('cl-hr', 'text', array(
                'label'       => 'Closing hour',
				'required'    => false,
                'attr' => array(
				    'class'       => 'features_input'
                )
            ))*/
			->add('price_poi', 'text', array(
                'label'       => 'Average price (in Dollars)',
				'required'    => false,
                'attr' => array(
				    'class'       => 'features_input'
                )
            ))
			->add('email_poi', 'email', array(
                'label'       => 'Email',
                    'required'    => false,
                'attr' => array(
				    'class'       => 'features_input',
                )
            ))
			->add('url_poi', 'text', array(
                'label'       => 'Website',
				'required'    => false,
                'attr' => array(
				    'class'       => 'features_input'
                )
            ))
			->add('phone_poi', 'text', array(
                'label'       => 'Phone',
                    'required'    => false,
                'attr' => array(
				    'class'       => 'features_input',
                )
            ));
			
		$builder
            ->add('country', 'choice', array(
                'label'       => "Country",
				'choices' => $this->poiData['country'],
				'placeholder' => 'Select Country..',
                'attr' => array(
                    'class'       => 'AddPoiSelect CountryRST',
					'required'    => true,
                    'empty_data'  => null			
                )
            ))
           ->add('city', 'text', array(
                'label'       => 'City',
                'attr' => array(
				    'class'       => 'features_input',
					'required'    => true
                )
            ))
			->add('address_poi', 'text', array(
                'label'       => 'Address',
                'attr' => array(
				    'class'       => 'features_input',
                    'required'    => true
                )
            )) 
			->add('description_poi', 'textarea', array(
                'label'       => 'Description',
				'required'    => false,
                'attr' => array(
                    'placeholder' => '',
					'cols' => 50,
                    'rows' => 5,
					'class'       => 'text_description',
                    'pattern'     => '.{5,}' //minlength
                )
            ))
			->add('latitude', 'hidden', array(
				'required'    => false
            )) 
			->add('longitude', 'hidden', array(
				'required'    => false
            ))->add('cityid', 'hidden', array(
				'required'    => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       /*$collectionConstraint = new Collection(array(
            'email' => array(
                new Email(array('message' => 'This is invalid email address.'))
            )
        ));*/
    }

    public function getName()
    {
        return 'AddPoi';
    }
}