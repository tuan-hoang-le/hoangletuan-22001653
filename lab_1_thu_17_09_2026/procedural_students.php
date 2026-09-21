<?php
/* 
 * Calculate the average score of all students.
 */
function calculateAverageScore(array $students): float {
    if (empty($students)) {
        return 0.0;
    }

    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student['score'];
    }

    return $totalScore / count($students);
}

/* 
 * Determine the rank based on the student's score.
 */
function getRank(float $score): string {
    if ($score >= 8.0) {
        return "Excellent";
    } elseif ($score >= 6.5) {
        return "Good";
    } elseif ($score >= 5.0) {
        return "Average";
    } else {
        return "Poor";
    }
}

/* 
 * Display the information of a single student.
 */
function displayStudent(array $student): void {
    $rank = getRank($student['score']);
    echo "Name: {$student['name']} | Age: {$student['age']} | Score: {$student['score']} | Rank: {$rank}<br>";
}

/* 
 * Find and return the student with the highest score.
 */
function findBestStudent(array $students): ?array {
    if (empty($students)) return null;

    $bestStudent = $students[0];
    foreach ($students as $student) {
        if ($student['score'] > $bestStudent['score']) {
            $bestStudent = $student;
        }
    }

    return $bestStudent;
}

/* 
 * Find and return the student with the lowest score.
 */
function findWorstStudent(array $students): ?array {
    if (empty($students)) return null;

    $worstStudent = $students[0];
    foreach ($students as $student) {
        if ($student['score'] < $worstStudent['score']) {
            $worstStudent = $student;
        }
    }

    return $worstStudent;
}

/* 
 * Count the number of students who passed (score >= 5.0).
 */
function countPassedStudents(array $students): int {
    $count = 0;
    foreach ($students as $student) {
        if ($student['score'] >= 5.0) {
            $count++;
        }
    }

    return $count;
}

/* 
 * Find a student by their exact name.
 */
function findStudentByName(array $students, string $name): ?array {
    foreach ($students as $student) {
        if ($student['name'] === $name) {
            return $student;
        }
    }

    return null;
}

// -----------------------------------------
// MAIN EXECUTION for exercises 1, 2, and 3.
// -----------------------------------------

// Initialize the list of students.
$students = [
    ["name" => "Nguyen Van An", "age" => 20, "score" => 8.5],
    ["name" => "Tran Thi Binh", "age" => 21, "score" => 6.5],
    ["name" => "Le Van Cuong", "age" => 19, "score" => 4.5],
    ["name" => "Pham Thi Dung", "age" => 20, "score" => 7.5]
];

echo "--- All students information ---<br>";
foreach ($students as $student) {
    displayStudent($student);
}

echo "<br>--- Average score ---<br>";
echo "Average score: " . calculateAverageScore($students) . "<br>";

echo "<br>--- Best student ---<br>";
$bestStudent = findBestStudent($students);
if ($bestStudent) displayStudent($bestStudent);

echo "<br>--- Worst student ---<br>";
$worstStudent = findWorstStudent($students);
if ($worstStudent) displayStudent($worstStudent);

echo "<br>--- Passed students count ---<br>";
echo "Total passed: " . countPassedStudents($students) . "<br>";

echo "<br>--- Find student by name ('Tran Thi Binh') ---<br>";
$foundStudent = findStudentByName($students, "Tran Thi Binh");
if ($foundStudent) {
    displayStudent($foundStudent);
} else {
    echo "Student not found.<br>";
}
?>