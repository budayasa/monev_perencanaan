<style>
    /* --- 0. ANTI-BLINK & SMOOTH TRANSITION --- */
    html, body {
        background-color: #f9fafb !important;
    }

    /* --- Loading Bar SPA (Orange) --- */
    .fi-spa-loading-bar, 
    .fi-spa-loading-bar::after,
    #nprogress .bar,
    .nprogress-custom-parent #nprogress .bar,
    .nprogress-custom-parent #nprogress .spinner {
        background-color: #FF6600 !important;
        color: #FF6600 !important;
        z-index: 99999 !important;
    }
    #nprogress .peg {
        box-shadow: 0 0 10px #FF6600, 0 0 5px #FF6600 !important;
    }

    /* --- 2. PERBAIKAN USER MENU DROPDOWN (PEKAT) --- */
    .fi-dropdown-panel {
        border: 1px solid rgba(0,0,0,0.1) !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    /* Teks & Icon Utama (Profile/Sign Out) */
    .fi-dropdown-list-item-label, 
    .fi-dropdown-list-item-label span,
    .fi-dropdown-list-item-icon,
    .fi-dropdown-header-label {
        color: #005000 !important; 
        font-weight: 800 !important;
    }

    /* --- FIX THEME SWITCHER AGAR PEKAT --- */
    .fi-theme-switcher {
        background-color: #f1f5f9 !important;
        border-radius: 0.75rem !important;
        padding: 0.35rem !important;
        margin: 0.5rem !important;
        border: 1px solid #e2e8f0 !important;
    }

    /* Icon Matahari/Bulan/Monitor */
    .fi-theme-switcher-btn svg {
        color: #005000 !important;
        stroke-width: 2.5px !important;
        opacity: 1 !important;
    }

    /* Warna Saat Mode Gelap (Dark Mode) */
    .dark .fi-dropdown-list-item-label, 
    .dark .fi-dropdown-list-item-label span,
    .dark .fi-dropdown-list-item-icon,
    .dark .fi-dropdown-header-label,
    .dark .fi-theme-switcher-btn svg {
        color: #fbbf24 !important;
    }

    .dark .fi-theme-switcher {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
    }

    .fi-dropdown-list-item {
        background-color: transparent !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 0.75rem !important;
        padding: 0.6rem 1rem !important;
        width: 100% !important;
    }

    /* =====================================================
       3. SIDEBAR — PREMIUM EDITION
    ===================================================== */

    /* Seluruh panel sidebar: gradient hijau soft premium */
    .fi-sidebar {
        background: linear-gradient(180deg, #1a5c35 0%, #1e6b3a 45%, #237a40 100%) !important;
        border-right: none !important;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.25) !important;
    }

    /* Header sidebar (logo area) */
    .fi-sidebar-header {
        background-color: #005000 !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        padding: 1.25rem 1rem !important;
    }

    /* Brand name di sidebar */
    .fi-sidebar-header .fi-brand-name {
        color: #ffffff !important;
        font-weight: 800 !important;
        letter-spacing: 0.05em !important;
        text-shadow: 0 2px 8px rgba(0,0,0,0.4) !important;
    }

    /* Tombol tutup sidebar */
    .fi-sidebar-header button {
        color: rgba(255,255,255,0.7) !important;
        transition: all 0.2s ease !important;
    }
    .fi-sidebar-header button:hover {
        color: #ffffff !important;
        background: rgba(255, 102, 0, 0.3) !important;
        border-radius: 0.5rem !important;
    }

    /* Nav wrapper (area scroll menu) */
    .fi-sidebar-nav {
        padding: 0.75rem 0.5rem !important;
    }

    /* Group label (judul kelompok menu, misal "MANAJEMEN") */
    .fi-sidebar-group-label {
        color: rgba(255, 200, 100, 0.65) !important;
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.12em !important;
        text-transform: uppercase !important;
        padding: 0.75rem 0.75rem 0.35rem !important;
    }

    /* Setiap item menu — default */
    .fi-sidebar-item > a,
    .fi-sidebar-item-button {
        color: rgba(255,255,255,0.78) !important;
        border-radius: 0.6rem !important;
        margin: 2px 0 !important;
        padding: 0.6rem 0.85rem !important;
        transition: all 0.2s cubic-bezier(.4,0,.2,1) !important;
        border-left: 3px solid transparent !important;
        position: relative !important;
        overflow: hidden !important;
    }

    /* Shine effect pseudo-element */
    .fi-sidebar-item > a::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.04) 100%);
        opacity: 0;
        transition: opacity 0.2s;
        border-radius: inherit;
    }

    /* Hover: geser sedikit ke kanan + terang */
    .fi-sidebar-item > a:hover,
    .fi-sidebar-item-button:hover {
        background: rgba(255,255,255,0.1) !important;
        color: #ffffff !important;
        border-left-color: rgba(255,102,0,0.5) !important;
        transform: translateX(3px) !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.15) !important;
    }
    .fi-sidebar-item > a:hover::before {
        opacity: 1 !important;
    }

    /* Icon menu default */
    .fi-sidebar-item-icon {
        color: rgba(255,255,255,0.55) !important;
        transition: color 0.2s, transform 0.2s !important;
    }
    .fi-sidebar-item > a:hover .fi-sidebar-item-icon {
        color: #FF6600 !important;
        transform: scale(1.15) !important;
    }

    /* Label teks menu default */
    .fi-sidebar .fi-sidebar-item-label {
        color: rgba(255,255,255,0.85) !important;
        font-size: 0.875rem !important;
        transition: color 0.2s !important;
    }
    .fi-sidebar-item > a:hover .fi-sidebar-item-label {
        color: #ffffff !important;
    }

    /* === ACTIVE STATE — menu yang sedang dipilih === */
    .fi-sidebar-item-active > a,
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(135deg, rgba(255,102,0,0.22) 0%, rgba(0,100,0,0.35) 100%) !important;
        border-left: 3px solid #FF6600 !important;
        color: #ffffff !important;
        box-shadow:
            0 4px 16px rgba(0,0,0,0.18),
            inset 0 1px 0 rgba(255,255,255,0.08) !important;
        transform: translateX(2px) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #FF6600 !important;
        filter: drop-shadow(0 0 6px rgba(255,102,0,0.55)) !important;
        transform: scale(1.1) !important;
    }

    .fi-sidebar-item-active .fi-sidebar-item-label {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    /* Pemisah antar grup */
    .fi-sidebar-group + .fi-sidebar-group {
        border-top: 1px solid rgba(255,255,255,0.07) !important;
        margin-top: 0.5rem !important;
        padding-top: 0.25rem !important;
    }

    /* Footer / area bawah sidebar */
    .fi-sidebar-footer {
        border-top: 1px solid rgba(255,255,255,0.1) !important;
        background: rgba(0,0,0,0.15) !important;
        padding: 0.75rem !important;
    }

    /* --- 4. CSS UPLOAD --- */
    .fi-fo-file-upload-trigger-label {
        color: #FF6600 !important;
        font-weight: 600;
    }
    .fi-fo-file-upload-trigger-icon {
        color: #FF6600 !important;
    }

    /* --- 5. LOGIN PAGE & CAPTCHA --- */
    .fi-simple-layout {
        background: url("{{ asset('images/background.jpg') }}") no-repeat center center fixed !important;
        background-size: cover !important;
        background-color: black !important;
    }
        
    .fi-simple-main-ctn {
        background: rgba(0, 0, 0, 0.4) !important; 
        border-radius: 2.5rem !important;
        backdrop-filter: blur(5px);
        padding: 3rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .fi-simple-main-ctn label span, 
    .fi-simple-main-ctn .fi-checkbox-label,
    .fi-simple-header-heading {
        color: white !important;
        text-shadow: 2px 2px 8px rgba(0,0,0,1) !important;
    }
    .fi-simple-main-ctn input:not([type="checkbox"]) {
        background: white !important;
        color: black !important;
    }

    #captcha-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }
    #captcha-modal {
        background: white;
        padding: 2.5rem;
        border-radius: 1.5rem;
        width: 90%;
        max-width: 400px;
        text-align: center;
    }
    .captcha-box {
        background: #fdf5e6;
        padding: 1.5rem;
        font-size: 2.5rem;
        font-weight: bold;
        letter-spacing: 0.8rem;
        margin: 1.5rem 0;
        color: #008080;
        border: 2px dashed #008080;
    }

    /* --- 6. TOPBAR --- */
    .fi-topbar,
    .fi-topbar nav {
        background-color: #005000 !important; 
    }

    .fi-topbar button svg,
    .fi-topbar-open-sidebar-btn,
    .fi-sidebar-collapse-btn {
        color: white !important;
    }
    
    .fi-topbar button:hover svg {
        color: #FF6600 !important;
        transition: color 0.2s;
    }

    /* --- 7. DASHBOARD TEXT COLORS --- */
    .fi-header-heading {
        color: #005000 !important;
        font-weight: 800 !important;
        letter-spacing: -0.02em;
    }

    .fi-breadcrumbs-item-label {
        color: #6B7280 !important;
    }
    .fi-breadcrumbs-item-label:hover {
        color: #FF6600 !important;
    }

    .fi-section-header-heading, 
    .fi-ta-header-heading {
        color: #005000 !important;
        font-weight: 700 !important;
    }

    .fi-wi-stats-overview-stat-value {
        color: #005000 !important;
    }

    .fi-wi-stats-overview-stat-label {
        color: #FF6600 !important;
        font-weight: 600;
    }

    /* --- 8. USER PROFILE ICON (AVATAR) --- */
    .fi-avatar {
        background-color: #FFFFFF !important; 
        border: 2px solid #FF6600 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .fi-avatar span, 
    .fi-avatar div,
    .fi-avatar-placeholder,
    .fi-avatar-placeholder span,
    [class*="fi-avatar"] span {
        color: #FF6600 !important;
        font-weight: 900 !important;
        text-transform: uppercase;
    }

    .fi-user-menu:hover .fi-avatar {
        background-color: #FF6600 !important;
        border-color: #FFFFFF !important;
    }
    
    .fi-user-menu:hover .fi-avatar span,
    .fi-user-menu:hover .fi-avatar div {
        color: #FFFFFF !important;
    }

    /* --- 9. NOTIFICATION BELL ICON --- */
    .fi-topbar-notifications-btn svg {
        color: #FF6600 !important;
        filter: drop-shadow(0 2px 4px rgba(255, 102, 0, 0.2));
    }

    .fi-topbar-notifications-btn:hover svg {
        transform: rotate(15deg);
        filter: brightness(1.2);
        transition: all 0.2s ease;
    }
    
    .fi-topbar-notifications-btn .fi-badge {
        background-color: #FF6600 !important;
        color: white !important;
    }

    /* =====================================================
       10. RESOURCE BANNER & KPI CARDS (.rb-*)
    ===================================================== */
    .rb-container {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        margin-bottom: 1.25rem;
    }
    .rb-hero {
        background: linear-gradient(135deg, #005000 0%, #166534 100%);
        border-bottom: 4px solid #FF6600;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        color: #ffffff;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 6px 16px rgba(0, 80, 0, 0.12);
    }
    .rb-hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        min-width: 260px;
    }
    .rb-hero-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.14);
        border: 2px solid #FF6600;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        flex-shrink: 0;
    }
    .rb-hero-title {
        font-size: 1.12rem;
        font-weight: 900;
        margin: 0;
        color: #ffffff;
        letter-spacing: -0.01em;
    }
    .rb-hero-sub {
        font-size: 0.76rem;
        color: #dcfce7;
        margin: 0.25rem 0 0;
        line-height: 1.45;
    }
    .rb-badge-accent {
        background: #FF6600;
        color: #ffffff;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .rb-nav-pills {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.45rem;
    }
    .rb-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.9rem;
        border-radius: 10px;
        font-size: 0.74rem;
        font-weight: 800;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.28);
        text-decoration: none;
        transition: all 0.15s;
    }
    .rb-nav-pill:hover {
        background: rgba(255, 255, 255, 0.24);
    }
    .rb-nav-pill.active {
        background: #FF6600;
        border-color: #FF6600;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .rb-stats-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 0.95rem;
    }
    @media (min-width: 640px) {
        .rb-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (min-width: 1024px) {
        .rb-stats-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    .rb-stat-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1.5px solid #e2e8f0;
        padding: 1rem 1.15rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
    }
    .rb-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .rb-stat-label {
        font-size: 0.66rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin: 0;
    }
    .rb-stat-value {
        font-size: 1.5rem;
        font-weight: 900;
        line-height: 1.2;
        margin: 0.15rem 0;
        color: #0f172a;
    }
    .rb-stat-value small {
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
    }
    .rb-stat-sub {
        font-size: 0.7rem;
        color: #64748b;
        margin: 0;
        line-height: 1.35;
    }

    /* =====================================================
       11. FILAMENT NATIVE TABLES & FORMS — PEMKAB BADUNG THEME
    ===================================================== */
    .fi-ta-ctn {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden !important;
    }
    .fi-ta-table thead tr {
        background: linear-gradient(135deg, #005000 0%, #166534 100%) !important;
        border-bottom: 3px solid #FF6600 !important;
    }
    .fi-ta-table thead th,
    .fi-ta-header-cell {
        background: transparent !important;
        padding-top: 0.9rem !important;
        padding-bottom: 0.9rem !important;
    }
    .fi-ta-header-cell-label,
    .fi-ta-header-cell button span,
    .fi-ta-header-cell svg {
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        font-size: 0.7rem !important;
    }
    .fi-ta-row:hover {
        background-color: #f0fdf4 !important;
    }
    .fi-tabs-item-active {
        background-color: #f0fdf4 !important;
        border-color: #005000 !important;
    }
    .fi-tabs-item-active .fi-tabs-item-label {
        color: #005000 !important;
        font-weight: 800 !important;
    }
    .fi-section {
        border-radius: 16px !important;
        border: 1px solid #d1fae5 !important;
        box-shadow: 0 2px 8px rgba(0, 80, 0, 0.03) !important;
    }
    .fi-section-header {
        background: linear-gradient(90deg, #f0fdf4 0%, #ffffff 100%) !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-radius: 16px 16px 0 0 !important;
    }
</style>

@if(request()->is('admin/login*'))
    <div id="captcha-overlay">
        <div id="captcha-modal">
            <h2 style="color: #005000; font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">Verifikasi Keamanan</h2>
            <p style="color: #4b5563;">Ketik kode di bawah untuk login:</p>
            <div class="captcha-box" id="captcha-display">
                <span id="captcha-text"></span>
            </div>
            <input type="text" id="captcha-input" placeholder="..." style="width: 100%; padding: 0.8rem; border: 2px solid #e5e7eb; border-radius: 0.75rem; margin-bottom: 1.5rem; text-align: center; text-transform: uppercase; font-weight: bold; font-size: 1.2rem;">
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="verifyCaptcha()" style="flex: 2; background: #005000; color: white; padding: 0.8rem; border-radius: 0.75rem; font-weight: bold;">VERIFIKASI</button>
                <button type="button" onclick="generateCaptcha()" style="flex: 1; background: #f3f4f6; padding: 0.8rem; border-radius: 0.75rem;">🔄</button>
            </div>
        </div>
    </div>

    <script>
        let currentCaptcha = "";
        const overlay = document.getElementById("captcha-overlay");
        const input = document.getElementById("captcha-input");
        const display = document.getElementById("captcha-text");
        let realSubmitBtn = null;

        function generateCaptcha() {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            currentCaptcha = "";
            for (let i = 0; i < 5; i++) {
                currentCaptcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            display.innerText = currentCaptcha;
            input.value = "";
            setTimeout(() => input.focus(), 100);
        }

        function verifyCaptcha() {
            if (input.value.toUpperCase() === currentCaptcha) {
                overlay.style.display = "none";
                realSubmitBtn.click();
            } else {
                alert("Kode Salah!");
                generateCaptcha();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                realSubmitBtn = document.querySelector('button[type="submit"]');
                if (realSubmitBtn) {
                    realSubmitBtn.style.display = "none";
                    const fakeBtn = realSubmitBtn.cloneNode(true);
                    fakeBtn.style.display = "flex";
                    fakeBtn.type = "button";
                    fakeBtn.id = "fake-login-btn";
                    realSubmitBtn.parentNode.insertBefore(fakeBtn, realSubmitBtn);
                    fakeBtn.addEventListener("click", function(e) {
                        e.preventDefault();
                        generateCaptcha();
                        overlay.style.display = "flex";
                    });
                }
            }, 500);
        });
        input.addEventListener("keypress", (e) => { if (e.key === "Enter") verifyCaptcha(); });
    </script>
@endif

