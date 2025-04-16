<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Repository\CompanyUserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('matriculeFiscale')
            ->add('secteur')
            ->add('adresse')
            ->add('siteweb')
            ->add('telephone')
            // ->add('companyUser', EntityType::class, [
            //     'class' => CompanyUser::class,
            //     'choices' => $usersWithoutCompany,
            //     'choice_label' => function (CompanyUser $companyUser) {
            //         return $companyUser->getNom();
            //     },
            // ])
            ->add('save', SubmitType::class)
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
