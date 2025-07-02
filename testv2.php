<?php
    include 'test-content.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png" type="image/x-icon">
    <title>Gates-MacGinitie Reading Test</title>

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Start Modal Styles */
        .start-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .start-modal-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .start-modal h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 2em;
        }

        .start-btn {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }

        .start-btn:hover {
            background: linear-gradient(45deg, #229954, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }

        /* Container and Layout */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .test-content-hidden {
            display: none;
        }

        .test-content-visible {
            display: block;
        }

        /* Header Styles */
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 2.2em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Test Section Styles */
        .test-section {
            display: none;
            animation: fadeIn 0.5s ease-in;
        }

        .test-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .test-header {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .test-header h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .test-header p {
            color: #7f8c8d;
            font-size: 1.1em;
        }

        /* Instructions Styles */
        .instructions {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            margin: 20px 0;
        }

        .instructions h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .instructions p {
            margin-bottom: 10px;
        }

        .instructions ul, .instructions ol {
            margin: 15px 0;
            padding-left: 30px;
        }

        .instructions li {
            margin: 8px 0;
        }

        /* Progress Container (Sticky) */
        .progress-container {
            position: sticky;
            top: 0;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
            border: 1px solid #e0e0e0;
        }

        .test-timer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
        }

        .test-timer.warning {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .pause-btn {
            background: #f39c12;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .pause-btn:hover {
            background: #e67e22;
        }

        .pause-btn.resume {
            background: #27ae60;
        }

        .pause-btn.resume:hover {
            background: #229954;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #3498db, #2980b9);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Question Styles */
        .question-container {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 3px solid #28a745;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .question-word, .question-text {
            font-weight: bold;
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .passage {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-style: italic;
            border-left: 3px solid #3498db;
        }

        .options {
            margin: 15px 0;
        }

        .option {
            margin: 8px 0;
        }

        .option label {
            display: flex;
            align-items: center;
            padding: 8px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .option label:hover {
            background: #e9ecef;
        }

        .option input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }

        /* Button Styles */
        .btn, .proceed-btn, .back-btn, .submit-btn {
            background: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover, .proceed-btn:hover, .back-btn:hover, .submit-btn:hover {
            background: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .proceed-btn {
            background: #27ae60;
            font-size: 18px;
            padding: 15px 30px;
            margin-top: 20px;
        }

        .proceed-btn:hover {
            background: #229954;
        }

        .proceed-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-btn {
            background: #95a5a6;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #7f8c8d;
        }

        .submit-btn {
            background: #e74c3c;
            font-size: 18px;
            padding: 15px 30px;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: #c0392b;
        }

        .submit-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .continue-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 1.3em;
            cursor: pointer;
            margin: 20px auto;
            display: block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            width: 80%;
            max-width: 300px;
        }

        .continue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Blur Overlay for Pause */
        .blur-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .pause-message {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .pause-message h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        /* Results Styles */
        .results {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .score-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #3498db;
        }

        .score-section h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .score-display {
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }

        .percentage {
            font-size: 1.5em;
            font-weight: bold;
            color: #3498db;
        }

        .overall-score {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
        }

        .overall-score h3 {
            margin-bottom: 10px;
        }

        .overall-score .score-display,
        .overall-score .percentage {
            color: white;
        }

        .reader-type-section {
            padding: 25px;
            margin: 20px 0;
            border-radius: 15px;
            text-align: center;
            color: white;
        }

        .reader-type-section h3 {
            margin: 0 0 15px 0;
            font-size: 1.8em;
        }

        .reader-type-section p {
            margin: 0;
            font-size: 1.1em;
            line-height: 1.5;
            opacity: 0.95;
        }

        /* Timer Styles (Fixed Position) */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            
            h1 {
                font-size: 1.8em;
            }
            
            .start-modal-content {
                padding: 20px;
                width: 95%;
            }
            
            .question-container {
                padding: 15px;
            }
            
            .progress-container {
                padding: 10px 15px;
            }
            
            .test-timer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .timer {
                position: static;
                margin-bottom: 10px;
                text-align: center;
            }
            
            .btn, .proceed-btn, .back-btn, .submit-btn {
                width: 100%;
                margin: 5px 0;
            }
            
            .modal-content {
                padding: 20px;
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .start-modal-content {
                padding: 15px;
            }
            
            .start-modal h2 {
                font-size: 1.5em;
            }
            
            .option label {
                font-size: 14px;
            }
            
            .question-word, .question-text {
                font-size: 1em;
            }
            
            .score-display {
                font-size: 1.5em;
            }
            
            .percentage {
                font-size: 1.2em;
            }
        }

        /* Print Styles */
        @media print {
            .timer, .progress-container, .blur-overlay, .modal {
                display: none !important;
            }
            
            .container {
                box-shadow: none;
                background: white;
            }
            
            .question-container {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="start-modal" id="startModal">
        <div class="start-modal-content">
            <h2>📚 Gates-MacGinitie Reading Test</h2>
            <p><strong>Welcome to the Reading Assessment!</strong></p>
            
            <div style="text-align: left; margin: 20px 0;">
                <p>This test consists of three parts:</p>
                <ul>
                    <li><strong>Vocabulary Test</strong> - Choose words with similar meanings</li>
                    <li><strong>Speed & Accuracy Test</strong> - Quick reading comprehension</li>
                    <li><strong>Comprehension Test</strong> - Detailed passage analysis</li>
                </ul>
            </div>
            
            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0;">
                <p><strong>⏰ Time Limit: 55 minutes</strong></p>
                <p style="font-size: 0.95em; color: #666; margin: 5px 0 0 0;">
                    The timer will start when you click "Start Test" and cannot be stopped.
                </p>
            </div>
            
            <p style="font-size: 0.9em; color: #888;">
                Make sure you're in a quiet environment and ready to focus.
            </p>
            
            <button id="startTestBtn" class="start-btn">🚀 Start Test</button>
        </div>
    </div>

    <div class="container test-content-hidden">
        <h1>📚 Gates-MacGinitie Reading Test</h1>
        
       <!-- Vocabulary Test Section -->
        <div id="vocabularySection" class="test-section active">
            <div class="test-header">
                <h2>Part 1: Vocabulary Test</h2>
                <p>Choose the word that means the same or nearly the same as the given word</p>
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Look at the test words V1 below. The word is <b><i><u>baby</b></i></u>. Now read the five words below baby. Find the same word in this group that means most nearly the same as <b><i><u>baby</b></i></u>. The word <b><i><u>child</b></i></u> most nearly the same as baby.
                <br><br>
                Now look at the test word number V2. Find the one word in the group below it that means most nearly the same and write its letter.
                <br><br>
                <b><i><u>Slide</b></i></u> means most nearly as <b><i><u>slip</b></i></u>. You should have written letter <b><i><u>A</b></i></u>.
                <br><br>
                <b>SAMPLES:</b>
                <ol style="list-style: none;">
                    <li>V1.  baby</li>
                        <ol>
                            <li style="display: inline-block;margin-right: 3rem;">A. box</li>
                            <li style="display: inline-block;margin-right: 3rem;">B. bath</li>
                            <li style="display: inline-block;margin-right: 3rem;">C. nest</li>
                            <li style="display: inline-block;margin-right: 3rem;">D. child</li>
                            <li style="display: inline-block;margin-right: 3rem;">E. bib</li>
                        </ol>
                    <li>V2.  slip</li>
                        <ol>
                            <li style="display: inline-block;margin-right: 3rem;">A. slide</li>
                            <li style="display: inline-block;margin-right: 3rem;">B. neat</li>
                            <li style="display: inline-block;margin-right: 3rem;">C. smile</li>
                            <li style="display: inline-block;margin-right: 3rem;">D. bad</li>
                            <li style="display: inline-block;margin-right: 3rem;">E. nap</li>
                        </ol>
                </ol>
                <br>
                <i>For each numbered word on this page, choose the letter of the word that means nearly the same. If you can’t decide which word means most nearly the same as the numbered test word, go on to the next test word.</i>
                </p>
            </div>
            
            <!-- Sticky Progress Bar and Timer -->
            <div class="progress-container">
                <div class="test-timer">
                    <strong>Time Remaining: <span id="timer">55:00</span></strong>
                    <button id="pauseBtn" class="pause-btn">⏸️ Pause</button>
                </div>
                <div class="progress-info">
                    <span class="progress-text">Vocabulary Progress</span>
                    <span class="progress-percentage" id="vocabProgressText">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="vocabProgressFill"></div>
                </div>
            </div>
            
            <form id="vocabularyTest">
                <div id="vocabQuestionsContainer"></div>
                <button type="button" id="proceedToSpeedBtn" class="proceed-btn" disabled>
                    Proceed to Speed Test
                </button>
            </form>
        </div>

        <!-- Speed and Accuracy Test Section -->
        <div id="speedSection" class="test-section">
            <div class="test-header">
                <h2>Part 2: Speed & Accuracy Test</h2>
                <p>Read each passage carefully and answer the questions</p>
                <button type="button" id="backToVocabBtn" class="back-btn">← Back to Vocabulary</button>
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Read each passage carefully and choose the best answer for each question. Work as quickly and accurately as possible.</p>
            </div>
            
            <!-- Sticky Progress Bar and Timer -->
            <div class="progress-container">
                <div class="test-timer">
                    <strong>Time Remaining: <span id="timer">55:00</span></strong>
                    <button id="pauseBtn" class="pause-btn">⏸️ Pause</button>
                </div>
                <div class="progress-info">
                    <span class="progress-text">Speed & Accuracy Progress</span>
                    <span class="progress-percentage" id="speedProgressText">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="speedProgressFill"></div>
                </div>
            </div>
            
            <form id="speedTest">
                <div id="speedQuestionsContainer"></div>
                <button type="button" id="proceedToCompreBtn" class="proceed-btn" disabled>
                    Proceed to Comprehension Test
                </button>
            </form>
        </div>

        <!-- Comprehension Test Section -->
        <div id="comprehensionSection" class="test-section">
            <div class="test-header">
                <h2>Part 3: Comprehension Test</h2>
                <p>Read each passage carefully and answer the questions</p>
                <button type="button" id="backToSpeedBtn" class="back-btn">← Back to Speed Test</button>
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Read the sample paragraph below. It has numbered blanks in it. The first blank is number C1. Look below the paragraph at the line of words with C1 in front of it. Find the word in line C1 that makes the best sense in blank C1. The word <b><i><u>house</u></i></b> is the answer to number C1.
                <br><br>
                Choose the word <b><i><u>house</u></i></b>.
                <br><br>
                Now look at the words in line C2.  Find the word in line C2 that makes the best answer in blank C2, and write its letter.
                <br><br>
                <b>SAMPLES:</b>
                <br>
                We have a playroom in our (C1) _______.  It is down in the basement, so we need to turn 	on an electric (C2)_______ .
                <br>
                <ol>
                    <li style="display: inline-block;margin-right: 3rem;">C1.</li>
                    <li style="display: inline-block;margin-right: 3rem;">A. stove</li>
                    <li style="display: inline-block;margin-right: 3rem;">B. house</li>
                    <li style="display: inline-block;margin-right: 3rem;">C. bed</li>
                    <li style="display: inline-block;margin-right: 3rem;">D. car</li>
                    <li style="display: inline-block;margin-right: 3rem;">E. lake</li>
                </ol>
                <ol>
                    <li style="display: inline-block;margin-right: 3rem;">C2.</li>
                    <li style="display: inline-block;margin-right: 3rem;">A. storm</li>
                    <li style="display: inline-block;margin-right: 3rem;">B. friend</li>
                    <li style="display: inline-block;margin-right: 3rem;">C. ladder</li>
                    <li style="display: inline-block;margin-right: 3rem;">D. room</li>
                    <li style="display: inline-block;margin-right: 3rem;">E. light</li>
                </ol>
                <br>
                The word <b><i><u>light</u></i></b> makes the best sense in blank C2. You should have written letter <b><i><u>E</u></i></b>.
                <br><br>
                <i>Now write the letter of the best word for each of the blanks that follow on this page and on the next pages.  If you can’t choose the best word for a blank, don’t spend too much time on it.  Go on to the next one. 
                </p>
            </div>
            
            <!-- Sticky Progress Bar and Timer -->
            <div class="progress-container">
                <div class="test-timer">
                    <strong>Time Remaining: <span id="timer">55:00</span></strong>
                    <button id="pauseBtn" class="pause-btn">⏸️ Pause</button>
                </div>
                <div class="progress-info">
                    <span class="progress-text">Comprehension Progress</span>
                    <span class="progress-percentage" id="comprehensionProgressText">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="comprehensionProgressFill"></div>
                </div>
            </div>
            
            <form id="comprehensionTest">
                <div id="comprehensionQuestionsContainer"></div>
                <button type="submit" class="submit-btn" disabled>Submit Complete Test</button>
            </form>
        </div>
        
        <!-- Results Section -->
        <div id="results" class="results"></div>
    </div>

    <!-- Blur overlay -->
    <div class="blur-overlay" id="blurOverlay">
        <div class="pause-message">
            <h2>⏸️ Test Paused</h2>
            <p>The timer is paused. Click the resume button to continue your test.</p>
            <button id="resumeBtn" class="pause-btn resume">▶️ Resume Test</button>
        </div>
    </div>

    <!-- Results -->
    <div id="resultsModal" class="modal">
        <div class="modal-content">
            <h2>🎉 Test Results</h2>
            <div id="modalResults"></div>
        </div>
    </div>

    <script>
        let timerStarted = false;

        // Test data from PHP - dynamically loaded
        const vocabData = <?php echo json_encode($vocabTest); ?>;
        const speedAccuracyData = <?php echo json_encode($speedAccuracyTest); ?>;
        const comprehensionData = <?php echo json_encode($comprehensionTest); ?>;
        
        let vocabAnswers = {};
        let speedAnswers = {};
        let comprehensionAnswers = {};
        let timerInterval;
        let timeRemaining = 55 * 60; // 55 minutes in seconds

        // Shuffle function
        function shuffle(array) {
            const newArray = [...array];
            for (let i = newArray.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
            }
            return newArray;
        }

        // Prepare vocabulary test
        function prepareVocabTest() {
            // const randomizedVocab = shuffle(vocabData);  
            const randomizedVocab = shuffle(vocabData).slice(0, 5); // Take ONLY 5 items       
            return randomizedVocab.map((item, index) => ({
                ...item,
                options: shuffle(item.options),
                questionNumber: index + 1
            }));
        }

        // Prepare speed test
        function prepareSpeedTest() {
             // const randomizedSpeedAccuracy = shuffle(speedAccuracyData);  
            const randomizedSpeedAccuracy = shuffle(speedAccuracyData).slice(0, 5); // Take ONLY 5 items       
            return randomizedSpeedAccuracy.map((item, index) => ({
                ...item,
                options: shuffle(item.options),
                questionNumber: index + 1
            }));
        }

        // Prepare comprehension test
        function prepareComprehensionTest() {
            const flattenedQuestions = [];
            let questionNumber = 1;
            
            // Take only the first 2 passages
            const limitedPassages = comprehensionData.slice(0, 2);
            
            // Flatten the nested structure
            limitedPassages.forEach(passageData => {
                const passage = passageData.passage;
                
                passageData.questions.forEach(questionData => {
                    // Convert the options object to an array
                    const optionsArray = Object.values(questionData.options);
                    
                    // Find the correct answer text (not just the letter)
                    const correctLetter = questionData.correct_answer;
                    const correctAnswer = questionData.options[correctLetter];
                    
                    flattenedQuestions.push({
                        question: `${passage}\n\nQuestion ${questionData.number}: Fill in the blank.`,
                        options: optionsArray,
                        answer: correctAnswer,
                        questionNumber: questionNumber++
                    });
                });
            });
            
            return flattenedQuestions;
        }

        // Generate vocabulary test HTML
        function generateVocabTest() {
            const questions = prepareVocabTest();
            const container = document.getElementById('vocabQuestionsContainer');
            
            questions.forEach(question => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question-container';
                questionDiv.innerHTML = `
                    <div class="question-word">${question.questionNumber}. ${question.question}</div>
                    <div class="options">
                        ${question.options.map((option, optIndex) => `
                            <div class="option">
                                <input type="radio" id="vocab_q${question.questionNumber}_${optIndex}" 
                                       name="vocab_question${question.questionNumber}" value="${option}">
                                <label for="vocab_q${question.questionNumber}_${optIndex}">${option}</label>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.appendChild(questionDiv);
            });
            
            return questions;
        }

        // Generate speed test HTML
        function generateSpeedTest() {
            const questions = prepareSpeedTest();
            const container = document.getElementById('speedQuestionsContainer');
            
            questions.forEach(question => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question-container';
                questionDiv.innerHTML = `
                    <div class="question-text">${question.questionNumber}. ${question.question}</div>
                    <div class="options">
                        ${question.options.map((option, optIndex) => `
                            <div class="option">
                                <input type="radio" id="speed_q${question.questionNumber}_${optIndex}" 
                                       name="speed_question${question.questionNumber}" value="${option}">
                                <label for="speed_q${question.questionNumber}_${optIndex}">${option}</label>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.appendChild(questionDiv);
            });
            
            return questions;
        }

        // Generate comprehension test HTML
        function generateComprehensionTest() {
            const questions = prepareComprehensionTest();
            const container = document.getElementById('comprehensionQuestionsContainer');
            
            questions.forEach(question => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question-container';
                
                // Split the question to show passage and question separately
                const [passage, questionText] = question.question.split('\n\nQuestion');
                
                questionDiv.innerHTML = `
                    <div class="passage" style="background: #f0f8ff; padding: 15px; border-radius: 8px; margin-bottom: 15px; font-style: italic;">
                        ${passage}
                    </div>
                    <div class="question-text">${question.questionNumber}. Question${questionText || ': Fill in the blank.'}</div>
                    <div class="options">
                        ${question.options.map((option, optIndex) => `
                            <div class="option">
                                <input type="radio" id="comprehension_q${question.questionNumber}_${optIndex}" 
                                    name="comprehension_question${question.questionNumber}" value="${option}">
                                <label for="comprehension_q${question.questionNumber}_${optIndex}">${option}</label>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.appendChild(questionDiv);
            });
            
            return questions;
        }

        // Update progress bar for vocabulary
        function updateVocabProgress() {
            const totalQuestions = vocabQuestions.length;
            
            if (totalQuestions === 0) {
                console.warn('No vocabulary questions found');
                return;
            }
            
            const checkedInputs = document.querySelectorAll('input[name^="vocab_question"]:checked');
            const answeredCount = new Set(Array.from(checkedInputs).map(input => input.name)).size;
            
            const progress = Math.round((answeredCount / totalQuestions) * 100);
            
            // Update UI elements
            const progressFill = document.getElementById('vocabProgressFill');
            const progressText = document.getElementById('vocabProgressText');
            const proceedBtn = document.getElementById('proceedToSpeedBtn');
            
            if (progressFill) progressFill.style.width = progress + '%';
            if (progressText) progressText.textContent = `${progress}% (${answeredCount}/${totalQuestions})`;
            if (proceedBtn) proceedBtn.disabled = answeredCount !== totalQuestions;
            
            // Update individual question styling
            const questionContainers = document.querySelectorAll('#vocabularySection .question-container');
            questionContainers.forEach((container, index) => {
                const questionNumber = index + 1;
                const radioButtons = container.querySelectorAll(`input[name="vocab_question${questionNumber}"]`);
                const isAnswered = Array.from(radioButtons).some(radio => radio.checked);
                
                container.classList.remove('answered');
                if (isAnswered) {
                    container.classList.add('answered');
                }
            });
            
            console.log(`Vocab Progress: ${progress}% (${answeredCount}/${totalQuestions})`);
        }


        // Update progress bar for speed test
        function updateSpeedProgress() {
            const totalQuestions = speedQuestions.length;
            
            if (totalQuestions === 0) {
                console.warn('No speed and accuracy questions found');
                return;
            }
            
            const checkedInputs = document.querySelectorAll('input[name^="speed_question"]:checked');
            const answeredCount = new Set(Array.from(checkedInputs).map(input => input.name)).size;
            
            const progress = Math.round((answeredCount / totalQuestions) * 100);
            
            // Update UI elements
            const progressFill = document.getElementById('speedProgressFill');
            const progressText = document.getElementById('speedProgressText');
            const proceedBtn = document.getElementById('proceedToCompreBtn');
            
            if (progressFill) progressFill.style.width = progress + '%';
            if (progressText) progressText.textContent = `${progress}% (${answeredCount}/${totalQuestions})`;
            if (proceedBtn) proceedBtn.disabled = answeredCount !== totalQuestions;
            
            // Update individual question styling
            const questionContainers = document.querySelectorAll('#speedSection .question-container');
            questionContainers.forEach((container, index) => {
                const questionNumber = index + 1;
                const radioButtons = container.querySelectorAll(`input[name="speed_question${questionNumber}"]`);
                const isAnswered = Array.from(radioButtons).some(radio => radio.checked);
                
                container.classList.remove('answered');
                if (isAnswered) {
                    container.classList.add('answered');
                }
            });
            
            console.log(`Speed and Accuracy Progress: ${progress}% (${answeredCount}/${totalQuestions})`);
        }

        // Update progress bar for comprehension test
        function updateComprehensionProgress() {
            const totalQuestions = comprehensionQuestions.length;
            
            if (totalQuestions === 0) {
                console.warn('No comprehension questions found');
                return;
            }
            
            const checkedInputs = document.querySelectorAll('input[name^="comprehension_question"]:checked');
            const answeredCount = new Set(Array.from(checkedInputs).map(input => input.name)).size;
            
            const progress = Math.round((answeredCount / totalQuestions) * 100);
            
            // Update UI elements
            const progressFill = document.getElementById('comprehensionProgressFill');
            const progressText = document.getElementById('comprehensionProgressText');
            const submitBtn = document.querySelector('#comprehensionTest .submit-btn'); // Get the submit button
            
            if (progressFill) progressFill.style.width = progress + '%';
            if (progressText) progressText.textContent = `${progress}% (${answeredCount}/${totalQuestions})`;
            
            // Enable/disable submit button based on completion
            if (submitBtn) {
                submitBtn.disabled = answeredCount !== totalQuestions;
            }
            
            // Update individual question styling
            const questionContainers = document.querySelectorAll('#comprehensionSection .question-container');
            questionContainers.forEach((container, index) => {
                const questionNumber = index + 1;
                const radioButtons = container.querySelectorAll(`input[name="comprehension_question${questionNumber}"]`);
                const isAnswered = Array.from(radioButtons).some(radio => radio.checked);
                
                container.classList.remove('answered');
                if (isAnswered) {
                    container.classList.add('answered');
                }
            });
            
            console.log(`Comprehension Progress: ${progress}% (${answeredCount}/${totalQuestions})`);
        }

        // Timer function
        let isPaused = false;

        function startTimer() {
            if (timerStarted) return; // Prevent multiple timers
            
            timerStarted = true;
            timerInterval = setInterval(() => {
                if (!isPaused) {
                    timeRemaining--;
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    
                    const timerElements = document.querySelectorAll('#timer');
                    timerElements.forEach(element => {
                        element.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });
                    
                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        alert('Time is up! Submitting your test...');
                        document.getElementById('comprehensionTest').dispatchEvent(new Event('submit'));
                    }
                }
            }, 1000);
        }

        // Handle start test button
        document.getElementById('startTestBtn').addEventListener('click', function() {
            // Hide the start modal
            document.getElementById('startModal').style.display = 'none';
            
            // Show the test content
            document.querySelector('.container').classList.remove('test-content-hidden');
            
            // Start the timer
            startTimer();
        });

        // Handle pause timer button
        function pauseTimer() {
            isPaused = true;
            document.getElementById('blurOverlay').style.display = 'flex';
            document.querySelector('.container').classList.add('content-blurred');
            
            // Update all pause buttons
            const pauseBtns = document.querySelectorAll('#pauseBtn');
            pauseBtns.forEach(btn => {
                btn.textContent = '▶️ Resume';
                btn.classList.add('resume');
            });
        }

        // Handle resume timer button
        function resumeTimer() {
            isPaused = false;
            document.getElementById('blurOverlay').style.display = 'none';
            document.querySelector('.container').classList.remove('content-blurred');
            
            // Update all pause buttons
            const pauseBtns = document.querySelectorAll('#pauseBtn');
            pauseBtns.forEach(btn => {
                btn.textContent = '⏸️ Pause';
                btn.classList.remove('resume');
            });
        }

        // Function to restore vocabulary answers
        function restoreVocabAnswers() {
            Object.keys(vocabAnswers).forEach(key => {
                const input = document.querySelector(`input[name="${key}"][value="${vocabAnswers[key]}"]`);
                if (input) input.checked = true;
            });
            updateVocabProgress();
        }

        // Function to restore speed answers
        function restoreSpeedAnswers() {
            Object.keys(speedAnswers).forEach(key => {
                const input = document.querySelector(`input[name="${key}"][value="${speedAnswers[key]}"]`);
                if (input) input.checked = true;
            });
            updateSpeedProgress();
        }

        // Function to restore comprehension answers
        function restoreComprehensionAnswers() {
            const comprehensionFormData = new FormData(document.getElementById('comprehensionTest'));
            comprehensionFormData.forEach((value, key) => {
                const input = document.querySelector(`input[name="${key}"][value="${value}"]`);
                if (input) input.checked = true;
            });
            updateComprehensionProgress();
        }

        // Store vocabulary answers
        function storeVocabAnswers() {
            const formData = new FormData(document.getElementById('vocabularyTest'));
            vocabAnswers = {};
            for (let [key, value] of formData.entries()) {
                vocabAnswers[key] = value;
            }
        }

        // Store speed answers
        function storeSpeedAnswers() {
            const formData = new FormData(document.getElementById('speedTest'));
            speedAnswers = {};
            for (let [key, value] of formData.entries()) {
                speedAnswers[key] = value;
            }
        }

        // Score all three tests
        function scoreCompleteTest() {
            // Score vocabulary
            let vocabCorrect = 0;
            const vocabResults = [];
            
            vocabQuestions.forEach((question, index) => {
                const userAnswer = vocabAnswers[`vocab_question${index + 1}`];
                const isCorrect = userAnswer === question.answer;
                
                if (isCorrect) vocabCorrect++;
                
                vocabResults.push({
                    question: question.question,
                    userAnswer: userAnswer || 'No answer',
                    correctAnswer: question.answer,
                    isCorrect: isCorrect
                });
            });

            // Score speed test
            let speedCorrect = 0;
            const speedResults = [];
            
            speedQuestions.forEach((question, index) => {
                const userAnswer = speedAnswers[`speed_question${index + 1}`];
                const isCorrect = userAnswer === question.answer;
                
                if (isCorrect) speedCorrect++;
                
                speedResults.push({
                    question: question.question,
                    userAnswer: userAnswer || 'No answer',
                    correctAnswer: question.answer,
                    isCorrect: isCorrect
                });
            });

            // Score comprehension test
            let comprehensionCorrect = 0;
            const comprehensionResults = [];
            const comprehensionFormData = new FormData(document.getElementById('comprehensionTest'));
            
            comprehensionQuestions.forEach((question, index) => {
                const userAnswer = comprehensionFormData.get(`comprehension_question${index + 1}`);
                const isCorrect = userAnswer === question.answer;
                
                if (isCorrect) comprehensionCorrect++;
                
                comprehensionResults.push({
                    question: question.question,
                    userAnswer: userAnswer || 'No answer',
                    correctAnswer: question.answer,
                    isCorrect: isCorrect
                });
            });

            return {
                vocab: { correct: vocabCorrect, total: vocabQuestions.length, results: vocabResults },
                speed: { correct: speedCorrect, total: speedQuestions.length, results: speedResults },
                comprehension: { correct: comprehensionCorrect, total: comprehensionQuestions.length, results: comprehensionResults }
            };
        }

        // Handle form submission
        function showResults() {
            const scoreData = scoreCompleteTest();
            
            const vocabPercentage = Math.round((scoreData.vocab.correct / scoreData.vocab.total) * 100);
            const speedPercentage = Math.round((scoreData.speed.correct / scoreData.speed.total) * 100);
            const comprehensionPercentage = Math.round((scoreData.comprehension.correct / scoreData.comprehension.total) * 100);
            
            const totalCorrect = scoreData.vocab.correct + scoreData.speed.correct + scoreData.comprehension.correct;
            const totalQuestions = scoreData.vocab.total + scoreData.speed.total + scoreData.comprehension.total;
            const overallPercentage = Math.round((totalCorrect / totalQuestions) * 100);
            
            // Determine reader type
            const readerType = determineReaderType(vocabPercentage, comprehensionPercentage, overallPercentage);
            
            const modalContent = `
                <div class="score-section">
                    <h3>📚 Vocabulary Section</h3>
                    <div class="score-display">${scoreData.vocab.correct}/${scoreData.vocab.total}</div>
                    <div class="percentage">${vocabPercentage}%</div>
                </div>
                
                <div class="score-section">
                    <h3>⚡ Speed & Accuracy Section</h3>
                    <div class="score-display">${scoreData.speed.correct}/${scoreData.speed.total}</div>
                    <div class="percentage">${speedPercentage}%</div>
                </div>
                
                <div class="score-section">
                    <h3>📖 Comprehension Section</h3>
                    <div class="score-display">${scoreData.comprehension.correct}/${scoreData.comprehension.total}</div>
                    <div class="percentage">${comprehensionPercentage}%</div>
                </div>
                
                <div class="overall-score">
                    <h3>🏆 Overall Score</h3>
                    <div class="score-display">${totalCorrect}/${totalQuestions}</div>
                    <div class="percentage">${overallPercentage}%</div>
                </div>
                
                <div class="reader-type-section" style="background: ${readerType.color}; color: white; padding: 25px; margin: 20px 0; border-radius: 15px; text-align: center;">
                    <h3 style="margin: 0 0 15px 0; font-size: 1.8em;">
                        ${readerType.icon} ${readerType.type}
                    </h3>
                    <p style="margin: 0; font-size: 1.1em; line-height: 1.5; opacity: 0.95;">
                        ${readerType.description}
                    </p>
                </div>
                
                <button class="continue-btn" onclick="continueToReading('${readerType.redirectUrl}')" 
                        style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; padding: 15px 40px; border: none; border-radius: 25px; font-size: 1.3em; cursor: pointer; margin: 20px auto; display: block; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); width: 80%; max-width: 300px;">
                    📖 Continue to Reading
                </button>
            `;
            
            document.getElementById('modalResults').innerHTML = modalContent;
            document.getElementById('resultsModal').style.display = 'block';
            
            // Prevent clicking outside to close
            document.getElementById('resultsModal').onclick = function(event) {
                if (event.target === this) {
                    return false;
                }
            };
        }

        // Function to handle continue to reading
        function continueToReading(url) {
            window.location.href = url;
        }

        // Function to determine reader type based on scores
        function determineReaderType(vocabPercentage, comprehensionPercentage, overallPercentage) {
            if (vocabPercentage >= 95 && comprehensionPercentage >= 90 && overallPercentage >= 95) {
                return {
                    type: 'Independent Reader',
                    description: 'You can read and understand text independently with excellent comprehension.',
                    redirectUrl: 'independent.php',
                    color: '#28a745', // Green
                    icon: '🌟'
                };
            } else if (vocabPercentage >= 90 && comprehensionPercentage >= 75 && overallPercentage >= 90 && overallPercentage <= 94) {
                return {
                    type: 'Instructional Reader',
                    description: 'You can read well with some guidance and support for optimal learning.',
                    redirectUrl: 'instructional.php',
                    color: '#ffc107', // Yellow/Gold
                    icon: '📚'
                };
            } else {
                return {
                    type: 'Frustrational Reader',
                    description: 'Reading material may be challenging. Additional support and practice recommended.',
                    redirectUrl: 'frustrational.php',
                    color: '#dc3545', // Red
                    icon: '💪'
                };
            }
        }
    
        // Initialize tests
        const vocabQuestions = generateVocabTest();
        const speedQuestions = generateSpeedTest();
        const comprehensionQuestions = generateComprehensionTest();
        
        updateComprehensionProgress();
        
        // Handle pause/resume timer
        document.addEventListener('click', function(e) {
            if (e.target.id === 'pauseBtn') {
                if (isPaused) {
                    resumeTimer();
                } else {
                    pauseTimer();
                }
            }
        });

        document.getElementById('resumeBtn').addEventListener('click', function() {
            resumeTimer();
        });

        // Add event listeners for progress tracking
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio') {
                if (e.target.name.startsWith('vocab_')) {
                    updateVocabProgress();
                } else if (e.target.name.startsWith('speed_')) {
                    updateSpeedProgress();
                } else if (e.target.name.startsWith('comprehension_')) {
                    updateComprehensionProgress();
                }
            }
        });

        // Handle back to vocabulary test
        document.getElementById('backToVocabBtn').addEventListener('click', function() {
            storeSpeedAnswers(); // Store current answers before going back
            document.getElementById('speedSection').classList.remove('active');
            document.getElementById('vocabularySection').classList.add('active');

            window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to top
        });

        // Handle proceed to speed test
        document.getElementById('proceedToSpeedBtn').addEventListener('click', function() {
            storeVocabAnswers();
            document.getElementById('vocabularySection').classList.remove('active');
            document.getElementById('speedSection').classList.add('active');
            restoreSpeedAnswers();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Handle back to speed test
        document.getElementById('backToSpeedBtn').addEventListener('click', function() {
            // Store comprehension answers
            const comprehensionFormData = new FormData(document.getElementById('comprehensionTest'));
            const tempComprehensionAnswers = {};
            for (let [key, value] of comprehensionFormData.entries()) {
                tempComprehensionAnswers[key] = value;
            }
            
            document.getElementById('comprehensionSection').classList.remove('active');
            document.getElementById('speedSection').classList.add('active');

            window.scrollTo({ top: 0, behavior: 'smooth' }); // Scroll to top
        });

        // Handle proceed to comprehension test
        document.getElementById('proceedToCompreBtn').addEventListener('click', function() {
            storeSpeedAnswers();
            document.getElementById('speedSection').classList.remove('active');
            document.getElementById('comprehensionSection').classList.add('active');
            restoreComprehensionAnswers();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Handle final form submission
        document.getElementById('comprehensionTest').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            
            const scoreData = scoreCompleteTest();
            showResults(); // Show results in modal
        });
    </script>
</body>
</html>