// ============================================
// JAVASCRIPT DOM MANIPULÁCIÓ
// ============================================

// ============ ELEM KIVÁLASZTÁS ============

// Egy elem kiválasztása
const elem1 = document.querySelector('#myId');
const elem2 = document.querySelector('.myClass'); // első elem
const elem3 = document.querySelector('div[data-id="5"]');

// Több elem kiválasztása
const elems1 = document.querySelectorAll('.myClass'); // NodeList
const elems2 = document.querySelectorAll('div.item');

// HTMLCollection -> Array konverzió
const elemsArray = Array.from(elems1);
const elemsArray2 = [...elems2]; // NodeList is működik

// ============ ELEM LÉTREHOZÁS & HOZZÁADÁS ============

// Elem létrehozása
const newDiv = document.createElement('div');
const newP = document.createElement('p');
const newImg = document.createElement('img');

// Tartalom beállítása
newDiv.textContent = 'Hello World'; // csak szöveg
newDiv.innerHTML = '<strong>Bold text</strong>'; // HTML is

// Attribútumok beállítása
newImg.src = 'image.jpg';
newImg.alt = 'Description';
newDiv.id = 'myId';
newDiv.className = 'myClass';
newDiv.setAttribute('data-id', '123');
newDiv.setAttribute('title', 'Tooltip');

// Stílus beállítása
newDiv.style.color = 'red';
newDiv.style.backgroundColor = 'blue';
newDiv.style.fontSize = '20px';
newDiv.style.display = 'none'; // elrejt
newDiv.style.display = 'block'; // megjelenít

// Osztályok kezelése
newDiv.classList.add('active');
newDiv.classList.remove('hidden');
newDiv.classList.toggle('visible');
newDiv.classList.contains('active'); // true/false
newDiv.classList.replace('old', 'new');

// Elem hozzáadása a DOM-hoz
document.body.appendChild(newDiv); // a végére
elem.appendChild(newP); // gyerekként
elem.insertBefore(newDiv, elem.firstChild); // első gyerekként
elem.append(newDiv); // modernebb, több elemet is elfogad
elem.prepend(newDiv); // elejére
elem.after(newDiv); // utána
elem.before(newDiv); // elé

// ============ ELEM MÓDOSÍTÁS ============

// Tartalom módosítása
elem.textContent = 'New text';
elem.innerHTML = '<span>New HTML</span>';
elem.innerText = 'Visible text'; // csak látható szöveg

// Attribútumok olvasása/írása
const src = elem.getAttribute('src');
elem.setAttribute('data-value', '42');
elem.removeAttribute('title');
const hasAttr = elem.hasAttribute('data-id');

// Data attribútumok
elem.dataset.userId = '123'; // data-user-id="123"
const userId = elem.dataset.userId;

// Érték olvasása/írása (input elemek)
const input = document.querySelector('input');
input.value = 'New value';
const val = input.value;

// ============ ELEM TÖRLÉS ============

elem.remove(); // saját magát törli
parent.removeChild(child); // gyerek törlése
elem.innerHTML = ''; // minden gyerek törlése

// ============ ESEMÉNYKEZELÉS ============

// Egyszerű eseménykezelő
elem.addEventListener('click', (event) => {
    console.log('Clicked!', event);
});

// Event object tulajdonságai
elem.addEventListener('click', (e) => {
    e.target; // az elem, amin az esemény történt
    e.currentTarget; // az elem, amihez a listener csatlakozik
    e.preventDefault(); // alapértelmezett viselkedés megakadályozása
    e.stopPropagation(); // buborékolás megállítása
});

// Gyakori események
elem.addEventListener('click', () => {}); // kattintás
elem.addEventListener('dblclick', () => {}); // dupla kattintás
elem.addEventListener('mouseenter', () => {}); // egér belép
elem.addEventListener('mouseleave', () => {}); // egér kilép
elem.addEventListener('mousemove', () => {}); // egér mozog

input.addEventListener('input', () => {}); // input változás (minden gépelés)
input.addEventListener('change', () => {}); // input változás (blur után)
input.addEventListener('focus', () => {}); // fókusz
input.addEventListener('blur', () => {}); // fókusz elvesztése

document.addEventListener('keydown', (e) => {
    console.log(e.key); // lenyomott billentyű
    console.log(e.code); // billentyű kód
});

document.addEventListener('keyup', () => {});
document.addEventListener('keypress', () => {});

// Form események
form.addEventListener('submit', (e) => {
    e.preventDefault(); // oldal újratöltés megakadályozása
    // form feldolgozás
});

// Eseménykezelő eltávolítása
const handler = () => console.log('Click');
elem.addEventListener('click', handler);
elem.removeEventListener('click', handler);

// ============ DOM TRAVERSAL (NAVIGÁLÁS) ============

// Szülő
const parent = elem.parentElement;
const parent2 = elem.parentNode;

// Gyerekek
const children = elem.children; // HTMLCollection (csak elemek)
const childNodes = elem.childNodes; // NodeList (szöveg node-ok is)
const firstChild = elem.firstElementChild;
const lastChild = elem.lastElementChild;

