<?php 

// Gates-MacGinitie Reading Test

// Vocabulary
$vocabTest = [
    ['question' => 'red', 'options' => ['one', 'bird', 'color', 'barn', 'dog'], 'answer' => 'color'],
    ['question' => 'muddy', 'options' => ['stupid', 'glowing', 'dirty', 'plot', 'vessel'], 'answer' => 'dirty'],
    ['question' => 'leap', 'options' => ['climb', 'green', 'swim', 'jump', 'nap'], 'answer' => 'jump'],
    ['question' => 'shower', 'options' => ['window', 'clown', 'film', 'grass', 'rain'], 'answer' => 'rain'],
    ['question' => 'big', 'options' => ['little', 'large', 'easy', 'now', 'fix'], 'answer' => 'large'],
    ['question' => 'shoot', 'options' => ['fire', 'feel', 'fence', 'yell', 'call'], 'answer' => 'fire'],
    ['question' => 'bracelet', 'options' => ['jewelry', 'pail', 'tool', 'embrace', 'splint'], 'answer' => 'jewelry'],
    ['question' => 'plum', 'options' => ['fruit', 'plan', 'good', 'iron', 'deep'], 'answer' => 'fruit'],
    ['question' => 'bravery', 'options' => ['modesty', 'cheering', 'weather', 'courage', 'flavor'], 'answer' => 'courage'],
    ['question' => 'miracle', 'options' => ['hymn', 'wonder', 'peak', 'storm', 'shackle'], 'answer' => 'wonder'],
    ['question' => 'walk', 'options' => ['wait', 'say', 'try', 'find', 'go'], 'answer' => 'go'],
    ['question' => 'father', 'options' => ['wing', 'paper', 'land', 'house', 'man'], 'answer' => 'man'],
    ['question' => 'mouse', 'options' => ['animal', 'hole', 'bug', 'nose', 'dress'], 'answer' => 'animal'],
    ['question' => 'talent', 'options' => ['trade', 'time', 'prize', 'skill', 'tale'], 'answer' => 'skill'],
    ['question' => 'receive', 'options' => ['sell', 'ticket', 'fool', 'basket', 'accept'], 'answer' => 'accept'],
    ['question' => 'ambush', 'options' => ['shrub', 'perish', 'burn-up', 'trap', 'retreat'], 'answer' => 'trap'],
    ['question' => 'cabin', 'options' => ['car', 'cradle', 'shelf', 'hut', 'trunk'], 'answer' => 'hut'],
    ['question' => 'vibration', 'options' => ['offense', 'inspiration', 'spirit', 'flying', 'shaking'], 'answer' => 'shaking'],
    ['question' => 'drowsy', 'options' => ['sleepy', 'low', 'messy', 'sorry', 'ugly'], 'answer' => 'sleepy'],
    ['question' => 'tune', 'options' => ['fish', 'spin', 'melody', 'jug', 'marvel'], 'answer' => 'melody'],
    ['question' => 'displace', 'options' => ['exhibits', 'offend', 'denounce', 'remove', 'settle'], 'answer' => 'remove'],
    ['question' => 'overcome', 'options' => ['play', 'fear', 'arrive', 'out', 'defeat'], 'answer' => 'defeat'],
    ['question' => 'petroleum', 'options' => ['covering', 'tomb', 'rock', 'floor', 'oil'], 'answer' => 'oil'],
    ['question' => 'terrify', 'options' => ['scorch', 'frighten', 'claim', 'assure', 'submit'], 'answer' => 'frighten'],
    ['question' => 'haul', 'options' => ['push', 'hold', 'drag', 'tear', 'growl'], 'answer' => 'drag'],
    ['question' => 'muscular', 'options' => ['tricky', 'strong', 'mole', 'filthy', 'sticky'], 'answer' => 'strong'],
    ['question' => 'disaster', 'options' => ['severe', 'show', 'misfortune', 'alert', 'object'], 'answer' => 'misfortune'],
    ['question' => 'massacre', 'options' => ['group', 'enlarge', 'manage', 'slaughter', 'section'], 'answer' => 'slaughter'],
    ['question' => 'combat', 'options' => ['win', 'push', 'army', 'fight', 'dense'], 'answer' => 'fight'],
    ['question' => 'transcript', 'options' => ['cave', 'copy', 'career', 'shipment', 'voyage'], 'answer' => 'copy'],
    ['question' => 'grapple', 'options' => ['struggle', 'lift', 'discover', 'crawl', 'sink'], 'answer' => 'struggle'],
    ['question' => 'gelatin', 'options' => ['knight', 'jelly', 'horse', 'speech', 'tent'], 'answer' => 'jelly'],
    ['question' => 'hesitation', 'options' => ['defiance', 'guilt', 'reward', 'delay', 'dwelling'], 'answer' => 'delay'],
    ['question' => 'villainy', 'options' => ['smooth', 'cottage', 'treasure', 'town', 'evil'], 'answer' => 'evil'],
    ['question' => 'outstretch', 'options' => ['injure', 'extend', 'flex', 'break', 'area'], 'answer' => 'extend'],
    ['question' => 'meager', 'options' => ['much', 'unite', 'scant', 'brown', 'through'], 'answer' => 'scant'],
    ['question' => 'juvenile', 'options' => ['wharf', 'gate', 'harmful', 'aged', 'youthful'], 'answer' => 'youthful'],
    ['question' => 'authentic', 'options' => ['beautiful', 'happy', 'real', 'horrible', 'better'], 'answer' => 'real'],
    ['question' => 'riotous', 'options' => ['flighty', 'strange', 'wild', 'immense', 'rosy'], 'answer' => 'wild'],
    ['question' => 'nourish', 'options' => ['sustain', 'fanfare', 'nasty', 'beast', 'cleanse'], 'answer' => 'sustain'],
    ['question' => 'flaw', 'options' => ['blend', 'fault', 'cement', 'beaten', 'laughed'], 'answer' => 'fault'],
    ['question' => 'chastise', 'options' => ['punish', 'applaud', 'fasten', 'insist', 'augment'], 'answer' => 'punish'],
    ['question' => 'whisk', 'options' => ['snip', 'dig', 'smell', 'brush', 'sneeze'], 'answer' => 'brush'],
    ['question' => 'competence', 'options' => ['ability', 'fee', 'mischief', 'umpire', 'context'], 'answer' => 'ability'],
    ['question' => 'negligent', 'options' => ['very wise', 'careless', 'lavish', 'delicate', 'courteous'], 'answer' => 'careless'],
    ['question' => 'inequality', 'options' => ['absence', 'foreign', 'difference', 'similarity', 'poor'], 'answer' => 'difference'],
    ['question' => 'tumultuous', 'options' => ['gruffly', 'grand', 'cloudy', 'adventurous', 'disorderly'], 'answer' => 'disorderly'],
    ['question' => 'vehement', 'options' => ['violent', 'attractive', 'robe', 'road', 'secret'], 'answer' => 'violent'],
    ['question' => 'conspicuous', 'options' => ['obvious', 'fake', 'expensive', 'knowing', 'suspect'], 'answer' => 'obvious'],
    ['question' => 'ultimate', 'options' => ['awkward', 'final', 'demand', 'quiet', 'clever'], 'answer' => 'final']
];

