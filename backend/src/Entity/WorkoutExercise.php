<?php

namespace App\Entity;

use App\Traits\UserOwnedEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use App\Repository\WorkoutExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=WorkoutExerciseRepository::class)
 * @Gedmo\SoftDeleteable()
 */
class WorkoutExercise
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use UserOwnedEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api", "workout_exercise_set", "workout", "workout_exercise"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="workoutExercises", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api", "workout_exercise_set", "workout_exercise"})
     */
    private $workout;

    /**
     * @ORM\ManyToOne(targetEntity=Exercise::class, inversedBy="workoutExercises", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api", "workout_exercise_set", "workout", "workout_exercise"})
     */
    private $exercise;

    /**
     * @ORM\OneToMany(targetEntity=WorkoutExerciseSet::class, mappedBy="workoutExercise", orphanRemoval=true, fetch="EXTRA_LAZY")
     * @Groups({"api", "workout_exercise"})
     */
    private $workoutExerciseSets;

    public function __construct(Workout $workout, Exercise $exercise)
    {
        $this->workoutExerciseSets = new ArrayCollection();
        $this->setWorkout($workout);
        $this->setExercise($exercise);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    public function setWorkout(?Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    public function setExercise(?Exercise $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * @return Collection|WorkoutExerciseSet[]
     */
    public function getWorkoutExerciseSets(): Collection
    {
        return $this->workoutExerciseSets;
    }

    public function addWorkoutExerciseSet(WorkoutExerciseSet $workoutExerciseSet): self
    {
        if (!$this->workoutExerciseSets->contains($workoutExerciseSet)) {
            $this->workoutExerciseSets[] = $workoutExerciseSet;
            $workoutExerciseSet->setWorkoutExercise($this);
        }

        return $this;
    }

    public function removeWorkoutExerciseSet(WorkoutExerciseSet $workoutExerciseSet): self
    {
        if ($this->workoutExerciseSets->contains($workoutExerciseSet)) {
            $this->workoutExerciseSets->removeElement($workoutExerciseSet);
            // set the owning side to null (unless already changed)
            if ($workoutExerciseSet->getWorkoutExercise() === $this) {
                $workoutExerciseSet->setWorkoutExercise(null);
            }
        }

        return $this;
    }

    public function getUser()
    {
        return $this->getWorkout()->getUser();
    }

    /**
     * @Groups({"api", "workout_exercise", "workout_exercise_set"})
     * @SerializedName("setTotals")
     * @return integer
     */
    public function getSetTotals(): int
    {
        $exercise = $this->getExercise();
        if(!$exercise)
        {
            return 0;
        }

        $sumStat = $exercise->getSumStat();
        if(!$sumStat)
        {
            return 0;
        }

        $sets = $this->getWorkoutExerciseSets();
        if(!$sets)
        {
            return 0;
        }

        $total = 0;
        foreach($sets as $set)
        {
            // Total up the stat that should be summed
            $stats = $set->getFormattedStats();
            if(!$stats)
            {
                continue;
            }

            foreach($stats as $stat)
            {
                if(!empty($stat[$sumStat]))
                {
                    $total += $stat[$sumStat];
                }
            }
        }

        return $total;
    }
}
