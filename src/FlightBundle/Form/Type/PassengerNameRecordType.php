<?php

namespace FlightBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\IsTrue;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PassengerNameRecordType extends AbstractType {

    protected $container;
    protected $passengers;
    protected $requestData;

    public function __construct($container, $passengers = null, $requestData = null) {
	    $this->container = $container;
        $this->passengers = $passengers;
        $this->requestData = $requestData;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
	$trans = $this->container->get('translator');
	$userId = $this->container->data['USERID'];
	$builder->add('firstName', 'text', [
		    'label' => 'First Name',
		    'label_attr' => [
			    'class' => 'labelstyle'
		    ],
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control1',
                'maxlength' => 255
		    ]
		])
		->add('surname', 'text', [
		    'label' => 'Surname',
		    'label_attr' => [
			    'class' => 'labelstyle labelstyle2'
		    ],
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control2',
                'maxlength' => 255
		    ]
		])
		->add('countryOfResidence', 'entity', [
		    'label' => 'Country of residence',
		    'label_attr' => [
			    'class' => 'labelstyle'
		    ],
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control1 selectback',
			    'data-counrty-code' => ''
		    ],
		    'class' => 'TTBundle:CmsCountries',
		    'empty_value' => 'Select your country',
		    'query_builder' => function (EntityRepository $er) {
		        return $er->createQueryBuilder('u')->where('u.dialingCode != 0')->orderBy('u.name', 'ASC');
	        },
		    'choice_label' => 'name',
		    'choice_attr' => function($val) {
		        return ['data-country-code' => $val->getCode()];
	        }
		])
		->add('email', 'email', [
		    'label' => 'Email',
		    'label_attr' => [
			    'class' => 'labelstyle labelstyle2'
		    ],
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control2',
                'maxlength' => 255
		    ]
		])
		->add('mobileCountryCode', 'entity', [
		    'label' => 'Mobile',
		    'label_attr' => [
			    'class' => 'labelstyle'
		    ],
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control1'
		    ],
		    'class' => 'TTBundle:CmsCountries',
		    'empty_value' => 'Country code',
		    'query_builder' => function (EntityRepository $err) {
		        return $err->createQueryBuilder('u')->where('u.dialingCode != 0')->orderBy('u.name', 'ASC');
	        },
		    'choice_label' => 'dialingCodeToString',
		    'choice_attr' => function($val) {
		        return ['data-country-code' => $val->getCode()];
	        }
		])
		->add('mobile', 'text', [
		    'required' => true,
		    'attr' => [
			    'class' => 'form-control1 mobile-input',
                'maxlength' => 30
		    ]
		])
		->add('alternativeNumber', 'text', [
		    'label' => 'Add alternative number',
		    'label_attr' => [
			    'class' => 'labelstyle labelstyle2'
		    ],
		    'required' => false,

		    'attr' => [
			    'class' => 'form-control2',
                'maxlength' => 30
		    ]
	    ])
        ->add('membership_id', 'text', [
            'label' => 'Membership ID',
            'label_attr' => [
                'class' => 'labelstyle labelstyle2'
            ],
            'required' => false,
            'attr' => [
                'class' => 'form-control2',
                'maxlength' => 100
            ]
        ]);

//	if (in_array($userId, array(37, 3745))) {
//	    $builder->add('pin', 'text', [
//		'required' => true,
//		'attr' => [
//		    'class' => 'form-control1 pin-input'
//		]
//	    ]);
//	}
	$builder->add('specialRequirement', 'textarea', [
		    'label' => 'Special Requirements',
		    'label_attr' => [
			    'class' => 'adultstyletext'
		    ],
		    'required' => false,
		    'attr' => [
			    'class' => 'col-xs-12 textariastyle',
			    'placeholder' => $trans->trans('Complete only if you need assistance (unaccompanied minors, reduced mobility,...) or services (heavy luggage, pets, equipment,...)'),
			    'maxlength' => 1000
		    ]
		])
//                ->add('bestOffersPrivacy', 'checkbox', [
//                    'required' => true,
//                    'mapped' => false,
//                    'constraints' => new IsTrue(['message' => $trans->trans('Kindly confirm you have read and accept the privacy policy')])
//                ])
		->add('terms', 'checkbox', [
		    'required' => true,
		    'mapped' => false,
		    'constraints' => new IsTrue(['message' => $trans->trans('Kindly confirm you have read and accept terms and conditions and the privacy policy')])
		])
		->add('passengerDetails', 'collection', ['type' => new PassengerDetailType($this->container, $this->passengers), 'allow_extra_fields' => true, 'by_reference' => false, 'cascade_validation' => true])
		->add('save', 'submit', [
		    'label' => 'Buy',
		    'attr' => [
			    'class' => 'alstdiv'
		    ]
	    ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA,array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::POST_SET_DATA,array($this, 'onPreSetData'));

    }

    public function configureOptions(OptionsResolver $resolver) {
	$resolver->setDefaults([
	    'data_class' => 'FlightBundle\Entity\PassengerNameRecord',
	    'csrf_protection' => false,
//            'csrf_field_name' => '_token',
	    'allow_extra_fields' => true,
	    'cascade_validation' => true
	]);
    }

    public function getName() {
	return 'passengerNameRecord';
    }

    /**
     * Preload Data
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {

        $user = $this->container->getUser();

        $form = $event->getForm();

        if ($user) {
            $form->get('firstName')->setData($user->getFName());
            $form->get('surname')->setData($user->getLName());
            $form->get('email')->setData($user->getYouremail());

            $country = $this->container->get('FlightRepositoryServices')->getCountryInfoByCode($user->getYourCountry());
            $form->get('countryOfResidence')->setData($country);
            $form->get('mobileCountryCode')->setData($country);

            $airline = $this->requestData->getAirlineCode();
            $airline = array_values($airline);

            $pnr = $this->container->get('FlightRepositoryServices')->getMemberShipIdByUserAndAirlineCode($user->getId(), $airline[0]);

            if ($pnr) {
                $form->get('membership_id')->setData($pnr->getMembershipId());
            }

            $passengerDetails = $form->get('passengerDetails');

            foreach( $passengerDetails as $i => $passengerDetail){
                $passengerDetail->get('firstName')->setData($user->getFName());
                $passengerDetail->get('surname')->setData($user->getLName());
                $passengerDetail->get('dateOfBirth')->setData($user->getYourbday());

                $passengerDetail->get('gender')->setData($user->getGender());
                break; //only the first child form element
            }
        }

    }
}
