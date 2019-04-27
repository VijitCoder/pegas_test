<?php
namespace App\Joker\Controller;

use App\Joker\Exception\APIException;
use App\Joker\Form\SendJokeType;
use App\Joker\APIClient\CategoryCachedProvider;
use App\Joker\Service\JokerService;
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
     * @param Request      $request
     * @param JokerService $service
     * @return JsonResponse
     * @throws APIException
     */
    public function joking(Request $request, JokerService $service): JsonResponse
    {
        $data = $request->request->all();
        $form = $this->createForm(SendJokeType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            $answer = ['message' => nl2br((string)$form->getErrors(true))];
            return $this->json($answer, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $joke = $service->getJoke($data['category']);

        $html = $this->renderView('joker/letters/letter.html.twig', ['joke' => $joke]);
        $text = $this->renderView('joker/letters/letter.txt.twig', ['joke' => $joke]);

        $answer = [
            'jokeId' => $joke->id,
            'joke' => $joke->joke,
            'isSent' => $service->send($data['email'], $html, $text),
            'isStored' => $service->store($joke),
        ];

         $answer['message'] = 'Request successfully processed.';

        return $this->json($answer);
    }
}
