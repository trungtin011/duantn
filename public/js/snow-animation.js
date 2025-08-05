// Dynamic Snow Animation
class SnowAnimation {
    constructor(container) {
        this.container = container;
        this.snowflakes = [];
        this.maxSnowflakes = 20;
        this.init();
    }

    init() {
        this.createSnowflakes();
        this.animate();
    }

    createSnowflakes() {
        for (let i = 0; i < this.maxSnowflakes; i++) {
            this.createSnowflake();
        }
    }

    createSnowflake() {
        const snowflake = document.createElement('div');
        snowflake.className = 'dynamic-snowflake';
        snowflake.innerHTML = '❅';
        
        // Random properties
        const size = Math.random() * 0.8 + 0.4;
        const left = Math.random() * 100;
        const animationDuration = Math.random() * 8 + 6;
        const animationDelay = Math.random() * 5;
        
        // Random snow colors
        const snowColors = ['#87CEEB', '#B0E0E6', '#ADD8E6', '#E0F6FF', '#A8D8EA'];
        const randomColor = snowColors[Math.floor(Math.random() * snowColors.length)];
        
        snowflake.style.cssText = `
            position: absolute;
            left: ${left}%;
            font-size: ${size}em;
            color: ${randomColor};
            text-shadow: 0 0 8px ${randomColor}90, 0 0 15px ${randomColor}60;
            animation: dynamicSnowfall ${animationDuration}s linear infinite;
            animation-delay: ${animationDelay}s;
            opacity: 0.9;
            pointer-events: none;
            z-index: 1;
            filter: drop-shadow(0 0 3px ${randomColor}80);
        `;

        this.container.appendChild(snowflake);
        this.snowflakes.push(snowflake);
    }

    animate() {
        // Remove snowflakes that are out of view and create new ones
        setInterval(() => {
            this.snowflakes.forEach((snowflake, index) => {
                const rect = snowflake.getBoundingClientRect();
                if (rect.top > window.innerHeight + 100) {
                    snowflake.remove();
                    this.snowflakes.splice(index, 1);
                    this.createSnowflake();
                }
            });
        }, 500);
        
        // Continuously add new snowflakes
        setInterval(() => {
            if (this.snowflakes.length < this.maxSnowflakes) {
                this.createSnowflake();
            }
        }, 800);
    }
}

// Initialize snow animation immediately
function initSnowAnimation() {
    const snowContainers = document.querySelectorAll('.snow-container');
    snowContainers.forEach(container => {
        new SnowAnimation(container);
    });
}

// Run immediately if DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSnowAnimation);
} else {
    initSnowAnimation();
}

// Add CSS for dynamic snowflakes
const style = document.createElement('style');
style.textContent = `
    @keyframes dynamicSnowfall {
        0% {
            transform: translateY(-50px) rotate(0deg);
            opacity: 0;
        }
        5% {
            opacity: 0.9;
        }
        95% {
            opacity: 0.9;
        }
        100% {
            transform: translateY(calc(100vh + 50px)) rotate(360deg);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style); 