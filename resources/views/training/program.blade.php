@extends('layouts.training')

@section('title', ($signup->plan === 'tgc100k' ? '100K' : '60K') . ' Training Program · RiCON')

@section('content')
  {{-- Locked distance badge (replaces the old 100K/60K toggle, no switching) --}}
  <div class="plan-toggle" aria-label="Your distance">
    <span class="toggle-btn active" style="display:inline-flex;align-items:center;cursor:default">{{ $signup->plan === 'tgc100k' ? '100K' : '60K' }}</span>
  </div>

  <div class="screen" id="s-welcome"></div>
  <div class="screen" id="s-week"></div>

  @php
    $signupData = [
        'first_name' => $signup->first_name,
        'plan'       => $signup->plan,
    ];
  @endphp
  <script type="application/json" id="program-data">{!! json_encode($program, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
  <script type="application/json" id="signup-data">{!! json_encode($signupData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endsection

@push('scripts')
<script>
const DATA   = JSON.parse(document.getElementById('program-data').textContent);
const SIGNUP = JSON.parse(document.getElementById('signup-data').textContent);
// One shared calendar: everyone is on the same week, anchored to the program start date
const START  = new Date(DATA.plans[SIGNUP.plan].program_start + 'T00:00:00');

// ── Config ──────────────────────────────────────────────────────────────────
const CAT_COLOR = {
  'Rest':              null,
  'Recovery':          '#5B90C8',
  'Endurance Run':     '#4A9D65',
  'Running Intervals': '#F04C24',
  'Tempo Run':         '#C9993A',
  'Specificity':       '#8A6CC0',
  'Race':              '#D4A843',
  'Other':             '#777',
};

const PHASE_COLOR = {
  'Base 1':     '#4A9D65',
  'Base 2':     '#4A9D65',
  'Build 1':    '#C97730',
  'Peak Block': '#C44040',
  'Deload 1':   '#5B7FA8',
  'Deload 2':   '#5B7FA8',
  'Deload 3':   '#5B7FA8',
  'Deload 4':   '#5B7FA8',
  'Taper 1':    '#8A6CC0',
  'Taper 2':    '#8A6CC0',
  'Taper 3':    '#8A6CC0',
  'Race Week':  '#D4A843',
};

// ── State ───────────────────────────────────────────────────────────────────
const activePlan = SIGNUP.plan; // locked at signup, no switching
let currentView = 'welcome';    // 'welcome' | 'week'
let selectedWeekNum = null;

// ── Helpers ─────────────────────────────────────────────────────────────────
function today() {
  const d = new Date();
  d.setHours(0, 0, 0, 0);
  return d;
}

function daysSinceStart() {
  return Math.floor((today() - START) / 86400000);
}

// Weeks past this are locked (content withheld until released)
function maxOpenWeek(plan) {
  return Math.min(plan.total_weeks, DATA.max_open_week || plan.total_weeks);
}

// Shared calendar week: week 1 started on program_start, clamped to 1..max open
function currentWeekNum(plan) {
  return Math.min(maxOpenWeek(plan), Math.max(1, Math.floor(daysSinceStart() / 7) + 1));
}

function isProgramComplete(plan) {
  return daysSinceStart() >= plan.total_weeks * 7;
}

function getCurrentWeek(plan) {
  return plan.weeks.find(w => w.week === currentWeekNum(plan));
}

// Fixed calendar dates come straight from the program data
function weekDatesLabel(n) {
  const plan = DATA.plans[SIGNUP.plan];
  const week = plan.weeks.find(w => w.week === n);
  return week ? week.dates_label : '';
}

// ── Render ───────────────────────────────────────────────────────────────────
function render() {
  const plan = DATA.plans[activePlan];
  const week = getCurrentWeek(plan);

  // Progress bar reflects the week being viewed, not just today's week
  let pct = 0;
  if (currentView === 'week') {
    const viewNum = selectedWeekNum || (week ? week.week : 0);
    pct = viewNum ? (viewNum / plan.total_weeks) * 100 : 0;
  }
  document.getElementById('progressFill').style.width = pct + '%';

  // Screens
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));

  if (currentView === 'week') {
    // Never render a locked week, even from a stale selection
    if (selectedWeekNum) selectedWeekNum = Math.min(selectedWeekNum, maxOpenWeek(plan));
    const viewWeek = selectedWeekNum
      ? plan.weeks.find(w => w.week === selectedWeekNum)
      : week;
    if (viewWeek) renderWeek(plan, viewWeek, week);
    else renderWelcome(plan, week);
  } else {
    renderWelcome(plan, week);
  }
}

// ── Welcome ──────────────────────────────────────────────────────────────────
function renderWelcome(plan, week) {
  const screen = document.getElementById('s-welcome');
  const complete = isProgramComplete(plan);

  if (complete) {
    screen.innerHTML = `
      <div class="label">${SIGNUP.first_name}, the block is done</div>
      <h1 class="launch__title">24 weeks.<br><em>Done.</em></h1>
      <p class="launch__sub">
        The full ${plan.total_weeks}-week program has wrapped.
        Every week stays open, so you can review any block whenever you need it.
      </p>
      <button class="btn" id="enterBtn">
        Review the plan
        <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    `;
  } else {
    const sat = week.days.find(d => d.day === 'Saturday');
    const keySub = (sat && !sat.is_rest)
      ? `Your anchor: ${sat.duration} ${sat.title}${sat.vert_gain_m ? ' with ' + sat.vert_gain_m + ' of vert' : ''}.`
      : `You're in the ${week.phase} block.`;

    screen.innerHTML = `
      <div class="label">${SIGNUP.first_name} &middot; Week ${week.week} of ${plan.total_weeks}, ${week.phase}</div>
      <h1 class="launch__title">Keep running<br>in <em>constant.</em></h1>
      <p class="launch__sub">
        ${weekDatesLabel(week.week)}. ${week.total_hours}.<br>
        ${keySub}
      </p>
      <button class="btn" id="enterBtn">
        See this week's plan
        <svg viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    `;
  }

  document.getElementById('enterBtn').addEventListener('click', () => {
    selectedWeekNum = null; // defaults to current week (pins at final week when complete)
    currentView = 'week';
    render();
    window.scrollTo(0, 0);
  });

  screen.classList.add('active');
}

// ── Week view ────────────────────────────────────────────────────────────────
function renderWeek(plan, week, currentWeek) {
  const screen = document.getElementById('s-week');
  const phaseColor = PHASE_COLOR[week.phase] || '#F04C24';
  const isThisWeek = week.week === currentWeekNum(plan) && !isProgramComplete(plan);

  let html = '';

  // Back link
  html += `<button class="back-link" id="backBtn" aria-label="Back to overview">&larr; Overview</button>`;

  // Phase color strip
  html += `<div class="phase-strip" style="background: linear-gradient(90deg, ${phaseColor} 0%, transparent 100%)"></div>`;

  // Week header
  html += `
    <div class="week__eyebrow">Week ${week.week} of ${plan.total_weeks}${isThisWeek ? ' &middot; This week' : ''}</div>
    <div class="week__phase">${week.phase}</div>
    <h2 class="week__title">${weekDatesLabel(week.week)}</h2>
    <div class="week__meta">
      <span class="week__dates">${week.is_deload ? 'Recovery week' : week.phase + ' block'}</span>
      <span class="week__hours">${week.total_hours}</span>
    </div>
    <div class="week__rule"></div>
    <div class="week__brief">${week.coach_brief.map(p => `<p>${p}</p>`).join('')}</div>
  `;

  // Block callout: training focus + block goal
  if (week.training_focus || week.block_goal) {
    html += '<div class="block-callout">';
    if (week.training_focus) {
      html += `<div class="block-row"><div class="block-key">Training Focus</div><div class="block-val">${week.training_focus}</div></div>`;
    }
    if (week.block_goal) {
      html += `<div class="block-row"><div class="block-key">Block Goal</div><div class="block-val">${week.block_goal}</div></div>`;
    }
    html += '</div>';
  }

  // Schedule: mark today's row, but only when viewing the current week
  const todayName = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][today().getDay()];
  html += '<div class="schedule">';
  for (const day of week.days) {
    html += renderDayRow(day, isThisWeek && day.day === todayName);
  }
  html += '</div>';

  // Reference: RPE scale + volume arc
  html += renderReference(plan, week);

  // Week navigation: past and current weeks open, locked weeks disabled
  const hasPrev = week.week > 1;
  const hasNext = week.week < maxOpenWeek(plan);
  const nextLocked = !hasNext && week.week < plan.total_weeks;
  html += `
    <div class="week-nav">
      <button class="week-nav__btn" id="prevWeekBtn" ${hasPrev ? '' : 'disabled'}>
        &larr; Week ${week.week - 1}
      </button>
      <span class="week-nav__label">Week ${week.week} of ${plan.total_weeks}</span>
      <button class="week-nav__btn" id="nextWeekBtn" ${hasNext ? '' : 'disabled'}>
        ${nextLocked ? `Week ${week.week + 1} &middot; Soon` : `Week ${week.week + 1} &rarr;`}
      </button>
    </div>
  `;

  screen.innerHTML = html;
  screen.classList.add('active');

  document.getElementById('backBtn').addEventListener('click', () => {
    currentView = 'welcome';
    render();
    window.scrollTo(0, 0);
  });

  if (hasPrev) {
    document.getElementById('prevWeekBtn').addEventListener('click', () => {
      selectedWeekNum = week.week - 1;
      render();
      window.scrollTo(0, 0);
    });
  }
  if (hasNext) {
    document.getElementById('nextWeekBtn').addEventListener('click', () => {
      selectedWeekNum = week.week + 1;
      render();
      window.scrollTo(0, 0);
    });
  }
}

function renderDayRow(day, isToday = false) {
  const todayBadge = `<div class="day-badge today-badge">Today</div>`;

  if (day.is_rest) {
    const isTravel = day.category === 'Rest' && (day.title.toLowerCase().includes('travel') || day.title.toLowerCase().includes('pre-race'));
    const label = isTravel ? day.title : 'Rest';
    return `
      <div class="day-row is-rest${isToday ? ' is-today' : ''}">
        <div class="day-name">${day.day.slice(0,3).toUpperCase()}</div>
        <div class="day-body">
          <div class="day-title">${label}</div>
        </div>
        ${isToday ? todayBadge : ''}
      </div>`;
  }

  const isKey  = day.day === 'Saturday' && day.category !== 'Race';
  const isRace = day.category === 'Race';
  const catColor = CAT_COLOR[day.category] || '#777';

  let rowClass = 'day-row';
  if (isKey)   rowClass += ' is-key';
  if (isRace)  rowClass += ' is-race';
  if (isToday) rowClass += ' is-today';

  let tags = '';
  if (catColor && day.category !== 'Race') {
    tags += `<span class="day-cat"><span class="day-cat-dot" style="background:${catColor}"></span>${day.category}</span>`;
  }
  if (day.vert_gain_m) tags += `<span class="day-tag day-vert">&uarr;&thinsp;${day.vert_gain_m}</span>`;
  if (day.full_pack)   tags += `<span class="day-tag day-pack">Full Pack</span>`;
  if (day.rpe && !day.title.toLowerCase().includes('rpe')) {
    tags += `<span class="day-cat" style="margin-left:4px"><span class="day-cat-dot" style="background:rgba(255,255,255,0.2)"></span>RPE ${day.rpe}</span>`;
  }

  // Today pill sits next to the key/race badge when they land on the same day
  const badge = (isKey  ? `<div class="day-badge key-badge">Key&nbsp;Session</div>`
              :  isRace ? `<div class="day-badge race-badge">Race&nbsp;Day</div>`
              :  '') + (isToday ? todayBadge : '');

  return `
    <div class="${rowClass}">
      <div class="day-name">${day.day.slice(0,3).toUpperCase()}</div>
      <div class="day-body">
        ${day.duration ? `<div class="day-dur">${day.duration}</div>` : ''}
        <div class="day-title">${day.title}</div>
        ${tags ? `<div class="day-tags">${tags}</div>` : ''}
      </div>
      ${badge}
    </div>`;
}

// ── Reference panel ──────────────────────────────────────────────────────────
function renderReference(plan, week) {
  // RPE scale
  let rpeRows = '';
  for (const r of DATA.rpe_scale) {
    rpeRows += `
      <div class="rpe-row">
        <div class="rpe-badge">RPE ${r.rpe}</div>
        <div>
          <div class="rpe-label">${r.label}</div>
          <div class="rpe-detail">${r.sensation}</div>
        </div>
      </div>`;
  }

  // Volume arc: all weeks as bars, current highlighted
  const hours = plan.weeks.map(w => parseFloat(w.total_hours));
  const maxH  = Math.max(...hours);
  let bars = '';
  for (let i = 0; i < plan.weeks.length; i++) {
    const w   = plan.weeks[i];
    const pct = (parseFloat(w.total_hours) / maxH) * 100;
    const cls = ['vol-bar',
      w.week === week.week ? 'is-current' : '',
      w.is_deload ? 'is-deload' : '',
    ].filter(Boolean).join(' ');
    bars += `<div class="${cls}" style="height:${pct}%" title="Week ${w.week}: ${w.total_hours}"></div>`;
  }

  return `
    <div class="ref-section">
      <div class="ref-heading">RPE Scale &middot; Perceived Effort</div>
      <div class="rpe-table">${rpeRows}</div>
      <div class="ref-heading">Volume &middot; All ${plan.total_weeks} Weeks</div>
      <div class="vol-chart">${bars}</div>
      <div class="vol-legend">
        <span>Week 1</span>
        <span class="center">${week.total_hours} this week</span>
        <span>Week ${plan.total_weeks}</span>
      </div>
    </div>`;
}

// ── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', render);
</script>
@endpush
