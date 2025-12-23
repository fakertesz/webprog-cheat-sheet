const canvas = document.querySelector('canvas');
const ctx = canvas.getContext("2d");

// Application state
const plane = {
  x: 0,
  y: 20,
  width: 60,
  height: 30,
  vx: 0,
  img: new Image(),
};
const parcel = {
  x: 0,
  y: plane.y + plane.height,
  width: 30,
  height: 30,
  vx: 0,
  vy: 0,
  ay: 0,
  img: new Image(),
};
const house = {
  x: 400,
  y: canvas.height - 120,
  width: 100,
  height: 100,
  img: new Image(),
};
let gameState = 0; // 0-start, 1-moving, 2-dropping, 3-hit, 4-missed

// ================= Game loop =====================

// Time-based animation (from the lecture slide)
let lastFrameTime = performance.now();

function next(currentTime = performance.now()) {
  const dt = (currentTime - lastFrameTime) / 1000; // seconds
  lastFrameTime = currentTime;

  update(dt); // Update current state
  render(); // Rerender the frame

  requestAnimationFrame(next);
}

function update(dt) {
  // c. Repülő mozgatása, ha elindult
  if (gameState >= 1 && gameState < 3) {
    plane.x += plane.vx * dt;
  }

  // d. Csomag mozgatása a repülővel együtt (ha még nincs ledobva)
  if (gameState === 1) {
    parcel.x = plane.x;
    parcel.y = plane.y + plane.height;
  }

  // e. Csomag zuhanása (ha le van dobva)
  if (gameState === 2) {
    parcel.vy += parcel.ay * dt;
    parcel.y += parcel.vy * dt;
    parcel.x += parcel.vx * dt;

    // f. Ütközés ellenőrzése házzal
    if (isCollision(parcel, house)) {
      gameState = 3; // hit
      parcel.ay = 0;
      parcel.vy = 0;
      parcel.vx = 0;
    }

    // g. Csomag eléri a vászon alját
    if (parcel.y + parcel.height >= canvas.height) {
      gameState = 4; // missed
      parcel.ay = 0;
      parcel.vy = 0;
      parcel.vx = 0;
      parcel.y = canvas.height - parcel.height;
    }
  }
}

function render() {
  // a. Vászon törlése
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // b. Képek kirajzolása (vagy téglalapok, ha képek nem töltődtek be)
  // Ház
  if (house.img.complete) {
    ctx.drawImage(house.img, house.x, house.y, house.width, house.height);
  } else {
    ctx.fillStyle = 'brown';
    ctx.fillRect(house.x, house.y, house.width, house.height);
  }

  // Repülő
  if (plane.img.complete) {
    ctx.drawImage(plane.img, plane.x, plane.y, plane.width, plane.height);
  } else {
    ctx.fillStyle = 'blue';
    ctx.fillRect(plane.x, plane.y, plane.width, plane.height);
  }

  // Csomag
  if (parcel.img.complete) {
    ctx.drawImage(parcel.img, parcel.x, parcel.y, parcel.width, parcel.height);
  } else {
    ctx.fillStyle = 'red';
    ctx.fillRect(parcel.x, parcel.y, parcel.width, parcel.height);
  }

  // f, g. Üzenetek megjelenítése
  if (gameState === 3) {
    ctx.fillStyle = 'green';
    ctx.font = '48px Arial';
    ctx.fillText('Delivered!', canvas.width / 2 - 120, canvas.height / 2);
  } else if (gameState === 4) {
    ctx.fillStyle = 'red';
    ctx.font = '48px Arial';
    ctx.fillText('Missed!', canvas.width / 2 - 80, canvas.height / 2);
  }
}

// c, e. Kattintás kezelése
canvas.addEventListener('click', () => {
  if (gameState === 0) {
    // Első kattintás: indítsd el a repülőt
    gameState = 1;
    plane.vx = 200; // 200 px/s
  } else if (gameState === 1) {
    // Második kattintás: dobd le a csomagot
    gameState = 2;
    parcel.ay = 300; // 300 px/s^2
    parcel.vx = plane.vx; // Csomag vízszintes sebessége megegyezik a repülőével
  }
});

// Start the loop
plane.img.src = "plane.png";
house.img.src = "house.png";
parcel.img.src = "parcel.png";
next(); 

// =============== Utility functions =================

function isCollision(box1, box2) {
  return !(
    box2.y + box2.height < box1.y ||
    box1.x + box1.width < box2.x ||
    box1.y + box1.height < box2.y ||
    box2.x + box2.width < box1.x
  );
}