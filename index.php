<?php include 'config/db_conn.php'; ?>
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
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/style.css">   
</head>
<body class="loading">

<!-- LOADER -->
<div id="loader-wrapper"><div class="spinner"></div></div>

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
            <div class="t2">Electricity Distribution Lanka</div>
        </div>
    </a>

    <!-- Desktop links -->
    <div class="nav-links">
        <a href="#hero" class="active">Home</a>
        <a href="#about">About</a>
        <a href="#tools">Tools</a>
        <a href="#app-download">Download App</a>
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
    <a href="#app-download" onclick="closeMobileMenu()"><i class="fab fa-android"></i> Download App</a>
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
     ANDROID APP DOWNLOAD SECTION
══════════════════════════════════════════ -->
<section id="app-download" style="padding: 80px 0;">
    <div class="container">
        <div class="app-download-card reveal">
            <!-- Decorative orbs -->
            <div class="app-orb app-orb-1"></div>
            <div class="app-orb app-orb-2"></div>

            <div class="row align-items-center g-5 position-relative">
                <!-- Left: Text -->
                <div class="col-lg-7">
                    <div class="section-tag" style="margin-bottom:18px;">
                        <i class="fab fa-android"></i> Mobile Application
                    </div>
                    <h2 class="section-heading" style="margin-bottom:16px;">
                        EDL Field App for <span class="accent">Android</span>
                    </h2>
                    <p class="section-sub" style="margin-bottom:28px;max-width:520px;">
                        Download the official EDL Mobile App to manage field jobs, meter records
                        and service requests directly from your Android device. Built for EDL field
                        officers — fast, secure and offline-capable.
                    </p>

                    <!-- Feature pills -->
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:36px;">
                        <span class="app-feat-pill"><i class="fas fa-wifi-slash"></i> Offline Ready</span>
                        <span class="app-feat-pill"><i class="fas fa-lock"></i> Secure Access</span>
                        <span class="app-feat-pill"><i class="fas fa-bolt"></i> Fast & Lightweight</span>
                        <span class="app-feat-pill"><i class="fas fa-sync-alt"></i> Auto Sync</span>
                    </div>

                    <!-- Download Button -->
                    <!-- ⚠️  APK LINK: Replace the href below with your actual .apk download URL -->
                    <!-- Example: Google Drive direct download or your server path like "assets/downloads/edl-app.apk" -->
                    <a href="assets/downloads/edl-app.apk"
                       id="apkDownloadBtn"
                       class="apk-download-btn"
                       download
                       aria-label="Download EDL Android App">
                        <div class="apk-btn-icon">
                            <i class="fab fa-android"></i>
                        </div>
                        <div class="apk-btn-text">
                            <span class="apk-sub">Download for</span>
                            <span class="apk-main">Android APK</span>
                        </div>
                        <i class="fas fa-download apk-arrow"></i>
                    </a>

                    <p style="margin-top:14px;font-size:.78rem;color:var(--muted);">
                        <i class="fas fa-info-circle me-1"></i>
                        For Android 7.0+ &bull; Enable "Install from Unknown Sources" if prompted &bull; Internal use only
                    </p>
                </div>

                <!-- Right: Phone Mockup -->
                <div class="col-lg-5 text-center">
                    <div class="phone-mockup-wrap">
                        <div class="phone-mockup">
                            <div class="phone-screen">
                                <div class="phone-screen-content">
                                    <div class="phone-notch"></div>
                                    <div style="padding:16px 12px;">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                                            <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#c0392b,#e74c3c);display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-bolt" style="color:#fff;font-size:.65rem;"></i>
                                            </div>
                                            <span style="font-weight:700;font-size:.75rem;color:#fff;">EDL Field App</span>
                                        </div>
                                        <!-- Mini dashboard blocks -->
                                        <div style="background:rgba(255,255,255,.08);border-radius:10px;padding:10px;margin-bottom:8px;">
                                            <div style="font-size:.55rem;color:rgba(255,255,255,.5);margin-bottom:4px;">TODAY'S JOBS</div>
                                            <div style="font-size:1.1rem;font-weight:800;color:#e74c3c;">12</div>
                                            <div style="height:3px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:6px;">
                                                <div style="width:65%;height:3px;background:linear-gradient(90deg,#c0392b,#e74c3c);border-radius:2px;"></div>
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:8px;">
                                            <div style="background:rgba(255,255,255,.06);border-radius:8px;padding:8px;text-align:center;">
                                                <i class="fas fa-pen-nib" style="color:#e74c3c;font-size:.7rem;"></i>
                                                <div style="font-size:.5rem;color:rgba(255,255,255,.6);margin-top:3px;">Removals</div>
                                                <div style="font-size:.85rem;font-weight:700;color:#fff;">7</div>
                                            </div>
                                            <div style="background:rgba(255,255,255,.06);border-radius:8px;padding:8px;text-align:center;">
                                                <i class="fas fa-exchange-alt" style="color:#3498db;font-size:.7rem;"></i>
                                                <div style="font-size:.5rem;color:rgba(255,255,255,.6);margin-top:3px;">Changes</div>
                                                <div style="font-size:.85rem;font-weight:700;color:#fff;">5</div>
                                            </div>
                                        </div>
                                        <div style="background:rgba(231,76,60,.15);border:1px solid rgba(231,76,60,.3);border-radius:8px;padding:8px;">
                                            <div style="font-size:.5rem;color:rgba(255,255,255,.5);margin-bottom:2px;">SYNC STATUS</div>
                                            <div style="display:flex;align-items:center;gap:4px;">
                                                <div style="width:6px;height:6px;border-radius:50%;background:#2ecc71;animation:blink 1.5s infinite;"></div>
                                                <span style="font-size:.6rem;color:#2ecc71;">Synced • Just now</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Glow effect -->
                        <div class="phone-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ── App Download Card ── */
