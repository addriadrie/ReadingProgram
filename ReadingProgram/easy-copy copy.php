<?php
include("stories.php");

$storyKey = $_GET['story'] ?? null;

// Step control
$step = $_POST['step'] ?? 'vocab';

// Check if a valid storyKey is provided
$storyIndex = $storyKey !== null && is_numeric($storyKey) && isset($easy[$storyKey]) ? $storyKey : 0;
$story = $easy[$storyIndex - 1];

// Define vocab and definitions for the chosen story
$prevocab = $story['pre-vocab'];
$words = array_keys($prevocab);
$definitions = array_values($prevocab);

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
            max-width: 800px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
        }

        .title,
        .section-title,
        .story-title {
            font-family: 'Chewy', cursive;
            color: #ffbd59;
            text-shadow: -1px -1px 0 #ffffff, 1px -1px 0 #ffffff, -1px 1px 0 #ffffff, 1px 1px 0 #ffffff, 0 0 5px #ffbd59, 0 0 5px #fff199;
            text-align: center;
            margin-bottom: 1rem;
        }

        .title {
            font-size: 3.5rem;
            font-weight: bolder;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .story-title {
            font-size: 2.8rem;
        }

        /* Vocab matching styles */
        ol {
            padding-left: 2rem;
        }

        li {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #dddddd;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            transition: all 0.3s ease;
            min-width: 180px;
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
            flex-direction: column;
            align-items: center;
            text-align: justify;
            line-height: 1.6;
            font-size: 1.1rem;
        }

        .story-image {
            max-width: 300px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Fill in the blanks styles */
        .paragraph {
            background-color: rgba(255, 255, 255, 0.5);
            padding: 15px;
            border-radius: 8px;
            line-height: 1.6;
            font-size: 1.05rem;
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
    </style>
</head>

<body>

    <div class="container">
        <?php if ($step === 'vocab'): ?>
            <p class="title">Match the Word to Its Definition</p>
            <form method="post" id="vocabForm">
                <input type="hidden" name="step" value="story">
                <ol id="definitionList">
                    <?php foreach ($definitions as $index => $definition): ?>
                        <li>
                            <select name="answer[]" class="word-select" onchange="updateOptions()">
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
                <div class="story-content">
                    <img src="<?php echo $story['image']; ?>" alt="Story Image" class="story-image">
                    <p><?php echo $story['content']; ?></p>
                </div>
                <form method="post">
                    <input type="hidden" name="step" value="postvocab">
                    <button type="submit">Continue</button>
                </form>
            </div>

        <?php elseif ($step === 'postvocab'): ?>
            <div class="test">
                <h3 class="section-title">Fill in the Blanks</h3>
                <p class="paragraph"><strong>Paragraph:</strong> <?php echo $story["post-vocab"]["paragraph"]; ?></p>
                <form method="post">
                    <input type="hidden" name="step" value="comprehension">
                    <div class="blanks-container">
                        <?php foreach ($story["post-vocab"]["blanks"] as $blank): ?>
                            <div class="blank-question">
                                <p><?php echo $blank["question"]; ?></p>
                                <div class="choices">
                                    <?php foreach ($blank["choices"] as $choice): ?>
                                        <label class="choice-label">
                                            <input type="radio" name="blank_<?php echo $blank["position"]; ?>" value="<?php echo $choice; ?>">
                                            <span><?php echo $choice; ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit">Continue</button>
                </form>
            </div>

        <?php elseif ($step === 'comprehension'): ?>
            <div class="test">
                <h3 class="section-title">Reading Comprehension Questions</h3>
                <form method="post" action="final.php">
                    <div class="questions-container">
                        <?php foreach ($story["questions"] as $num => $q): ?>
                            <div class="question-item">
                                <label for="q<?php echo $num; ?>"><?php echo $num . '. ' . $q; ?></label>
                                <textarea name="q<?php echo $num; ?>" rows="3" cols="60" class="answer-textarea"></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit">Submit Answers</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateOptions);
    </script>
    
</body>

</html>