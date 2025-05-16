<?php
include("stories.php");

$storyKey = $_GET['story'] ?? null;

// Step control
$step = $_POST['step'] ?? 'vocab';

// Check if a valid storyKey is provided
$storyIndex = $storyKey !== null && is_numeric($storyKey) && isset($easy[$storyKey]) ? $storyKey : 0;
$story = $easy[$storyIndex];

// Define vocab and definitions for the chosen story
$prevocab = $story['pre-vocab'];
$words = array_keys($prevocab);
$definitions = array_values($prevocab);

// Timer settings
$readingTime = $_POST['readingTime'] ?? $story['readingTime'] ?? 300; // Default 5 minutes in seconds

// Store user answers if this is a submission
$vocabAnswers = $_POST['answer'] ?? [];
$vocabScore = 0;

// Calculate score for vocabulary matching
if (!empty($vocabAnswers)) {
    foreach ($vocabAnswers as $index => $answer) {
        if ($answer === $words[$index]) {
            $vocabScore++;
        }
    }
}

// Process post-vocab answers
$blankAnswers = [];
$blankScore = 0;
$totalBlanks = 0;

if ($step === 'postvocab-submit') {
    $answers = $story["post-vocab"]["answers"];
    $totalBlanks = count($answers);

    for ($i = 0; $i < $totalBlanks; $i++) {
        $position = $i + 1;
        $userAnswer = $_POST["blank_$position"] ?? '';
        $correctAnswer = $answers[$i];
        $blankAnswers[$position] = [
            'user' => $userAnswer,
            'correct' => $correctAnswer,
            'isCorrect' => ($userAnswer === $correctAnswer)
        ];

        if ($userAnswer === $correctAnswer) {
            $blankScore++;
        }
    }

    // Move to comprehension step after processing
    $step = 'comprehension';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    <title>Reading Program</title>

    <!-- css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- font -->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Chewy" rel="stylesheet">


    <!-- stylesheet -->
    <style>
        body {
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 2.5em;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            /* Increase from 800px to 90% of viewport width */
            width: 90%;
            max-height: 90vh;
            /* Increase from 85vh to 90vh */
            overflow-y: auto;
        }

        .vocab-title,
        .section-title,
        .story-title {
            font-family: 'Chewy', cursive;
            color: #ffbd59;
            text-shadow: -1px -1px 0 #ffffff, 1px -1px 0 #ffffff, -1px 1px 0 #ffffff, 1px 1px 0 #ffffff, 0 0 5px #ffbd59, 0 0 5px #fff199;
            text-align: center;
            margin-bottom: 1rem;
        }

        .vocab-title,
        .section-title {
            font-size: 3.5rem;
            font-weight: bolder;
        }

        .story-title {
            font-size: 4rem;
            font-weight: bolder;
        }

        /* Vocab matching styles */
        ol {
            padding-left: 23%;
        }

        li {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            /* Increase from 1rem */
        }

        select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #dddddd;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-width: 220px;
            /* Increase from 180px */
            cursor: pointer;
            background-color: white;
        }

        select:hover {
            border-color: #ffbd59;
            box-shadow: 0 0 5px rgba(255, 189, 89, 0.5);
        }

        select:focus {
            outline: none;
            border-color: #ffbd59;
            box-shadow: 0 0 8px rgba(255, 189, 89, 0.8);
        }

        button {
            background-color: #ffbd59;
            color: white;
            border: none;
            padding: 12px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #ffa726;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .definition {
            flex: 1;
            font-size: 1.1rem;
        }

        /* Story styles */
        .story-content {
            display: flex;
            flex-direction: row;
            /* Change from column to row */
            align-items: flex-start;
            text-align: justify;
            line-height: 1.6;
            font-size: 1.5rem;
            gap: 2rem;
            /* Add gap between the two sides */
        }

        /* Create a text column container for all paragraphs */
        .text-column {
            width: 75%;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        /* Create an image column container for all images */
        .image-column {
            width: 25%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: sticky;
            top: 1rem;
        }

        .story-paragraph {
            margin-bottom: 0;
            /* Remove bottom margin since we use gap */
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 8px;
            text-align: justify;
        }

        .story-image-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin: 0;
            /* Remove margin since we use gap */
        }

        .story-image {
            max-width: 100%;
            /* Change from 300px to 100% of container */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Make definition list more spacious */
        #definitionList {
            max-width: 100%;
            padding-right: 2em;
        }

        /* Fill in the blanks styles */
        .paragraph {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .blanks-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .blank-question {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 8px;
            font-size: 1.25rem;
        }

        .choices {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-left: 1rem;
        }

        .choice-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .choice-label:hover {
            background-color: rgba(255, 189, 89, 0.2);
        }

        input[type="radio"] {
            cursor: pointer;
        }

        input[type="radio"]:checked+span {
            font-weight: bold;
            color: #ff9800;
        }

        /* Comprehension questions styles */
        .questions-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .question-item {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 8px;
        }

        .answer-textarea {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            border: 2px solid #dddddd;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .answer-textarea:focus {
            outline: none;
            border-color: #ffbd59;
            box-shadow: 0 0 8px rgba(255, 189, 89, 0.8);
        }

        /* Timer styles */
        .timer-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff9800;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .timer-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .timer-button {
            background-color: #ffbd59;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .timer-button:hover {
            background-color: #ffa726;
        }

        /* Toast styling */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            max-width: 300px;
            display: none;
        }

        .toast-header {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #ff9800;
        }

        .toast-body {
            margin-bottom: 15px;
        }

        .toast-buttons {
            display: flex;
            gap: 10px;
        }

        .toast-button {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .toast-primary {
            background-color: #ffbd59;
            color: white;
        }

        .toast-secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .toast-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #timerSetup {
            margin-top: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #timerSetup label {
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        #timerSetup input {
            padding: 8px;
            border-radius: 6px;
            border: 2px solid #dddddd;
            text-align: center;
            width: 100px;
            margin-bottom: 1rem;
        }

        /* Blur effect for content */
        .blurred-content {
            filter: blur(5px);
            user-select: none;
            pointer-events: none;
        }

        /* Finish button */
        .finish-button-container {
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .finish-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .finish-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Fill in the blanks activity styling - like in the screenshot */
        .word-bank {
            background-color: #ebf5e1;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .word-item {
            display: inline-block;
            background-color: #ffffff;
            color: #008000;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 20px;
            border: 2px solid #008000;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .word-item:hover {
            background-color: #e1f0d8;
            transform: scale(1.05);
        }

        .fill-blanks-text {
            font-size: 1.4rem;
            line-height: 2.2;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .blank-input {
            border: none;
            border-bottom: 2px solid #ffbd59;
            background-color: transparent;
            padding: 5px;
            min-width: 120px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            color: #0066cc;
        }

        .blank-input:focus {
            outline: none;
            border-bottom: 2px solid #ff9800;
            background-color: rgba(255, 189, 89, 0.1);
        }

        /* Score display */
        .score-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center;
            border: 2px solid #28a745;
        }

        .score-title {
            font-size: 1.5rem;
            color: #28a745;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .score-details {
            font-size: 1.2rem;
        }

        /* Correct and incorrect answer highlighting */
        .correct-answer {
            color: #28a745;
            font-weight: bold;
        }

        .incorrect-answer {
            color: #dc3545;
            text-decoration: line-through;
        }

        .answer-feedback {
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php if ($step === 'vocab'): ?>
            <p class="vocab-title">Vocabulary Matching</p>
            <form method="post" id="vocabForm">
                <input type="hidden" name="step" value="story">
                <ol id="definitionList">
                    <?php foreach ($definitions as $index => $definition): ?>
                        <li>
                            <select name="answer[]" class="word-select" onchange="updateOptions()" required>
                                <option value=""></option>
                                <?php foreach ($words as $word): ?>
                                    <option value="<?php echo $word; ?>"><?php echo $word; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="definition"><?php echo $definition; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
                <button type="submit">Submit</button>
            </form>

        <?php elseif ($step === 'story'): ?>
            <div class="story">
                <h2 class="story-title"><?php echo $story['title']; ?></h2>

                <div id="timerSetup">
                    <label for="readingTimeInput">Set Reading Time (minutes):</label>
                    <input type="number" id="readingTimeInput" min="1" max="30" value="<?php echo ceil($readingTime / 60); ?>">
                    <button type="button" id="startReadingBtn" class="timer-button">Start Reading</button>
                </div>

                <div class="timer-container" style="display: none;">
                    <div class="timer" id="timer">05:00</div>
                    <div class="timer-controls">
                        <button type="button" class="timer-button" id="pauseTimerBtn">Pause</button>
                        <button type="button" class="timer-button" id="resetTimerBtn">Reset</button>
                    </div>
                </div>

                <div class="story-content" id="storyContent" style="display: none;">
                    <div class="text-column">
                        <?php
                        // Process all paragraphs for left column
                        if (!empty($story['content_structured'])) {
                            foreach ($story['content_structured'] as $item) {
                                if ($item['type'] === 'paragraph') {
                                    echo '<div class="story-paragraph">' . $item['content'] . '</div>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="image-column">
                        <?php
                        // Process all images for right column
                        if (!empty($story['content_structured'])) {
                            foreach ($story['content_structured'] as $item) {
                                if ($item['type'] === 'image') {
                                    echo '<div class="story-image-container">
                                        <img src="' . $item['src'] . '" alt="' . ($item['alt'] ?? 'Story image') . '" class="story-image">
                                    </div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="finish-button-container" style="display: none;" id="finishButtonContainer">
                    <button type="button" class="finish-button" id="finishReadingBtn">Finish Reading</button>
                </div>

                <form method="post" id="continueForm" style="display: none;">
                    <input type="hidden" name="step" value="postvocab">
                    <button type="submit">Continue</button>
                </form>
            </div>

            <!-- Toast notification for timer -->
            <div class="toast" id="timerToast">
                <div class="toast-header">Time's Up!</div>
                <div class="toast-body">Your reading time has expired. Would you like to continue to the post-vocabulary section or read the story again?</div>
                <div class="toast-buttons">
                    <button class="toast-button toast-primary" id="continueBtn">Continue</button>
                    <button class="toast-button toast-secondary" id="readAgainBtn">Read Again</button>
                </div>
            </div>

        <?php elseif ($step === 'postvocab'): ?>
            <div class="test">
                <h3 class="section-title">Fill in the Blanks</h3>

                <!-- Word Bank Section -->
                <div class="word-bank">
                    <?php
                    // Get all words from the word bank
                    $wordBank = $story["post-vocab"]["word_bank"];
                    // Shuffle them to make it more interesting
                    shuffle($wordBank);

                    // Display word bank
                    foreach ($wordBank as $word): ?>
                        <span class="word-item" onclick="selectWord(this)"><?php echo $word; ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- Fill in the blanks paragraph with actual blanks -->
                <div class="fill-blanks-text">
                    <?php
                    // Get the gap fill text and create input fields where there are blanks
                    $gapfillText = $story["post-vocab"]["gapfill_text"];

                    // Replace each blank marker (__________) with an input field
                    $counter = 1;
                    $pattern = '/(__________)/';
                    $filledText = preg_replace_callback($pattern, function ($matches) use (&$counter) {
                        return '<input type="text" class="blank-input" id="blank_' . $counter++ . '" readonly onclick="this.value=\'\'">';
                    }, $gapfillText);

                    echo $filledText;
                    ?>
                </div>

                <form method="post" id="postVocabForm">
                    <input type="hidden" name="step" value="postvocab-submit">

                    <?php
                    // Count blank inputs needed based on answers array
                    $totalAnswers = count($story["post-vocab"]["answers"]);
                    for ($i = 1; $i <= $totalAnswers; $i++):
                    ?>
                        <input type="hidden" name="blank_<?php echo $i; ?>" id="blank_<?php echo $i; ?>_value">
                    <?php endfor; ?>

                    <button type="button" onclick="submitBlanks()">Check Answers</button>
                </form>
            </div>


        <?php elseif ($step === 'postvocab-submit' || ($step === 'comprehension' && !empty($blankAnswers))): ?>
            <div class="test">
                <h3 class="section-title">Fill in the Blanks Results</h3>

                <!-- Score display -->
                <div class="score-container">
                    <div class="score-title">Your Score</div>
                    <div class="score-details">
                        You got <span class="correct-answer"><?php echo $blankScore; ?></span> out of <?php echo $totalBlanks; ?> correct!
                    </div>
                </div>

                <!-- Show results with feedback -->
                <div class="fill-blanks-text">
                    <?php
                    // Get the gap fill text and show the results with feedback
                    $gapfillText = $story["post-vocab"]["gapfill_text"];
                    $answers = $story["post-vocab"]["answers"];

                    $counter = 0;
                    $pattern = '/(__________)/';
                    $resultText = preg_replace_callback($pattern, function ($matches) use (&$counter, $blankAnswers) {
                        $position = $counter + 1;
                        $userAnswer = $blankAnswers[$position]['user'] ?? '';
                        $correctAnswer = $blankAnswers[$position]['correct'] ?? '';
                        $isCorrect = $blankAnswers[$position]['isCorrect'] ?? false;

                        $counter++;

                        if ($isCorrect) {
                            return '<span class="correct-answer">' . $userAnswer . '</span>';
                        } else {
                            return '<span class="incorrect-answer">' . $userAnswer . '</span> <span class="correct-answer">(' . $correctAnswer . ')</span>';
                        }
                    }, $gapfillText);

                    echo $resultText;
                    ?>
                </div>

                <form method="post">
                    <input type="hidden" name="step" value="comprehension">
                    <button type="submit">Continue to Comprehension Questions</button>
                </form>
            </div>

        <?php elseif ($step === 'comprehension'): ?>
            <div class="test">
                <h3 class="section-title">Reading Comprehension Questions</h3>
                <form method="post" action="final.php">
                    <div class="questions-container">
                        <?php foreach ($story["questions"] as $num => $q): ?>
                            <div class="question-item">
                                <label for="q<?php echo $num; ?>"><?php echo $q; ?></label>
                                <textarea name="q<?php echo $num; ?>" rows="3" cols="60" class="answer-textarea"></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit">Submit Answers</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Function to update available options
        function updateOptions() {
            const selects = document.querySelectorAll('.word-select');
            const selectedValues = Array.from(selects).map(select => select.value).filter(value => value !== "");

            // For each select element
            selects.forEach(select => {
                const currentValue = select.value;
                const options = select.querySelectorAll('option');

                // For each option in the select
                options.forEach(option => {
                    // Skip the placeholder option
                    if (option.value === "") return;

                    // If the option is selected in this select or not selected elsewhere, enable it
                    // Otherwise disable it
                    if (option.value === currentValue || !selectedValues.includes(option.value)) {
                        option.disabled = false;
                        option.style.display = '';
                    } else {
                        option.disabled = true;
                        option.style.display = 'none';
                    }
                });
            });
        }

        // Timer functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize on page load
            updateOptions();

            // Check if on the story page
            const storyContent = document.getElementById('storyContent');
            const continueForm = document.getElementById('continueForm');
            const timerElement = document.getElementById('timer');

            if (storyContent) {
                const startReadingBtn = document.getElementById('startReadingBtn');
                const timerSetup = document.getElementById('timerSetup');
                const timerContainer = document.querySelector('.timer-container');
                const pauseTimerBtn = document.getElementById('pauseTimerBtn');
                const resetTimerBtn = document.getElementById('resetTimerBtn');
                const timerToast = document.getElementById('timerToast');
                const continueBtn = document.getElementById('continueBtn');
                const readAgainBtn = document.getElementById('readAgainBtn');
                const finishButtonContainer = document.getElementById('finishButtonContainer');
                const finishReadingBtn = document.getElementById('finishReadingBtn');

                let timerInterval;
                let timeLeft = <?php echo $readingTime; ?>; // Default time in seconds
                let isTimerPaused = false;

                // Format time as MM:SS
                function formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                }

                // Start the timer
                function startTimer() {
                    // Show the story content, timer, and finish button
                    storyContent.style.display = 'flex'; // Changed from 'block' to 'flex' to support the new layout
                    timerContainer.style.display = 'flex';
                    timerSetup.style.display = 'none';
                    finishButtonContainer.style.display = 'block';

                    // Set the initial timer display
                    timerElement.textContent = formatTime(timeLeft);

                    // Start the countdown
                    timerInterval = setInterval(function() {
                        if (!isTimerPaused) {
                            timeLeft--;
                            timerElement.textContent = formatTime(timeLeft);

                            // When time's up
                            if (timeLeft <= 0) {
                                clearInterval(timerInterval);
                                // Blur the content
                                storyContent.classList.add('blurred-content');
                                timerToast.style.display = 'block';
                            }
                        }
                    }, 1000);
                }

                // Start reading button click
                startReadingBtn.addEventListener('click', function() {
                    // Get the custom timer value from the input
                    const readingTimeInput = document.getElementById('readingTimeInput');
                    const minutes = parseInt(readingTimeInput.value) || 5;
                    timeLeft = minutes * 60;

                    startTimer();
                });

                // Pause/Resume timer
                pauseTimerBtn.addEventListener('click', function() {
                    isTimerPaused = !isTimerPaused;
                    pauseTimerBtn.textContent = isTimerPaused ? 'Resume' : 'Pause';
                });

                // Reset timer
                resetTimerBtn.addEventListener('click', function() {
                    // Get the custom timer value from the input (if available)
                    const readingTimeInput = document.getElementById('readingTimeInput');
                    const minutes = parseInt(readingTimeInput.value) || 5;

                    clearInterval(timerInterval);
                    timeLeft = minutes * 60;
                    timerElement.textContent = formatTime(timeLeft);
                    isTimerPaused = false;
                    pauseTimerBtn.textContent = 'Pause';

                    // Remove blur if applied
                    storyContent.classList.remove('blurred-content');

                    // Restart the timer
                    startTimer();
                });

                // Continue to post-vocab from toast
                continueBtn.addEventListener('click', function() {
                    continueForm.style.display = 'block';
                    continueForm.submit();
                });

                // Read again from toast
                readAgainBtn.addEventListener('click', function() {
                    timerToast.style.display = 'none';
                    const readingTimeInput = document.getElementById('readingTimeInput');
                    const minutes = parseInt(readingTimeInput.value) || 5;

                    clearInterval(timerInterval);
                    timeLeft = minutes * 60;
                    timerElement.textContent = formatTime(timeLeft);
                    isTimerPaused = false;
                    pauseTimerBtn.textContent = 'Pause';

                    // Remove blur
                    storyContent.classList.remove('blurred-content');

                    // Restart the timer
                    startTimer();
                });

                // Finish Reading button click handler
                finishReadingBtn.addEventListener('click', function() {
                    // Clear the timer interval
                    clearInterval(timerInterval);

                    // Show the continue form
                    continueForm.style.display = 'block';
                    continueForm.submit();
                });

                // Show continue button when finished reading (optional)
                document.addEventListener('scroll', function() {
                    if (storyContent.style.display !== 'none') {
                        // Check if we've scrolled to the bottom of the story
                        const scrollPosition = window.scrollY + window.innerHeight;
                        const contentBottom = storyContent.offsetTop + storyContent.offsetHeight;

                        if (scrollPosition >= contentBottom) {
                            continueForm.style.display = 'block';
                        }
                    }
                });
            }
        });

        // Global variable to track the currently selected word
        let selectedWord = null;

        // Function to handle word selection from the word bank
        function selectWord(element) {
            // If we already have a word selected, deselect it visually
            if (selectedWord) {
                selectedWord.style.backgroundColor = '';
                selectedWord.style.color = '';
            }

            // Select the new word and highlight it
            selectedWord = element;
            selectedWord.style.backgroundColor = '#008000';
            selectedWord.style.color = '#ffffff';

            // Let user know they can click on a blank to place the word
            toastr.info('Click on a blank to place this word', 'Word Selected');
        }

        // Function to place the selected word in a blank when clicked
        document.addEventListener('DOMContentLoaded', function() {
            // Find all blank inputs
            const blankInputs = document.querySelectorAll('.blank-input');

            // Add click event listeners to each blank input
            blankInputs.forEach(input => {
                input.addEventListener('click', function() {
                    // If a word is selected from the word bank
                    if (selectedWord) {
                        // Place the word in the blank
                        this.value = selectedWord.textContent;

                        // Also update the hidden input for form submission
                        const hiddenInput = document.getElementById(this.id + '_value');
                        if (hiddenInput) {
                            hiddenInput.value = selectedWord.textContent;
                        }

                        // Deselect the word
                        selectedWord.style.backgroundColor = '';
                        selectedWord.style.color = '';
                        selectedWord.style.opacity = '0.5'; // Dim the used word
                        selectedWord.style.pointerEvents = 'none'; // Make it unclickable
                        selectedWord = null;
                    }
                });
            });
        });

        // Function to submit the blank answers
        function submitBlanks() {
            // Check if all blanks are filled
            const blanks = document.querySelectorAll('.blank-input');
            const emptyBlanks = Array.from(blanks).filter(input => !input.value);

            if (emptyBlanks.length > 0) {
                toastr.warning('Please fill in all blanks before submitting', 'Incomplete');
                // Highlight empty blanks
                emptyBlanks.forEach(blank => {
                    blank.style.borderBottom = '2px solid red';
                    setTimeout(() => {
                        blank.style.borderBottom = '2px solid #ffbd59';
                    }, 2000);
                });
                return;
            }

            // Update all hidden inputs
            blanks.forEach(blank => {
                const hiddenInput = document.getElementById(blank.id + '_value');
                if (hiddenInput) {
                    hiddenInput.value = blank.value;
                }
            });

            // Submit the form
            document.getElementById('postVocabForm').submit();
        }
    </script>

</body>

</html>