// Speed and Accuracy
$speedAccuracyTest = [
    [
        'question' => 'The gray squirrels dart quickly along the branches and leaf from tree to tree.  They scamper over the ground hunting for nuts.  These animals move __________ .', 
        'options' => ['slowly', 'quickly', 'awkwardly', 'heavily'], 
        'answer' => 'quickly'
    ],
    [
        'question' => 'The pilgrims arrived in the New World in 1920... On what had they traveled?', 
        'options' => ['airplane', 'train', 'boat', 'automobile'], 
        'answer' => 'boat'
    ],
    [
        'question' => 'Ohio is an Indian word which means ‘beautiful river’.  Many of our states have Indian names.  From what people did we get the word ‘Ohio’?', 
        'options' => ['Italian', 'Spanish', 'English', 'Indian'], 
        'answer' => 'Indian'
    ],
    [
        'question' => 'The loon is a bird with a mysterious cry.  It is the size of a large duck and swims very fast under water to catch its food.  What does the loon eat?', 
        'options' => ['nuts', 'corn', 'fish', 'bread'], 
        'answer' => 'fish'
    ],
    [
        'question' => 'Plants die without light.  One way of killing a small patch of Poison Ivy is to cover it with heavy paper.  Soon it will die because it can get no __________ .',
        'options' => ['soil', 'paper', 'light', 'leaves'], 
        'answer' => 'light'
    ],
    [
        'question' => 'Gutta-Perch is a gum made from the juice of a certain tropical tree.  After the juice is boiled, it becomes rubbery. Gutta-Percha is a __________ .', 
        'options' => ['gum', 'native', 'tool', 'fish'], 
        'answer' => 'gum'
    ],
    [
        'question' => 'A frog’s skin is smooth and bare.  He must keep it moist or it will die.  He lives near ponds and streams.  What does he need?', 
        'options' => ['bread', 'water', 'clothes', 'waves'], 
        'answer' => 'water'
    ],
    [
        'question' => 'In the 1600’s, the British Navy found that New England’s tail pines made good masts for their sailing vessels.  These pines were used on __________ .', 
        'options' => ['buses', 'planes', 'ships', 'trains'], 
        'answer' => 'ships'
    ],
    [
        'question' => 'The powder horn was made from the horn of a cow or buffalo.  The hollow inside held gunpowder.  This horn was used with a __________ .', 
        'options' => ['gun', 'boat', 'ear', 'drain'], 
        'answer' => 'gun'
    ],
    [
        'question' => 'A skin diver often wears a face mask of rubber and glass, and uses a snorkel tube for breathing.  He uses the snorkel tube when he needs __________ .', 
        'options' => ['food', 'heat', 'light', 'air'], 
        'answer' => 'air'
    ],
    [
        'question' => 'John Chapman walked miles planting apple seed in clearings and in the wilderness  to help the pioneer.  People called him, not John Chapman but John __________ .', 
        'options' => ['Alden', 'Appleseed', 'Bull', 'Crusee'], 
        'answer' => 'Appleseed'
    ],
    [
        'question' => 'The dormouse is a furry animal somewhat large than a rat.  It lives in nuts and sleeps all winter. In size, this animal is most like a  __________ .', 
        'options' => ['goat', 'squirrel', 'cow', 'fly'], 
        'answer' => 'squirrel'
    ],
    [
        'question' => '“Wheh,”  sighed Janie as she dropped her school books.  “I’ll change to some cool clothes and then get a glass of cold lemonade.  It really feels like __________ .”', 
        'options' => ['summer', 'snow', 'winter', 'running'], 
        'answer' => 'summer'
    ],
    [
        'question' => 'A rattlesnake is dangerous for it can inject poison into its victim through its fangs.  It does not always rattle before biting.  One should avoid this __________ .', 
        'options' => ['food', 'snake', 'bird', 'place'], 
        'answer' => 'snake'
    ],
    [
        'question' => 'Peanuts are not nuts at all.  They grow underground and are related to peas and beans.  To gather these “nuts” what would you have to do?', 
        'options' => ['climb', 'prune', 'swim', 'dig'], 
        'answer' => 'dig'
    ],
    [
        'question' => 'The French people gave the United States the Statue of Liberty.  It stands in New York Harbor.  From what county did this statue come?', 
        'options' => ['New York', 'Liberia', 'America', 'France'], 
        'answer' => 'France'
    ],
    [
        'question' => 'When flying in the proper lane, an airplane pilot can hear a certain radio signal.  Straying off course bring another signal.  These signals help the __________ .', 
        'options' => ['singer', 'pilot', 'radiologist', 'motorist'], 
        'answer' => 'pilot'
    ],
    [
        'question' => 'Early man sometimes built his house in stilts over a steam or fond. It is difficult now to find ruins of these houses built over __________ .', 
        'options' => ['battles', 'fire', 'water', 'cliffs'], 
        'answer' => 'water'
    ],
    [
        'question' => 'Dogs trained by the monks of Saint Bernard is the Swiss Alps search for persons lost in snowstorms.  These dogs have a keen sense of smell.  They are __________ .', 
        'options' => ['useful', 'wild', 'lost', 'disloyal'], 
        'answer' => 'useful'
    ],
    [
        'question' => 'When Captain John Smith was caught, the chief ordered him killed.  The chief’s daughter was successful in begging for Smith’s life.  Who was saved?', 
        'options' => ['chief', 'daughter', 'beggar', 'Smith'], 
        'answer' => 'Smith'
    ],
    [
        'question' => 'The white flowers among the pretty green leaves of the chokeberry bush are later replaced by bunches of red berries. How does the chokeberry look?', 
        'options' => ['bright', 'homely', 'dull', 'crumpled'], 
        'answer' => 'bright'
    ],
    [
        'question' => 'Puck is a character in one of Shakespeare’s plays.   He is an impish fairy who is always playing tricks on others.  What word best describes Puck?', 
        'options' => ['mischievous', 'studious', 'sorry', 'weary'], 
        'answer' => 'mischievous'
    ],
    [
        'question' => 'The code for the display of our flag states that the flag should not touch the ground on being raised or lowered.  Every American should know this __________ .', 
        'options' => ['color', 'number', 'president', 'code'], 
        'answer' => 'code'
    ],
    [
        'question' => 'It tells you what is happening all over the world.  It gives TV listings and the weather.  It is made of paper and is printed daily.  It is a __________ .', 
        'options' => ['newspaper', 'book', 'radio', 'telegram'], 
        'answer' => 'newspaper'
    ],
    [
        'question' => 'Although an eagle is strong, it can carry only about eight pounds, so it could not carry a big child.  Which of the following could it carry?', 
        'options' => ['horse', 'rabbit', 'barge', 'man'], 
        'answer' => 'rabbit'
    ],
    [
        'question' => 'The first American underwater tunnel for cars was built under the river between New York and New Jersey.  What goes through this tunnel?', 
        'options' => ['trains', 'rivers', 'cars', 'ants'], 
        'answer' => 'cars'
    ],
    [
        'question' => 'Trees are helped by birds that rid their leaves and barks of many insects.  Trees provide these birds with places for this nest with __________ .', 
        'options' => ['food', 'roots', 'color', 'wings'], 
        'answer' => 'food'
    ],
    [
        'question' => 'One of the hardest things for a student pilot to learn is how to come safely back to earth.  He is usually has less trouble in taking off or banking than in __________ .', 
        'options' => ['landing', 'banking', 'climbing', 'taking off'], 
        'answer' => 'landing'
    ],
    [
        'question' => 'At night, huge electric signs made New York City’s Broadways, a fairyland of light and color.  How does this famous street looks like at night?', 
        'options' => ['dark', 'gay', 'dim', 'gloomy'], 
        'answer' => 'gay'],
    [
        'question' => 'Penicillin, used in the treatment of infectious disease, was discovered by Alexander Fleming.  This discovery was giant step forward in the Science of __________ .', 
        'options' => ['Philately', 'Philosophy', 'Medicine', 'Plastics'], 
        'answer' => 'Medicine'
    ],
    [
        'question' => 'Almost all paper is made from wool.  Hemlock, Spruce and Balsam are often used.  These are all soft woods.  What kind of wood is Hemlock?', 
        'options' => ['red', 'hard', 'spruce', 'soft'], 
        'answer' => 'soft'
    ],
    [
        'question' => 'London, the capital of Great Britain, is on the Thames river, Florence in Italy, is on the Ago River.  What river runs through the city if London?', 
        'options' => ['Agno', 'Britain', 'Thames', 'Florence'], 
        'answer' => 'Thames'
    ],
    [
        'question' => 'A destroyer recovered the space capsule at sea.  John H. Gleen had made the first American orbital flight and was inside the capsule when it was __________ .', 
        'options' => ['lost', 'recovered', 'torpedoed', 'painted'], 
        'answer' => 'recovered'
    ],
    [
        'question' => 'Pennsylvania was a tract of land given to William Pen in payment of a debt owed to his father by King Charles of England.  Later, it became a __________ .', 
        'options' => ['seaway', 'city', 'highway', 'state'], 
        'answer' => 'state'
    ],
    [
        'question' => 'The trapdoor spider builds her home in the ground.  It is lined with cobwebs and has small door.  This order is the reason for the name of this __________ .', 
        'options' => ['spider', 'fly', 'web', 'secret'], 
        'answer' => 'spider'
    ],
    [
        'question' => 'A diamond is the hardest natural substance known. Diamond cutters use one diamond to cut another.  In addition to being a jewel, a diamond maybe a __________ .', 
        'options' => ['light', 'tool', 'radio', 'cut'], 
        'answer' => 'tool'
    ]
];

