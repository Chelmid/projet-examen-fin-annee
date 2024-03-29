<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "email@email.fr"
                ]
            ])
            ->add('adresse', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => true
                ]
            ])
            ->add('ville', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => true
                ]
            ])
            ->add('cp', IntegerType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => true
                ]
            ])
            ->add('pays', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => true
                ]
            ])
            ->add('telephone', IntegerType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => true
                ]
            ])
            ->add('numbertva', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                    'required' => false
                ]
            ])
            ->add('groupclient', ChoiceType::class,
                ['choices' => array(
                    'Retail' => 1,
                    'Gift' => 2),
                    'expanded' => true,
                    'required' => true,
                    'multiple' => false,
                    "attr" => [
                        "class" => "mr-3",
                    ]]
            )
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                "attr" => [
                    "class" => "form-control",
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
