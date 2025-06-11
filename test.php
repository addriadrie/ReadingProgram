<?php
    include 'test-content.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gates-MacGinitie Reading Test</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.2em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .test-section {
            display: none;
        }
        
        .test-section.active {
            display: block;
        }
        
        .instructions {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .question-container {
            margin-bottom: 25px;
            padding: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: #fafbfc;
            transition: all 0.3s ease;
        }
        
        .question-container:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }
        
        .question-word {
            font-size: 1.4em;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        
        .question-text {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .options {
            display: grid;
            gap: 10px;
        }
        
        .option {
            display: flex;
            align-items: center;
            padding: 12px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .option:hover {
            background: #e3f2fd;
            border-color: #2196f3;
        }
        
        .option input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        
        .option label {
            cursor: pointer;
            flex: 1;
            font-size: 1.1em;
        }
        
        .submit-btn, .proceed-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            display: block;
            margin: 40px auto 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .submit-btn:hover, .proceed-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .submit-btn:disabled, .proceed-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .results {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            display: none;
        }
        
        .score {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .correct {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .incorrect {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Sticky Progress Bar Styles */
        .progress-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
            padding: 15px 20px;
            margin: -30px -30px 20px -30px; /* Extend to container edges */
            border-bottom: 2px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .progress-text {
            font-weight: 600;
            color: #333;
            font-size: 1em;
            text-align: center;
            margin-top: 5px;
        }

        .progress-percentage {
            font-weight: bold;
            color: #667eea;
            font-size: 1.1em;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.5s ease;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }

        /* Add animated shine effect */
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 2s infinite;
        }

        #vocabProgressFill {
            transition: width 0.3s ease-in-out;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Adjust container padding to accommodate sticky progress bar */
        .test-section {
            padding-top: 10px;
        }

        /* Make sure the test header doesn't interfere with sticky progress */
        .test-header {
            margin-top: 0;
            margin-bottom: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .progress-container {
                padding: 12px 15px;
                margin: -20px -20px 15px -20px;
            }
            
            .progress-info {
                flex-direction: column;
                gap: 5px;
                align-items: flex-start;
            }
            
            .progress-text, .progress-percentage {
                font-size: 0.9em;
            }
        }
    
        .test-header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 10px;
        }
        
        .test-timer {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .question-result {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Gates-MacGinitie Reading Test</h1>
        
       <!-- Vocabulary Test Section -->
        <div id="vocabularySection" class="test-section active">
            <div class="test-header">
                <h2>Part 1: Vocabulary Test</h2>
                <p>Choose the word that means the same or nearly the same as the given word</p>
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Choose the word that means the same or nearly the same as the given word. Click on your answer choice for each question. You must answer all questions to proceed to the next section.</p>
            </div>
            
            <!-- Sticky Progress Bar -->
            <div class="progress-container">
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
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Read each passage carefully and choose the best answer for each question. Work as quickly and accurately as possible.</p>
            </div>

            <div class="test-timer">
                <strong>Time Remaining: <span id="timer">20:00</span></strong>
            </div>
            
            <!-- Sticky Progress Bar -->
            <div class="progress-container">
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
            </div>
            
            <div class="instructions">
                <h3>Instructions:</h3>
                <p>Read each passage carefully and choose the best answer for each question. Take your time to understand the content.</p>
            </div>
            
            <!-- Sticky Progress Bar -->
            <div class="progress-container">
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
                <button type="submit" class="submit-btn">Submit Complete Test</button>
            </form>
        </div>
        
        <!-- Results Section -->
        <div id="results" class="results"></div>
    </div>

    <script>
        // Test data from PHP - dynamically loaded
        const vocabData = <?php echo json_encode($vocabTest); ?>;
        const speedAccuracyData = <?php echo json_encode($speedAccuracyTest); ?>;
        const comprehensionData = <?php echo json_encode($comprehensionTest); ?>;
        
        let vocabAnswers = {};
        let speedAnswers = {};
        let comprehensionAnswers = {};
        let timerInterval;
        let timeRemaining = 20 * 60; // 20 minutes in seconds

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
        // function prepareSpeedTest() {
        //     return speedAccuracyData.map((item, index) => ({
        //         ...item,
        //         options: shuffle(item.options),
        //         questionNumber: index + 1
        //     }));
        // }

        // Prepare comprehension test
        // Updated function to handle nested comprehension data structure
        function prepareComprehensionTest() {
            const flattenedQuestions = [];
            let questionNumber = 1;
            
            // Flatten the nested structure
            comprehensionData.forEach(passageData => {
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

        // Updated generateComprehensionTest function
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
                questionDiv.innerHTML = `
                    <div class="question-text">${question.questionNumber}. ${question.question}</div>
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

        // // Update progress bar for vocabulary
        // function updateVocabProgress() {
        //     const totalQuestions = vocabQuestions.length;
        //     const answeredQuestions = new Set();
            
        //     // Debug: Log total questions
        //     console.log('Total vocab questions:', totalQuestions);
            
        //     // Find all checked vocab radio buttons
        //     const checkedInputs = document.querySelectorAll('input[name^="vocab_question"]:checked');
        //     console.log('Checked inputs found:', checkedInputs.length);
            
        //     checkedInputs.forEach(input => {
        //         const questionName = input.name;
        //         answeredQuestions.add(questionName);
        //         console.log('Added question:', questionName);
        //     });
            
        //     console.log('Unique answered questions:', answeredQuestions.size);
            
        //     const progress = (answeredQuestions.size / totalQuestions) * 100;
        //     document.getElementById('vocabProgressFill').style.width = progress + '%';
            
        //     // Enable proceed button if all questions answered
        //     const proceedBtn = document.getElementById('proceedToSpeedBtn');
        //     if (answeredQuestions.size === totalQuestions) {
        //         console.log('All questions answered! Enabling button.');
        //         proceedBtn.disabled = false;
        //     } else {
        //         console.log(`Only ${answeredQuestions.size}/${totalQuestions} questions answered. Button stays disabled.`);
        //         proceedBtn.disabled = true;
        //     }
        // }

        // // Also add this to check if the event listener is working
        // document.addEventListener('change', function(e) {
        //     if (e.target.type === 'radio') {
        //         console.log('Radio button changed:', e.target.name, e.target.value);
        //         if (e.target.name.startsWith('vocab_')) {
        //             console.log('Updating vocab progress...');
        //             updateVocabProgress();
        //         } else if (e.target.name.startsWith('speed_')) {
        //             updateSpeedProgress();
        //         } else if (e.target.name.startsWith('comprehension_')) {
        //             updateComprehensionProgress();
        //         }
        //     }
        // });

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
            
            console.log(`Vocab Progress: ${progress}% (${answeredCount}/${totalQuestions})`);
        }

        // Update progress bar for speed test
        function updateSpeedProgress() {
            const totalQuestions = speedQuestions.length;
            const answeredQuestions = new Set();
            
            document.querySelectorAll('input[name^="speed_question"]:checked').forEach(input => {
                const questionName = input.name;
                answeredQuestions.add(questionName);
            });
            
            const progress = (answeredQuestions.size / totalQuestions) * 100;
            document.getElementById('speedProgressFill').style.width = progress + '%';

            // Enable proceed button if all questions answered
            const proceedBtn = document.getElementById('proceedToCompreBtn');
            if (answeredQuestions.size === totalQuestions) {
                proceedBtn.disabled = false;
            } else {
                proceedBtn.disabled = true;
            }
        }

        // Update progress bar for comprehension test
        function updateComprehensionProgress() {
            const totalQuestions = comprehensionQuestions.length;
            const answeredQuestions = new Set();
            
            document.querySelectorAll('input[name^="comprehension_question"]:checked').forEach(input => {
                const questionName = input.name;
                answeredQuestions.add(questionName);
            });
            
            const progress = (answeredQuestions.size / totalQuestions) * 100;
            document.getElementById('comprehensionProgressFill').style.width = progress + '%';
        }

        // Timer function
        function startTimer() {
            timerInterval = setInterval(() => {
                timeRemaining--;
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    alert('Time is up! Submitting your test...');
                    document.getElementById('comprehensionTest').dispatchEvent(new Event('submit'));
                }
            }, 1000);
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

        // Score all three tests - FIXED
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

        // Display complete results
        function displayCompleteResults(scoreData) {
            const resultsDiv = document.getElementById('results');
            const vocabPercentage = Math.round((scoreData.vocab.correct / scoreData.vocab.total) * 100);
            const speedPercentage = Math.round((scoreData.speed.correct / scoreData.speed.total) * 100);
            const comprehensionPercentage = Math.round((scoreData.comprehension.correct / scoreData.comprehension.total) * 100);
            
            const totalCorrect = scoreData.vocab.correct + scoreData.speed.correct + scoreData.comprehension.correct;
            const totalQuestions = scoreData.vocab.total + scoreData.speed.total + scoreData.comprehension.total;
            const overallPercentage = Math.round((totalCorrect / totalQuestions) * 100);
            
            let resultsHTML = `
                <div class="score ${overallPercentage >= 70 ? 'correct' : 'incorrect'}">
                    <h2>Complete Test Results</h2>
                    <p>Vocabulary: ${scoreData.vocab.correct}/${scoreData.vocab.total} (${vocabPercentage}%)</p>
                    <p>Speed & Accuracy: ${scoreData.speed.correct}/${scoreData.speed.total} (${speedPercentage}%)</p>
                    <p>Comprehension: ${scoreData.comprehension.correct}/${scoreData.comprehension.total} (${comprehensionPercentage}%)</p>
                    <p><strong>Overall Score: ${totalCorrect}/${totalQuestions} (${overallPercentage}%)</strong></p>
                </div>
                
                <h3>Vocabulary Test Results:</h3>
            `;
            
            scoreData.vocab.results.forEach((result, index) => {
                resultsHTML += `
                    <div class="question-result ${result.isCorrect ? 'correct' : 'incorrect'}">
                        <strong>${index + 1}. ${result.question}</strong><br>
                        Your answer: ${result.userAnswer}<br>
                        Correct answer: ${result.correctAnswer}
                        ${result.isCorrect ? ' ✓' : ' ✗'}
                    </div>
                `;
            });

            resultsHTML += '<h3>Speed & Accuracy Test Results:</h3>';
            
            scoreData.speed.results.forEach((result, index) => {
                resultsHTML += `
                    <div class="question-result ${result.isCorrect ? 'correct' : 'incorrect'}">
                        <strong>${index + 1}. ${result.question.substring(0, 100)}...</strong><br>
                        Your answer: ${result.userAnswer}<br>
                        Correct answer: ${result.correctAnswer}
                        ${result.isCorrect ? ' ✓' : ' ✗'}
                    </div>
                `;
            });

            // Add comprehension test results - ADDED
            resultsHTML += '<h3>Comprehension Test Results:</h3>';
            
            scoreData.comprehension.results.forEach((result, index) => {
                resultsHTML += `
                    <div class="question-result ${result.isCorrect ? 'correct' : 'incorrect'}">
                        <strong>${index + 1}. ${result.question.substring(0, 100)}...</strong><br>
                        Your answer: ${result.userAnswer}<br>
                        Correct answer: ${result.correctAnswer}
                        ${result.isCorrect ? ' ✓' : ' ✗'}
                    </div>
                `;
            });
            
            resultsDiv.innerHTML = resultsHTML;
            resultsDiv.style.display = 'block';
            resultsDiv.scrollIntoView({ behavior: 'smooth' });
        }

        // Initialize tests
        const vocabQuestions = generateVocabTest();
        const speedQuestions = generateSpeedTest();
        const comprehensionQuestions = generateComprehensionTest();

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

        // Handle proceed to speed test
        document.getElementById('proceedToSpeedBtn').addEventListener('click', function() {
            storeVocabAnswers();
            document.getElementById('vocabularySection').classList.remove('active');
            document.getElementById('speedSection').classList.add('active');
            startTimer();
        });

        // Handle proceed to comprehension test - FIXED
        document.getElementById('proceedToCompreBtn').addEventListener('click', function() {
            storeSpeedAnswers(); // Store speed answers
            document.getElementById('speedSection').classList.remove('active');
            document.getElementById('comprehensionSection').classList.add('active');
            // Timer continues from speed test
        });

        // Handle final form submission
        document.getElementById('comprehensionTest').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            
            const scoreData = scoreCompleteTest();
            displayCompleteResults(scoreData);
        });
    </script>
</body>
</html>