$comprehensionTest = [
    [
        'passage' => 'Mother and Dad have been shopping. When they returned they brought new skates for the twins. The children were very (1)_____. They put them right on and went (2) _____.',
        'questions' => [
            [
                'number' => 1,
                'options' => [
                    'A' => 'unhappy',
                    'B' => 'empty', 
                    'C' => 'short',
                    'D' => 'heavy',
                    'E' => 'happy'
                ],
                'correct_answer' => 'E'
            ],
            [
                'number' => 2,
                'options' => [
                    'A' => 'swimming',
                    'B' => 'skating',
                    'C' => 'sledding',
                    'D' => 'walking',
                    'E' => 'reading'
                ],
                'correct_answer' => 'B'
            ]
        ]
    ],
    
    [
        'passage' => 'In building a nest, the mother bird may use twigs, mud, bits of straw, or piece of string. When the nest is nearly (3)_____, she may line it with feathers pulled from her own breast. These (4)_____ make the nest a soft home for the baby birds.',
        'questions' => [
            [
                'number' => 3,
                'options' => [
                    'A' => 'broken',
                    'B' => 'forgotten',
                    'C' => 'finished',
                    'D' => 'empty',
                    'E' => 'missed'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 4,
                'options' => [
                    'A' => 'babies',
                    'B' => 'sticks',
                    'C' => 'feathers',
                    'D' => 'nests',
                    'E' => 'pebbles'
                ],
                'correct_answer' => 'C'
            ]
        ]
    ],
    
    [
        'passage' => '"There\'s a good wind (5) _____," said Dave. "Just the day to fly my kite." In an hour, the wind became much (6) _____ and Dave lost his kite.',
        'questions' => [
            [
                'number' => 5,
                'options' => [
                    'A' => 'mill',
                    'B' => 'bellow',
                    'C' => 'yesterday',
                    'D' => 'belong',
                    'E' => 'blowing'
                ],
                'correct_answer' => 'E'
            ],
            [
                'number' => 6,
                'options' => [
                    'A' => 'better',
                    'B' => 'less',
                    'C' => 'smaller',
                    'D' => 'stronger',
                    'E' => 'gentle'
                ],
                'correct_answer' => 'D'
            ]
        ]
    ],
    
    [
        'passage' => 'If it were not for their coat of white fur, polar bears would easily be seen by hunters. As it is, they look so much like the surrounding (7) _____ that hunters often do not see them until they (8) _____.',
        'questions' => [
            [
                'number' => 7,
                'options' => [
                    'A' => 'snow',
                    'B' => 'coal',
                    'C' => 'dirt',
                    'D' => 'sugar',
                    'E' => 'water'
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 8,
                'options' => [
                    'A' => 'aren\'t',
                    'B' => 'move',
                    'C' => 'hide',
                    'D' => 'move',
                    'E' => 'aim'
                ],
                'correct_answer' => 'B'
            ]
        ]
    ],
    
    [
        'passage' => 'The porter who makes up the beds on a train has other (9) _____ too. For example, he helps the passengers with their (10) _____ as they arrive at their destinations. In general, he tries to make them comfortable.',
        'questions' => [
            [
                'number' => 9,
                'options' => [
                    'A' => 'word',
                    'B' => 'engines',
                    'C' => 'wise',
                    'D' => 'fares',
                    'E' => 'responsibilities'
                ],
                'correct_answer' => 'E'
            ],
            [
                'number' => 10,
                'options' => [
                    'A' => 'baggage',
                    'B' => 'customers',
                    'C' => 'taxes',
                    'D' => 'tips',
                    'E' => 'comfortable'
                ],
                'correct_answer' => 'A'
            ]
        ]
    ],
    
    [
        'passage' => 'Skating down a driveway is often not safe. If the driveway is steep and one is coasting fast, it is (11) _____ to make the sharp turn that is necessary to avoid going into the (12) _____.',
        'questions' => [
            [
                'number' => 11,
                'options' => [
                    'A' => 'warning',
                    'B' => 'difficult',
                    'C' => 'early',
                    'D' => 'different',
                    'E' => 'able'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 12,
                'options' => [
                    'A' => 'turn',
                    'B' => 'driveway',
                    'C' => 'sun',
                    'D' => 'beginning',
                    'E' => 'street'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'In some countries, people who own waterfront property or small islands possess only what is above the high tide mark. They do not own the foreshore, that strip of (13) _____ lying between the high water (14) _____.',
        'questions' => [
            [
                'number' => 13,
                'options' => [
                    'A' => 'format',
                    'B' => 'land',
                    'C' => 'bark',
                    'D' => 'cloth',
                    'E' => 'time'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 14,
                'options' => [
                    'A' => 'storms',
                    'B' => 'pressure',
                    'C' => 'valves',
                    'D' => 'temperature',
                    'E' => 'marks'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'The hummingbird has a long slender bill. It thrusts this bill into flowers to get nectar and insects. When (15) _____, it beats its wings so rapidly that they sound like the (16) _____ of a tiny motor.',
        'questions' => [
            [
                'number' => 15,
                'options' => [
                    'A' => 'hopping',
                    'B' => 'resting',
                    'C' => 'flying',
                    'D' => 'flowers',
                    'E' => 'walking'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 16,
                'options' => [
                    'A' => 'hum',
                    'B' => 'scratch',
                    'C' => 'grit',
                    'D' => 'size',
                    'E' => 'crash'
                ],
                'correct_answer' => 'A'
            ]
        ]
    ],
    
    [
        'passage' => 'As one looks down a long straight road, it seems to grow narrower from a (17) _____. Telephone poles give the (18) _____ of growing smaller as the eye follows a row of them toward the horizon.',
        'questions' => [
            [
                'number' => 17,
                'options' => [
                    'A' => 'distance',
                    'B' => 'time',
                    'C' => 'division',
                    'D' => 'turnpike',
                    'E' => 'city'
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 18,
                'options' => [
                    'A' => 'score',
                    'B' => 'call',
                    'C' => 'method',
                    'D' => 'height',
                    'E' => 'appearance'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'In 1954, Roger Bannister ran a mile in less than four minutes. Prior to this, it was thought (19) _____ for a man to run a "four-minute" mile. Then in 1961, Herb Elliott of Australia ran the mile in three (20) _____, fifty-four and a half seconds. He bettered Bannister\'s (21) _____ by nearly five seconds.',
        'questions' => [
            [
                'number' => 19,
                'options' => [
                    'A' => 'impossible',
                    'B' => 'illegal',
                    'C' => 'idea',
                    'D' => 'careful',
                    'E' => 'improper'
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 20,
                'options' => [
                    'A' => 'hours',
                    'B' => 'times',
                    'C' => 'parts',
                    'D' => 'minutes',
                    'E' => 'courts'
                ],
                'correct_answer' => 'D'
            ],
            [
                'number' => 21,
                'options' => [
                    'A' => 'right',
                    'B' => 'record',
                    'C' => 'timely',
                    'D' => 'mill',
                    'E' => 'recount'
                ],
                'correct_answer' => 'B'
            ]
        ]
    ],
    
    [
        'passage' => 'The high cost of building good roads has made it necessary for highway builders to exact tolls from motorists. "Turnpike" is one name given to those highways where travelers must pay (22) _____. All (23) _____ using the (24) _____ go through toll gates and thereby share the cost of good roads.',
        'questions' => [
            [
                'number' => 22,
                'options' => [
                    'A' => 'told',
                    'B' => 'motorists',
                    'C' => 'tolls',
                    'D' => 'roads',
                    'E' => 'respect'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 23,
                'options' => [
                    'A' => 'building',
                    'B' => 'vehicles',
                    'C' => 'necessary',
                    'D' => 'ready',
                    'E' => 'without'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 24,
                'options' => [
                    'A' => 'turnpike',
                    'B' => 'gates',
                    'C' => 'builders',
                    'D' => 'tools',
                    'E' => 'teletypes'
                ],
                'correct_answer' => 'A'
            ]
        ]
    ],
    
    [
        'passage' => 'In 1927, Charles A. Lindbergh made the first nonstop solo flight from New York to Paris in thirty-three and a half hours. Jet planes now (25) _____ the Atlantic Ocean take only a (26) _____ of the time that Lindbergh took.',
        'questions' => [
            [
                'number' => 25,
                'options' => [
                    'A' => 'refusing',
                    'B' => 'cover',
                    'C' => 'enter',
                    'D' => 'crossing',
                    'E' => 'going'
                ],
                'correct_answer' => 'D'
            ],
            [
                'number' => 26,
                'options' => [
                    'A' => 'century',
                    'B' => 'fraction',
                    'C' => 'double',
                    'D' => 'passing',
                    'E' => 'history'
                ],
                'correct_answer' => 'B'
            ]
        ]
    ],
    
    [
        'passage' => 'Sago is a food obtained from the trunk of a certain species of palm tree. The finest sago comes from large (27) _____ that grow in the East Indies. Each tree (28) _____ yields from one hundred to eight hundred pounds of sago.',
        'questions' => [
            [
                'number' => 27,
                'options' => [
                    'A' => 'fires',
                    'B' => 'trees',
                    'C' => 'fruits',
                    'D' => 'forests',
                    'E' => 'animals'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 28,
                'options' => [
                    'A' => 'branch',
                    'B' => 'top',
                    'C' => 'root',
                    'D' => 'leaf',
                    'E' => 'trunk'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'It isn\'t wise to send money through the mail, since for a few cents, you can buy a postal money order for the exact (29) _____ you wish to send. Only the person to whom you make it out can get (30) _____ for it. To receive the money, he must show proper (31) _____.',
        'questions' => [
            [
                'number' => 29,
                'options' => [
                    'A' => 'person',
                    'B' => 'address',
                    'C' => 'amount',
                    'D' => 'poster',
                    'E' => 'paper'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 30,
                'options' => [
                    'A' => 'cash',
                    'B' => 'trouble',
                    'C' => 'postage',
                    'D' => 'mail',
                    'E' => 'stocks'
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 31,
                'options' => [
                    'A' => 'manners',
                    'B' => 'face',
                    'C' => 'own',
                    'D' => 'identification',
                    'E' => 'ideas'
                ],
                'correct_answer' => 'D'
            ]
        ]
    ],
    
    [
        'passage' => 'Normal air pressure is about sixteen pounds per square inch. If the air pressure suddenly becomes much less than this, you feel light headed and dizzy. If the air (32) _____ increases to much more than sixteen pounds per square inch, the world seems to be pressing down and trying to suffocate you. Air pressure is something that you live in all the time and yet never (33) _____ unless it suddenly (34) _____.',
        'questions' => [
            [
                'number' => 32,
                'options' => [
                    'A' => 'dampness',
                    'B' => 'perhaps',
                    'C' => 'ways',
                    'D' => 'pressure',
                    'E' => 'letter'
                ],
                'correct_answer' => 'D'
            ],
            [
                'number' => 33,
                'options' => [
                    'A' => 'notice',
                    'B' => 'eat',
                    'C' => 'drink',
                    'D' => 'ask',
                    'E' => ''
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 34,
                'options' => [
                    'A' => 'appears',
                    'B' => 'changes',
                    'C' => 'stays',
                    'D' => 'changes',
                    'E' => 'cries'
                ],
                'correct_answer' => 'B'
            ]
        ]
    ],
    
    [
        'passage' => 'As they paddled into the lake shore, they saw the log (35) _____ which was to be their headquarters for the trapping season. "Couldn\'t be better (36) _____," said Don. "It\'s almost surrounded by sheltering trees and only a stone\'s throw from the (37) _____ of the lake."',
        'questions' => [
            [
                'number' => 35,
                'options' => [
                    'A' => 'roll',
                    'B' => 'cabin',
                    'C' => 'farm',
                    'D' => 'fence',
                    'E' => 'cut'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 36,
                'options' => [
                    'A' => 'scene',
                    'B' => 'mountain',
                    'C' => 'situated',
                    'D' => 'situated',
                    'E' => 'tree'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 37,
                'options' => [
                    'A' => 'edge',
                    'B' => 'swimming',
                    'C' => 'tree',
                    'D' => 'hill',
                    'E' => 'river'
                ],
                'correct_answer' => 'A'
            ]
        ]
    ],
    
    [
        'passage' => 'Speed was necessary and Fred had tried to find a shortcut through the forest. Now he knew that his (38) _____ had not been a good one. He was (39) _____, (40) _____ trying to get his bearings.',
        'questions' => [
            [
                'number' => 38,
                'options' => [
                    'A' => 'purchase',
                    'B' => 'safe',
                    'C' => 'time',
                    'D' => 'decision',
                    'E' => 'speed'
                ],
                'correct_answer' => 'D'
            ],
            [
                'number' => 39,
                'options' => [
                    'A' => 'lost',
                    'B' => 'large',
                    'C' => 'asleep',
                    'D' => 'torn',
                    'E' => 'last'
                ],
                'correct_answer' => 'A'
            ],
            [
                'number' => 40,
                'options' => [
                    'A' => 'saved',
                    'B' => 'locked',
                    'C' => 'desperately',
                    'D' => 'clocked',
                    'E' => 'best'
                ],
                'correct_answer' => 'C'
            ]
        ]
    ],
    
    [
        'passage' => 'The best fence diving is the result of long practice. However, (41) _____ in and of itself, does not bring championship form. Championship diving is the (42) _____ of such specifics as muscular (43) _____ and coordination plus exact timing.',
        'questions' => [
            [
                'number' => 41,
                'options' => [
                    'A' => 'prevention',
                    'B' => 'practice',
                    'C' => 'reaction',
                    'D' => 'practice',
                    'E' => 'degree'
                ],
                'correct_answer' => 'B'
            ],
            [
                'number' => 42,
                'options' => [
                    'A' => 'importance',
                    'B' => 'spring',
                    'C' => 'result',
                    'D' => 'school',
                    'E' => 'reading'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 43,
                'options' => [
                    'A' => 'rest',
                    'B' => 'punch',
                    'C' => 'pain',
                    'D' => 'springboard',
                    'E' => 'control'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'The Lincoln cent, first minted in 1909, was the first cent to bear the (44) _____ of an actual person. In 1959, the reverse side of the Lincoln cent was (46) _____ by a front view of the Lincoln Memorial, situated in Washington, DC.',
        'questions' => [
            [
                'number' => 44,
                'options' => [
                    'A' => 'back',
                    'B' => 'imagination',
                    'C' => 'likeness',
                    'D' => 'thumbprint',
                    'E' => 'bust'
                ],
                'correct_answer' => 'E'
            ],
            [
                'number' => 46,
                'options' => [
                    'A' => 'covered',
                    'B' => 'designed',
                    'C' => 'massed',
                    'D' => 'generated',
                    'E' => 'replaced'
                ],
                'correct_answer' => 'E'
            ]
        ]
    ],
    
    [
        'passage' => 'The process of alternating layers of flat glass with layers of plastic is one form of lamination. This special process yields a (47) _____ which is used for windshields. A windshield made of (48) _____ glass is relatively safe because the plastic layers have an elastic quality which (49) _____ broken glass from shattering and causing injuries.',
        'questions' => [
            [
                'number' => 47,
                'options' => [
                    'A' => 'cleaner',
                    'B' => 'residue',
                    'C' => 'crop',
                    'D' => 'product',
                    'E' => 'project'
                ],
                'correct_answer' => 'D'
            ],
            [
                'number' => 48,
                'options' => [
                    'A' => 'steel',
                    'B' => 'curved',
                    'C' => 'laminated',
                    'D' => 'transfused',
                    'E' => 'plate'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 49,
                'options' => [
                    'A' => 'each',
                    'B' => 'tries',
                    'C' => 'prevents',
                    'D' => 'encourages',
                    'E' => 'causes'
                ],
                'correct_answer' => 'C'
            ]
        ]
    ],
    
    [
        'passage' => 'In ordinary (50) _____ the qualities of the speaker\'s voice give important clues to his thoughts and feelings. But when you read someone else\'s written work, you must study the (51) _____ carefully so that you can interpret the (52) _____.',
        'questions' => [
            [
                'number' => 50,
                'options' => [
                    'A' => 'textbooks',
                    'B' => 'thinking',
                    'C' => 'feelings',
                    'D' => 'material',
                    'E' => 'conversation'
                ],
                'correct_answer' => 'E'
            ],
            [
                'number' => 51,
                'options' => [
                    'A' => 'dictionary',
                    'B' => 'letters',
                    'C' => 'text',
                    'D' => 'syllables',
                    'E' => 'spelling'
                ],
                'correct_answer' => 'C'
            ],
            [
                'number' => 52,
                'options' => [
                    'A' => 'enthusiastic',
                    'B' => 'common',
                    'C' => 'listener\'s',
                    'D' => 'writer\'s',
                    'E' => 'association\'s'
                ],
                'correct_answer' => 'D'
            ]
        ]
    ]
];


?>