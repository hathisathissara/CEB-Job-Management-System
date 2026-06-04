<?php include 'config/db_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDL Office Portal | Bandarawela Consumer Service Center</title>
    <meta name="description" content="EDL Bandarawela Consumer Service Center – Official Internal Job Management Portal for field operations, meter management and service requests.">
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


</head>
<body class="loading">

<!-- LOADER -->
<div id="loader-wrapper"></div>

<!-- ── FLOATING WHATSAPP BUTTON ── -->
<a id="whatsappFab" href="https://wa.me/94701207991?text=Hello,%20I%20need%20some%20assistance%20regarding%20the%20EDL%20System." target="_blank" rel="noopener" aria-label="WhatsApp Support">
    <span class="fab-tooltip">WhatsApp Support</span>
    <i class="fab fa-whatsapp"></i>
</a>

<!-- ── FLOATING SUPPORT BUTTON ── -->
<button id="supportFab" data-bs-toggle="modal" data-bs-target="#supportModal" aria-label="Report an issue">
    <span class="fab-tooltip">Report Issue</span>
    <i class="fas fa-headset"></i>
</button>

<!-- ── SUPPORT MODAL ── -->
<div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:490px;">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="modal-title d-flex align-items-center gap-2" id="supportModalLabel">
                    <span style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#c0392b,#e74c3c);display:flex;align-items:center;justify-content:center;font-size:.8rem;"><i class="fas fa-bug" style="color:#fff;"></i></span>
                    Report System Issue
                </div>
                <button class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">

                <!-- Alert messages -->
                <div id="supSuccess" class="sup-alert success mb-3 d-none">
                    <i class="fas fa-check-circle me-1"></i> Report submitted successfully. Thank you!
                </div>
                <div id="supError" class="sup-alert error mb-3 d-none">
                    <i class="fas fa-exclamation-circle me-1"></i> Oops! Could not submit. Please try again.
                </div>

                <form id="supportForm" action="https://formspree.io/f/xyzzzwkg" method="POST">

                    <div class="mb-3">
                        <label>Your Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="yourname@domain.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Issue Type</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <select name="type" class="form-select">
                                <option value="System Error / Bug">System Error / Bug</option>
                                <option value="UI Issue">Design / UI Issue</option>
                                <option value="Feature Request">Feature Request</option>
                                <option value="Other">Other Feedback</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label>Describe the Issue</label>
                        <textarea name="message" class="form-control" rows="4"
                            placeholder="What page? What happened? Please describe in detail…" required style="resize:none;"></textarea>
                    </div>

                    <input type="hidden" name="reporting_user" value="Public Portal">
                    <input type="hidden" name="system_time" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" name="source" value="Public Index">

                    <div class="d-grid">
                        <button type="submit" class="btn-support-submit" id="supSubmitBtn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Report
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- BACKGROUND -->
<div class="bg-base"></div>
<div class="bg-grid"></div>
<div class="orb orb-r"></div>
<div class="orb orb-g"></div>
<div class="orb orb-b"></div>



<!-- ══════════════════════════════════════════
     STICKY NAVBAR
══════════════════════════════════════════ -->
<nav class="navbar-edl" id="mainNav">
    <!-- Brand -->
    <a class="nav-brand" href="#hero">
        <div class="nav-bolt"><i class="fas fa-bolt"></i></div>
        <div class="nav-brand-text">
            <div class="t1">EDL PORTAL</div>
            <div class="t2">Bandarawela Consumer Service Center</div>
        </div>
    </a>

    <!-- Desktop links -->
    <div class="nav-links">
        <a href="#hero" class="active">Home</a>       
        <a href="#tools">Tools</a>
        <a href="#about">About</a>
        <a href="admin/login" class="nav-tool-btn"><i class="fas fa-user-shield"></i> Officer Login</a>
    </div>

    <!-- Status + Toggle -->
    <div style="display:flex;align-items:center;gap:12px;">
        <div class="nav-status"><div class="status-dot"></div>Live</div>
        <button class="nav-toggle" id="navToggle" aria-label="Menu"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="#hero"   onclick="closeMobileMenu()"><i class="fas fa-home"></i> Home</a>  
    <a href="#tools"  onclick="closeMobileMenu()"><i class="fas fa-th-large"></i> Tools</a>
     <a href="#about"  onclick="closeMobileMenu()"><i class="fas fa-building"></i> About EDL</a>
    <a href="admin/login"><i class="fas fa-user-shield"></i> Officer Login</a>
    <a href="job"><i class="fas fa-pen-nib"></i> Meter Removal</a>
    <a href="change"><i class="fas fa-exchange-alt"></i> Meter Change</a>
    <a href="new_service_request"><i class="fas fa-plug"></i> New Connection</a>
