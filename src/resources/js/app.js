import './bootstrap';

// ============================================
// 1. SCROLL REVEAL ANIMATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.scroll-reveal');
    
    function reveal() {
        for (let i = 0; i < reveals.length; i++) {
            const windowHeight = window.innerHeight;
            const elementTop = reveals[i].getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add('revealed');
            }
        }
    }
    
    window.addEventListener('scroll', reveal);
    reveal(); // Trigger pertama kali
});

// ============================================
// 2. CONFETTI EFFECT (Lebih Keren)
// ============================================
function triggerConfetti() {
    const colors = ['#ec4899', '#f43f5e', '#fb7185', '#fda4af', '#fecdd3', '#fbbf24', '#34d399', '#60a5fa'];
    
    // Tambahkan efek suara "pop" (opsional - kalau ada audio)
    // const popSound = new Audio('/sounds/pop.mp3');
    // popSound.play().catch(e => console.log('Audio not supported'));
    
    for (let i = 0; i < 80; i++) { // Tambah jadi 80 confetti
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');
        
        // Random position
        confetti.style.left = Math.random() * 100 + '%';
        
        // Random size
        const size = Math.random() * 12 + 4;
        confetti.style.width = size + 'px';
        confetti.style.height = size + 'px';
        
        // Random color
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        
        // Random shape (persegi, lingkaran, segitiga)
        const shapes = ['square', 'circle', 'triangle'];
        const shape = shapes[Math.floor(Math.random() * shapes.length)];
        
        if (shape === 'circle') {
            confetti.style.borderRadius = '50%';
        } else if (shape === 'triangle') {
            confetti.style.backgroundColor = 'transparent';
            confetti.style.borderLeft = `${size/2}px solid transparent`;
            confetti.style.borderRight = `${size/2}px solid transparent`;
            confetti.style.borderBottom = `${size}px solid ${colors[Math.floor(Math.random() * colors.length)]}`;
            confetti.style.width = '0';
            confetti.style.height = '0';
        }
        
        confetti.style.position = 'fixed';
        confetti.style.bottom = '0';
        confetti.style.zIndex = '9999';
        confetti.style.pointerEvents = 'none';
        
        // Random animation duration
        const duration = Math.random() * 3 + 1.5;
        confetti.style.animationDuration = duration + 's';
        
        // Random delay
        confetti.style.animationDelay = Math.random() * 0.5 + 's';
        
        document.body.appendChild(confetti);
        
        // Hapus setelah animasi selesai
        setTimeout(() => {
            confetti.remove();
        }, duration * 1000);
    }
}

// ============================================
// 3. ATTACH CONFETTI KE TOMBOL
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.camera-shutter, .btn-fun, .btn-funny, .booking-btn');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Cek apakah tombol memiliki link
            if (btn.tagName === 'A' && btn.href && !btn.href.includes('#')) {
                e.preventDefault();
                triggerConfetti();
                
                // Delay redirect biar confetti keliatan
                setTimeout(() => {
                    window.location.href = btn.href;
                }, 500);
            } else {
                triggerConfetti();
            }
        });
    });
});

// ============================================
// 4. PARTICLE BACKGROUND (Opsional - Keren!)
// ============================================
function createParticleBackground() {
    const particleContainer = document.createElement('div');
    particleContainer.style.position = 'fixed';
    particleContainer.style.top = '0';
    particleContainer.style.left = '0';
    particleContainer.style.width = '100%';
    particleContainer.style.height = '100%';
    particleContainer.style.pointerEvents = 'none';
    particleContainer.style.zIndex = '-1';
    particleContainer.style.opacity = '0.3';
    document.body.appendChild(particleContainer);
    
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.style.position = 'absolute';
        particle.style.width = Math.random() * 5 + 2 + 'px';
        particle.style.height = particle.style.width;
        particle.style.backgroundColor = '#ec4899';
        particle.style.borderRadius = '50%';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.opacity = Math.random() * 0.5;
        particle.style.animation = `float ${Math.random() * 5 + 3}s ease-in-out infinite`;
        particle.style.animationDelay = Math.random() * 5 + 's';
        particleContainer.appendChild(particle);
    }
}

