<?php
namespace App\Controller;

use App\Entity\Exercise;
use App\Exception\AccessDeniedException;
use App\Form\ExerciseType;
use App\Helper\FormValidator;
use App\Repository\ExerciseRepository;
use App\Service\RestApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * @Route("", defaults={ "authenticate": 1 })
 */
class ExerciseController extends BaseRestController
{

    /**
     * @Route("/exercise", name="app_exercise_create", methods={"POST"})
     */
    public function createExercise(Request $request)
    {
        $exercise = new Exercise();
        $exercise->setUser($this->getUser());
        $form = $this->createForm(ExerciseType::class, $exercise);

        return $this->restService->handleFormRequest($request, $form);
    }

    /**
     * @Route("/exercise/{id}", name="app_exercise_update", methods={"PATCH"})
     */
    public function updateExercise(Exercise $exercise, Request $request)
    {
        if(!$exercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(ExerciseType::class, $exercise);
        return $this->restService->handleFormRequest($request, $form);
    }

    /**
     * @Route("/exercise/{id}", name="app_excercise_get", methods={"GET"})
     */
    public function getExercise(Exercise $exercise)
    {
        if(!$exercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        return $this->restService->respond($exercise);
    }

    /**
     * @Route("/exercise/{id}", name="app_exercise_delete", methods={"DELETE"})
     */
    public function deleteExercise(Exercise $exercise)
    {
        if(!$exercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $this->restService->deleteEntity($exercise);

        return $this->json([]);
    }

    /**
     * @Route("/exercise", name="app_excercise_get_collection", methods={"GET"})
     */
    public function getExercises(ExerciseRepository $exerciseRepository)
    {
        $exercises = $exerciseRepository->findBy(['user' => $this->getUser()]);

        return $this->restService->respond($exercises);
    }
}
