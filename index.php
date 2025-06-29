<?php

// Combining the original code with the provided changes, addressing the user's requests.
$page = 'index';
include 'header.php';
require_once 'database/init_db.php';

// Get profile data
try {
    $stmt = $pdo->query("SELECT * FROM profile WHERE id = 1");
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM skills ORDER BY category, name");
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC LIMIT 3");
    $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM education ORDER BY start_date DESC LIMIT 2");
    $education = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC LIMIT 3");
    $recent_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM activities ORDER BY created_at DESC");
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM portfolio ORDER BY created_at DESC");
    $portfolio_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Set default values if database error
    $profile = [
        'name' => 'Revolutionary Developer',
        'title' => 'Full Stack Developer & Digital Innovator',
        'bio' => 'Seorang developer passionate yang berfokus pada inovasi digital',
        'image_url' => 'img/profile.png',
        'email' => 'admin@revolutionary.dev',
        'phone' => '+62 812-3456-7890',
        'location' => 'Jakarta, Indonesia'
    ];
    $skills = [];
    $experiences = [];
    $education = [];
    $recent_articles = [];
    $activities = [];
    $portfolio_items = [];
}
?>

<!-- Hero Section -->
<section class="hero" id="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-intro">
                    <span class="hero-greeting">Halo! 👋</span>
                    <h1 class="hero-title">
                        Saya <span class="highlight"><?php echo htmlspecialchars($profile['name']); ?></span>
                    </h1>
                    <p class="hero-subtitle"><?php echo htmlspecialchars($profile['title']); ?></p>
                </div>
                <p class="hero-description"><?php echo htmlspecialchars($profile['bio']); ?></p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">1</span>
                        <span class="stat-label">Tahun Pengalaman</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">5+</span>
                        <span class="stat-label">Proyek Selesai</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Proyek Tidak Mangkrak</span>
                    </div>
                </div>
                <div class="hero-buttons">
                    <a href="contact.php" class="btn btn-primary">
                        <i class="bx bx-phone"></i>
                        Hubungi Saya
                    </a>
                    <a href="#experience" class="btn btn-outline">
                        <i class="bx bx-briefcase"></i>
                        Lihat Portfolio
                    </a>
                    <a href="<?php echo htmlspecialchars($profile['email']); ?>" class="btn btn-secondary">
                        <i class="bx bx-download"></i>
                        Download CV
                    </a>
                    <div class="hero-audio-player">
                        <div class="audio-player-container" id="audioPlayerContainer">
                            <div class="audio-player-header">Background Music</div>
                            <div class="custom-audio-player">
                                <div class="audio-controls">
                                    <button class="play-pause-btn" id="playPauseBtn">
                                        <i class="bx bx-play"></i>
                                    </button>
                                    <div class="audio-info">
                                        <div class="audio-title">Chill Vibes</div>
                                        <div class="audio-artist">Portfolio Theme</div>
                                    </div>
                                    <button class="volume-control" id="volumeBtn">
                                        <i class="bx bx-volume-full"></i>
                                    </button>
                                </div>
                                <div class="progress-container" id="progressContainer">
                                    <div class="progress-bar" id="progressBar"></div>
                                </div>
                                <div class="time-display">
                                    <span id="currentTime">0:00</span>
                                    <span id="duration">0:00</span>
                                </div>
                            </div>
                            <audio id="bg-music" preload="metadata">
                                <source src="assets/musics/bgmusic.mp3" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-container">
                    <img src="<?php echo htmlspecialchars($profile['image_url']); ?>" alt="<?php echo htmlspecialchars($profile['name']); ?>">
                    <div class="image-decoration"></div>
                </div>
                <div class="floating-elements">
                    <div class="floating-icon" style="--delay: 0s;">
                        <i class="bx bxl-javascript"></i>
                    </div>
                    <div class="floating-icon" style="--delay: 1s;">
                        <i class="bx bxl-react"></i>
                    </div>
                    <div class="floating-icon" style="--delay: 2s;">
                        <i class="bx bxl-php"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <div class="scroll-text">Scroll untuk explore</div>
            <div class="scroll-arrow">
                <i class="bx bx-chevron-down"></i>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section about" id="about">
    <div class="container">
        <div class="section-title">
            <h2>Tentang Saya</h2>
            <p>Mengenal lebih dekat profil dan keahlian saya</p>
        </div>
        <div class="about-content">
            <div class="profile-summary">
                <div class="profile-image">
                    <img src="<?php echo htmlspecialchars($profile['image_url']); ?>" alt="About <?php echo htmlspecialchars($profile['name']); ?>">
                </div>
                <div class="profile-details">
                    <h3>Profil Singkat</h3>
                    <p><?php echo htmlspecialchars($profile['bio']); ?></p>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="bx bx-envelope"></i>
                            <span><?php echo htmlspecialchars($profile['email']); ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="bx bx-phone"></i>
                            <span><?php echo htmlspecialchars($profile['phone']); ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="bx bx-location-plus"></i>
                            <span><?php echo htmlspecialchars($profile['location']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Skills Section -->
<section class="section skills" id="skills">
    <div class="container">
        <div class="section-title">
            <h2>Keahlian</h2>
            <p>Teknologi dan tools yang saya kuasai</p>
        </div>
        <?php if (!empty($skills)): ?>
        <div class="skills-container">
            <?php 
            $skill_categories = [];
            foreach ($skills as $skill) {
                $skill_categories[$skill['category']][] = $skill;
            }
            ?>
            <?php foreach ($skill_categories as $category => $category_skills): ?>
            <div class="skill-category">
                <h3><?php echo htmlspecialchars($category); ?></h3>
                <div class="skills-grid">
                    <?php foreach ($category_skills as $skill): ?>
                    <div class="skill-item">
                        <div class="skill-header">
                            <span class="skill-name"><?php echo htmlspecialchars($skill['name']); ?></span>
                            <span class="skill-percentage"><?php echo intval($skill['percentage']); ?>%</span>
                        </div>
                        <div class="skill-bar">
                            <div class="skill-progress" style="width: <?php echo intval($skill['percentage']); ?>%" data-percentage="<?php echo intval($skill['percentage']); ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="no-data">Data keahlian belum tersedia.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Experience Section -->
    <section id="experience" class="experience-section">
      <div class="container">
        <h2 class="section-title">Pengalaman Kerja</h2>
        <div class="experience-timeline">
          <?php
          try {
              $stmt = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC");
              $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

              if (empty($experiences)) {
                  echo '<p style="text-align: center; color: #666;">Belum ada pengalaman yang ditambahkan.</p>';
              } else {
                  foreach ($experiences as $experience) {
                      echo '<div class="experience-item">';
                      echo '<div class="experience-icon">';
                      echo '<i class="bx bx-briefcase"></i>';
                      echo '</div>';
                      echo '<div class="experience-content">';
                      echo '<h3>' . htmlspecialchars($experience['position']) . '</h3>';
                      echo '<h4>' . htmlspecialchars($experience['company']) . '</h4>';
                      echo '<span class="experience-date">' . htmlspecialchars($experience['period']) . '</span>';
                      echo '<p>' . htmlspecialchars($experience['description']) . '</p>';
                      echo '</div>';
                      echo '</div>';
                  }
              }
          } catch (PDOException $e) {
              echo '<p style="text-align: center; color: #666;">Error loading experiences.</p>';
          }
          ?>
        </div>
      </div>
    </section>

<!-- Education Section -->
<section class="section education" id="education">
    <div class="container">
        <div class="section-title">
            <h2>Pendidikan</h2>
            <p>Latar belakang pendidikan formal</p>
        </div>
        <?php if (!empty($education)): ?>
        <div class="education-grid">
            <?php foreach ($education as $edu): ?>
            <div class="education-card">
                <div class="education-header">
                    <h3><?php echo htmlspecialchars($edu['degree']); ?></h3>
                    <span class="education-period"><?php echo htmlspecialchars($edu['period']); ?></span>
                </div>
                <h4><?php echo htmlspecialchars($edu['institution']); ?></h4>
                <p><?php echo htmlspecialchars($edu['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="no-data">Data pendidikan belum tersedia.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Portfolio Section -->
    <section id="portfolio" class="portfolio">
      <div class="container">
        <h2 class="section-title">Portfolio</h2>
        <div class="portfolio-grid">
          <?php if (!empty($portfolio_items)): ?>
            <?php foreach ($portfolio_items as $portfolio_item): ?>
              <div class="portfolio-item" data-project-url="<?php echo htmlspecialchars($portfolio_item['project_url'] ?? '#'); ?>" data-github-url="<?php echo htmlspecialchars($portfolio_item['github_url'] ?? '#'); ?>">
                <img src="<?php echo htmlspecialchars($portfolio_item['image_url']); ?>" alt="<?php echo htmlspecialchars($portfolio_item['title']); ?>">
                <div class="portfolio-overlay">
                  <div class="portfolio-content">
                    <h3><?php echo htmlspecialchars($portfolio_item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($portfolio_item['description']); ?></p>
                    <?php if (!empty($portfolio_item['technologies'])): ?>
                      <div class="portfolio-tech">
                        <span><?php echo htmlspecialchars($portfolio_item['technologies']); ?></span>
                      </div>
                    <?php endif; ?>
                    <div class="portfolio-actions">
                      <?php if (!empty($portfolio_item['project_url'])): ?>
                        <a href="<?php echo htmlspecialchars($portfolio_item['project_url']); ?>" class="portfolio-btn" target="_blank" onclick="event.stopPropagation()">
                          <i class="bx bx-link-external"></i> Demo
                        </a>
                      <?php endif; ?>
                      <?php if (!empty($portfolio_item['github_url'])): ?>
                        <a href="<?php echo htmlspecialchars($portfolio_item['github_url']); ?>" class="portfolio-btn" target="_blank" onclick="event.stopPropagation()">
                          <i class="bx bxl-github"></i> Code
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-data">Data portfolio belum tersedia.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Activities Section -->
    <section id="activities" class="activities">
      <div class="container">
        <h2 class="section-title">Recent Activities</h2>
        <div class="activities-timeline">
          <?php
          try {
              $stmt = $pdo->query("SELECT * FROM activities ORDER BY created_at DESC LIMIT 6");
              $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

              if (empty($activities)) {
                  echo '<p style="text-align: center; color: #666;">Belum ada aktivitas yang ditambahkan.</p>';
              } else {
                  foreach ($activities as $activity) {
                      echo '<div class="activity-item">';
                      echo '<div class="activity-icon">';
                      echo '<i class="bx ' . htmlspecialchars($activity['icon']) . '"></i>';
                      echo '</div>';
                      echo '<div class="activity-content">';
                      echo '<h3>' . htmlspecialchars($activity['title']) . '</h3>';
                      echo '<p>' . htmlspecialchars($activity['description']) . '</p>';
                      echo '<span class="activity-date">' . date('F Y', strtotime($activity['created_at'])) . '</span>';
                      echo '</div>';
                      echo '</div>';
                  }
              }
          } catch (PDOException $e) {
              echo '<p style="text-align: center; color: #666;">Error loading activities.</p>';
          }
          ?>
        </div>
      </div>
    </section>

<?php include 'footer.php'; ?>
