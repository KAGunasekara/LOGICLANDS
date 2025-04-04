let level = 1;
let score = 0;
let timerDuration = 90;
let timerInterval;
let questionNumber = 1;
let attemptsLeft = 14; 
const apiEndpoint = "api/banana_api.php";
const saveScoreEndpoint = "scoreboard.php";

const victorySound = new Audio('sound/game-background-loop-uplifting-adventure-music-loop-248804.mp3');

document.addEventListener("DOMContentLoaded", () => {
    initializeGame();
});

// Initialize game
function initializeGame() {
    loadLevel(level);
    document.getElementById("submit-answer").addEventListener("click", () => {
        const userAnswer = document.getElementById("answer-input").value.trim();
        if (validateAnswer(userAnswer)) {
            checkAnswer(userAnswer);
        }
    });
    fetchQuestion(); // Initial question fetch
}

// Validate user's answer
function validateAnswer(answer) {
    if (!answer || answer.length < 1) {
        alert("Hey champ noooo!! Answer cannot be empty!");
        return false;
    }
    if (answer.length > 50) {
        alert("Answer is too long champ ! Please enter a shorter answer.");
        return false;
    }
    return true;
}

// Loading the current level
function loadLevel(currentLevel) {
  const levelNames = [...Array(10)].map((_, index) => (index + 1).toString()); // Generate an array for 10 levels
  const currentLevelName = levelNames[currentLevel - 1] || "10";

  const gameContainer = document.querySelector(".game-container");
  gameContainer.classList.remove("red-alert");

  // Update level display
  document.getElementById("level-indicator").innerText = `Level: ${currentLevelName}`;
  document.getElementById("current-score").innerText = score;
  document.getElementById("answer-input").value = ""; // Clear the answer input field
  
  // Remove the question number structure and display only level-based question
  //document.getElementById("question-indicator").innerText = `Level ${currentLevelName} - Question`;

    // Adjust the number of attempts and timer duration
    attemptsLeft = Math.max(1, 15 - currentLevel); 
    timerDuration = Math.max(1, 90 - (currentLevel - 1) * 10);
    document.getElementById("attempts-indicator").innerText = `Attempts: ${attemptsLeft}`;
    startTimer();
    fetchQuestion(); // Fetch the first question for the level
}

// Start the timer
function startTimer() {
    clearInterval(timerInterval);
    let timeLeft = timerDuration;

    const gameContainer = document.querySelector(".game-container");
    gameContainer.classList.remove("red-alert");

    document.getElementById("time-remaining").innerText = `${timeLeft} seconds`;

    timerInterval = setInterval(() => {
        timeLeft--;
        document.getElementById("time-remaining").innerText = `${timeLeft} seconds`;

        if (timeLeft === 5) {
            gameContainer.classList.add("red-alert");
        }

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            gameContainer.classList.remove("red-alert");
            alert("Ohhh Time's up champ! You failed this level.");
            
            saveScore(score);
            resetGame();
        }
    }, 1000);
}

// Fetch a question from the API
function fetchQuestion() {
    fetch(apiEndpoint)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Failed to fetch the question. HTTP status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.question && data.solution !== undefined) {
                displayQuestion(data);
                document.getElementById("submit-answer").dataset.correctAnswer = data.solution;
            } else {
                throw new Error("Invalid question data received.");
            }
        })
        .catch((error) => {
            console.error("Error fetching question:", error);
            document.getElementById("question-grid").innerText = "Error loading question. Please try again later.";
        });
}

// Check the user's answer
function checkAnswer(userAnswer) {
    const correctAnswer = document.getElementById("submit-answer").dataset.correctAnswer;

    if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
        score += level * 20;
        document.getElementById("current-score").innerText = score;
        questionNumber++;

        if (questionNumber > 0) {
            level++;
            if (level > 10) {
                victorySound.play();
                alert("Hey champ you're completed all levels! Congratulations!");
                saveScore(score);
                window.location.href = "game.html";
                
            } else {
                //fetchScores();
                loadLevel(level);
            }
        } else {
            fetchQuestion();
            document.getElementById("question-indicator").innerText = `Question: ${questionNumber}/3`;
            document.getElementById("answer-input").value = "";
        }
    } else {
        attemptsLeft--;
        document.getElementById("attempts-indicator").innerText = `Attempts: ${attemptsLeft}`;
        if (attemptsLeft <= 0) {
            alert("Ohhhh champ no more attempts left! You failed this level.");
            //saveScore();
            saveScore(score);
            resetGame();
        } else {
            alert("Champ Incorrect answer. Try again!");
        }
    }
}

// Display the question
function displayQuestion(questionData) {
    const questionGrid = document.getElementById("question-grid");

    if (questionData.question.startsWith("http")) {
        questionGrid.innerHTML = `<img src="${questionData.question}" alt="Question Image" style="max-width: 100%; height: auto; border-radius: 5px;">`;
    } else {
        questionGrid.innerText = questionData.question;
    }
}



// Reset the game
function resetGame() {
    clearInterval(timerInterval);
    score = 0;
    level = 1;
    questionNumber = 1;
    attemptsLeft = 14; // Reset attempts
    const gameContainer = document.querySelector(".game-container");
    gameContainer.classList.remove("red-alert");
    alert("Omg Game Over. Try again champ!");
    loadLevel(level);
}
