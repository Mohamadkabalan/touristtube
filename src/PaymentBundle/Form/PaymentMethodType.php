<?php

namespace PaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\CardScheme;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use PaymentBundle\EventSubscriber\PaymentSubscriber;

/**
 * This class represents the Payment form, 
 * which can then be reuse anywhere in the application.
 */
class PaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $paymentGateway = $options['payment_service'];

        //Credit Card
        $builder->add('cardholder', 'text', array(
            'required' => true,
            'mapped' => false,
            'label' =>'Card Holder',
            'attr' => array(
              
                'id' => 'cardholder',
                'autocomplete' => 'off',
                'placeholder' => 'Cardholder Name'
             ),
             'constraints' => array(
                    new NotBlank(array('message' => 'Card Holder is required field')),
                ),  
        ))->add('cardnumber', 'text', array(
            'required' => true,
            'mapped' => false,
            'label' =>'Card Number',
            'attr' => array(
     
                'id' => 'cardnumber',
                'autocomplete' => 'off',
                'placeholder' => 'Card number'
             ),
             'constraints' => array(
                new NotBlank(array('message' => 'Card Number is required field')),
                new CardScheme(
                array(
                    'message' => 'Unsupported card type or invalid card number',
                    'schemes' => array(
                        'AMEX', 
                        'CHINA_UNIONPAY', 
                        'DINERS', 
                        'DISCOVER',
                        'INSTAPAYMENT', 
                        'JCB', 
                        'LASER', 
                        'MAESTRO',
                        'MASTERCARD', 
                        'VISA', 
                    )
                )),
           ),
             
        ))->add('card_expire_month', 'choice', array(
            'choices'   => $this->getExpireMonths(),
            'required' => true,
            'mapped' => false,
            'label' =>'Month',
            'attr' => array(
     
                'id' => 'card_expire_month',
                'autocomplete' => 'off',
                'placeholder' => ''
             ),
        ))->add('card_expire_year', 'choice', array(
            'choices'   => $this->getExpireYears(),
            'required' => true,
            'mapped' => false,
            'label' =>'Year',
            'attr' => array(
              
                'id' => 'card_expire_year',
                'autocomplete' => 'off',
                'placeholder' => ''
             ),
        ))->add('cvv', 'text', array(
            'required' => true,
            'mapped' => false,
            'label' =>'Cvv',
            'attr' => array(
         
                'id' => 'cvv',
                'autocomplete' => 'off',
                'placeholder' => ''
             ),
             'constraints' => array(
                    new NotBlank(array('message' => 'Security Code is required field')),
                ),
        ));
        
        $builder->add('server_error_callback', 'text', array(
            'required' => false,
            'mapped' => false,
            'label' =>'server_error_callback'
        ));

        $builder->addEventSubscriber( new PaymentSubscriber( $paymentGateway ) );
    }
    
    public function getName()
    {
        return 'touristtube_payment_method_type';
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => '',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'payment_service' => null
        ));
    }
    
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setRequired('payment_service');
    }

    private function getExpireMonths(){
        return array(
            '' => 'Month',
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',

        );
    }
    
    private function getExpireYears(){
        
        $years = array();
        $years[""] = 'Year';
        
        for ($y=date('Y'); $y<= date('Y') + 12; $y++){
            $years[$y] = $y;
        }
        return $years;
    }
    
        
}
