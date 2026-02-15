<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\ContactFormDTO;

class ContactFormDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
            ])
            ->add('email', EmailType::class, [
                'required' => 'false',
                'empty_data' => '',
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
            ])
            ->add('service', ChoiceType::class, [
                'choices'  => [
                    'Compta' => "compta@demo.fr",
                    'Support' => "support@demo.fr",
                    'Marketing' => "marketing@demo.fr"
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            // ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            // ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDating(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => ContactFormDTO::class,
        ]);
    }
}
