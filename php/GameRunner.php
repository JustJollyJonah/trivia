<?php

include __DIR__ . '/Game.php';

$notAWinner;

$aGame = new Game();

$aGame->add("Chet");
$aGame->add("Pat");
$aGame->add("Sue");


do {

    $aGame->roll(rand(0, 5) + 1);

    if (rand(0, 9) == 7) {
        echoln("Wrong answer");
        $aGame->wrongAnswer();
    } else {
        echoln("Right answer");
        $notAWinner = $aGame->correctAnswer();
    }


} while (!$notAWinner);
  
