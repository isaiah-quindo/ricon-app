<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/svg+xml" href="/logomark.svg">
  <title>@yield('title', 'TGC Training Plan · RiCON')</title>
  <meta name="description" content="Week-by-week ultra trail training by Edify Endurance. 100K and 60K plans on one shared 24-week calendar." />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kufam:wght@700;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --orange:   #F04C24;
  --dark:     #21252D;
  --white:    #FFFFFF;
  --border:   rgba(255,255,255,0.12);
  --muted:    rgba(255,255,255,0.50);
  --hover-bg: rgba(240,76,36,0.07);
  --sel-bg:   rgba(240,76,36,0.13);
}

html { scroll-behavior: smooth; }

body {
  background: var(--dark);
  color: var(--white);
  font-family: 'Manrope', sans-serif;
  -webkit-font-smoothing: antialiased;
  min-height: 100vh;
}

/* ── Progress bar ── */
.progress {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 3px;
  background: rgba(255,255,255,0.08);
  z-index: 200;
}
.progress__fill {
  height: 100%;
  background: var(--orange);
  transition: width 0.6s cubic-bezier(.4,0,.2,1);
}

/* ── Logo ── */
.logo {
  position: fixed;
  top: 24px; left: 28px;
  z-index: 200;
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
}
.logo__mark { height: 22px; width: auto; }
.logo__word { height: 13px; width: auto; opacity: 0.85; }

/* ── Plan toggle (top right) ── */
.plan-toggle {
  position: fixed;
  top: 20px; right: 28px;
  z-index: 200;
  display: flex;
  gap: 0;
  border: 1px solid var(--border);
}
.toggle-btn {
  padding: 0 16px;
  min-height: 36px;
  background: none;
  border: none;
  font-family: 'Manrope', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--muted);
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
.toggle-btn.active {
  background: var(--orange);
  color: var(--white);
}
.toggle-btn:not(.active):hover { color: var(--white); }

/* ── Screen ── */
.screen {
  display: none;
  flex-direction: column;
  justify-content: center;
  min-height: 100vh;
  padding: 100px 40px 72px;
  max-width: 680px;
  margin: 0 auto;
  animation: fadeUp 0.38s cubic-bezier(.4,0,.2,1) forwards;
}
.screen.active { display: flex; }

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: none; }
}

/* ── Shared label ── */
.label {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: var(--orange);
  margin-bottom: 22px;
}

/* ── Pre-launch screen ── */
.launch__title {
  font-family: 'Kufam', sans-serif;
  font-size: clamp(38px, 8vw, 68px);
  font-weight: 900;
  line-height: 1.04;
  letter-spacing: -0.02em;
  margin-bottom: 22px;
}
.launch__title em { color: var(--orange); font-style: normal; }
.launch__sub {
  font-size: 16px;
  color: var(--muted);
  line-height: 1.65;
  margin-bottom: 40px;
  max-width: 400px;
}
.launch__meta {
  display: flex;
  gap: 12px;
  margin-bottom: 48px;
  flex-wrap: wrap;
}
.launch__pill {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--muted);
  padding: 8px 16px;
  border: 1px solid var(--border);
}
.launch__pill span { color: var(--orange); }
.launch__soon {
  font-size: 13px;
  color: var(--muted);
  letter-spacing: 0.04em;
  margin-bottom: 40px;
}

