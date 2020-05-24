<?php

namespace App\Entity;

use App\Traits\UserOwnedEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\WorkoutExerciseSetRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=WorkoutExerciseSetRepository::class)
 * @Gedmo\SoftDeleteable()
 */
class WorkoutExerciseSet
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use UserOwnedEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api", "workout_exercise_set", "workout_exercise"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=WorkoutExercise::class, inversedBy="workoutExerciseSets", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workoutExercise;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api", "workout_exercise_set", "workout_exercise"})
     */
    private $statNotes = "";

    /**
     * @ORM\Column(type="text")
     * @Groups({"api", "workout_exercise_set", "workout_exercise"})
     */
    private $notes = "";

    public function __construct(WorkoutExercise $workoutExercise)
    {
        $this->setWorkoutExercise($workoutExercise);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkoutExercise(): ?WorkoutExercise
    {
        return $this->workoutExercise;
    }

    public function setWorkoutExercise(?WorkoutExercise $workoutExercise): self
    {
        $this->workoutExercise = $workoutExercise;

        return $this;
    }

    public function getStatNotes(): ?string
    {
        return $this->statNotes;
    }

    public function setStatNotes(string $statNotes): self
    {
        $this->statNotes = $statNotes;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getUser()
    {
        return $this->getWorkoutExercise()->getWorkout()->getUser();
    }

    /**
     * @Groups({"api", "workout_exercise", "workout_exercise_set"})
     * @SerializedName("stats")
     * @return array|null
     */
    public function getFormattedStats(): ?array
    {
        $formattedStats = [];

        // Get set template
        $statTemplate = $this->getWorkoutExercise()->getExercise()->getStatTemplate();
        if(!$statTemplate)
        {
            return null;
        }


        // Separate each header from the set template
        $headers = explode(",", $statTemplate);

        // Split each line of the statNotes
        $statNoteLines = explode(PHP_EOL, $this->getStatNotes());

        foreach($statNoteLines as $statNoteLine)
        {
            // Split the line by commas
            $individualStats = explode(",", $statNoteLine);
            if(!$individualStats)
            {
                // Skip if empty
                continue;
            }

            $formattedStat = [];
            foreach($individualStats as $key => $individualStat)
            {
                if(!$individualStat)
                {
                    // Skip if empty
                    continue;
                }

                $statName = $headers[$key] ?? "stat_" . $key;
                $formattedStat[$statName] = $individualStat;
            }

            if(!$formattedStat)
            {
                // Don't add if empty
                continue;
            }

            $formattedStats[] = $formattedStat;
        }

        return $formattedStats ?: null;
    }
}
