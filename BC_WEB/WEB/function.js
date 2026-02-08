// function.js (bản thay thế, chứa slider, menu scroll, popup tìm kiếm, debug logs)
console.log("✅ function.js loaded");

document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ DOMContentLoaded - init scripts");

  /* ---------------- SLIDER CHÍNH ---------------- */
  try {
    const slidesEl = document.querySelector('.slides');
    const images = slidesEl ? slidesEl.querySelectorAll('img') : [];
    const totalSlides = images.length || 1;
    let currentIndex = 0;
    let slideWidth = slidesEl ? slidesEl.clientWidth : 0;

    function updateSlide() {
      if (!slidesEl) return;
      const stepPercent = 100 / totalSlides;
      slidesEl.style.transform = `translateX(-${currentIndex * stepPercent}%)`;
    }

    // next / prev buttons (gắn nếu tồn tại)
    const btnNext = document.querySelector('.next');
    const btnPrev = document.querySelector('.prev');
    if (btnNext) {
      btnNext.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlide();
        resetAutoSlide();
      });
    }
    if (btnPrev) {
      btnPrev.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        updateSlide();
        resetAutoSlide();
      });
    }

    // auto slide (chỉ khi có >1 ảnh)
    let autoSlide = null;
    if (totalSlides > 1) {
      autoSlide = setInterval(() => {
        currentIndex = (currentIndex + 1) % totalSlides;
        updateSlide();
      }, 3000);
    }
    function resetAutoSlide() {
      if (autoSlide) {
        clearInterval(autoSlide);
        autoSlide = setInterval(() => {
          currentIndex = (currentIndex + 1) % totalSlides;
          updateSlide();
        }, 3000);
      }
    }

    window.addEventListener('resize', () => {
      if (slidesEl) slideWidth = slidesEl.clientWidth;
      updateSlide();
    });
    console.log("✅ Slider initialized", { totalSlides });
  } catch (err) {
    console.error("Slider init error:", err);
  }

  /* ------------- TREND / HORIZ SCROLLER ------------- */
  try {
    const eventList = document.querySelector('.event-list');
    const prevBtn = document.querySelector('.trend-btn.prev');
    const nextBtn = document.querySelector('.trend-btn.next');

    if (eventList && prevBtn && nextBtn) {
      let scrollAmount = 0;
      function getCardWidth() {
        const card = eventList.querySelector('.event-card');
        if (!card) return 250;
        return card.offsetWidth + 40;
      }
      function getStep() {
        return getCardWidth() * 4;
      }
      nextBtn.addEventListener('click', () => {
        scrollAmount += getStep();
        const maxScroll = eventList.scrollWidth - eventList.clientWidth;
        if (scrollAmount > maxScroll) scrollAmount = maxScroll;
        eventList.style.transform = `translateX(-${scrollAmount}px)`;
      });
      prevBtn.addEventListener('click', () => {
        scrollAmount -= getStep();
        if (scrollAmount < 0) scrollAmount = 0;
        eventList.style.transform = `translateX(-${scrollAmount}px)`;
      });
      window.addEventListener('resize', () => {
        scrollAmount = Math.floor(scrollAmount / getCardWidth()) * getCardWidth();
        eventList.style.transform = `translateX(-${scrollAmount}px)`;
      });
      console.log("✅ Trend scroller initialized");
    }
  } catch (err) {
    console.error("Trend scroller error:", err);
  }

  /* ------------- MENU SCROLL TO SECTION ------------- */
  try {
    const scrollToSection = (id) => {
      const section = document.querySelector(id);
      if (section) section.scrollIntoView({ behavior: "smooth" });
    };
    const elMusic = document.querySelector(".music");
    const elSport = document.querySelector(".sport");
    const elCourse = document.querySelector(".course");
    if (elMusic) elMusic.addEventListener("click", () => scrollToSection("#music-section"));
    if (elSport) elSport.addEventListener("click", () => scrollToSection("#sport-section"));
    if (elCourse) elCourse.addEventListener("click", () => scrollToSection("#art-section"));
    console.log("✅ Menu anchors initialized");
  } catch (err) {
    console.error("Menu anchors error:", err);
  }

  /* ------------- POPUP TÌM KIẾM + AJAX ------------- */
  try {
    const searchBtn = document.getElementById("search-button");
    const searchInput = document.getElementById("search-text");

    // tạo overlay popup (nếu đã có thì dùng lại)
    let overlay = document.getElementById('search-popup');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'search-popup';
      overlay.className = 'popup-overlay';
      overlay.innerHTML = `
        <div class="popup-box" role="dialog" aria-modal="true" aria-label="Kết quả tìm kiếm">
          <div class="popup-header">
            <h3>Kết quả tìm kiếm</h3>
            <button id="close-popup" class="close-btn" aria-label="Đóng">✖</button>
          </div>
          <div id="popup-results" class="popup-grid"><p class="muted">Chưa có kết quả</p></div>
        </div>
      `;
      document.body.appendChild(overlay);
    }

    const popupResults = overlay.querySelector('#popup-results');
    const closePopupBtn = overlay.querySelector('#close-popup');

    function openPopup(html) {
      if (popupResults) popupResults.innerHTML = html || '<p>Không có kết quả.</p>';
      overlay.style.display = 'flex';
    }
    function closePopup() {
      overlay.style.display = 'none';
    }

    // đóng khi bấm nút
    if (closePopupBtn) closePopupBtn.addEventListener('click', closePopup);
    // đóng khi click ngoài box
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closePopup();
    });

    // hỗ trợ Enter
    if (searchInput) {
      searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          searchBtn && searchBtn.click();
        }
      });
    }

    // attach click
    if (!searchBtn) {
      console.warn("⚠️ Không tìm thấy button #search-button");
    } else {
      searchBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        const keyword = (searchInput && searchInput.value || '').trim();
        if (!keyword) {
          alert('⚠️ Vui lòng nhập từ khóa!');
          return;
        }
        console.log("🔎 Searching:", keyword);

        // show loading
        openPopup('<p>🔎 Đang tìm kiếm...</p>');

        try {
          const url = `php/timkiem.php?keyword=${encodeURIComponent(keyword)}&t=${Date.now()}`;
          const res = await fetch(url, { cache: 'no-store' });
          if (!res.ok) {
            console.error("Fetch failed:", res.status, res.statusText);
            popupResults.innerHTML = `<p>⚠️ Lỗi server: ${res.status}</p>`;
            return;
          }
          const html = await res.text();
          console.log("🔁 Search response length:", html.length);
          // nếu server trả chuỗi rỗng -> thông báo
          if (!html || html.trim().length === 0) {
            popupResults.innerHTML = `<p>Không có kết quả phù hợp.</p>`;
          } else {
            // small safety: convert relative ../img/... -> absolute if needed
            const fixedHtml = html.replace(/src="\.\.\/img\//g, 'src="img/');
            popupResults.innerHTML = fixedHtml;
          }
        } catch (err) {
          console.error("Fetch error:", err);
          popupResults.innerHTML = `<p>⚠️ Lỗi khi tải kết quả.</p>`;
        }
      });
    }

    console.log("✅ Popup search initialized");
  } catch (err) {
    console.error("Search popup init error:", err);
  }
});
// chuyển silde các loại sự kiện
document.querySelectorAll('.slider-container').forEach(section => {
    const track = section.querySelector('.slider-track');
    const events = track.querySelectorAll('.event');
    const prev = section.querySelector('.prev');
    const next = section.querySelector('.next');

    let index = 0;
    const maxIndex = events.length - 4; // vì hiển thị 4 event

    function updateSlider() {
        track.style.transform = `translateX(-${index * (events[0].offsetWidth + 20)}px)`;
    }

    next.addEventListener('click', () => {
        if (index < maxIndex) {
            index++;
            updateSlider();
        }
    });

    prev.addEventListener('click', () => {
        if (index > 0) {
            index--;
            updateSlider();
        }
    });

    // Vuốt tay cho mobile
    let startX = 0;
    track.addEventListener('touchstart', (e) => startX = e.touches[0].clientX);
    track.addEventListener('touchmove', (e) => {
        let moveX = e.touches[0].clientX;
        if (moveX < startX - 50 && index < maxIndex) { // vuốt trái
            index++;
            updateSlider();
            startX = moveX;
        } else if (moveX > startX + 50 && index > 0) { // vuốt phải
            index--;
            updateSlider();
            startX = moveX;
        }
    });
});