/* ── Week screen ── */
.week__eyebrow {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--orange);
  margin-bottom: 6px;
}
.week__phase {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 20px;
}
.week__title {
  font-family: 'Kufam', sans-serif;
  font-size: clamp(28px, 5vw, 46px);
  font-weight: 900;
  line-height: 1.07;
  letter-spacing: -0.02em;
  margin-bottom: 10px;
}
.week__meta {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 28px;
}
.week__dates {
  font-size: 13px;
  color: var(--muted);
}
.week__hours {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--orange);
  padding: 5px 10px;
  border: 1px solid rgba(240,76,36,0.3);
}
.week__rule {
  width: 36px; height: 2px;
  background: var(--orange);
  margin-bottom: 22px;
}
.week__brief {
  font-size: 15px;
  color: var(--muted);
  line-height: 1.75;
  margin-bottom: 40px;
  max-width: 480px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* ── Day schedule ── */
.schedule { display: flex; flex-direction: column; gap: 10px; margin-bottom: 48px; }

.day-row {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 16px 20px;
  border: 1px solid var(--border);
  transition: border-color 0.15s;
}
.day-row.is-rest {
  opacity: 0.30;
  padding: 12px 20px;
}
.day-row.is-key {
  border-color: var(--orange);
  background: var(--sel-bg);
}
.day-row.is-race {
  border-color: rgba(212,168,67,0.6);
  background: rgba(212,168,67,0.06);
}

.day-name {
  width: 34px; min-width: 34px; height: 34px;
  border: 1px solid rgba(255,255,255,0.15);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 700; letter-spacing: 0.1em;
  color: var(--muted);
  flex-shrink: 0;
  margin-top: 1px;
}
.day-row.is-key .day-name {
  background: var(--orange);
  border-color: var(--orange);
  color: var(--white);
}
.day-row.is-race .day-name {
  background: rgba(212,168,67,0.15);
  border-color: rgba(212,168,67,0.5);
  color: #D4A843;
}
.day-row.is-rest .day-name {
  border-color: rgba(255,255,255,0.08);
}

/* ── Day body ── */
.day-body { flex: 1; min-width: 0; font-family: 'Manrope', sans-serif; }
.day-dur {
  font-family: 'Manrope', sans-serif;
  font-size: 11px;
  font-weight: 700;
  color: var(--muted);
  margin-bottom: 3px;
  letter-spacing: 0.04em;
}
.day-row.is-key .day-dur { color: rgba(240,76,36,0.85); }
.day-title {
  font-family: 'Manrope', sans-serif;
  font-size: 15px;
  font-weight: 500;
  color: rgba(255,255,255,0.80);
  line-height: 1.4;
  margin-bottom: 6px;
}
.day-row.is-key .day-title { color: var(--white); }
.day-row.is-rest .day-title { font-size: 13px; }

.day-tags {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}
.day-cat {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 10px;
  letter-spacing: 0.06em;
  color: var(--muted);
}
.day-cat-dot {
  width: 5px; height: 5px;
  border-radius: 50%;
  flex-shrink: 0;
}
.day-tag {
  font-size: 10px;
  letter-spacing: 0.06em;
  color: var(--muted);
  padding: 2px 7px;
  border: 1px solid rgba(255,255,255,0.1);
}
.day-vert { color: rgba(74,157,101,0.85); border-color: rgba(74,157,101,0.2); }
.day-pack { color: rgba(138,108,192,0.85); border-color: rgba(138,108,192,0.2); }

.day-badge {
  margin-left: auto;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  flex-shrink: 0;
  padding-top: 2px;
  align-self: flex-start;
}
.day-badge.key-badge { color: var(--orange); }
.day-badge.race-badge { color: #D4A843; }

/* ── 60K notice ── */
.notice {
  font-size: 12px;
  color: rgba(212,168,67,0.75);
  border: 1px solid rgba(212,168,67,0.2);
  padding: 12px 16px;
  line-height: 1.6;
  margin-bottom: 32px;
}

/* ── Button ── */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: var(--orange);
  color: var(--white);
  font-family: 'Manrope', sans-serif;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 17px 32px;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: opacity 0.18s;
  align-self: flex-start;
}
.btn:hover { opacity: 0.86; }
.btn svg { width: 15px; height: 15px; flex-shrink: 0; }
.btn-ghost {
  background: none;
  color: var(--muted);
  border: 1px solid var(--border);
  margin-left: 12px;
}
.btn-ghost:hover { color: var(--white); border-color: rgba(255,255,255,0.3); opacity: 1; }

/* ── Back link ── */
.back-link {
  background: none;
  border: none;
  font-family: 'Manrope', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  cursor: pointer;
  padding: 0;
  margin-bottom: 28px;
  display: inline-block;
  align-self: flex-start;
  text-align: left;
  transition: color 0.15s;
}
.back-link:hover { color: var(--white); }

/* ── Week navigation ── */
.week-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 48px;
  padding-top: 24px;
  border-top: 1px solid var(--border);
  gap: 16px;
}
.week-nav__btn {
  background: none;
  border: 1px solid var(--border);
  font-family: 'Manrope', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--muted);
  cursor: pointer;
  padding: 10px 16px;
  transition: border-color 0.15s, color 0.15s;
  white-space: nowrap;
}
.week-nav__btn:hover:not(:disabled) {
  border-color: var(--orange);
  color: var(--white);
}
.week-nav__btn:disabled {
  opacity: 0.2;
  cursor: default;
}
.week-nav__label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.25);
  text-align: center;
  flex: 1;
}

