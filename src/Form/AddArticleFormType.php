<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\LieuAchat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('fichier_photo', FileType::class, [
                'label'=> 'Photo du ticket',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    New File([
                        'maxSize' => '1024k',
                        'mimeTypesMessage' => 'Veuillez ajouter une photo au format jpg.',
                ])]
            ])
            ->add('date_achat', DateType::class)
            ->add('date_garantie', DateType::class)
            ->add('lieu_achat', EntityType::class, [
                'class' => LieuAchat::class,
                'choice_label' => 'nom',
            ])
            ->add('zone_saisie')
            ->add('notice')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
