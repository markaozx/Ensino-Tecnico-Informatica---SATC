let currentSlideIndex = 0;
let autoPlayInterval;
const autoPlayDelay = 5000;

function initCarousel() {
    const slides = document.querySelectorAll('.carousel-slide img');
    const dots = document.querySelectorAll('.dot');
    const container = document.querySelector('.carousel-container');
    
    if (slides.length === 0) return;

    // Define altura baseada na proporção do Banner5
    setCarouselHeight();
    window.addEventListener('resize', setCarouselHeight);

    showSlide(currentSlideIndex);
    startAutoPlay();

    const prevBtn = document.querySelector('.carousel-prev');
    const nextBtn = document.querySelector('.carousel-next');
    
    if (prevBtn) prevBtn.addEventListener('click', () => changeSlide(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => changeSlide(1));

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    if (container) {
        container.addEventListener('mouseenter', stopAutoPlay);
        container.addEventListener('mouseleave', startAutoPlay);
    }

    let touchStartX = 0;
    let touchEndX = 0;

    if (container) {
        container.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoPlay();
        }, { passive: true });

        container.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
            startAutoPlay();
        }, { passive: true });
    }
}

function setCarouselHeight() {
    const container = document.querySelector('.carousel-container');
    if (!container) return;
    
    const windowWidth = window.innerWidth;
    
    // Proporção do Banner5: 1920x934 (aproximadamente 2.056:1)
    // Isso mantém a altura proporcional em qualquer largura
    let height;
    
    if (windowWidth >= 1920) {
        height = 934; // Altura fixa para telas grandes (1920x934)
    } else if (windowWidth >= 1440) {
        height = windowWidth / 2.056; // Mantém proporção 1920x934
    } else if (windowWidth >= 1024) {
        height = windowWidth / 2.2; // Proporção ajustada para laptops
    } else if (windowWidth >= 768) {
        height = windowWidth / 2.5; // Tablets
    } else {
        height = windowWidth / 1.8; // Mobile - mais vertical para melhor visualização
    }
    
    container.style.height = height + 'px';
}

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide img');
    const dots = document.querySelectorAll('.dot');
    const slideContainer = document.querySelector('.carousel-slide');
    
    if (!slideContainer || slides.length === 0) return;

    if (index >= slides.length) currentSlideIndex = 0;
    if (index < 0) currentSlideIndex = slides.length - 1;

    const offset = -currentSlideIndex * 100;
    slideContainer.style.transform = `translateX(${offset}%)`;

    dots.forEach(dot => dot.classList.remove('active'));
    if (dots[currentSlideIndex]) {
        dots[currentSlideIndex].classList.add('active');
    }
}

function changeSlide(direction) {
    stopAutoPlay();
    currentSlideIndex += direction;
    const slides = document.querySelectorAll('.carousel-slide img');
    
    if (currentSlideIndex >= slides.length) currentSlideIndex = 0;
    if (currentSlideIndex < 0) currentSlideIndex = slides.length - 1;
    
    showSlide(currentSlideIndex);
    startAutoPlay();
}

function goToSlide(index) {
    stopAutoPlay();
    currentSlideIndex = index;
    showSlide(currentSlideIndex);
    startAutoPlay();
}

function startAutoPlay() {
    stopAutoPlay();
    autoPlayInterval = setInterval(() => {
        currentSlideIndex++;
        const slides = document.querySelectorAll('.carousel-slide img');
        if (currentSlideIndex >= slides.length) currentSlideIndex = 0;
        showSlide(currentSlideIndex);
    }, autoPlayDelay);
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
        autoPlayInterval = null;
    }
}

function handleSwipe() {
    const touchStartX = window.touchStartX || 0;
    const touchEndX = window.touchEndX || 0;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > 50) {
        if (diff > 0) {
            changeSlide(1);
        } else {
            changeSlide(-1);
        }
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCarousel);
} else {
    initCarousel();
}

window.plusSlides = changeSlide;
window.currentSlide = goToSlide;