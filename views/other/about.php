<?php
session_start();
$active_page = 'about';
$page_title = 'Về Chúng Tôi - SAPHIRA | Thương Hiệu Nước Hoa Cao Cấp';
$page_description = 'SAPHIRA - Thương hiệu nước hoa cao cấp hàng đầu Việt Nam. Khám phá câu chuyện thương hiệu, sứ mệnh và cam kết mang đến những mùi hương tinh tế nhất.';
include __DIR__ . '/../../config/helpers.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="SAPHIRA, nước hoa cao cấp, thương hiệu nước hoa, nước hoa chính hãng, about saphira">
    <meta name="author" content="SAPHIRA">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://saphira.com/about">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:image" content="<?php echo asset('img/saphira-og-image.jpg'); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?php echo $page_title; ?>">
    <meta property="twitter:description" content="<?php echo $page_description; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://saphira.com/about">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('img/favicon.ico'); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo asset('css/style-men.css'); ?>">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "SAPHIRA",
        "description": "Thương hiệu nước hoa cao cấp hàng đầu Việt Nam",
        "url": "https://saphira.com",
        "logo": "https://saphira.com/public/img/logo.png",
        "foundingDate": "2024",
        "founder": {
            "@type": "Person",
            "name": "SAPHIRA Team"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "123 Luxury Avenue",
            "addressLocality": "Ho Chi Minh City",
            "addressCountry": "VN"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+84-123-456-789",
            "contactType": "customer service",
            "availableLanguage": ["Vietnamese", "English"]
        },
        "sameAs": [
            "https://facebook.com/saphira",
            "https://instagram.com/saphira"
        ]
    }
    </script>

    <style>
        /* About Page Specific Styles */
        .about-hero {
            position: relative;
            height: 60vh;
            min-height: 400px;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('<?php echo asset("img/about-hero.jpg"); ?>') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .about-hero-content {
            max-width: 800px;
            padding: 0 20px;
            animation: fadeInUp 1s ease;
        }

        .about-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 600;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #D4AF37, #F4E4BC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .about-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            line-height: 1.8;
        }

        .about-section {
            padding: 80px 20px;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .section-grid.reverse {
            direction: rtl;
        }

        .section-grid.reverse>* {
            direction: ltr;
        }

        .section-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .section-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .section-image:hover img {
            transform: scale(1.05);
        }

        .section-content h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            margin-bottom: 1.5rem;
        }

        .section-content p {
            color: #ccc;
            line-height: 1.9;
            margin-bottom: 1rem;
            font-size: 1.05rem;
        }

        .about-bg-dark {
            background: linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%);
        }

        .about-bg-light {
            background: #111;
        }

        /* Values Section */
        .values-section {
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(135deg, #0d0d0d 0%, #1a1510 100%);
        }

        .values-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            margin-bottom: 3rem;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .value-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 20px;
            padding: 40px 30px;
            transition: all 0.4s ease;
        }

        .value-card:hover {
            transform: translateY(-10px);
            border-color: #D4AF37;
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.15);
        }

        .value-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #D4AF37, #B8860B);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .value-icon .material-symbols-outlined {
            font-size: 32px;
            color: #0a0a0a;
        }

        .value-card h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 1rem;
        }

        .value-card p {
            color: #999;
            line-height: 1.7;
        }

        /* Team Section */
        .team-section {
            padding: 100px 20px;
            background: #0a0a0a;
            text-align: center;
        }

        .team-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #D4AF37;
            margin-bottom: 1rem;
        }

        .team-section>p {
            color: #888;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .team-member {
            text-align: center;
        }

        .team-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #D4AF37;
            margin-bottom: 1rem;
        }

        .team-member h4 {
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }

        .team-member span {
            color: #D4AF37;
            font-size: 0.9rem;
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #1a1510, #0d0d0d);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .stat-item h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            color: #D4AF37;
            margin-bottom: 0.5rem;
        }

        .stat-item p {
            color: #999;
            font-size: 0.95rem;
        }

        /* CTA Section */
        .cta-section {
            padding: 100px 20px;
            text-align: center;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(0, 0, 0, 0.9)), url('<?php echo asset("img/cta-bg.jpg"); ?>') center/cover no-repeat;
        }

        .cta-section h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: #ccc;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .cta-btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #D4AF37, #B8860B);
            color: #0a0a0a;
            text-decoration: none;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {

            .section-grid,
            .section-grid.reverse {
                grid-template-columns: 1fr;
                direction: ltr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .about-hero {
                height: 50vh;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="about-hero">
            <div class="about-hero-content">
                <h1>Câu Chuyện SAPHIRA</h1>
                <p>Nơi nghệ thuật nước hoa gặp gỡ đam mê - Kiến tạo những mùi hương độc đáo cho tâm hồn Việt.</p>
            </div>
        </section>

        <!-- Story Section -->
        <section class="about-section about-bg-dark">
            <div class="about-container">
                <div class="section-grid">
                    <div class="section-image">
                        <img src="<?php echo asset('img/about-story.jpg'); ?>"
                            alt="Câu chuyện SAPHIRA - Thương hiệu nước hoa cao cấp" loading="lazy">
                    </div>
                    <div class="section-content">
                        <h2>Khởi Nguồn Từ Đam Mê</h2>
                        <p>SAPHIRA được thành lập năm 2024 bởi những người đam mê nước hoa với mong muốn mang đến cho
                            người Việt những trải nghiệm hương thơm cao cấp nhất.</p>
                        <p>Chúng tôi tin rằng mỗi mùi hương là một câu chuyện, một ký ức, một cảm xúc. Và sứ mệnh của
                            SAPHIRA là giúp bạn tìm thấy mùi hương phản ánh đúng con người bạn.</p>
                        <p>Từ những ngày đầu tiên, chúng tôi đã cam kết chỉ mang đến những sản phẩm chính hãng 100% với
                            chất lượng vượt trội và dịch vụ tận tâm.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="about-section about-bg-light">
            <div class="about-container">
                <div class="section-grid reverse">
                    <div class="section-image">
                        <img src="<?php echo asset('img/about-mission.jpg'); ?>"
                            alt="Sứ mệnh SAPHIRA - Nước hoa chính hãng" loading="lazy">
                    </div>
                    <div class="section-content">
                        <h2>Sứ Mệnh Của Chúng Tôi</h2>
                        <p><strong>Tầm nhìn:</strong> Trở thành điểm đến hàng đầu cho những người yêu thích nước hoa tại
                            Việt Nam.</p>
                        <p><strong>Sứ mệnh:</strong> Mang đến trải nghiệm mua sắm nước hoa đẳng cấp với sản phẩm chính
                            hãng, giá cả minh bạch và dịch vụ chuyên nghiệp.</p>
                        <p><strong>Giá trị cốt lõi:</strong> Chất lượng - Uy tín - Tận tâm - Sáng tạo</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="values-section">
            <div class="about-container">
                <h2>Giá Trị Cốt Lõi</h2>
                <div class="values-grid">
                    <article class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">verified</span>
                        </div>
                        <h3>100% Chính Hãng</h3>
                        <p>Cam kết tất cả sản phẩm đều là hàng chính hãng, nhập khẩu trực tiếp từ các thương hiệu uy tín
                            trên thế giới.</p>
                    </article>
                    <article class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">spa</span>
                        </div>
                        <h3>Chất Lượng Cao Cấp</h3>
                        <p>Tuyển chọn kỹ lưỡng từng sản phẩm, đảm bảo mang đến những mùi hương tinh tế và độ bền tuyệt
                            vời.</p>
                    </article>
                    <article class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <h3>Tư Vấn Tận Tâm</h3>
                        <p>Đội ngũ chuyên gia giàu kinh nghiệm sẵn sàng tư vấn giúp bạn tìm được mùi hương phù hợp nhất.
                        </p>
                    </article>
                    <article class="value-card">
                        <div class="value-icon">
                            <span class="material-symbols-outlined">local_shipping</span>
                        </div>
                        <h3>Giao Hàng Toàn Quốc</h3>
                        <p>Dịch vụ giao hàng nhanh chóng, đóng gói cẩn thận, đảm bảo sản phẩm đến tay bạn an toàn.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>50+</h3>
                    <p>Thương hiệu cao cấp</p>
                </div>
                <div class="stat-item">
                    <h3>500+</h3>
                    <p>Sản phẩm đa dạng</p>
                </div>
                <div class="stat-item">
                    <h3>10K+</h3>
                    <p>Khách hàng hài lòng</p>
                </div>
                <div class="stat-item">
                    <h3>99%</h3>
                    <p>Đánh giá 5 sao</p>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team-section">
            <h2>Đội Ngũ Của Chúng Tôi</h2>
            <p>Những người đam mê nước hoa, tận tâm mang đến trải nghiệm tốt nhất cho bạn.</p>
            <div class="team-grid">
                <div class="team-member">
                    <img class="team-avatar" src="https://randomuser.me/api/portraits/men/32.jpg" alt="CEO SAPHIRA">
                    <h4>Nguyễn Văn A</h4>
                    <span>Founder & CEO</span>
                </div>
                <div class="team-member">
                    <img class="team-avatar" src="https://randomuser.me/api/portraits/women/44.jpg"
                        alt="Creative Director SAPHIRA">
                    <h4>Trần Thị B</h4>
                    <span>Creative Director</span>
                </div>
                <div class="team-member">
                    <img class="team-avatar" src="https://randomuser.me/api/portraits/men/67.jpg"
                        alt="Head of Sales SAPHIRA">
                    <h4>Lê Văn C</h4>
                    <span>Head of Sales</span>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <h2>Khám Phá Bộ Sưu Tập</h2>
            <p>Hãy để SAPHIRA đồng hành cùng bạn trong hành trình tìm kiếm mùi hương hoàn hảo.</p>
            <a href="main.php?r=category/men" class="cta-btn">Khám Phá Ngay</a>
        </section>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>