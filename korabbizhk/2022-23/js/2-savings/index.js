const form = document.querySelector("form");
const divContainer = document.querySelector(".container");

function updateDiagram() {
  const inputs = document.querySelectorAll('input[type="range"]');
  let M = 0;
  inputs.forEach(input => {
    M += Number(input.dataset.consumption);
  });
  console.log([M]);

  const consumptions = [];
  inputs.forEach(input => {
    const ci = (input.value / input.max) * input.dataset.consumption;
    consumptions.push(ci);

    const label = document.querySelector(`label[for="${input.id}"]`);
    if (label) {
      label.style.width = `${(ci / M) * 100}%`;
    }
  });
  console.log(consumptions);
}

form.addEventListener('input', updateDiagram);

updateDiagram();
