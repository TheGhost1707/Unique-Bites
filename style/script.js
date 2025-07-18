document.addEventListener("DOMContentLoaded", () => {
    initLoader();
    initAutoSlide();
    initMenuAnimation();
    initSloganRotator();
    initTestimonials();
});

/* ========== LOADER ========== */
function initLoader() {
    const loader = document.getElementById("loader");
    const loaderText = document.getElementById("loader-text");

    function hideLoader(delay = 1500) {
        setTimeout(() => {
            loader.classList.remove("active");
            loader.style.display = "none";
        }, delay);
    }

    function showPageLoading() {
        loaderText.textContent = "Mohon tunggu...";
        loader.classList.add("active");
    }

    // Koneksi lambat (opsional)
    if (navigator.connection) {
        navigator.connection.addEventListener('change', () => {
            if (navigator.connection.downlink < 0.5) {
                showPageLoading();
                hideLoader(2000);
            }
        });
    }

    hideLoader(2500); // Sembunyikan loader saat halaman siap
}

/* ========== AUTO SLIDE BANNER ========== */
function initAutoSlide() {
    const slidesContainer = document.querySelector('.slides');
    const dots = document.querySelectorAll('.dot');
    if (!slidesContainer || dots.length === 0) return;

    let currentIndex = 0;
    const totalSlides = dots.length;

    function updateDots(index) {
        dots.forEach(dot => dot.classList.remove('active'));
        dots[index].classList.add('active');
    }

    function autoSlide() {
        currentIndex = (currentIndex + 1) % totalSlides;
        slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateDots(currentIndex);
    }

    setInterval(autoSlide, 7000);
}

/* ========== ANIMASI MASUK MENU CARD DAN JUDUL ========== */
function initMenuAnimation() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                if (entry.target.classList.contains('underline-title')) {
                    entry.target.classList.add('in-view');
                }
            }
        });
    }, { threshold: 0.15 });

    document.querySelectorAll('.menu-card, .underline-title').forEach(el => {
        observer.observe(el);
    });
}

/* ========== ROTASI SLOGAN ========== */
function initSloganRotator() {
    const slogans = [
        "Rasa yang unik selalu ada",
        "Lezatnya nggak ketebak",
        "Sekali coba, pasti nagih",
        "Teman nongkrong terbaik",
        "Bikin hari kamu lebih manis dan berwarna"
    ];

    let index = 0;
    const textElement = document.getElementById("slogan-text");
    if (!textElement) return;

    setInterval(() => {
        textElement.style.opacity = 0;
        setTimeout(() => {
            index = (index + 1) % slogans.length;
            textElement.textContent = slogans[index];
            textElement.style.opacity = 1;
        }, 500);
    }, 3000);
}

/* ========== TESTIMONIAL SLIDER ========== */
function initTestimonials() {
    const forbiddenWords = [
        "anjing", "bangsat", "kontol", "memek", "tolol",
        "babi", "fuck", "shit", "ngentot", "pantek"
    ];
    const forbiddenRegex = new RegExp(`\\b(${forbiddenWords.join("|")})\\b`, "gi");

    const testimonials = [
        {
            nama: "Intan",
            komentar: "Snack-nya enak banget, apalagi yang corndog cheese mozarella + sosis! Wajib coba!",
            rating: 5,
            initials: "I",
            color: "#FFC4C4"
        },
        {
            nama: "Bagus",
            komentar: "Minumannya creamy tapi ringan, cocok banget buat siang hari.",
            rating: 4,
            initials: "B",
            color: "#B8E0D2"
        },
        {
            nama: "Dian Sartika",
            komentar: "Kopinya enak.. cocok nih buat sambil nugas",
            rating: 5,
            initials: "D",
            color: "#DCD6F7"
        }
    ];

    let currentSlide = 0;

    function filterKataKasar(text) {
        return text.replace(forbiddenRegex, match => "*".repeat(match.length));
    }

    function renderTestimonials() {
        const slider = document.getElementById("testimonial-slider");
        if (!slider) return;

        slider.innerHTML = "";
        if (testimonials.length === 0) {
            slider.innerHTML = "<p>Belum ada komentar.</p>";
            return;
        }

        testimonials.forEach((item, index) => {
            const div = document.createElement("div");
            div.classList.add("testimonial");
            if (index === currentSlide) div.classList.add("active");

            div.innerHTML = `
                <div class="avatar-custom" style="background-color: ${item.color}">${item.initials}</div>
                <p>“${filterKataKasar(item.komentar)}”</p>
                <div class="rating-stars">${"⭐️".repeat(item.rating)}</div>
                <h5>${item.nama}</h5>
            `;
            slider.appendChild(div);
        });
    }

    function nextSlide() {
        if (testimonials.length === 0) return;
        currentSlide = (currentSlide + 1) % testimonials.length;
        updateSlideVisibility();
    }

    function updateSlideVisibility() {
        const slides = document.querySelectorAll(".testimonial");
        slides.forEach((slide, index) => {
            slide.classList.toggle("active", index === currentSlide);
        });
    }

    renderTestimonials();
    setInterval(nextSlide, 6000);
}
