<?php
declare(strict_types=1);


namespace App\Form;


use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeItemFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', HiddenType::class, [
                'mapped' => false
            ])
            ->add('state', HiddenType::class, [
                'data' => Item::STATE_DENIED
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Deny reason'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Deny',
                'attr' => [
                    'class' => 'btn btn-danger',
                    'style' => 'float: right;'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class
        ]);
    }

}