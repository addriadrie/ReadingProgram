<?php
    session_start();

    // Include the content file
    require_once 'content.php';
    require_once 'independent_config.php';
    $dbHelper = new DatabaseHelper();

    // Initialize session variables if not set
    if (!isset($_SESSION['test_stage'])) {
        $_SESSION['test_stage'] = 'start';
        $_SESSION['pretest_answers'] = [];
        $_SESSION['posttest_answers'] = [];
        $_SESSION['activity_answers'] = [];
        $_SESSION['pretest_score'] = 0;
        $_SESSION['posttest_score'] = 0;
        $_SESSION['activity_score'] = 0;
        $_SESSION['selected_activity'] = null;
    }

    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'start_pretest':
                    $_SESSION['test_stage'] = 'pretest_combined';
                    $_SESSION['pretest_start_time'] = time();
                    break;
                    
                case 'submit_pretest':
                    $_SESSION['pretest_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['pretest_score'] = calculateScore($_SESSION['pretest_answers'], $independentContent[0]['comprehension']);
                    $_SESSION['test_stage'] = 'pretest_results';
                    break;
                    
                case 'select_activity':
                 $_SESSION['selected_activity'] = (int)$_POST['story_selection'];
                    $_SESSION['test_stage'] = 'activity_combined';
                    $_SESSION['activity_start_time'] = time();
                    break;

                case 'complete_activity':
                    // Calculate activity score
                    $_SESSION['activity_answers'] = $_POST['activity_answers'] ?? [];
                    $_SESSION['activity_score'] = calculateScore($_SESSION['activity_answers'], $independentContent[$_SESSION['selected_activity']]['comprehension']);
                    $_SESSION['test_stage'] = 'activity_results';
                    break;
                    
                case 'view_posttest':
                    $_SESSION['test_stage'] = 'start_posttest';
                    break;
                    
                case 'start_posttest':
                    $_SESSION['test_stage'] = 'posttest_combined';
                    $_SESSION['posttest_start_time'] = time();
                    break;

                case 'submit_posttest':
                    error_log("DEBUG: Post-test submission started");
                    
                    $_SESSION['posttest_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['posttest_score'] = calculateScore($_SESSION['posttest_answers'], $independentContent[0]['comprehension']);
                    
                    error_log("DEBUG: Post-test score calculated: " . $_SESSION['posttest_score']);
                    error_log("DEBUG: About to save results to database");
                    
                    // Get Gates-MacGinitie results from session
                    $gmrtResults = $_SESSION['gmrt_test_results'] ?? null;
                    
                    if ($gmrtResults) {
                        error_log("DEBUG: GMRT results found in session");
                        
                        // Save ALL results to database (GMRT + Independent reading activity)
                        $saveResult = saveResultsToDatabase(
                            $dbHelper,
                            // Gates-MacGinitie Reading Test results (from test.php)
                            $gmrtResults['gmrt_vocab_score'] ?? 0,
                            $gmrtResults['gmrt_speed_score'] ?? 0, 
                            $gmrtResults['gmrt_comprehension_score'] ?? 0,
                            $gmrtResults['gmrt_vocab_answers'] ?? [],
                            $gmrtResults['gmrt_speed_answers'] ?? [],
                            $gmrtResults['gmrt_comprehension_answers'] ?? [],
                            
                            // Independent reading activity results (from independent.php)
                            $_SESSION['pretest_score'],
                            $_SESSION['activity_score'],
                            $_SESSION['posttest_score'],
                            $_SESSION['pretest_answers'],
                            $_SESSION['activity_answers'],
                            $_SESSION['posttest_answers'],
                            
                            $_SESSION['selected_activity']
                        );
                        
                        error_log("DEBUG: Save result: " . ($saveResult ? 'SUCCESS' : 'FAILED'));
                        
                        if (!$saveResult) {
                            error_log("ERROR: Failed to save results to database");
                        }
                    } else {
                        error_log("ERROR: No GMRT results found in session");
                        // You might want to handle this case - maybe save only independent.php results
                        // or redirect back to test.php
                    }
                    
                    $_SESSION['test_stage'] = 'final_results';
                    break;
                    
                case 'complete_reset':
                    session_unset();
                    session_destroy();
                    header('Location: test.php');
                    exit;
                    break; 
            }
        }
    }

    // Function to calculate score
    function calculateScore($answers, $questions) {
        $correct = 0;
        foreach ($questions as $index => $question) {
            if (isset($answers[$index]) && $answers[$index] === $question['answer']) {
                $correct++;
            }
        }
        return round(($correct / count($questions)) * 100, 2);
    }

    // Function to get remaining time
    function getRemainingTime($startTime, $duration) {
        $elapsed = time() - $startTime;
        $remaining = $duration - $elapsed;
        return max(0, $remaining);
    }

    // Function to format time
    function formatTime($seconds) {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    // Function to handle time out
    function isTimeExpired($startTime, $duration) {
        return (time() - $startTime) > $duration;
    }

    // Function to auto-save results
    function saveResultsToDatabase(
        $dbHelper,
        $gmrtVocabScore, $gmrtSpeedScore, $gmrtComprehensionScore,
        $gmrtVocabAnswers, $gmrtSpeedAnswers, $gmrtComprehensionAnswers,
        $pretestScore, $activityScore, $posttestScore,
        $pretestAnswers, $activityAnswers, $posttestAnswers,
        $selectedActivity
    ) {
        // Debug the selectedActivity variable
        error_log("DEBUG: selectedActivity type: " . gettype($selectedActivity));
        error_log("DEBUG: selectedActivity value: " . json_encode($selectedActivity));
        
        // Handle different types of selectedActivity
        $storyIndex = 0;
        $storyTitle = 'Unknown';
        
        if (is_array($selectedActivity)) {
            $storyIndex = $selectedActivity['index'] ?? 0;
            $storyTitle = $selectedActivity['title'] ?? 'Unknown';
        } elseif (is_string($selectedActivity)) {
            // If it's just a string (story title)
            $storyTitle = $selectedActivity;
            $storyIndex = 0; // Default index
        } elseif (is_numeric($selectedActivity)) {
            // If it's just a number (story index)
            $storyIndex = $selectedActivity;
            $storyTitle = 'Story ' . $selectedActivity;
        }
        
        $data = [
            'session_id' => session_id(),
            
            // Gates-MacGinitie Reading Test results
            'gmrt_vocab_score' => $gmrtVocabScore,
            'gmrt_speed_score' => $gmrtSpeedScore,
            'gmrt_comprehension_score' => $gmrtComprehensionScore,
            'gmrt_vocab_answers' => $gmrtVocabAnswers,
            'gmrt_speed_answers' => $gmrtSpeedAnswers,
            'gmrt_comprehension_answers' => $gmrtComprehensionAnswers,
            'gmrt_vocab_total' => $_SESSION['gmrt_test_results']['gmrt_vocab_total'] ?? 0,
            'gmrt_speed_total' => $_SESSION['gmrt_test_results']['gmrt_speed_total'] ?? 0,
            'gmrt_comprehension_total' => $_SESSION['gmrt_test_results']['gmrt_comprehension_total'] ?? 0,
            
            // Independent reading activity results
            'pretest_score' => $pretestScore,
            'activity_score' => $activityScore,
            'posttest_score' => $posttestScore,
            'selected_story_index' => $storyIndex,
            'selected_story_title' => $storyTitle,
            'pretest_answers' => $pretestAnswers,
            'activity_answers' => $activityAnswers,
            'posttest_answers' => $posttestAnswers,
            'total_time_minutes' => null // Set to null since you don't have calculateTotalTime
        ];
        
        error_log("DEBUG: Final data array: " . json_encode($data));
        
        return $dbHelper->saveAllResults($data);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.png" type="image/x-icon">
    <title>Independent Reading Level</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php
        // Display timer for timed sections
        if (in_array($_SESSION['test_stage'], ['pretest_combined', 'activity_combined', 'posttest_combined'])) {
            if ($_SESSION['test_stage'] === 'pretest_combined') {
                $timeLimit = 900; // 15 minutes
                $startTime = $_SESSION['pretest_start_time'];
            } elseif ($_SESSION['test_stage'] === 'activity_combined') {
                $timeLimit = 2100; // 35 minutes
                $startTime = $_SESSION['activity_start_time'];
            } elseif ($_SESSION['test_stage'] === 'posttest_combined') {
                $timeLimit = 900; // 15 minutes
                $startTime = $_SESSION['posttest_start_time'];
            }
            
            $remainingTime = getRemainingTime($startTime, $timeLimit);
            $timerClass = $remainingTime <= 300 ? 'timer warning' : 'timer';
            
            echo '<div class="' . $timerClass . '" id="timer">' . formatTime($remainingTime) . '</div>';
        }
        ?>

        <?php
        switch ($_SESSION['test_stage']) {
            case 'start':
                ?>
                <div class="instructions">
                    <h3>Welcome to the Independent Reading Level Test</h3>
                    <p>Based on your previous assessment, you've been identified as a independent reader. This specialized test will help us better understand your reading abilities and provide targeted support.</p>
                    
                    <p>This test consists of three parts:</p>
                    <ul>
                        <li><strong>Pre-test:</strong> Read a passage and answer questions (15 minutes)</li>
                        <li><strong>Activity:</strong> Select a story, read it and answer questions (35 minutes)</li>
                        <li><strong>Post-test:</strong> Read the same passage as the pretest, and answer questions (15 minutes)</li>
                    </ul>
                    <p>The passage and questions will be displayed side by side for easier reference.</p>
                    <p>Please ensure you have a stable internet connection and will not be interrupted during the test.</p>
                    
                    <div class="alert alert-info">
                        <strong>Note:</strong> You've already completed the initial Gates-MacGinitie Reading Test. This assessment will provide additional insights into your reading level.
                    </div>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_pretest" class="btn btn-success">Start Pre-Test</button>
                </form>
                <?php
                break;

            case 'pretest_combined':
                $pretest = $independentContent[0];
                ?>
                <div class="progress">Pre-Test (15 minutes)</div>
                <h2>Pre-Test: <?php echo htmlspecialchars($pretest['title']); ?></h2>
                
                <div class="test-layout">
                    <!-- Passage Section -->
                    <div class="passage-section">
                        <h3>Reading Passage</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($pretest['content'])); ?>
                        </div>
                    </div>
                    
                    <!-- Questions Section -->
                    <div class="questions-section">
                        <h3>Questions</h3>
                        <div class="instructions">
                            <p>Answer the following questions based on the story you just read.</p>
                        </div>
                        <form method="post" id="pretest-form">
                            <?php foreach ($pretest['comprehension'] as $index => $question): ?>
                                <div class="question">
                                    <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                                    <div class="options">
                                        <?php 
                                        $shuffledOptions = $question['options'];
                                        $keys = array_keys($shuffledOptions);
                                        shuffle($keys);
                                        $shuffledOptions = array_merge(array_flip($keys), $shuffledOptions);
                                        
                                        foreach ($shuffledOptions as $option => $text): ?>
                                            <label>
                                                <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                                <?php echo htmlspecialchars($text); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="submit-section">
                                <button type="submit" name="action" value="submit_pretest" class="btn btn-success">Submit Pre-Test</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;

            case 'pretest_results':
                $scoreClass = $_SESSION['pretest_score'] >= 80 ? 'good' : ($_SESSION['pretest_score'] >= 60 ? 'average' : 'poor');
                ?>
                <h2>Pre-Test Results</h2>
                <div class="score <?php echo $scoreClass; ?>">
                    Your Pre-Test Score: <?php echo $_SESSION['pretest_score']; ?>%
                </div>
                <div class="instructions">
                    <h3>Select a Story for Activity</h3>
                    <p>Choose one of the following stories to read as an activity:</p>
                </div>
                <form method="post">
                    <div class="story-selection">
                        <?php foreach ($independentContent as $index => $story): ?>
                            <?php if ($story['type'] === 'activity'): ?>
                                <div class="story-card">
                                    <label>
                                        <input type="radio" name="story_selection" value="<?php echo $index; ?>" required>
                                        <h3><?php echo htmlspecialchars($story['title']); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($story['content'], 0, 150)); ?>...</p>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" name="action" value="select_activity" class="btn btn-success">Start Activity</button>
                </form>
                <?php
                break;
            
            case 'activity_combined':
                $activity = $independentContent[$_SESSION['selected_activity']];
                ?>
                <div class="progress">Activity - Practice Reading</div>
                <h2>Activity: <?php echo htmlspecialchars($activity['title']); ?></h2>
                
                <div class="test-layout">
                    <!-- Passage Section -->
                    <div class="passage-section">
                        <h3>Reading Passage</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($activity['content'])); ?>
                        </div>
                    </div>
                    
                    <!-- Questions Section -->
                    <div class="questions-section">
                        <h3>Questions</h3>
                        <div class="instructions">
                            <p>Answer the following questions based on the story you just read.</p>
                        </div>
                        
                        <form method="post" id="activity-form">
                            <?php foreach ($activity['comprehension'] as $index => $question): ?>
                                <div class="question">
                                    <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                                    <div class="options">
                                        <?php 
                                        $shuffledOptions = $question['options'];
                                        $keys = array_keys($shuffledOptions);
                                        shuffle($keys);
                                        $shuffledOptions = array_merge(array_flip($keys), $shuffledOptions);
                                        
                                        foreach ($shuffledOptions as $option => $text): ?>
                                            <label>
                                                <input type="radio" name="activity_answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                                <?php echo htmlspecialchars($text); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" name="action" value="complete_activity" class="btn btn-success">Proceed to Post-Test</button>
                            
                        </form>
                    </div>
                </div>
                <?php
                break;

            case 'activity_results':
                $activityScoreClass = $_SESSION['activity_score'] >= 80 ? 'good' : ($_SESSION['activity_score'] >= 60 ? 'average' : 'poor');
                ?>
                <h2>Activity Results</h2>
                <div class="score <?php echo $activityScoreClass; ?>">
                    Your Activity Score: <?php echo $_SESSION['activity_score']; ?>%
                </div>
                <div class="instructions">
                    <h3>Activity Complete</h3>
                    <p>You have completed the reading activity with a score of <?php echo $_SESSION['activity_score']; ?>%.</p>
                    <p>Now you can proceed to the post-test, which will be based on the pre-test story.</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="view_posttest" class="btn btn-success">View Post-Test</button>
                </form>
                <?php
                break;

            case 'start_posttest':
                ?>
                <h2>Ready for Post-Test</h2>
                <div class="instructions">
                    <h3>Post-Test Instructions</h3>
                    <p>You will now take a post-test based on the story you just read during the pre-test activity.</p>
                    <p>You have 15 minutes to complete this test.</p>
                    <p>The story and questions will be displayed side by side for easier reference.</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_posttest" class="btn btn-success">Start Post-Test</button>
                </form>
                <?php
                break;

           case 'posttest_combined':
                $posttest = $independentContent[0];
                ?>
                <div class="progress">Post-Test (15 minutes)</div>
                <h2>Post-Test: <?php echo htmlspecialchars($posttest['title']); ?></h2>
                
                <div class="test-layout">
                    <!-- Passage Section -->
                    <div class="passage-section">
                        <h3>Reading Passage</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($posttest['content'])); ?>
                        </div>
                    </div>
                    
                    <!-- Questions Section -->
                    <div class="questions-section">
                        <h3>Questions</h3>
                        <div class="instructions">
                            <p>Answer the following questions based on the story you just read.</p>
                        </div>
                        <form method="post" id="posttest-form">
                            <?php foreach ($posttest['comprehension'] as $index => $question): ?>
                                <div class="question">
                                    <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                                    <div class="options">
                                        <?php 
                                        $shuffledOptions = $question['options'];
                                        $keys = array_keys($shuffledOptions);
                                        shuffle($keys);
                                        $shuffledOptions = array_merge(array_flip($keys), $shuffledOptions);
                                        
                                        foreach ($shuffledOptions as $option => $text): ?>
                                            <label>
                                                <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                                <?php echo htmlspecialchars($text); ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="submit-section">
                                <button type="submit" name="action" value="submit_posttest" class="btn btn-success">Submit Post-Test</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                break;

            case 'final_results':
               $pretestClass = $_SESSION['pretest_score'] >= 80 ? 'good' : ($_SESSION['pretest_score'] >= 60 ? 'average' : 'poor');
                $activityClass = $_SESSION['activity_score'] >= 80 ? 'good' : ($_SESSION['activity_score'] >= 60 ? 'average' : 'poor');
                $posttestClass = $_SESSION['posttest_score'] >= 80 ? 'good' : ($_SESSION['posttest_score'] >= 60 ? 'average' : 'poor');
                $improvement = $_SESSION['posttest_score'] - $_SESSION['pretest_score'];
                $improvementClass = $improvement > 0 ? 'good' : ($improvement < 0 ? 'poor' : 'average');
                ?>
                <h2>Final Test Results</h2>
                
                <div class="results-summary">
                    <div class="score <?php echo $pretestClass; ?>">
                        Pre-Test Score: <?php echo $_SESSION['pretest_score']; ?>%
                    </div>
                    <div class="score <?php echo $activityClass; ?>">
                        Activity Score: <?php echo $_SESSION['activity_score']; ?>%
                    </div>
                    <div class="score <?php echo $posttestClass; ?>">
                        Post-Test Score: <?php echo $_SESSION['posttest_score']; ?>%
                    </div>
                </div>
                
                <div class="instructions">
                    <h3>Performance Summary</h3>
                    <div class="score <?php echo $improvementClass; ?>">
                        <strong>Overall Improvement:</strong> <?php echo ($improvement > 0 ? '+' : '') . $improvement; ?>%
                    </div>
                    <p><strong>Activity to Post-Test Change:</strong> <?php echo $_SESSION['posttest_score'] - $_SESSION['activity_score']; ?>%</p>
                    
                    <?php if ($improvement > 0): ?>
                        <div class="alert alert-success">
                            <strong>Great improvement!</strong> Your post-test score shows significant progress compared to your pre-test.
                        </div>
                    <?php elseif ($improvement < 0): ?>
                        <div class="alert alert-warning">
                            <strong>Don't worry!</strong> Sometimes scores can vary. The important thing is that you completed the full assessment.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <strong>Consistent performance!</strong> Your scores remained stable throughout the assessment.
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="button-group">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="action" value="complete_reset" class="btn btn-danger">Take Test Again</button>
                    </form>
                </div>
                <?php
                break;
        }
        ?>
    </div>

    <script>
        // Timer functionality
        <?php if (in_array($_SESSION['test_stage'], ['pretest_combined', 'activity_combined', 'posttest_combined'])): ?>
        let remainingTime = <?php echo $remainingTime; ?>;
        const timerElement = document.getElementById('timer');
        
        function updateTimer() {
            if (remainingTime <= 0) {
                // Auto-submit when time runs out
                const currentStage = '<?php echo $_SESSION['test_stage']; ?>';
                
                if (currentStage === 'pretest_combined') {
                    const form = document.getElementById('pretest-form');
                    if (form) {
                        form.submit();
                    }
                } else if (currentStage === 'activity_combined') {
                    const form = document.getElementById('activity-form');
                    if (form) {
                        form.submit();
                    }
                } else if (currentStage === 'posttest_combined') {
                    const form = document.getElementById('posttest-form');
                    if (form) {
                        form.submit();
                    }
                }
                return;
            }
            
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Add warning class when 5 minutes left
            if (remainingTime <= 300) {
                timerElement.className = 'timer warning';
            }
            
            remainingTime--;
        }
        
        // Update timer every second
        setInterval(updateTimer, 1000);
        <?php endif; ?>
        
        // Auto-save answers functionality
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Answer saved:', this.name, this.value);
                // You can implement local storage auto-save here if needed
            });
        });
        
        // Prevent accidental page refresh during test
        <?php if ($_SESSION['test_stage'] !== 'start' && $_SESSION['test_stage'] !== 'final_results'): ?>
        let formSubmitting = false;

        document.addEventListener('submit', function() {
            formSubmitting = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formSubmitting) {
                return;
            }
            
            const message = 'Are you sure you want to leave? Your progress may be lost.';
            e.returnValue = message;
            return message;
        });
        <?php endif; ?>
    </script>
</body>
</html>