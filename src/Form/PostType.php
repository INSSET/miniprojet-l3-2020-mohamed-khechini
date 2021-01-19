<?php

namespace App\Form;

use App\Entity\Post;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class PostType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('picture', FileType::class, [
                    'required'=> false,
                    'label' => 'Image',
                    'data_class' => null
               ])
                ->add('title', TextType::class, ['label' => 'Titre'])
                ->add('body', TextareaType::class, ['label' => 'Article']);
                
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('isPublished', CheckboxType::class, ['label' => 'Publié', 'required'=> false]);
        }

        $builder->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
