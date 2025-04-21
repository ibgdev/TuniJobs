<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\JobOffer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('salaire')
            ->add('location', ChoiceType::class, [
                'choices' => $this->getTunisianGovernorates(),
                'placeholder' => 'Choisir un gouvernorat',
                'label' => 'Location',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'nom',
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->addTime(...));
    }

    public function addTime(PostSubmitEvent $event): void
    {
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

    private function getTunisianGovernorates(): array
    {
        $governorates = [
            'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa', 'Jendouba', 'Kairouan',
            'Kasserine', 'Kébili', 'Le Kef', 'Mahdia', 'La Manouba', 'Médenine', 'Monastir',
            'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse', 'Tataouine', 'Tozeur',
            'Tunis', 'Zaghouan'
        ];

        return array_combine($governorates, $governorates);
    }
}