/* ── Phase color strip ── */
.phase-strip {
  width: 100%; height: 2px;
  margin-bottom: 28px;
  opacity: 0.6;
}

/* ── Reference section ── */
.ref-section { margin-top: 8px; margin-bottom: 48px; }
.ref-heading {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: var(--muted);
  padding-bottom: 12px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 16px;
}

/* RPE rows */
.rpe-table { display: flex; flex-direction: column; gap: 6px; margin-bottom: 40px; }
.rpe-row {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 12px 16px;
  border: 1px solid var(--border);
}
.rpe-badge {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.06em;
  color: var(--orange);
  min-width: 40px;
  flex-shrink: 0;
  padding-top: 1px;
}
.rpe-label {
  font-size: 13px;
  font-weight: 600;
  color: rgba(255,255,255,0.85);
  margin-bottom: 3px;
}
.rpe-detail { font-size: 11px; color: var(--muted); line-height: 1.55; }

/* Volume chart */
.vol-chart {
  display: flex;
  align-items: flex-end;
  gap: 3px;
  height: 52px;
  margin-bottom: 10px;
}
.vol-bar {
  flex: 1;
  background: rgba(255,255,255,0.07);
  min-height: 3px;
  transition: background 0.15s;
}
.vol-bar.is-deload  { background: rgba(91,127,168,0.35); }
.vol-bar.is-current { background: var(--orange); }
.vol-legend {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  color: rgba(255,255,255,0.25);
  letter-spacing: 0.06em;
}
.vol-legend span.center { text-align: center; color: rgba(240,76,36,0.7); }

/* ── Block callout (training focus + block goal) ── */
.block-callout {
  border: 1px solid var(--border);
  margin-bottom: 32px;
}
.block-row {
  display: flex;
  align-items: baseline;
  gap: 16px;
  padding: 12px 16px;
}
.block-row + .block-row { border-top: 1px solid var(--border); }
.block-key {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--orange);
  min-width: 96px;
  flex-shrink: 0;
}
.block-val {
  font-size: 13px;
  font-weight: 500;
  color: rgba(255,255,255,0.78);
  line-height: 1.45;
}

/* ── Gate screen ── */
.gate__sub {
  font-size: 16px; color: var(--muted); line-height: 1.65;
  margin-bottom: 40px; max-width: 440px;
}
.gate__form { display: flex; flex-direction: column; gap: 24px; max-width: 400px; }
.gate__field { display: flex; flex-direction: column; gap: 8px; }
.gate__label {
  font-size: 10px; font-weight: 700; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--muted);
}
.gate__input {
  width: 100%; background: rgba(255,255,255,0.05);
  border: 1px solid var(--border); color: var(--white);
  font-family: 'Manrope', sans-serif; font-size: 15px;
  padding: 14px 16px; outline: none; transition: border-color 0.15s;
  -webkit-appearance: none;
}
.gate__input::placeholder { color: rgba(255,255,255,0.25); }
.gate__input:focus { border-color: rgba(240,76,36,0.6); }
.gate__plans { display: flex; }
.gate__plan-btn {
  flex: 1; display: flex; flex-direction: column;
  align-items: center; gap: 4px; padding: 14px 16px;
  background: none; border: 1px solid var(--border);
  color: var(--muted); cursor: pointer;
  font-family: 'Manrope', sans-serif;
  transition: border-color 0.15s, background 0.15s, color 0.15s;
}
.gate__plan-btn + .gate__plan-btn { border-left: none; }
.gate__plan-btn.active { background: var(--sel-bg); border-color: var(--orange); color: var(--white); }
.gate__plan-btn:not(.active):hover { border-color: rgba(255,255,255,0.3); }
.gate__plan-dist { font-size: 15px; font-weight: 700; letter-spacing: 0.04em; }
.gate__plan-btn.active .gate__plan-dist { color: var(--orange); }
.gate__plan-date {
  font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
  text-transform: uppercase; opacity: 0.6;
}
.gate__error { font-size: 13px; color: rgba(240,76,36,0.9); line-height: 1.5; padding: 12px 0; }
.gate__fine {
  font-size: 11px; color: rgba(255,255,255,0.25);
  line-height: 1.6; letter-spacing: 0.02em; margin-top: -8px;
}

