<?php

namespace App\Form;

use App\Validator\Constraints\NoHtml;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                    new NoSuspiciousCharacters(),
                    new NoHtml(),
                    new Regex(
                        pattern: '/\d/',
                        match: false,
                        message: 'Your name cannot contain a number',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new NoSuspiciousCharacters(),
                    new NoHtml(),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                    new NoSuspiciousCharacters(),
                    new NoHtml(),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                    new NoSuspiciousCharacters(),
                    new NoHtml(),
                ],
            ]);
    }
}
