// ========= Utility functions =========
function random(a, b) {
  return Math.floor(Math.random() * (b - a + 1)) + a;
}

// ========= Selected elements =========
const inputGuess = document.querySelector("#inputGuess");
const form = document.querySelector("form");
const tableGuesses = document.querySelector("#guesses");
const divTheWord = document.querySelector("details > div");
const spanError = document.querySelector("#error");
const btnGuess = document.querySelector("form > button");
const divEndOfGame = document.querySelector("#end-of-game");
const btnRestart = document.querySelector("#restart");

// ========= Solution =========

const selectRandomWord = () => {
  return wordList[random(0, wordList.length-1)];
}

let randomWord = selectRandomWord();
divTheWord.innerHTML = randomWord;

form.addEventListener('submit', (e) => {
  e.preventDefault();

  inputGuess.select();
  spanError.innerHTML = '';

  const guess = inputGuess.value.toLowerCase();

  if (guess.length !== 5) {
    spanError.innerHTML = 'The length of the word is not 5!';
    return;
  }

  if (!wordList.includes(guess)) {
    spanError.innerHTML = 'The word is not considered acceptable!';
    return;
  }

  let matchCount = 0;
  for (let i = 0; i < 5; i++) {
    if (guess[i] === randomWord[i]) {
      matchCount++;
    }
  }
  console.log(matchCount);

  const newRow = document.createElement('tr');
  newRow.innerHTML = `<td>${guess}</td><td>${matchCount}</td>`;

  if (guess === randomWord) {
    newRow.classList.add('correct');
    divEndOfGame.style.display = 'block';
  }

  tableGuesses.insertBefore(newRow, tableGuesses.firstChild);
});

btnRestart.addEventListener('click', () => {
  location.reload();
});
