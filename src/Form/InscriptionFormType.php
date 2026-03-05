<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\Impl\ClassroomRepositoryImpl;
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

        $builder
            ->add('pseudoUser', TextType::class,[
                'constraints' =>[new NotBlank()]
            ])
            ->add('school', ChoiceType::class,[
                'mapped' => false,
                'placeholder' => '👇 Touchez pour choisir',
                'choices' => array_combine($options['schools'], $options['schools']),
            ])

            ->add('classroom', EntityType::class,[
                'class' => Classroom::class,
                'placeholder' => $selectedSchool ? 'Choisir la classe' : '🔒 Choisissez d\'abord l\'école',
                'query_builder' => function (ClassroomRepositoryImpl $classroomRepositoryImpl) use ($selectedSchool) {
                    return $classroomRepositoryImpl->qbBySchool($selectedSchool);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'schools' => [],
            'selected_school' => '',
        ]);

        $resolver->setAllowedTypes('schools', 'array');
        $resolver->setAllowedTypes('selected_school', 'string');
    }
}
