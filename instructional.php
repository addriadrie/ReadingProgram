<?php
    session_start();

    // Include the content file
    require_once 'content.php';

    // Initialize session variables if not set
    if (!isset($_SESSION['test_stage'])) {
        $_SESSION['test_stage'] = 'start';
        $_SESSION['pretest_answers'] = [];
        $_SESSION['posttest_answers'] = [];
        $_SESSION['pretest_score'] = 0;
        $_SESSION['posttest_score'] = 0;
        $_SESSION['selected_posttest'] = null;
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
                    $_SESSION['pretest_score'] = calculateScore($_SESSION['pretest_answers'], $instructionalContent[0]['comprehension']);
                    $_SESSION['test_stage'] = 'pretest_results';
                    break;
                    
                case 'select_posttest':
                    $_SESSION['selected_posttest'] = (int)$_POST['story_selection'];
                    $_SESSION['test_stage'] = 'posttest_combined';
                    $_SESSION['posttest_start_time'] = time();
                    break;
                    
                case 'submit_posttest':
                    $_SESSION['posttest_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['posttest_score'] = calculateScore($_SESSION['posttest_answers'], $instructionalContent[$_SESSION['selected_posttest']]['comprehension']);
                    
                    // Save results to CSV
                    saveResultsToCSV(
                        $_SESSION['pretest_score'],
                        $_SESSION['posttest_score'],
                        $_SESSION['selected_posttest'],
                        $_SESSION['pretest_answers'],
                        $_SESSION['posttest_answers']
                    );
                    
                    $_SESSION['test_stage'] = 'final_results';
                    break;
                    
                case 'reset_test':
                    session_unset();
                    session_destroy();
                    session_start();
                    $_SESSION['test_stage'] = 'start';
                    $_SESSION['pretest_answers'] = [];
                    $_SESSION['posttest_answers'] = [];
                    $_SESSION['pretest_score'] = 0;
                    $_SESSION['posttest_score'] = 0;
                    $_SESSION['selected_posttest'] = null;
                    header('Location: ' . $_SERVER['PHP_SELF']);
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
    function saveResultsToCSV($pretestScore, $posttestScore, $selectedStory, $pretestAnswers, $posttestAnswers) {
        $csvFile = 'instructional_results.csv';
        $fileExists = file_exists($csvFile);
        
        // Open file for appending
        $handle = fopen($csvFile, 'a');
        
        if ($handle === false) {
            error_log("Could not open CSV file for writing");
            return false;
        }
        
        // Add header row if file is new
        if (!$fileExists) {
            $header = [
                'Timestamp',
                'Session ID',
                'Pretest Score',
                'Posttest Score',
                'Score Improvement',
                'Selected Story Index',
                'Selected Story Title',
                'Pretest Answers',
                'Posttest Answers',
                'Total Time (minutes)'
            ];
            fputcsv($handle, $header);
        }
        
        // Calculate total time if timestamps are available
        $totalTime = 'N/A';
        if (isset($_SESSION['pretest_start_time'])) {
            $totalMinutes = round((time() - $_SESSION['pretest_start_time']) / 60, 2);
            $totalTime = $totalMinutes;
        }
        
        // Get story title
        global $instructionalContent;
        $storyTitle = isset($instructionalContent[$selectedStory]) ? 
                    $instructionalContent[$selectedStory]['title'] : 'Unknown';
        
        // Prepare data row
        $data = [
            date('Y-m-d H:i:s'),
            session_id(),
            $pretestScore,
            $posttestScore,
            $posttestScore - $pretestScore,
            $selectedStory,
            $storyTitle,
            json_encode($pretestAnswers),
            json_encode($posttestAnswers),
            $totalTime
        ];
        
        // Write data to CSV
        $result = fputcsv($handle, $data);
        fclose($handle);
        
        return $result !== false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Comprehension Test System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .timer.warning {
            background: #f39c12;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .test-layout {
            display: block;
            margin-top: 20px;
        }
        .passage-section {
            margin-bottom: 30px;
        }

        .passage-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .questions-section {
            margin-top: 20px;
        }

        .questions-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .content {
            padding: 0;
            margin: 0;
            border: none;
            background: transparent;
            border-radius: 0;
        }
        .question {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 3px solid #28a745;
        }
        .question h4 {
            margin-top: 0;
            color: #2c3e50;
        }
        .options {
            margin: 15px 0;
        }
        .options label {
            display: block;
            margin: 8px 0;
            padding: 8px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .options label:hover {
            background: #e9ecef;
        }
        .options input[type="radio"] {
            margin-right: 10px;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .btn-success {
            background: #27ae60;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-danger {
            background: #e74c3c;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .score {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .score.good {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .score.average {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .score.poor {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .story-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .story-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        .story-card:hover {
            border-color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .story-card input[type="radio"] {
            margin-right: 10px;
        }
        .story-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .instructions {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
        }
        .progress {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            color: #7f8c8d;
        }
        .submit-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .passage-section {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Display timer for timed sections
        if (in_array($_SESSION['test_stage'], ['pretest_combined', 'posttest_combined'])) {
            $timeLimit = ($_SESSION['test_stage'] === 'pretest_combined') ? 900 : 3000; // 15 min for pretest, 50 min for posttest
            $startTime = ($_SESSION['test_stage'] === 'pretest_combined') ? $_SESSION['pretest_start_time'] : $_SESSION['posttest_start_time'];
            
            $remainingTime = getRemainingTime($startTime, $timeLimit);
            $timerClass = $remainingTime <= 300 ? 'timer warning' : 'timer';
            
            echo '<div class="' . $timerClass . '" id="timer">' . formatTime($remainingTime) . '</div>';
        }
        ?>

        <?php
        switch ($_SESSION['test_stage']) {
            case 'start':
                ?>
                <h1>Reading Comprehension Test System</h1>
                <div class="instructions">
                    <h3>Welcome to the Reading Comprehension Test</h3>
                    <p>This test consists of two parts:</p>
                    <ul>
                        <li><strong>Pre-test:</strong> Read a passage and answer questions on the same page (15 minutes)</li>
                        <li><strong>Post-test:</strong> Select a story, read it and answer questions on the same page (50 minutes)</li>
                    </ul>
                    <p>The passage and questions will be displayed side by side for easier reference.</p>
                    <p>Please ensure you have a stable internet connection and will not be interrupted during the test.</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_pretest" class="btn btn-success">Start Pre-Test</button>
                </form>
                <?php
                break;

            case 'pretest_combined':
                $pretest = $instructionalContent[0];
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
                    <h3>Select a Story for Post-Test</h3>
                    <p>Choose one of the following stories to read for your post-test:</p>
                </div>
                <form method="post">
                    <div class="story-selection">
                        <?php foreach ($instructionalContent as $index => $story): ?>
                            <?php if ($story['type'] === 'posttest'): ?>
                                <div class="story-card">
                                    <label>
                                        <input type="radio" name="story_selection" value="<?php echo $index; ?>" required>
                                        <h3><?php echo htmlspecialchars($story['title']); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($story['content'], 0, 200)) . '...'; ?></p>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" name="action" value="select_posttest" class="btn btn-success">Start Post-Test</button>
                </form>
                <?php
                break;

            case 'posttest_combined':
                $posttest = $instructionalContent[$_SESSION['selected_posttest']];
                ?>
                <div class="progress">Post-Test (50 minutes)</div>
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
                $posttestClass = $_SESSION['posttest_score'] >= 80 ? 'good' : ($_SESSION['posttest_score'] >= 60 ? 'average' : 'poor');
                ?>
                <h2>Final Test Results</h2>
                <div class="score <?php echo $pretestClass; ?>">
                    Pre-Test Score: <?php echo $_SESSION['pretest_score']; ?>%
                </div>
                <div class="score <?php echo $posttestClass; ?>">
                    Post-Test Score: <?php echo $_SESSION['posttest_score']; ?>%
                </div>
                <div class="instructions">
                    <h3>Test Summary</h3>
                    <p><strong>Pre-Test:</strong> <?php echo $_SESSION['pretest_score']; ?>%</p>
                    <p><strong>Post-Test:</strong> <?php echo $_SESSION['posttest_score']; ?>%</p>
                    <p><strong>Improvement:</strong> <?php echo $_SESSION['posttest_score'] - $_SESSION['pretest_score']; ?>%</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="reset_test" class="btn btn-danger">Take Test Again</button>
                </form>
                <?php
                break;
        }
        ?>
    </div>

    <script>
        // Timer functionality
        <?php if (in_array($_SESSION['test_stage'], ['pretest_combined', 'posttest_combined'])): ?>
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