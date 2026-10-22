<?php
/* 
 * Class Student represents an individual student and encapsulates grading logic.
 */
class Student {
    private string $name;
    private float $score;

    /* 
     * Constructor to initialize student information.
     */
    public function __construct(string $name, float $score) {
        $this->name = $name;
        $this->score = $score;
    }

    /* 
     * Get student name.
     */
    public function getName(): string {
        return $this->name;
    }

    /* 
     * Get student score.
     */
    public function getScore(): float {
        return $this->score;
    }

    /* 
     * Determine academic rank based on score.
     */
    public function getRank(): string {
        if ($this->score >= 8.0) {
            return "Giỏi";
        } elseif ($this->score >= 6.5) {
            return "Khá";
        } elseif ($this->score >= 5.0) {
            return "Trung bình";
        } else {
            return "Yếu";
        }
    }

    /* 
     * Display student information using echo.
     */
    public function display(): void {
        echo "Họ và tên: " . htmlspecialchars($this->name) . "<br>";
        echo "Điểm số: " . number_format($this->score, 1) . "<br>";
        echo "Xếp loại: " . htmlspecialchars($this->getRank()) . "<br>";
    }
}

// --------------
// MAIN EXECUTION
// --------------

// Declare student variables.
$name = "Nguyễn Văn An";
$score = 8.5;

// Instantiate student object and display results.
$student = new Student($name, $score);
$student->display();