<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('fullname', TextType::class, ['label' => 'Nom & Prenom'])
                ->add('username', TextType::class, [
                    'label' => 'Pseudo',
                    'constraints' => [
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Too short only {{ value }} instead of {{ limit }}'
                        ])
                    ]
                ])
                ->add('email', EmailType::class, ['label' => 'Email'])
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'constraints' => array(
                        new NotBlank(),
                        new Regex([
                            'pattern' => '/(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_])[A-Za-z\d@$!%*#?&]{3,}/',
                            'message'=>'Minimum three characters, at least one letter, one number and one special character.'
                        ]),
                    ),
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation']
                ])
                ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
