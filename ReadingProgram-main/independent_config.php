<?php
class DatabaseHelper {
    private $pdo;
    
    public function __construct() {
        try {
            // Your database connection parameters
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=readingprogram",
                "root",
                ""
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("DEBUG: Database connection successful");
        } catch (PDOException $e) {
            error_log("ERROR: Database connection failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function saveAllResults($data) {
        try {
            error_log("DEBUG: saveAllResults method called");
            
            // Check if table exists with all required fields
            $sql = "CREATE TABLE IF NOT EXISTS independent_results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                session_id VARCHAR(255) NOT NULL,
                
                -- Gates-MacGinitie Reading Test results
                gmrt_vocab_score DECIMAL(5,2) NOT NULL,
                gmrt_speed_score DECIMAL(5,2) NOT NULL,
                gmrt_comprehension_score DECIMAL(5,2) NOT NULL,
                gmrt_vocab_answers TEXT NOT NULL,
                gmrt_speed_answers TEXT NOT NULL,
                gmrt_comprehension_answers TEXT NOT NULL,
                gmrt_vocab_total INT NOT NULL,
                gmrt_speed_total INT NOT NULL,
                gmrt_comprehension_total INT NOT NULL,
                
                -- Independent reading activity results
                pretest_score DECIMAL(5,2) NOT NULL,
                activity_score DECIMAL(5,2) NOT NULL,
                posttest_score DECIMAL(5,2) NOT NULL,
                selected_story_index INT NOT NULL,
                selected_story_title VARCHAR(255) NOT NULL,
                pretest_answers TEXT NOT NULL,
                activity_answers TEXT NOT NULL,
                posttest_answers TEXT NOT NULL,
                total_time_minutes DECIMAL(8,2),
                
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $this->pdo->exec($sql);
            error_log("DEBUG: Table created or already exists");
            
            // FIXED: Use the correct table name 'independent_results' instead of 'complete_results'
            $sql = "INSERT INTO independent_results (
                session_id,
                gmrt_vocab_score, gmrt_speed_score, gmrt_comprehension_score,
                gmrt_vocab_answers, gmrt_speed_answers, gmrt_comprehension_answers,
                gmrt_vocab_total, gmrt_speed_total, gmrt_comprehension_total,
                pretest_score, activity_score, posttest_score,
                selected_story_index, selected_story_title, pretest_answers,
                activity_answers, posttest_answers, total_time_minutes
            ) VALUES (
                :session_id,
                :gmrt_vocab_score, :gmrt_speed_score, :gmrt_comprehension_score,
                :gmrt_vocab_answers, :gmrt_speed_answers, :gmrt_comprehension_answers,
                :gmrt_vocab_total, :gmrt_speed_total, :gmrt_comprehension_total,
                :pretest_score, :activity_score, :posttest_score,
                :selected_story_index, :selected_story_title, :pretest_answers,
                :activity_answers, :posttest_answers, :total_time_minutes
            )";
            
            $stmt = $this->pdo->prepare($sql);
            
            // Convert arrays to JSON
            $data['gmrt_vocab_answers'] = json_encode($data['gmrt_vocab_answers']);
            $data['gmrt_speed_answers'] = json_encode($data['gmrt_speed_answers']);
            $data['gmrt_comprehension_answers'] = json_encode($data['gmrt_comprehension_answers']);
            $data['pretest_answers'] = json_encode($data['pretest_answers']);
            $data['activity_answers'] = json_encode($data['activity_answers']);
            $data['posttest_answers'] = json_encode($data['posttest_answers']);
            
            error_log("DEBUG: About to execute insert statement");
            error_log("DEBUG: Data to insert: " . json_encode($data));
            
            $result = $stmt->execute($data);
            
            if ($result) {
                $insertId = $this->pdo->lastInsertId();
                error_log("DEBUG: Insert successful, ID: " . $insertId);
                return true;
            } else {
                error_log("ERROR: Insert failed");
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("ERROR: Database error in saveAllResults: " . $e->getMessage());
            return false;
        }
    }
    
    // Keep the old method for backward compatibility
    public function saveIndependentResults($data) {
        return $this->saveAllResults($data);
    }
}
?>