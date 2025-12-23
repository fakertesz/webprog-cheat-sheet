// Selected elements
const ulContestants = document.querySelector("ul#contestants");
const divEditor = document.querySelector("#contestant-editor");
const btnNew = document.querySelector("#btnNew");
const inputNew = document.querySelector("#inputNew");

// Data
let contestants = {
  "1": {
    id: "1",
    name: "Contestant 1",
    penalties: [
      { timestamp: Date.now(), duration: 60000 },
      { timestamp: Date.now() - 2000, duration: 10000 },
      { timestamp: Date.now() - 10000, duration: 30000 },
    ],
  },
  "2": {
    id: "2",
    name: "Contestant 2",
    penalties: [
      { timestamp: Date.now(), duration: 10000 },
      { timestamp: Date.now() - 5000, duration: 10000 },
      { timestamp: Date.now() - 30000, duration: 30000 },
    ],
  },
};
let selectedContestant = null;

// ========= Solution =========

// h. Load from localStorage
if (localStorage.getItem('contestants')) {
  contestants = JSON.parse(localStorage.getItem('contestants'));
}

// Helper function to calculate remaining time for a penalty
function getRemainingTime(penalty) {
  const now = Date.now();
  const endTime = penalty.timestamp + penalty.duration;
  const remaining = endTime - now;
  return Math.max(0, remaining);
}

// b. Calculate cumulative remaining time for a contestant
function getCumulativePenalty(contestant) {
  let total = 0;
  for (const penalty of contestant.penalties) {
    total += getRemainingTime(penalty);
  }
  return total;
}

// a. Render contestants list
function renderContestants() {
  ulContestants.innerHTML = '';

  for (const id in contestants) {
    const contestant = contestants[id];
    const li = document.createElement('li');
    li.dataset.id = contestant.id;

    const cumulativePenalty = getCumulativePenalty(contestant);
    const seconds = Math.ceil(cumulativePenalty / 1000);

    li.innerHTML = `${contestant.name} <span>${seconds}s</span>`;

    // b. Add penalty class if cumulative penalty > 0
    if (cumulativePenalty > 0) {
      li.classList.add('penalty');
    }

    ulContestants.appendChild(li);
  }
}

// d. Render details panel
function renderDetails() {
  if (!selectedContestant) {
    divEditor.style.display = 'none';
    return;
  }

  divEditor.style.display = 'block';
  const contestant = contestants[selectedContestant];

  // c. Update h2 with contestant name
  divEditor.querySelector('h2').textContent = contestant.name;

  // d. Render penalties list
  const ul = divEditor.querySelector('ul');
  ul.innerHTML = '';

  for (const penalty of contestant.penalties) {
    const li = document.createElement('li');
    const remaining = getRemainingTime(penalty);
    const seconds = Math.ceil(remaining / 1000);

    const startDate = new Date(penalty.timestamp).toLocaleString();
    const durationSec = penalty.duration / 1000;

    li.innerHTML = `
      ${startDate} + ${durationSec}s
      <progress max="${penalty.duration}" value="${remaining}"></progress>
      ${seconds}s
    `;

    ul.appendChild(li);
  }
}

// h. Save to localStorage
function saveToStorage() {
  localStorage.setItem('contestants', JSON.stringify(contestants));
}

// c. Handle contestant click
ulContestants.addEventListener('click', (e) => {
  const li = e.target.closest('li');
  if (li) {
    selectedContestant = li.dataset.id;
    renderDetails();
  }
});

// e. Handle penalty button clicks
divEditor.addEventListener('click', (e) => {
  if (e.target.matches('[data-duration]')) {
    const duration = parseInt(e.target.dataset.duration);
    const contestant = contestants[selectedContestant];

    contestant.penalties.push({
      timestamp: Date.now(),
      duration: duration
    });

    renderDetails();
    renderContestants();
    saveToStorage();
  }
});

// f. Add new contestant
btnNew.addEventListener('click', () => {
  const name = inputNew.value.trim();
  if (name) {
    const id = Date.now().toString();
    contestants[id] = {
      id: id,
      name: name,
      penalties: []
    };

    inputNew.value = '';
    renderContestants();
    saveToStorage();
  }
});

// g. Update display every second
setInterval(() => {
  renderContestants();
  renderDetails();
}, 1000);

// Initial render
renderContestants();
renderDetails();
