<?php
namespace App\Joker\Controller;

use App\Joker\Exception\APIException;
use App\Joker\Service\CategoryCachedProvider;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This controller serves for joking randomly to email by using the external API as a joke generator.
 *
 * @link http://www.icndb.com/api/ joke generator
 */
class JokerController extends AbstractController
{
    /**
     * Provide the form for select the joking target
     *
     * @Route("/", name="index", methods="GET|HEAD")
     *
     * @param CategoryCachedProvider $provider
     * @return Response
     * @throws APIException
     * @throws InvalidArgumentException
     */
    public function index(CategoryCachedProvider $provider): Response
    {
        return $this->render(
            'joker/index.html.twig',
            ['categories' => $provider->getCategories()]
        );
    }

    /**
     * Get a random joke from the external API, send it to the specified email and store the joke to the disk.
     *
     * (ajax)POST
     *
     * @Route("/joking", name="joking", methods="POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function joking(Request $request): JsonResponse
    {
        $csrfToken = $request->get('csrf_token');
        if (!$this->isCsrfTokenValid('token', $csrfToken)) {
            return $this->json(['message' => 'Wrong CSRF token'], Response::HTTP_BAD_REQUEST);
        }

        $answer = ['message' => 'stub'];

        return $this->json($answer);
    }
}
