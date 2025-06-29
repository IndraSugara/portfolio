<?php include 'header.php'; ?>

<!-- Articles Section -->
<section class="section articles">
    <div class="container">
        <div class="section-title">
            <h2>Artikel</h2>
            <p>Berbagi pengetahuan dan pengalaman dalam dunia teknologi</p>
        </div>

        <!-- Search and Filter -->
        <div class="search-container">
            <div class="search-box">
                <i class="bx bx-search"></i>
                <input type="text" id="searchInput" placeholder="Cari artikel...">
            </div>
            <div class="filter-tags">
                <button class="tag-filter active" data-tag="all">Semua</button>
                <button class="tag-filter" data-tag="programming">Programming</button>
                <button class="tag-filter" data-tag="design">Design</button>
                <button class="tag-filter" data-tag="technology">Technology</button>
            </div>
        </div>

        <!-- Articles Grid -->
        <div class="articles-grid">
            <?php
            // Include database connection
            require_once 'database/init_db.php';
            
            try {
                $stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
                while ($article = $stmt->fetch()) {
                    ?>
                    <article class="article-card">
                        <div class="article-image">
                            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-date">
                                    <i class="bx bx-calendar"></i>
                                    <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                                </span>
                                <span class="article-tag"><?php echo htmlspecialchars($article['category']); ?></span>
                            </div>
                            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                            <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-outline">Baca Selengkapnya</a>
                        </div>
                    </article>
                    <?php
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="pagination-btn" id="prevPage" disabled>
                <i class="bx bx-chevron-left"></i> Sebelumnya
            </button>
            <span class="page-info">Halaman <span id="currentPage">1</span> dari <span id="totalPages">1</span></span>
            <button class="pagination-btn" id="nextPage">
                Selanjutnya <i class="bx bx-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Notification -->
<div class="notification" id="notification"></div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.tag-filter');
    const articlesGrid = document.querySelector('.articles-grid');
    
    // Search functionality
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const articles = document.querySelectorAll('.article-card');
        
        articles.forEach(article => {
            const title = article.querySelector('h3').textContent.toLowerCase();
            const excerpt = article.querySelector('.article-excerpt').textContent.toLowerCase();
            
            if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                article.style.display = 'block';
            } else {
                article.style.display = 'none';
            }
        });
    });
    
    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tag = this.dataset.tag;
            
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter articles
            const articles = document.querySelectorAll('.article-card');
            articles.forEach(article => {
                const articleTag = article.querySelector('.article-tag').textContent.toLowerCase();
                if (tag === 'all' || articleTag === tag) {
                    article.style.display = 'block';
                } else {
                    article.style.display = 'none';
                }
            });
        });
    });
});
</script> 