</div>


<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section id="hero">
    <!-- NOTIFICATION COMPONENT -->
<?php include 'includes/notification_component.php'; ?>
    <!-- Full Background Image Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade hero-bg-carousel" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100" data-bs-interval="4000">
                <img src="assets/images/edl_power_grid.webp" class="d-block w-100 h-100" alt="EDL Power Grid" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_smart_meter.webp" class="d-block w-100 h-100" alt="EDL Smart Meter" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_team.webp" class="d-block w-100 h-100" alt="EDL Field Team" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_substation.webp" class="d-block w-100 h-100" alt="EDL Substation" style="object-fit: cover;">
            </div>
        </div>
        <div class="hero-overlay"></div>
    </div>

    <!-- Hero Content Over Carousel -->
    <div class="hero-content-wrapper container position-relative z-index-2 d-flex flex-column align-items-center justify-content-center h-100 text-center">
        <div class="hero-badge mb-4"><i class="fas fa-shield-halved"></i>Internal Use Only</div>

        <div class="hero-icon-wrap mb-4">
            <div class="pulse-ring"></div>
            <div class="pulse-ring pulse-ring2"></div>
            <div class="core"><i class="fas fa-bolt"></i></div>
        </div>

        <h1 class="hero-title">
            Job Management<br><span class="grad">Portal</span>
        </h1>

        <p class="hero-sub mx-auto" style="max-width: 600px;">
            EDL Bandarawela Consumer Service Center — secure digital platform
            for field operations, meter management and service coordination.
        </p>
    </div>

    <div class="scroll-hint z-index-2">
        <span>Scroll to explore</span>
        <i class="fas fa-chevron-down arrow"></i>
    </div>
</section>

<div class="divider" style="max-width:900px;"></div>
<!-- ══════════════════════════════════════════
     GREETING POPUP MODAL (One-time per session)
══════════════════════════════════════════ -->
<?php
$greeting_q = $conn->query("SELECT * FROM company_events WHERE category='Greeting' ORDER BY id DESC LIMIT 1");
if ($greeting_q && $greeting_q->num_rows > 0):
    $gr = $greeting_q->fetch_assoc();
    $grImgSrc = str_replace('../../uploads/', 'uploads/', $gr['image_path']);
?>
<div class="modal fade" id="greetingPopup" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 overflow-hidden greeting-modal-card">
            
            <!-- Animated close button -->
            <button type="button" data-bs-dismiss="modal" aria-label="Close" class="greeting-close-btn">
                <i class="fas fa-times"></i>
            </button>

            <!-- Sparkle decorations -->
            <div class="greeting-sparkle" style="top:15%; left:10%;"></div>
            <div class="greeting-sparkle" style="top:25%; right:15%; animation-delay:0.5s;"></div>
            <div class="greeting-sparkle" style="top:8%; left:55%; animation-delay:1s;"></div>

            <!-- Hero Image with overlay -->
            <div class="greeting-img-wrap">
                <img src="<?php echo $grImgSrc; ?>" alt="<?php echo htmlspecialchars($gr['title']); ?>">
                <div class="greeting-img-overlay"></div>
                <div class="greeting-img-badge">
                    <i class="fas fa-gift me-1"></i> <?php echo $gr['category']; ?>
                </div>
            </div>
            
            <!-- Content body -->
            <div class="greeting-body">
                <h4 class="greeting-title"><?php echo htmlspecialchars($gr['title']); ?></h4>
                <div class="greeting-divider"></div>
                <p class="greeting-msg"><?php echo nl2br(htmlspecialchars($gr['message'])); ?></p>
                <div class="greeting-date">
                    <i class="far fa-calendar-alt me-1"></i> <?php echo date('F d, Y', strtotime($gr['created_at'])); ?>
                </div>
                <div class="greeting-brand">
                    <i class="fas fa-bolt"></i> EDL Bandarawela Consumer Service Center
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const _greetId = '<?php echo $gr["id"]; ?>';
</script>
<?php else: ?>
<script>
    const _greetId = null;
