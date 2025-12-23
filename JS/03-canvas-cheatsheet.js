// ============================================
// JAVASCRIPT CANVAS RAJZOLÁS & ANIMÁCIÓ
// ============================================

// ============ CANVAS ALAPOK ============

// Canvas elem létrehozása HTML-ben:
// <canvas id="myCanvas" width="800" height="600"></canvas>

// Canvas és context megszerzése
const canvas = document.querySelector('#myCanvas');
const ctx = canvas.getContext('2d');

// Canvas méret beállítása
canvas.width = 800;
canvas.height = 600;

// Canvas törlése
ctx.clearRect(0, 0, canvas.width, canvas.height);

// ============ RAJZOLÁSI ALAPOK ============

// --- TÉGLALAP ---
ctx.fillStyle = 'red'; // kitöltési szín
ctx.fillRect(50, 50, 200, 100); // x, y, width, height

ctx.strokeStyle = 'blue'; // körvonal szín
ctx.lineWidth = 3; // körvonal vastagság
ctx.strokeRect(300, 50, 200, 100);

ctx.clearRect(60, 60, 50, 50); // törlés (lyuk)

// --- VONAL ---
ctx.beginPath();
ctx.moveTo(50, 200); // kezdőpont
ctx.lineTo(200, 250); // végpont
ctx.lineTo(150, 300);
ctx.stroke(); // vonal rajzolása
ctx.closePath();

// Vonal stílus
ctx.lineWidth = 5;
ctx.lineCap = 'round'; // 'butt', 'round', 'square'
ctx.lineJoin = 'round'; // 'miter', 'round', 'bevel'

// --- KÖR & ÍV ---
ctx.beginPath();
ctx.arc(400, 300, 50, 0, Math.PI * 2); // x, y, radius, startAngle, endAngle
ctx.fillStyle = 'green';
ctx.fill();
ctx.stroke();

// Félkör
ctx.beginPath();
ctx.arc(500, 300, 50, 0, Math.PI);
ctx.fill();

// Íves vonal
ctx.beginPath();
ctx.moveTo(100, 400);
ctx.arcTo(200, 400, 200, 500, 50); // x1, y1, x2, y2, radius
ctx.stroke();

// --- GÖRBE (BEZIER) ---
// Kvadratikus görbe
ctx.beginPath();
ctx.moveTo(50, 450);
ctx.quadraticCurveTo(200, 400, 250, 450); // cpx, cpy, x, y
ctx.stroke();

// Köbös görbe
ctx.beginPath();
ctx.moveTo(300, 450);
ctx.bezierCurveTo(350, 400, 450, 500, 500, 450); // cp1x, cp1y, cp2x, cp2y, x, y
ctx.stroke();

// --- SZÖVEG ---
ctx.font = '30px Arial';
ctx.fillStyle = 'black';
ctx.fillText('Hello Canvas!', 50, 550); // x, y

ctx.strokeStyle = 'red';
ctx.strokeText('Outline text', 300, 550);

// Szöveg igazítás
ctx.textAlign = 'center'; // 'left', 'right', 'center', 'start', 'end'
ctx.textBaseline = 'middle'; // 'top', 'hanging', 'middle', 'alphabetic', 'bottom'

// Szöveg méret
const metrics = ctx.measureText('Hello');
console.log(metrics.width);

// ============ SZÍNEK & STÍLUSOK ============

// Kitöltési szín
ctx.fillStyle = 'red';
ctx.fillStyle = '#FF0000';
ctx.fillStyle = 'rgb(255, 0, 0)';
ctx.fillStyle = 'rgba(255, 0, 0, 0.5)'; // átlátszóság

// Gradiens - Lineáris
const gradient = ctx.createLinearGradient(0, 0, 200, 0); // x0, y0, x1, y1
gradient.addColorStop(0, 'red');
gradient.addColorStop(0.5, 'yellow');
gradient.addColorStop(1, 'green');
ctx.fillStyle = gradient;
ctx.fillRect(50, 50, 200, 100);

