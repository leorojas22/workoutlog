<?php
namespace App\Controller;

use App\Entity\Workout;
use App\Entity\Exercise;
use App\Entity\WorkoutExercise;
use App\Service\RestApiService;
use App\Exception\AccessDeniedException;
use App\Repository\WorkoutRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @IsGranted("ROLE_USER")
 * @Route("", defaults={ "authenticate": 1 })
 */
class WorkoutController extends BaseRestController
{
    protected $apiSerializeGroup = "workout";

    /**
     * @Route("/workout", name="app_workout_create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $workout = new Workout();
        $workout->setUser($this->getUser());
        $this->isGranted("ROLE_USER");
        $this->restService->saveEntity($workout);

        return $this->restService->respond($workout);
    }

    /**
     * @Route("/workout/{id}", name="app_workout_get", methods={"GET"})
     */
    public function getWorkout(Workout $workout)
    {
        if(!$workout->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        return $this->restService->respond($workout);
    }

    /**
     * @Route("/workout", name="app_workout_get_collection", methods={"GET"})
     */
    public function getWorkouts(WorkoutRepository $workoutRepository)
    {
        $workouts = $workoutRepository->findBy(['user' => $this->getUser()]);

        return $this->restService->respond($workouts);
    }
}
