const inputCircleNumber = document.querySelector("#circle-number");
const buttonStart = document.querySelector("#start");
const divContainer = document.querySelector("#container");
const divOutput = document.querySelector("#output");

// Application state

let canGuess = false;
let solution = [];
let series = [];

// ========= Utility functions =========

function random(a, b) {
  return Math.floor(Math.random() * (b - a + 1)) + a;
}

function toggleHighlight(node) {
  node.classList.toggle("highlight")
  node.addEventListener("animationend", function (e) {
    node.classList.remove("highlight");
  }, {once: true});
}

// =====================================

// a.
function drawCircles() {
  const count = inputCircleNumber.value;
  divContainer.innerHTML = '';
  for (let i = 0; i < count; i++) {
    const circle = document.createElement('a');
    circle.innerHTML = i + 1;
    circle.classList.add('circle');
    divContainer.appendChild(circle);
  }
}

inputCircleNumber.addEventListener('input', drawCircles);

// b.
buttonStart.addEventListener('click', () => {
  canGuess = false;
  solution = [];
  series = [];

  const circleCount = Number(inputCircleNumber.value);
  for (let i = 0; i < 7; i++) {
    series.push(random(1, circleCount));
  }
  console.log(series);

  // f.
  divOutput.textContent = 'Flashing circles...';

  // c & d.
  flashCircles();
});

// d.
function flashCircles(i = 0) {
  if (i < series.length) {
    const circles = document.querySelectorAll('.circle');
    const circleIndex = series[i] - 1;
    toggleHighlight(circles[circleIndex]);
    setTimeout(() => flashCircles(i + 1), 1000);
  } else {
    // f.
    divOutput.textContent = 'Now, your turn...';
    canGuess = true;
  }
}

// e.
divContainer.addEventListener('click', (e) => {
  if (!e.target.classList.contains('circle')) return;

  // g.
  if (!canGuess) return;

  const circles = Array.from(document.querySelectorAll('.circle'));
  const clickedIndex = circles.indexOf(e.target) + 1;
  solution.push(clickedIndex);

  if (solution.length === series.length) {
    const success = series.every((val, idx) => val === solution[idx]);
    console.log(success);

    // f.
    divOutput.textContent = success ? 'Nice job!' : 'Failed!';
    canGuess = false;
  }
});

drawCircles();