// Gradiens - Radiális
const radialGradient = ctx.createRadialGradient(400, 100, 20, 400, 100, 80);
radialGradient.addColorStop(0, 'yellow');
radialGradient.addColorStop(1, 'red');
ctx.fillStyle = radialGradient;
ctx.fillRect(300, 50, 200, 100);

// Minta (pattern)
const img = new Image();
img.src = 'pattern.png';
img.onload = () => {
    const pattern = ctx.createPattern(img, 'repeat'); // 'repeat', 'repeat-x', 'repeat-y', 'no-repeat'
    ctx.fillStyle = pattern;
    ctx.fillRect(0, 0, 200, 200);
};

// Átlátszóság
ctx.globalAlpha = 0.5; // 0-1

// ============ TRANSZFORMÁCIÓK ============

// Mentés és visszaállítás
ctx.save(); // állapot mentése
// ... transzformációk
ctx.restore(); // állapot visszaállítása

// Eltolás
ctx.translate(100, 100); // x, y

// Forgatás
ctx.rotate(Math.PI / 4); // 45 fok (radiánban!)

// Skálázás
ctx.scale(2, 2); // x, y

// Példa: Forgatott téglalap
ctx.save();
ctx.translate(400, 300); // középpont
ctx.rotate(Math.PI / 4);
ctx.fillRect(-50, -50, 100, 100); // középről rajzol
ctx.restore();

// ============ KÉP RAJZOLÁS ============

const image = new Image();
image.src = 'image.jpg';
image.onload = () => {
    // Teljes kép
    ctx.drawImage(image, 0, 0);

    // Méretezett kép
    ctx.drawImage(image, 0, 0, 200, 150); // x, y, width, height

    // Kivágás és méretezés
    ctx.drawImage(
        image,
        50, 50, 100, 100, // forrás: sx, sy, sWidth, sHeight
        0, 0, 200, 200 // cél: dx, dy, dWidth, dHeight
    );
};

// ============ PIXEL MANIPULÁCIÓ ============

// Pixel adatok olvasása
const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
const pixels = imageData.data; // [r, g, b, a, r, g, b, a, ...]

// Pixel módosítása
for (let i = 0; i < pixels.length; i += 4) {
    pixels[i] = 255 - pixels[i]; // red inverz
    pixels[i + 1] = 255 - pixels[i + 1]; // green inverz
    pixels[i + 2] = 255 - pixels[i + 2]; // blue inverz
    // pixels[i + 3] = alfa (átlátszóság)
}

// Pixel adatok visszaírása
ctx.putImageData(imageData, 0, 0);

// ============ ANIMÁCIÓ ============

// --- requestAnimationFrame alapú animáció ---
let x = 0;
let y = 100;
let dx = 2; // sebesség x irányban
let dy = 1; // sebesség y irányban

function animate() {
    // Canvas törlése
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Rajzolás
    ctx.beginPath();
    ctx.arc(x, y, 20, 0, Math.PI * 2);
    ctx.fillStyle = 'blue';
    ctx.fill();

    // Mozgatás
    x += dx;
    y += dy;

    // Falról visszapattanás
    if (x + 20 > canvas.width || x - 20 < 0) {
        dx = -dx;
    }
    if (y + 20 > canvas.height || y - 20 < 0) {
        dy = -dy;
    }

    // Következő frame
    requestAnimationFrame(animate);
}

// Animáció indítása
animate();

// ============ GYAKORLATI PÉLDÁK ============

// --- Több labda animáció OOP-vel ---
class Ball {
    constructor(x, y, radius, dx, dy, color) {
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.dx = dx;
        this.dy = dy;
        this.color = color;
    }

    draw(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
        ctx.strokeStyle = 'black';
        ctx.stroke();
    }

    update(canvasWidth, canvasHeight) {
        this.x += this.dx;
        this.y += this.dy;

        if (this.x + this.radius > canvasWidth || this.x - this.radius < 0) {
            this.dx = -this.dx;
        }
        if (this.y + this.radius > canvasHeight || this.y - this.radius < 0) {
            this.dy = -this.dy;
        }
    }
}

// Labdák generálása
const balls = Array.from({ length: 10 }, () => new Ball(
    Math.random() * canvas.width,
    Math.random() * canvas.height,
    Math.random() * 20 + 10,
    (Math.random() - 0.5) * 4,
    (Math.random() - 0.5) * 4,
    `hsl(${Math.random() * 360}, 50%, 50%)`
));

