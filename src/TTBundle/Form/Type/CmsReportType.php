<?php
// src/AppBundle/Form/Type/ReportType.php
namespace TTBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;



class CmsReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		//echo $this->local;
		//$translator = new Translator('fr_FR', new MessageSelector());
		//$translator->setFallbackLocales(array('fr'));
		//$translator->addLoader('array', new ArrayLoader());
		//$translator->addResource('array', array(
		//	'Hello World!' => 'Bonjour',
		//), 'fr');

		//echo $translator->trans('Hello World!')."\n";
		
        $builder
            ->add('first_name', 'text', array(
                'label'       => "First Name",
                'attr' => array(
                    'placeholder' => 'What\'s your first name?',
					'class'       => 'FeedbackForm',
					'value'       => '',
                    'pattern'     => '.{2,}' //minlength					
                )
            ))
            ->add('last_name', 'text', array(
                'label'       => "Last Name",
                'attr' => array(
                    'placeholder' => 'What\'s your last name?',
					'class'       => 'FeedbackForm',
					'value'       => '',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('email', 'email', array(
                'label'       => 'Email',
                'attr' => array(
				    'class'       => 'email',
					'value'       => '',
                    'placeholder' => 'email'
                )
            ))
            ->add('msg', 'textarea', array(
                'label'       => 'Message',
                'attr' => array(
                    'placeholder' => '',
					'cols' => 50,
                    'rows' => 5,
					'class'       => 'message',
                    'pattern'     => '.{5,}' //minlength
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
//        $collectionConstraint = new Collection(array(
//            'title' => array(
//                new NotBlank(array('message' => 'Title should not be blank.')),
//                new Length(array('min' => 2))
//            ),
//            'email' => array(
//                new NotBlank(array('message' => 'Email should not be blank.')),
//                new Email(array('message' => 'this is Invalid email address.'))
//            ),
//            'msg' => array(
//                new NotBlank(array('message' => 'Message should not be blank.')),
//                new Length(array('min' => 5))
//            )
//        ));
    }

    public function getName()
    {
        return 'cmsreport';
    }
}