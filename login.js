document.addEventListener("DOMContentLoaded", function () {
    const particlesContainer = document.querySelector('.particles');

    for (let i = 0; i < 30; i++) {
        let particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.top = `${Math.random() * 100}vh`;
        particlesContainer.appendChild(particle);

        setInterval(() => {
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;
        }, 5000);
    }
});
