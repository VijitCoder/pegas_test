<?php
namespace App\Joker\Controller;

use App\Joker\Exception\APIException;
use App\Joker\Form\SendJokeType;
use App\Joker\APIClient\CategoryCachedProvider;
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
        $data = $request->request->all();
        $form = $this->createForm(SendJokeType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            $answer = ['message' => nl2br((string)$form->getErrors(true))];
            return $this->json($answer, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // $form->getData() or just $data
        $answer = ['message' => 'good'];

        return $this->json($answer);
    }
}
