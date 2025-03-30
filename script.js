document.addEventListener("DOMContentLoaded", function () {
    // Starfield Effect
    const canvas = document.getElementById("starfield");
    const ctx = canvas.getContext("2d");
    let stars = [];

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    function createStars() {
        stars = [];
        for (let i = 0; i < 150; i++) {
            stars.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 3,
                speed: Math.random() * 1.5 + 0.5
            });
        }
    }

    function animateStars() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
        
        stars.forEach(star => {
            ctx.beginPath();
            ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
            ctx.fill();
            star.y += star.speed;
            if (star.y > canvas.height) {
                star.y = 0;
                star.x = Math.random() * canvas.width;
            }
        });

        requestAnimationFrame(animateStars);
    }

    resizeCanvas();
    createStars();
    animateStars();

    window.addEventListener("resize", () => {
        resizeCanvas();
        createStars();
    });
});

// login button 
document.getElementById('login-btn').addEventListener('click', function() {
    window.location.href = 'login.html'; 
});

//signup button
document.getElementById('signup-btn').addEventListener('click', function() {
    window.location.href = 'signup.html'; 
});

