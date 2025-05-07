<?php
$storyKey = $_GET['story'] ?? null;

// Step control
$step = $_POST['step'] ?? 'vocab';

// Data

if ($storyKey === 'StoryOne') {
$prevocab = [
    "dimmed" => "to turn down lights so that a room is darker",
    "anniversary" => "the same date that an important event happened",
    "document" => "a paper that gives information or proof of something",
    "commemorate" => "to celebrate the memory of something",
    "cuisine" => "a particular type of cooking"
];

$story = [
    "title" => "The Graduation Trip",
    "image" => "images/1-graduation.png",
    "content" => "Joanna, Benny, Doris, Michelle, Flora, Shawn, Luc, Penny, and Gordon were all very excited. Their parents had promised to send them on a world tour for their graduation present. The children would have the responsibility of planning their own itinerary. They would have a whole month to travel, so they would be able to visit many places.",
    "post-vocab" => [
        "paragraph" => "Photosynthesis is the process by which plants make their own food using sunlight, carbon dioxide, and water.",
        "blanks" => [
            [
                "position" => 1,
                "question" => "Photosynthesis is the process by which plants make their own ____. ",
                "choices" => ["food", "shelter", "energy"],
                "answer" => "food"
            ],
            [
                "position" => 2,
                "question" => "They use sunlight, carbon dioxide, and ____. ",
                "choices" => ["nitrogen", "oxygen", "water"],
                "answer" => "water"
            ]
        ]
    ],
    "questions" => [
        "1" => "What did the children's parents promise to do?",
        "2" => "Who went on the trip?",
        "3" => "Where did the children go to plan their itinerary?",
        "4" => "What did the children decide their itinerary should focus on?",
        "5" => "What was the children's first destination?"
    ]
];
}

$words = array_keys($prevocab);
$definitions = array_values($prevocab);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Step-by-Step Learning</title>
  <style>
    body { font-family: Arial; margin: 20px; }
    select, textarea { margin-top: 10px; margin-bottom: 20px; }
    .story, .test { margin-top: 40px; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; }
    .card { margin-bottom: 20px; }
    button { padding: 10px 20px; }
  </style>
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