/* ── Mobile ── */
@media (max-width: 520px) {
  .screen { padding: 80px 20px 56px; }
  .logo__word { display: none; }
  .plan-toggle { right: 16px; top: 16px; }
  .toggle-btn { padding: 0 14px; min-height: 44px; }
  .logo { top: 18px; left: 20px; }
  .day-row { padding: 14px 14px; gap: 12px; }
  .day-title { font-size: 14px; }
  .vol-chart { gap: 2px; }
  .block-row { flex-direction: column; gap: 4px; }
  .block-key { min-width: unset; }
  .gate__form { max-width: 100%; }
  .gate__input { font-size: 16px; }
  .gate__plan-btn { padding: 12px 10px; }
  .btn { align-self: stretch; justify-content: center; }
  .back-link { padding: 12px 0; }
  .week-nav { gap: 8px; }
  .week-nav__btn { min-height: 44px; padding: 10px 12px; font-size: 10px; }
  .week-nav__label { font-size: 10px; }
}
</style>
  <!-- Meta Pixel Code -->
  <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '1001258219021585');
      fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=1001258219021585&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
</head>
<body>

  <!-- Progress bar -->
  <div class="progress"><div class="progress__fill" id="progressFill"></div></div>

  <!-- Logo -->
  <a class="logo" href="/" aria-label="RiCON home">
    <svg class="logo__mark" aria-hidden="true" viewBox="0 0 230 165" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M215.765 0C220.954 0 225.68 2.9834 227.912 7.66756C229.904 11.8466 229.592 16.7148 227.181 20.5779L207.672 44.7951L192.604 63.3738C185.029 72.7136 173.644 78.1381 161.619 78.1381H125.843L150.597 111.104C151.635 112.486 151.602 114.395 150.518 115.741L137.989 131.294C136.444 133.212 133.506 133.162 132.027 131.192L88.152 72.7621C85.0909 68.6854 84.5963 63.2286 86.8749 58.6681C89.1535 54.1076 93.8139 51.2265 98.912 51.2265H161.619C165.532 51.2265 169.237 49.4612 171.703 46.4217L187.526 26.9116H40.0659L125.238 142.638C126.254 144.019 126.213 145.911 125.137 147.246L112.739 162.637C111.186 164.565 108.229 164.502 106.759 162.51L2.61887 21.4318C-0.389149 17.3447 -0.842148 11.9127 1.44776 7.384C3.73776 2.85528 8.38103 2.36461e-06 13.4558 0H215.765Z" fill="url(#logo_grad)"/><defs><radialGradient id="logo_grad" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(172 375) rotate(-100.278) scale(381.115 589.559)"><stop stop-color="#A41C01"/><stop offset="1" stop-color="#FC4C00"/></radialGradient></defs></svg>
    <svg class="logo__word" aria-hidden="true" viewBox="0 0 222 52" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M102.426 0C104.801 0 106.943 0.186054 108.853 0.558594C110.762 0.931116 112.275 1.35076 113.393 1.81641V12.5029H112.624C111.506 11.8976 110.133 11.4086 108.503 11.0361C106.873 10.6636 105.104 10.4775 103.194 10.4775C99.5155 10.4775 96.4882 11.106 94.1133 12.3633C91.785 13.6206 90.0619 15.4139 88.9443 17.7422C87.8268 20.0705 87.2686 22.8179 87.2686 25.9844C87.2686 29.1508 87.8268 31.8983 88.9443 34.2266C90.0619 36.5549 91.785 38.3482 94.1133 39.6055C96.4882 40.8628 99.5155 41.4912 103.194 41.4912C105.104 41.4912 106.873 41.3051 108.503 40.9326C110.133 40.5601 111.506 40.0712 112.624 39.4658H113.393V50.1533C112.275 50.619 110.762 51.0376 108.853 51.4102C106.943 51.7827 104.801 51.9688 102.426 51.9688C96.2792 51.9687 91.1336 50.9215 86.9893 48.8262C82.8913 46.7306 79.7938 43.7499 77.6982 39.8848C75.6494 35.9732 74.625 31.3395 74.625 25.9844C74.625 20.7225 75.6494 16.1589 77.6982 12.2939C79.7938 8.38229 82.8913 5.35499 86.9893 3.21289C91.1337 1.07096 96.2791 7.66683e-06 102.426 0ZM144.736 0C150.138 5.70059e-05 154.748 1.0946 158.566 3.2832C162.431 5.47186 165.365 8.52199 167.367 12.4336C169.37 16.3452 170.371 20.862 170.371 25.9844C170.371 31.1067 169.37 35.6235 167.367 39.5352C165.365 43.4468 162.431 46.4979 158.566 48.6865C154.748 50.8751 150.138 51.9687 144.736 51.9688C139.288 51.9688 134.654 50.8979 130.836 48.7559C127.017 46.5672 124.107 43.5171 122.104 39.6055C120.102 35.6938 119.101 31.1533 119.101 25.9844C119.101 20.862 120.102 16.3452 122.104 12.4336C124.107 8.52194 127.017 5.47187 130.836 3.2832C134.654 1.0946 139.288 0 144.736 0ZM20.5361 0.837891C25.0997 0.837891 28.8722 1.56032 31.8525 3.00391C34.8792 4.40092 37.1379 6.40311 38.6279 9.01074C40.1646 11.6185 40.9326 14.6924 40.9326 18.2314C40.9326 21.491 40.3269 24.4246 39.1162 27.0322C37.9055 29.5934 36.2527 31.7825 34.1572 33.5986C33.2338 34.4171 32.2476 35.1486 31.2012 35.7969L42.4697 50.4326V51.1309H28.7783L20.4717 39.1318C19.9156 39.1675 19.3551 39.1865 18.79 39.1865H12.4336V51.1309H0V0.837891H20.5361ZM63.6914 51.1309H50.6914V18.1309H63.6914V51.1309ZM209.373 21.8867V0.837891H221.736V51.1309H209.373V38.1641L191.072 19.666V51.1309H178.639V0.837891H188.558L209.373 21.8867ZM144.736 10.4775C141.849 10.4775 139.427 11.1298 137.472 12.4336C135.563 13.7374 134.119 15.5764 133.141 17.9512C132.209 20.2795 131.743 22.9575 131.743 25.9844C131.743 29.0111 132.209 31.712 133.141 34.0869C134.119 36.4153 135.562 38.2313 137.472 39.5352C139.428 40.839 141.849 41.4912 144.736 41.4912C147.623 41.4911 150.021 40.839 151.931 39.5352C153.84 38.2313 155.26 36.4152 156.191 34.0869C157.169 31.712 157.658 29.0112 157.658 25.9844C157.658 22.9575 157.169 20.2795 156.191 17.9512C155.26 15.5765 153.84 13.7374 151.931 12.4336C150.021 11.1298 147.623 10.4776 144.736 10.4775ZM12.4336 29.1279H18.5107C20.839 29.1279 22.7254 28.639 24.1689 27.6611C25.659 26.6367 26.7527 25.3322 27.4512 23.749C28.1496 22.1658 28.499 20.4664 28.499 18.6504C28.499 16.0892 27.7538 14.2029 26.2637 12.9922C24.8202 11.7816 22.888 11.1758 20.4668 11.1758H12.4336V29.1279ZM63.6914 13.1309H50.6914V1.13086H63.6914V13.1309Z" fill="white"/></svg>
  </a>

  @yield('content')

  @stack('scripts')
</body>
</html>
