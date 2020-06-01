<?php

namespace App\Entity;

use App\Traits\UserOwnedEntity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorkoutRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass=WorkoutRepository::class)
 * @Gedmo\SoftDeleteable()
 */
class Workout
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workouts", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=WorkoutExercise::class, mappedBy="workout", orphanRemoval=true)
     * @Groups({"api", "workout"})
     * @MaxDepth(1)
     */
    private $workoutExercises;


    public function __construct()
    {
        $this->workoutExercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|WorkoutExercise[]
     */
    public function getWorkoutExercises(): Collection
    {
        return $this->workoutExercises;
    }

    public function addWorkoutExercise(WorkoutExercise $workoutExercise): self
    {
        if (!$this->workoutExercises->contains($workoutExercise)) {
            $this->workoutExercises[] = $workoutExercise;
            $workoutExercise->setWorkout($this);
        }

        return $this;
    }

    public function removeWorkoutExercise(WorkoutExercise $workoutExercise): self
    {
        if ($this->workoutExercises->contains($workoutExercise)) {
            $this->workoutExercises->removeElement($workoutExercise);
            // set the owning side to null (unless already changed)
            if ($workoutExercise->getWorkout() === $this) {
                $workoutExercise->setWorkout(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"api", "workout", "workout_exercise"})
     * @SerializedName("createdAt")
     */
    public function getCreatedAtApi()
    {
        return $this->getCreatedAt();
    }
}
