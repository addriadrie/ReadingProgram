<?php
include("stories.php");

// Retrieve the story index from the URL (e.g., ?story=0)
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if ($step === 'vocab'): ?>
        <h2>Match the Word to Its Definition</h2>
        <form method="post">
            <input type="hidden" name="step" value="story">
            <ol>
                <?php foreach ($definitions as $index => $definition): ?>
                    <li>
                        <select name="answer[]">
                            <option value="">-- Choose a word --</option>
                            <?php foreach ($words as $word): ?>
                                <option value="<?php echo $word; ?>"><?php echo $word; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo $definition; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
            <button type="submit">Submit</button>
        </form>

    <?php elseif ($step === 'story'): ?>
        <div class="story">
            <h2><?php echo $story['title']; ?></h2>
            <img src="<?php echo $story['image']; ?>" alt="Story Image" style="max-width: 300px;"><br><br>
            <p><?php echo $story['content']; ?></p>
            <form method="post">
                <input type="hidden" name="step" value="postvocab">
                <button type="submit">Finish</button>
            </form>
        </div>

    <?php elseif ($step === 'postvocab'): ?>
        <div class="test">
            <h3>Fill in the Blanks</h3>
            <p><strong>Paragraph:</strong> <?php echo $story["post-vocab"]["paragraph"]; ?></p>
            <form method="post">
                <input type="hidden" name="step" value="comprehension">
                <?php foreach ($story["post-vocab"]["blanks"] as $blank): ?>
                    <p>
                        <?php echo $blank["question"]; ?><br>
                        <?php foreach ($blank["choices"] as $choice): ?>
                            <label>
                                <input type="radio" name="blank_<?php echo $blank["position"]; ?>" value="<?php echo $choice; ?>">
                                <?php echo $choice; ?>
                            </label><br>
                        <?php endforeach; ?>
                    </p>
                <?php endforeach; ?>
                <button type="submit">Continue to Comprehension</button>
            </form>
        </div>

    <?php elseif ($step === 'comprehension'): ?>
        <div class="test">
            <h3>Reading Comprehension Questions</h3>
            <form method="post" action="final.php">
                <?php foreach ($story["questions"] as $num => $q): ?>
                    <p>
                        <label for="q<?php echo $num; ?>"><?php echo $num . '. ' . $q; ?></label><br>
                        <textarea name="q<?php echo $num; ?>" rows="3" cols="60"></textarea>
                    </p>
                <?php endforeach; ?>
                <button type="submit">Submit Answers</button>
            </form>
        </div>

    <?php endif; ?>

</body>

</html>