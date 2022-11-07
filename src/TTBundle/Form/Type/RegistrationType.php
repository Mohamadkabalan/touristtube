<?php
// src/AppBundle/Form/Type/RegistrationType.php
namespace TTBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		 $builder
            ->add('YourEmail', 'text', array(
				'label'       => 'Email Address',
                'attr' => array(
                    'placeholder' => 'Email Address',
					'class'       => 'email',
                )
            ))
            ->add('YourUserName', 'text', array(
				'label'       => 'Username',
				'required'    => false,
                'attr' => array(
				    'class'       => 'username',
                    'placeholder' => 'Username',
					'pattern'     => '.{3,}' //minlength
                )
            ))
          ;
		  $builder->add('YourPassword', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',
           'type'        => 'password',
		   'invalid_message' => 'your password and confirm password did not match.'
        )); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'YourEmail' => array(
                new NotBlank(array('message' => 'Email address should not be blank.')),
				new Email(array('message' => 'This is Invalid email address.'))
            ),
            'YourUserName' => array(
                new NotBlank(array('message' => 'Username should not be blank.')),
                new Length(array('min' => 3))
            ),
			 'YourPassword' => array(
                new NotBlank(array('message' => 'Password should not be blank.')),
                new Length(array('min' => 3))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName()
    {
        return 'registration';
    }
}