// Aktifkan particle background (opsional - komentar jika tidak mau)
// createParticleBackground();

// ============================================
// 5. TOOLTIP LUCU (Hover Effect)
// ============================================
function addFunnyTooltips() {
    const tooltips = {
        '.booking-btn': '📸 Klik untuk booking!',
        '.camera-shutter': '📷 Jepret!',
        '.btn-fun': '✨ Ada kejutan loh!',
        'a[href*="booking"]': '🎯 Booking sekarang yuk!',
        'a[href*="register"]': '🎁 Dapatkan bonus pendaftaran!',
    };
    
    for (const [selector, message] of Object.entries(tooltips)) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            el.setAttribute('title', message);
        });
    }
}

document.addEventListener('DOMContentLoaded', addFunnyTooltips);

// ============================================
// 6. PAGE TRANSITION (Smooth Loading)
// ============================================
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && link.href && link.href.includes(window.location.origin) && !link.target) {
        e.preventDefault();
        const targetUrl = link.href;
        
        // Tambah efek fade out
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.3s ease';
        
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 300);
    }
});

// Reset opacity saat page load
window.addEventListener('pageshow', function() {
    document.body.style.opacity = '1';
});

// ============================================
// 7. COUNTER ANIMATION (Untuk angka statistik)
// ============================================
function animateNumber(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerText = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Aktifkan counter untuk elemen dengan class .count-up
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.count-up');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-target'));
                animateNumber(el, 0, target, 2000);
                observer.unobserve(el);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
});

// ============================================
// 8. CURSOR CUSTOM LUCU (Opsional)
// ============================================
// function customCursor() {
//     const cursor = document.createElement('div');
//     cursor.style.position = 'fixed';
//     cursor.style.width = '30px';
//     cursor.style.height = '30px';
//     cursor.style.borderRadius = '50%';
//     cursor.style.backgroundColor = '#ec4899';
//     cursor.style.opacity = '0.5';
//     cursor.style.pointerEvents = 'none';
//     cursor.style.zIndex = '9999';
//     cursor.style.transition = 'transform 0.1s ease';
//     document.body.appendChild(cursor);
//     
//     document.addEventListener('mousemove', (e) => {
//         cursor.style.transform = `translate(${e.clientX - 15}px, ${e.clientY - 15}px)`;
//     });
//     
//     document.addEventListener('mouseenter', () => {
//         cursor.style.display = 'block';
//     });
// }

// Aktifkan custom cursor (komentar jika tidak mau)
// customCursor();

// ============================================
// 9. NOTIFICATION SOUND (Untuk Booking Success)
// ============================================
function playNotificationSound() {
    // Cek apakah ada di halaman success
    if (window.location.pathname.includes('/booking/success')) {
        // Buat suara "ding" dengan Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 880;
        gainNode.gain.value = 0.5;
        
        oscillator.start();
        gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 1);
        oscillator.stop(audioContext.currentTime + 1);
    }
}

document.addEventListener('DOMContentLoaded', playNotificationSound);

// ============================================
// 10. LOGO CLICK EASTER EGG
// ============================================
let clickCount = 0;
document.addEventListener('DOMContentLoaded', function() {
    const logo = document.querySelector('a[href="/"], .logo, [class*="logo"]');
    if (logo) {
        logo.addEventListener('click', (e) => {
            clickCount++;
            if (clickCount === 5) {
                alert('🎉 Wah, kamu nemu Easter Egg! 🎉\n\nKamu mendapatkan diskon 10%! \n\nGunakan kode: SELFI10');
                clickCount = 0;
            }
        });
    }
});

// ============================================
// EXPORT FUNCTIONS (Jika perlu dipanggil dari Livewire)
// ============================================
window.triggerConfetti = triggerConfetti;
window.playNotificationSound = playNotificationSound;