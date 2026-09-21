<?php
/* 
 * Class Student representing a single student entity.
 */
class Student {
    // Encapsulated properties.
    private string $name;
    private int $age;
    private float $score;

    /* 
     * Constructor to initialize a new Student.
     */
    public function __construct(string $name, int $age, float $score) {
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }

    /* 
     * Get the student's score.
     */
    public function getScore(): float {
        return $this->score;
    }

    /* 
     * Determine the rank based on the student's score.
     */
    public function getRank(): string {
        if ($this->score >= 8.0) {
            return "Excellent";
        } elseif ($this->score >= 6.5) {
            return "Good";
        } elseif ($this->score >= 5.0) {
            return "Average";
        } else {
            return "Poor";
        }
    }

    /* 
     * Check if the student has passed.
     */
    public function isPassed(): bool {
        return $this->score >= 5.0;
    }

    /* 
     * Display the student's information.
     */
    public function display(): void {
        $rank = $this->getRank();
        $status = $this->isPassed() ? "Passed" : "Failed";
        echo "Name: {$this->name} | Age: {$this->age} | Score: {$this->score} | Rank: {$rank} | Status: {$status}<br>";
    }
}

/* 
 * Class StudentManager to handle operations on a collection of students.
 */
class StudentManager {
    // List of student objects.
    private array $students = [];

    /* 
     * Add a student to the manager's list.
     */
    public function addStudent(Student $student): void {
        $this->students[] = $student;
    }

    /* 
     * Iterate and display all students in the list.
     */
    public function displayAllStudents(): void {
        foreach ($this->students as $student) {
            $student->display();
        }
    }

    /* 
     * Find and return the student with the highest score.
     */
    public function getHighestScoreStudent(): ?Student {
        if (empty($this->students)) return null;

        $bestStudent = $this->students[0];
        foreach ($this->students as $student) {
            if ($student->getScore() > $bestStudent->getScore()) {
                $bestStudent = $student;
            }
        }

        return $bestStudent;
    }

    /* 
     * Count the total number of students who passed.
     */
    public function countPassedStudents(): int {
        $count = 0;
        foreach ($this->students as $student) {
            if ($student->isPassed()) {
                $count++;
            }
        }

        return $count;
    }

    /* 
     * Calculate the average score of all students in the class.
     */
    public function calculateClassAverage(): float {
        if (empty($this->students)) return 0.0;

        $totalScore = 0;
        foreach ($this->students as $student) {
            $totalScore += $student->getScore();
        }

        return $totalScore / count($this->students);
    }
}

// ------------------------------
// MAIN EXECUTION for exercise 4.
// ------------------------------

// Create individual student objects.
$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

// Initialize manager and populate the list.
$manager = new StudentManager();
$manager->addStudent($student1);
$manager->addStudent($student2);
$manager->addStudent($student3);
$manager->addStudent($student4);

echo "--- All students (OOP implementation) ---<br>";
$manager->displayAllStudents();

echo "<br>--- Highest score student ----<br>";
$bestStudent = $manager->getHighestScoreStudent();
if ($bestStudent) {
    $bestStudent->display();
}

echo "<br>--- Highest score student ---<br>";
$bestStudent = $manager->getHighestScoreStudent();
if ($bestStudent) {
    $bestStudent->display();
}

echo "<br>--- Passed students count ---<br>";
echo "Total passed: " . $manager->countPassedStudents() . "<br>";

echo "<br>--- Class average score ---<br>";
echo "Average score: " . $manager->calculateClassAverage() . "<br>";
?>