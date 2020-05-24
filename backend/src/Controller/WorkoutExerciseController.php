<?php
namespace App\Controller;

use DateTime;
use App\Entity\Workout;
use App\Entity\Exercise;
use App\Entity\WorkoutExercise;
use App\Entity\WorkoutExerciseSet;
use App\Exception\AccessDeniedException;
use App\Form\UpdateWorkoutExerciseSetType;
use App\Repository\ExerciseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @IsGranted("ROLE_USER")
 * @Route("", defaults={ "authenticate": 1 })
 */
class WorkoutExerciseController extends BaseRestController
{
    protected $apiSerializeGroup = "workout_exercise";

    /**
     * @Route("/workout/{workoutId}/exercise/{exerciseId}", name="app_workout_add_exercise", methods={"post"})
     * @ParamConverter("workout", options={"mapping": {"workoutId": "id"}})
     * @ParamConverter("exercise", options={"mapping": {"exerciseId": "id"}})
     */
    public function addWorkoutExercise(Workout $workout, Exercise $exercise)
    {
        // Make sure the workout and exercise belongs to the user
        if(!$workout->belongsTo($this->getUser()) || !$exercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        // Create a new WorkoutExercise
        $workoutExercise = new WorkoutExercise($workout, $exercise);
        $this->restService->saveEntity($workoutExercise);

        return $this->restService->respond($workoutExercise);
    }

    /**
     * @Route("/workout-exercise/{id}", name="app_workout_exercise_update", methods={"PATCH"})
     */
    public function updateWorkoutExercise(WorkoutExercise $workoutExercise, ExerciseRepository $exerciseRepository, Request $request)
    {
        if(!$workoutExercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $payload = $this->restService->parseJsonPayload($request);

        $exercise = $exerciseRepository->findOneBy([
            'id' => $payload['exerciseId'] ?? 0,
            'user' => $this->getUser()
        ]);

        if(!$exercise)
        {
            throw new AccessDeniedException();
        }


        $workoutExercise->setExercise($exercise);
        $this->restService->saveEntity($workoutExercise);

        return $this->restService->respond($workoutExercise);
    }

    /**
     * @Route("/workout-exercise/{id}", name="app_workout_exercise_delete", methods={"DELETE"})
     */
    public function deleteWorkoutExercise(WorkoutExercise $workoutExercise)
    {
        if(!$workoutExercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $this->restService->deleteEntity($workoutExercise);

        return $this->json([]);
    }


    /**
     * @Route("/workout-exercise/{id}", name="app_workout_exercise_get", methods={"GET"})
     */
    public function getWorkoutExercise(WorkoutExercise $workoutExercise)
    {
        if(!$workoutExercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        return $this->restService->respond($workoutExercise);
    }
}
