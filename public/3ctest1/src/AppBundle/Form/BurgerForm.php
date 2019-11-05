<?php


namespace AppBundle\Form;


use AppBundle\Entity\Burger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BurgerForm
 * @package AppBundle\Form
 */
class BurgerForm extends AbstractType
{
    /** @var TranslatorInterface  */
    private $translator;

    /**
     * AddAvisCodeForm constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('name')
            ])
            ->add('desc', TextareaType::class, [
                'label' => $this->translator->trans('desc')
            ])
            ->add('price', IntegerType::class, [
                'label' => $this->translator->trans('price')
            ])
            ->add('supp_double', IntegerType::class, [
                'label' => $this->translator->trans('double'),
            ])
            ->add('save', SubmitType::class, [
                'label'=> $this->translator->trans('save'),
            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Burger::class
        ]);
    }

}