</script>
<?php endif; ?>


<!-- ══════════════════════════════════════════
     CSR & ALERTS — BENTO MAGAZINE SECTION
══════════════════════════════════════════ -->
<?php
$csr_q = $conn->query("SELECT * FROM company_events WHERE category IN ('CSR','Alert') ORDER BY id DESC LIMIT 10");
if ($csr_q && $csr_q->num_rows > 0):
    $ev_all = [];
    while($ev = $csr_q->fetch_assoc()) $ev_all[] = $ev;
?>
<section class="csr-section" id="updates">

    <!-- Ambient glow blobs -->
    <div class="csr-blob csr-blob-1"></div>
    <div class="csr-blob csr-blob-2"></div>

    <div class="container position-relative">

        <!-- Header -->
        <div class="csr-header reveal">
            <div class="csr-tag">
                <span class="csr-tag-dot"></span>
                Community &amp; Updates
            </div>
            <h2 class="section-heading">EDL in the <span class="accent">Society</span></h2>
            <p class="section-sub mx-auto">Community welfare drives, public notices and recent organizational updates.</p>
        </div>

        <!-- CAROUSEL -->
        <div class="swiper news-slider reveal">
            <div class="swiper-wrapper">
                <?php 
                $events = $ev_all;
                // Duplicate if too few for smooth loop
                if (count($events) > 0 && count($events) < 4) {
                    $events = array_merge($events, $events, $events);
                }
                foreach($events as $ev): 
                    $imgSrc = str_replace('../../uploads/', 'uploads/', $ev['image_path']);
                    $isAlert = $ev['category'] == 'Alert';
                    $dataJson = htmlspecialchars(json_encode($ev), ENT_QUOTES, 'UTF-8');
                    $dataImg  = htmlspecialchars($imgSrc, ENT_QUOTES);
                ?>
                <div class="swiper-slide">
                    <div class="news-card <?php echo $isAlert ? 'news-card--alert' : 'news-card--csr'; ?>"
                         onclick="openNewsModal('<?php echo $dataImg; ?>', <?php echo $dataJson; ?>)">
                        
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($ev['title']); ?>" loading="lazy">
                        <div class="news-overlay"></div>
                        
                        <div class="news-content">
                            <span class="news-badge">
                                <?php echo $isAlert ? '<i class="fas fa-bolt"></i> NEWS' : '<i class="fas fa-heart"></i> CSR'; ?>
                            </span>
                            <h3><?php echo htmlspecialchars($ev['title']); ?></h3>
                            <div class="news-date">
                                <i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($ev['created_at'])); ?>
                            </div>
                            <div class="read-more-btn">
                                Read More <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- NAVIGATION -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════
     CINEMATIC NEWS MODAL
═══════════════════════════════════════════════════ -->
<div class="modal fade" id="newsDetailModal" tabindex="-1" aria-hidden="true" style="z-index:10005;">
    <div class="modal-dialog modal-dialog-centered ndm-dialog">
        <div class="ndm-card">

            <!-- Ambient colour bar -->
            <div class="ndm-colorbar" id="ndmColorbar"></div>

            <!-- Hero image + close -->
            <div class="ndm-hero">
                <img id="newsModalImg" src="" alt="Cover">
                <div class="ndm-hero-shade"></div>
                <button class="ndm-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                <span class="ndm-hero-badge" id="ndmHeroBadge"></span>
            </div>

            <!-- Body -->
            <div class="ndm-body" id="ndmBody">
                <div class="ndm-meta">
                    <i class="far fa-calendar-alt"></i>
                    <span id="newsModalDate"></span>
                </div>
                <h2 class="ndm-title" id="newsModalTitle"></h2>
                <div class="ndm-divider"></div>
                <div class="ndm-message" id="newsModalMessage"></div>
            </div>

        </div>
    </div>
