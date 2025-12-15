<?php
$file = 'e:/laragon/www/de/duan1/public/css/style.css';
$content = file_get_contents($file);

// CSS to add after .bg-dark-light
$gridCSS = '

  /* Grid System */
  .grid {
      display: grid;
      gap: 30px;
  }

  .grid-cols-2 {
      grid-template-columns: repeat(2, 1fr);
  }

  .grid-cols-3 {
      grid-template-columns: repeat(3, 1fr);
  }

  .grid-cols-4 {
      grid-template-columns: repeat(4, 1fr);
  }

  /* Section Title */
  .section-title {
      font-size: 42px;
      text-align: center;
      margin-bottom: 50px;
      color: var(--light-color);
  }

  .section-title-left {
      font-size: 36px;
      margin-bottom: 40px;
      color: var(--light-color);
  }

  /* Category Section */
  .category-section {
      padding: 80px 0;
  }

  .category-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
  }

  .category-card {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      background-color: var(--dark-light);
      transition: var(--transition);
      cursor: pointer;
  }

  .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  }

  .category-card-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      transition: var(--transition);
  }

  .category-card:hover .category-card-image {
      transform: scale(1.05);
  }

  .category-card-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.8));
  }

  .category-card-content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 20px;
      text-align: center;
  }

  .category-card-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--light-color);
      transition: var(--transition);
  }

  .category-card-count {
      font-size: 14px;
      color: var(--gray-color);
      margin-top: 5px;
  }

';

// Find the .bg-dark-light closing brace and insert after it
$pattern = '/\.bg-dark-light\s*\{\s*background-color:\s*var\(--dark-light\);\s*\}/';
if (preg_match($pattern, $content, $matches)) {
    $content = preg_replace($pattern, $matches[0] . $gridCSS, $content, 1);
    file_put_contents($file, $content);
    echo "Done! Added grid and category CSS.";
} else {
    echo "Pattern not found!";
}
