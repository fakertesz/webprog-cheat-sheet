// ========= Selected elements =========
const canvas = document.querySelector('canvas');
const ctx = canvas.getContext("2d");

// =============== Utilities =================
function isCollision(box1, box2) {
  return !(
    box2.y + box2.height < box1.y ||
    box1.x + box1.width < box2.x ||
    box1.y + box1.height < box2.y ||
    box2.x + box2.width < box1.x
  );
}

function random(a, b) {
  return Math.floor(Math.random() * (b - a + 1)) + a;
}

// ========= Application state =========
const arrow = {
  fx: 10,
  fy: 290,
  tx: 30,
  ty: 350,
};
const ball = {
  x: 10,
  y: 290,
  width: 20,
  height: 20,
  vx: 0,    // px/s
  vy: 0,    // px/s
  ay: 0,  // px/s2
  img: new Image(),
};
const windows = [
  { x: 479, y: 122, width: 15, height: 30 },
  { x: 494, y: 240, width: 18, height: 42 },
  { x: 562, y: 240, width: 18, height: 42 },
];
const bush = {
  x: 250,
  y: 200,
  width: 100,
  height: 200,
  img: new Image(),
};
// c. Choose random window
let lovedWindow = random(0, 2);
let gameState = 0; // 0-start, 1-moving, 2-hit, 3-missed
let message = "";

// ========= Time-based animation (from the lecture slide) =========
let lastFrameTime = performance.now();

function next(currentTime = performance.now()) {
  const dt = (currentTime - lastFrameTime) / 1000; // seconds
  lastFrameTime = currentTime;

  update(dt); // Update current state
  render(); // Rerender the frame

  requestAnimationFrame(next);
}

function update(dt) {
  // e. Update ball position if moving
  if (gameState === 1) {
    ball.x += ball.vx * dt;
    ball.y += ball.vy * dt;
    ball.vy += ball.ay * dt;

    // f. Check collision with bush
    if (isCollision(ball, bush)) {
      gameState = 2;
      ball.vx = 0;
      ball.vy = 0;
      ball.ay = 0;
      message = "Oooops!";
    }

    // g. Check collision with windows
    for (let i = 0; i < windows.length; i++) {
      if (isCollision(ball, windows[i])) {
        gameState = 2;
        ball.vx = 0;
        ball.vy = 0;
        ball.ay = 0;
        if (i === lovedWindow) {
          message = "Come, my lover!";
        } else {
          message = "Oooops!";
        }
      }
    }

    // h. Check if ball hits ground
    if (ball.y + ball.height >= canvas.height) {
      gameState = 2;
      ball.vx = 0;
      ball.vy = 0;
      ball.ay = 0;
      ball.y = canvas.height - ball.height;
      message = "Oooops!";
    }
  }
}

function render() {
  // Background
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // a. Draw rectangles (and b. use images)
  // Draw bush
  if (bush.img.complete) {
    ctx.drawImage(bush.img, bush.x, bush.y, bush.width, bush.height);
  } else {
    ctx.fillStyle = 'green';
    ctx.fillRect(bush.x, bush.y, bush.width, bush.height);
  }

  // c. Draw windows (yellow for loved window, gray for others)
  for (let i = 0; i < windows.length; i++) {
    if (i === lovedWindow) {
      ctx.fillStyle = 'yellow';
    } else {
      ctx.fillStyle = 'gray';
    }
    ctx.fillRect(windows[i].x, windows[i].y, windows[i].width, windows[i].height);
  }

  // Draw ball
  if (ball.img.complete) {
    ctx.drawImage(ball.img, ball.x, ball.y, ball.width, ball.height);
  } else {
    ctx.fillStyle = 'black';
    ctx.fillRect(ball.x, ball.y, ball.width, ball.height);
  }

  // d. Draw arrow if in start state
  if (gameState === 0) {
    ctx.strokeStyle = 'red';
    ctx.lineWidth = 3;
    ctx.beginPath();
    ctx.moveTo(arrow.fx, arrow.fy);
    ctx.lineTo(arrow.tx, arrow.ty);
    ctx.stroke();
  }

  // Draw message if game ended
  if (gameState === 2) {
    ctx.fillStyle = 'white';
    ctx.font = '30px Arial';
    ctx.fillText(message, 50, 50);
  }
}

// ========= Event listeners =========
// d. Mousemove - update arrow target position
canvas.addEventListener('mousemove', (e) => {
  if (gameState === 0) {
    arrow.tx = e.offsetX;
    arrow.ty = e.offsetY;
  }
});

// e. Click - start ball movement
canvas.addEventListener('click', () => {
  if (gameState === 0) {
    gameState = 1;

    // Calculate velocity based on arrow direction
    const dx = arrow.tx - arrow.fx;
    const dy = arrow.ty - arrow.fy;

    ball.vx = dx * 3;
    ball.vy = dy * 3;
    ball.ay = 300; // gravity
  }
});

// ========= Start the loop =========
bush.img.src = "bush.png";
ball.img.src = "ball.png";
next(); 
