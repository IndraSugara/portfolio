document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menuToggle");
  const navMenu = document.querySelector(".nav-menu");
  const menuIcon = document.querySelector(".menu-icon");

  // Simple and reliable mobile menu
  if (menuToggle && navMenu && menuIcon) {
    let isMenuOpen = false;

    // Single function to handle menu toggle
    function toggleMenu() {
      isMenuOpen = !isMenuOpen;
      menuToggle.checked = isMenuOpen;
      
      if (isMenuOpen) {
        navMenu.classList.add("show");
        document.body.style.overflow = "hidden";
      } else {
        navMenu.classList.remove("show");
        document.body.style.overflow = "";
      }
    }

    // Main click handler for menu icon
    menuIcon.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });

    // Add passive touch handler for better performance
    menuIcon.addEventListener("touchstart", function (e) {
      // Don't prevent default for passive listener
    }, { passive: true });

    // Handle checkbox change (fallback)
    menuToggle.addEventListener("change", function () {
      isMenuOpen = this.checked;
      if (isMenuOpen) {
        navMenu.classList.add("show");
        document.body.style.overflow = "hidden";
      } else {
        navMenu.classList.remove("show");
        document.body.style.overflow = "";
      }
    });

    // Close menu when clicking nav links
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        isMenuOpen = false;
        menuToggle.checked = false;
        navMenu.classList.remove("show");
        document.body.style.overflow = "";
      });
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (e) {
      if (isMenuOpen && !navMenu.contains(e.target) && !menuIcon.contains(e.target)) {
        isMenuOpen = false;
        menuToggle.checked = false;
        navMenu.classList.remove("show");
        document.body.style.overflow = "";
      }
    });
  }

  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        const headerOffset =
          document.querySelector(".header")?.offsetHeight || 0;
        const elementPos = target.offsetTop - headerOffset - 20;
        window.scrollTo({ top: elementPos, behavior: "smooth" });
      }
    });
  });

  // Contact form
  const contactForm = document.querySelector(".contact-form-new form");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;

      submitBtn.textContent = "Mengirim...";
      submitBtn.disabled = true;

      fetch("contact.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.text())
        .then(() => {
          showNotification("Pesan berhasil dikirim!", "success");
          this.reset();
        })
        .catch(() => {
          showNotification("Gagal mengirim pesan. Silakan coba lagi.", "error");
        })
        .finally(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        });
    });
  }

  function showNotification(message, type = "success") {
    const notif = document.createElement("div");
    notif.className = `notification ${type}`;
    notif.textContent = message;
    document.body.appendChild(notif);
    setTimeout(() => notif.classList.add("show"), 100);
    setTimeout(() => {
      notif.classList.remove("show");
      setTimeout(() => notif.remove(), 300);
    }, 3000);
  }

  // Search and filter
  const searchInput = document.querySelector(".search-box input");
  const filterTags = document.querySelectorAll(".tag-filter");
  const articleCards = document.querySelectorAll(".article-card");

  function filterArticles() {
    const term = searchInput?.value.toLowerCase() || "";
    const activeTag =
      document.querySelector(".tag-filter.active")?.textContent.trim() || "All";

    articleCards.forEach((card) => {
      const title = card.querySelector("h3").textContent.toLowerCase();
      const excerpt = card
        .querySelector(".article-excerpt")
        .textContent.toLowerCase();
      const category = card.querySelector(".article-tag").textContent.trim();
      const match =
        (title.includes(term) || excerpt.includes(term)) &&
        (activeTag === "All" || category === activeTag);
      card.style.display = match ? "block" : "none";
    });
  }

  searchInput?.addEventListener("input", filterArticles);
  filterTags.forEach((tag) => {
    tag.addEventListener("click", () => {
      filterTags.forEach((t) => t.classList.remove("active"));
      tag.classList.add("active");
      filterArticles();
    });
  });

  // Skill bars animation
  const skillBars = document.querySelectorAll(".skill-progress");
  const skillObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const bar = entry.target;
          const percent = bar.getAttribute("data-percentage") || "0%";
          bar.style.width = "0%";
          setTimeout(() => {
            bar.style.transition = "width 1.5s ease-in-out";
            bar.style.width = percent.includes("%") ? percent : `${percent}%`;
          }, 200);
          skillObserver.unobserve(bar);
        }
      });
    },
    { threshold: 0.5 }
  );

  skillBars.forEach((bar) => skillObserver.observe(bar));

  // Header scroll effect
  const header = document.querySelector(".header");
  let lastScrollTop = 0;
  window.addEventListener("scroll", () => {
    const top = window.pageYOffset || document.documentElement.scrollTop;
    header?.classList.toggle("scrolled", top > 100);
    header.style.transform =
      top > lastScrollTop && top > 200 ? "translateY(-100%)" : "translateY(0)";
    lastScrollTop = Math.max(0, top);
  }, { passive: true });

  // Highlight active nav
  const sections = document.querySelectorAll("section[id]");
  const navLinks = document.querySelectorAll(".nav-link");
  window.addEventListener("scroll", () => {
    const pos = window.scrollY + 100;
    sections.forEach((sec) => {
      const top = sec.offsetTop;
      const bottom = top + sec.offsetHeight;
      const id = sec.getAttribute("id");
      if (pos >= top && pos < bottom) {
        navLinks.forEach((link) => {
          link.classList.toggle(
            "active",
            link.getAttribute("href") === `#${id}`
          );
        });
      }
    });
  }, { passive: true });

  // Animate cards
  const animatedCards = document.querySelectorAll(
    ".article-card, .service-card, .education-card"
  );
  const fadeObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          el.style.opacity = "0";
          el.style.transform = "translateY(30px)";
          el.style.transition = "all 0.6s ease";
          setTimeout(() => {
            el.style.opacity = "1";
            el.style.transform = "translateY(0)";
          }, 100);
          fadeObserver.unobserve(el);
        }
      });
    },
    { threshold: 0.3 }
  );
  animatedCards.forEach((card) => fadeObserver.observe(card));

  // Admin form button feedback
  document.querySelectorAll(".admin-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        const originalText = btn.textContent;
        btn.textContent = "Menyimpan...";
        btn.disabled = true;
        setTimeout(() => {
          btn.textContent = originalText;
          btn.disabled = false;
        }, 2000);
      }
    });
  });

  // Stats counter
  function animateCounters() {
    const counters = document.querySelectorAll(".stat-number");
    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const counter = entry.target;
            const target =
              parseInt(counter.textContent.replace(/\D/g, "")) || 0;
            const suffix = counter.textContent.replace(/\d/g, "");
            let count = 0;
            const step = target / 50;
            const interval = setInterval(() => {
              count += step;
              if (count >= target) {
                counter.textContent = target + suffix;
                clearInterval(interval);
              } else {
                counter.textContent = Math.floor(count) + suffix;
              }
            }, 40);
            counterObserver.unobserve(counter);
          }
        });
      },
      { threshold: 0.5 }
    );
    counters.forEach((c) => counterObserver.observe(c));
  }
  animateCounters();

  // Delete button confirm + submit
  document.querySelectorAll("form button[type='submit']").forEach((button) => {
    if (button.textContent.includes("Hapus")) {
      button.addEventListener("click", function (e) {
        if (!confirm("Yakin ingin menghapus data ini?")) {
          e.preventDefault();
        }
      });
    }
  });

  // Dark mode
  const darkToggle = document.querySelector(".dark-mode-toggle");
  const isDark = localStorage.getItem("darkMode") === "true";
  if (isDark) document.body.classList.add("dark-mode");
  darkToggle?.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    localStorage.setItem(
      "darkMode",
      document.body.classList.contains("dark-mode")
    );
  });

  // Mobile nav menu
  const mobileBtn = document.querySelector(".mobile-menu-btn");
  mobileBtn?.addEventListener("click", function () {
    navMenu?.classList.toggle("active");
    this.classList.toggle("active");
  });

  document.querySelectorAll(".nav-menu a").forEach((link) => {
    link.addEventListener("click", () => {
      navMenu?.classList.remove("active");
      mobileBtn?.classList.remove("active");
    });
  });

  // Portfolio item click functionality
  document.querySelectorAll(".portfolio-item").forEach((item) => {
    item.addEventListener("click", function(e) {
      // Don't trigger if clicking on action buttons
      if (e.target.closest('.portfolio-btn')) {
        return;
      }
      
      const projectUrl = this.getAttribute("data-project-url");
      const githubUrl = this.getAttribute("data-github-url");
      
      // Priority: project URL first, then GitHub URL
      if (projectUrl && projectUrl !== '#') {
        window.open(projectUrl, '_blank');
      } else if (githubUrl && githubUrl !== '#') {
        window.open(githubUrl, '_blank');
      } else {
        // Show notification if no links available
        showNotification("Link project belum tersedia", "info");
      }
    });
    
    // Add hover sound effect (optional)
    item.addEventListener("mouseenter", function() {
      // Add subtle animation class
      this.classList.add("portfolio-hover");
    });
    
    item.addEventListener("mouseleave", function() {
      this.classList.remove("portfolio-hover");
    });
  });

  // Enhanced notification function with more types
  function showNotification(message, type = "success") {
    const notif = document.createElement("div");
    notif.className = `notification ${type}`;
    notif.textContent = message;
    
    // Add icon based on type
    let icon = "";
    switch(type) {
      case "success": icon = "✓ "; break;
      case "error": icon = "✗ "; break;
      case "info": icon = "ℹ "; break;
      default: icon = "";
    }
    notif.textContent = icon + message;
    
    document.body.appendChild(notif);
    setTimeout(() => notif.classList.add("show"), 100);
    setTimeout(() => {
      notif.classList.remove("show");
      setTimeout(() => notif.remove(), 300);
    }, 3000);
  }
});
