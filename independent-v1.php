<?php
// independent.php
require_once 'independent-content.php';

// Handle AJAX request for story content
if (isset($_GET['ajax']) && isset($_GET['story_index'])) {
    $storyIndex = (int)$_GET['story_index'];
    if (isset($independentStories[$storyIndex])) {
        header('Content-Type: application/json');
        echo json_encode($independentStories[$storyIndex]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Story not found']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Independent Stories</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .open-modal-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .open-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
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

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .modal-header h2 {
            font-size: 1.8rem;
            margin: 0;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #ff6b6b;
        }

        .modal-body {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }

        .story-buttons {
            display: grid;
            gap: 15px;
        }

        .story-btn {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
            border: none;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: left;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .story-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: linear-gradient(45deg, #0984e3, #74b9ff);
        }

        /* Story Content Styles */
        .story-content {
            display: none;
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .story-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in;
        }

        .activity-section {
            display: none;
            margin-bottom: 30px;
        }

        .activity-section.active {
            display: block;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .story-title {
            font-size: 2.2rem;
            color: #2d3436;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 3px solid #74b9ff;
            padding-bottom: 10px;
        }

        .story-text {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 30px;
            color: #2d3436;
        }

        .story-text p {
            margin-bottom: 1.2em;
            text-indent: 1.5em;
        }

        .story-text p:first-child {
            text-indent: 0;
        }

        .vocabulary-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .vocabulary-section h3 {
            color: #2d3436;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .vocab-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #74b9ff;
        }

        .vocab-word {
            font-weight: bold;
            color: #0984e3;
        }

        .comprehension-section {
            background: #f1f3f4;
            padding: 20px;
            border-radius: 10px;
        }

        .comprehension-section h3 {
            color: #2d3436;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }

        .question {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 15px;
            color: #2d3436;
        }

        .options {
            list-style: none;
            padding: 0;
        }

        .options li {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .options li:hover {
            background: #e9ecef;
        }

        .options li.selected {
            background: #74b9ff;
            color: white;
            border-color: #0984e3;
        }

        .options li.correct {
            background: #00b894;
            color: white;
            border-color: #00a085;
        }

        .options li.incorrect {
            background: #e17055;
            color: white;
            border-color: #d63031;
        }

        .submit-quiz-btn, .next-section-btn {
            background: linear-gradient(45deg, #00b894, #00cec9);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1rem;
            margin: 20px 10px 0 0;
            transition: all 0.3s ease;
        }

        .submit-quiz-btn:hover, .next-section-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .quiz-results {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }

        .score-display {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .score-excellent { color: #00b894; }
        .score-good { color: #fdcb6e; }
        .score-needs-improvement { color: #e17055; }

        .vocab-quiz-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .vocab-word-question {
            font-weight: bold;
            margin-bottom: 15px;
            color: #2d3436;
            font-size: 1.2rem;
        }

        .vocab-options {
            list-style: none;
            padding: 0;
        }

        .vocab-options li {
            padding: 12px;
            margin: 8px 0;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .vocab-options li:hover {
            background: #e9ecef;
        }

        .vocab-options li.selected {
            background: #74b9ff;
            color: white;
            border-color: #0984e3;
        }

        .vocab-options li.correct {
            background: #00b894;
            color: white;
            border-color: #00a085;
        }

        .vocab-options li.incorrect {
            background: #e17055;
            color: white;
            border-color: #d63031;
        }

        .submit-vocab-btn {
            background: linear-gradient(45deg, #a29bfe, #6c5ce7);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1rem;
            margin: 20px 10px 0 0;
            transition: all 0.3s ease;
        }

        .submit-vocab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .progress-bar {
            background: #e9ecef;
            height: 8px;
            border-radius: 4px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            height: 100%;
            transition: width 0.5s ease;
        }

        .back-btn {
            background: linear-gradient(45deg, #636e72, #2d3436);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .loading {
            text-align: center;
            padding: 40px;
            font-size: 1.2rem;
            color: #74b9ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Independent Stories</h1>
            <p>Explore engaging stories with vocabulary and comprehension exercises</p>
        </div>

        <div style="text-align: center;">
            <button class="open-modal-btn" onclick="openModal()">
                📚 Select a Story
            </button>
        </div>

        <!-- Modal -->
        <div id="storyModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Choose Your Story</h2>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="story-buttons">
                        <?php foreach ($independentStories as $index => $story): ?>
                            <button class="story-btn" onclick="loadStory(<?php echo $index; ?>)">
                                <?php echo htmlspecialchars($story['title']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Story Content Area -->
        <div id="storyContent" class="story-content">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>

    <script>
        let currentStory = null;
        let currentSection = 0;
        let userAnswers = {};
        let preVocabAnswers = {};
        let postVocabAnswers = {};
        let vocabQuestions = [];
        const sections = ['pre-vocab', 'story', 'post-vocab', 'quiz', 'results'];

        function openModal() {
            document.getElementById('storyModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('storyModal').style.display = 'none';
        }

        function loadStory(index) {
            closeModal();
            
            const contentDiv = document.getElementById('storyContent');
            contentDiv.innerHTML = '<div class="loading">Loading story...</div>';
            contentDiv.classList.add('active');

            // Reset state
            currentSection = 0;
            userAnswers = {};
            preVocabAnswers = {};
            postVocabAnswers = {};
            vocabQuestions = [];

            // Fetch story data via AJAX
            fetch(`?ajax=1&story_index=${index}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        contentDiv.innerHTML = '<div class="loading">Error loading story</div>';
                        return;
                    }
                    
                    currentStory = data;
                    generateVocabQuestions();
                    displayStoryStructure();
                    showSection(0);
                })
                .catch(error => {
                    console.error('Error:', error);
                    contentDiv.innerHTML = '<div class="loading">Error loading story</div>';
                });
        }

        function generateVocabQuestions() {
            if (!currentStory.vocabs) return;
            
            const vocabEntries = Object.entries(currentStory.vocabs);
            vocabQuestions = [];
            
            vocabEntries.forEach(([word, correctDefinition], index) => {
                // Create distractors from other definitions
                const otherDefinitions = vocabEntries
                    .filter(([_, def]) => def !== correctDefinition)
                    .map(([_, def]) => def)
                    .slice(0, 3);
                
                // Shuffle options
                const options = [correctDefinition, ...otherDefinitions].sort(() => Math.random() - 0.5);
                
                vocabQuestions.push({
                    word: word,
                    options: options,
                    correct: correctDefinition,
                    index: index
                });
            });
        }

        function displayStoryStructure() {
            const contentDiv = document.getElementById('storyContent');
            
            // Format content with paragraph breaks
            const formattedContent = currentStory.content
                .split(/\s{2,}/)
                .map(paragraph => paragraph.trim())
                .filter(paragraph => paragraph.length > 0)
                .map(paragraph => `<p>${paragraph}</p>`)
                .join('');

            let preVocabQuizHtml = '';
            let postVocabQuizHtml = '';
            
            if (vocabQuestions.length > 0) {
                vocabQuestions.forEach((q, index) => {
                    const optionsHtml = q.options.map((option, optIndex) => 
                        `<li data-answer="${option}" onclick="selectVocabAnswer('pre', ${index}, '${option.replace(/'/g, "\\'")}')">
                            ${String.fromCharCode(65 + optIndex)}) ${option}
                        </li>`
                    ).join('');
                    
                    preVocabQuizHtml += `
                        <div class="vocab-quiz-item" data-vocab-question="pre-${index}">
                            <div class="vocab-word-question">What does "${q.word}" mean?</div>
                            <ul class="vocab-options">
                                ${optionsHtml}
                            </ul>
                        </div>
                    `;
                });

                vocabQuestions.forEach((q, index) => {
                    const optionsHtml = q.options.map((option, optIndex) => 
                        `<li data-answer="${option}" onclick="selectVocabAnswer('post', ${index}, '${option.replace(/'/g, "\\'")}')">
                            ${String.fromCharCode(65 + optIndex)}) ${option}
                        </li>`
                    ).join('');
                    
                    postVocabQuizHtml += `
                        <div class="vocab-quiz-item" data-vocab-question="post-${index}">
                            <div class="vocab-word-question">What does "${q.word}" mean?</div>
                            <ul class="vocab-options">
                                ${optionsHtml}
                            </ul>
                        </div>
                    `;
                });
            }

            let comprehensionQuizHtml = '';
            if (currentStory.comprehension) {
                currentStory.comprehension.forEach((q, index) => {
                    comprehensionQuizHtml += `
                        <div class="question" data-question="${index}">
                            <div class="question-text">${index + 1}. ${q.question}</div>
                            <ul class="options">
                    `;
                    for (const [key, value] of Object.entries(q.options)) {
                        comprehensionQuizHtml += `<li data-answer="${key}" onclick="selectAnswer(${index}, '${key}')">${key}) ${value}</li>`;
                    }
                    comprehensionQuizHtml += `</ul></div>`;
                });
            }

            contentDiv.innerHTML = `
                <button class="back-btn" onclick="backToSelection()">← Back to Stories</button>
                
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 20%"></div>
                </div>

                <h1 class="story-title">${currentStory.title}</h1>

                <!-- Pre-Vocabulary Quiz Section -->
                <div id="section-0" class="activity-section">
                    <div class="section-header">
                        <h2>📚 Pre-Reading Vocabulary Quiz</h2>
                        <p>Test your knowledge of key vocabulary before reading</p>
                    </div>
                    <div class="vocabulary-section">
                        ${preVocabQuizHtml}
                    </div>
                    <button class="submit-vocab-btn" onclick="submitVocabQuiz('pre')">Submit Pre-Vocabulary Quiz</button>
                    <div id="preVocabResults" class="quiz-results" style="display: none;"></div>
                </div>

                <!-- Story Section -->
                <div id="section-1" class="activity-section">
                    <div class="section-header">
                        <h2>📖 Reading</h2>
                        <p>Read the story carefully</p>
                    </div>
                    <div class="story-text">${formattedContent}</div>
                    <button class="next-section-btn" onclick="nextSection()">Continue to Post-Vocabulary Quiz →</button>
                </div>

                <!-- Post-Vocabulary Quiz Section -->
                <div id="section-2" class="activity-section">
                    <div class="section-header">
                        <h2>📝 Post-Reading Vocabulary Quiz</h2>
                        <p>Test your vocabulary knowledge after reading the story</p>
                    </div>
                    <div class="vocabulary-section">
                        ${postVocabQuizHtml}
                    </div>
                    <button class="submit-vocab-btn" onclick="submitVocabQuiz('post')">Submit Post-Vocabulary Quiz</button>
                    <div id="postVocabResults" class="quiz-results" style="display: none;"></div>
                </div>

                <!-- Comprehension Quiz Section -->
                <div id="section-3" class="activity-section">
                    <div class="section-header">
                        <h2>🤔 Comprehension Quiz</h2>
                        <p>Answer questions based on what you read</p>
                    </div>
                    <div class="comprehension-section">
                        ${comprehensionQuizHtml}
                    </div>
                    <button class="submit-quiz-btn" onclick="submitComprehensionQuiz()">Submit Comprehension Quiz</button>
                </div>

                <!-- Results Section -->
                <div id="section-4" class="activity-section">
                    <div class="section-header">
                        <h2>🎉 Final Results</h2>
                        <p>Here's your complete performance summary!</p>
                    </div>
                    <div id="finalResults" class="quiz-results">
                        <!-- Results will be populated here -->
                    </div>
                    <button class="next-section-btn" onclick="restartActivity()">Try Another Story</button>
                </div>
            `;
        }

        function showSection(sectionIndex) {
            // Hide all sections
            for (let i = 0; i < sections.length; i++) {
                const section = document.getElementById(`section-${i}`);
                if (section) section.classList.remove('active');
            }

            // Show current section
            const currentSectionEl = document.getElementById(`section-${sectionIndex}`);
            if (currentSectionEl) {
                currentSectionEl.classList.add('active');
            }

            // Update progress bar
            const progress = ((sectionIndex + 1) / sections.length) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;

            currentSection = sectionIndex;
        }

        function nextSection() {
            if (currentSection < sections.length - 1) {
                showSection(currentSection + 1);
            }
        }

        function selectVocabAnswer(type, questionIndex, answer) {
            if (type === 'pre') {
                preVocabAnswers[questionIndex] = answer;
            } else {
                postVocabAnswers[questionIndex] = answer;
            }
            
            // Update UI to show selection
            const question = document.querySelector(`[data-vocab-question="${type}-${questionIndex}"]`);
            const options = question.querySelectorAll('.vocab-options li');
            
            options.forEach(option => {
                option.classList.remove('selected');
                if (option.dataset.answer === answer) {
                    option.classList.add('selected');
                }
            });
        }

        function selectAnswer(questionIndex, answer) {
            userAnswers[questionIndex] = answer;
            
            // Update UI to show selection
            const question = document.querySelector(`[data-question="${questionIndex}"]`);
            const options = question.querySelectorAll('.options li');
            
            options.forEach(option => {
                option.classList.remove('selected');
                if (option.dataset.answer === answer) {
                    option.classList.add('selected');
                }
            });
        }

        function submitVocabQuiz(type) {
            const answers = type === 'pre' ? preVocabAnswers : postVocabAnswers;
            let correctAnswers = 0;
            const totalQuestions = vocabQuestions.length;

            // Check answers and update UI
            vocabQuestions.forEach((q, index) => {
                const question = document.querySelector(`[data-vocab-question="${type}-${index}"]`);
                const options = question.querySelectorAll('.vocab-options li');
                const userAnswer = answers[index];
                const correctAnswer = q.correct;

                if (userAnswer === correctAnswer) {
                    correctAnswers++;
                }

                // Only show correct/incorrect visual feedback for post-vocab quiz
                if (type === 'post') {
                    options.forEach(option => {
                        const optionText = option.dataset.answer;
                        option.style.pointerEvents = 'none';
                        
                        if (optionText === correctAnswer) {
                            option.classList.add('correct');
                        } else if (optionText === userAnswer && userAnswer !== correctAnswer) {
                            option.classList.add('incorrect');
                        }
                    });
                } else {
                    // For pre-vocab, just disable clicking
                    options.forEach(option => {
                        option.style.pointerEvents = 'none';
                    });
                }
            });

            // Only show results for post-vocab quiz
            if (type === 'post') {
                // Calculate and display results
                const percentage = Math.round((correctAnswers / totalQuestions) * 100);
                let scoreClass = 'score-needs-improvement';
                let feedback = 'Keep studying! Vocabulary takes practice.';
                
                if (percentage >= 80) {
                    scoreClass = 'score-excellent';
                    feedback = 'Excellent vocabulary knowledge!';
                } else if (percentage >= 60) {
                    scoreClass = 'score-good';
                    feedback = 'Good vocabulary understanding!';
                }

                const resultsDiv = document.getElementById(`${type}VocabResults`);
                resultsDiv.innerHTML = `
                    <div class="score-display ${scoreClass}">${correctAnswers}/${totalQuestions} (${percentage}%)</div>
                    <p>${feedback}</p>
                `;
                resultsDiv.style.display = 'block';
            }

            // Hide submit button and show continue button
            const submitBtn = document.querySelector(`#section-${currentSection} .submit-vocab-btn`);
            submitBtn.style.display = 'none';
            
            const continueBtn = document.createElement('button');
            continueBtn.className = 'next-section-btn';
            continueBtn.textContent = type === 'pre' ? 'Continue to Story →' : 'Continue to Comprehension Quiz →';
            continueBtn.onclick = nextSection;
            submitBtn.parentNode.appendChild(continueBtn);
        }

        function submitComprehensionQuiz() {
            let correctAnswers = 0;
            const totalQuestions = currentStory.comprehension.length;

            // Show correct/incorrect answers
            currentStory.comprehension.forEach((q, index) => {
                const question = document.querySelector(`[data-question="${index}"]`);
                const options = question.querySelectorAll('.options li');
                const userAnswer = userAnswers[index];
                const correctAnswer = q.answer;

                if (userAnswer === correctAnswer) {
                    correctAnswers++;
                }

                options.forEach(option => {
                    const optionKey = option.dataset.answer;
                    option.style.pointerEvents = 'none';
                    
                    if (optionKey === correctAnswer) {
                        option.classList.add('correct');
                    } else if (optionKey === userAnswer && userAnswer !== correctAnswer) {
                        option.classList.add('incorrect');
                    }
                });
            });

            // Hide submit button
            document.querySelector('.submit-quiz-btn').style.display = 'none';
            
            // Store comprehension results and advance
            window.comprehensionResults = {
                correct: correctAnswers,
                total: totalQuestions,
                percentage: Math.round((correctAnswers / totalQuestions) * 100)
            };
            
            // Auto-advance to results after 2 seconds
            setTimeout(() => {
                showFinalResults();
                nextSection();
            }, 2000);
        }

        function showFinalResults() {
            // Calculate all results
            const preVocabCorrect = Object.keys(preVocabAnswers).filter(key => 
                preVocabAnswers[key] === vocabQuestions[key].correct
            ).length;
            const preVocabTotal = vocabQuestions.length;
            const preVocabPercentage = Math.round((preVocabCorrect / preVocabTotal) * 100);

            const postVocabCorrect = Object.keys(postVocabAnswers).filter(key => 
                postVocabAnswers[key] === vocabQuestions[key].correct
            ).length;
            const postVocabTotal = vocabQuestions.length;
            const postVocabPercentage = Math.round((postVocabCorrect / postVocabTotal) * 100);

            const comprehensionResults = window.comprehensionResults || { correct: 0, total: 0, percentage: 0 };

            // Calculate improvement
            const vocabImprovement = postVocabPercentage - preVocabPercentage;
            let improvementText = '';
            let improvementClass = '';
            
            if (vocabImprovement > 0) {
                improvementText = `📈 Improved by ${vocabImprovement}%`;
                improvementClass = 'score-excellent';
            } else if (vocabImprovement === 0) {
                improvementText = `📊 Maintained same level`;
                improvementClass = 'score-good';
            } else {
                improvementText = `📉 Decreased by ${Math.abs(vocabImprovement)}%`;
                improvementClass = 'score-needs-improvement';
            }

            // Calculate overall performance
            const overallPercentage = Math.round(
                (preVocabPercentage + postVocabPercentage + comprehensionResults.percentage) / 3
            );
            let overallClass = 'score-needs-improvement';
            let overallFeedback = 'Keep practicing! Reading comprehension improves with time.';
            
            if (overallPercentage >= 80) {
                overallClass = 'score-excellent';
                overallFeedback = 'Outstanding performance! You have excellent reading comprehension skills.';
            } else if (overallPercentage >= 60) {
                overallClass = 'score-good';
                overallFeedback = 'Good work! You show solid understanding of the material.';
            }

            // Display comprehensive results
            const resultsDiv = document.getElementById('finalResults');
            resultsDiv.innerHTML = `
                <div class="score-display ${overallClass}">Overall Score: ${overallPercentage}%</div>
                <p style="font-size: 1.2rem; margin-bottom: 20px;">${overallFeedback}</p>
                
                <div style="display: grid; gap: 15px; margin-bottom: 20px;">
                    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <h4 style="color: #2d3436; margin-bottom: 10px;">📚 Pre-Reading Vocabulary</h4>
                        <div class="score-display" style="font-size: 1.5rem;">${preVocabCorrect}/${preVocabTotal} (${preVocabPercentage}%)</div>
                    </div>
                    
                    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <h4 style="color: #2d3436; margin-bottom: 10px;">📝 Post-Reading Vocabulary</h4>
                        <div class="score-display" style="font-size: 1.5rem;">${postVocabCorrect}/${postVocabTotal} (${postVocabPercentage}%)</div>
                        <div class="${improvementClass}" style="font-weight: bold; margin-top: 10px;">${improvementText}</div>
                    </div>
                    
                    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <h4 style="color: #2d3436; margin-bottom: 10px;">🤔 Reading Comprehension</h4>
                        <div class="score-display" style="font-size: 1.5rem;">${comprehensionResults.correct}/${comprehensionResults.total} (${comprehensionResults.percentage}%)</div>
                    </div>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-top: 20px;">
                    <h4 style="color: #2d3436; margin-bottom: 15px;">💡 Learning Insights</h4>
                    <ul style="text-align: left; color: #636e72;">
                        ${vocabImprovement > 0 ? 
                            '<li>✅ Great job! Reading the story helped improve your vocabulary understanding.</li>' : 
                            '<li>📖 Consider reviewing the vocabulary words and their context in the story.</li>'
                        }
                        ${comprehensionResults.percentage >= 70 ? 
                            '<li>✅ You demonstrated good reading comprehension skills.</li>' : 
                            '<li>📚 Try reading more carefully and looking for key details in the text.</li>'
                        }
                        ${overallPercentage >= 70 ? 
                            '<li>🎯 You\'re ready for more challenging reading materials!</li>' : 
                            '<li>💪 Keep practicing with similar stories to build your skills.</li>'
                        }
                    </ul>
                </div>
            `;
        }

        function restartActivity() {
            // Reset all state
            currentStory = null;
            currentSection = 0;
            userAnswers = {};
            preVocabAnswers = {};
            postVocabAnswers = {};
            vocabQuestions = [];
            window.comprehensionResults = null;
            
            // Hide story content and show modal
            document.getElementById('storyContent').classList.remove('active');
            openModal();
        }
    
    </script>
</body>
</html>