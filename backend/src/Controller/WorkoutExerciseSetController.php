<?php
namespace App\Controller;

use App\Entity\WorkoutExercise;
use App\Entity\WorkoutExerciseSet;
use App\Exception\AccessDeniedException;
use App\Form\UpdateWorkoutExerciseSetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @IsGranted("ROLE_USER")
 * @Route("", defaults={ "authenticate": 1 })
 */
class WorkoutExerciseSetController extends BaseRestController
{
    protected $apiSerializeGroup = "workout_exercise_set";

    /**
     * @Route("/workout-exercise/{id}/set", name="app_workout_exercise_set_add", methods={"POST"})
     */
    public function addSet(WorkoutExercise $workoutExercise)
    {
        // Make sure the workout exercise belongs to the logged in user
        if(!$workoutExercise->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        // Create a new set
        $workoutSet = new WorkoutExerciseSet($workoutExercise);
        $this->restService->saveEntity($workoutSet);

        return $this->restService->respond($workoutSet);
    }

    /**
     * @Route("/workout-exercise/{workoutExerciseId}/set/{workoutExerciseSetId}", name="app_workout_exercise_set_update", methods={"PATCH"})
     * @ParamConverter("workoutSet", options={"mapping": {"workoutExerciseSetId": "id", "workoutExerciseId": "workoutExercise"}})
     */
    public function updateSet(WorkoutExerciseSet $workoutSet, Request $request)
    {
        if(!$workoutSet->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(UpdateWorkoutExerciseSetType::class, $workoutSet);

        return $this->restService->handleFormRequest($request, $form);
    }

    /**
     * @Route("/workout-exercise/{workoutExerciseId}/set/{workoutExerciseSetId}", name="app_workout_exercise_set_delete", methods={"DELETE"})
     * @ParamConverter("workoutSet", options={"mapping": {"workoutExerciseSetId": "id", "workoutExerciseId": "workoutExercise"}})
     */
    public function deleteSet(WorkoutExerciseSet $workoutSet)
    {
        if(!$workoutSet->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        $this->restService->deleteEntity($workoutSet);

        return $this->json([]);
    }

    /**
     * @Route("/workout-exercise/{workoutExerciseId}/set/{workoutExerciseSetId}", name="app_workout_exercise_set_get", methods={"GET"})
     * @ParamConverter("workoutSet", options={"mapping": {"workoutExerciseSetId": "id", "workoutExerciseId": "workoutExercise"}})
     */
    public function getSet(WorkoutExerciseSet $workoutExerciseSet)
    {
        if(!$workoutExerciseSet->belongsTo($this->getUser()))
        {
            throw new AccessDeniedException();
        }

        return $this->restService->respond($workoutExerciseSet);
    }
}
