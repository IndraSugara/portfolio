// Create stars in the background
function createStars() {
  const starsContainer = document.createElement('div');
  starsContainer.className = 'stars-container';
  starsContainer.style.position = 'fixed';
  starsContainer.style.top = '0';
  starsContainer.style.left = '0';
  starsContainer.style.width = '100%';
  starsContainer.style.height = '100%';
  starsContainer.style.pointerEvents = 'none';
  starsContainer.style.zIndex = '1';
  document.body.appendChild(starsContainer);

  for (let i = 0; i < 50; i++) {
    const star = document.createElement('div');
    star.className = 'star';
    star.style.left = Math.random() * 100 + '%';
    star.style.top = Math.random() * 100 + '%';
    star.style.animationDelay = Math.random() * 2 + 's';
    starsContainer.appendChild(star);
  }
}

// Create sparkle effect on click
function createSparkle(e) {
  const sparkle = document.createElement('div');
  sparkle.className = 'sparkle';
  sparkle.style.left = e.pageX + 'px';
  sparkle.style.top = e.pageY + 'px';
  document.body.appendChild(sparkle);

  // Remove sparkle after animation
  setTimeout(() => sparkle.remove(), 1000);
}

// Add revolutionary text effect
function addRevolutionaryEffect() {
  const headings = document.querySelectorAll('h1, h2, h3');
  headings.forEach(heading => {
    if (!heading.classList.contains('revolutionary-text')) {
      heading.classList.add('revolutionary-text');
    }
  });
}

// Create unity circles
function createUnityCircles() {
  const sections = document.querySelectorAll('section');
  sections.forEach(section => {
    const circle = document.createElement('div');
    circle.className = 'unity-circle';
    circle.style.left = Math.random() * 80 + 10 + '%';
    circle.style.top = Math.random() * 80 + 10 + '%';
    section.appendChild(circle);
  });
}

// Add banner wave effect
function addBannerWave() {
  const header = document.querySelector('.header');
  const banner = document.createElement('div');
  banner.className = 'banner-wave';
  header.appendChild(banner);
}

// Loading animation
function showLoadingAnimation() {
  const overlay = document.createElement('div');
  overlay.className = 'loading-overlay';
  
  const symbol = document.createElement('div');
  symbol.className = 'loading-symbol';
  
  overlay.appendChild(symbol);
  document.body.appendChild(overlay);

  return overlay;
}

function hideLoadingAnimation(overlay) {
  overlay.style.opacity = '0';
  setTimeout(() => overlay.remove(), 500);
}

// Initialize all animations
document.addEventListener('DOMContentLoaded', () => {
  // Show loading animation
  const loadingOverlay = showLoadingAnimation();

  // Initialize animations after a short delay
  setTimeout(() => {
    createStars();
    addRevolutionaryEffect();
    createUnityCircles();
    addBannerWave();

    // Add click event for sparkles
    document.addEventListener('click', createSparkle);

    // Hide loading animation
    hideLoadingAnimation(loadingOverlay);
  }, 1500);
});

// Smooth scroll enhancement
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      // Create sparkles along the scroll path
      const start = window.pageYOffset;
      const end = target.offsetTop;
      const distance = end - start;
      const steps = 20;
      const stepSize = distance / steps;
      
      for (let i = 0; i < steps; i++) {
        setTimeout(() => {
          const sparkle = document.createElement('div');
          sparkle.className = 'sparkle';
          sparkle.style.left = (Math.random() * 100) + '%';
          sparkle.style.top = (start + (stepSize * i)) + 'px';
          document.body.appendChild(sparkle);
          setTimeout(() => sparkle.remove(), 1000);
        }, i * 50);
      }

      target.scrollIntoView({
        behavior: 'smooth'
      });
    }
  });
});

// Add hover effect to buttons
document.querySelectorAll('.btn').forEach(button => {
  button.addEventListener('mouseover', () => {
    const sparkle = document.createElement('div');
    sparkle.className = 'sparkle';
    sparkle.style.left = button.offsetLeft + (button.offsetWidth / 2) + 'px';
    sparkle.style.top = button.offsetTop + (button.offsetHeight / 2) + 'px';
    document.body.appendChild(sparkle);
    setTimeout(() => sparkle.remove(), 1000);
  });
});

// Enhance skill bars animation
function enhanceSkillBars() {
  const skillBars = document.querySelectorAll('.skill-progress');
  
  skillBars.forEach(bar => {
    const percentage = bar.getAttribute('data-percentage');
    bar.style.width = '0%';
    
    // Add sparkles during animation
    const animate = () => {
      let width = 0;
      const interval = setInterval(() => {
        if (width >= percentage) {
          clearInterval(interval);
        } else {
          width++;
          bar.style.width = width + '%';
          
          if (width % 10 === 0) {
            const sparkle = document.createElement('div');
            sparkle.className = 'sparkle';
            sparkle.style.left = bar.offsetLeft + (bar.offsetWidth * (width / 100)) + 'px';
            sparkle.style.top = bar.offsetTop + (bar.offsetHeight / 2) + 'px';
            document.body.appendChild(sparkle);
            setTimeout(() => sparkle.remove(), 1000);
          }
        }
      }, 20);
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animate();
          observer.unobserve(entry.target);
        }
      });
    });

    observer.observe(bar);
  });
} 
 