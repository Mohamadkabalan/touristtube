<?php

namespace FlightBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\IsTrue;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use FlightBundle\Entity\PassengerDetail;


class PassengerDetailType extends AbstractType
{

    protected $container;
    protected $passengers;

    public function __construct($container, $passengers = null)
    {
        $this->container = $container;
        $this->passengers = $passengers;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $trans = $this->container->get('translator');
        $builder->add('type', 'hidden')
            ->add('firstName', 'text', [
                'label' => 'First Name',
                'label_attr' => [
                    'class' => 'labelstyle'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control1',
                    'data-validation' => 'required',
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
            ]);

            $builder->add('phone', 'text', [
                'label' => 'Phone',
                'label_attr' => [
                    'class' => 'labelstyle labelstyle2'
                ],
                'required' => false,
                'attr' => [
                    'class' => 'form-control3 mobile-input',
                    'maxlength' => 20
                ]
            ])
                ->add('email', 'email', [
                    'label' => 'Email Address',
                    'label_attr' => [
                        'class' => 'labelstyle labelstyle2'
                    ],
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control4',
                        'maxlength' => 60
                    ]
                ])
                ->add('receiveAlerts', 'checkbox', [
                    'required' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'receiveAlerts',
                        'checked' => 'checked'

                    ]
                ])
                ->add('usePhoneEmail', 'checkbox', [
                    'required' => false,
                    'mapped' => false,
                    'attr' => [
                        'class' => 'usePhoneEmail'
                    ]
                ]);


        if ($this->container->data['isPassportRequired'] == 1) {
            $date = new \DateTime();
            $year = $date->modify('+10 years');
            $builder->add('passportNo', 'text', [
                'label' => 'Passport No.',
                'label_attr' => [
                    'class' => 'labelstyle labelstyle2'
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control2',
                    'maxlength' => 30
                ]
            ])
                ->add('passportExpiry', 'birthday', [
                    'label' => 'Passport Expiry',
                    'label_attr' => [
                        'class' => 'labelstyle labelstyle2'
                    ],
                    'years' => range(date('Y'), $year->format('Y')),
                    'empty_value' => ['year' => 'year', 'month' => 'month', 'day' => 'day'],
                    'required' => true,
                    'attr' => [
                        'class' => 'dob'
                    ]
                ])
                ->add('passportIssueCountry', 'entity', [
                    'label' => 'Issued Country',
                    'label_attr' => [
                        'class' => 'labelstyle'
                    ],
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control2'
                    ],
                    'class' => 'TTBundle:CmsCountries',
                    'empty_value' => 'Select Country',
                    'query_builder' => function (EntityRepository $erIssue) {
                        return $erIssue->createQueryBuilder('u')->where('u.dialingCode != 0')->orderBy('u.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'choice_attr' => function ($val) {
                        return ['data-country-code' => $val->getCode()];
                    }
                ])
                ->add('passportNationalityCountry', 'entity', [
                    'label' => 'Nationality Country',
                    'label_attr' => [
                        'class' => 'labelstyle'
                    ],
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control2',
                    ],
                    'class' => 'TTBundle:CmsCountries',
                    'empty_value' => 'Select Country',
                    'query_builder' => function (EntityRepository $erNation) {
                        return $erNation->createQueryBuilder('u')->where('u.dialingCode != 0')->orderBy('u.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'choice_attr' => function ($val) {
                        return ['data-country-code' => $val->getCode()];
                    }
                ])
                ->add('idNo', 'text', [
                    'label' => 'ID No.',
                    'label_attr' => [
                        'class' => 'labelstyle labelstyle2'
                    ],
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control2',
                        'maxlength' => 30
                    ]
                ]);

        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::POST_SET_DATA, array($this, 'onPreSetData'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FlightBundle\Entity\PassengerDetail',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'cascade_validation' => true
        ]);
    }

    public function getName()
    {
        return 'passengerDetail';
    }

    /**
     * Preload Data
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $passengerDetail = new PassengerDetail();

        if ($passengerDetail instanceof $data) {

            $yearStart = date("Y") - 12;
            $yearEnd = $yearStart - 100;
            $choices = [
                'Mr' => 'M',
                'Miss' => 'F'
            ];

            if ($data->getType() == "CNN") {
                $yearStart = date("Y") - 2;
                $yearEnd = $yearStart - 12;
            }

            if ($data->getType() == "INF") {
                $yearStart = date("Y");
                $yearEnd = $yearStart - 2;
            }

            if ($data->getType() == "ADT") {
                $choices = [
                    'Mr' => 'M',
                    'Ms / Mrs / Miss' => 'F'
                ];
            }

            $form->add('dateOfBirth', 'birthday',
                [
                    'label' => 'Date of Birth',
                    'label_attr' => [
                        'class' => 'labelstyle'
                    ],
                    'years' => range($yearStart, $yearEnd),
                    'empty_value' => ['year' => 'year', 'month' => 'month', 'day' => 'day'],
                    'required' => true,
                    'attr' => [
                        'class' => 'dob'
                    ]
                ]);

            $form->add('gender', 'choice', [
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'choices' => $choices,
                'empty_data' => 'M',
                'data' => 'M',
                'placeholder' => false,
                'choices_as_values' => true,
            ]);

        }
    }
}
