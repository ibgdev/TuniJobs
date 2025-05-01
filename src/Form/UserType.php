<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Entreprise' => 'ROLE_ENTERPRISE',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Condidate' => 'ROLE_CANDIDATE',
                ],
                'expanded' => true,  // checkboxes
                'multiple' => true,  // allow multiple roles
                'label' => 'Rôles',
            ])
            ->add('isVerified')
            ->addEventListener(FormEvents::POST_SUBMIT, $this->addTime(...));
        ;
    }

    public function addTime(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!($data instanceof User)) {
            return;
        }
        $data->setCreatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
