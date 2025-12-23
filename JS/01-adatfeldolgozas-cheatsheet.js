// ============================================
// JAVASCRIPT ADATFELDOLGOZÁS - TÖMBÖK, OBJEKTUMOK
// ============================================

// ============ TÖMB METÓDUSOK ============

// --- MAP: Átalakítás, új tömb létrehozása ---
const numbers = [1, 2, 3, 4, 5];
const doubled = numbers.map(n => n * 2); // [2, 4, 6, 8, 10]
const squared = numbers.map(n => n ** 2); // [1, 4, 9, 16, 25]

const users = [
    { name: 'Anna', age: 25 },
    { name: 'Béla', age: 30 }
];
const names = users.map(user => user.name); // ['Anna', 'Béla']
const ageInMonths = users.map(u => ({ ...u, ageInMonths: u.age * 12 }));

// --- FILTER: Szűrés ---
const evens = numbers.filter(n => n % 2 === 0); // [2, 4]
const odds = numbers.filter(n => n % 2 !== 0); // [1, 3, 5]
const adults = users.filter(user => user.age >= 18);
const startsWithA = users.filter(u => u.name.startsWith('A'));

// --- REDUCE: Összegzés, akkumuláció ---
const sum = numbers.reduce((acc, n) => acc + n, 0); // 15
const product = numbers.reduce((acc, n) => acc * n, 1); // 120
const max = numbers.reduce((acc, n) => Math.max(acc, n), -Infinity);

// Objektummá alakítás
const userById = users.reduce((acc, user) => {
    acc[user.name] = user;
    return acc;
}, {});

// Csoportosítás
const grouped = users.reduce((acc, user) => {
    const key = user.age >= 30 ? 'senior' : 'junior';
    if (!acc[key]) acc[key] = [];
    acc[key].push(user);
    return acc;
}, {});

// --- FIND & FINDINDEX: Keresés ---
const firstEven = numbers.find(n => n % 2 === 0); // 2
const firstEvenIndex = numbers.findIndex(n => n % 2 === 0); // 1
const anna = users.find(u => u.name === 'Anna');

// --- SOME & EVERY: Logikai ellenőrzés ---
const hasEven = numbers.some(n => n % 2 === 0); // true
const allPositive = numbers.every(n => n > 0); // true
const allAdults = users.every(u => u.age >= 18);

// --- SORT: Rendezés ---
const sorted = [...numbers].sort((a, b) => a - b); // növekvő
const sortedDesc = [...numbers].sort((a, b) => b - a); // csökkenő
const sortedByAge = [...users].sort((a, b) => a.age - b.age);
const sortedByName = [...users].sort((a, b) => a.name.localeCompare(b.name));

// --- FOREACH: Iteráció (nem tér vissza semmivel) ---
numbers.forEach((n, index) => console.log(`Index ${index}: ${n}`));

// --- INCLUDES: Tartalmazza-e ---
const hasFive = numbers.includes(5); // true

// --- SLICE: Részlet másolása ---
const firstThree = numbers.slice(0, 3); // [1, 2, 3]
const lastTwo = numbers.slice(-2); // [4, 5]

// --- SPLICE: Módosítás (mutáló!) ---
const arr = [1, 2, 3, 4, 5];
arr.splice(2, 1); // törlés: [1, 2, 4, 5]
arr.splice(2, 0, 3); // beszúrás: [1, 2, 3, 4, 5]

// --- CONCAT: Összekapcsolás ---
const combined = [1, 2].concat([3, 4]); // [1, 2, 3, 4]
const combined2 = [...[1, 2], ...[3, 4]]; // spread operátorral

// --- FLAT & FLATMAP: Lapítás ---
const nested = [[1, 2], [3, 4]];
const flattened = nested.flat(); // [1, 2, 3, 4]
const flatMapped = numbers.flatMap(n => [n, n * 2]); // [1, 2, 2, 4, 3, 6, ...]

// ============ OBJEKTUM MŰVELETEK ============

const person = { name: 'János', age: 28, city: 'Budapest' };

// --- KULCSOK, ÉRTÉKEK, BEJEGYZÉSEK ---
const keys = Object.keys(person); // ['name', 'age', 'city']
const values = Object.values(person); // ['János', 28, 'Budapest']
const entries = Object.entries(person); // [['name', 'János'], ['age', 28], ...]

// Bejegyzéseken iterálás
Object.entries(person).forEach(([key, value]) => {
    console.log(`${key}: ${value}`);
});

