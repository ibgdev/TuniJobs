<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\JobOffer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('salaire')
            ->add('location')
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ])  
            ->addEventListener(FormEvents::POST_SUBMIT, $this->addTime(...))
        ;
    }

    public function addTime(PostSubmitEvent $event){
        $data = $event->getData();
        if (!($data instanceof JobOffer)) {
            return;
        }
        $data->setDatePublication(new \DateTimeImmutable());
    } 

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
