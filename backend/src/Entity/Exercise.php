<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExerciseRepository;
use App\Traits\UserOwnedEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=ExerciseRepository::class)
 * @Gedmo\SoftDeleteable()
 */
class Exercise
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
     * @ORM\Column(type="string", length=255)
     * @Groups({"api", "workout_exercise_set", "workout", "workout_exercise"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exercises", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Groups({"api", "workout_exercise_set", "workout", "workout_exercise"})
     */
    private $statTemplate;

    /**
     * @ORM\OneToMany(targetEntity=WorkoutExercise::class, mappedBy="exercise", orphanRemoval=true)
     */
    private $workoutExercises;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api", "workout_exercise_set", "workout", "workout_exercise"})
     */
    private $sumStat = "";

    public function __construct()
    {
        $this->workouts = new ArrayCollection();
        $this->workoutExercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getStatTemplate(): ?string
    {
        return $this->statTemplate;
    }

    public function setStatTemplate(string $statTemplate): self
    {
        $this->statTemplate = $statTemplate;

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
            $workoutExercise->setExercise($this);
        }

        return $this;
    }

    public function removeWorkoutExercise(WorkoutExercise $workoutExercise): self
    {
        if ($this->workoutExercises->contains($workoutExercise)) {
            $this->workoutExercises->removeElement($workoutExercise);
            // set the owning side to null (unless already changed)
            if ($workoutExercise->getExercise() === $this) {
                $workoutExercise->setExercise(null);
            }
        }

        return $this;
    }

    public function getSumStat(): ?string
    {
        return $this->sumStat;
    }

    public function setSumStat(string $sumStat): self
    {
        $this->sumStat = $sumStat;

        return $this;
    }
}