</div>

<?php endif; ?>
<!-- ══════════════════════════════════════════
     TOOLS SECTION
══════════════════════════════════════════ -->
<section id="tools">
    <div class="container">
        <div class="text-center mb-60 reveal" style="margin-bottom:56px;">
            <div class="section-tag"><i class="fas fa-th-large"></i>Available Tools</div>
            <h2 class="section-heading">Choose Your <span class="accent">Workspace</span></h2>
            <p class="section-sub mx-auto">
                Access the right tool for your role. All tools require authorisation and are for internal use only.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Officer Login -->
            <div class="col-md-4 reveal">
                <a href="admin/login" class="tool-big-card d-block">
                    <span class="tbc-badge badge-active">Active</span>
                    <div class="tbc-icon red"><i class="fas fa-user-shield"></i></div>
                    <div class="tbc-title">Officer Login</div>
                    <div class="tbc-desc">Secure access to the admin dashboard, reports, analytics, user management and system settings.</div>
                    <div class="tbc-arrow">Open Portal <i class="fas fa-arrow-right tbc-arrow-ico"></i></div>
                </a>
            </div>

            <!-- New Job -->
            <div class="col-md-4 reveal">
                <a href="job" class="tool-big-card d-block">
                    <span class="tbc-badge badge-active">Active</span>
                    <div class="tbc-icon gold"><i class="fas fa-pen-nib"></i></div>
                    <div class="tbc-title">Meter Removal</div>
                    <div class="tbc-desc">Submit and track meter removal and disconnection requests. Manage job status and field worker assignments.</div>
                    <div class="tbc-arrow">Enter Record <i class="fas fa-arrow-right tbc-arrow-ico"></i></div>
                </a>
            </div>

            <!-- Meter Change -->
            <div class="col-md-4 reveal">
                <a href="change" class="tool-big-card d-block">
                    <span class="tbc-badge badge-active">Active</span>
                    <div class="tbc-icon blue"><i class="fas fa-exchange-alt"></i></div>
                    <div class="tbc-title">Meter Change</div>
                    <div class="tbc-desc">Log and process meter replacement requests, track progress and generate completion reports.</div>
                    <div class="tbc-arrow">Open Form <i class="fas fa-arrow-right tbc-arrow-ico"></i></div>
                </a>
            </div>

            <!-- New Connection -->
            <div class="col-md-4 reveal">
                <a href="new_service_request" class="tool-big-card d-block">
                    <span class="tbc-badge badge-active">Active</span>
                    <div class="tbc-icon gold"><i class="fas fa-plug"></i></div>
                    <div class="tbc-title">New Connection</div>
                    <div class="tbc-desc">Register new service application and track progress.</div>
                    <div class="tbc-arrow">Open Form <i class="fas fa-arrow-right tbc-arrow-ico"></i></div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     ABOUT SECTION