// Testvérek
const next = elem.nextElementSibling;
const prev = elem.previousElementSibling;

// Keresés felfelé
const closest = elem.closest('.container'); // legközelebbi szülő, ami illeszkedik

// ============ MÉRETEK & POZÍCIÓ ============

// Méretek
const width = elem.offsetWidth; // szélesség (border-rel)
const height = elem.offsetHeight; // magasság (border-rel)
const clientWidth = elem.clientWidth; // szélesség (border nélkül)
const clientHeight = elem.clientHeight; // magasság (border nélkül)

// Pozíció
const rect = elem.getBoundingClientRect();
rect.top; // távolság a viewport tetejétől
rect.left; // távolság a viewport bal szélétől
rect.width; // szélesség
rect.height; // magasság

// Scroll pozíció
const scrollTop = elem.scrollTop;
const scrollLeft = elem.scrollLeft;
window.scrollY; // függőleges scroll
window.scrollX; // vízszintes scroll

// Görgetés
elem.scrollIntoView(); // elemhez görget
window.scrollTo(0, 0); // tetejére görget

// ============ GYAKORLATI PÉLDÁK ============

// Lista generálás tömbből
const items = ['Apple', 'Banana', 'Cherry'];
const ul = document.createElement('ul');
items.forEach(item => {
    const li = document.createElement('li');
    li.textContent = item;
    ul.appendChild(li);
});
document.body.appendChild(ul);

// Lista generálás map-pel
const ul2 = document.createElement('ul');
ul2.innerHTML = items.map(item => `<li>${item}</li>`).join('');
document.body.appendChild(ul2);

// Táblázat generálás
const data = [
    { name: 'Anna', age: 25 },
    { name: 'Béla', age: 30 }
];

const table = document.createElement('table');
const thead = document.createElement('thead');
thead.innerHTML = '<tr><th>Name</th><th>Age</th></tr>';
const tbody = document.createElement('tbody');
tbody.innerHTML = data
    .map(row => `<tr><td>${row.name}</td><td>${row.age}</td></tr>`)
    .join('');
table.append(thead, tbody);
document.body.appendChild(table);

// Kártyák generálása objektum tömbből
const products = [
    { name: 'Laptop', price: 300000 },
    { name: 'Phone', price: 150000 }
];

const container = document.querySelector('#products');
products.forEach(product => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
        <h3>${product.name}</h3>
        <p>Price: ${product.price} Ft</p>
        <button class="buy-btn" data-product="${product.name}">Buy</button>
    `;
    container.appendChild(card);
});

// Event delegation (esemény delegálás)
container.addEventListener('click', (e) => {
    if (e.target.classList.contains('buy-btn')) {
        const productName = e.target.dataset.product;
        console.log(`Buying: ${productName}`);
    }
});

// Szűrés input alapján
const searchInput = document.querySelector('#search');
const listItems = document.querySelectorAll('.item');

searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    listItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? 'block' : 'none';
    });
});

// Toggle gomb
const toggleBtn = document.querySelector('#toggle');
const content = document.querySelector('#content');

toggleBtn.addEventListener('click', () => {
    content.classList.toggle('hidden');
    toggleBtn.textContent = content.classList.contains('hidden') ? 'Show' : 'Hide';
});

// Form validáció
const form = document.querySelector('#myForm');
const nameInput = document.querySelector('#name');
const errorDiv = document.querySelector('#error');

form.addEventListener('submit', (e) => {
    e.preventDefault();

    if (nameInput.value.trim() === '') {
        errorDiv.textContent = 'Name is required!';
        nameInput.classList.add('error');
        return;
    }

    errorDiv.textContent = '';
    nameInput.classList.remove('error');
    console.log('Form submitted:', nameInput.value);
});

// Dinamikus stílus váltás
const themeBtn = document.querySelector('#theme-toggle');
themeBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
});

// Elemek számolása és megjelenítése
const counter = document.querySelector('#counter');
const items2 = document.querySelectorAll('.item');
counter.textContent = `Total items: ${items2.length}`;

// Checkbox-ok kezelése
const checkboxes = document.querySelectorAll('input[type="checkbox"]');
const selectAllBtn = document.querySelector('#select-all');

selectAllBtn.addEventListener('click', () => {
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
});

// Local Storage használata
const saveBtn = document.querySelector('#save');
const loadBtn = document.querySelector('#load');

saveBtn.addEventListener('click', () => {
    const data = { name: 'John', age: 30 };
    localStorage.setItem('userData', JSON.stringify(data));
});

loadBtn.addEventListener('click', () => {
    const data = JSON.parse(localStorage.getItem('userData'));
    console.log(data);
});

// Template elem használata
const template = document.querySelector('#item-template');
const container2 = document.querySelector('#items-container');

data.forEach(item => {
    const clone = template.content.cloneNode(true);
    clone.querySelector('.name').textContent = item.name;
    clone.querySelector('.age').textContent = item.age;
    container2.appendChild(clone);
});
