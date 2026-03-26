<?php include '../config/db_conn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDL Office Portal | Electricity Distribution Lanka pvt ltd</title>
    <meta name="description" content="Electricity Distribution Lanka pvt ltd – Official Internal Job Management Portal for field operations, meter management and service requests.">
    <link rel="icon" href="https://img.icons8.com/color/48/d11212/flash-on.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="manifest.json">
    <link rel="stylesheet" href="assets/css/loader.css">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js').then(() => console.log('SW Registered'));
        }
    </script>
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="loading">

<!-- LOADER -->
<div id="loader-wrapper"><div class="spinner"></div></div>

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
            <div class="t2">Electricity Distribution Lanka</div>
        </div>
    </a>

    <!-- Desktop links -->
    <div class="nav-links">
        <a href="#hero" class="active">Home</a>
        <a href="#about">About</a>
        <a href="#tools">Tools</a>
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
    <a href="#about"  onclick="closeMobileMenu()"><i class="fas fa-building"></i> About EDL</a>
    <a href="#tools"  onclick="closeMobileMenu()"><i class="fas fa-th-large"></i> Tools</a>
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
<?php include '../includes/notification_component.php'; ?>
    <!-- Full Background Image Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade hero-bg-carousel" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100" data-bs-interval="4000">
                <img src="assets/images/edl_power_grid.png" class="d-block w-100 h-100" alt="EDL Power Grid" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_smart_meter.png" class="d-block w-100 h-100" alt="EDL Smart Meter" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_team.png" class="d-block w-100 h-100" alt="EDL Field Team" style="object-fit: cover;">
            </div>
            <div class="carousel-item h-100" data-bs-interval="4000">
                <img src="assets/images/edl_substation.png" class="d-block w-100 h-100" alt="EDL Substation" style="object-fit: cover;">
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
            Electricity Distribution Lanka pvt ltd — secure digital platform
            for field operations, meter management and service coordination.
        </p>
    </div>

    <div class="scroll-hint z-index-2">
        <span>Scroll to explore</span>
        <i class="fas fa-chevron-down arrow"></i>
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
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">500K+</div><div class="stat-label">Consumers Served</div></div></div>
            <div class="col-auto d-none d-md-block"><div class="stat-divider" style="height:60px;"></div></div>
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">25+</div><div class="stat-label">Service Areas</div></div></div>
            <div class="col-auto d-none d-md-block"><div class="stat-divider" style="height:60px;"></div></div>
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">4</div><div class="stat-label">Active Tools</div></div></div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════
     ABOUT SECTION
══════════════════════════════════════════ -->
<section id="about">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Left – Text -->
            <div class="col-lg-6 reveal">
                <div class="section-tag"><i class="fas fa-building"></i>About EDL</div>
                <h2 class="section-heading">Powering Lives Across<br><span class="accent">Sri Lanka</span></h2>
                <p class="section-sub mb-4">
                    Electricity Distribution Lanka pvt ltd (EDL) is a leading electricity distribution 
                    company in Sri Lanka committed to delivering reliable, affordable and sustainable 
                    power supply to homes and businesses across the region.
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
                            <img src="assets/images/edl_team.png" alt="EDL Field Team at work" loading="lazy">
                            <div class="img-label"><i class="fas fa-users"></i>Our Field Team</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="about-img-wrap">
                            <img src="assets/images/edl_substation.png" alt="EDL Substation Control Room" loading="lazy">
                            <div class="img-label"><i class="fas fa-microchip"></i>Control Infrastructure</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="divider" style="max-width:900px;"></div>

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
                        <div style="font-size:.72rem;color:var(--muted);">Electricity Distribution Lanka pvt ltd</div>
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
                    <a href="#">Version 3.0</a>
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
                <span class="ver-pill">v3.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════
     SCRIPTS
══════════════════════════════ -->
<script>
/* ── Loader ── */
window.addEventListener('load', () => {
    const ldr = document.getElementById('loader-wrapper');
    setTimeout(() => {
        ldr.style.opacity = '0';
        setTimeout(() => { ldr.style.display='none'; document.body.classList.remove('loading'); }, 500);
    }, 300);
});

/* ── Navbar scroll ── */
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);

    /* Active nav link by section */
    const sections = ['hero','about','tools'];
    const links = document.querySelectorAll('.nav-links a:not(.nav-tool-btn)');
    let cur = '';
    sections.forEach(id => {
        const el = document.getElementById(id);
        if(el && window.scrollY >= el.offsetTop - 100) cur = id;
    });
    links.forEach(a => {
        a.classList.remove('active');
        if(a.getAttribute('href') === '#'+cur) a.classList.add('active');
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
</body>
</html>