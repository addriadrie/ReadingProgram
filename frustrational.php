<?php
    session_start();

    // Include the content file
    require_once 'content.php';

    // Initialize session variables if not set
    if (!isset($_SESSION['test_stage'])) {
        $_SESSION['test_stage'] = 'start';
        $_SESSION['pretest_answers'] = [];
        $_SESSION['prevocab_answers'] = [];
        $_SESSION['postvocab_answers'] = [];
        $_SESSION['activity_answers'] = [];
        $_SESSION['posttest_answers'] = [];
        $_SESSION['pretest_score'] = 0;
        $_SESSION['prevocab_score'] = 0;
        $_SESSION['postvocab_score'] = 0;
        $_SESSION['activity_score'] = 0;
        $_SESSION['posttest_score'] = 0;
        $_SESSION['selected_posttest'] = null;
        $_SESSION['selected_activity'] = null;
    }

    // Handle form submissions - SINGLE SWITCH STATEMENT ONLY
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'start_pretest':
                    $_SESSION['test_stage'] = 'pretest';
                    $_SESSION['pretest_start_time'] = time();
                    break;
                    
                case 'start_pretest_questions':
                    $_SESSION['test_stage'] = 'pretest_questions';
                    $_SESSION['pretest_questions_start_time'] = time();
                    break;
                    
                case 'submit_pretest':
                    $_SESSION['pretest_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['pretest_score'] = calculateScore($_SESSION['pretest_answers'], $frustrationalContent[0]['comprehension']);
                    $_SESSION['test_stage'] = 'activity_selection';
                    $_SESSION['activity_start_time'] = time();
                    break;

                case 'select_activity':
                    $_SESSION['selected_activity'] = (int)$_POST['story_selection'];
                    $_SESSION['test_stage'] = 'prevocab_test';
                    break;

                case 'submit_prevocab':
                    $_SESSION['prevocab_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['prevocab_score'] = calculateVocabScore($_SESSION['prevocab_answers'], $frustrationalContent[$_SESSION['selected_activity']]['vocabulary']);
                    $_SESSION['test_stage'] = 'activity_reading';
                    break;
                
                case 'start_postvocab':
                    $_SESSION['test_stage'] = 'postvocab_test';
                    break;

                case 'submit_postvocab':
                    $_SESSION['postvocab_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['postvocab_score'] = calculateVocabScore($_SESSION['postvocab_answers'], $frustrationalContent[$_SESSION['selected_activity']]['vocabulary']);
                    $_SESSION['test_stage'] = 'activity_questions';
                    break;

                case 'submit_activity':
                    $_SESSION['activity_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['activity_score'] = calculateScore($_SESSION['activity_answers'], $frustrationalContent[$_SESSION['selected_activity']]['comprehension']);
                    $_SESSION['test_stage'] = 'posttest_selection';
                    break;
                    
                case 'select_posttest':
                    $_SESSION['selected_posttest'] = (int)$_POST['story_selection'];
                    $_SESSION['test_stage'] = 'posttest_reading';
                    $_SESSION['posttest_start_time'] = time();
                    break;
                    
                case 'start_posttest_questions':
                    $_SESSION['test_stage'] = 'posttest_questions';
                    break;
                    
                case 'submit_posttest':
                    $_SESSION['posttest_answers'] = $_POST['answers'] ?? [];
                    $_SESSION['posttest_score'] = calculateScore($_SESSION['posttest_answers'], $frustrationalContent[$_SESSION['selected_posttest']]['comprehension']);
                    
                    // Save results to CSV
                    saveResultsToCSV(
                        $_SESSION['pretest_score'],
                        $_SESSION['posttest_score'],
                        $_SESSION['selected_posttest'],
                        $_SESSION['pretest_answers'],
                        $_SESSION['posttest_answers'],
                        $_SESSION['prevocab_score'],
                        $_SESSION['postvocab_score'],
                        $_SESSION['activity_score']
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
                    $_SESSION['selected_activity'] = null; // Add this line
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

    // Function to calculate vocab score - FIXED LOGIC
    function calculateVocabScore($answers, $vocabulary) {
        $correct = 0;
        $vocabWords = array_keys($vocabulary);
        
        foreach ($answers as $index => $answer) {
            // Check if the selected answer matches the correct word for this index
            if (isset($vocabWords[$index]) && $answer === $vocabWords[$index]) {
                $correct++;
            }
        }
        return round(($correct / count($vocabulary)) * 100, 2);
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
    function saveResultsToCSV($pretestScore, $posttestScore, $selectedPosttest, $pretestAnswers, $posttestAnswers, $prevocabScore, $postvocabScore, $activityScore) {
        $csvFile = 'frustrational_results.csv';
        $fileExists = file_exists($csvFile);
        
        $handle = fopen($csvFile, 'a');
        
        if ($handle === false) {
            error_log("Could not open CSV file for writing");
            return false;
        }
        
        if (!$fileExists) {
            $header = [
                'Timestamp', 'Session ID', 'Pretest Score', 'Posttest Score', 'Score Improvement',
                'Pre-Vocab Score', 'Post-Vocab Score', 'Vocab Improvement', 'Activity Score',
                'Selected Activity Index', 'Selected Activity Title', 'Selected Posttest Index', 'Selected Posttest Title',
                'Pretest Answers', 'Posttest Answers', 'Total Time (minutes)'
            ];
            fputcsv($handle, $header);
        }
        
        $totalTime = 'N/A';
        if (isset($_SESSION['pretest_start_time'])) {
            $totalMinutes = round((time() - $_SESSION['pretest_start_time']) / 60, 2);
            $totalTime = $totalMinutes;
        }
        
        global $frustrationalContent;
        $activityTitle = isset($frustrationalContent[$_SESSION['selected_activity']]) ? 
                    $frustrationalContent[$_SESSION['selected_activity']]['title'] : 'Unknown';
        $posttestTitle = isset($frustrationalContent[$selectedPosttest]) ? 
                    $frustrationalContent[$selectedPosttest]['title'] : 'Unknown';
        
        $data = [
            date('Y-m-d H:i:s'), 
            session_id(), 
            $pretestScore, 
            $posttestScore, 
            $posttestScore - $pretestScore,
            $prevocabScore, 
            $postvocabScore, 
            $postvocabScore - $prevocabScore, 
            $activityScore,
            $_SESSION['selected_activity'],
            $activityTitle,
            $selectedPosttest, 
            $posttestTitle, 
            json_encode($pretestAnswers), 
            json_encode($posttestAnswers), 
            $totalTime
        ];
        
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
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    <title>Frustrational Reading Level</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
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
        .pretest-content {
            padding: 25px 0;
            margin: 20px 0;
            line-height: 1.8;
            font-size: 16px;
        }
        /* .content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        } */
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
    </style>
</head>
<body>
    <div class="container">
        <?php
        // 15 minutes total for entire pretest
        if ($_SESSION['test_stage'] === 'pretest') {
            $timeLimit = 900; // 15 minutes
            $startTime = $_SESSION['pretest_start_time'];
            $remainingTime = getRemainingTime($startTime, $timeLimit);
            $timerClass = $remainingTime <= 300 ? 'timer warning' : 'timer';
            echo '<div class="' . $timerClass . '" id="timer">' . formatTime($remainingTime) . '</div>';
        }

        // 35 minutes total for entire activity sequence
        if (in_array($_SESSION['test_stage'], ['activity_selection', 'prevocab_test', 'activity_reading', 'postvocab_test', 'activity_questions'])) {
            $timeLimit = 2100; // 35 minutes
            $startTime = $_SESSION['activity_start_time'];
            $remainingTime = getRemainingTime($startTime, $timeLimit);
            $timerClass = $remainingTime <= 300 ? 'timer warning' : 'timer';
            echo '<div class="' . $timerClass . '" id="timer">' . formatTime($remainingTime) . '</div>';
        }

        // 15 minutes total for entire posttest (reading + questions)
        if (in_array($_SESSION['test_stage'], ['posttest_reading', 'posttest_questions'])) {
            $timeLimit = 900; // 15 minutes
            $startTime = $_SESSION['posttest_start_time'];
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
                    <p>This test consists of multiple parts:</p>
                    <ul>
                        <li><strong>Pre-test:</strong> Read a passage and answer questions (15 minutes)</li>
                        <li><strong>Vocabulary Tests:</strong> Pre and post reading vocabulary assessments</li>
                        <li><strong>Activity:</strong> Read a story and answer questions (35 minutes total)</li>
                        <li><strong>Post-test:</strong> Select and read a story and answer questions (15 minutes)</li>
                    </ul>
                    <p>Please ensure you have a stable internet connection and will not be interrupted during the test.</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_pretest" class="btn btn-success">Start Pre-Test</button>
                </form>
                <?php
                break;

           case 'pretest':
                $pretest = $frustrationalContent[0];
                ?>
                <div class="progress">Pre-Test - Reading & Questions</div>
                
                <h2><?php echo htmlspecialchars($pretest['title']); ?></h2>
                <div class="passage-text">
                    <?php echo nl2br(htmlspecialchars($pretest['content'])); ?>
                </div>
                
                <h3>Answer the following questions based on the passage above:</h3>
                <form method="post">
                    <?php foreach ($pretest['comprehension'] as $index => $question): ?>
                        <div class="question">
                            <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                            <div class="options">
                                <?php foreach ($question['options'] as $option => $text): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                        <?php echo htmlspecialchars($text); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="submit_pretest" class="btn btn-success">Submit Pre-Test</button>
                </form>
                <?php
                break;

            case 'start_pretest':
                $_SESSION['test_stage'] = 'pretest'; 
                $_SESSION['pretest_start_time'] = time();
                break;

            case 'activity_selection':
                ?>
                <h2>Select Activity Story</h2>
                <div class="instructions">
                    <h3>Pre-Test Complete!</h3>
                    <p>Your pre-test score: <strong><?php echo $_SESSION['pretest_score']; ?>%</strong></p>
                    <p>Now choose a story for your main activity (vocabulary tests, reading, and comprehension questions):</p>
                </div>
                <form method="post">
                    <div class="story-selection">
                        <?php foreach ($frustrationalContent as $index => $story): ?>
                            <?php if ($story['type'] === 'activity'): ?>
                                <div class="story-card">
                                    <label>
                                        <input type="radio" name="story_selection" value="<?php echo $index; ?>" required>
                                        <h3><?php echo htmlspecialchars($story['title'] ?: 'Story ' . ($index + 1)); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($story['content'], 0, 200)) . '...'; ?></p>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" name="action" value="select_activity" class="btn btn-success">Start Activity</button>
                </form>
                <?php
                break;

            case 'prevocab_test':
                $activity = $frustrationalContent[$_SESSION['selected_activity']];
                ?>
                <div class="progress">Pre-Activity Vocabulary Test</div>
                <h2>Vocabulary Test - <?php echo htmlspecialchars($activity['title']); ?></h2>
                <p>Match each word with its correct definition:</p>
                <form method="post">
                    <?php 
                    $vocabWords = array_keys($activity['vocabulary']);
                    $vocabDefinitions = array_values($activity['vocabulary']);
                    
                    foreach ($vocabWords as $index => $word): ?>
                        <div class="question">
                            <h4>Word <?php echo $index + 1; ?>: <?php echo htmlspecialchars($word); ?></h4>
                            <div class="options">
                                <?php 
                                $shuffledDefs = $vocabDefinitions;
                                shuffle($shuffledDefs);
                                
                                foreach ($shuffledDefs as $definition): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $word; ?>" required>
                                        <?php echo htmlspecialchars($definition); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="submit_prevocab" class="btn">Continue to Reading</button>
                </form>
                <?php
                break;

            case 'activity_reading':
                $activity = $frustrationalContent[$_SESSION['selected_activity']];
                ?>
                <div class="progress">Activity Reading Phase</div>
                <h2><?php echo htmlspecialchars($activity['title']); ?></h2>
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($activity['content'])); ?>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_postvocab" class="btn">Proceed to Post-Vocabulary</button>
                </form>
                <?php
                break;

            case 'postvocab_test':
                $activity = $frustrationalContent[$_SESSION['selected_activity']];
                ?>
                <div class="progress">Post-Activity Vocabulary Test</div>
                <h2>Vocabulary Test (After Reading) - <?php echo htmlspecialchars($activity['title']); ?></h2>
                <p>Match each word with its correct definition:</p>
                <form method="post">
                    <?php 
                    $vocabWords = array_keys($activity['vocabulary']);
                    $vocabDefinitions = array_values($activity['vocabulary']);
                    
                    foreach ($vocabWords as $index => $word): ?>
                        <div class="question">
                            <h4>Word <?php echo $index + 1; ?>: <?php echo htmlspecialchars($word); ?></h4>
                            <div class="options">
                                <?php 
                                $shuffledDefs = $vocabDefinitions;
                                shuffle($shuffledDefs);
                                
                                foreach ($shuffledDefs as $definition): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $word; ?>" required>
                                        <?php echo htmlspecialchars($definition); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="submit_postvocab" class="btn">Continue to Questions</button>
                </form>
                <?php
                break;

            case 'activity_questions':
                $activity = $frustrationalContent[$_SESSION['selected_activity']];
                ?>
                <div class="progress">Activity Comprehension Questions</div>
                <h2>Reading Comprehension Questions - <?php echo htmlspecialchars($activity['title']); ?></h2>
                <form method="post">
                    <?php foreach ($activity['comprehension'] as $index => $question): ?>
                        <div class="question">
                            <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                            <div class="options">
                                <?php foreach ($question['options'] as $option => $text): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                        <?php echo htmlspecialchars($text); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="submit_activity" class="btn">Complete Activity</button>
                </form>
                <?php
                break;

            case 'posttest_selection':
                ?>
                <h2>Select Post-Test Story</h2>
                <div class="instructions">
                    <h3>Activity Complete!</h3>
                    <p>Now choose a story for your final test:</p>
                </div>
                <form method="post">
                    <div class="story-selection">
                        <?php foreach ($frustrationalContent as $index => $story): ?>
                            <?php if ($story['type'] === 'posttest'): ?>
                                <div class="story-card">
                                    <label>
                                        <input type="radio" name="story_selection" value="<?php echo $index; ?>" required>
                                        <h3><?php echo htmlspecialchars($story['title'] ?: 'Story ' . ($index + 1)); ?></h3>
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

            case 'posttest_reading':
                $posttest = $frustrationalContent[$_SESSION['selected_posttest']];
                ?>
                <div class="progress">Post-Test Reading Phase</div>
                <h2><?php echo htmlspecialchars($posttest['title']); ?></h2>
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($posttest['content'])); ?>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="start_posttest_questions" class="btn">Proceed to Questions</button>
                </form>
                <?php
                break;

            case 'posttest_questions':
                $posttest = $frustrationalContent[$_SESSION['selected_posttest']];
                ?>
                <div class="progress">Post-Test Questions Phase</div>
                <h2>Post-Test Questions</h2>
                <form method="post">
                    <?php foreach ($posttest['comprehension'] as $index => $question): ?>
                        <div class="question">
                            <h4>Question <?php echo $index + 1; ?>: <?php echo nl2br(htmlspecialchars($question['question'])); ?></h4>
                            <div class="options">
                                <?php foreach ($question['options'] as $option => $text): ?>
                                    <label>
                                        <input type="radio" name="answers[<?php echo $index; ?>]" value="<?php echo $option; ?>" required>
                                        <?php echo htmlspecialchars($text); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="action" value="submit_posttest" class="btn btn-success">Submit Post-Test</button>
                </form>
                <?php
                break;

            case 'final_results':
                ?>
                <h2>Final Test Results</h2>
                <div class="score">Pre-Test Score: <?php echo $_SESSION['pretest_score']; ?>%</div>
                <div class="score">Pre-Vocabulary Score: <?php echo $_SESSION['prevocab_score']; ?>%</div>
                <div class="score">Post-Vocabulary Score: <?php echo $_SESSION['postvocab_score']; ?>%</div>
                <div class="score">Activity Score: <?php echo $_SESSION['activity_score']; ?>%</div>
                <div class="score">Post-Test Score: <?php echo $_SESSION['posttest_score']; ?>%</div>
                <div class="instructions">
                    <h3>Test Summary</h3>
                    <p><strong>Overall Improvement:</strong> <?php echo $_SESSION['posttest_score'] - $_SESSION['pretest_score']; ?>%</p>
                    <p><strong>Vocabulary Improvement:</strong> <?php echo $_SESSION['postvocab_score'] - $_SESSION['prevocab_score']; ?>%</p>
                </div>
                <form method="post">
                    <button type="submit" name="action" value="reset_test" class="btn btn-danger">Take Test Again</button>
                </form>
                <?php
                break;

            default:
                echo '<p>Unknown test stage: ' . $_SESSION['test_stage'] . '</p>';
                break;
        }
        ?>
    </div>

    <script>
        // Timer functionality
        <?php if (in_array($_SESSION['test_stage'], ['pretest', 'activity_selection', 'prevocab_test', 'activity_reading', 'postvocab_test', 'activity_questions', 'posttest_reading', 'posttest_questions'])): ?>
        let remainingTime = <?php echo $remainingTime; ?>;
        const timerElement = document.getElementById('timer');

        function updateTimer() {
            if (remainingTime <= 0) {
                const currentStage = '<?php echo $_SESSION['test_stage']; ?>';
                
                if (currentStage === 'pretest') {
                    // Auto-submit pretest
                    const form = document.querySelector('form[method="post"]');
                    if (form) {
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'submit_pretest';
                            form.appendChild(actionInput);
                        }
                        form.submit();
                    }
                    
                } else if (currentStage === 'activity_selection') {
                    // Auto-select first available activity story
                    const firstActivityRadio = document.querySelector('input[name="story_selection"]:first-of-type');
                    if (firstActivityRadio) {
                        firstActivityRadio.checked = true;
                        const form = document.querySelector('form[method="post"]');
                        if (form) {
                            let actionInput = form.querySelector('input[name="action"]');
                            if (!actionInput) {
                                actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'action';
                                actionInput.value = 'select_activity';
                                form.appendChild(actionInput);
                            }
                            form.submit();
                        }
                    }
                    
                } else if (currentStage === 'prevocab_test') {
                    // Auto-submit pre-vocab test
                    const form = document.querySelector('form[method="post"]');
                    if (form) {
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'submit_prevocab';
                            form.appendChild(actionInput);
                        }
                        form.submit();
                    }
                    
                } else if (currentStage === 'activity_reading') {
                    // Auto-advance to post-vocab test
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'start_postvocab';
                    
                    form.appendChild(actionInput);
                    document.body.appendChild(form);
                    form.submit();
                    
                } else if (currentStage === 'postvocab_test') {
                    // Auto-submit post-vocab test
                    const form = document.querySelector('form[method="post"]');
                    if (form) {
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'submit_postvocab';
                            form.appendChild(actionInput);
                        }
                        form.submit();
                    }
                    
                } else if (currentStage === 'activity_questions') {
                    // Auto-submit activity questions
                    const form = document.querySelector('form[method="post"]');
                    if (form) {
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'submit_activity';
                            form.appendChild(actionInput);
                        }
                        form.submit();
                    }
                    
                } else if (currentStage === 'posttest_reading') {
                    // Auto-advance to posttest questions
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'start_posttest_questions';
                    
                    form.appendChild(actionInput);
                    document.body.appendChild(form);
                    form.submit();
                    
                } else if (currentStage === 'posttest_questions') {
                    // Auto-submit posttest
                    const form = document.querySelector('form[method="post"]');
                    if (form) {
                        let actionInput = form.querySelector('input[name="action"]');
                        if (!actionInput) {
                            actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action';
                            actionInput.value = 'submit_posttest';
                            form.appendChild(actionInput);
                        }
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
        
        // Auto-save answers (optional feature)
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // You can implement auto-save functionality here
                console.log('Answer saved:', this.name, this.value);
            });
        });
        
        // Prevent accidental page refresh during test
        <?php if ($_SESSION['test_stage'] !== 'start' && $_SESSION['test_stage'] !== 'final_results'): ?>
        let formSubmitting = false;

        // Track when forms are being submitted
        document.addEventListener('submit', function() {
            formSubmitting = true;
        });

        window.addEventListener('beforeunload', function(e) {
            // Don't show warning if user is submitting a form
            if (formSubmitting) {
                return;
            }
            
            // Only show warning for actual navigation away from page
            const message = 'Are you sure you want to leave? Your progress may be lost.';
            e.returnValue = message;
            return message;
        });
        <?php endif; ?>
    </script>
</body>
</html>