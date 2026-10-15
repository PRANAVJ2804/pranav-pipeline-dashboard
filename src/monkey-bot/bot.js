(function () {
  'use strict';
  const g = id => document.getElementById(id);
  const E = s => esc(String(s == null ? '' : s));

  /* ================= text helpers ================= */
  function norm(s) {
    return String(s == null ? '' : s).toLowerCase()
      .replace(/[‘’´`]/g, "'").replace(/'s\b/g, '').replace(/'/g, '')
      .replace(/([a-z])-(?=[a-z])/g, '$1 ')
      .replace(/[^a-z0-9@.+#\/:\-]+/g, ' ').replace(/\s+/g, ' ').trim();
  }
  function toks(s) { return norm(s).split(' ').map(t => t.replace(/^[.:\/\-]+|[.:\/\-]+$/g, '')).filter(Boolean); }
  function dist(a, b, mx) {
    const la = a.length, lb = b.length;
    if (Math.abs(la - lb) > mx) return mx + 1;
    let p2 = null, p1 = new Array(lb + 1), cur;
    for (let j = 0; j <= lb; j++) p1[j] = j;
    for (let i = 1; i <= la; i++) {
      cur = new Array(lb + 1); cur[0] = i; let rm = i;
      for (let j = 1; j <= lb; j++) {
        const c = a.charCodeAt(i - 1) === b.charCodeAt(j - 1) ? 0 : 1;
        let v = Math.min(p1[j] + 1, cur[j - 1] + 1, p1[j - 1] + c);
        if (p2 && i > 1 && j > 1 && a.charCodeAt(i - 1) === b.charCodeAt(j - 2) && a.charCodeAt(i - 2) === b.charCodeAt(j - 1)) v = Math.min(v, p2[j - 2] + 1);
        cur[j] = v; if (v < rm) rm = v;
      }
      if (rm > mx) return mx + 1;
      p2 = p1; p1 = cur;
    }
    return p1[lb];
  }
  const tol = n => n <= 3 ? 0 : n <= 6 ? 1 : 2;
  const near = (a, b) => { if (a === b) return true; const t = tol(Math.max(a.length, b.length)); return t > 0 && dist(a, b, t) <= t; };
  const pad = n => String(n).padStart(2, '0');
  const MONTHS = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
  const WDAYS = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const NUMW = {one: 1, first: 1, '1st': 1, two: 2, second: 2, '2nd': 2, three: 3, third: 3, '3rd': 3, four: 4, fourth: 4, '4th': 4, five: 5, fifth: 5, '5th': 5, six: 6, sixth: 6, '6th': 6, seven: 7, eight: 8};
  const numOf = t => /^\d+$/.test(t) ? +t : (NUMW[t] || 0);

  /* ================= vocabulary (typo correction) ================= */
  const LEX = {
    status: 'status stage state standing progress stat stages statuses',
    created: 'created creation added opened createdon started joined create',
    when: 'when wen whn whenn',
    who: 'who whom whos hu',
    last: 'last latest recent recently lately previous final',
    worked: 'worked work handled handle handling touched updated modified edited changed commented contacted called spoke followed managed owner owned',
    university: 'university universities uni unis college colleges univ univs universty univercity unversity',
    program: 'program programs programme programmes course courses prog progs',
    batch: 'batch batches intake intakes',
    deal: 'deal deals student students record records candidate candidates people',
    lead: 'lead leads',
    count: 'count counts total totals',
    many: 'many',
    how: 'how hw',
    set: 'set make change update mark edit modify put turn convert move shift fix correct changing updating making marking setting mak mke mrk chnge chage chang updte updat edt',
    paid: 'paid payed payd cleared',
    notpaid: 'unpaid notpaid nonpaid',
    sem: 'sem semester semesters sems semister semestor semestr',
    follow: 'follow followup followups',
    overdue: 'overdue stale delayed late overdues',
    phone: 'phone mobile cell whatsapp contact',
    email: 'email mail emailid',
    name: 'name names',
    team: 'team teams',
    mode: 'mode payment',
    roll: 'roll rollno',
    reference: 'reference references referral',
    docs: 'document documents docs doc',
    active: 'active open ongoing',
    closed: 'closed inactive',
    each: 'each every per wise',
    list: 'list show display give tell',
    help: 'help helps',
    cancel: 'cancel stop abort',
    most: 'most top highest biggest largest maximum max',
    detail: 'detail details info information summary',
    current: 'current present',
    next: 'next upcoming',
    admission: 'admission',
    registration: 'registration',
    payment: 'payment',
    comment: 'comment comments remark remarks note notes',
    today: 'today',
    tomorrow: 'tomorrow tmrw tomorow tommorow tomorrow'
  };
  const LEXV = {}, LEXALL = [];
  Object.keys(LEX).forEach(c => LEX[c].split(' ').forEach(v => { LEXV[v] = c; if (v.length >= 4) LEXALL.push([v, c]); }));
  const STOP = new Set('a an the of for to is was are am in on at by me my i you your please pls plz can could would tell show give get what whats which his her him he she it its this that those these and or with from about has have had do does did kindly let know need want check find see there their them any all also just only into out up'.split(' '));
  const CORE = new Set(['a', 'an', 'the', 'of', 'for', 'to', 'is', 'was', 'in', 'on', 'at', 'by', 'and', 'or', 'with', 'from', 'status', 'stage', 'sem', 'semester', 'paid', 'university', 'program', 'deal', 'student', 'how', 'many', 'what', 'who', 'when', 'which', 'tell', 'show', 'give', 'me', 'my', 'please']);

  let DT = null, DTn = -1;
  function dataTokens() {
    if (DT && DTn === allRows.length) return DT;
    DT = new Set(); DTn = allRows.length;
    allRows.forEach(r => { toks(r._NAME || '').forEach(t => DT.add(t)); });
    cols.forEach(c => { if (c.options) c.options.forEach(o => toks(o.name).forEach(t => DT.add(t))); });
    ['uni', 'program', 'batch', 'mode', 'team', 'lead', 'docstat'].forEach(ro => { const c = colByRole(ro); if (c) allRows.forEach(r => toks(r[c.key] || '').forEach(t => DT.add(t))); });
    return DT;
  }
  function fixTok(t) {
    if (LEXV[t]) return LEXV[t];
    if (t.length < 4 || /\d/.test(t) || dataTokens().has(t)) return t;
    let best = null, bd = 9;
    for (let i = 0; i < LEXALL.length; i++) {
      const v = LEXALL[i][0];
      if (Math.abs(v.length - t.length) > 2) continue;
      const tl = tol(Math.max(v.length, t.length));
      if (tl === 0) continue;
      const d = dist(t, v, tl);
      if (d <= tl && d < bd) { bd = d; best = LEXALL[i][1]; }
    }
    return best || t;
  }

  /* ================= natural dates ================= */
  function nlDate(text) {
    const s = ' ' + norm(text) + ' ';
    const now = new Date(); now.setHours(0, 0, 0, 0);
    let d = null;
    const T = toks(text).map(t => (t.length >= 5 && near(t, 'tomorrow')) ? 'tomorrow' : t);
    const s2 = ' ' + T.join(' ') + ' ';
    let m;
    if ((m = /(\d{4})-(\d{1,2})-(\d{1,2})/.exec(s))) d = new Date(+m[1], +m[2] - 1, +m[3]);
    else if ((m = /\b(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{2,4})\b/.exec(s))) d = new Date(+m[3] < 100 ? 2000 + +m[3] : +m[3], +m[2] - 1, +m[1]);
    else if ((m = /\b(\d{1,2})[\/\-](\d{1,2})\b/.exec(s)) && +m[1] >= 1 && +m[1] <= 31 && +m[2] >= 1 && +m[2] <= 12) { d = new Date(now.getFullYear(), +m[2] - 1, +m[1]); if (d < now) d = new Date(now.getFullYear() + 1, +m[2] - 1, +m[1]); }
    else if (/\bday after tomorrow\b/.test(s2) || /\bovermorrow\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 2); }
    else if (/\btomorrow\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 1); }
    else if (/\btoday\b|\btonight\b/.test(s2)) d = new Date(now);
    else if ((m = /\bin (\d+|a|one|two|three|four|five|six|seven|ten) (day|days|week|weeks|month|months)\b/.exec(s2))) {
      const n = /^\d+$/.test(m[1]) ? +m[1] : (m[1] === 'a' ? 1 : (NUMW[m[1]] || (m[1] === 'ten' ? 10 : 1)));
      d = new Date(now);
      if (/week/.test(m[2])) d.setDate(d.getDate() + 7 * n); else if (/month/.test(m[2])) d.setMonth(d.getMonth() + n); else d.setDate(d.getDate() + n);
    } else if (/\bnext week\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 7); }
    else {
      for (let i = 0; i < 7 && !d; i++) {
        const w = WDAYS[i], re = new RegExp('\\b(?:next |this |coming )?(' + w + '|' + w.slice(0, 3) + ')\\b');
        const tk = T.find(t => t.length >= 3 && (t === w.slice(0, 3) || (t.length >= 5 && near(t, w))));
        if (tk) { d = new Date(now); let add = (i - d.getDay() + 7) % 7; if (add === 0 || /\bnext\b/.test(s2)) add = add === 0 ? 7 : add + (/\bnext\b/.test(s2) && add < 7 ? 0 : 0); d.setDate(d.getDate() + add); }
      }
      if (!d) {
        for (let k = 0; k < T.length && !d; k++) {
          const t = T[k];
          let mi = -1;
          for (let q = 0; q < 12; q++) { const mn = MONTHS[q]; if (t.length >= 3 && (mn.indexOf(t) === 0 || (t.length >= 5 && near(t, mn)) || (t === 'sept' && q === 8))) { mi = q; break; } }
          if (mi < 0 || t === 'mar' && false) continue;
          const nb = x => { const mm = /^(\d{1,2})(?:st|nd|rd|th)?$/.exec(x || ''); return mm ? +mm[1] : 0; };
          let day = nb(T[k - 1]) || nb(T[k + 1]);
          let yr = 0; const y = /^(20\d\d)$/.exec(T[k + 2] || '') || /^(20\d\d)$/.exec(T[k + 1] || '') || /^(20\d\d)$/.exec(T[k - 2] || '') || /^(20\d\d)$/.exec(T[k - 1] || '');
          if (y) yr = +y[1];
          if (day >= 1 && day <= 31) {
            let yy = yr || now.getFullYear(); d = new Date(yy, mi, day);
            if (!yr && d < now) d = new Date(yy + 1, mi, day);
          }
        }
      }
      if (!d && (m = /\b(\d{1,2})(?:st|nd|rd|th)?\b/.exec(s2)) && /\b(?:on|by|date)\b/.test(s2) && +m[1] >= 1 && +m[1] <= 31) {
        d = new Date(now.getFullYear(), now.getMonth(), +m[1]); if (d < now) d.setMonth(d.getMonth() + 1);
      }
    }
    if (!d || isNaN(d)) return null;
    let hh = 11, mm = 0, tm;
    if ((tm = /\b(\d{1,2})(?::(\d{2}))?\s*(am|pm)\b/.exec(s))) { hh = +tm[1] % 12 + (tm[3] === 'pm' ? 12 : 0); mm = +(tm[2] || 0); }
    else if ((tm = /\b([01]?\d|2[0-3]):([0-5]\d)\b/.exec(s))) { hh = +tm[1]; mm = +tm[2]; }
    else if (/\bmorning\b/.test(s2)) hh = 10; else if (/\bnoon\b/.test(s2)) hh = 12; else if (/\bafternoon\b/.test(s2)) hh = 15; else if (/\bevening\b/.test(s2)) hh = 18;
    d.setHours(hh, mm, 0, 0);
    return d;
  }
  const dIso = d => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
  const dIsoT = d => dIso(d) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
  const dNice = (d, withTime) => d.getDate() + ' ' + MONTHS[d.getMonth()].slice(0, 3).replace(/^./, c => c.toUpperCase()) + ' ' + d.getFullYear() + (withTime ? ', ' + ((d.getHours() % 12) || 12) + ':' + pad(d.getMinutes()) + ' ' + (d.getHours() >= 12 ? 'pm' : 'am') : '');
  function parseStored(s) {
    const m = /(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2}))?/.exec(String(s || ''));
    return m ? new Date(+m[1], +m[2] - 1, +m[3], +(m[4] || 0), +(m[5] || 0)) : null;
  }
  function niceStored(s) { const d = parseStored(s); if (!d) return s ? String(s) : ''; return dNice(d, /\d{2}:\d{2}/.test(String(s))); }
  function agoDays(d) {
    const n = Math.round((Date.now() - d.getTime()) / 864e5);
    return n <= 0 ? 'today' : n === 1 ? 'yesterday' : n < 60 ? n + ' days ago' : n < 730 ? Math.round(n / 30) + ' months ago' : Math.round(n / 365) + ' years ago';
  }

  /* ================= data access ================= */
  const colsEd = () => cols.filter(c => c.editable && !(SCOPE && c.role === 'team'));
  const semCols = () => cols.filter(c => /^semstat\d$/.test(c.role || '')).sort((a, b) => +a.role.slice(7) - +b.role.slice(7));
  const nameOf = r => r._NAME || r.TITLE || ('Deal ' + r.ID);
  const stageOf = r => r.STAGE_ID || '';
  function val(r, role) { const c = colByRole(role); return c ? String(r[c.key] || '') : ''; }
  function lastCm(r) {
    if (!cmSrc) return null;
    const text = String(r[cmSrc] || '').replace(/\r/g, '');
    if (!text.trim()) return null;
    const re = /\s*\|{1,2}\s*(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}(?::\d{2})?\s*(?:[ap]m)?)\s*(?:\|{1,2}[ \t]*([^\r\n]*))?/gi;
    let last = 0, m, best = null;
    while ((m = re.exec(text))) {
      const c = text.slice(last, m.index).split('\n').filter(l => !/^\s*-{3,}\s*$/.test(l)).join(' ').replace(/\s+/g, ' ').trim();
      last = m.index + m[0].length;
      const ms = tsMs(m[1]);
      if (!best || ms > best.ms) best = {c: c, at: m[1].replace(/\s+/g, ' ').trim(), ms: ms, by: (m[2] || '').trim()};
    }
    return best;
  }
  let ACT = null, ACTat = 0;
  async function activity() {
    if (ACT && Date.now() - ACTat < 45000) return ACT;
    try {
      const r = await fetch('api.php?action=activity', {credentials: 'same-origin'});
      const j = await r.json();
      ACT = j && j.ok ? j.items : []; ACTat = Date.now();
    } catch (e) { ACT = ACT || []; }
    return ACT;
  }
  function payState(r, n) {
    const c = cols.find(x => x.role === 'semstat' + n); if (!c) return '';
    const v = String(r[c.key] || '').trim().toLowerCase();
    if (!v || /not\s*paid|unpaid|pending|due/.test(v)) return 'notpaid';
    if (/paid|done|yes/.test(v)) return 'paid';
    return 'other';
  }

  /* ================= entity: which deal? ================= */
  let NI = null, NIn = -1;
  function nameIndex() {
    if (NI && NIn === allRows.length) return NI;
    NIn = allRows.length;
    NI = allRows.map(r => ({r: r, t: toks(nameOf(r)).filter(t => t.length >= 2)})).filter(x => x.t.length);
    return NI;
  }
  function findDeals(T, C) {
    const out = {list: [], how: ''};
    const joined = T.join(' ');
    let m;
    const em = T.find(t => t.indexOf('@') > 0);
    if (em) { const hit = allRows.filter(r => String(r._EMAIL || '').toLowerCase().split(/[,\s;]+/).indexOf(em) >= 0); if (hit.length) { out.list = hit.map(r => ({r: r, s: 9})); out.how = 'email'; return out; } }
    const ph = T.map(t => t.replace(/[^\d]/g, '')).find((d, i) => d.length >= 8 && /^[+\d\-\s]+$/.test(T[i]));
    if (ph) { const key = ph.slice(-10); const hit = allRows.filter(r => String(r._PHONE || '').replace(/\D/g, '').slice(-10) === key); if (hit.length) { out.list = hit.map(r => ({r: r, s: 9})); out.how = 'phone'; return out; } }
    const idm = /(?:\bdeal|\bid|#)\s*(?:no\.?|number)?\s*#?\s*(\d{2,9})\b/.exec(joined) || /#(\d{2,9})\b/.exec(joined);
    let idv = idm ? idm[1] : null;
    if (!idv) { const bare = T.find((t, i) => /^\d{3,9}$/.test(t) && !/^(19|20)\d\d$/.test(t) && !(T[i - 1] && /^(sem|semester|sems)$/.test(C[i - 1] === 'sem' ? 'sem' : T[i - 1]))); if (bare) idv = bare; }
    if (idv) { const r = allRows.find(x => String(x.ID) === idv); if (r) { out.list = [{r: r, s: 9}]; out.how = 'id'; return out; } }
    // by name
    const dt = dataTokens(); const nameTok = new Set(); nameIndex().forEach(x => x.t.forEach(t => nameTok.add(t)));
    const cand = [];
    T.forEach((t, i) => {
      if (/^\d+$/.test(t) || t.length < 2) return;
      if (STOP.has(t)) return;
      const lexed = LEXV[t] || C[i] !== t;
      if (lexed && !(nameTok.has(t) && !CORE.has(t))) return;
      cand.push(t);
    });
    if (!cand.length) return out;
    const res = [];
    nameIndex().forEach(x => {
      let sum = 0, cnt = 0; const usedQ = {};
      x.t.forEach(nt => {
        let best = 0, bj = -1;
        cand.forEach((q, j) => {
          if (usedQ[j]) return;
          let s = 0;
          if (q === nt) s = 1;
          else if (q.length >= 3 && nt.length >= q.length && nt.indexOf(q) === 0 && !/\d$/.test(q)) s = 0.8;
          else if (q.length >= 4 && nt.length >= 4 && near(q, nt)) s = 0.85;
          if (s > best) { best = s; bj = j; }
        });
        if (best >= 0.8) { sum += best; cnt++; usedQ[bj] = 1; }
      });
      if (cnt && sum >= 0.8) res.push({r: x.r, s: sum, c: cnt, n: x.t.length, full: cnt === x.t.length});
    });
    if (!res.length) return out;
    res.sort((a, b) => b.c - a.c || b.s - a.s || (b.full ? 1 : 0) - (a.full ? 1 : 0));
    const top = res[0];
    out.list = res.filter(x => x.c === top.c && x.s >= top.s - 0.15);
    out.how = 'name';
    // exact full-name hit wins
    const full = out.list.filter(x => x.full && x.n === top.c);
    if (full.length === 1 && out.list.length > 1 && full[0].s >= out.list[0].s) out.list = full;
    return out;
  }

  /* ================= entity: filters for counting ================= */
  const GENERIC = new Set(['university', 'universities', 'college', 'institute', 'of', 'the', 'and', 'univ', 'school', 'for']);
  let VD = null, VDn = -1;
  function valueDict() {
    if (VD && VDn === allRows.length) return VD;
    VDn = allRows.length; VD = [];
    ['uni', 'program', 'batch', 'mode', 'team', 'lead', 'docstat', 'stage', 'sem'].forEach(ro => {
      const c = colByRole(ro); if (!c) return;
      const set = new Set();
      allRows.forEach(r => { const v = String(ro === 'uni' ? uniOf(r) : r[c.key] || '').trim(); if (v) set.add(v); });
      if (c.options) c.options.forEach(o => set.add(o.name));
      set.forEach(v => {
        const all = toks(v); let dts = all.filter(t => !GENERIC.has(t));
        if (!dts.length) dts = all;
        if (!dts.length) return;
        VD.push({role: ro, col: c, v: v, t: dts, all: all});
      });
    });
    return VD;
  }
  function STAGEWORDS() { const o = {}; valueDict().forEach(vd => { if (vd.role === 'stage') vd.t.forEach(t => { o[t] = 1; }); }); return o; }
  function matchValues(T, C, skip) {
    const q = T.map((t, i) => ({t: t, i: i})).filter(x => !skip[x.i] && !/^\d+$/.test(x.t) && !STOP.has(x.t) && !(x.t === 'no' && !/^(univers|program|batch|team|mode|stage|status)/.test(T[x.i + 1] || '')) && !(LEXV[x.t] && x.t !== 'active' && !(STAGEWORDS()[x.t] && /^(stage|stages|status)$/.test(T[x.i + 1] || T[x.i - 1] || ''))) || (!skip[x.i] && /^\d+$/.test(x.t)));
    const hits = [];
    const qtoks = T.filter((t, i) => !skip[i]);
    valueDict().forEach(vd => {
      if (vd.role === 'sem' && !/^\d/.test(vd.t.join(''))) { /* 'sem 1' handled below */ }
      const used = [];
      let ok = true;
      for (let k = 0; k < vd.t.length; k++) {
        const nt = vd.t[k]; let f = -1;
        for (let j = 0; j < q.length; j++) {
          if (used.indexOf(j) >= 0) continue;
          const qt = q[j].t;
          if (qt === nt || (qt.length >= 6 && nt.length >= 6 && near(qt, nt)) || (qt.length >= 4 && nt.length > qt.length && nt.indexOf(qt) === 0 && nt.length - qt.length <= 3)) { f = j; break; }
        }
        if (f < 0) { ok = false; break; }
        used.push(f);
      }
      if (ok) hits.push({vd: vd, idx: used.map(j => q[j].i), n: vd.t.length, len: vd.v.length, exact: vd.all.every(x => qtoks.indexOf(x) >= 0)});
    });
    // loose: a single distinctive word that is part of a longer value ("amity" -> Amity University Online)
    valueDict().forEach(vd => {
      q.forEach(x => {
        if (/^\d+$/.test(x.t) || x.t.length < 3 || vd.role === 'stage' || vd.role === 'sem' || vd.role === 'lead' || vd.role === 'docstat' || vd.role === 'mode' || /^(not|online|other|new|general|the|all|none)$/.test(x.t)) return;
        if (!vd.t.some(nt => nt === x.t || (x.t.length >= 6 && nt.length >= 6 && near(x.t, nt)) || (x.t.length >= 4 && nt.length > x.t.length && nt.indexOf(x.t) === 0 && nt.length - x.t.length <= 3))) return;
        if (hits.some(h => h.vd === vd && h.idx.indexOf(x.i) >= 0)) return;
        hits.push({vd: vd, idx: [x.i], n: 1, len: vd.v.length, exact: false});
      });
    });
    // acronyms: "muj" = Manipal University Jaipur, "smu" = Sikkim Manipal University
    valueDict().forEach(vd => {
      if (vd.all.length < 2) return;
      const ac = vd.all.map(x => x[0]).join('');
      if (ac.length < 3) return;
      q.forEach(x => { if (x.t === ac) hits.push({vd: vd, idx: [x.i], n: 1, len: vd.v.length, exact: true}); });
    });
    // "semester" on its own is the payment mode "Semester" (like Lumpsum / Annual), unless it is about a semester number or payment status
    {
      const cs = C.join(' ');
      const modeSem = valueDict().find(vd => vd.role === 'mode' && /^sem(ester)?([ -]?wise)?$/i.test(vd.v.trim()));
      if (modeSem && !/\bsem\w*\s*\d/.test(cs) && !/\b\d(st|nd|rd|th)?\s*sem/.test(cs) && !/\b(paid|payed|notpaid|not paid|unpaid|due|current|fees?|status)\b/.test(cs) && !hits.some(h => h.vd.role === 'mode')) {
        const sk = skip || {};
        const i = T.findIndex((t, k) => /^semesters?$/.test(t) && !sk[k]);
        if (i >= 0) {
          const nxt = T[i + 1] || '', prv = T[i - 1] || '';
          if (!/^(\d+|number|no)$/.test(nxt) && !/^(which|each|per|by|this|last|next|previous|every|all|what|whats)$/.test(prv)) hits.push({vd: modeSem, idx: [i], n: 1, len: modeSem.v.length, exact: true});
        }
      }
    }
    hits.sort((a, b) => (b.exact ? 1 : 0) - (a.exact ? 1 : 0) || b.n - a.n || b.len - a.len);
    const taken = {}, out = [];
    hits.forEach(h => {
      if (h.vd.role === 'sem') return;
      const clash = h.idx.filter(i => taken[i]);
      if (clash.length) {
        const t0 = taken[clash[0]];
        // an ambiguous short word (e.g. "manipal") may belong to several values of the same field: keep them all (OR)
        if (clash.length === h.idx.length && !h.exact && t0.vd.role === h.vd.role && !t0.exact && t0.n === h.n && h.idx.every(i => taken[i] === t0)) out.push(h);
        return;
      }
      h.idx.forEach(i => { taken[i] = h; });
      out.push(h);
    });
    return out;
  }

  /* ================= counting & lists ================= */
  function valueDictHas(t) { return valueDict().some(vd => vd.t.indexOf(t) >= 0 || vd.all.indexOf(t) >= 0); }
  function describeFilter(f) { return f.label; }
  function parseFilters(T, C, skip) {
    const F = [];
    const s = C.join(' ');
    // semester payment
    const semM = /\bsem\w*\s*(\d)\b/.exec(s) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(s);
    const wordNum = (() => { const m = /\bsem\w*\s*(one|two|three|four|five|six)\b/.exec(s) || /\b(first|second|third|fourth|fifth|sixth)\s*sem/.exec(s); return m ? NUMW[m[1]] : 0; })();
    const semN = semM ? +semM[1] : wordNum;
    const wantPaid = /\bpaid\b/.test(s), wantNot = /\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bdue\b|\bunpaid\b/.test(s);
    if (semN && (wantPaid || wantNot)) {
      const st = wantNot ? 'notpaid' : 'paid';
      F.push({label: 'Sem ' + semN + ' ' + (st === 'paid' ? 'paid' : 'not paid'), test: r => payState(r, semN) === st});
    } else if (semN) {
      const c = colByRole('sem');
      if (c) F.push({label: 'current semester Sem ' + semN, test: r => new RegExp('(^|\\D)' + semN + '(\\D|$)').test(String(r[c.key] || ''))});
    } else if (wantPaid || wantNot) {
      const st = wantNot ? 'notpaid' : 'paid';
      const c = colByRole('sem');
      F.push({label: (st === 'paid' ? 'paid' : 'not paid') + ' for the current semester', test: r => { const n = semOf(r); return n ? payState(r, n) === st : false; }});
    }
    if (/\boverdue\b/.test(s) || /\bfollow\b.*\b(overdue)\b/.test(s)) F.push({label: 'follow-up overdue', test: r => isStale(r)});
    if (/\bnot contacted\b|\buntouched\b|\bnever contacted\b|\bno comment\b/.test(s) || /\bnot (?:yet )?(?:called|worked|touched|contacted)\b/.test(s)) F.push({label: 'not contacted yet', test: r => isNotContacted(r)});
    if (/\bactive\b/.test(s)) F.push({label: 'active', test: r => !isClosed(r)});
    else if (/\bclosed\b/.test(s)) F.push({label: 'closed', test: r => isClosed(r)});
    if (/\balumni\b/.test(s)) F.push({label: 'alumni', test: r => isAlumni(r)});
    if (/\bre ?reg\w*|\bre registration\b/.test(norm(T.join(' ')))) {
      const want = /\bdone\b|\bcompleted?\b|\bregistered\b/.test(s) ? RR.R : /\bpending\b|\boverdue\b|\blate\b/.test(s) ? RR.O : null;
      if (want) { F.push({label: 're-registration ' + want, test: r => rrStatus(r) === want}); T.forEach((t, i) => { if (/^(pending|done|completed|complete|registered|overdue|late)$/.test(t) || C[i] === 'overdue') skip = Object.assign(skip || {}, {[i]: 1}); }); }
    }
    const hits = matchValues(T, C, skip || {});
    F.consumed = {}; F.roles = {};
    hits.forEach(h => { h.idx.forEach(i => { F.consumed[i] = 1; }); F.roles[h.vd.role] = 1; });
    const byRole = {};
    hits.forEach(h => { const a = (byRole[h.vd.role] = byRole[h.vd.role] || {col: h.vd.col, vals: []}); if (a.vals.indexOf(h.vd.v) < 0) a.vals.push(h.vd.v); });
    Object.keys(byRole).forEach(ro => { const c = byRole[ro].col, vals = byRole[ro].vals, isU = ro === 'uni'; F.push({label: vals.join(' + '), role: ro, test: r => vals.indexOf(String(isU ? uniOf(r) : r[c.key] || '').trim()) >= 0}); });
    return F;
  }
  const NOUN_ROLE = {university: 'uni', program: 'program', batch: 'batch', team: 'team', status: 'stage', mode: 'mode', lead: 'lead', docs: 'docstat'};
  const NOUN_LABEL = {uni: ['university', 'universities'], program: ['program', 'programs'], batch: ['batch', 'batches'], team: ['team', 'teams'], stage: ['stage', 'stages'], mode: ['payment mode', 'payment modes'], lead: ['lead status', 'lead statuses'], docstat: ['document status', 'document statuses']};
  function groupBy(list, role) {
    const c = colByRole(role), m = {};
    list.forEach(r => { const v = role === 'uni' ? uniOf(r) : String(r[c.key] || '').trim() || '(empty)'; m[v] = (m[v] || 0) + 1; });
    return Object.keys(m).map(k => ({k: k, n: m[k]})).sort((a, b) => b.n - a.n || a.k.localeCompare(b.k));
  }
  function filtText(F) { return F.length ? ' (' + F.map(describeFilter).join(' + ') + ')' : ''; }
  function doCount(U) {
    const F = parseFilters(U.T, U.C, U.skip);
    const list = allRows.filter(r => F.every(f => f.test(r)));
    const s = U.s;
    let role = null;
    Object.keys(NOUN_ROLE).forEach(k => { if (!role && new RegExp('\\b' + k + '\\b').test(s) && !(k === 'lead' && /\blead status\b/.test(s) === false && /\bleads?\b/.test(s))) role = NOUN_ROLE[k]; });
    if (role === 'lead' && !/\blead status\b/.test(s)) role = null;
    const roleKey = {uni: 'uni', program: 'program', batch: 'batch', team: 'team', stage: 'stage', mode: 'mode', lead: 'lead', docstat: 'docstat'};
    if (role && F.roles && F.roles[role]) role = null;
    // a word after in/of/for/under that matched nothing: say so instead of counting everything
    for (let i = 0; i < U.T.length - 1; i++) {
      if (/^(in|of|for|under|from|at|named|called)$/.test(U.T[i])) {
        const t = U.T[i + 1];
        if (t && t.length >= 3 && !STOP.has(t) && !LEXV[t] && U.C[i + 1] === t && !/^\d+$/.test(t) && !(F.consumed && F.consumed[i + 1]) && !/^(total|all|the|each|every|not)$/.test(t)) {
          let nearest = null, bd = 9;
          valueDict().forEach(vd => vd.t.forEach(x => { if (x.length >= 4) { const d = dist(t, x, 3); if (d < bd) { bd = d; nearest = vd.v; } } }));
          return {h: 'Hmm, I couldn’t find anything called <b>“' + E(t) + '”</b> in universities, programs, batches or stages. 🙈' + (nearest && bd <= 3 ? '<br>Did you mean <b>' + E(nearest) + '</b>?' : ''), mood: 'conf', chips: nearest && bd <= 3 ? ['How many deals in ' + nearest + '?'] : baseChips()};
        }
      }
    }
    if (role === 'stage' && /\bcount\b|\bhow many deals\b/.test(s) === false && !/\bstages?\b|\bstatus(es)?\b/.test(s)) role = null;
    const each = /\beach\b|\bwise\b/.test(s) || /\bby\b/.test(U.raw);
    const least = /\b(least|fewest|lowest|smallest|minimum)\b/.test(U.raw.toLowerCase());
    const most = /\bmost\b/.test(s) || least;
    if (!allRows.length) return {t: 'I do not see any deals loaded yet. Press “Refresh data” in the account menu once and ask me again.', mood: 'sad'};
    // distinct count of a dimension
    if (role && !each && !most) {
      const grp = groupBy(list.filter(r => role !== 'uni' || uniOf(r) !== UNASSIGNED), role).filter(x => x.k !== '(empty)');
      const nm = NOUN_LABEL[role];
      let h = '<b>' + fmtN(grp.length) + '</b> ' + (grp.length === 1 ? nm[0] : nm[1]) + (F.length ? filtText(F) : '') + ' with deals.';
      if (grp.length) h += list_(grp.slice(0, 12).map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k + (F.length ? filtText(F) : '')) + '</span>')) + (grp.length > 12 ? '<div class="mbs">…and ' + (grp.length - 12) + ' more.</div>' : '');
      return {h: h, mood: 'happy', ctx: {role: role}};
    }
    if (role && (each || most)) {
      const grp = groupBy(list, role).filter(x => !least || x.k !== '(empty)' && x.k !== UNASSIGNED); if (least) grp.sort((a, b) => a.n - b.n || a.k.localeCompare(b.k));
      const nm = NOUN_LABEL[role];
      const top = most ? grp.slice(0, 1) : grp.slice(0, 15);
      let h = most ? 'The ' + nm[0] + ' with the ' + (least ? 'fewest' : 'most') + ' deals' + filtText(F) + ' is <b>' + E(top[0] ? top[0].k : '—') + '</b> with <b>' + (top[0] ? numLink(groupIds(list, role, top[0].k), top[0].k) : '0') + '</b> deals.' : 'Deals by ' + nm[0] + filtText(F) + ':';
      if (!most) h += list_(top.map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k + (F.length ? filtText(F) : '')) + '</span>')) + (grp.length > 15 ? '<div class="mbs">…and ' + (grp.length - 15) + ' more.</div>' : '');
      else if (grp.length > 1) h += list_(grp.slice(1, 5).map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k) + '</span>')) + '<div class="mbs">Runners up above.</div>';
      return {h: h, mood: 'happy'};
    }
    const n = list.length;
    let h;
    const left = U.T.filter((t, i) => !(F.consumed && F.consumed[i]) && !(U.skip && U.skip[i]) && !STOP.has(t) && !LEXV[t] && U.C[i] === t && t.length >= 3 && !/^\d+$/.test(t) && !valueDictHas(t) && !/^(overdue|stale|active|closed|alumni|paid|unpaid|due|contacted|touched|worked|called|untouched|created|added|joined|today|yesterday|tomorrow|week|month|year|monday|tuesday|wednesday|thursday|friday|saturday|sunday|number|numbers|total|count|many|much|show|list)$/.test(t));
    const warn = left.length && F.length ? '<div class="mbs">⚠️ I didn’t understand “' + E(left.slice(0, 3).join(' ')) + '”, so it is <b>not</b> in this count.</div>' : '';
    if (!F.length) h = 'There are <b>' + numLink(list, 'All deals') + '</b> deals (students) in total' + (SCOPE ? ' for your team' : '') + '.';
    else h = '<b>' + numLink(list, F.map(describeFilter).join(' + ')) + '</b> deal' + (n === 1 ? '' : 's') + filtText(F) + '.';
    const ex = F.length && n && n <= 5 ? list_(list.map(r => '<a href="#" data-deal="' + E(r.ID) + '">' + E(nameOf(r)) + '</a> <span class="mbs">#' + E(r.ID) + '</span>')) : '';
    return {h: h + warn + ex, mood: n ? 'happy' : 'conf', ctx: {}};
  }
  /* clickable numbers: open exactly those deals in the table */
  let SELN = 0;
  function mkSel(list, label) {
    const M = (window.__botSel = window.__botSel || {});
    const key = 'b' + (++SELN) + Date.now().toString(36).slice(-4);
    M[key] = {label: label || 'From Monkey bot', ids: list.map(r => String(r.ID))};
    const ks = Object.keys(M); if (ks.length > 15) ks.slice(0, ks.length - 15).forEach(k => { delete M[k]; });
    try { sessionStorage.setItem('pranav_botsel', JSON.stringify(M)); } catch (e) {}
    return key;
  }
  function numLink(list, label) {
    const n = list.length;
    if (!n) return fmtN(0);
    return '<a href="#" class="mbnum" data-sel="' + mkSel(list, label) + '" title="Open these ' + n + ' deal' + (n === 1 ? '' : 's') + ' in the table">' + fmtN(n) + '</a>';
  }
  function groupIds(list, role, k) { const c = colByRole(role); return list.filter(r => (role === 'uni' ? uniOf(r) : (c ? String(r[c.key] || '').trim() : '') || '(empty)') === k); }
  const list_ = a => '<ul class="mbl2">' + a.map(x => '<li>' + x + '</li>').join('') + '</ul>';

  /* ================= deal answers ================= */
  function semLine(r) {
    const sc = semCols(); if (!sc.length) return '';
    const parts = sc.map(c => { const n = c.role.slice(7), v = String(r[c.key] || '').trim() || 'Not Paid'; return 'Sem ' + n + ': ' + v; });
    return parts.join(' · ');
  }
  function rrText(r) { try { return rrStatus(r); } catch (e) { return ''; } }
  function dealCard(r) {
    const cm = lastCm(r);
    const rows_ = [
      ['Student', nameOf(r) + ' · #' + r.ID],
      ['Stage', stageOf(r) || '—'],
      ['University', uniOf(r)],
      ['Program', val(r, 'program') || '—'],
      ['Batch', val(r, 'batch') || '—'],
      ['Current sem', val(r, 'sem') || '—'],
      ['Semester fees', semLine(r) || '—'],
      ['Lead status', val(r, 'lead') || '—'],
      ['Documents', val(r, 'docstat') || '—'],
      ['Next follow-up', niceStored(val(r, 'fuview') || val(r, 'fuupd')) || '—'],
      ['Created', r.DATE_CREATE ? niceStored(r.DATE_CREATE) + ' (' + agoDays(parseStored(r.DATE_CREATE) || new Date()) + ')' : '—']
    ];
    let h = '<div class="mbcard">' + rows_.map(x => '<div><span>' + E(x[0]) + '</span><b>' + E(x[1]) + '</b></div>').join('');
    if (cm) h += '<div class="wide"><span>Last comment</span><b>' + E(cm.c.length > 160 ? cm.c.slice(0, 157) + '…' : cm.c) + '</b><i>' + E([cm.by, cm.at].filter(Boolean).join(' · ')) + '</i></div>';
    return h + '</div>';
  }
  const ATTRS = [
    ['created', /\bcreated\b|\bwhen\b.*\b(deal|added|opened|started|joined)\b|\bwhen\b.*\bcreated\b/],
    ['worked', /\bwho\b.*\b(worked|owner|touched|commented|contacted)\b|\b(last|latest) (worked|contacted|commented|touched)\b|\bwho\b.*\bhandling\b|\bworked (on|last)\b/],
    ['followup', /\bfollow\b|\bnext (call|date)\b/],
    ['phone', /\bphone\b|\bmobile\b|\bcell\b|\bwhatsapp\b|\bcontact\b(?! person)/],
    ['email', /\bemail\b/],
    ['uni', /\buniversity\b/],
    ['program', /\bprogram\b/],
    ['batch', /\bbatch\b/],
    ['mode', /\bmode\b|\bpayment mode\b/],
    ['sempay', /\bsem\w*\s*\d\b.*\b(status|paid|notpaid)\b|\b(status|paid|notpaid)\b.*\bsem\w*\s*\d\b|\bfee\b|\bfees\b/],
    ['curs', /\bcurrent\b.*\bsem|\bwhich sem/],
    ['lead', /\blead status\b|\blead\b.*\bstatus\b|\bhot\b|\bwarm\b|\bcold\b/],
    ['docs', /\bdocs\b.*\bstatus\b|\bdocs\b/],
    ['comment', /\bcomment\b|\bremark\b|\bnote\b/],
    ['stage', /\bstatus\b|\bstage\b|\bwhere\b|\bprogress\b/],
    ['detail', /\bdetail\b|\binfo\b|\bsummary\b|\bwho is\b|\babout\b/]
  ];
  function attrsOf(s) { return ATTRS.filter(a => a[1].test(s)).map(a => a[0]); }
  async function answerAttr(r, attrs, s) {
    const nm = '<b>' + E(nameOf(r)) + '</b>';
    const parts = [];
    let mood = 'happy';
    for (const a of attrs) {
      if (a === 'created') {
        const d = parseStored(r.DATE_CREATE);
        parts.push(d ? nm + '’s deal #' + E(r.ID) + ' was created on <b>' + E(niceStored(r.DATE_CREATE)) + '</b> (' + agoDays(d) + ').' : 'I cannot see a created date for this deal.');
      } else if (a === 'worked') {
        const cm = lastCm(r), act = (await activity()).filter(e => String(e.deal) === String(r.ID) && e.type !== 'login' && e.changes);
        const ea = act[0];
        let h = '';
        if (cm) h += 'The last comment on ' + nm + '’s deal is from <b>' + E(cm.by || 'someone who did not sign it') + '</b> on <b>' + E(cm.at) + '</b>: “' + E(cm.c.length > 140 ? cm.c.slice(0, 137) + '…' : cm.c) + '”';
        if (ea) h += (h ? '<br>' : '') + 'Last edit made from this dashboard: <b>' + E(ea.user) + '</b> on <b>' + E(niceStored(String(ea.at).replace('T', ' ').slice(0, 16))) + '</b>.';
        if (!h) { h = 'No one has commented on this deal yet, and I see no dashboard edits for it. It looks untouched. 🙈'; mood = 'conf'; }
        parts.push(h);
      } else if (a === 'followup') {
        const v = val(r, 'fuview') || val(r, 'fuupd');
        parts.push(v ? 'Next follow-up for ' + nm + ' is <b>' + E(niceStored(v)) + '</b>.' : nm + ' has no follow-up date set.');
      } else if (a === 'phone') parts.push(nm + '’s phone: <b>' + E(r._PHONE || 'not on file') + '</b>');
      else if (a === 'email') parts.push(nm + '’s email: <b>' + E(r._EMAIL || 'not on file') + '</b>');
      else if (a === 'uni') parts.push(nm + ' is in <b>' + E(uniOf(r)) + '</b>.');
      else if (a === 'program') parts.push(nm + '’s program is <b>' + E(val(r, 'program') || 'not set') + '</b>.');
      else if (a === 'batch') parts.push(nm + ' is in batch <b>' + E(val(r, 'batch') || 'not set') + '</b>.');
      else if (a === 'mode') parts.push(nm + ' pays by <b>' + E(val(r, 'mode') || 'not set') + '</b>.');
      else if (a === 'curs') parts.push(nm + ' is in <b>' + E(val(r, 'sem') || 'no semester set') + '</b>.');
      else if (a === 'lead') parts.push(nm + '’s lead status is <b>' + E(val(r, 'lead') || 'not set') + '</b>.');
      else if (a === 'docs') parts.push(nm + '’s documents status: <b>' + E(val(r, 'docstat') || 'not set') + '</b>.');
      else if (a === 'comment') { const cm = lastCm(r); parts.push(cm ? 'Latest comment on ' + nm + ': “' + E(cm.c) + '” — <i>' + E([cm.by, cm.at].filter(Boolean).join(', ')) + '</i>' : 'No comments on ' + nm + '’s deal yet.'); }
      else if (a === 'sempay') {
        const m = /\bsem\w*\s*(\d)\b/.exec(s) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(s);
        if (m) { const c = cols.find(x => x.role === 'semstat' + m[1]); parts.push(c ? nm + '’s Semester ' + m[1] + ' status: <b>' + E(String(r[c.key] || '').trim() || 'Not Paid') + '</b>.' : 'There is no Semester ' + m[1] + ' status field.'); }
        else parts.push(nm + '’s semester fees: ' + E(semLine(r)));
      } else if (a === 'stage') parts.push(nm + ' is at stage <b>' + E(stageOf(r) || 'not set') + '</b>.');
    }
    const showCard = !attrs.length || attrs.indexOf('detail') >= 0 || (attrs.length === 1 && attrs[0] === 'stage');
    return {h: parts.filter(Boolean).join('<br>') + (showCard ? dealCard(r) : ''), mood: mood, deal: r, actions: true};
  }

  /* ================= editing ================= */
  const FIELD_RULES = [
    ['fuupd', /\bfollow\b/],
    ['lastpay', /\blast\b.*\bpayment\b|\blast pay\b/],
    ['nextpay', /\bnext\b.*\bpayment\b|\bre ?registration date\b|\bnext pay\b/],
    ['lead', /\blead status\b|\blead stage\b|\blead\b(?!s)/],
    ['docstat', /\bdocs\b|\bdocuments?\b/],
    ['refadm', /\breference admission\b|\bref admission\b/],
    ['refhas', /\breference\b|\bhas reference\b/],
    ['mode', /\bmode\b|\bpayment\b/],
    ['roll', /\broll\b/],
    ['reg', /\bregistration date\b|\breg date\b|\bregistration\b/],
    ['adm', /\badmission date\b|\badmission\b/],
    ['team', /\bteam\b/],
    ['batch', /\bbatch\b/],
    ['program', /\bprogram\b/],
    ['uni', /\buniversity\b/],
    ['_PHONE', /\bphone\b|\bmobile\b|\bcell\b|\bwhatsapp\b|\bnumber\b/],
    ['_EMAIL', /\bemail\b/],
    ['_NAME', /\bname\b/]
  ];
  function fieldsIn(clause, wholeStr) {
    const out = [];
    const semM = /\bsem\w*\s*(\d)\b/.exec(clause) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(clause) || (() => { const m = /\bsem\w*\s*(one|two|three|four|five|six)\b/.exec(clause) || /\b(first|second|third|fourth|fifth|sixth)\s*sem/.exec(clause); return m ? [m[0], NUMW[m[1]]] : null; })();
    const cur = /\bcurrent\b/.test(clause);
    if (semM && !cur) { const c = cols.find(x => x.role === 'semstat' + semM[1]); if (c) out.push(c); else out.push({missingSem: +semM[1]}); return out; }
    if (cur && /\bsem\b/.test(clause)) { const c = colByRole('sem'); if (c) out.push(c); return out; }
    for (const fr of FIELD_RULES) { if (fr[1].test(clause)) { const c = colByRole(fr[0]); if (c) { out.push(c); break; } } }
    if (!out.length && /\bstatus\b/.test(clause) && !/\bsem\b/.test(clause)) { const c = colByRole('stage'); if (c) out.push(c); }
    if (!out.length && /\bsem\b/.test(clause)) { const c = colByRole('sem'); if (c) out.push(c); }
    return out;
  }
  const PAY_WORDS = /\bpaid\b|\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bdue\b|\bunpaid\b|\bpending\b/;
  function matchOption(col, clause, rawToks) {
    const opts = col.options || [];
    const isPay = /^semstat/.test(col.role || '');
    if (isPay) {
      const wantNot = /\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bunpaid\b|\bdue\b|\bpending\b|\bnot\b/.test(clause), wantPaid = /\bpaid\b/.test(clause);
      if (wantNot) { const o = opts.find(x => /not\s*paid|unpaid|pending|due/i.test(x.name)); if (o) return o; }
      if (wantPaid) { const o = opts.find(x => /^paid/i.test(x.name)) || opts.find(x => /paid/i.test(x.name) && !/not/i.test(x.name)); if (o) return o; }
    }
    const q = rawToks.filter(t => !STOP.has(t));
    let best = null, bs = 0;
    opts.forEach(o => {
      const ot = toks(o.name).filter(t => !STOP.has(t));
      if (!ot.length) return;
      let hit = 0;
      ot.forEach(t => { if (q.some(qt => qt === t || (qt.length >= 5 && t.length >= 5 && near(qt, t) && !LEXV[qt]) || (qt.length >= 4 && t.indexOf(qt) === 0 && !LEXV[qt]))) hit++; });
      const sc = hit / ot.length + (hit === ot.length ? ot.length * 0.01 : 0);
      if (hit && sc > bs) { bs = sc; best = o; }
    });
    if (!best) return null;
    const ties = opts.filter(o => { const ot = toks(o.name).filter(t => !STOP.has(t)); let hit = 0; ot.forEach(t => { if (q.some(qt => qt === t || (qt.length >= 5 && t.length >= 5 && near(qt, t) && !LEXV[qt]) || (qt.length >= 4 && t.indexOf(qt) === 0 && !LEXV[qt]))) hit++; }); return ot.length && (hit / ot.length + (hit === ot.length ? ot.length * 0.01 : 0)) >= bs - 1e-9; });
    if (ties.length > 1) return null;
    return bs >= 0.99 ? best : (bs >= 0.5 && ot_len(best) > 1 ? best : null);
  }
  const ot_len = o => o ? toks(o.name).length : 0;
  function parseEdit(U, deal) {
    const s = U.s;
    const clauses = s.split(/\s*(?:,|;|&|\band\b|\balso\b|\bplus\b|\bthen\b)\s*/).filter(x => x.trim());
    const rawClauses = U.raw.split(/\s*(?:,|;|&|\band\b|\balso\b|\bplus\b|\bthen\b)\s*/i);
    const items = []; let pendFields = [], unresolved = [];
    clauses.forEach((cl, ci) => {
      const raw = rawClauses[ci] || cl;
      const rt = toks(raw);
      const sepM = /\b(to|as|is)\b|=/.exec(cl);
      const pre = sepM ? cl.slice(0, sepM.index) : cl;
      let post = sepM ? cl.slice(sepM.index + sepM[0].length) : cl;
      post = post.replace(/\s+\b(for|of)\b\s.*$/, '');
      const clV = post.trim() ? post : cl;
      const rsep = /\b(to|as|is)\b|=/i.exec(raw);
      let rpost = rsep ? raw.slice(rsep.index + rsep[0].length) : raw;
      rpost = rpost.replace(/\s+\b(for|of)\b\s.*$/i, '');
      const rtV = toks(rpost.trim() ? rpost : raw);
      let fields = fieldsIn(pre, s);
      if (!fields.length && sepM) { fields = fieldsIn(cl, s); if (fields.length && /\bstatus\b/.test(post) && !/\bsem\b/.test(cl)) { const stc = colByRole('stage'); if (stc) fields = [stc]; } if (fields.length && fields[0].role === 'docstat' && /\bdocs\b/.test(post) && !/\bdocs\b/.test(pre)) { const stc = colByRole('stage'); if (stc) fields = [stc]; } }
      if (!fields.length && pendFields.length === 0 && ci > 0 && /^\W*\d\b/.test(cl) && items.length && items[items.length - 1].col && /^semstat/.test(items[items.length - 1].col.role || '')) {
        const n = (/^\W*(\d)/.exec(cl) || [])[1]; const c = cols.find(x => x.role === 'semstat' + n); if (c) fields = [c];
      }
      const missing = fields.filter(f => f.missingSem);
      missing.forEach(m => unresolved.push('There is no Semester ' + m.missingSem + ' status field on this dashboard.'));
      fields = fields.filter(f => !f.missingSem);
      let value = null, vcol = null;
      const tryVal = col => {
        const kind = fieldKind(col);
        if (col.key === '_PHONE') { const m = /\+?\d[\d\s\-]{6,}\d/.exec(raw); return m ? {disp: m[0].replace(/[\s]+/g, ''), send: m[0].replace(/[\s]+/g, '')} : null; }
        if (col.key === '_EMAIL') { const m = /[\w.+\-]+@[\w\-]+\.[\w.\-]+/.exec(raw); return m ? {disp: m[0], send: m[0]} : null; }
        if (col.key === '_NAME') { const m = /(?:\bto\b|\bas\b|=|\bis\b)\s+(.+?)(?:\s+\bfor\b.*)?$/i.exec(raw); return m && m[1].trim().length > 1 ? {disp: m[1].trim(), send: m[1].trim()} : null; }
        if (kind === 'select') { const o = matchOption(col, clV, rtV); return o ? {disp: o.name, send: o.id} : null; }
        if (kind === 'bool') { if (/\b(yes|y|true|haan|ha)\b/.test(clV)) return {disp: 'Yes', send: 'Yes'}; if (/\b(no|false|nahi|nope)\b/.test(clV)) return {disp: 'No', send: 'No'}; return null; }
        if (kind === 'date' || kind === 'datetime') { const d = nlDate(raw); return d ? (kind === 'date' ? {disp: dNice(d, false), send: dIso(d)} : {disp: dNice(d, true), send: dIsoT(d)}) : null; }
        const m = /(?:\bto\b|\bas\b|=|\bis\b)\s+(.+?)(?:\s+\bfor\b.*)?$/i.exec(raw);
        return m && m[1].trim() ? {disp: m[1].trim(), send: m[1].trim()} : null;
      };
      if (fields.length) {
        const gotV = [];
        fields.forEach(f => { const v = tryVal(f); if (v) { items.push({col: f, v: v}); gotV.push(f); } else pendFields.push(f); });
        if (gotV.length && pendFields.length) { const still = []; pendFields.forEach(f => { if (fields.includes(f)) { still.push(f); return; } const v = tryVal(f); if (v) items.push({col: f, v: v}); else still.push(f); }); pendFields = still; }
        if (pendFields.length && !fields.some(f => !pendFields.includes(f))) { /* all pending */ }
      } else if (!missing.length) {
        // value with no field: give it to pending fields, or infer a column
        let done = false;
        if (pendFields.length) {
          const still = [];
          pendFields.forEach(f => { const v = tryVal(f); if (v) { items.push({col: f, v: v}); done = true; } else still.push(f); });
          pendFields = still;
        }
        if (!done && (PAY_WORDS.test(cl) || U.hasSetVerb)) {
          if (PAY_WORDS.test(cl)) { const sc = semCols(); const m = /\bsem\w*\s*(\d)\b/.exec(s); if (m) { const c = sc.find(x => x.role === 'semstat' + m[1]); if (c) { const v = tryVal(c); if (v) { items.push({col: c, v: v}); done = true; } } } if (!done) unresolved.push({askSem: true, text: cl}); }
          else {
            const cand = [];
            colsEd().forEach(c => { if (fieldKind(c) === 'select' && !/^semstat/.test(c.role || '')) { const o = matchOption(c, cl, rt); if (o) cand.push({col: c, v: {disp: o.name, send: o.id}}); } });
            if (cand.length === 1) items.push(cand[0]);
            else if (cand.length > 1) unresolved.push({ambig: cand.map(x => x.col.label), text: cl});
          }
        }
      }
    });
    pendFields.forEach(f => unresolved.push({noValue: f}));
    return {items: items, unresolved: unresolved};
  }

  /* ================= understand ================= */
  function understand(raw) {
    const T = toks(raw), C = T.map(fixTok);
    const s = C.join(' ');
    const U = {raw: raw, T: T, C: C, s: s, skip: {}};
    U.hasSetVerb = /\bset\b/.test(s);
    U.question = /\?\s*$/.test(raw) || /^(what|whats|when|who|how|which|is|are|does|did|do|can you tell|tell me|show)\b/.test(s);
    return U;
  }
  const DOMAIN_RX = /\b(status|university|program|batch|deal|lead|count|sem|follow|overdue|phone|email|team|mode|roll|reference|docs|active|closed|comment|created|worked|paid|notpaid|detail|payment|admission|registration|student|students|stage|fee|fees|dashboard|report|reports|login|password|refresh|filter|profile|bitrix|crm|pipeline|alumni|rereg)\b/;
  const SOCIAL = [
    [/^(hi+|hello+|hey+|hola|namaste|yo|hii+|helo|hallo|good (morning|afternoon|evening|night)|gm)\b/, 'hi'],
    [/^(thanks|thank you|thankyou|thx|tysm|ty|thanku|thnx|cool thanks|great thanks)\b/, 'thanks'],
    [/^(bye|goodbye|see you|cya|good night|gn|tc)\b/, 'bye'],
    [/\b(who are you|what are you|your name|who r u|are you a bot|ur name)\b/, 'who'],
    [/\b(what can you do|help|how to use|what do you do|what all|abilities|commands|menu|options)\b/, 'help'],
    [/^(ok|okay|k|fine|nice|great|good|awesome|cool|wow|hmm+|alright|sure)\W*$/, 'ok'],
    [/\b(how are you|how r u|hows it going|how do you do)\b/, 'howru'],
    [/^(lol|haha+|hehe+|lmao)\W*$/, 'lol']
  ];

  /* ================= replies ================= */
  const TRAINED = [
    'Oo-oo-ah-ah… 😭 the admin has <b>not trained me</b> on that yet. I’m sitting here sobbing into my banana. Please tell him (nicely) to teach me! 🍌',
    'Waaah! 😢 I don’t know that one — the admin never taught me. I tried reading the manual but I ate the pages. Please tell the admin, pretty please! 🙏🍌',
    '😭 *sniff* That’s above my banana grade! The admin hasn’t trained me for this. Go tell him, and tell him I’m crying!',
    'Ooh ooh, I wish I knew! 🥺 But the admin hasn’t trained me on that. Please tell him I need more lessons — I will even do my homework (and eat it).'
  ];
  const OOT = [
    '🙈 Whoa, that’s way outside my jungle! I only talk about <b>this website</b> — deals, students, universities, programs, payments and follow-ups. Ask me something from here and I’ll swing right in! 🌴',
    '🐒 Going out of the topic, my friend! I’m only trained for this dashboard. Try asking about a student, a university or a count of deals. 🍌',
    '🌴 That’s not in my jungle! I know only this website’s data. Ask me about a student’s status, how many universities, or to open a deal to edit.'
  ];
  let ti = 0, oi = 0;
  const notTrained = () => ({h: TRAINED[ti++ % TRAINED.length], mood: 'cry', copy: true});
  const outOfTopic = () => ({h: OOT[oi++ % OOT.length], mood: 'conf'});
  const HELP = '<b>I’m Monkey bot 🐒</b> — I read this dashboard’s data and I find things fast. Editing is your job, so I open the lead for you. Typos are fine!<ul class="mbl2"><li><b>A student:</b> “status of Rahul”, “phone of Rahul”, “who worked last on deal 108”, “when was Rahul’s deal created”</li><li><b>Counts:</b> “how many deals were created this month”, “how many in muj + smu + amity”, “overdue deals”, “sem 1 paid in Amity”, “which university has the most deals”</li><li><b>Latest:</b> “latest deal created”, “last 5 deals”</li><li><b>My work:</b> “last deal I worked on”, “last university I worked on”, “what filter was I using”</li><li><b>Filters:</b> “filter muj + smu + amity”, “only mba and bba”, “clear filters”</li><li><b>Reports:</b> “open alumni report”, “create a report of university by stage for muj + smu”</li><li><b>Edit:</b> “edit Rahul”, “change Rahul’s sem 1 status”. I open that lead’s edit form and walk you through it. No questions.</li><li><b>How-to:</b> refresh data, change password, export CSV, what “overdue” means…</li></ul>🍌';

  /* ================= UI ================= */
  const FACE = (
    '<g class="mbbody"><circle cx="36" cy="106" r="22" fill="#8a5632"/><circle cx="36" cy="106" r="12" fill="#f0c7a0"/><circle cx="164" cy="106" r="22" fill="#8a5632"/><circle cx="164" cy="106" r="12" fill="#f0c7a0"/>' +
    '<circle cx="100" cy="102" r="64" fill="#9a6338"/><path d="M100 72c-14-14-46-6-46 24 0 14 6 22 14 28 4 18 18 30 32 30s28-12 32-30c8-6 14-14 14-28 0-30-32-38-46-24z" fill="#f4d5b0"/>' +
    '<g class="mbe"><ellipse cx="78" cy="102" rx="13" ry="15" fill="#fff"/><g class="mbp"><circle cx="78" cy="104" r="7" fill="#2a1a12"/><circle cx="80.5" cy="101" r="2.3" fill="#fff"/></g></g>' +
    '<g class="mbe"><ellipse cx="122" cy="102" rx="13" ry="15" fill="#fff"/><g class="mbp"><circle cx="122" cy="104" r="7" fill="#2a1a12"/><circle cx="124.5" cy="101" r="2.3" fill="#fff"/></g></g>' +
    '<path class="mbhap" d="M66 106q12-16 24 0M110 106q12-16 24 0"/>' +
    '<path class="mbbr mbbl" d="M64 82q14-9 28-1"/><path class="mbbr mbbrr" d="M108 81q14-8 28 1"/>' +
    '<ellipse cx="100" cy="127" rx="9" ry="6" fill="#6b3f22"/>' +
    '<path class="mbm mbm1" d="M84 141q16 14 32 0"/><path class="mbm mbm2" d="M84 148q16-16 32 0"/><path class="mbm mbm3" d="M84 144q8-7 16 0t16 0"/><path class="mbm4" d="M80 136q20 30 40 0z" fill="#4a1f12" stroke="#4a1f12" stroke-width="3" stroke-linejoin="round"/><path class="mbm5" d="M92 150q8 8 16 0q-8-6-16 0z" fill="#ef6f85"/>' +
    '<path class="mbt mbt1" d="M70 118q-6 12 0 18q6-6 0-18z"/><path class="mbt mbt2" d="M130 118q-6 12 0 18q6-6 0-18z"/></g>' +
    '<text class="mbq" x="150" y="48" font-size="46" font-weight="800" fill="#7c5cf6">?</text>'
  );
  const css = '' +
    '.mbl{position:fixed;right:22px;bottom:22px;z-index:40;width:68px;height:68px;border-radius:50%;border:0;padding:0;cursor:pointer;background:linear-gradient(135deg,#8b6cff,#5326d9);box-shadow:0 12px 28px rgba(83,38,217,.45);display:grid;place-items:center;transition:transform .2s,box-shadow .2s;animation:mbPop .6s .8s both}' +
    '.mbl:hover{transform:translateY(-3px) scale(1.06);box-shadow:0 18px 34px rgba(83,38,217,.55)}.mbl svg{width:54px;height:54px;overflow:visible}' +
    '.mbl .mbtip{position:absolute;right:76px;top:50%;transform:translateY(-50%);white-space:nowrap;background:var(--card);color:var(--text);border:1px solid var(--line);padding:7px 12px;border-radius:12px;font-weight:700;font-size:13px;box-shadow:0 8px 20px rgba(30,20,80,.2);opacity:0;pointer-events:none;transition:opacity .2s}' +
    '.mbl:hover .mbtip,.mbl.hint .mbtip{opacity:1}' +
    '@keyframes mbPop{from{transform:scale(0) rotate(-30deg)}to{transform:none}}' +
    '.mbp2{position:fixed;right:22px;bottom:104px;z-index:41;width:396px;max-width:calc(100vw - 24px);height:min(620px,calc(100vh - 128px));display:flex;flex-direction:column;background:var(--card);color:var(--text);border:1px solid var(--line);border-radius:22px;box-shadow:0 30px 70px rgba(30,20,80,.38);overflow:hidden;animation:mbIn .28s cubic-bezier(.2,.9,.3,1.1) both;transform-origin:100% 100%}' +
    '.mbp2[hidden]{display:none}@keyframes mbIn{from{opacity:0;transform:translateY(18px) scale(.92)}to{opacity:1;transform:none}}' +
    '.mbh{display:flex;align-items:center;gap:12px;padding:14px 16px;background:linear-gradient(120deg,#7c5cf6,#c04ae6);color:#fff}' +
    '.mbh .av{width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.2);display:grid;place-items:center;flex:none}.mbh .av svg{width:46px;height:46px;overflow:visible}' +
    '.mbh h3{margin:0;font-size:17px;letter-spacing:-.2px}.mbh p{margin:1px 0 0;font-size:12px;opacity:.9}.mbh .sp{flex:1;min-width:0}' +
    '.mbhb{display:flex;align-items:center;gap:8px;flex:none;align-self:center}.mbh button{box-sizing:border-box;flex:0 0 32px;width:32px;height:32px;min-width:32px;min-height:32px;margin:0;padding:0;line-height:0;border-radius:10px;border:0;background:rgba(255,255,255,.18);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;position:static;transform:none}.mbh button svg{display:block;width:16px;height:16px;flex:none}.mbh button:hover{background:rgba(255,255,255,.32)}' +
    '.mbmsgs{flex:1;overflow:auto;padding:14px 14px 6px;display:flex;flex-direction:column;gap:10px;background:var(--bg);scroll-behavior:smooth}' +
    '.mbr{display:flex;gap:8px;align-items:flex-end;animation:mbMsg .3s both}.mbr.me{justify-content:flex-end}@keyframes mbMsg{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}' +
    '.mbr .ma{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#8b6cff,#5326d9);display:grid;place-items:center;flex:none}.mbr .ma svg{width:26px;height:26px}' +
    '.mbb{max-width:84%;padding:10px 13px;border-radius:16px;font-size:14px;line-height:1.45;background:var(--card);border:1px solid var(--line);border-bottom-left-radius:5px;overflow-wrap:anywhere}' +
    '.mbr.me .mbb{background:linear-gradient(135deg,#7c5cf6,#9b4df0);color:#fff;border:0;border-radius:16px;border-bottom-right-radius:5px}' +
    '.mbb a{color:var(--accent);font-weight:700}.mbb b{font-weight:700}.mbs{font-size:12px;color:var(--muted)}.mbb a.mbnum{text-decoration:underline dotted;text-underline-offset:3px;cursor:pointer}.mbb a.mbnum:hover{background:color-mix(in srgb,#7c5cf6 15%,transparent);border-radius:4px}.mbn{display:inline-block;min-width:26px;text-align:center;background:color-mix(in srgb,#7c5cf6 15%,transparent);color:var(--accent);border-radius:8px;font-weight:800;font-size:12px;padding:0 6px;margin-left:4px}' +
    '.mbl2{margin:7px 0 2px;padding-left:18px}.mbl2 li{margin:3px 0}' +
    '.mbcard{display:grid;grid-template-columns:1fr 1fr;gap:6px 10px;margin-top:9px;padding:10px;border-radius:12px;background:color-mix(in srgb,#7c5cf6 7%,transparent);font-size:12.5px}.mbcard div{min-width:0}.mbcard span{display:block;color:var(--muted);font-size:11px}.mbcard b{display:block;font-weight:700;overflow-wrap:anywhere}.mbcard .wide{grid-column:1/-1}.mbcard i{display:block;color:var(--muted);font-size:11px;font-style:normal}' +
    '.mbchg{margin:8px 0 2px;padding:9px 11px;border-radius:12px;background:color-mix(in srgb,#f59e0b 13%,transparent);border:1px dashed color-mix(in srgb,#f59e0b 55%,transparent);font-size:13px}.mbchg div{margin:3px 0}.mbchg s{color:var(--muted)}.mbchg em{font-style:normal;font-weight:800;color:#0b7a46}' +
    '.mbact{display:flex;flex-wrap:wrap;gap:6px;margin-top:9px}.mbact button,.mbch button{border:1.5px solid color-mix(in srgb,#7c5cf6 40%,transparent);background:var(--card);color:var(--accent);border-radius:999px;padding:6px 12px;font:inherit;font-size:12.5px;font-weight:700;cursor:pointer;transition:transform .15s,background .15s}.mbact button:hover,.mbch button:hover{background:color-mix(in srgb,#7c5cf6 14%,var(--card));transform:translateY(-1px)}' +
    '.mbact button.go{background:linear-gradient(135deg,#16a34a,#15803d);border-color:transparent;color:#fff}.mbact button.no{border-color:color-mix(in srgb,#e11d48 50%,transparent);color:#e11d48}.mbact button:disabled{opacity:.5;cursor:default;transform:none}' +
    '.mbch{display:flex;flex-wrap:wrap;gap:6px;padding:6px 14px 2px;background:var(--bg)}' +
    '.mbty{display:flex;gap:4px;padding:12px 14px}.mbty i{width:7px;height:7px;border-radius:50%;background:#a78bfa;animation:mbDot 1s infinite}.mbty i:nth-child(2){animation-delay:.15s}.mbty i:nth-child(3){animation-delay:.3s}@keyframes mbDot{0%,60%,100%{transform:none;opacity:.5}30%{transform:translateY(-5px);opacity:1}}' +
    '.mbf{display:flex;gap:8px;padding:10px 12px 12px;background:var(--card);border-top:1px solid var(--line)}.mbf input{flex:1;min-width:0;padding:11px 14px;font:inherit;font-size:14px;color:var(--text);background:var(--bg);border:1.5px solid var(--line);border-radius:14px;outline:0}.mbf input:focus{border-color:#7c5cf6;box-shadow:0 0 0 3px rgba(124,92,246,.16)}' +
    '.mbf button{width:44px;border:0;border-radius:14px;background:linear-gradient(135deg,#7c5cf6,#9b4df0);color:#fff;font-size:18px;cursor:pointer}.mbf button:disabled{opacity:.5}' +
    '.mbhead svg .mbhap,.mbhead svg .mbm2x{display:none}' +
    /* face moods */
    '.mbx{overflow:visible}.mbx .mbe{transform-box:fill-box;transform-origin:center;animation:mbBlink 5s infinite}@keyframes mbBlink{0%,92%,100%{transform:scaleY(1)}95%{transform:scaleY(.1)}}' +
    '.mbx .mbp{transition:transform .25s}.mbx .mbhap{display:none;fill:none;stroke:#2a1a12;stroke-width:5;stroke-linecap:round}.mbx .mbbr{fill:none;stroke:#5a3118;stroke-width:5;stroke-linecap:round;transform-box:fill-box;transform-origin:center;transition:transform .3s}' +
    '.mbx .mbm{fill:none;stroke:#6b3f22;stroke-width:3.5;stroke-linecap:round;display:none}.mbx .mbm1{display:block}.mbx .mbm4,.mbx .mbm5,.mbx .mbt,.mbx .mbq{display:none}.mbx .mbt{fill:#60a5fa;opacity:.9}' +
    '.mbx .mbbody{transform-box:fill-box;transform-origin:50% 100%;animation:mbBob 4.5s ease-in-out infinite}@keyframes mbBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-2px) rotate(-1.2deg)}}' +
    '.mbx.think .mbp{transform:translate(-3px,-5px)}.mbx.think .mbbrr{transform:translateY(-6px)}' +
    '.mbx.happy .mbe,.mbx.happy .mbp{display:none}.mbx.happy .mbhap{display:block}.mbx.happy .mbm1{display:none}.mbx.happy .mbm4,.mbx.happy .mbm5{display:block}.mbx.happy .mbbody{animation:mbJump .4s cubic-bezier(.3,0,.4,1) 4 alternate}@keyframes mbJump{from{transform:translateY(0) rotate(-3deg)}to{transform:translateY(-10px) rotate(3deg)}}' +
    '.mbx.cry .mbm1{display:none}.mbx.cry .mbm2{display:block}.mbx.cry .mbbl{transform:translateY(3px) rotate(-14deg)}.mbx.cry .mbbrr{transform:translateY(3px) rotate(14deg)}.mbx.cry .mbt{display:block;animation:mbTear 1.1s ease-in infinite}.mbx.cry .mbt2{animation-delay:.35s}.mbx.cry .mbbody{animation:mbSob .5s ease-in-out infinite alternate}' +
    '@keyframes mbTear{0%{transform:translateY(-6px) scale(.6);opacity:0}25%{opacity:1}100%{transform:translateY(34px) scale(1);opacity:0}}@keyframes mbSob{from{transform:translateY(0)}to{transform:translateY(2px) rotate(1.4deg)}}' +
    '.mbx.conf .mbm1{display:none}.mbx.conf .mbm3{display:block}.mbx.conf .mbbl{transform:translateY(-7px) rotate(-8deg)}.mbx.conf .mbp{transform:translate(4px,-1px)}.mbx.conf .mbq{display:block;animation:mbQ 1.4s ease-in-out infinite;transform-box:fill-box;transform-origin:center}@keyframes mbQ{0%,100%{transform:rotate(-10deg) scale(.9)}50%{transform:rotate(10deg) scale(1.15)}}' +
    '.mbl.hasq{animation:mbWig 2.4s 2s ease-in-out infinite}@keyframes mbWig{0%,100%{transform:none}8%{transform:rotate(-10deg)}16%{transform:rotate(10deg)}24%{transform:rotate(-6deg)}32%{transform:none}}' +
    '@media(max-width:600px){.mbp2{right:0;left:0;bottom:0;top:0;width:auto;max-width:none;height:auto;border-radius:0}.mbl{right:14px;bottom:14px}.mbl.open{display:none}}' +
    '@media(prefers-reduced-motion:reduce){.mbx *,.mbl,.mbp2,.mbr{animation:none!important}}';

  const st = document.createElement('style'); st.textContent = css; document.head.appendChild(st);
  const wrap = document.createElement('div');
  wrap.innerHTML =
    '<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs><symbol id="mbface" viewBox="0 0 200 190"><circle cx="36" cy="106" r="22" fill="#8a5632"/><circle cx="36" cy="106" r="12" fill="#f0c7a0"/><circle cx="164" cy="106" r="22" fill="#8a5632"/><circle cx="164" cy="106" r="12" fill="#f0c7a0"/><circle cx="100" cy="102" r="64" fill="#9a6338"/><path d="M100 72c-14-14-46-6-46 24 0 14 6 22 14 28 4 18 18 30 32 30s28-12 32-30c8-6 14-14 14-28 0-30-32-38-46-24z" fill="#f4d5b0"/><ellipse cx="78" cy="102" rx="13" ry="15" fill="#fff"/><circle cx="78" cy="104" r="7" fill="#2a1a12"/><ellipse cx="122" cy="102" rx="13" ry="15" fill="#fff"/><circle cx="122" cy="104" r="7" fill="#2a1a12"/><ellipse cx="100" cy="127" rx="9" ry="6" fill="#6b3f22"/><path d="M84 141q16 14 32 0" fill="none" stroke="#6b3f22" stroke-width="3.5" stroke-linecap="round"/></symbol></defs></svg>' +
    '<button class="mbl hasq" id="mbOpen" type="button" aria-label="Open Monkey bot" aria-expanded="false"><span class="mbtip">Ask Monkey bot 🐒</span><svg class="mbx" id="mbLaunch" viewBox="0 0 200 190" aria-hidden="true">' + FACE + '</svg></button>' +
    '<section class="mbp2" id="mbPanel" role="dialog" aria-label="Monkey bot chat" hidden>' +
    '<div class="mbh mbhead"><div class="av"><svg class="mbx" id="mbHead" viewBox="0 0 200 190" aria-hidden="true">' + FACE + '</svg></div><div class="sp"><h3>Monkey bot</h3><p id="mbSub">Ask me about deals, students and counts 🍌</p></div><div class="mbhb"><button type="button" id="mbClear" title="Clear chat" aria-label="Clear chat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 12a8.5 8.5 0 1 1-2.5-6"/><path d="M18.5 2.5V7H14"/></svg></button><button type="button" id="mbClose" title="Close" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button></div></div>' +
    '<div class="mbmsgs" id="mbMsgs" aria-live="polite"></div><div class="mbch" id="mbChips"></div>' +
    '<form class="mbf" id="mbForm" autocomplete="off"><input id="mbIn" type="text" maxlength="400" placeholder="Ask me anything about the deals…" aria-label="Message Monkey bot"><button type="submit" aria-label="Send">➤</button></form></section>';
  document.body.appendChild(wrap);

  const msgs = g('mbMsgs'), chips = g('mbChips'), input = g('mbIn'), panel = g('mbPanel'), openB = g('mbOpen');
  const S = {deal: null, pending: null, edit: null, busy: false, asked: ''};
  function mood(m, hold) {
    const hd = g('mbHead'); hd.setAttribute('class', 'mbx' + (m && m !== 'idle' ? ' ' + m : ''));
    clearTimeout(mood.t); if (m && m !== 'idle' && m !== 'think') mood.t = setTimeout(() => hd.setAttribute('class', 'mbx'), hold || 7000);
  }
  function scroll() { msgs.scrollTop = msgs.scrollHeight; }
  function addBot(h, opts) {
    opts = opts || {};
    const d = document.createElement('div'); d.className = 'mbr';
    d.innerHTML = '<div class="ma"><svg viewBox="0 0 200 190" aria-hidden="true"><use href="#mbface"/></svg></div><div class="mbb">' + h + '</div>';
    msgs.appendChild(d); scroll(); return d;
  }
  function addMe(t) {
    const d = document.createElement('div'); d.className = 'mbr me'; d.innerHTML = '<div class="mbb">' + E(t) + '</div>'; msgs.appendChild(d); scroll();
  }
  function setChips(list) {
    chips.innerHTML = (list || []).map(c => '<button type="button" data-q="' + E(c) + '">' + E(c) + '</button>').join('');
  }
  const baseChips = () => ['Deals created this month', 'Latest deal created', 'My last deal', 'Overdue follow-ups', 'What can you do?'];
  function dealChips() { return S.deal ? ['Who worked last?', 'When was it created?', 'Edit this deal', 'Next follow-up?'] : baseChips(); }
  function actionsFor(r) {
    return '<div class="mbact"><button type="button" data-act="edit" data-id="' + E(r.ID) + '">✎ Edit this deal</button><button type="button" data-act="form" data-id="' + E(r.ID) + '">Open edit form</button></div>';
  }
  function respond(rep) {
    if (!rep) return;
    let h = rep.h || E(rep.t || '');
    if (rep.deal && rep.actions) { S.deal = rep.deal; h += actionsFor(rep.deal); }
    if (rep.copy) h += '<div class="mbact"><button type="button" data-act="copyq">📋 Copy my question for the admin</button></div>';
    addBot(h);
    mood(rep.mood || 'idle');
    g('mbSub').textContent = rep.mood === 'cry' ? 'Sniff… I need more training 😭' : rep.mood === 'conf' ? 'Hmm? That is not my jungle 🙈' : 'Ask me about deals, students and counts 🍌';
    setChips(rep.chips || dealChips());
  }
  async function think(fn, ms) {
    S.busy = true; mood('think');
    const ty = document.createElement('div'); ty.className = 'mbr'; ty.innerHTML = '<div class="ma"><svg viewBox="0 0 200 190"><use href="#mbface"/></svg></div><div class="mbb mbty"><i></i><i></i><i></i></div>'; msgs.appendChild(ty); scroll();
    const t0 = Date.now();
    let rep;
    try { rep = await fn(); } catch (e) { rep = {h: 'Oops, I tripped over a vine: ' + E(e && e.message ? e.message : 'something went wrong') + '. Nothing was changed.', mood: 'cry'}; }
    const wait = Math.max(0, (ms || 450) - (Date.now() - t0));
    await new Promise(r => setTimeout(r, wait));
    ty.remove(); S.busy = false; respond(rep);
  }

  /* ---------- confirm / edit flow ---------- */
  function showWho(list, then) {
    S.pending = {t: 'which', then: then, list: list.map(x => x.r)};
    const shown = S.pending.list.slice(0, 8);
    const h = 'I found <b>' + list.length + '</b> students that match. Which one do you mean?' +
      '<div class="mbact">' + shown.map(r => '<button type="button" data-pick="' + E(r.ID) + '">' + E(nameOf(r)) + ' · #' + E(r.ID) + (uniOf(r) !== UNASSIGNED ? ' · ' + E(uniOf(r)) : '') + '</button>').join('') + '</div>' +
      (list.length > 8 ? '<div class="mbs" style="margin-top:6px">Showing 8 of ' + list.length + '. Type the full name or deal number to narrow it down.</div>' : '');
    return {h: h, mood: 'conf', chips: ['Cancel']};
  }
  function describeEdit(E_) {
    const r = E_.deal;
    let h = 'Please check, this is what I understood:<div class="mbchg"><div>👤 <b>' + E(nameOf(r)) + '</b> · #' + E(r.ID) + '</div>' +
      E_.items.map(it => '<div>• ' + E(it.col.label) + ': <s>' + E(it.old || 'empty') + '</s> → <em>' + E(it.v.disp) + '</em></div>').join('') +
      (E_.fu ? '<div>• Next follow-up: <em>' + E(dNice(E_.fu, true)) + '</em></div>' : '') +
      '<div class="mbs" style="margin-top:6px">I will also add this comment: “' + E(E_.comment) + '”</div></div>' +
      '<div class="mbact"><button type="button" class="go" data-act="yes">✅ Yes, save it</button><button type="button" class="no" data-act="no">✖ No, cancel</button></div><div class="mbs" style="margin-top:5px">You can also type yes or no. To fix something, just tell me, for example “make it sem 2” or “follow up on 25 Oct”.</div>';
    return h;
  }
  function buildComment(E_) {
    const who = ME || 'a user';
    const bits = E_.items.map(it => it.col.label + ' changed from “' + (it.old || 'empty') + '” to “' + it.v.disp + '”');
    return ('Monkey bot: ' + who + ' asked me to update this deal. ' + bits.join('; ') + '.').slice(0, 980);
  }
  function askFollow() {
    S.edit.step = 'fu';
    return {h: 'When should the <b>next follow-up</b> be? (every saved change needs one) 📅<br><span class="mbs">e.g. tomorrow, 20 Oct, in 3 days, next monday 4pm</span>', mood: 'idle', chips: ['Tomorrow', 'In 3 days', 'Next week', 'Cancel']};
  }
  function toConfirm() {
    const e = S.edit; e.comment = buildComment(e); e.step = 'confirm';
    return {h: describeEdit(e), mood: 'idle', chips: ['Yes', 'No']};
  }
  function startEdit(deal, parsed, U) {
    const ed = colsEd(), fuCol = colByRole('fuupd');
    const items = []; const msgsOut = [];
    parsed.items.forEach(it => {
      const c = it.col;
      if (fuCol && c.key === fuCol.key) { return; }
      if (!ed.some(x => x.key === c.key)) { msgsOut.push('I can’t edit <b>' + E(c.label) + '</b> with your login (read-only).'); return; }
      let old = String(deal[c.key] || '');
      if (!old && /^semstat/.test(c.role || '')) old = 'Not Paid';
      const same = norm(old) === norm(it.v.disp);
      if (same) { msgsOut.push('<b>' + E(c.label) + '</b> is already <b>' + E(old || it.v.disp) + '</b>.'); return; }
      items.push({col: c, v: it.v, old: old});
    });
    let fu = null;
    const fi = parsed.items.find(it => fuCol && it.col.key === fuCol.key);
    if (fi) { const d = nlDate(U.raw); if (d) fu = d; }
    else if (/\bfollow\b/.test(U.s)) { const d = nlDate(U.raw); if (d) fu = d; }
    if (!items.length) {
      return {h: (msgsOut.length ? msgsOut.join('<br>') + '<br>' : '') + 'Nothing to change then. 🍌', mood: 'idle'};
    }
    S.deal = deal;
    S.edit = {deal: deal, items: items, fu: fu, step: 'fu', note: msgsOut};
    const lead = msgsOut.length ? msgsOut.join('<br>') + '<br>' : '';
    const rep = fu ? toConfirm() : askFollow();
    rep.h = lead + rep.h;
    return rep;
  }
  async function executeEdit() {
    const e = S.edit; if (!e) return {t: 'There is nothing waiting to be saved.'};
    const r = e.deal, changes = {}, original = {};
    e.items.forEach(it => { changes[it.col.key] = it.v.send; original[it.col.key] = r[it.col.key] || ''; });
    const fuCol = colByRole('fuupd');
    if (fuCol && e.fu) { changes[fuCol.key] = dIsoT(e.fu); original[fuCol.key] = r[fuCol.key] || ''; }
    let by = ''; try { by = localStorage.getItem('pranav_cm_by') || ''; } catch (x) {}
    by = ((by || ME || 'user') + ' via Monkey bot').slice(0, 60);
    let resp, j = {};
    try {
      resp = await fetch('api.php?action=update', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({id: r.ID, changes: changes, original: original, comment: cmSrc ? e.comment : '', by: by})});
      if (resp.status === 401) { location.reload(); return {t: 'Your session ended. Please log in again.'}; }
      j = await resp.json().catch(() => ({}));
    } catch (x) { resp = null; }
    if (resp && resp.ok && j.ok) {
      applyRowUpdates(r, j.updated || {});
      TOUCHED[r.ID] = Date.now(); BOT_IDS[r.ID] = Date.now(); evAdd('Monkey bot saved changes to ' + nameOf(r)); ACT = null;
      try { renderRoute(false); } catch (x) {}
      S.edit = null;
      return {h: '🎉 <b>Done!</b> Saved to Bitrix24 for <b>' + E(nameOf(r)) + '</b>.' + (cmSrc ? '<div class="mbs" style="margin-top:4px">Comment added: “' + E(e.comment) + '”</div>' : '') + dealCard(r), mood: 'happy', deal: r, actions: true};
    }
    let msg = !resp ? 'I could not reach the server. Nothing was saved.' : (j.error || ('Server error ' + resp.status + '. Nothing was saved.'));
    if (resp && resp.status === 409 && j.conflicts) {
      const parts = Object.keys(j.conflicts).map(k => j.conflicts[k].label + ' is now “' + (j.conflicts[k].live || 'empty') + '”');
      msg += ' Changed in Bitrix24 meanwhile: ' + parts.join('; ') + '. I reloaded the latest values, please tell me again.';
      applyRowUpdates(r, j.fresh || {}); try { renderRoute(false); } catch (x) {}
    }
    S.edit = null;
    return {h: '😢 <b>Not saved.</b> ' + E(msg), mood: 'cry'};
  }

  /* ================= v2: periods, filters, reports, my work, knowledge ================= */
  const TWL = ['today', 'yesterday', 'tomorrow', 'week', 'month', 'year', 'this', 'last', 'next', 'past', 'previous', 'days', 'weeks', 'months', 'years'];
  const twFix = t => { if (TWL.indexOf(t) >= 0 || t.length < 4) return t; for (let i = 0; i < TWL.length; i++) if (near(t, TWL[i])) return TWL[i]; return t; };
  const MABBR = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
  const startDay = d => new Date(d.getFullYear(), d.getMonth(), d.getDate());
  const addDays = (d, n) => new Date(d.getFullYear(), d.getMonth(), d.getDate() + n);
  const isTimeWord = t => { const x = twFix(t); return TWL.indexOf(x) >= 0 || MONTHS.indexOf(x) >= 0 || (t.length >= 3 && MABBR.indexOf(t.slice(0, 3)) >= 0 && MONTHS[MABBR.indexOf(t.slice(0, 3))].indexOf(t) === 0) || /^(19|20)\d\d$/.test(t) || /^\d{1,2}(st|nd|rd|th)$/.test(t); };
  function period(raw, futureOk) {
    const low = norm(raw), T = low.split(' ').map(twFix), s = T.join(' ');
    const now = new Date(), t0 = startDay(now);
    let m;
    if ((m = /\b(?:last|past|previous) (\d+) (day|days|week|weeks|month|months)\b/.exec(s))) {
      const n = +m[1], u = m[2][0];
      const a = u === 'd' ? addDays(t0, -n + 1) : u === 'w' ? addDays(t0, -7 * n + 1) : new Date(t0.getFullYear(), t0.getMonth() - n, t0.getDate());
      return {a: a, b: addDays(t0, 1), label: 'in the last ' + n + ' ' + m[2]};
    }
    if (/\btoday\b/.test(s)) return {a: t0, b: addDays(t0, 1), label: 'today'};
    if (/\byesterday\b/.test(s)) return {a: addDays(t0, -1), b: t0, label: 'yesterday'};
    if (/\btomorrow\b/.test(s)) return {a: addDays(t0, 1), b: addDays(t0, 2), label: 'tomorrow'};
    const mon = addDays(t0, -((t0.getDay() + 6) % 7)), y = t0.getFullYear(), mo = t0.getMonth();
    if (/\bthis week\b/.test(s)) return {a: mon, b: addDays(mon, 7), label: 'this week'};
    if (/\b(?:last|previous) week\b/.test(s)) return {a: addDays(mon, -7), b: mon, label: 'last week'};
    if (/\bnext week\b/.test(s)) return {a: addDays(mon, 7), b: addDays(mon, 14), label: 'next week'};
    if (/\bthis month\b/.test(s)) return {a: new Date(y, mo, 1), b: new Date(y, mo + 1, 1), label: 'this month'};
    if (/\b(?:last|previous) month\b/.test(s)) return {a: new Date(y, mo - 1, 1), b: new Date(y, mo, 1), label: 'last month'};
    if (/\bnext month\b/.test(s)) return {a: new Date(y, mo + 1, 1), b: new Date(y, mo + 2, 1), label: 'next month'};
    if (/\bthis year\b/.test(s)) return {a: new Date(y, 0, 1), b: new Date(y + 1, 0, 1), label: 'this year'};
    if (/\b(?:last|previous) year\b/.test(s)) return {a: new Date(y - 1, 0, 1), b: new Date(y, 0, 1), label: 'last year'};
    // dd/mm[/yyyy]
    if ((m = /\b(\d{1,2})\/(\d{1,2})(?:\/(\d{2,4}))?\b/.exec(low))) {
      let yy = m[3] ? +m[3] : y; if (yy < 100) yy += 2000;
      const d = new Date(yy, +m[2] - 1, +m[1]);
      if (!isNaN(d)) return {a: d, b: addDays(d, 1), label: 'on ' + dNice(d)};
    }
    // month names (with optional day / year)
    for (let j = 0; j < T.length; j++) {
      const t = T[j];
      let k = MONTHS.indexOf(t);
      if (k < 0 && t === 'sept') k = 8;
      if (k < 0 && t.length === 3) k = MABBR.indexOf(t);
      if (k < 0) continue;
      const prev = T[j - 1] || '', next = T[j + 1] || '';
      const dayAdj = /^\d{1,2}(st|nd|rd|th)?$/.test(prev) ? parseInt(prev, 10) : /^\d{1,2}(st|nd|rd|th)?$/.test(next) && !/^(19|20)\d\d$/.test(next) ? parseInt(next, 10) : 0;
      const yr = /^(19|20)\d\d$/.test(next) ? +next : /^(19|20)\d\d$/.test(prev) ? +prev : 0;
      if ((t === 'may' || t === 'mar') && !yr && !dayAdj && !/^(in|of|during|for|since|on|created|added|from)$/.test(prev)) continue;
      let yy = yr || y;
      if (dayAdj) { let d = new Date(yy, k, dayAdj); if (!yr && !futureOk && d > now) d = new Date(yy - 1, k, dayAdj); return {a: d, b: addDays(d, 1), label: 'on ' + dNice(d)}; }
      let a = new Date(yy, k, 1);
      if (!yr && !futureOk && a > now) a = new Date(yy - 1, k, 1);
      return {a: a, b: new Date(a.getFullYear(), a.getMonth() + 1, 1), label: 'in ' + MONTHS[k].replace(/^./, c => c.toUpperCase()) + ' ' + a.getFullYear()};
    }
    return null;
  }
  const inP = (d, p) => !!d && d >= p.a && d < p.b;
  const createdOf = r => parseStored(r.DATE_CREATE);
  const fuOf = r => parseStored(val(r, 'fuview') || val(r, 'fuupd'));
  const dealLink = r => '<a href="#" data-deal="' + E(r.ID) + '">' + E(nameOf(r)) + '</a> <span class="mbs">#' + E(r.ID) + (uniOf(r) !== UNASSIGNED ? ' · ' + E(uniOf(r)) : '') + '</span>';
  const timeFree = U => {
    const T = [], C = [];
    U.T.forEach((t, i) => { if (!isTimeWord(t) && !/^(new|newest|newly|recent|recently|latest|oldest|earliest)$/.test(t)) { T.push(t); C.push(U.C[i]); } });
    return {T: T, C: C};
  };
  function valueFilters(U) {
    const tf = timeFree(U);
    const F = parseFilters(tf.T, tf.C, {});
    return F;
  }

  /* ---- counts / lists for a period ---- */
  function periodReply(U, raw) {
    const p0 = period(raw, true);
    if (!p0) return null;
    const fu = /\bfollow\b/.test(U.s) || p0.a > new Date();
    const p = fu ? p0 : period(raw, false);
    if (!p) return null;
    const F = valueFilters(U);
    const list = allRows.filter(r => F.every(f => f.test(r)) && inP(fu ? fuOf(r) : createdOf(r), p));
    list.sort((a, b) => ((fu ? fuOf(a) : createdOf(a)) - (fu ? fuOf(b) : createdOf(b))) * (fu ? 1 : -1));
    const n = list.length;
    const what = fu ? 'with a follow-up due ' : 'created ';
    let h = '<b>' + numLink(list, 'Deals ' + what + p.label) + '</b> deal' + (n === 1 ? '' : 's') + (F.length ? filtText(F) : '') + ' ' + what + '<b>' + E(p.label) + '</b>' + (p.b - p.a > 864e5 * 2 ? ' <span class="mbs">(' + dNice(p.a) + ' – ' + dNice(addDays(p.b, -1)) + ')</span>' : '') + '.';
    if (n) h += list_(list.slice(0, 6).map(r => dealLink(r) + ' <span class="mbs">' + E(niceStored(fu ? (val(r, 'fuview') || val(r, 'fuupd')) : r.DATE_CREATE)) + '</span>')) + (n > 6 ? '<div class="mbs">…and ' + (n - 6) + ' more.</div>' : '');
    else h += '<div class="mbs">Nothing there. 🍌</div>';
    return {h: h, mood: n ? 'happy' : 'conf', chips: baseChips()};
  }

  /* ---- latest / oldest deal ---- */
  function latestReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (period(raw, true) || /\b(least|fewest|lowest|smallest|most|highest|biggest)\b/.test(low)) return null;
    const old = /\b(oldest|earliest)\b/.test(low);
    const isLast = /\b(last|lastest|latest|newest|newly|recent|recently|new)\b/.test(low);
    if (!(old || isLast)) return null;
    if (!/\bdeals?\b|\bleads?\b|\badmissions?\b|\brecords?\b|\bentry\b|\bentries\b/.test(s + ' ' + low)) return null;
    if (/\b(worked|comment|follow|edit|edited|updated|touched|contacted)\b/.test(s) || /\b(i|my|me|mine)\b/.test(low)) return null;
    const F = valueFilters(U);
    const list = allRows.filter(r => F.every(f => f.test(r)) && createdOf(r));
    if (!list.length) return {h: 'I cannot see any created dates yet. 🙈', mood: 'conf'};
    list.sort((a, b) => (createdOf(b) - createdOf(a)) * (old ? -1 : 1));
    const nm = /\b(\d{1,2})\b/.exec(low), plural = /\bdeals\b|\bstudents\b|\bleads\b/.test(low);
    const n = nm ? Math.min(+nm[1], 15) : (plural && /\b(few|some|recent|latest|newest)\b/.test(low) ? 5 : 1);
    if (n > 1) {
      return {h: 'The ' + (old ? 'oldest' : 'latest') + ' <b>' + n + '</b> deals' + (F.length ? filtText(F) : '') + ':' + list_(list.slice(0, n).map(r => dealLink(r) + ' <span class="mbs">' + E(niceStored(r.DATE_CREATE)) + ' (' + agoDays(createdOf(r)) + ')</span>')), mood: 'happy'};
    }
    const r = list[0];
    return {h: 'The ' + (old ? 'oldest' : 'latest') + ' deal' + (F.length ? filtText(F) : '') + ' is <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + '), created <b>' + E(niceStored(r.DATE_CREATE)) + '</b> (' + agoDays(createdOf(r)) + ').' + dealCard(r), mood: 'happy', deal: r, actions: true};
  }

  /* ---- what I worked on ---- */
  async function myItems() {
    const act = (await activity()).filter(e => e && e.user === ME && e.type !== 'login' && e.deal && e.changes);
    act.sort((a, b) => String(b.at).localeCompare(String(a.at)));
    return act;
  }
  const whenTxt = at => { const d = new Date(at); return isNaN(d) ? String(at) : dNice(d, true) + ' (' + agoDays(d) + ')'; };
  async function workReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (!/\b(i|my|mine|ive|i have|myself)\b/.test(low)) return null;
    if (!(/\b(worked|work)\b/.test(s) || /\b(saved|opened|did|done|handled|edit|edited|my work|my activity|my edits|my changes)\b/.test(low))) return null;
    if (U.hasSetVerb && fieldsIn(U.s, U.s).length) return null;
    if (/\bfilter/.test(low)) return null;
    const items = await myItems();
    const byDeal = [];
    const seen = {};
    items.forEach(e => { if (!seen[e.deal]) { seen[e.deal] = 1; byDeal.push(e); } });
    const p = period(raw, false);
    if (/\b(how many)\b/.test(low) || /\bcount\b|\bnumber of\b/.test(s)) {
      const inn = p ? items.filter(e => inP(new Date(e.at), p)) : items;
      const dd = {}; inn.forEach(e => { dd[e.deal] = 1; });
      return {h: 'You made <b>' + inn.length + '</b> save' + (inn.length === 1 ? '' : 's') + ' on <b>' + Object.keys(dd).length + '</b> deal' + (Object.keys(dd).length === 1 ? '' : 's') + ' from this dashboard' + (p ? ' <b>' + E(p.label) + '</b>' : ' so far') + '.<div class="mbs">I can only see edits saved from this dashboard.</div>', mood: 'happy'};
    }
    if (!byDeal.length) {
      // fall back to comments signed with the user's name
      let best = null;
      const me = String(ME || '').toLowerCase();
      if (me) allRows.forEach(r => { const cm = lastCm(r); if (cm && String(cm.by || '').toLowerCase().indexOf(me) >= 0 && (!best || cm.ms > best.cm.ms)) best = {r: r, cm: cm}; });
      if (best) return {h: 'I have no saved edits from you in the dashboard log, but the latest comment signed by you is on <b>' + E(nameOf(best.r)) + '</b> (#' + E(best.r.ID) + ') at <b>' + E(best.cm.at) + '</b>.' + dealCard(best.r), mood: 'happy', deal: best.r, actions: true};
      return {h: 'I can’t see anything you worked on yet. 🙈 I only know edits saved from this dashboard and comments signed with your name. Once you save a deal, ask me again!', mood: 'conf'};
    }
    const rowOf = id => allRows.find(r => String(r.ID) === String(id));
    const wantUni = /\buniversit|\buni\b|\bcollege\b/.test(s + ' ' + low), wantProg = /\bprogram|\bcourse\b/.test(s + ' ' + low), wantBatch = /\bbatch\b/.test(s);
    const many = /\b(last|recent|latest)\s+(\d{1,2})\b|\b(\d{1,2})\s+(recent|latest|last)\b/.exec(low);
    const dist_ = (fn, label) => {
      const out = [], sn = {};
      byDeal.forEach(e => { const r = rowOf(e.deal); if (!r) return; const v = fn(r); if (v && !sn[v]) { sn[v] = 1; out.push({v: v, r: r, e: e}); } });
      if (!out.length) return '';
      return 'The last ' + label + ' you worked on is <b>' + E(out[0].v) + '</b> <span class="mbs">(deal ' + E(nameOf(out[0].r)) + ', ' + E(whenTxt(out[0].e.at)) + ')</span>.' + (out.length > 1 ? '<div class="mbs">Before that: ' + out.slice(1, 4).map(x => E(x.v)).join(' → ') + '</div>' : '');
    };
    if (wantUni || wantProg || wantBatch) {
      const parts = [];
      if (wantUni) parts.push(dist_(r => uniOf(r) !== UNASSIGNED ? uniOf(r) : '', 'university'));
      if (wantProg) parts.push(dist_(r => val(r, 'program'), 'program'));
      if (wantBatch) parts.push(dist_(r => val(r, 'batch'), 'batch'));
      return {h: parts.filter(Boolean).join('<br>') || 'I could not work that out from your recent edits. 🙈', mood: 'happy'};
    }
    const n = many ? Math.min(+(many[2] || many[3]), 15) : (/\bdeals\b|\bstudents\b|\brecent\b/.test(low) && !/\bdeal\b/.test(low) ? 5 : 1);
    if (n > 1) {
      return {h: 'Your last <b>' + Math.min(n, byDeal.length) + '</b> deals:' + list_(byDeal.slice(0, n).map(e => { const r = rowOf(e.deal); return r ? dealLink(r) + ' <span class="mbs">' + E(whenTxt(e.at)) + '</span>' : 'Deal #' + E(e.deal); })), mood: 'happy'};
    }
    const e = byDeal[0], r = rowOf(e.deal);
    if (!r) return {h: 'Your last save was on deal #' + E(e.deal) + ' at ' + E(whenTxt(e.at)) + ', but I can’t see that deal now.', mood: 'conf'};
    const ch = Object.keys(e.changes || {}).map(k => { const c = e.changes[k]; return E(c.field) + ': <s>' + E(c.old || 'empty') + '</s> → <em>' + E(c.new || 'empty') + '</em>'; });
    return {h: 'The last deal you worked on is <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + '), saved <b>' + E(whenTxt(e.at)) + '</b>.' + (ch.length ? '<div class="mbs" style="margin:4px 0">' + ch.join('<br>') + '</div>' : '') + dealCard(r), mood: 'happy', deal: r, actions: true};
  }

  /* ---- dashboard filters (the filter bar) ---- */
  const GF_ID = {uni: 'uni', program: 'prog', batch: 'batch', mode: 'mode', stage: 'stage', team: 'team', sem: 'sem'};
  const GF_LABEL = {uni: 'University', prog: 'Program', batch: 'Batch', mode: 'Mode of payment', stage: 'Stage', team: 'Team Name', sem: 'Current semester'};
  function gfText(gf) {
    const parts = Object.keys(gf || {}).filter(k => gf[k] && gf[k].length).map(k => '<b>' + E(GF_LABEL[k] || k) + '</b>: ' + gf[k].map(E).join(', '));
    return parts.join(' · ');
  }
  const gfNow = () => { const o = {}; try { Object.keys(GF).forEach(k => { if (GF[k] && GF[k].length) o[k] = GF[k].slice(); }); } catch (e) {} return o; };
  let LASTGF = null;
  try { LASTGF = JSON.parse(localStorage.getItem('mb_lastgf') || 'null'); } catch (e) {}
  setInterval(() => {
    const n = gfNow();
    if (Object.keys(n).length && (!LASTGF || JSON.stringify(LASTGF.gf) !== JSON.stringify(n))) {
      LASTGF = {gf: n, at: Date.now()};
      try { localStorage.setItem('mb_lastgf', JSON.stringify(LASTGF)); } catch (e) {}
    }
  }, 1500);
  function valueGroups(T, C, skip) {
    const hits = matchValues(T, C, skip || {});
    const G = {}, used = {};
    hits.forEach(h => {
      const id = GF_ID[h.vd.role]; if (!id) return;
      (G[id] = G[id] || {role: h.vd.role, vals: [], idx: []});
      if (G[id].vals.indexOf(h.vd.v) < 0) G[id].vals.push(h.vd.v);
      h.idx.forEach(i => { used[i] = 1; G[id].idx.push(i); });
    });
    return {G: G, used: used};
  }
  function gfValue(id, v) { return v; }
  function applyGF(next, merge) {
    const base = merge ? gfNow() : {};
    const cur = merge ? base : gfNow();
    Object.keys(next).forEach(id => {
      if (merge && cur[id]) next[id].forEach(v => { if (cur[id].indexOf(v) < 0) cur[id].push(v); });
      else cur[id] = next[id].slice();
    });
    GF = cur; gfSave(); try { page = 0; } catch (e) {}
    if (route.type === 'report' || route.type === 'reports' || route.rb) location.hash = '#/'; else renderRoute(false);
    return cur;
  }
  function filterReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (/\b(clear|remove|reset|cancel|drop|delete|turn off|disable|no)\b.*\bfilters?\b|\bfilters?\b.*\b(clear|remove|reset|off)\b|\bunfilter\b|\bshow (me )?(everything|all deals)\b|\bremove all\b/.test(low)) {
      const had = Object.keys(gfNow()).length;
      GF = {}; gfSave(); try { page = 0; } catch (e) {} if (route.type === 'report' || route.type === 'reports') location.hash = '#/'; else renderRoute(false);
      return {h: had ? 'Done, all dashboard filters are cleared. 🧹 Showing all <b>' + fmtN(allRows.length) + '</b> deals.' : 'No filters were on, so everything is already showing. 🍌', mood: 'happy'};
    }
    if (/\b(what|which|whats|current|last|previous|recent|my|any)\b.*\bfilters?\b|\bfilters?\b.*\b(applied|using|used|worked|set|on|selected|active|now)\b/.test(low) && !/\b(apply|set|use)\b.*\bfilters?\b.*\b(for|to|on)\b/.test(low) && !valueGroups(U.T, U.C, {}).used[0] && !Object.keys(valueGroups(U.T, U.C, {}).G).length) {
      const n = gfNow();
      if (Object.keys(n).length) return {h: 'Right now these filters are on:<div class="mbs" style="margin:4px 0">' + gfText(n) + '</div>Showing <b>' + fmtN(gfRows.length) + '</b> of <b>' + fmtN(allRows.length) + '</b> deals.<div class="mbact"><button type="button" data-act="clearf">🧹 Clear filters</button></div>', mood: 'happy'};
      if (LASTGF && Object.keys(LASTGF.gf || {}).length) return {h: 'No filter is on right now. The last filter I saw you use (' + E(whenTxt(LASTGF.at)) + '):<div class="mbs" style="margin:4px 0">' + gfText(LASTGF.gf) + '</div><div class="mbact"><button type="button" data-act="reapply">↻ Apply it again</button></div>', mood: 'happy'};
      return {h: 'You haven’t used any filter yet that I could see. 🙈 Try “filter muj + smu” or “only mba and bba”.', mood: 'conf'};
    }
    const explicit = /\bfilters?\b|\bfiltered\b|\bapply\b|\bnarrow\b|\bfocus\b|\brestrict\b/.test(low);
    const weak = /\bonly\b|\blimit\b/.test(low);
    const showish = weak || /^(show|display|view|open|switch|go|see)\b/.test(low) || /\b(show me|take me|open|switch to)\b/.test(low);
    if (/\b(how many|count|total|number of|percent|percentage|who|when|status|phone|email|follow|created|worked|list)\b/.test(low) && !explicit) return null;
    if (/\breport\b/.test(low)) return null;
    const VG = valueGroups(U.T, U.C, {});
    const ids = Object.keys(VG.G);
    if (!ids.length) {
      if (explicit && /\bfilters?\b/.test(low)) return {h: 'Tell me what to filter, for example “filter muj + smu + amity”, “only mba and bba” or “filter batch january 2025”. 🐒', mood: 'idle', chips: ['Filter by university', 'What filter is on?', 'Clear filters']};
      return null;
    }
    // bare list of values, or explicit/show verb
    const rest = U.T.filter((t, i) => !VG.used[i] && !STOP.has(t) && !LEXV[t] && !isTimeWord(t) && t.length > 2 && !/^(filters?|filtered|only|apply|narrow|focus|restrict|limit|view|display|open|switch|show|see|deals?|students?|and|plus|also|add|include)$/.test(t));
    const bare = !explicit && !showish && U.T.every((t, i) => VG.used[i] || /^(and|or|plus|with|the|a|only|also|deals?|students?|for|of)$/.test(t));
    if (!(explicit || showish || bare)) return null;
    if (showish && !explicit && attrsOf(U.s).length) return null;
    const next = {};
    ids.forEach(id => { next[id] = VG.G[id].vals; });
    // keep only values that exist in the dashboard filter lists
    const defs = fieldDefs(); const fixed = {};
    ids.forEach(id => { const d = defs.find(x => x.id === id); if (!d) return; const have = new Set(allRows.map(r => fv(r, d.key))); const ok = next[id].filter(v => have.has(v)); if (ok.length) fixed[id] = ok; });
    if (!Object.keys(fixed).length) return {h: 'I understood the filter, but that field isn’t on your filter bar. 🙈', mood: 'conf'};
    const add = /\b(also|add|include|along with|as well|too|plus)\b/.test(low) && Object.keys(gfNow()).length;
    applyGF(fixed, !!add);
    const miss = rest.slice(0, 3);
    const total = gfRows.length;
    const dim = gfText(gfNow()).split(' · ').join('<br>');
    return {h: '✅ Filter applied on the dashboard:<div class="mbs" style="margin:4px 0">' + dim + '</div>Showing <b>' + fmtN(total) + '</b> of <b>' + fmtN(allRows.length) + '</b> deals.' + (Object.keys(gfNow()).length > Object.keys(fixed).length ? '<div class="mbs" style="margin-top:4px">Your other filters are still on (they combine). Say “clear filters” to start fresh.</div>' : '') + (miss.length ? '<div class="mbs" style="margin-top:4px">I couldn’t match “' + miss.map(E).join('”, “') + '” to anything, so I ignored it.</div>' : '') + '<div class="mbact"><button type="button" data-nav="#/master/sheet">Open these deals</button><button type="button" data-act="clearf">🧹 Clear filters</button></div>', mood: 'happy', chips: ['What filter is on?', 'Clear filters', 'How many deals?']};
  }

  /* ---- reports ---- */
  const kwHit = (low, words) => low.split(' ').some(t => t.length >= 3 && words.some(w => t === w || (t.length >= 5 && near(t, w))));
  function reportTarget(low) {
    if (kwHit(low, ['percentage', 'percent']) && /\bre ?reg|registration|rereg/.test(low)) return 'rr';
    if (/\bre ?reg|\brereg|re-?registration|registration tracker/.test(low)) return 'reg';
    if (kwHit(low, ['documents', 'document', 'docs'])) return 'docs';
    if (kwHit(low, ['reference', 'references', 'referral', 'referrals'])) return 'ref';
    if (kwHit(low, ['alumni', 'alumnus'])) return 'alumni';
    if (kwHit(low, ['activity', 'audit']) || /\bwho changed|change log|activity log|history of changes/.test(low)) return 'activity';
    if (kwHit(low, ['custom', 'builder'])) return 'custom';
    return null;
  }
  function axisList(low) {
    const T = low.split(' '), out = [];
    const add = (key, label) => { if (key && !out.some(o => o.key === key)) out.push({key: key, label: label}); };
    for (let i = 0; i < T.length; i++) {
      const t = T[i];
      const semN = (/^sem\w*$/.test(t) || near(t, 'semester')) && (/^\d$/.test(T[i + 1] || '') ? +T[i + 1] : /^\d$/.test(T[i - 1] || '') ? +T[i - 1] : 0);
      if (semN && /\b(status|paid|fee|fees|payment)\b/.test(T.slice(i - 1, i + 4).join(' '))) { const c = cols.find(x => x.role === 'semstat' + semN); if (c) { add(c.key, 'Semester ' + semN + ' status'); for (let j = i + 1; j <= i + 3; j++) if (/^(status|statuses|paid|fee|fees|payment)$/.test(T[j] || '')) T[j] = ''; continue; } }
      if (/^(universit\w*|unis?|colleges?|univ)$/.test(t) || near(t, 'university') || near(t, 'universities')) add(uniKey(), 'University');
      else if (/^(programs?|programmes?|courses?)$/.test(t) || near(t, 'programs')) add(progKey(), 'Program');
      else if (/^(batch|batches|intake|intakes)$/.test(t)) add(batchKeyG(), 'Batch');
      else if (/^(mode|modes)$/.test(t) || (t === 'payment' && !/^sem/.test(T[i - 1] || '')) || /^(payment)$/.test(t) && /mode/.test(T[i + 1] || '')) add(modeKey(), 'Mode of payment');
      else if (/^(stage|stages|status|statuses|pipeline)$/.test(t) && !/^sem/.test(T[i - 1] || '')) add('STAGE_ID', 'Stage');
      else if (/^(team|teams)$/.test(t) && !SCOPE) { const c = colByRole('team'); if (c) add(c.key, 'Team'); }
      else if (/^(lead)$/.test(t) && /status|statuses/.test(T[i + 1] || '')) { const c = colByRole('lead'); if (c) { add(c.key, 'Lead status'); T[i + 1] = ''; } }
      else if (/^(semester|sem|sems|semesters)$/.test(t) && !/^\d$/.test(T[i + 1] || '') && !/^\d$/.test(T[i - 1] || '')) add(semKeyCur(), 'Current semester');
    }
    return out;
  }
  function reportReply(U, raw) {
    const low = norm(raw);
    const navVerb = /\b(open|show|take me|go to|goto|view|see|display|navigate|jump)\b/.test(low), tg0 = reportTarget(low);
    const hasReport = tg0 === 'activity' || (navVerb && tg0 && (tg0 !== 'docs' || /pending/.test(low)) && !/\bhow many|count|status of|phone\b/.test(low)) || /\breports?\b|\bpivot\b|\bcross ?tab\b|\bbreakdown\b|\bbreak down\b|\btable of\b/.test(low) || kwHit(low, ['report', 'reports']);
    if (!hasReport) return null;
    if (/\bhow\b.*\b(report|reports)\b.*\b(work|works|use|used)\b/.test(low)) return null;
    const nav = id => REPORTS.some(r => r.id === id);
    const VG = valueGroups(U.T, U.C, {});
    const qp = new URLSearchParams();
    const defs = fieldDefs();
    Object.keys(VG.G).forEach(id => { const d = defs.find(x => x.id === id); if (!d) return; const have = new Set(allRows.map(r => fv(r, d.key))); const ok = VG.G[id].vals.filter(v => have.has(v)); if (ok.length) qp.set(id, ok.join(MS)); });
    const flt = qp.toString();
    const fnote = Object.keys(VG.G).length ? '<div class="mbs">Filters carried over: ' + gfText(Object.fromEntries(Object.keys(VG.G).map(id => [id, VG.G[id].vals]))) + '</div>' : '';
    const create = /\b(create|build|make|generate|prepare|design|new|need|want|draw|give me|get me)\b/.test(low);
    const axes = axisList(low);
    const target = reportTarget(low);
    const list = () => REPORTS.map(r => '<button type="button" data-nav="#/reports/' + E(r.id) + '">' + r.icon + ' ' + E(r.title) + '</button>').join('');
    // build a custom report
    if (create && axes.length && (!target || target === 'custom')) {
      const r1 = axes[0], c1 = axes[1] || (axes[0].key === 'STAGE_ID' ? {key: uniKey(), label: 'University'} : {key: 'STAGE_ID', label: 'Stage'});
      const p = new URLSearchParams(); p.set('r1', r1.key); p.set('c1', c1.key);
      if (axes[2]) p.set('r2', axes[2].key); if (axes[3]) p.set('c2', axes[3].key);
      qp.forEach((v, k) => p.set(k, v));
      const hash = '#/reports/custom?' + p.toString();
      setTimeout(() => { location.hash = hash; }, 250);
      return {h: '🧩 Built it in the <b>Custom report builder</b>:<ul class="mbl2"><li>Down the side: <b>' + E(r1.label) + '</b>' + (axes[2] ? ' → <b>' + E(axes[2].label) + '</b>' : '') + '</li><li>Across the top: <b>' + E(c1.label) + '</b>' + (axes[3] ? ' → <b>' + E(axes[3].label) + '</b>' : '') + '</li></ul>' + fnote + '<div class="mbs">It’s a live view, not a saved report. Bookmark the page to keep it, or press “Download CSV” there.</div>', mood: 'happy', chips: ['Show all reports', 'What filter is on?', 'How many deals?']};
    }
    if (create && !axes.length && !target) {
      return {h: 'Sure! Tell me what to put down the side and across the top, for example:<ul class="mbl2"><li>“create a report of university by stage”</li><li>“make a report of program vs sem 1 status for muj + smu”</li><li>“build a report of batch by mode of payment”</li></ul>Or open one of the ready-made reports:<div class="mbact">' + list() + '</div>', mood: 'idle', chips: ['Create report university by stage', 'Show all reports']};
    }
    if (target && nav(target)) {
      const def = REPORTS.find(r => r.id === target);
      const hash = '#/reports/' + target + (flt ? '?' + flt : '');
      setTimeout(() => { location.hash = hash; }, 250);
      return {h: (create ? 'That report already exists, so I’m taking you there: ' : 'Opening ') + def.icon + ' <b>' + E(def.title) + '</b>… 🐒' + fnote + (create ? '<div class="mbs">If you want a different table, say “create a report of X by Y”.</div>' : ''), mood: 'happy', chips: ['Show all reports', 'How many deals?']};
    }
    if (axes.length && !create) {
      // "report of university by stage" without a create verb → still build it
      return reportReply(U, 'create ' + raw);
    }
    setTimeout(() => { location.hash = '#/reports'; }, 250);
    return {h: 'Here are the reports I know. Tap one to open it:<div class="mbact">' + list() + '</div>', mood: 'happy', chips: ['Create report university by stage', 'Documents pending report']};
  }

  /* ---- editing by phone / email: guide, never edit ---- */
  function guideEdit(list, U, how) {
    if (list.length > 1) {
      return {h: '🐒 Ooh ooh! Editing is <b>your</b> job, boss. I only swing around the data! This matches <b>' + list.length + '</b> leads. Tap the one you want and I’ll open it for you:<div class="mbact">' + list.slice(0, 8).map(r => '<button type="button" data-act="form" data-id="' + E(r.ID) + '">' + E(nameOf(r)) + ' · #' + E(r.ID) + '</button>').join('') + '</div>', mood: 'happy'};
    }
    const r = list[0];
    let want = [];
    try { const parsed = parseEdit(U, r); want = parsed.items.map(it => '<b>' + E(it.col.label) + '</b> → <em>' + E(it.v.disp) + '</em>'); } catch (e) {}
    const steps = [];
    steps.push('Set ' + (want.length ? want.join(', ') : 'the field you want to change') + '.');
    steps.push('Pick the <b>Next follow-up</b> date.');
    steps.push('Write a <b>comment</b> and your <b>name</b>.');
    steps.push('Press <b>Save</b>.');
    S.edit = null; S.pending = null; S.deal = r; S.via = how || 'name';
    try { openEdit(r); const b = document.getElementById('mBanner'); if (b) { b.textContent = '🐒 Monkey bot: ' + steps.map((x, i) => (i + 1) + '. ' + x.replace(/<[^>]+>/g, '')).join('  ') ; b.hidden = false; } } catch (e) {}
    setTimeout(() => { try { openPanel(false); } catch (e) {} }, 200);
    return {h: '🐒 Ooh ooh! That one’s <b>your work</b>, boss. I only swing around the data! I opened <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + ') for you. Quick drill:<ol class="mbl2">' + steps.map(x => '<li>' + x + '</li>').join('') + '</ol>I’ll be right here if you need me. 🍌', mood: 'happy'};
  }

  /* ---- everyday questions & how-tos ---- */
  const WD = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  function kbReply(U, raw) {
    const low = norm(raw), s = U.s;
    const R = (h, mood, chips) => ({h: h, mood: mood || 'happy', chips: chips});
    const t = new Date();
    if (/\b(today'?s? date|date today|what.?s the date|what date|what day|which day|current date|date now|todays date)\b/.test(low)) return R('Today is <b>' + WD[t.getDay()] + ', ' + dNice(t) + '</b>. 📅');
    if (/\b(what time|current time|time now|time is it|whats the time|time please)\b/.test(low)) return R('It’s <b>' + ((t.getHours() % 12) || 12) + ':' + pad(t.getMinutes()) + ' ' + (t.getHours() >= 12 ? 'pm' : 'am') + '</b> on your device. 🕐');
    if (/\b(what.?s (happening|going on|new|up)|what is (happening|going on)|what am i (doing|looking|viewing|seeing)|where am i|what (did|have) i (just )?(do|done|open|opened|change|changed)|any (news|updates?|changes)|what.?s new|are you watching|what are you (seeing|watching)|current page|which page|recent activity|what happened)\b/.test(low)) return R(watchSummary(), 'happy', ['Overdue follow-ups', 'Latest deal created']);
    if (/\b(who am i|my name|who is logged|logged in as|my login|my username|which user|my role|my access|what access|what can i see|which team am i)\b/.test(low)) return R('You’re <b>' + E(ME || 'a user') + '</b>. ' + (SCOPE ? 'This is a team login, so you see only the deals of team <b>' + E(SCOPE) + '</b>' : 'You see <b>all</b> deals') + ' (' + fmtN(allRows.length) + ' loaded).');
    if (/\b(what|which)\b.*\b(fields?|things|columns?)\b.*\b(edit|change|update)\b|\bwhat can (i|you) (edit|change|update)\b|\bwhich fields\b/.test(low)) { const ed = colsEd().map(c => c.label); return R(ed.length ? 'With your login I can edit: ' + list_(ed.map(E)) + '<div class="mbs">Every save needs a next follow-up date, a comment and a name.</div>' : 'Your login is read-only, so I can’t edit anything. 🙈', 'happy'); }
    if (/\bwho (is|s) (the |your |our )?(admin|boss|owner|developer|creator)\b/.test(low)) return R('The <b>admin</b> is the person who set up this dashboard and trained me. 🐒 If I can’t answer, tell him — I’ll be crying in the corner.');
    if (/\b(who|what)\b.*\b(made|built|created|developed|trained|programmed|designed|owns?|is)\b.*\b(you|ur|your)\b|\bwho is your (admin|boss|maker|creator|developer|owner)\b|\byour (admin|creator|maker|developer)\b/.test(low)) return R('My <b>admin</b> built me and keeps me in the jungle. 🌴 If I don’t know something, it’s his fault — tell him to train me! 🍌');
    if (/\b(are you|r u|u r|you are)\b.*\b(ai|a\.i|chatgpt|gpt|claude|robot|real|human|bot|alive|smart|intelligent|monkey)\b/.test(low)) return R('I’m a <b>rule-based monkey bot</b> 🐒 — not a human, not a big AI. I read this dashboard’s data and follow rules my admin taught me. I get confused easily, so be nice!');
    if (/\bhow old (are|r) (you|u)\b|\byour age\b|\bwhen were you born|\bwhere do you live|\bwhere are you from|\byour (family|friend|girlfriend|boyfriend|wife|husband)\b/.test(low)) return R('I live inside this dashboard, rent-free, paid in bananas. 🍌 Age: ageless.');
    if (/\b(i love you|love u|luv u|marry me|be my friend|you are cute|ur cute|you are sweet)\b/.test(low)) return R('Aww! 🙈 *blushes in monkey* I love you too — but I love clean data even more. 🍌');
    if (/\b(stupid|dumb|idiot|useless|worst bot|bad bot|nonsense|shut up|hate you|fool|donkey)\b/.test(low)) return R('😢 Ouch, that hurt my banana. I’m trying my best! Tell me what I got wrong and the admin will train me.', 'cry');
    if (/\b(good bot|well done|great job|good job|nice work|you are smart|you are clever|clever|smart bot|brilliant|you rock|superb|excellent)\b/.test(low)) return R('Yay! 🎉 Thank you! *does a tiny backflip* 🐒');
    if (/\b(sorry|my bad|oops|apologies|pardon)\b/.test(low) && low.split(' ').length <= 4) return R('No worries at all! 🍌 Monkeys forgive fast.');
    if (/\b(banana|bananas|favourite food|favorite food|do you eat|hungry)\b/.test(low) && !/\bdeal|student|count/.test(low)) return R('Bananas! 🍌🍌🍌 Forget deals, I’d trade ten universities for one ripe banana.');
    if (/\b(what is|whats|what s|explain|tell me about|about|describe)\b.*\b(this (site|website|dashboard|page|app|portal|tool)|pranav pipeline|the dashboard|the website)\b|\bwhat does this (site|website|dashboard|do)\b/.test(low)) return R('This is the <b>Pranav Pipeline dashboard</b> — a live view of every deal (student) in the Bitrix24 pipeline: stages, universities, programs, semester payments and follow-ups. You can filter, open reports, edit deals (with a comment) and ask me. 🐒', 'happy', ['How many deals?', 'Show all reports', 'What can you do?']);
    if ((/\b(data|dashboard|site|website)\b.*\b(old|fresh|stale|updated|refreshed|latest|sync|synced|current|live)\b|\b(last|when)\b.*\b(refresh|refreshed|sync|synced)\b|\bhow old\b.*\bdata\b/.test(low)) && !/\bwho\b|\bstudent\b|\bdeal #?\d|status of\b/.test(low)) { const u = document.getElementById('updated'); return R('The data was loaded: <b>' + E(u ? u.textContent.replace(/^Updated\s*/i, '') : 'a little while ago') + '</b>.<div class="mbs">To get the newest data: account menu (your name, top right) → <b>Refresh data</b>. Your own saves show up straight away.</div>'); }
    if (/\b(how|where)\b.*\b(refresh|reload)\b|\brefresh data\b|\bupdate the data\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Refresh data</b>. It pulls the latest from Bitrix24. Your own saves show up instantly without that. 🔄');
    if (/\b(log ?out|sign ?out|logoff|log off)\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Sign out</b>. 👋');
    if (/\b(change|reset|forgot|new|update)\b.*\bpassword\b|\bpassword\b.*\b(change|reset|forgot)\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Change password</b> (at least 8 characters). 🔑 If you forgot it completely, ask the admin to reset it.');
    if (/\b(export|download|csv|excel|spreadsheet|xls)\b/.test(low) && !/\bhow many|count\b/.test(low)) return R('Open <b>All deals</b> (or any report) and press <b>Download CSV</b> — it opens in Excel. 📥 Filters you set are respected.', 'happy', ['Show all reports']);
    if (/\b(how|where)\b.*\b(search|find)\b|\bctrl ?k\b|\bshortcut\b|\bsearch (bar|box)\b/.test(low)) return R('Use the <b>search bar at the top</b> (or press <b>Ctrl + K</b>) and type a student’s name, phone, email or university. 🔍');
    if (/\bhow\b.*\b(edit|update|change|modify)\b.*\b(deal|lead|student|status|record)\b|\bhow (do|can|to)\b.*\bedit\b/.test(low)) return R('Two ways:<ul class="mbl2"><li><b>With me:</b> say “make sem 1 status paid for Rahul”. I’ll ask for the follow-up date, confirm, save and add a comment.</li><li><b>By hand:</b> open <b>All deals</b>, click the student, change the fields, pick the <b>Next follow-up</b>, add a comment and your name, then Save.</li></ul>');
    if (/\b(dark|light)\s*(mode|theme)\b|\btheme\b/.test(low)) return R('The dashboard follows your <b>device’s dark/light setting</b>. 🌗 Change it in your phone/computer settings.');
    if (/\b(delete|remove|erase)\b.*\b(deal|lead|student|record)\b/.test(low)) return R('I can’t delete deals. 🙊 I only <b>edit</b> them. Deleting must be done in Bitrix24 by someone with permission.', 'cry');
    if (/\b(create|add|new|make)\s+(a\s+|the\s+|one\s+)?(new\s+)?(deal|lead|student|record|entry)\b/.test(low) && !/\breport|latest|last|newest|created|how many|count/.test(low)) return R('I can’t create new deals — I only edit existing ones. 🙈 New deals come from Bitrix24.', 'cry');
    if (/\b(send|write|draft)\b.*\b(email|mail|whatsapp|sms|message)\b|\b(call|dial|ring)\b.*\b(student|him|her|them|phone|number)\b/.test(low)) return R('I’m a monkey, not a post office! 🐒 I can’t send messages or make calls. But I can tell you a student’s phone or email.');
    if (/\b(undo|revert|rollback|roll back)\b/.test(low)) return R('I can’t undo a save. 🙈 But you can just tell me to change it back (for example “make sem 1 status not paid for Rahul”). Every change is recorded in the <b>Activity log</b> report.');
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\b(overdue|stale)\b/.test(low) && !/\bhow many\b|percent|%|count/.test(low)) return R('<b>Follow-up overdue</b> = an active student who has comments, but the latest comment is <b>' + (typeof STALE_DAYS !== 'undefined' ? STALE_DAYS : 15) + '+ days old</b> (or has no date). Closed and alumni deals are not counted.', 'happy', ['How many overdue?']);
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\bnot contacted\b/.test(low)) return R('<b>Not contacted</b> = an active student whose <b>Comment History is empty</b> — nobody has commented on that deal yet.', 'happy', ['How many not contacted?']);
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\balumni\b/.test(low)) return R('<b>Alumni</b> = students who finished their programme (their stage is grouped as “Completed”).');
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\b(re ?reg\w*|re registration)\b/.test(low)) return R('<b>Re-registration</b> = the student paid for the next semester on time. It’s <b>Done</b> when today falls between the Last Payment Date and the Next Payment Date, and <b>Pending</b> when today is past the Next Payment Date. See the <b>Re Reg Percentage</b> report.', 'happy', ['Open re reg percentage report']);
    if (/\b(what|meaning|mean|define|explain)\b/.test(low) && /\b(active|closed)\b/.test(low) && !/\b(how many|count|list|percent|percentage|%|share)\b/.test(low) && /\b(mean|meaning|define|explain|definition|what is|what are|whats)\b/.test(low) && low.split(' ').length <= 8) return R('<b>Closed</b> deals are in a finished stage (completed, dropped, refund, lost…). Everything else is <b>Active</b>.');
    if (/\b(what|which)\b.*\b(stages?)\b.*\b(are there|exist|available|do we have|have)\b|\blist (of )?(all )?stages\b/.test(low)) { const g = groupBy(allRows, 'stage'); return R('Stages in use:' + list_(g.map(x => E(x.k) + ' <span class="mbn">' + fmtN(x.n) + '</span>'))); }
    if (/\b(thank you so much|thanks a lot|many thanks)\b/.test(low)) return R('You’re welcome! 🍌🍌');
    if (/\b(i am|im|i m)\b.*\b(bored|tired|sad|angry|happy|excited)\b/.test(low) && low.split(' ').length <= 5) return R('Aww! 🐒 Here, have a virtual banana 🍌. Then let’s clear some follow-ups, shall we?', 'happy', ['Overdue follow-ups']);
    if (/\b(test|testing|check|ping)\b\W*$/.test(low) && low.split(' ').length <= 2) return R('I’m awake and swinging! 🐒 Ask me something.');
    return null;
  }

  /* ================= step-by-step guides ================= */
  const ol_ = a => '<ol class="mbl2">' + a.map(x => '<li>' + x + '</li>').join('') + '</ol>';
  const tips_ = a => '<div class="mbs" style="margin-top:6px"><b>Good to know</b><br>' + a.map(x => '• ' + x).join('<br>') + '</div>';
  const GUIDES = {
    edit: {t: 'How to update or edit a lead (deal)', h: () =>
      '<b>Way 1: ask me (fastest)</b>' + ol_([
        'Tell me <b>who</b> and <b>what</b>: “make sem 1 status paid for Rahul Sharma” or “change stage to Documents for deal 103”. Spelling mistakes are fine.',
        'If several students match, <b>tap the right one</b>.',
        'I ask for the <b>next follow-up date</b> (every save needs one). Say “tomorrow”, “20 Oct”, “in 3 days” or “next monday 4pm”.',
        'I repeat what I understood: the old → new value, the follow-up date and the comment I will write. <b>Check it carefully.</b>',
        'Say <b>yes</b> to save, or <b>no</b> to cancel. To fix something, just tell me (“make it sem 2”, “follow up on 25 Oct”).',
        'I save to Bitrix24 and add a comment like “Monkey bot: &lt;your name&gt; asked me to update this deal…”.'
      ]) +
      '<b>Way 2: by hand</b>' + ol_([
        'Find the student: type in the <b>search bar</b> at the top (or press <b>Ctrl + K</b>), or open <b>All deals</b>. Click the student to open the <b>edit form</b>.',
        'Change what you need. Changed fields get highlighted.',
        'Pick a new <b>Next Follow-up</b> date. It is required.',
        'Type a <b>comment</b> (what happened or what will happen). It is required.',
        'Check <b>Comment by (your name)</b>. It is remembered for next time.',
        'Press <b>Save to Bitrix24</b>. If Save is greyed out, the line at the bottom of the form tells you what is missing.'
      ]) +
      tips_(['Fixing only the <b>name, phone or email</b> needs no comment and no follow-up date.', 'If someone changed the same field in Bitrix24 meanwhile, you’ll see a conflict message. Nothing is saved; reopen the deal and redo it.', 'Some fields are read-only (for example Next Payment Date (P) and Comment History).', 'Team logins can only see and edit their own team’s deals.', 'I never edit from a phone number or email. I open the form for you instead.']),
      c: ['Edit a deal now', 'Change a semester status', 'Fix a phone number']},
    sem: {t: 'How to change a semester payment status', h: () =>
      '<b>With me:</b> say “make sem 3 status paid for Priya” (or “sem 2 not paid”). I’ll ask the follow-up date, confirm, save and comment.' +
      '<br><b>By hand:</b>' + ol_(['Open the student (search bar or All deals).', 'In the form, find <b>Semester 1 … Semester 6 Status</b> and choose <b>Paid</b> or <b>Not Paid</b>.', 'Pick a new <b>Next Follow-up</b> date.', 'Write a <b>comment</b> and check your <b>name</b>.', 'Press <b>Save to Bitrix24</b>.']) +
      tips_(['A semester with no value shows “Not Paid” by default. That default gets saved together with your next save.', 'The <b>Current semester</b> field is a different field: it says which semester the student is in.']),
      c: ['Edit a deal now']},
    stage: {t: 'How to change a stage, lead status or documents status', h: () =>
      ol_(['Open the student (search bar or All deals).', 'Choose the new value in the dropdown: <b>Stage</b>, <b>Lead Status</b> or <b>Documents Status</b>.', 'Pick a new <b>Next Follow-up</b> date, write a <b>comment</b>, check your <b>name</b>.', 'Press <b>Save to Bitrix24</b>.']) +
      '<div class="mbs" style="margin-top:6px">With me: “set stage to documents for Rahul”, “change lead status to hot for deal 103”.</div>' +
      tips_(['The stage decides whether a student counts as <b>Active</b>, Completed, Dropped or Refund in the reports.']),
      c: ['Edit a deal now']},
    followup: {t: 'How to set the next follow-up date', h: () =>
      ol_(['Open the student.', 'Pick the date in <b>Next Follow-up</b>. It is required for every normal save.', 'Add a comment about the call and press <b>Save</b>.']) +
      '<div class="mbs" style="margin-top:6px">With me: “follow up with Rahul on 25 Oct”.</div>' + tips_(['A deal shows under <b>Follow-up overdue</b> when it has comments but the latest one is 15+ days old. A new comment fixes that.']),
      c: ['What does overdue mean?']},
    comment: {t: 'How to add a comment', h: () =>
      ol_(['Open the student.', 'Type in <b>Comments</b>. It is saved to Bitrix24 with today’s date and time.', 'Check <b>Comment by (your name)</b> and pick a new <b>Next Follow-up</b> date.', 'Press <b>Save</b>. The comment appears in the Comment History.']) +
      tips_(['A comment without any other change is fine; it still needs the follow-up date.']),
      c: ['Edit a deal now']},
    contact: {t: 'How to fix a name, phone or email', h: () =>
      ol_(['Open the student.', 'Edit <b>Student name</b>, <b>Phone</b> or <b>Email</b>. Separate several phones or emails with a comma.', 'Press <b>Save to Bitrix24</b>.']) +
      tips_(['If you change <b>only</b> these three, no comment, name or follow-up date is needed.', 'I won’t edit these for you. I can open the form though: “take me to Rahul”.']),
      c: ['How to find a student']},
    conflict: {t: 'What to do if Save is greyed out or fails', h: () =>
      '<b>Save is greyed out:</b> read the line at the bottom of the form. It says what is missing: a change, a comment, your name or a new Next Follow-up date.' +
      '<br><b>“Someone changed it meanwhile” (conflict):</b>' + ol_(['Nothing was saved.', 'Close the form and open the deal again to see the latest values.', 'Redo your change and save.']) +
      '<br><b>Session ended:</b> sign in again, then retry. If it still fails, tell the admin what the message said.',
      c: ['How to edit a lead']},
    search: {t: 'How to find a student', h: () =>
      ol_(['Click the <b>search bar</b> at the top, or press <b>Ctrl + K</b>.', 'Type a name, phone, email or university.', 'Click a result to open the deal.']) + '<div class="mbs" style="margin-top:6px">Or ask me: “status of Rahul”, “take me to Rahul”, “phone of 9876…”.</div>',
      c: ['How to find a student']},
    filter: {t: 'How to filter the dashboard', h: () =>
      ol_(['On the Dashboard, Master overview or a university page, use the <b>Filters</b> bar: University, Program, Batch, Mode of payment, Stage, Current semester, Team.', 'Each filter allows <b>several choices</b>. The counters update straight away.', 'Press <b>Clear filters</b> to reset.']) +
      '<div class="mbs" style="margin-top:6px">With me: “filter muj + smu + amity”, “only mba and bba”, “clear filters”.</div>',
      c: ['What filter is on?', 'Clear filters']},
    report: {t: 'How to open or build a report', h: () =>
      '<b>Open one:</b> click <b>Reports</b> in the sidebar and choose it (Re-registration tracker, Documents pending, Reference, Re Reg Percentage, Alumni, Activity log).' +
      '<br><b>Build your own:</b>' + ol_(['Open <b>Custom report builder</b>.', 'Choose what goes <b>down the side</b> and <b>across the top</b> (university, program, batch, stage, semester status…).', 'Optionally narrow it with the filters.', 'Click any number to see those students. Press <b>Download CSV</b> to export.']) +
      '<div class="mbs" style="margin-top:6px">With me: “create a report of university by stage for muj + smu” or “open the alumni report”.</div>',
      c: ['Show all reports', 'Create report university by stage']},
    csv: {t: 'How to download data as CSV (Excel)', h: () =>
      ol_(['Open <b>All deals</b> or any report. Apply the filters you want first.', 'Press <b>Download CSV</b>.', 'Open the file in Excel or Google Sheets.']), c: ['Show all reports']},
    activity: {t: 'How to see who changed what', h: () =>
      ol_(['Click your <b>name (top right)</b> → <b>Activity log</b> (team logins see <b>Your activity</b>).', 'Filter by person, date or search text.', 'Each line shows the deal, each field’s old → new value, the comment and who made it.']), c: ['Open the activity log']},
    refresh: {t: 'How to refresh the data', h: () => ol_(['Click your <b>name (top right)</b>.', 'Press <b>Refresh data</b>. It pulls the latest from Bitrix24.']) + tips_(['Your own saves show up instantly without refreshing.', 'The top bar shows how old the data is.']), c: ['How old is the data?']},
    password: {t: 'How to change your password or profile', h: () => ol_(['Click your <b>name (top right)</b>.', '<b>Change password</b>: enter the old one and a new one (at least 8 characters).', '<b>Your profile</b>: update your name, phone or colour.']) + tips_(['Forgot it completely? Ask the admin to reset it.']), c: []},
    signout: {t: 'How to sign out', h: () => ol_(['Click your <b>name (top right)</b>.', 'Press <b>Sign out</b>.']), c: []},
    overdue: {t: 'How to work through follow-up overdue', h: () =>
      ol_(['Click <b>Follow-up overdue</b> in the sidebar (or ask me “overdue deals”).', 'Open a student, call them, then add a <b>comment</b> and a new <b>Next Follow-up</b> date.', 'Save. The deal leaves the overdue list.']) + tips_(['Overdue = active student whose latest comment is 15+ days old.', '<b>Not contacted</b> (also in the sidebar) = active students with no comment at all.']), c: ['Overdue follow-ups']},
    rereg: {t: 'How the Re Reg Percentage works', h: () =>
      ol_(['Only students in an <b>Active</b> stage are looked at.', 'No <b>Last Payment Date</b> → not counted at all.', 'Today between Last and Next Payment Date → <b>Done</b>.', 'Everything else (no Next Payment Date, or past it) → <b>Pending</b> (delayed).', '<b>Re-reg % = Done ÷ (Done + Pending).</b>']), c: ['Open re reg percentage report']},
    bot: {t: 'How to use me (Monkey bot)', h: () => HELP, c: ['Edit a deal now', 'Deals created this month']}
  };
  const GUIDE_MENU = [['edit', 'Edit a lead'], ['sem', 'Semester payment status'], ['stage', 'Stage / lead / documents status'], ['followup', 'Follow-up date'], ['comment', 'Add a comment'], ['contact', 'Fix name / phone / email'], ['search', 'Find a student'], ['filter', 'Filters'], ['report', 'Reports'], ['csv', 'Download CSV'], ['activity', 'Activity log'], ['overdue', 'Overdue follow-ups'], ['refresh', 'Refresh data'], ['password', 'Password / profile']];
  function howTopic(low) {
    const ed = /\b(edit|update|change|modify|fix|correct|set|mark|alter|add)\b/.test(low);
    if (/\b(conflict|someone else (changed|edited)|changed meanwhile|cannot save|can.?t save|couldn.?t save|save (button )?(is )?(disabled|greyed|grayed|not working)|not saved|save fails?|failed to save)\b/.test(low)) return 'conflict';
    if (/\b(phone|mobile|email|mail|student name|contact)\b/.test(low) && ed) return 'contact';
    if (/\b(sem|semester|installment|fee status|payment status)\b/.test(low) && /\b(paid|status|payment|edit|update|change|mark|set)\b/.test(low) && !/\b(report|filter)\b/.test(low)) return 'sem';
    if (/\bfollow ?-?ups?\b/.test(low) && /\b(overdue|pending|due|list|work through|clear)\b/.test(low)) return 'overdue';
    if (/\bfollow ?-?ups?\b/.test(low)) return 'followup';
    if (/\b(comment|note|remark)\b/.test(low)) return 'comment';
    if (/\b(stage|lead status|documents? status|docs status)\b/.test(low) && ed) return 'stage';
    if (/\bre ?reg\w*|re registration\b/.test(low)) return 'rereg';
    if (/\b(overdue|not contacted)\b/.test(low)) return 'overdue';
    if (/\b(search|find|look ?up|locate)\b/.test(low)) return 'search';
    if (/\bfilters?\b/.test(low)) return 'filter';
    if (/\b(csv|export|excel|download)\b/.test(low)) return 'csv';
    if (/\b(report|reports|pivot|custom table)\b/.test(low)) return 'report';
    if (/\b(activity|audit|who changed|change log)\b/.test(low)) return 'activity';
    if (/\b(refresh|reload|latest data|update the data)\b/.test(low)) return 'refresh';
    if (/\b(password|profile)\b/.test(low)) return 'password';
    if (/\b(log ?out|sign ?out)\b/.test(low)) return 'signout';
    if (/\b(you|bot|monkey)\b/.test(low) && /\b(use|work|works|talk|ask|chat)\b/.test(low)) return 'bot';
    if (/\b(edit|update|change|modify|alter)\b/.test(low) && /\b(lead|deal|student|record|details|data|information|info|entry)\b/.test(low)) return 'edit';
    if (ed && /\b(a|the|an|one)\b/.test(low) && /\b(lead|deal)\b/.test(low)) return 'edit';
    return null;
  }
  function howReply(raw) {
    const low = norm(raw);
    const howIntent = /\b(how (do|can|should|to|would|will)|how i|steps?|guide|guidance|process|procedure|instructions?|tutorial|walk me through|teach me|explain how|what should i do|what do i do|where (do|can|should) i|way to|help me (to |with )?(edit|update|change|find|filter|download|add)|what is the process|how does one)\b/.test(low) || /^(how to|how)\b/.test(low) || /\b(can you (guide|teach|show) me)\b/.test(low);
    const stuck = /\b(conflict|cannot save|can.?t save|couldn.?t save|save (button )?(is )?(disabled|greyed|grayed|not working)|not saved|save fails?|failed to save)\b/.test(low);
    if (!howIntent && !stuck) return null;
    if (/\bhow (many|much|old|long|far|often|is|are|was|were|big|come)\b/.test(low)) return null;
    const id = howTopic(low);
    if (!id) {
      if (/^(how|guide|guidance|process|steps|instructions|tutorial|teach me|help me|what should i do)\b/.test(low) && low.split(' ').length <= 6) return {h: 'Happy to guide you! 🐒 What do you want to learn?<div class="mbact">' + GUIDE_MENU.map(g => '<button type="button" data-q="How to ' + E(g[1].toLowerCase()) + '">' + E(g[1]) + '</button>').join('') + '</div>', mood: 'happy', chips: ['How to edit a lead', 'How to use you']};
      return null;
    }
    const gd = GUIDES[id];
    return {h: '📘 <b>' + E(gd.t) + '</b><div style="margin-top:6px">' + gd.h() + '</div>', mood: 'happy', chips: gd.c.length ? gd.c : ['How to edit a lead', 'What can you do?']};
  }

  /* ---- data quality & percentages ---- */
  function statReply(U, raw) {
    const low = norm(raw), s = U.s;
    const F = valueFilters(U);
    const base = allRows.filter(r => F.every(f => f.test(r)));
    const ft = F.length ? filtText(F) : '';
    const line = (arr, what) => { const n = arr.length; return {h: '<b>' + numLink(arr, 'Deals that ' + what.replace(/<[^>]+>/g, '')) + '</b> deal' + (n === 1 ? '' : 's') + ft + ' ' + what + '.', mood: 'happy'}; };
    const miss = /\b(no|without|missing|empty|blank|lack|lacking)\b/.test(low) || /\bdont have\b|\bdo not have\b/.test(low);
    if (miss && /\b(phone|mobile|number|contact)\b/.test(low)) return line(base.filter(r => !String(r._PHONE || '').replace(/\D/g, '')), 'have <b>no phone number</b>');
    if (miss && /\bemail\b|\bmail\b/.test(low)) return line(base.filter(r => !String(r._EMAIL || '').trim()), 'have <b>no email</b>');
    if (miss && /\bfollow\b/.test(s)) return line(base.filter(r => !fuOf(r)), 'have <b>no follow-up date</b>');
    if (miss && /\buniversit|\buni\b/.test(low + ' ' + s)) return line(base.filter(r => uniOf(r) === UNASSIGNED), 'have <b>no university</b> set');
    if (miss && /\bprogram|\bcourse\b/.test(low + ' ' + s)) return line(base.filter(r => !val(r, 'program')), 'have <b>no program</b> set');
    if (miss && /\bbatch\b/.test(s)) return line(base.filter(r => !val(r, 'batch')), 'have <b>no batch</b> set');
    if (miss && /\bcomment|\bnote\b|\bremark\b/.test(s)) return line(base.filter(r => !lastCm(r)), 'have <b>no comments</b>');
    if (/\bduplicat\w*\b|\brepeated\b|\bsame (phone|number)\b/.test(low) && /\b(phone|number|mobile|email|deals?|students?|names?)\b/.test(low)) {
      const m = {}; base.forEach(r => { const k = String(r._PHONE || '').replace(/\D/g, '').slice(-10); if (k.length >= 8) (m[k] = m[k] || []).push(r); });
      const d = Object.keys(m).filter(k => m[k].length > 1);
      const n = d.reduce((a, k) => a + m[k].length, 0);
      if (!d.length) return {h: 'No duplicate phone numbers' + ft + '. ✨', mood: 'happy'};
      return {h: '<b>' + d.length + '</b> phone number' + (d.length === 1 ? ' is' : 's are') + ' shared by <b>' + n + '</b> deals' + ft + '. First few:' + list_(d.slice(0, 5).map(k => m[k].slice(0, 3).map(r => dealLink(r)).join(' · '))), mood: 'happy'};
    }
    if (/\b(percent|percentage|%|share|ratio|proportion)\b/.test(low) && !/\breport\b/.test(low)) {
      if (!F.length) return {h: 'Tell me which group to measure, for example “what percent of deals are active”, “percentage of sem 1 paid” or “what percent are in Amity”.', mood: 'idle'};
      const n = base.length, tot = allRows.length;
      return {h: F.map(f => E(f.label)).join(' + ') + ': <b>' + numLink(base, F.map(f => f.label).join(' + ')) + '</b> of <b>' + fmtN(tot) + '</b> deals = <b>' + (tot ? (100 * n / tot).toFixed(1) : '0') + '%</b>.', mood: 'happy'};
    }
    if (/\baverage\b|\bavg\b|\bper (university|program|batch|team)\b/.test(low) && /\b(deals?|students?)\b/.test(low + ' ' + s)) {
      const role = /\bprogram/.test(low) ? 'program' : /\bbatch/.test(low) ? 'batch' : /\bteam/.test(low) ? 'team' : 'uni';
      const g = groupBy(base, role).filter(x => x.k !== '(empty)' && x.k !== UNASSIGNED);
      if (!g.length) return null;
      const n = g.reduce((a, x) => a + x.n, 0);
      return {h: 'On average <b>' + (n / g.length).toFixed(1) + '</b> deals per ' + NOUN_LABEL[role][0] + ' (' + fmtN(n) + ' deals across ' + g.length + ' ' + NOUN_LABEL[role][1] + ').', mood: 'happy'};
    }
    return null;
  }

  async function extras(raw, U) {
    const low = norm(raw);
    { const hw = howReply(raw); if (hw) return hw; }
    // never steal a clear edit instruction
    if (U.hasSetVerb && /\b(set|make|change|update|mark|edit|modify|put|turn|convert|chnge|updte|chage)\b/.test(low) && fieldsIn(U.s, U.s).length && !/\bfilters?\b|\breport\b/.test(low)) return null;
    let r;
    if ((r = await workReply(U, raw))) return r;
    if ((r = reportReply(U, raw))) return r;
    if ((r = filterReply(U, raw))) return r;
    if ((r = kbReply(U, raw))) return r;
    if ((r = latestReply(U, raw))) return r;
    if ((r = periodReply(U, raw)) && (/\b(how many|count|number|total|list|show|which|who|deals?|students?|leads?|due|created|added|new|came|joined|follow)\b/.test(low))) return r;
    if ((r = statReply(U, raw))) return r;
    return null;
  }

  /* ---------- main brain ---------- */
  const YES = /^(y|yes|yep|yeah|yup|ya|yea|ok|okay|sure|confirm|confirmed|do it|go ahead|save|save it|correct|right|haan|ha|done|proceed|approve|accept|yes please|please do|👍|✅)\W*$/;
  const NO = /^(n|no|nope|nah|cancel|stop|dont|do not|abort|wrong|nahi|no thanks|never mind|nevermind|leave it|discard|✖|❌)\W*$/;
  async function brain(text) {
    let raw = text.trim();
    if (/\bkitn[eai]\b/i.test(raw)) raw = raw.replace(/\b(hain|hai|h)\b/gi, ' ').replace(/\b([a-z0-9&+]+)\s+(me|mein|main)\b/gi, 'in $1').replace(/\b(ka|ke|ki)\b/gi, ' ');
    raw = raw.replace(/\b(kitn[eai]|ginti|gintee)\b/gi, 'how many').replace(/\bhow\s+much\b(?=\s+(deals?|leads?|students?|universit|programs?|batch))/gi, 'how many').replace(/\b(tally|headcount|head count)\b/gi, 'count');
    if (raw.indexOf('@') < 0) raw = raw.replace(/([a-z0-9])\s+\+\s+(?=[a-z0-9])/gi, '$1 ').replace(/([a-z0-9])\+(?=[a-z])/gi, '$1 ');
    const low = norm(raw);
    // "take me to <student>" -> open that deal with its edit form
    {
      const nm = /^(?:please |pls |can you |could you )?(?:take me to|take me|open|go to|goto|navigate to|bring up|pull up|jump to|view|show me the deal of|show me deal of)\s+(?:the\s+)?(?:deal\s+(?:of\s+|for\s+)?|student\s+|edit\s+(?:form\s+)?(?:of\s+|for\s+)?)?(.+)$/i.exec(raw);
      if (nm && allRows.length && !/\b(report|reports|filter|filters|dashboard|sheet|universit\w*|programs?|batch\w*|overdue|alumni|activity|log|home|master|deals|students)\b/i.test(nm[1])) {
        const Un = understand(nm[1]);
        if (!valueGroups(Un.T, Un.C, {}).used[0] && !Object.keys(valueGroups(Un.T, Un.C, {}).G).length) {
          const fn = findDeals(Un.T, Un.C);
          const openIt = r => { S.edit = null; S.pending = null; S.deal = r; S.via = fn.how || 'name'; try { openEdit(r); } catch (e) {} setTimeout(() => { try { openPanel(false); } catch (e) {} }, 250); return {h: '📂 Opened <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + ') with its edit form. Change what you need, add the follow-up date, a comment and your name, then Save. I’ll be here if you need me. 🐒', mood: 'happy'}; };
          if (fn.list.length === 1) return openIt(fn.list[0].r);
          if (fn.list.length > 1) { S.edit = null; S.viaNext = fn.how; return showWho(fn.list, openIt); }
        }
      }
    }
    // confirmation step
    if (S.edit && S.edit.step === 'confirm') {
      if (YES.test(low)) return executeEdit();
      if (NO.test(low)) { S.edit = null; return {h: 'No problem, I cancelled it. Nothing was changed. 👍', mood: 'idle'}; }
      const d = nlDate(raw), U0 = understand(raw), p0 = parseEdit(U0, S.edit.deal);
      if (p0.items.length || (d && /\bfollow\b|\bon\b|\bby\b|tomorrow|next|in \d/.test(U0.s))) {
        const e = S.edit, fuCol = colByRole('fuupd');
        p0.items.forEach(it => { if (fuCol && it.col.key === fuCol.key) return; let old = String(e.deal[it.col.key] || ''); if (!old && /^semstat/.test(it.col.role || '')) old = 'Not Paid'; const ix = e.items.findIndex(x => x.col.key === it.col.key); const row = {col: it.col, v: it.v, old: old}; if (ix >= 0) e.items[ix] = row; else e.items.push(row); });
        if (d && (!p0.items.length || /\bfollow\b/.test(U0.s))) e.fu = d;
        return toConfirm();
      }
      { const Ux = understand(raw), fx = findDeals(Ux.T, Ux.C); if (fx.list.length || Ux.question || /\b(how many|count|filter|report|show|open|take me|who|when|what)\b/.test(low)) { S.edit = null; }
        else return {h: 'I’m waiting for a <b>yes</b> or a <b>no</b> on the change above. 🙂', mood: 'idle', chips: ['Yes', 'No']}; }
    }
    if (S.edit && S.edit.step === 'fu') {
      if (NO.test(low) || /^cancel\b/.test(low)) { S.edit = null; return {h: 'Okay, cancelled. Nothing was changed. 👍', mood: 'idle'}; }
      const d = nlDate(raw);
      if (d) { S.edit.fu = d; return toConfirm(); }
      { const Ux = understand(raw), fx = findDeals(Ux.T, Ux.C); if (fx.list.length || Ux.question || /\b(how many|count|filter|report|show|open|take me|who|when|what|edit|set|make|change)\b/.test(low)) { S.edit = null; }
        else return {h: 'I couldn’t read that as a date. 🙈 Try “tomorrow”, “20 Oct”, “in 3 days” or “next monday 4pm”.', mood: 'conf', chips: ['Tomorrow', 'In 3 days', 'Next week', 'Cancel']}; }
    }
    if (S.pending && S.pending.t === 'which') {
      if (NO.test(low) || /^cancel\b/.test(low)) { S.pending = null; return {h: 'Okay, forget it. 👍', mood: 'idle'}; }
      const U1 = understand(raw), f = findDeals(U1.T, U1.C);
      let pick = null;
      const ownIntent = attrsOf(U1.s).length || U1.hasSetVerb || /\bhow many\b|\bhow\b.*\bmany\b/.test(U1.s);
      if (ownIntent) { S.pending = null; } else {
      const num = /^\d{1,2}$/.test(low) ? +low : 0;
      if (num && S.pending.list[num - 1]) pick = S.pending.list[num - 1];
      else if (f.list.length) { const inList = f.list.filter(x => S.pending.list.some(y => y.ID === x.r.ID)); if (inList.length === 1) pick = inList[0].r; else if (f.list.length === 1) pick = f.list[0].r; }
      if (pick) { const then = S.pending.then; S.pending = null; S.deal = pick; S.via = (f && f.how) || S.viaNext || 'name'; return then(pick); }
      S.pending = null;
      }
    }
    if (S.pending && S.pending.t === 'sem') {
      const m = /\b(\d)\b/.exec(low) || (() => { const w = /\b(one|two|three|four|five|six|first|second|third|fourth|fifth|sixth)\b/.exec(low); return w ? [0, NUMW[w[1]]] : null; })();
      if (m) { const p = S.pending; S.pending = null; return p.then(+m[1]); }
      if (NO.test(low)) { S.pending = null; return {h: 'Okay, cancelled. 👍', mood: 'idle'}; }
      S.pending = null;
    }
    if (NO.test(low) && !S.edit && !S.pending) return {h: 'Okay 👍 nothing pending on my side.', mood: 'idle'};
    { const g1 = low.split(' ')[0] || ''; const nw = low.split(' ').length;
      if (nw <= 2 && g1.length >= 3) { if (near(g1, 'hello') || near(g1, 'namaste') || near(g1, 'hiii')) return {h: 'Ooh ooh! 🐒 Hello' + (ME ? ' <b>' + E(ME) + '</b>' : '') + '! Ask me about any student or a count, or tell me which lead to open for editing.', mood: 'happy', chips: baseChips()}; if (near(g1, 'thanks') || near(g1, 'thankyou')) return {h: 'Anytime! 🍌', mood: 'happy'}; } }
    // social
    for (const sc of SOCIAL) {
      if (sc[0].test(low)) {
        if (sc[1] === 'hi') return {h: 'Ooh ooh! 🐒 Hello' + (ME ? ' <b>' + E(ME) + '</b>' : '') + '! Ask me about any student or a count, or tell me which lead to open for editing.', mood: 'happy', chips: baseChips()};
        if (sc[1] === 'thanks') return {h: 'Anytime! 🍌 A banana for you too.', mood: 'happy'};
        if (sc[1] === 'bye') return {h: 'Bye bye! 🙊 I’ll be hanging around here.', mood: 'happy'};
        if (sc[1] === 'who') return {h: 'I’m <b>Monkey bot</b> 🐒, this dashboard’s helper. I answer from your live data, and when you want to edit a lead I open it for you.', mood: 'happy'};
        if (sc[1] === 'help') return {h: HELP, mood: 'happy', chips: baseChips()};
        if (sc[1] === 'ok') return {h: '👍', mood: 'idle'};
        if (sc[1] === 'howru') return {h: 'Swinging great! 🍌 What shall we look up?', mood: 'happy'};
        if (sc[1] === 'lol') return {h: '🙊', mood: 'happy'};
      }
    }
    if (!allRows.length) return {h: 'I don’t have any deals loaded yet. Open the account menu → “Refresh data”, then ask me again. 🍌', mood: 'conf'};
    const U = understand(raw);
    const f = findDeals(U.T, U.C);
    const pron = /\b(his|her|him|he|she|its|this|that|same|it)\b/.test(U.T.join(' '));
    const ex = await extras(raw, U); if (ex) return ex;
    // ---- edit ----
    const editish = U.hasSetVerb && !/^\W*(who|when|what|how many|which)\b/.test(U.s);
    if (editish || (/\bto\b|\bas\b/.test(U.s) && PAY_WORDS.test(U.s) && /\bsem\b/.test(U.s) && !U.question)) {
      if ((f.how === 'phone' || f.how === 'email') && f.list.length) return guideEdit(f.list.map(x => x.r), U, f.how);
      const go = deal => guideEdit([deal], U, f.how);
      if (f.list.length === 1) { S.deal = f.list[0].r; S.via = f.how; return go(f.list[0].r); }
      if (f.list.length > 1) { S.viaNext = f.how; return showWho(f.list, go); }
      if (curDeal() && (pron || !f.list.length)) {
        { const nm_ = /\b(?:for|of|student|named|called)\s+([a-z][a-z.]{2,})/.exec(norm(raw)); if (nm_ && !pron && !LEXV[nm_[1]] && !STOP.has(nm_[1]) && !/^(deal|this|that|current|him|her|them|same|sem|semester|status|stage|lead|leads)$/.test(nm_[1]) && !valueDictHas(nm_[1])) return {h: 'I couldn’t find a student called <b>“' + E(nm_[1]) + '”</b> 🙈 Check the spelling, or give me the full name or deal number.', mood: 'conf'}; }
        return go(curDeal());
      }
      setTimeout(() => { try { openPanel(false); const g = document.getElementById('gsearch'); if (g) g.focus(); } catch (e) {} }, 350);
      return {h: '🐒 Ooh ooh! Editing is <b>your</b> job, boss, I only swing around the data! I don’t know which lead you mean, so I opened the <b>search box</b>. Pick the lead there and its edit form opens. 🍌', mood: 'happy'};
    }
    // ---- counting ----
    const isCount = (/\b(least|fewest|lowest|smallest|most|highest|biggest|largest)\b/.test(low) && /\b(universit\w*|program\w*|batch\w*|team\w*|payment mode|deals?)\b/.test(low) && !/\b(worked|filter)\b/.test(low)) || /\b(how many|count|total|number of|no of)\b/.test(U.s.replace(/\bhow\b \bmany\b/, 'how many')) || (/\bhow\b/.test(U.s) && /\bmany\b/.test(U.s)) || /\b(each|most|least|fewest|lowest|smallest)\b/.test(U.s) && /\b(university|program|batch|team|status|mode|deal)\b/.test(U.s) || /^(list|show|display)?\s*(all )?(universit|program|batch|team)/.test(U.s);
    const cntKw = /\b(how many|count|number of|no of|total)\b/.test(raw.toLowerCase().replace(/\bno\.\s*of\b/, 'no of'));
    if (isCount && f.list.length && cntKw && !/\b(least|fewest|lowest|smallest|most|highest|biggest|largest|each)\b/.test(low)) {
      if (parseFilters(U.T, U.C, U.skip).length) return doCount(U);
      const L = f.list.map(x => x.r), n = L.length;
      return {h: '🐒 I found <b>' + numLink(L, 'Matching “' + raw.slice(0, 40) + '”') + '</b> deal' + (n === 1 ? '' : 's') + ' matching that.' + (n <= 6 ? list_(L.map(r => dealLink(r))) : '<div class="mbs">Too many to list; be more specific (full name, phone or deal number).</div>'), mood: 'happy'};
    }
    if (isCount && !f.list.length) { return doCount(U); }
    if (/^(overdue|stale|not contacted)\b/.test(U.s) || /\b(overdue|not (yet )?(contacted|worked|touched|called))\b/.test(U.s) && !f.list.length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    // ---- a student ----
    const attrs = attrsOf(U.s);
    if (!f.list.length && !attrs.length && /\b(deals?|students?)\b/.test(U.s) && !/\b(set|filter|report)\b/.test(U.s) && parseFilters(U.T, U.C, U.skip).length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    if (f.list.length === 1) {
      S.deal = f.list[0].r; S.via = f.how;
      return answerAttr(f.list[0].r, attrs, U.s);
    }
    if (f.list.length > 1) { S.viaNext = f.how; return showWho(f.list, d => answerAttr(d, attrs, U.s)); }
    if (curDeal() && attrs.length && (pron || U.T.length <= 6)) return answerAttr(curDeal(), attrs, U.s);
    if (attrs.length && /\b(who|when|status|phone|email|follow|created)\b/.test(U.s) && DOMAIN_RX.test(U.s) && (U.question || attrs.length)) {
      if (/\b(student|deal)\b/.test(U.s) || /\b(status|created|phone|email|worked)\b/.test(U.s)) {
        S.pending = {t: 'which', then: d => answerAttr(d, attrs, U.s), list: []};
        return {h: 'Sure! Which student? Tell me the name, phone number or deal number. 🐒', mood: 'idle', chips: ['Cancel']};
      }
    }
    // names not found but looks like a person lookup
    if (/\b(status|created|phone|email|who|worked|detail|follow)\b/.test(U.s) && U.T.some(t => !STOP.has(t) && !LEXV[t] && t.length > 2 && !/^\d+$/.test(t))) {
      const w = U.T.filter(t => !STOP.has(t) && !LEXV[t] && t.length > 2 && !/^\d+$/.test(t) && LEXV[fixTok(t)] === undefined).slice(0, 3).join(' ');
      if (w) return {h: 'Hmm, I searched but couldn’t find a student matching <b>“' + E(w) + '”</b> among the deals you can see. 🙈 Try the full name, phone number or deal number.', mood: 'conf'};
    }
    if (!attrs.length && !U.hasSetVerb && parseFilters(U.T, U.C, U.skip).length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    // domain but unanswerable vs out of topic
    const words = U.C.join(' ');
    if (DOMAIN_RX.test(words) || U.T.some(t => dataTokens().has(t) && t.length > 3 && !STOP.has(t))) return notTrained();
    return outOfTopic();
  }

  async function send(text) {
    text = String(text || '').trim(); if (!text || S.busy) return;
    addMe(text); input.value = ''; S.asked = text; setChips([]);
    await think(() => brain(text), Math.min(900, 380 + text.length * 6));
  }

  /* ================= watcher: knows what is happening on the page ================= */
  const EV = [], BOT_IDS = {};
  const evAdd = t => { EV.push({at: Date.now(), t: t}); if (EV.length > 40) EV.shift(); };
  const agoTxt = ms => { const m = Math.round(ms / 60000); return m < 1 ? 'just now' : m < 60 ? m + ' min ago' : Math.round(m / 60) + ' h ago'; };
  const modalOpen = () => { try { return !!editRow && !g('modal').hidden; } catch (e) { return false; } };
  const curDeal = () => (modalOpen() ? editRow : null) || S.deal;
  function pageText() {
    try {
      const r = route;
      if (r.type === 'home') return 'the <b>Dashboard</b> home';
      if (r.type === 'master') return r.sheet ? 'the <b>All deals</b> table' : 'the <b>Master overview</b>';
      if (r.type === 'uni') return 'the <b>' + E(r.name.split(MS).join(' + ')) + '</b> page' + (r.sheet ? ' (deals table)' : '');
      if (r.type === 'reports') return 'the <b>Reports</b> list';
      if (r.type === 'report') { const d = REPORTS.find(x => x.id === r.id); return 'the <b>' + E(d ? d.title : 'report') + '</b> report'; }
    } catch (e) {}
    return 'the dashboard';
  }
  let WS = {hash: location.hash, gf: '', edit: null, fetched: null, n: -1, touched: {}};
  function watchTick() {
    try {
      if (!window.allRows || !allRows.length) return;
      const gf = JSON.stringify(gfNow());
      if (WS.n === -1) { WS = {hash: location.hash, gf: gf, edit: modalOpen() ? editRow : null, fetched: fetchedAt, n: allRows.length, touched: Object.assign({}, TOUCHED)}; return; }
      const notes = [];
      if (location.hash !== WS.hash) { WS.hash = location.hash; evAdd('Went to ' + pageText().replace(/<[^>]+>/g, '')); }
      if (gf !== WS.gf) { WS.gf = gf; const n = gfNow(); evAdd(Object.keys(n).length ? 'Filtered: ' + gfText(n).replace(/<[^>]+>/g, '') : 'Cleared the filters'); }
      const ed = modalOpen() ? editRow : null;
      if ((ed && ed.ID) !== (WS.edit && WS.edit.ID)) { if (ed) evAdd('Opened the edit form of ' + nameOf(ed)); else if (WS.edit) evAdd('Closed the edit form of ' + nameOf(WS.edit)); WS.edit = ed; }
      if (fetchedAt !== WS.fetched) { WS.fetched = fetchedAt; if (WS.n !== allRows.length) evAdd('Data refreshed (' + fmtN(allRows.length) + ' deals)'); else evAdd('Data refreshed'); notes.push('Data was just refreshed from Bitrix24 (' + fmtN(allRows.length) + ' deals).'); WS.n = allRows.length; }
      Object.keys(TOUCHED).forEach(id => { if (TOUCHED[id] !== WS.touched[id]) { WS.touched[id] = TOUCHED[id]; if (!(BOT_IDS[id] && Date.now() - BOT_IDS[id] < 8000)) { const r = allRows.find(x => String(x.ID) === String(id)); evAdd('Saved changes to ' + (r ? nameOf(r) : 'deal #' + id)); notes.push('You just saved changes to <b>' + E(r ? nameOf(r) : '#' + id) + '</b>. 👍'); } } });
      if (notes.length && panel && !panel.hidden && !S.busy) { addBot('👀 ' + notes.join('<br>')); mood('happy'); }
    } catch (e) {}
  }
  setInterval(watchTick, 2500);
  function watchSummary() {
    const act = allRows.filter(r => !isClosed(r)).length, od = allRows.filter(r => isStale(r)).length, nc = allRows.filter(r => isNotContacted(r)).length;
    const n = gfNow(), cd = modalOpen() ? editRow : null;
    let h = '👀 Here’s what I can see right now:<ul class="mbl2"><li>You’re on ' + pageText() + '.</li>';
    h += '<li>' + (Object.keys(n).length ? 'Filters on: ' + gfText(n) + ' (showing ' + fmtN(gfRows.length) + ' of ' + fmtN(allRows.length) + ')' : 'No filters are on (' + fmtN(allRows.length) + ' deals).') + '</li>';
    if (cd) h += '<li>The edit form of <b>' + E(nameOf(cd)) + '</b> is open.</li>';
    else if (S.deal) h += '<li>We were last talking about <b>' + E(nameOf(S.deal)) + '</b>.</li>';
    h += '<li><b>' + fmtN(act) + '</b> active deals · <b>' + fmtN(od) + '</b> follow-ups overdue · <b>' + fmtN(nc) + '</b> not contacted.</li>';
    try { const u = g('updated'); if (u) h += '<li>Data: ' + E(u.textContent.replace(/^Updated\s*/i, '')) + '.</li>'; } catch (e) {}
    h += '</ul>';
    const ev = EV.slice(-6).reverse();
    if (ev.length) h += '<div class="mbs" style="margin-bottom:3px">Recent moves:</div>' + list_(ev.map(x => E(x.t) + ' <span class="mbs">' + agoTxt(Date.now() - x.at) + '</span>'));
    else h += '<div class="mbs">Nothing has happened since I started watching. 😴</div>';
    return h;
  }
  const GREET = [
    'Ahh, <b>{n}</b>, you again?! 🙄 Came to eat my head, or was I sleeping peacefully? 😴',
    'Oh no, he’s back! I was in the middle of a banana nap. 🍌😴 What now?',
    'Hide the bananas, <b>{n}</b> is here! 🙈 What do you want this time?',
    'Mmm? Huh? I wasn’t sleeping! I was… resting my eyes. 😪 Go on, <b>{n}</b>.',
    'I was just counting deals in my sleep… 1,193… 1,194… and you made me lose count! 😤',
    'Poke poke! 🐒 Who woke the monkey? Oh, it’s you, <b>{n}</b>. Fine, I’m up.',
    'Reporting for duty (after a short nap). 🫡🍌 What’s the emergency, <b>{n}</b>?',
    'Ah, the human who types too much returns. 🙊 Questions? Complaints? Bananas?',
    'Oo-oo-ah-ah! 🎉 Oh, it’s just you. I thought it was the admin coming to train me. 😭',
    'Back already?! I haven’t even finished my banana. 🍌 Make it quick, <b>{n}</b>.',
    'Did someone say follow-ups? No? Then why did you wake me up? 😴 Okay okay, ask away.',
    'Knock knock… oh wait, you don’t knock, you just poke me. 🐒 Yes, <b>{n}</b>?'
  ];
  const GREET_LONG = ['Where have you been, <b>{n}</b>?! I got so lonely I started reading the activity log. 🥲', 'It’s been ages! I almost started talking to the progress bars. 🙊 Welcome back, <b>{n}</b>!'];
  const GREET_QUICK = ['Back <i>already</i>?! I haven’t even finished my banana. 🍌 You’re making me dizzy, <b>{n}</b>.', 'Again?! I just sat down! 😤 Fine, what is it?'];
  const GREET_NIGHT = ['It’s late, <b>{n}</b>! Even monkeys sleep at night. 🌙 But go on, I’ll stay up.'];
  const GREET_MORN = ['Good morning, <b>{n}</b>! ☀️ Is it banana time yet?'];
  let lastGreet = 0, lastClose = 0, gi = -1;
  function ctxNotes() {
    const out = [];
    try {
      let seen = null; try { seen = JSON.parse(localStorage.getItem('mb_seen') || 'null'); } catch (e) {}
      const maxId = allRows.reduce((m, r) => Math.max(m, +r.ID || 0), 0);
      if (seen && seen.max && maxId > seen.max) { const k = allRows.filter(r => (+r.ID || 0) > seen.max).length; if (k) out.push('👀 <b>' + k + '</b> new deal' + (k === 1 ? '' : 's') + ' since you last opened me.'); }
      try { localStorage.setItem('mb_seen', JSON.stringify({max: maxId, at: Date.now()})); } catch (e) {}
      const od = allRows.filter(r => isStale(r)).length;
      if (od) out.push('⏰ <b>' + fmtN(od) + '</b> follow-up' + (od === 1 ? ' is' : 's are') + ' overdue.');
      const n = gfNow(); if (Object.keys(n).length) out.push('🎛 Filters are on: ' + gfText(n) + ' (' + fmtN(gfRows.length) + ' deals).');
      if (route.type !== 'home') out.push('📍 You’re on ' + pageText() + '.');
      if (fetchedAt && Date.now() - fetchedAt > 3600e3) out.push('🕰 The data is ' + agoTxt(Date.now() - fetchedAt).replace(' ago', '') + ' old. Press “Refresh data” if you need it fresh.');
      const last = EV.slice().reverse().find(x => /^Saved|^Opened/.test(x.t)); if (last) out.push('📝 ' + last.t + ' ' + agoTxt(Date.now() - last.at) + '.');
    } catch (e) {}
    return out.slice(0, 2);
  }
  function greetOpen(first) {
    if (Date.now() - lastGreet < 1500) return;
    lastGreet = Date.now();
    const nm = ME || 'friend', h = new Date().getHours(), away = lastClose ? Date.now() - lastClose : 0;
    let pool = GREET;
    if (away && away < 25000) pool = GREET_QUICK; else if (away > 40 * 60000) pool = GREET_LONG; else if (h >= 23 || h < 5) pool = GREET_NIGHT; else if (h >= 5 && h < 9 && Math.random() < .5) pool = GREET_MORN;
    let i; do { i = Math.floor(Math.random() * pool.length); } while (pool === GREET && i === gi && pool.length > 1); if (pool === GREET) gi = i;
    let t = pool[i].replace(/\{n\}/g, E(nm));
    const notes = allRows.length ? ctxNotes() : [];
    if (first) t += '<div class="mbs" style="margin-top:6px">I’m <b>Monkey bot</b>. I watch this dashboard, answer from its data, and open any lead you want to edit. Typos are fine!</div>';
    if (notes.length) t += '<div class="mbs" style="margin-top:6px">' + notes.join('<br>') + '</div>';
    respond({h: t, mood: 'happy', chips: baseChips()});
  }

  /* ---------- events ---------- */
  function openPanel(o) {
    if (!o && !panel.hidden) lastClose = Date.now();
    panel.hidden = !o; openB.classList.toggle('open', o); openB.setAttribute('aria-expanded', o ? 'true' : 'false'); openB.classList.remove('hasq');
    if (o) {
      greetOpen(!msgs.children.length);
      setTimeout(() => input.focus(), 60);
    }
  }
  openB.onclick = () => openPanel(panel.hidden);
  g('mbClose').onclick = () => { openPanel(false); openB.focus(); };
  g('mbClear').onclick = () => { msgs.innerHTML = ''; S.deal = null; S.pending = null; S.edit = null; respond({h: 'Fresh start! 🍌 What shall we look up?', mood: 'happy', chips: baseChips()}); };
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !panel.hidden && !document.querySelector('.modal:not([hidden])')) { openPanel(false); } });
  g('mbForm').onsubmit = e => { e.preventDefault(); send(input.value); };
  chips.addEventListener('click', e => { const b = e.target.closest('button[data-q]'); if (b) send(b.dataset.q); });
  msgs.addEventListener('click', e => {
    const q = e.target.closest('button[data-q]'); if (q) { send(q.dataset.q); return; }
    const pk = e.target.closest('button[data-pick]');
    if (pk && S.pending && S.pending.t === 'which') {
      const r = allRows.find(x => String(x.ID) === pk.dataset.pick); if (!r) return;
      const then = S.pending.then; S.pending = null; S.deal = r; S.via = S.viaNext || 'name'; addMe(nameOf(r) + ' · #' + r.ID);
      think(() => then(r), 350); return;
    }
    const nv = e.target.closest('button[data-nav]'); if (nv) { location.hash = nv.dataset.nav; return; }
    const a = e.target.closest('button[data-act]');
    if (a) {
      const act = a.dataset.act;
      if (act === 'clearf') { GF = {}; gfSave(); try { page = 0; } catch (x) {} renderRoute(false); a.textContent = '✓ Cleared'; a.disabled = true; return; }
      if (act === 'reapply') { if (LASTGF) { applyGF(LASTGF.gf, false); a.textContent = '✓ Applied'; a.disabled = true; } return; }
      if (act === 'yes' || act === 'no') { if (S.busy) return; a.closest('.mbact').querySelectorAll('button').forEach(b => { b.disabled = true; }); send(act === 'yes' ? 'Yes' : 'No'); }
      else if (act === 'edit') { const r = allRows.find(x => String(x.ID) === a.dataset.id); if (r) { think(() => guideEdit([r], understand('edit this deal'), S.via || 'name'), 300); } }
      else if (act === 'form') { const r = allRows.find(x => String(x.ID) === a.dataset.id); if (r && typeof openEdit === 'function') { try { openEdit(r); } catch (x) {} } }
      else if (act === 'copyq') { try { navigator.clipboard.writeText('Monkey bot could not answer: “' + S.asked + '”. Please train it. 🐒'); a.textContent = '✓ Copied'; } catch (x) { a.textContent = 'Copy failed'; } }
      return;
    }
    const nl = e.target.closest('a.mbnum');
    if (nl) { e.preventDefault(); location.hash = hashFor(null, {bot: nl.dataset.sel}, true); setTimeout(() => { try { openPanel(false); } catch (x) {} }, 150); return; }
    const dl = e.target.closest('a[data-deal]');
    if (dl) { e.preventDefault(); const r = allRows.find(x => String(x.ID) === dl.dataset.deal); if (r) { S.deal = r; send('status of deal ' + r.ID); } }
  });

  window.__monkeyBot = {parseFilters: parseFilters, valueDict: valueDict, doCount: doCount, brain: brain, understand: understand, findDeals: findDeals, nlDate: nlDate, state: S, parseEdit: parseEdit, fixTok: fixTok};
  setTimeout(() => { if (panel.hidden) openB.classList.add('hint'); setTimeout(() => openB.classList.remove('hint'), 4200); }, 2500);
})();
