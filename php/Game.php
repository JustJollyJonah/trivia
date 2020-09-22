<?php
function echoln($string)
{
    echo $string . "\n";
}

class Game
{
    private $players = [];
    private $places = [];
    private $purses = [];
    private $inPenaltyBox = [];

    private $popQuestions = [];
    private $scienceQuestions = [];
    private $sportsQuestions = [];
    private $rockQuestions = [];

    const POP_QUESTION_TITLE = "Pop Question ";
    const SCIENCE_QUESTION_TITLE = "Science Question ";
    const SPORTS_QUESTION_TITLE = "Sports Question ";
    const ROCK_QUESTION_TITLE = "Rock Question ";

    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    function __construct()
    {
        for ($i = 0; $i < 50; $i++) $this->createQuestions($i);
    }

    function createQuestions($index) {
        array_push($this->popQuestions, self::POP_QUESTION_TITLE . $index);
        array_push($this->scienceQuestions, self::SCIENCE_QUESTION_TITLE . $index);
        array_push($this->sportsQuestions, self::SPORTS_QUESTION_TITLE . $index);
        array_push($this->rockQuestions, self::ROCK_QUESTION_TITLE . $index);
    }

    function add($playerName) {
        $playerIndex = array_push($this->players, $playerName) - 1;
        $this->places[$playerIndex] = 0;
        $this->purses[$playerIndex] = 0;
        $this->inPenaltyBox[$playerIndex] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . $playerIndex);
        return true;
    }

    function roll($roll)
    {
        $playerName = $this->players[$this->currentPlayer];
        $playerPosition = &$this->places[$this->currentPlayer];
        echoln($playerName . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;
                echoln($playerName . " is getting out of the penalty box");
            } else {
                echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
                return;
            }
        }

        $playerPosition += $roll;
        if ($playerPosition > 11) $playerPosition -= 12;
        echoln($playerName
            . "'s new location is "
            . $playerPosition);
        echoln("The category is " . $this->currentCategory());
        $this->askQuestion();
    }

    function askQuestion()
    {
        $array = [];
        switch ($this->currentCategory()) {
            case "Pop":
                $array = &$this->popQuestions;
                break;
            case "Science":
                $array = &$this->scienceQuestions;
                break;
            case "Sports":
                $array = &$this->sportsQuestions;
                break;
            case "Rock":
                $array = &$this->rockQuestions;
                break;
        }
        echoln(array_shift($array));
    }

    function currentPlayerPosition() {
        return $this->places[$this->currentPlayer];
    }

    function currentCategory()
    {
        $playerPosition = $this->currentPlayerPosition();
        if (in_array($playerPosition, [0, 4, 8])) return "Pop";
        if (in_array($playerPosition, [1, 5, 9])) return "Science";
        if (in_array($playerPosition, [2, 6, 10])) return "Sports";
        return "Rock";
    }

    /**
     * Returns true when a winner was reached. Only happens if player gets more than 6 coins.
     *
     * @return bool
     */
    function correctAnswer()
    {
        if ($this->inPenaltyBox[$this->currentPlayer] && !$this->isGettingOutOfPenaltyBox) {
            $this->currentPlayer++;
            if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
            return false;
        }

        echoln("Answer was correct!!!!");
        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer]
            . " now has "
            . $this->purses[$this->currentPlayer]
            . " Gold Coins.");

        $winner = $this->didPlayerWin();
        $this->nextPlayer();
        return $winner;
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->nextPlayer();
    }

    function nextPlayer() {
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
    }


    function didPlayerWin()
    {
        return $this->purses[$this->currentPlayer] == 6;
    }
}
