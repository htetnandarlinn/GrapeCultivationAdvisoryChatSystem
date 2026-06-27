<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Grape Cultivation Advisory Chat System</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css" />

</head>

<body>
<?php include __DIR__ . '/layouts/header.php'; ?>

  
  <section class="hero">
    <div class="hero-left">
      <div class="hero-badge"><span></span> Smart Advisory for Better Harvest</div>
      <h1>Grape Cultivation<br /><span class="green">Advisory Chat System</span></h1>
      <p>Connect with agricultural experts, ask questions, upload disease images, and get reliable advice to improve
        your
        grape cultivation.</p>
      <div class="hero-ctas">
        <button class="cta-dark" id="askExpertBtn">Ask an Expert</button>
        <button class="cta-light" id="browseArticlesBtn">Browse Articles</button>
      </div>
      <div class="hero-trust">
        <div class="avatar-stack">
          <span style="background:#81c784;"></span>
          <span style="background:#64b5f6;"></span>
          <span style="background:#ffb74d;"></span>
        </div>
        <p>Trusted by farmers. Guided by experts.</p>
      </div>
    </div>

    <div class="hero-right">
      <img src="<?= $baseUrl ?>/assets/images/grape-chat-preview.png" alt="Grape Advisory System" class="hero-full-image">
    </div>
  </section>
  <!-- FEATURES -->
  <section class="features">
    <p class="section-eyebrow">WHAT WE OFFER</p>
    <h2 class="section-title">Our Key Features</h2>
    <div class="features-grid">

      <div class="feature-card">
        <div class="icon-wrap ic-green">
          <svg width="20" height="20" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
        </div>
        <h4>Ask Questions</h4>
        <p>Farmers can ask questions about grape cultivation, diseases, and farm management.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-purple">
          <svg width="20" height="20" fill="#9c27b0" viewBox="0 0 24 24">
            <path
              d="M16 13l6.96 5.79c.32.27.04.79-.36.62l-8.55-3.6-.4 1.69c-.06.26-.42.29-.52.04l-2.16-5.43-7.6-3.21c-.31-.13-.27-.6.06-.68L20.84 4.6c.32-.08.6.21.51.53L16 13z" />
          </svg>
        </div>
        <h4>Upload Images</h4>
        <p>Upload images of grape plants or affected areas to help experts give accurate advice.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-orange">
          <svg width="20" height="20" fill="#f57c00" viewBox="0 0 24 24">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm1 16h-2v-2h2v2zm0-4h-2V7h2v6z" />
          </svg>
        </div>
        <h4>Disease Management</h4>
        <p>Get correct identification and management advice for grape diseases from experts.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-blue">
          <svg width="20" height="20" fill="#1565c0" viewBox="0 0 24 24">
            <path
              d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H7V9h10v2zm-3 4H7v-2h7v2zM17 7H7V5h10v2z" />
          </svg>
        </div>
        <h4>Cultivation Articles</h4>
        <p>Read helpful articles and guides on grape farming techniques.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-yellow">
          <svg width="20" height="20" fill="#f9a825" viewBox="0 0 24 24">
            <path
              d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
          </svg>
        </div>
        <h4>Notifications</h4>
        <p>Get notified when experts reply or new updates are available.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-blue">
          <svg width="20" height="20" fill="#1565c0" viewBox="0 0 24 24">
            <path
              d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
          </svg>
        </div>
        <h4>Profile Management</h4>
        <p>Update personal information and manage your account settings.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-shield">
          <svg width="20" height="20" fill="#2e7d32" viewBox="0 0 24 24">
            <path
              d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
          </svg>
        </div>
        <h4>Secure Access</h4>
        <p>Role-based access for farmers and experts ensures data security and privacy.</p>
      </div>

      <div class="feature-card">
        <div class="icon-wrap ic-pink">
          <svg width="20" height="20" fill="#c62828" viewBox="0 0 24 24">
            <path
              d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
          </svg>
        </div>
        <h4>Consultation History</h4>
        <p>Track your consultation history and review past conversations.</p>
      </div>

    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="how">
    <p class="section-eyebrow">HOW IT WORKS</p>
    <h2 class="section-title">Simple Steps to Get Advice</h2>
    <div class="steps">

      <div class="step">
        <div class="step-circle sc1">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path
              d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
          </svg>
          <div class="step-num">1</div>
        </div>
        <h4>Register</h4>
        <p>Create your account as a farmer.</p>
      </div>

      <div class="step-arrow">→</div>

      <div class="step">
        <div class="step-circle sc2">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
          <div class="step-num">2</div>
        </div>
        <h4>Ask a Question</h4>
        <p>Describe your problem and upload images of grape plants if needed.</p>
      </div>

      <div class="step-arrow">→</div>

      <div class="step">
        <div class="step-circle sc3">
          <svg width="28" height="28" fill="#f57c00" viewBox="0 0 24 24">
            <path
              d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 1c-1.46 0-2.78.35-3.83.91A4.99 4.99 0 0 0 4 18v1h16v-1a4.99 4.99 0 0 0-4.17-4.93A8.3 8.3 0 0 0 12 13z" />
          </svg>
          <div class="step-num" style="background:#f57c00;">3</div>
        </div>
        <h4>Expert Reviews</h4>
        <p>The expert reviews your question and image, and analyzes the issue.</p>
      </div>

      <div class="step-arrow">→</div>

      <div class="step">
        <div class="step-circle sc4">
          <svg width="28" height="28" fill="#7b1fa2" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
          <div class="step-num" style="background:#7b1fa2;">4</div>
        </div>
        <h4>Get Expert Advice</h4>
        <p>Receive practical advice and recommended solutions from experts.</p>
      </div>

      <div class="step-arrow">→</div>

      <div class="step">
        <div class="step-circle sc5">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M9 16.2l-3.5-3.5L4 14.2 9 19.2l11-11-1.4-1.4z" />
          </svg>
          <div class="step-num">5</div>
        </div>
        <h4>Improve Your Farm</h4>
        <p>Apply the advice and improve your grape cultivation success.</p>
      </div>

    </div>
  </section>

  <!-- CTA BANNER -->
  <div class="cta-banner">
    <div class="cta-banner-left">
      <div class="cta-banner-icon">🍇</div>
      <div>
        <h2>Need Help with Your Grapes?</h2>
        <p>Our experts are ready to help you with any grape cultivation challenge.</p>
      </div>
    </div>
    <a href="#" class="btn-cta-white" id="startConsultationBtn">
      <svg width="16" height="16" fill="none" stroke="#2e7d32" stroke-width="2" viewBox="0 0 24 24">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
      </svg>
      Start Consultation
    </a>
  </div>
<?php include __DIR__ . '/layouts/footer.php'; ?>

  <script>
    // Footer year
    document.getElementById("currentYear").textContent = new Date().getFullYear();

    // Nav active link
    document.querySelectorAll("#navLinks a").forEach(link => {
      link.addEventListener("click", e => {
        e.preventDefault();
        document.querySelector("#navLinks a.active")?.classList.remove("active");
        link.classList.add("active");
      });
    });

    // CTA buttons
    document.getElementById("loginBtn").addEventListener("click", () => alert("Login clicked"));
    document.getElementById("registerBtn").addEventListener("click", () => alert("Register clicked"));
    document.getElementById("askExpertBtn").addEventListener("click", () => alert("Ask an Expert clicked"));
    document.getElementById("browseArticlesBtn").addEventListener("click", () => alert("Browse Articles clicked"));
    document.getElementById("startConsultationBtn").addEventListener("click", e => {
      e.preventDefault();
      alert("Start Consultation clicked");
    });
  </script>

</body>

</html>