function animateBalls() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    balls.forEach(ball => {
        ball.update(canvas.width, canvas.height);
        ball.draw(ctx);
    });

    requestAnimationFrame(animateBalls);
}

animateBalls();

// --- Egér követés ---
let mouseX = 0;
let mouseY = 0;

canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    mouseX = e.clientX - rect.left;
    mouseY = e.clientY - rect.top;
});

function followMouse() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.beginPath();
    ctx.arc(mouseX, mouseY, 30, 0, Math.PI * 2);
    ctx.fillStyle = 'red';
    ctx.fill();

    requestAnimationFrame(followMouse);
}

// --- Kattintásra rajzolás ---
const particles = [];

canvas.addEventListener('click', (e) => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    // Részecskék generálása
    for (let i = 0; i < 20; i++) {
        particles.push({
            x: x,
            y: y,
            dx: (Math.random() - 0.5) * 6,
            dy: (Math.random() - 0.5) * 6,
            radius: Math.random() * 3 + 1,
            life: 100
        });
    }
});

function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particles.forEach((p, index) => {
        p.x += p.dx;
        p.y += p.dy;
        p.life--;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255, 0, 0, ${p.life / 100})`;
        ctx.fill();

        if (p.life <= 0) {
            particles.splice(index, 1);
        }
    });

    requestAnimationFrame(animateParticles);
}

// --- Gravitáció szimuláció ---
class GravityBall {
    constructor(x, y, radius, color) {
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.color = color;
        this.dy = 0;
        this.gravity = 0.5;
        this.bounce = 0.7; // pattanás
    }

    update(canvasHeight) {
        this.dy += this.gravity;
        this.y += this.dy;

        // Földdel ütközés
        if (this.y + this.radius > canvasHeight) {
            this.y = canvasHeight - this.radius;
            this.dy = -this.dy * this.bounce;
        }
    }

    draw(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.fill();
    }
}

// --- Vonal rajzolás egérrel ---
let drawing = false;
let lastX = 0;
let lastY = 0;

canvas.addEventListener('mousedown', (e) => {
    drawing = true;
    const rect = canvas.getBoundingClientRect();
    lastX = e.clientX - rect.left;
    lastY = e.clientY - rect.top;
});

canvas.addEventListener('mousemove', (e) => {
    if (!drawing) return;

    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.strokeStyle = 'black';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.stroke();

    lastX = x;
    lastY = y;
});

canvas.addEventListener('mouseup', () => {
    drawing = false;
});

// --- Sprite animáció ---
const sprite = new Image();
sprite.src = 'spritesheet.png';

const spriteWidth = 64;
const spriteHeight = 64;
let frameX = 0;
let frameY = 0;
const maxFrames = 4;
let frameCount = 0;
const frameDelay = 10;

function animateSprite() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.drawImage(
        sprite,
        frameX * spriteWidth, frameY * spriteHeight, // forrás
        spriteWidth, spriteHeight,
        100, 100, // cél pozíció
        spriteWidth * 2, spriteHeight * 2 // cél méret (2x nagyítás)
    );

    frameCount++;
    if (frameCount % frameDelay === 0) {
        frameX++;
        if (frameX >= maxFrames) {
            frameX = 0;
        }
    }

    requestAnimationFrame(animateSprite);
}

// --- Ütközés detektálás ---
function circleCollision(c1, c2) {
    const dx = c2.x - c1.x;
    const dy = c2.y - c1.y;
    const distance = Math.sqrt(dx * dx + dy * dy);
    return distance < c1.radius + c2.radius;
}

// --- FPS számláló ---
let lastTime = 0;
let fps = 0;

function calculateFPS(timestamp) {
    if (lastTime) {
        fps = Math.round(1000 / (timestamp - lastTime));
    }
    lastTime = timestamp;

    ctx.fillStyle = 'black';
    ctx.font = '20px Arial';
    ctx.fillText(`FPS: ${fps}`, 10, 30);

    requestAnimationFrame(calculateFPS);
}
