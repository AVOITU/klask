<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\Establishment;
use App\Repository\Impl\EstablishmentRepositoryImpl;
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
        $selectedSchool = $options['selected_school'];
        $schools = $options['schools'];
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

            ->add('school', ChoiceType::class, [
                'label' => 'Mon établissement',
                'mapped' => false,
                'required' => true,
                'placeholder' => '👇 Touchez pour choisir',
                'choices' => array_combine($schools, $schools),
                'data' => $selectedSchool !== '' ? $selectedSchool : null,
            ])

            ->add('group', EntityType::class, [
                'label' => 'Mon groupe',
                'class' => Group::class,
                'choice_label' => 'nameGroup',
                'placeholder' => $selectedSchool !== ''
                    ? '👇 Choisir le groupe'
                    : '🔒 Choisissez d’abord l’établissement',
                'required' => true,
                'query_builder' => function (GroupRepositoryImpl $repo) use ($selectedSchool) {
                return $repo->qbByEstablishment($selectedSchool);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'schools' => [],
            'selected_school' => '',
            'nom_depart' => '',
        ]);

        $resolver->setAllowedTypes('schools', 'array');
        $resolver->setAllowedTypes('selected_school', 'string');
        $resolver->setAllowedTypes('nom_depart', 'string');
    }
}
