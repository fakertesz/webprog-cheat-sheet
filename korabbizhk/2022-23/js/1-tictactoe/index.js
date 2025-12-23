const task1 = document.querySelector('#task1');
const task2 = document.querySelector('#task2');
const task3 = document.querySelector('#task3');
const task4 = document.querySelector('#task4');

const game = [
  "XXOO",
  "O OX",
  "OOO ",
];

const firstRowLength = game[0].length;
const allSameLength = (list) => {
  return list.every(row => row.length === firstRowLength);
}
task1.innerHTML = allSameLength(game);


const allXorO = (list) => {
  return list.every(
    row => row.split('').every(
      char => char === 'X' || char === 'O' || char === ' ')
    );
}
task2.innerHTML = allXorO(game);

const nrOfLetters = (list, letter) => {
  return list.map(row => row.split('')).flat().filter(char => char === letter).length
}

nrOfXs = nrOfLetters(game, 'X');
nrOfOs = nrOfLetters(game, 'O');
task3.innerHTML = `X / O = ${nrOfXs} / ${nrOfXs}`

const isGameOver = (list) => {
  return list.findIndex(row => threeConsecutivesExist(row));
}

const threeConsecutivesExist = (row) => {
  for (let i = 0; i < row.length - 2; i++) {
    if (row[i] !== '' && row[i] === row[i + 1] && row[i + 1] === row[i + 2]) {
      return true;
    }
  }
  return false;
}

task4.innerHTML = isGameOver(game);