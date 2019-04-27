<?php
namespace App\Joker\Form;

use App\Joker\Exception\APIException;
use App\Joker\Service\CategoryProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form for send joke request
 */
class SendJokeType extends AbstractType
{
    /**
     * An unique key to help generate the secret token.
     *
     * This option is optional but greatly enhances the security
     * of the generated token by making it different for each form.
     */
    public const CSRF_TOKEN_ID = 'token';

    /**
     * @var CategoryProvider
     */
    private $categoryProvider;

    /**
     * Inject dependencies
     *
     * @param CategoryProvider $categoryProvider
     */
    public function __construct(CategoryProvider $categoryProvider)
    {
        $this->categoryProvider = $categoryProvider;
    }

    /**
     *  Builds the form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @throws APIException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->categoryProvider->getCategories();

        $builder
            ->add('email', EmailType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(['message' => 'Email should be specified.']),
                ],
            ])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'choices'  => $categories,
                'invalid_message' => 'Unknown category.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'    => true,
            'csrf_field_name'    => 'csrf_token',
            'csrf_token_id'      => static::CSRF_TOKEN_ID,
        ]);
    }
}