══════════════════════════════════════════ -->
<section id="about">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Left – Text -->
            <div class="col-lg-6 reveal">
                <div class="section-tag"><i class="fas fa-building"></i>About EDL</div>
                <h2 class="section-heading">Powering Lives Across<br><span class="accent">Bandarawela</span></h2>
                <p class="section-sub mb-4">
                    The EDL Bandarawela Consumer Service Center is committed to delivering reliable, affordable and sustainable 
                    power supply to homes and businesses across the Bandarawela region as a core part of Electricity Distribution Lanka.
                </p>
                <p class="section-sub mb-36" style="margin-bottom:32px;">
                    Our mission is to modernise distribution infrastructure, 
                    reduce technical losses and provide excellent customer service through 
                    cutting-edge technology and a dedicated workforce.
                </p>

                <div class="about-feature">
                    <div class="about-feat-icon"><i class="fas fa-network-wired"></i></div>
                    <div class="about-feat-text">
                        <strong>Modern Distribution Network</strong>
                        <span>State-of-the-art grid infrastructure covering urban and rural areas island-wide.</span>
                    </div>
                </div>
                <div class="about-feature">
                    <div class="about-feat-icon"><i class="fas fa-leaf"></i></div>
                    <div class="about-feat-text">
                        <strong>Sustainable Energy</strong>
                        <span>Committed to green energy integration and reducing carbon footprint.</span>
                    </div>
                </div>
                <div class="about-feature">
                    <div class="about-feat-icon"><i class="fas fa-headset"></i></div>
                    <div class="about-feat-text">
                        <strong>24/7 Customer Support</strong>
                        <span>Round-the-clock service teams ensuring uninterrupted power supply.</span>
                    </div>
                </div>
            </div>

            <!-- Right – Images -->
            <div class="col-lg-6 reveal">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="about-img-wrap">
                            <img src="assets/images/edl_team.webp" alt="EDL Field Team at work" loading="lazy">
                            <div class="img-label"><i class="fas fa-users"></i>Our Field Team</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="about-img-wrap">
                            <img src="assets/images/edl_substation.webp" alt="EDL Substation Control Room" loading="lazy">
                            <div class="img-label"><i class="fas fa-microchip"></i>Control Infrastructure</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- ══════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════ -->
<div class="stats-bar">
    <div class="container">
        <div class="row justify-content-center align-items-center g-0">
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">2005</div><div class="stat-label">Established</div></div></div>
            <div class="col-auto d-none d-md-block"><div class="stat-divider" style="height:60px;"></div></div>
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">35K+</div><div class="stat-label">Consumers Served</div></div></div>
            <div class="col-auto d-none d-md-block"><div class="stat-divider" style="height:60px;"></div></div>
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">25+</div><div class="stat-label">Service Areas</div></div></div>
            <div class="col-auto d-none d-md-block"><div class="stat-divider" style="height:60px;"></div></div>
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">4</div><div class="stat-label">Active Tools</div></div></div>
        </div>
    </div>
</div>
<!-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <div class="bolt"><i class="fas fa-bolt" style="color:#fff;font-size:.9rem;"></i></div>
                    <div>
                        <div style="font-weight:800;font-size:.95rem;">EDL PORTAL</div>
                        <div style="font-size:.72rem;color:var(--muted);">Bandarawela Consumer Service Center</div>
                    </div>
                </div>
                <p class="footer-desc">
                    Internal job management system for field operations, meter services 
                    and service request coordination. Authorised personnel only.
                </p>
            </div>

            <div class="col-lg-2 col-md-3 offset-lg-2">
                <div class="footer-link-group">
                    <h6>Navigation</h6>
                    <a href="#hero">Home</a>
                    <a href="#about">About</a>
                    <a href="#tools">Tools</a>
                </div>
            </div>

            <div class="col-lg-2 col-md-3">
                <div class="footer-link-group">
                    <h6>Tools</h6>
                    <a href="admin/login">Officer Login</a>
                    <a href="job">New Job Record</a>
                    <a href="change">Meter Change</a>
                    <a href="new_service_request">New Connection</a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <div class="footer-link-group">
                    <h6>System</h6>
                    <a href="#">Version 4.0</a>
                    <a href="#">Internal Use Only</a>
                    <div style="margin-top:14px;">
                        <div class="nav-status" style="display:inline-flex;"><div class="status-dot"></div>System Online</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; <?php echo date('Y'); ?> Electricity Distribution Lanka pvt ltd. All rights reserved.</div>
            <div style="display:flex;align-items:center;gap:10px;">
                Developed by <a href="https://hathisathissara.unaux.com/" target="_blank">Hathisa Thissara</a>
                <span class="ver-pill">v4.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════
     SCRIPTS
══════════════════════════════ -->
<script>

