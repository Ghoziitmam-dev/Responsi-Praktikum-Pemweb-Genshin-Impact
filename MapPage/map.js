const wrapper = document.getElementById('mapWrapper');
const img = document.getElementById('mapImg');
const zoomInBtn = document.getElementById('zoomIn');
const zoomOutBtn = document.getElementById('zoomOut');
const resetBtn = document.getElementById('resetZoom');

let scale = 0.4;
let posX = 0;
let posY = 0;
let isDragging = false;
let startX, startY;

img.onload = () => {
    updateTransform();
};

function updateTransform() {
    img.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
}

zoomInBtn.addEventListener('click', () => {
    scale = Math.min(scale + 0.1, 2);
    updateTransform();
});

zoomOutBtn.addEventListener('click', () => {
    scale = Math.max(scale - 0.1, 0.2);
    updateTransform();
});

resetBtn.addEventListener('click', () => {
    scale = 0.4;
    posX = 0;
    posY = 0;
    updateTransform();
});

wrapper.addEventListener('wheel', (e) => {
    e.preventDefault();
    const zoomSpeed = 0.05;
    if (e.deltaY < 0) {
        scale = Math.min(scale + zoomSpeed, 2);
    } else {
        scale = Math.max(scale - zoomSpeed, 0.2);
    }
    updateTransform();
});

wrapper.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.clientX - posX;
    startY = e.clientY - posY;
});

window.addEventListener('mouseup', () => {
    isDragging = false;
});

window.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    posX = e.clientX - startX;
    posY = e.clientY - startY;
    updateTransform();
});

wrapper.addEventListener('touchstart', (e) => {
    if (e.touches.length === 1) {
        isDragging = true;
        startX = e.touches[0].clientX - posX;
        startY = e.touches[0].clientY - posY;
    }
});

window.addEventListener('touchend', () => {
    isDragging = false;
});

window.addEventListener('touchmove', (e) => {
    if (!isDragging || e.touches.length !== 1) return;
    posX = e.touches[0].clientX - startX;
    posY = e.touches[0].clientY - startY;
    updateTransform();
});