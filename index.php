<?php include 'db_conn.php'; ?>
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
    <link rel="stylesheet" href="loader.css">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js').then(() => console.log('SW Registered'));
        }
    </script>
    <style>
        /* ═══════════════════════════════════════
           GLOBAL TOKENS
        ═══════════════════════════════════════ */
        :root {
            --red:      #c0392b;
            --red-l:    #e74c3c;
            --red-ll:   #ff6b6b;
            --gold:     #f39c12;
            --dark:     #0d0f12;
            --dark2:    #13161c;
            --dark3:    #1a1e27;
            --border:   rgba(255,255,255,0.07);
            --muted:    rgba(255,255,255,0.45);
            --white:    #ffffff;
            --nav-h:    70px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--white);
            overflow-x: hidden;
        }
        body.loading { overflow: hidden; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark2); }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 3px; }

        /* ═══════════════════════════════════════
           BACKGROUND
        ═══════════════════════════════════════ */
        .bg-base {
            position: fixed; inset: 0; z-index: -2;
            background: linear-gradient(135deg, #0d0f12 0%, #13161c 60%, #0f1218 100%);
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: -1;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 56px 56px;
        }
        .orb {
            position: fixed; border-radius: 50%; filter: blur(100px); z-index: -1; pointer-events: none;
            animation: floatOrb 9s ease-in-out infinite;
        }
        .orb-r { width:500px; height:500px; background: rgba(192,57,43,.18); top:-120px; left:-120px; }
        .orb-g { width:350px; height:350px; background: rgba(243,156,18,.10); bottom:-80px; right:-80px; animation-delay:4s; }
        .orb-b { width:250px; height:250px; background: rgba(52,152,219,.07); top:40%; left:55%; animation-delay:7s; }

        @keyframes floatOrb {
            0%,100%{ transform:translate(0,0) scale(1); }
            40%    { transform:translate(35px,-25px) scale(1.06); }
            70%    { transform:translate(-25px,40px) scale(.96); }
        }

        /* ═══════════════════════════════════════
           STICKY NAVBAR
        ═══════════════════════════════════════ */
        .navbar-edl {
            position: fixed; top:0; left:0; right:0; z-index: 1000;
            height: var(--nav-h);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px;
            background: rgba(13,15,18,.75);
            backdrop-filter: blur(24px);
            border-bottom: 1px solid var(--border);
            transition: background .3s;
        }
        .navbar-edl.scrolled {
            background: rgba(13,15,18,.95);
            border-bottom-color: rgba(192,57,43,.3);
        }

        /* Brand */
        .nav-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
        .nav-bolt {
            width:40px; height:40px;
            background: linear-gradient(135deg, var(--red), var(--red-l));
            border-radius:11px;
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; color:#fff;
            box-shadow: 0 0 22px rgba(192,57,43,.45);
        }
        .nav-brand-text { line-height:1.2; }
        .nav-brand-text .t1 { font-size:.95rem; font-weight:800; color:#fff; letter-spacing:.5px; }
        .nav-brand-text .t2 { font-size:.68rem; color:var(--muted); font-weight:400; }

        /* Nav links */
        .nav-links { display:flex; align-items:center; gap:6px; }
        .nav-links a {
            padding:8px 16px; border-radius:9px;
            font-size:.82rem; font-weight:600; color:rgba(255,255,255,.65);
            text-decoration:none; transition:all .22s; white-space:nowrap;
        }
        .nav-links a:hover { background:rgba(255,255,255,.07); color:#fff; }
        .nav-links a.active { color:#fff; background:rgba(192,57,43,.18); }

        /* Tool-links in nav */
        .nav-tool-btn {
            padding: 8px 18px !important;
            background: rgba(192,57,43,.15) !important;
            border: 1px solid rgba(192,57,43,.3) !important;
            border-radius: 9px !important;
            color: var(--red-l) !important;
            font-weight: 700 !important;
            display:flex; align-items:center; gap:7px;
        }
        .nav-tool-btn:hover {
            background: rgba(192,57,43,.3) !important;
            color:#fff !important;
        }

        .nav-status {
            display:flex; align-items:center; gap:6px;
            font-size:.7rem; color: #2ed573; font-weight:700;
            background: rgba(46,213,115,.08); border:1px solid rgba(46,213,115,.18);
            padding:5px 12px; border-radius:20px;
        }
        .status-dot { width:6px; height:6px; border-radius:50%; background:#2ed573; animation:pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }

        /* Mobile hamburger */
        .nav-toggle { display:none; background:none; border:none; color:#fff; font-size:1.3rem; cursor:pointer; }
        .mobile-menu {
            display:none; position:fixed; top:var(--nav-h); left:0; right:0;
            background:rgba(13,15,18,.97); border-bottom:1px solid var(--border);
            padding:20px; flex-direction:column; gap:8px; z-index:999;
        }
        .mobile-menu.open { display:flex; }
        .mobile-menu a {
            padding:12px 16px; border-radius:10px; color:rgba(255,255,255,.8);
            text-decoration:none; font-weight:600; font-size:.9rem;
            border:1px solid var(--border); display:flex; align-items:center; gap:10px;
        }
        .mobile-menu a:hover { background:rgba(192,57,43,.12); color:#fff; }

        /* ═══════════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════════ */
        #hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 120px 0 80px;
            overflow: hidden;
            width: 100%;
        }

        .hero-bg-carousel {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(13,15,18,0.6) 0%, rgba(13,15,18,0.95) 100%);
            z-index: 1;
        }

        .hero-content-wrapper {
            z-index: 2;
        }

        .z-index-2 {
            z-index: 2;
        }

        .hero-badge {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(192,57,43,.12); border:1px solid rgba(192,57,43,.28);
            color:var(--red-l); padding:7px 20px; border-radius:30px;
            font-size:.72rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
            margin-bottom:32px;
            animation: fadeUp .6s ease both;
        }

        .hero-icon-wrap {
            position:relative; width:110px; height:110px;
            margin:0 auto 32px; animation: fadeUp .65s ease .1s both;
        }
        .hero-icon-wrap .pulse-ring {
            position:absolute; inset:-10px; border-radius:50%;
            border:2px solid rgba(192,57,43,.25);
            animation: ringPulse 2.8s ease-in-out infinite;
        }
        .hero-icon-wrap .pulse-ring2 {
            position:absolute; inset:-22px;
            border-color:rgba(192,57,43,.12); animation-delay:.9s;
        }
        .hero-icon-wrap .core {
            width:110px; height:110px;
            background:linear-gradient(135deg, var(--red) 0%, var(--red-l) 100%);
            border-radius:30px; display:flex; align-items:center; justify-content:center;
            font-size:3rem; color:#fff;
            box-shadow: 0 24px 70px rgba(192,57,43,.45), 0 0 0 1px rgba(255,255,255,.06);
        }
        @keyframes ringPulse {
            0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.06);opacity:.4}
        }

        .hero-title {
            font-size: clamp(2.4rem, 5.5vw, 4rem);
            font-weight: 900; line-height:1.08; letter-spacing:-1.5px;
            margin-bottom:16px; animation: fadeUp .7s ease .2s both;
        }
        .hero-title .grad {
            background: linear-gradient(90deg, var(--red-l), var(--gold));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }

        .hero-sub {
            color:var(--muted); font-size:1.05rem; font-weight:400;
            max-width:560px; margin:0 auto 48px; line-height:1.7;
            animation: fadeUp .7s ease .3s both;
        }

        /* ── Tool Picker Card ── */
        .tool-card {
            background: rgba(255,255,255,.04);
            border:1px solid var(--border);
            border-radius:24px; padding:36px 40px;
            max-width:580px; margin:0 auto;
            backdrop-filter:blur(20px);
            box-shadow: 0 40px 80px rgba(0,0,0,.35);
            animation: fadeUp .7s ease .4s both;
        }

        .tool-card-label {
            font-size:.68rem; font-weight:700; letter-spacing:2px;
            text-transform:uppercase; color:var(--muted);
            display:flex; align-items:center; gap:8px; margin-bottom:14px;
        }
        .tool-card-label::before {
            content:''; display:inline-block; width:18px; height:2px;
            background:var(--red); border-radius:2px;
        }

        /* Dropdown */
        .drop-wrap { position:relative; margin-bottom:18px; }
        .drop-wrap::after {
            content:'\f078'; font-family:'Font Awesome 6 Free'; font-weight:900;
            position:absolute; right:18px; top:50%; transform:translateY(-50%);
            color:var(--muted); pointer-events:none; font-size:.78rem; transition:.3s;
        }
        .drop-wrap:focus-within::after { color:var(--red-l); transform:translateY(-50%) rotate(180deg); }

        .tool-select {
            width:100%; padding:17px 46px 17px 18px;
            background:rgba(255,255,255,.05); border:1.5px solid rgba(255,255,255,.1);
            border-radius:13px; color:#fff; font-family:'Inter',sans-serif;
            font-size:.97rem; font-weight:500; cursor:pointer;
            outline:none; appearance:none; -webkit-appearance:none; transition:all .25s;
        }
        .tool-select:focus {
            border-color:var(--red); background:rgba(192,57,43,.07);
            box-shadow:0 0 0 4px rgba(192,57,43,.12);
        }
        .tool-select option { background:#1a1e27; color:#fff; }
        .tool-select option:disabled { color:rgba(255,255,255,.3); }

        /* Preview strip */
        .tool-preview {
            display:none; align-items:center; gap:12px;
            padding:14px 16px; margin-bottom:16px;
            background:rgba(192,57,43,.07); border:1px solid rgba(192,57,43,.18);
            border-radius:12px; animation:fadeIn .3s ease;
        }
        .tool-preview.on { display:flex; }
        .preview-ico {
            width:38px; height:38px; border-radius:9px; flex-shrink:0;
            background:rgba(192,57,43,.14); color:var(--red-l);
            display:flex; align-items:center; justify-content:center; font-size:.9rem;
        }
        .preview-txt strong { display:block; font-size:.88rem; color:#fff; }
        .preview-txt span { font-size:.75rem; color:var(--muted); }

        /* Go Button */
        .go-btn {
            width:100%; padding:17px; border:none; border-radius:13px;
            background:linear-gradient(135deg, var(--red) 0%, var(--red-l) 100%);
            color:#fff; font-family:'Inter',sans-serif; font-size:.97rem; font-weight:700;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px;
            box-shadow:0 10px 30px rgba(192,57,43,.3); position:relative; overflow:hidden;
            transition:all .3s;
        }
        .go-btn::before {
            content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            transition:left .5s;
        }
        .go-btn:hover::before { left:100%; }
        .go-btn:hover { transform:translateY(-2px); box-shadow:0 16px 40px rgba(192,57,43,.45); }
        .go-btn:active { transform:translateY(0); }
        .go-btn:disabled {
            background:rgba(255,255,255,.07); box-shadow:none;
            cursor:not-allowed; transform:none; color:var(--muted);
        }
        .go-btn:disabled::before { display:none; }

        /* Scroll-down indicator */
        .scroll-hint {
            margin-top:60px; display:flex; flex-direction:column; align-items:center; gap:8px;
            color:var(--muted); font-size:.75rem; letter-spacing:1px; text-transform:uppercase;
            animation: fadeUp .7s ease .8s both;
        }
        .scroll-hint .arrow { animation: bounce 1.8s infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(6px)} }

        /* ═══════════════════════════════════════
           SECTION COMMONS
        ═══════════════════════════════════════ */
        section { padding: 100px 0; }
        .section-tag {
            display:inline-flex; align-items:center; gap:8px;
            background:rgba(192,57,43,.1); border:1px solid rgba(192,57,43,.22);
            color:var(--red-l); padding:6px 16px; border-radius:30px;
            font-size:.68rem; font-weight:700; letter-spacing:2px; text-transform:uppercase;
            margin-bottom:18px;
        }
        .section-heading {
            font-size:clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight:900; letter-spacing:-1px; line-height:1.12; margin-bottom:14px;
        }
        .section-heading .accent {
            background:linear-gradient(90deg, var(--red-l), var(--gold));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
        }
        .section-sub { color:var(--muted); font-size:.97rem; line-height:1.75; max-width:600px; }
        .divider {
            height:1px; background:linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 0 auto;
        }

        /* ═══════════════════════════════════════
           STATS BAR
        ═══════════════════════════════════════ */
        .stats-bar {
            background:rgba(255,255,255,.03); border-top:1px solid var(--border); border-bottom:1px solid var(--border);
            padding:44px 0;
        }
        .stat-item { text-align:center; padding:10px 20px; }
        .stat-num {
            font-size:2.4rem; font-weight:900; letter-spacing:-1px;
            background:linear-gradient(135deg, var(--red-l), var(--gold));
            -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
            line-height:1;
        }
        .stat-label { font-size:.75rem; color:var(--muted); font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-top:6px; }
        .stat-divider { width:1px; background:var(--border); align-self:stretch; }

        /* ═══════════════════════════════════════
           ABOUT SECTION
        ═══════════════════════════════════════ */
        #about { padding:100px 0; }

        .about-img-wrap {
            position:relative; border-radius:20px; overflow:hidden;
        }
        .about-img-wrap img {
            width:100%; height:340px; object-fit:cover; display:block;
            border-radius:20px;
            border:1px solid rgba(255,255,255,.07);
        }
        .about-img-wrap .img-label {
            position:absolute; bottom:14px; left:14px;
            background:rgba(0,0,0,.7); backdrop-filter:blur(10px);
            border:1px solid rgba(255,255,255,.1); border-radius:9px;
            padding:8px 14px; font-size:.75rem; font-weight:600; color:#fff;
            display:flex; align-items:center; gap:7px;
        }
        .about-img-wrap .img-label i { color:var(--red-l); }

        .about-feature {
            display:flex; align-items:flex-start; gap:14px;
            padding:16px 18px; border-radius:14px;
            background:rgba(255,255,255,.03); border:1px solid var(--border);
            margin-bottom:14px; transition:all .25s;
        }
        .about-feature:hover { background:rgba(192,57,43,.06); border-color:rgba(192,57,43,.2); }
        .about-feat-icon {
            width:40px; height:40px; border-radius:10px; flex-shrink:0;
            background:rgba(192,57,43,.12); color:var(--red-l);
            display:flex; align-items:center; justify-content:center; font-size:.9rem;
        }
        .about-feat-text strong { display:block; font-size:.88rem; color:#fff; font-weight:700; margin-bottom:2px; }
        .about-feat-text span { font-size:.78rem; color:var(--muted); line-height:1.5; }

        /* ═══════════════════════════════════════
           TOOLS SECTION
        ═══════════════════════════════════════ */
        #tools { padding:100px 0; }

        .tool-big-card {
            background:rgba(255,255,255,.03);
            border:1px solid var(--border);
            border-radius:20px; padding:32px 28px;
            height:100%; display:flex; flex-direction:column;
            transition: all .3s; cursor:pointer; text-decoration:none; color:#fff;
            position:relative; overflow:hidden;
        }
        .tool-big-card::before {
            content:''; position:absolute; inset:0; opacity:0;
            background:linear-gradient(135deg, rgba(192,57,43,.08), transparent);
            transition:opacity .3s;
        }
        .tool-big-card:hover { border-color:rgba(192,57,43,.35); transform:translateY(-4px);
            box-shadow:0 20px 50px rgba(0,0,0,.35); color:#fff; }
        .tool-big-card:hover::before { opacity:1; }

        .tbc-icon {
            width:56px; height:56px; border-radius:14px; margin-bottom:20px;
            display:flex; align-items:center; justify-content:center; font-size:1.3rem;
        }
        .tbc-icon.red   { background:rgba(192,57,43,.15); color:var(--red-l); }
        .tbc-icon.gold  { background:rgba(243,156,18,.12); color:var(--gold); }
        .tbc-icon.blue  { background:rgba(52,152,219,.12); color:#3498db; }

        .tbc-title { font-size:1.05rem; font-weight:800; margin-bottom:6px; }
        .tbc-desc  { font-size:.82rem; color:var(--muted); line-height:1.6; flex:1; }
        .tbc-arrow {
            margin-top:20px; display:inline-flex; align-items:center; gap:6px;
            font-size:.78rem; font-weight:700; color:var(--red-l);
        }
        .tool-big-card:hover .tbc-arrow-ico { transform:translateX(4px); }
        .tbc-arrow-ico { transition:transform .25s; }

        .tbc-badge {
            position:absolute; top:16px; right:16px;
            font-size:.65rem; font-weight:700; letter-spacing:1px;
            padding:4px 10px; border-radius:20px;
        }
        .badge-active { background:rgba(46,213,115,.12); color:#2ed573; border:1px solid rgba(46,213,115,.2); }
        .badge-soon   { background:rgba(255,255,255,.06); color:var(--muted); border:1px solid var(--border); }

        /* ═══════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════ */
        footer {
            background:rgba(255,255,255,.02); border-top:1px solid var(--border);
            padding:44px 0 24px;
        }
        .footer-brand { display:flex; align-items:center; gap:12px; margin-bottom:14px; }
        .footer-brand .bolt {
            width:38px; height:38px; background:linear-gradient(135deg,var(--red),var(--red-l));
            border-radius:10px; display:flex; align-items:center; justify-content:center;
            font-size:.9rem; box-shadow:0 0 18px rgba(192,57,43,.35);
        }
        .footer-desc { color:var(--muted); font-size:.82rem; line-height:1.7; }
        .footer-link-group h6 { font-size:.72rem; font-weight:700; letter-spacing:1.5px;
            text-transform:uppercase; color:var(--muted); margin-bottom:16px; }
        .footer-link-group a {
            display:block; color:rgba(255,255,255,.6); font-size:.82rem;
            text-decoration:none; padding:4px 0; transition:color .2s;
        }
        .footer-link-group a:hover { color:#fff; }
        .footer-bottom {
            margin-top:40px; padding-top:20px; border-top:1px solid var(--border);
            display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;
            font-size:.72rem; color:var(--muted);
        }
        .footer-bottom a { color:var(--gold); text-decoration:none; font-weight:600; }
        .footer-bottom a:hover { color:#fff; }
        .ver-pill {
            background:rgba(255,255,255,.05); border:1px solid var(--border);
            padding:3px 10px; border-radius:20px; font-size:.68rem; font-weight:600;
        }

        /* ═══════════════════════════════════════
           ANIMATIONS
        ═══════════════════════════════════════ */
        @keyframes fadeUp {
            from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)}
        }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        .reveal { opacity:0; transform:translateY(28px); transition:opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity:1; transform:translateY(0); }

        /* ═══════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════ */
        @media(max-width:991px){
            .stat-divider { display:none; }
            .about-img-wrap img { height:280px; }
        }
        @media(max-width:767px){
            .navbar-edl { padding:0 20px; }
            .nav-links { display:none; }
            .nav-status { display:none; }
            .nav-toggle { display:block; }
            .hero-title { letter-spacing:-0.5px; }
            .tool-card { padding:24px 20px; }
            footer .row > div { margin-bottom:28px; }
            .footer-bottom { flex-direction:column; text-align:center; }
        }
    </style>
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
    <a href="job"><i class="fas fa-pen-nib"></i> New Job Record</a>
    <a href="change"><i class="fas fa-exchange-alt"></i> Meter Change</a>
</div>


<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section id="hero">
    <!-- NOTIFICATION COMPONENT -->
<?php include 'notification_component.php'; ?>
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
            <div class="col-6 col-md-3"><div class="stat-item reveal"><div class="stat-num">3</div><div class="stat-label">Active Tools</div></div></div>
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
                    <div class="tbc-title">New Job Record</div>
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