/* ── Navbar scroll ── */
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);

    /* Active nav link by section */
    const links = document.querySelectorAll('.nav-links a:not(.nav-tool-btn)');
    const sections = Array.from(links)
        .map(link => link.getAttribute('href'))
        .filter(href => href && href.startsWith('#'))
        .map(href => href.slice(1));
    let cur = '';
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 100) cur = id;
    });
    links.forEach(a => {
        a.classList.remove('active');
        if (a.getAttribute('href') === '#'+cur) a.classList.add('active');
    });
});

/* ── Mobile menu ── */
document.getElementById('navToggle').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.toggle('open');
});
function closeMobileMenu() { document.getElementById('mobileMenu').classList.remove('open'); }

/* ── Scroll reveal ── */
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold:.12 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
/* ── Support Form Submit ── */
document.getElementById('supportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn  = document.getElementById('supSubmitBtn');
    const ok   = document.getElementById('supSuccess');
    const err  = document.getElementById('supError');
    const orig = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending…';
    ok.classList.add('d-none');
    err.classList.add('d-none');

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'Accept': 'application/json' }
        });
        if (res.ok) {
            ok.classList.remove('d-none');
            form.reset();
        } else {
            err.classList.remove('d-none');
        }
    } catch(_) {
        err.classList.remove('d-none');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
    }
});

/* Reset alerts when modal closes */
document.getElementById('supportModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('supSuccess').classList.add('d-none');
    document.getElementById('supError').classList.add('d-none');
});

/* ── Greeting Popup (One-time per session) ── */
if (_greetId && document.getElementById('greetingPopup')) {
    const seenKey = 'edl_greeting_seen';
    const lastSeen = sessionStorage.getItem(seenKey);
    
    if (lastSeen !== _greetId) {
        // Show popup after loader finishes (1.5s delay for smooth UX)
        setTimeout(function() {
            const greetModal = new bootstrap.Modal(document.getElementById('greetingPopup'));
            greetModal.show();
            sessionStorage.setItem(seenKey, _greetId);
        }, 1500);
    }
}

/* ── News Slider (Coverflow Swiper) ── */
if (document.querySelector('.news-slider')) {
    const newsSwiper = new Swiper('.news-slider', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        loop: true,
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 100,
            modifier: 2,
            slideShadows: true,
        },
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        }
    });
}

/* ── News Modal Handler (Bento Grid) ── */
function openNewsModal(imgSrc, data) {
    const isAlert = data.category === 'Alert';

    // Image
    document.getElementById('newsModalImg').src = imgSrc;

    // Title
    document.getElementById('newsModalTitle').innerText = data.title;

    // Date
    document.getElementById('newsModalDate').innerText = new Date(data.created_at)
        .toLocaleDateString(undefined, { year:'numeric', month:'long', day:'numeric' });

    // Hero badge
    const heroBadge = document.getElementById('ndmHeroBadge');
    heroBadge.innerHTML = isAlert
        ? '<i class="fas fa-bolt me-1"></i> NEWS'
        : '<i class="fas fa-heart me-1"></i> CSR';
    heroBadge.className = 'ndm-hero-badge ' + (isAlert ? 'is-alert' : 'is-csr');

    // Top colour bar tint per category
    const colorbar = document.getElementById('ndmColorbar');
    colorbar.style.background = isAlert
        ? 'linear-gradient(90deg,#c0392b,#ff6b6b)'
        : 'linear-gradient(90deg,#2563eb,#38bdf8)';

    // Process message – linkify URLs + line breaks
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    let msg = (data.message || '').replace(urlRegex, url =>
        `<a href="${url}" target="_blank" rel="noopener">` +
        `<i class="fas fa-external-link-alt small me-1"></i>${url}</a>`
    ).replace(/\n/g, '<br>');
    document.getElementById('newsModalMessage').innerHTML = msg;

    // Scroll body back to top on reopen
    const body = document.getElementById('ndmBody');
    if (body) body.scrollTop = 0;

    new bootstrap.Modal(document.getElementById('newsDetailModal')).show();
}
</script>
</body>
</html>