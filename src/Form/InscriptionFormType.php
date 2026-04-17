<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
//use App\Entity\Establishment;
//use App\Repository\Impl\EstablishmentRepositoryImpl;
use App\Repository\Impl\GroupRepositoryImpl;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class InscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $selectedEstablishment = $options['selected_establishment'];
        $establishments = $options['establishments'];
        $defaultNickname = $options['nom_depart'];

        $builder
            ->add('pseudoUser', TextType::class, [
                'label' => 'Mon identité secrète',
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => $defaultNickname,
                'attr' => [
                    'readonly' => true,
                ],
            ])

            ->add('establishment', ChoiceType::class, [
                'label' => 'Mon établissement',
                'mapped' => false,
                'required' => true,
                'placeholder' => '👇 Touchez pour choisir',
                'choices' => array_combine($establishments, $establishments),
                'data' => $selectedEstablishment !== '' ? $selectedEstablishment : null,
            ])

            ->add('group', EntityType::class, [
                'label' => 'Mon groupe',
                'class' => Group::class,
                'choice_label' => 'nameGroup',
                'placeholder' => $selectedEstablishment !== ''
                    ? '👇 Choisir le groupe'
                    : '🔒 Choisissez d’abord l’établissement',
                'required' => true,
                'query_builder' => function (GroupRepositoryImpl $repo) use ($selectedEstablishment) {
                return $repo->qbByEstablishment($selectedEstablishment);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'establishments' => [],
            'selected_establishment' => '',
            'nom_depart' => '',
        ]);

        $resolver->setAllowedTypes('establishments', 'array');
        $resolver->setAllowedTypes('selected_establishment', 'string');
        $resolver->setAllowedTypes('nom_depart', 'string');
    }
}