.app-download-card {
    background: linear-gradient(135deg, rgba(255,255,255,.04) 0%, rgba(255,255,255,.01) 100%);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 28px;
    padding: 56px 52px;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(12px);
    box-shadow: 0 0 80px rgba(192,57,43,.08), 0 32px 64px rgba(0,0,0,.25);
    transition: transform .3s ease, box-shadow .3s ease;
}
.app-download-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 0 100px rgba(192,57,43,.14), 0 40px 80px rgba(0,0,0,.3);
}
.app-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    pointer-events: none;
    z-index: 0;
}
.app-orb-1 {
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(192,57,43,.18), transparent 70%);
    top: -100px; right: -80px;
}
.app-orb-2 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(52,152,219,.12), transparent 70%);
    bottom: -80px; left: -60px;
}

/* Feature pills */
.app-feat-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 999px;
    font-size: .78rem;
    color: rgba(255,255,255,.75);
    font-weight: 500;
    transition: background .2s, border-color .2s;
}
.app-feat-pill:hover {
    background: rgba(192,57,43,.15);
    border-color: rgba(192,57,43,.35);
    color: #fff;
}
.app-feat-pill i { color: #e74c3c; }

/* Download Button */
.apk-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    padding: 16px 28px;
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
    border-radius: 16px;
    text-decoration: none;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 8px 32px rgba(192,57,43,.45);
    transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
    position: relative;
    overflow: hidden;
}
.apk-download-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.15), transparent);
    opacity: 0;
    transition: opacity .25s;
}
.apk-download-btn:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 16px 48px rgba(192,57,43,.55);
    color: #fff;
}
.apk-download-btn:hover::before { opacity: 1; }
.apk-btn-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.apk-btn-text { display: flex; flex-direction: column; line-height: 1.2; }
.apk-sub  { font-size: .72rem; opacity: .8; font-weight: 400; }
.apk-main { font-size: 1.05rem; font-weight: 700; }
.apk-arrow { font-size: 1rem; margin-left: 8px; opacity: .85; animation: bounceX 1.6s infinite; }
@keyframes bounceX {
    0%,100% { transform: translateX(0); }
    50%      { transform: translateX(5px); }
}

/* Phone Mockup */
.phone-mockup-wrap {
    position: relative;
    display: inline-block;
}
.phone-mockup {
    width: 200px;
    margin: 0 auto;
    background: linear-gradient(160deg, #1a1a2e, #16213e);
    border: 2px solid rgba(255,255,255,.12);
    border-radius: 36px;
    padding: 10px;
    box-shadow: 0 20px 60px rgba(0,0,0,.5), inset 0 1px 0 rgba(255,255,255,.08);
    position: relative;
    z-index: 1;
    animation: phoneFloat 4s ease-in-out infinite;
}
@keyframes phoneFloat {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-12px); }
}
.phone-screen {
    background: #0d0d1a;
    border-radius: 28px;
    overflow: hidden;
    min-height: 380px;
    position: relative;
}
.phone-notch {
    width: 70px; height: 22px;
    background: #0d0d1a;
    border-radius: 0 0 14px 14px;
    margin: 0 auto 8px;
    position: relative;
    z-index: 2;
}
.phone-glow {
    position: absolute;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(231,76,60,.3), transparent 70%);
    bottom: -40px; left: 50%; transform: translateX(-50%);
    filter: blur(30px);
    z-index: 0;
}
@keyframes blink {
    0%,100% { opacity:1; }
    50%      { opacity:.3; }
}

/* Responsive */
@media (max-width: 768px) {
    .app-download-card { padding: 36px 24px; }
    .apk-download-btn  { width: 100%; justify-content: center; }
    .phone-mockup      { width: 160px; }
    .phone-screen      { min-height: 300px; }
}
</style>

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
    const sections = ['hero','about','tools','app-download'];
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
</script>
</body>
</html>