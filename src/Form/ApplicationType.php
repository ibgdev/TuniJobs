<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('cv', FileType::class, [
            'label' => 'CV (PDF uniquement)',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['application/pdf'],
                    'mimeTypesMessage' => 'Veuillez uploader un fichier PDF valide.',
                ]),
            ],
        ])
        ->add('letterMotivation', FileType::class, [
            'label' => 'Lettre de motivation (PDF uniquement)',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['application/pdf'],
                    'mimeTypesMessage' => 'Veuillez uploader un fichier PDF valide.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
