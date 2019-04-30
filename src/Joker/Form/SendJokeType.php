<?php
namespace App\Joker\Form;

use App\Joker\Exception\APIException;
use App\Joker\APIClient\CachedAPIClient;
use Psr\Cache\InvalidArgumentException;
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
     * @var CachedAPIClient
     */
    private $apiClient;

    /**
     * Inject dependencies
     *
     * @param CachedAPIClient $apiClient
     */
    public function __construct(CachedAPIClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     *  Builds the form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @throws APIException
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->apiClient->getCategories();

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

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection'    => true,
            'csrf_field_name'    => 'csrf_token',
            'csrf_token_id'      => static::CSRF_TOKEN_ID,
        ]);
    }
}