// Objektum átalakítása
const doubled2 = Object.fromEntries(
    Object.entries(person).map(([key, value]) =>
        [key, typeof value === 'number' ? value * 2 : value]
    )
);

// --- MÁSOLÁS & ÖSSZEFÉSÜLÉS ---
const copy = { ...person }; // spread operátor
const merged = { ...person, country: 'Hungary', age: 29 }; // felülírás + új mező
const assigned = Object.assign({}, person, { age: 29 }); // Object.assign

// --- DESTRUKTURÁLÁS ---
const { name, age } = person;
const { name: firstName, ...rest } = person; // rest operátor

// --- OPTIONAL CHAINING & NULLISH COALESCING ---
const data = { user: { profile: { email: 'test@test.com' } } };
const email = data?.user?.profile?.email; // 'test@test.com'
const phone = data?.user?.profile?.phone ?? 'N/A'; // 'N/A'

// ============ KOMBINÁLT PÉLDÁK ============

// Szűrés + Rendezés + Map
const products = [
    { name: 'Laptop', price: 300000, category: 'electronics' },
    { name: 'Phone', price: 150000, category: 'electronics' },
    { name: 'Book', price: 3000, category: 'books' }
];

const expensiveElectronics = products
    .filter(p => p.category === 'electronics' && p.price > 100000)
    .sort((a, b) => b.price - a.price)
    .map(p => ({ ...p, priceWithVAT: p.price * 1.27 }));

// Csoportosítás + Összegzés
const totalByCategory = products.reduce((acc, p) => {
    acc[p.category] = (acc[p.category] || 0) + p.price;
    return acc;
}, {});

// Átlag számítás
const avgPrice = products.reduce((sum, p) => sum + p.price, 0) / products.length;

// Legdrágább termék
const mostExpensive = products.reduce((max, p) =>
    p.price > max.price ? p : max
);

// Egyedi értékek
const categories = [...new Set(products.map(p => p.category))];

// Több tömb összefésülése
const array1 = [1, 2, 3];
const array2 = [3, 4, 5];
const uniqueMerged = [...new Set([...array1, ...array2])]; // [1, 2, 3, 4, 5]

// ============ STRING METÓDUSOK ============

const text = "Hello World";
const upper = text.toUpperCase(); // "HELLO WORLD"
const lower = text.toLowerCase(); // "hello world"
const trimmed = "  hello  ".trim(); // "hello"
const split = text.split(' '); // ['Hello', 'World']
const joined = ['Hello', 'World'].join(' '); // "Hello World"
const replaced = text.replace('World', 'JavaScript'); // "Hello JavaScript"
const includes = text.includes('World'); // true
const startsWith = text.startsWith('Hello'); // true
const slice = text.slice(0, 5); // "Hello"

// ============ MATH MŰVELETEK ============

Math.max(1, 2, 3); // 3
Math.min(1, 2, 3); // 1
Math.round(4.7); // 5
Math.floor(4.7); // 4
Math.ceil(4.3); // 5
Math.random(); // 0 és 1 között
Math.floor(Math.random() * 100); // 0-99 közötti egész

// ============ DATE MŰVELETEK ============

const now = new Date();
const year = now.getFullYear();
const month = now.getMonth(); // 0-11
const day = now.getDate();
const hours = now.getHours();
const formatted = now.toLocaleDateString('hu-HU');

// ============ PRAKTIKUS PÉLDÁK ============

// Hallgatók átlag jegyei
const students = [
    { name: 'Anna', grades: [4, 5, 3, 5] },
    { name: 'Béla', grades: [3, 3, 4, 2] }
];

const averages = students.map(s => ({
    name: s.name,
    average: s.grades.reduce((sum, g) => sum + g, 0) / s.grades.length
}));

// Szűrés több feltétel alapján
const filtered = products.filter(p =>
    p.price > 50000 &&
    p.category === 'electronics' &&
    p.name.includes('Phone')
);

// Duplikátumok eltávolítása objektum tömb esetén
const items = [
    { id: 1, name: 'A' },
    { id: 2, name: 'B' },
    { id: 1, name: 'A' }
];
const unique = Array.from(
    new Map(items.map(item => [item.id, item])).values()
);

// Páros-páratlan csoportosítás
const parityGroups = numbers.reduce((acc, n) => {
    const key = n % 2 === 0 ? 'even' : 'odd';
    acc[key] = [...(acc[key] || []), n];
    return acc;
}, {});
