<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StayNest — Management Homestay</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@500;600;700&family=Nunito:wght@400;500;600;700;800&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --paper:#F6EFDC;
    --paper-deep:#EEE1C3;
    --panel:#FFFBF1;
    --ink:#2B2013;
    --ink-soft:#6B5A44;
    --teak:#6B4226;
    --teak-deep:#4A2D19;
    --leaf:#4F7942;
    --leaf-deep:#39592F;
    --marigold:#E3A857;
    --marigold-deep:#C6863A;
    --clay:#B5533C;
    --line: rgba(43,32,19,0.14);
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html,body{height:100%; overscroll-behavior:none;}
  body{
    background:var(--teak-deep);
    color:var(--ink);
    font-family:'Nunito', sans-serif;
    line-height:1.6;
    overflow:hidden;
  }
  a{color:inherit; text-decoration:none;}
  h1,h2,h3{font-family:'Zilla Slab', serif; font-weight:700; letter-spacing:-0.01em; color:var(--teak-deep);}
  .hand{font-family:'Caveat', cursive; font-weight:700;}
  button{font-family:inherit; cursor:pointer;}
  :focus-visible{outline:3px solid var(--marigold); outline-offset:2px; border-radius:6px;}

  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-size:12px; font-weight:800; letter-spacing:0.06em; text-transform:uppercase;
    color:var(--leaf-deep); background:rgba(79,121,66,0.12);
    padding:6px 14px; border-radius:999px;
  }
  .eyebrow::before{content:"🌿";}

  .btn{
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    padding:14px 26px; border-radius:14px; font-weight:800; font-size:15px;
    transition: transform 0.15s ease, box-shadow 0.15s ease; border:none;
  }
  .btn-leaf{background:var(--marigold-deep); color:#FBF6E9; box-shadow:0 6px 0 var(--teak-deep);}
  .btn-leaf:hover{transform:translateY(-2px); box-shadow:0 8px 0 var(--teak-deep);}
  .btn-outline{background:transparent; color:var(--teak-deep); border:2px solid var(--teak);}
  .btn-outline:hover{background:rgba(107,66,38,0.08);}

  /* ============ BOOK: horizontal snap-scroll pages ============ */
  .book{
    display:flex;
    height:100vh;
    overflow-x:auto;
    overflow-y:hidden;
    scroll-snap-type:x mandatory;
    scroll-behavior:auto;
  }
  .book::-webkit-scrollbar{display:none;}
  .page{
    flex:0 0 100vw;
    height:100vh;
    scroll-snap-align:start;
    display:flex; flex-direction:column; justify-content:center;
    padding:56px 8vw 90px;
    overflow-y:auto;
    position:relative;
    /* Efek fade-in sederhana */
    transition: opacity 0.6s ease;
    opacity: 0;
  }
  .page.is-active {
    opacity: 1;
  }

  /* Staggered Content: Konten muncul dengan jeda */
  .page > *:not(header):not(.page-num) {
      transition: opacity 0.6s ease, transform 0.6s ease;
      opacity: 0;
      transform: translateY(30px);
  }
  .page.is-active > *:not(header):not(.page-num) {
      opacity: 1;
      transform: translateY(0);
  }

  /* Jeda per elemen */
  .page.is-active .eyebrow { transition-delay: 0.1s; }
  .page.is-active h1, .page.is-active h2 { transition-delay: 0.2s; }
  .page.is-active p, .page.is-active ul { transition-delay: 0.3s; }
  .page.is-active .btn { transition-delay: 0.4s; }
  .page.is-active .kunci-wrap { transition-delay: 0.2s; }
  .page.is-active .stamps-row { transition-delay: 0.2s; }
  .page.is-active .price-cards { transition-delay: 0.3s; }
  /* Kembalikan background untuk halaman yang membutuhkannya */
  .kunci-page, .stamps-page {
    background: #D2B48C;
  }
  .cover {
    background: #1A150C;
  }
  .price-page {
    background: #ECDDB9;
  }
  .closing-page {
    background: linear-gradient(135deg, rgba(43,32,19,0.85), rgba(20,14,8,0.95)), url('/img/GambarOm.png');
    background-size: cover;
    background-position: center;
  }
  .page-num{
    position:absolute; bottom:22px; left:8vw;
    font-family:'Zilla Slab',serif; font-size:12px; color:var(--ink-soft);
    letter-spacing:0.08em;
  }

  /* ---- Page 0: Cover ---- */
  .cover{
    background:#1A150C;
    align-items:center; text-align:center;
    background-image: repeating-linear-gradient(90deg, rgba(255,255,255,0.03) 0 3px, transparent 3px 26px);
  }
  .cover .logo-mark{
    width:64px; height:64px; border-radius:16px; background:var(--marigold);
    display:flex; align-items:center; justify-content:center; font-size:30px; margin:0 auto 22px;
  }
  .cover h1{color:#F6EFDC; font-size:clamp(2rem,5vw,3.4rem); margin-bottom:14px;}
  .cover p{color:rgba(246,239,220,0.75); max-width:480px; margin:0 auto 30px; font-size:16px;}
  .cover .hint{color:rgba(246,239,220,0.55); font-size:13px; margin-top:26px;}

  /* ---- Page 1: Papan Kunci ---- */
  .kunci-page{align-items:center; background:#D2B48C;}
  .kunci-wrap{display:flex; gap:60px; align-items:center; max-width:1000px; margin:0 auto; flex-wrap:wrap; justify-content:center;}
  .kunci-copy{max-width:340px;}
  .kunci-copy h2{font-size:clamp(1.5rem,3vw,2.1rem); margin:14px 0 12px;}
  .kunci-copy p{color:var(--ink-soft); font-size:14.5px;}

  .pegboard{
    background:var(--teak); border-radius:22px; padding:26px; position:relative; width:340px;
    box-shadow:0 20px 40px -18px rgba(43,32,19,0.5);
  }
  .pegboard-head{display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;}
  .pegboard-head h3{color:#F6EFDC; font-size:14px;}
  .pegboard-head p{color:rgba(246,239,220,0.7); font-size:10.5px; margin-top:2px;}
  .pill-live{font-size:10px; font-weight:800; color:#2B2013; background:var(--marigold); padding:4px 9px; border-radius:999px;}
  .peg-grid{display:grid; grid-template-columns:repeat(4, 1fr); gap:12px 8px; margin-bottom:16px;}
  .peg{display:flex; flex-direction:column; align-items:center; gap:5px;}
  .peg-hook{width:16px; height:9px; border-radius:0 0 7px 7px; background:rgba(246,239,220,0.55);}
  .peg-key{width:32px; height:36px; position:relative; display:flex; align-items:center; justify-content:center; font-size:8.5px; font-weight:800; color:#2B2013; border-radius:7px;}
  .peg-key::before{content:""; position:absolute; top:-7px; left:50%; transform:translateX(-50%); width:7px; height:7px; border-radius:50%; border:2.5px solid currentColor; background:transparent;}
  .peg-tersedia .peg-key{background:var(--marigold); color:#2B2013;}
  .peg-terisi .peg-hook{background:transparent;}
  .peg-terisi .peg-key{width:26px; height:13px; border-radius:7px; background:transparent; border:2px dashed rgba(246,239,220,0.4); color:rgba(246,239,220,0.5); font-size:7px;}
  .peg-bersih .peg-key{background:#DDD1AC; color:#4A2D19;}
  .peg-label{font-size:9px; color:rgba(246,239,220,0.85); font-weight:700;}
  .peg-foot{display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(246,239,220,0.2); padding-top:14px;}
  .peg-foot .amt{color:var(--marigold); font-size:16px; font-weight:800; font-family:'Zilla Slab',serif;}
  .peg-foot .lbl{color:rgba(246,239,220,0.7); font-size:10px;}

  /* ---- Page 2: Fitur — passport stamps ---- */
  .stamps-page{align-items:center; background:#D2B48C;}
  .stamps-head{text-align:center; max-width:560px; margin:0 auto 40px;}
  .stamps-head h2{font-size:clamp(1.5rem,3vw,2.1rem); margin:14px 0 10px;}
  .stamps-head p{color:var(--ink-soft); font-size:14.5px;}
  .stamps-row{display:flex; gap:36px; justify-content:center; flex-wrap:wrap; max-width:900px; margin:0 auto;}
  .stamp{
    width:220px; text-align:center; padding:22px 16px;
    border:3px solid var(--clay); border-radius:50%/18%;
    color:var(--clay); transform:rotate(var(--r,0deg));
    position:relative;
  }
  .stamp:nth-child(1){--r:-6deg; border-color:var(--leaf); color:var(--leaf-deep);}
  .stamp:nth-child(2){--r:4deg; border-color:var(--marigold-deep); color:var(--marigold-deep);}
  .stamp:nth-child(3){--r:-3deg; border-color:var(--clay); color:var(--clay);}
  .stamp .icon{font-size:26px; margin-bottom:8px;}
  .stamp h3{font-size:14.5px; font-family:'Nunito',sans-serif; font-weight:800; color:inherit; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.03em;}
  .stamp p{font-size:11.5px; color:var(--ink-soft); font-family:'Nunito',sans-serif;}

  /* ---- Page 3: Harga — Modern Price Cards (background diperbarui) ---- */
  .price-page{
    align-items:center;
    position:relative;
    overflow:hidden;
    background: linear-gradient(180deg, #FBF6E9 0%, #F6EFDC 45%, #ECDDB9 100%);
    background-image:
      radial-gradient(circle at 12% 10%, rgba(227,168,87,0.22) 0%, transparent 42%),
      radial-gradient(circle at 88% 8%, rgba(79,121,66,0.14) 0%, transparent 40%),
      radial-gradient(circle at 90% 92%, rgba(181,83,60,0.13) 0%, transparent 45%),
      radial-gradient(circle at 8% 90%, rgba(107,66,38,0.10) 0%, transparent 40%);
  }
  .price-page::after{
    content:"";
    position:absolute; top:-120px; left:50%; transform:translateX(-50%);
    width:640px; height:320px; border-radius:50%;
    background:radial-gradient(circle, rgba(227,168,87,0.35) 0%, transparent 70%);
    filter:blur(10px);
    pointer-events:none;
  }
  .price-container{position:relative; z-index:1; width:100%; max-width:850px; margin:0 auto;}
  .bubble-title {
    display: inline-block;
    padding: 10px 20px;
    background: rgba(227,168,87,0.15);
    border-radius: 50px;
    color: var(--marigold);
    font-weight: 900;
    text-transform: uppercase;
    text-align: center;
    font-size: clamp(1rem, 2.5vw, 1.8rem);
    margin-bottom: 15px;
    letter-spacing: 0.05em;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
  }
  .price-sub{
    text-align:center; color:white; font-size:16px; font-weight:400;
    margin:0 auto 40px; max-width:600px;
    opacity:0.8; font-style:italic;
  }
  .price-cards{display:flex; gap:24px; justify-content:center; flex-wrap:wrap;}
  .price-card{
    background:var(--panel); border:2px solid var(--paper-deep); border-radius:24px; padding:40px;
    flex:1; min-width:300px; display:flex; flex-direction:column;
    transition:transform 0.2s ease, box-shadow 0.2s ease;
  }
  .price-card:hover{transform:translateY(-5px); box-shadow:0 15px 30px rgba(43,32,19,0.1);}
  .price-card.premium{border-color:var(--marigold); position:relative; box-shadow:0 10px 25px rgba(227,168,87,0.15);}
  .price-card.premium::before{
    content:"TERPOPULER"; position:absolute; top:-12px; right:20px;
    background:var(--marigold); color:#2B2013; font-size:10px; font-weight:800;
    padding:4px 12px; border-radius:6px; letter-spacing:0.05em;
  }
  .price-card h3{font-size:20px; margin-bottom:8px;}
  .price-card .price{font-family:'Zilla Slab',serif; font-size:36px; font-weight:700; color:var(--leaf-deep); margin:20px 0;}
  .price-card .price span{font-family:'Nunito',sans-serif; font-size:14px; font-weight:600; color:var(--ink-soft);}
  .price-card ul{list-style:none; margin:0 0 30px; flex-grow:1;}
  .price-card ul li{margin-bottom:12px; font-size:14px; color:var(--ink-soft); display:flex; align-items:center; gap:8px;}
  .price-card ul li::before{content:"✓"; color:var(--leaf);}

  /* ---- Page 4: Closing ---- */
  .closing-page{
    align-items:center; justify-content:center; text-align:center;
    position:relative;
    color:#F6EFDC;
    /* Background estetik: Gambar + Gradient + Grain effect */
    background:
      linear-gradient(135deg, rgba(43,32,19,0.85), rgba(20,14,8,0.95)),
      url('/img/GambarOm.png');
    background-size:cover;
    background-position:center;
  }
  .closing-page > *{position:relative; z-index:1;}
  .closing-page h2{
    font-family:'Zilla Slab', serif; font-style:italic; font-weight:600;
    color:var(--marigold); font-size:clamp(1.7rem,3.6vw,2.6rem); max-width:560px; margin:0 auto 16px;
    text-shadow:0 2px 12px rgba(0,0,0,0.4);
  }
  .closing-page p{color:rgba(246,239,220,0.85); max-width:440px; margin:0 auto 26px; font-size:14.5px;}
  .closing-page .stamp-seal{
    width:60px; height:60px; border-radius:50%; background:var(--marigold); color:#2B2013;
    display:flex; align-items:center; justify-content:center; font-size:26px; margin:0 auto 20px;
  }
  .closing-foot{position:absolute; bottom:20px; left:0; width:100%; color:rgba(246,239,220,0.6); font-size:12px;}
  @media(max-width:760px){
    .closing-page{background-attachment:scroll;}
  }

  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
  }
  .floating { animation: float 6s ease-in-out infinite; }
  /* ============ Book chrome: index tabs + arrows ============ */
  .tabs-index{
    position:fixed; right:0; top:50%; transform:translateY(-50%);
    display:flex; flex-direction:column; z-index:20;
  }
  .tab-idx{
    background:var(--paper-deep); border:none; border-radius:8px 0 0 8px;
    padding:12px 10px; font-size:11px; font-weight:800; color:var(--ink-soft);
    writing-mode:vertical-rl; text-orientation:mixed;
    border-top:2px solid var(--paper); box-shadow:-2px 0 6px rgba(0,0,0,0.08);
  }
  .tab-idx.active{background:var(--marigold); color:#2B2013;}

  .nav-arrows{
    position:fixed; bottom:24px; left:50%; transform:translateX(-50%);
    display:flex; gap:14px; align-items:center; z-index:20;
  }
  .nav-arrows button{
    width:40px; height:40px; border-radius:50%; border:2px solid var(--teak);
    background:var(--panel); color:var(--teak-deep); font-size:18px; font-weight:800;
    display:flex; align-items:center; justify-content:center;
  }
  .nav-arrows button:hover{background:var(--marigold); border-color:var(--marigold-deep);}
  .dots{display:flex; gap:7px;}
  .dot{width:8px; height:8px; border-radius:50%; background:rgba(107,90,68,0.3);}
  .dot.active{background:var(--teak-deep);}

  @media(max-width:760px){
    body{overflow:auto;}
    .book{height:auto; overflow-x:visible; overflow-y:visible; display:block; scroll-snap-type:none;}
    .page{height:auto; min-height:100vh; padding:60px 4vw 90px;}
    .tabs-index{display:none;}
    .kunci-wrap{flex-direction:column; gap:30px;}
    .pegboard { width: 100%; max-width: 340px; padding: 20px; }
    .peg-grid { grid-template-columns: repeat(4, 1fr); gap: 10px 5px; }
    .stamps-row{gap:22px;}
    .price-cards { display: flex; flex-direction: column; flex-wrap: nowrap; gap: 20px; padding: 0 10px; }
    .price-page { padding-bottom: 120px !important; height: auto !important; padding-top: 50px; }
    .price-card { width: 100%; min-width: auto !important; margin-bottom: 20px; flex-shrink: 0; padding: 20px; }
  }
</style>
</head>
<body>

<div class="book" id="book">

  <!-- PAGE 0 — Cover -->
  <section class="page cover" id="p0">
    <header style="position:absolute; top:20px; left:0; width:100%; padding:0 8vw; display:flex; justify-content:space-between; align-items:center; z-index:10;">
        <span style="font-size:1.5rem; font-weight:800; color:#F6EFDC;">StayNest</span>
    </header>

    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; width: 100%;">
        <span class="eyebrow" style="background:rgba(227,168,87,0.2); color:#E3A857; margin-bottom: 24px; display: block; width: fit-content;">SELAMAT DATANG</span>

        <div class="floating" style="margin-bottom: 24px; width: 100px; height: 100px; color: var(--marigold); opacity: 0.6;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
        </div>

        <h1 style="font-size: clamp(3rem, 8vw, 5rem); margin-bottom: 16px;">StayNest</h1>
        <p style="font-size: clamp(1rem, 2vw, 1.25rem); max-width: 500px; line-height: 1.4; color:rgba(246,239,220,0.85); margin-bottom: 32px;">Teman kelola homestay keluarga — kamar, tamu, dan uang masuk, serapi papan kunci di meja resepsionis.</p>

        <button class="btn btn-leaf" onclick="goTo(1)" style="font-size: 1.1rem; padding: 16px 32px;">Buka Halaman Pertama →</button>
        <p class="hint" style="margin-top: 24px;">Geser ke samping, atau pakai tab di kanan layar</p>
    </div>
  </section>

  <!-- PAGE 1 — Papan Kunci -->
  <section class="page kunci-page" id="p1">
    <header style="position:absolute; top:20px; left:0; width:100%; padding:0 8vw; display:flex; justify-content:space-between; align-items:center; z-index:10;">
        <span style="font-size:1.5rem; font-weight:800; color:var(--ink);">StayNest</span>
    </header>
    <div class="kunci-wrap">
      <div class="pegboard">
        <div class="pegboard-head">
          <div><h3>Papan Kunci Hari Ini</h3><p>Homestay Kaliurang · <span id="todayDate">{{ date('l, j M') }}</span></p></div>
          <span class="pill-live">TERBARU</span>
        </div>
        <div class="peg-grid">
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">01</div></div>
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">02</div></div>
          <div class="peg peg-bersih"><div class="peg-hook"></div><div class="peg-key">🧹</div><div class="peg-label">03</div></div>
          <div class="peg peg-tersedia"><div class="peg-hook"></div><div class="peg-key">🔑</div><div class="peg-label">04</div></div>
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">05</div></div>
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">06</div></div>
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">07</div></div>
          <div class="peg peg-terisi"><div class="peg-hook"></div><div class="peg-key">luar</div><div class="peg-label">08</div></div>
        </div>
        <div class="peg-foot">
          <div><p class="amt">Rp 12.850.000</p><p class="lbl">Pendapatan bulan ini</p></div>
          <div class="lbl">7 dari 8 terisi</div>
        </div>
      </div>
      <div class="kunci-copy">
        <span class="eyebrow"> Contoh Ruang Kerja</span>
        <h2>Cukup lihat papan kunci, langsung tahu kamar mana yang kosong.</h2>
        <b>Gantungan kunci berisi berarti kamar tersedia untuk tamu baru. Gantungan kunci kosong berarti kamar sedang terisi. Cek status kamar secara real-time dan pantau okupansi homestay Anda dengan lebih akurat.</b>
      </div>
    </div>
    <span class="page-num">Papan Kunci</span>
  </section>

  <!-- PAGE 2 — Fitur -->
  <section class="page stamps-page" id="p2">
    <header style="position:absolute; top:20px; left:0; width:100%; padding:0 8vw; display:flex; justify-content:space-between; align-items:center; z-index:10;">
        <span style="font-size:1.5rem; font-weight:800; color:var(--ink);">StayNest</span>
    </header>
    <div class="stamps-head">
      <span class="eyebrow">Cap Fitur</span>
      <h2>Tiga hal yang paling sering bikin repot, sudah dirapikan</h2>
      <b>Dibuat sesuai kebiasaan homestay keluarga — sederhana, bukan software untuk hotel besar.</b>
    </div>
    <div class="stamps-row">
      <div class="stamp">
        <div class="icon">🛏️</div>
        <h3>Status Kamar Update</h3>
        <p>Tandai kosong, terisi, atau dibersihkan langsung dari HP.</p>
      </div>
      <div class="stamp">
        <div class="icon">📖</div>
        <h3>Tanpa Booking Ganda</h3>
        <p>Tanggal dicek otomatis sebelum reservasi tersimpan.</p>
      </div>
      <div class="stamp">
        <div class="icon">👨‍👩‍👧</div>
        <h3>Dipakai Bareng Keluarga</h3>
        <p>Ajak staf atau keluarga, akses bisa dibatasi per orang.</p>
      </div>
    </div>
    <span class="page-num">Fitur</span>
  </section>

  <!-- PAGE 3 — Harga -->
  <section class="page price-page" id="p3">
    <header style="position:absolute; top:20px; left:0; width:100%; padding:0 8vw; display:flex; justify-content:space-between; align-items:center; z-index:10;">
        <span style="font-size:1.5rem; font-weight:800; color:var(--ink);">StayNest</span>
    </header>
    <div class="price-container">
      <h2 class="bubble-title">Pilih Paket Sesuai Kebutuhan Homestay Anda</h2>
      <p class="price-sub">Semua paket sudah termasuk manajemen kamar, buku tamu digital, dan laporan bulanan untuk pengelolaan yang lebih efisien.</p>

      <div class="price-cards">
        <div class="price-card">
          <h3>Paket Hemat</h3>
          <div class="price">Rp 199K<span>/ bulan</span></div>
          <ul>
            <li>Sampai 4 kamar</li>
            <li>2 akun keluarga/staf</li>
            <li>Manajemen Reservasi</li>
          </ul>
          <a href="{{ route('register', ['plan' => 'hemat']) }}" class="btn btn-outline">Pilih Hemat</a>
        </div>

        <div class="price-card premium">
          <h3>Paket Lengkap</h3>
          <div class="price">Rp 499K<span>/ bulan</span></div>
          <ul>
            <li>Kamar tidak terbatas</li>
            <li>Akun staf tanpa batas</li>
            <li>Laporan keuangan & PDF Export</li>
            <li>Check-in Digital Tamu</li>
          </ul>
          <a href="{{ route('register', ['plan' => 'lengkap']) }}" class="btn btn-leaf">Pilih Lengkap</a>
        </div>
      </div>
    </div>
    <span class="page-num">Harga (berlangganan)</span>
  </section>

  <!-- PAGE 4 — Closing -->
  <section class="page closing-page" id="p4">
    <header style="position:absolute; top:20px; left:0; width:100%; padding:0 8vw; display:flex; justify-content:space-between; align-items:center; z-index:10;">
        <span style="font-size:1.5rem; font-weight:800; color:#F6EFDC;">StayNest</span>
        <a href="{{ route('login') }}" style="color:#F6EFDC; font-size:14px; font-weight:600; padding:8px 16px; border:1px solid rgba(246,239,220,0.3); border-radius:8px;">Masuk</a>
    </header>
    <!-- <div class="stamp-seal">💌</div> -->
    <span class="eyebrow" style="background:rgba(227,168,87,0.2); color:#E3A857;">HOMESTAY</span>
    <h2>Kamar yang Anda tandai malam ini bisa jadi yang terakhir kali dicatat di kertas.</h2>
    <p>Tidak perlu kartu kredit. Coba dulu 14 hari, batalkan kapan saja.</p>
    <a href="{{ route('register') }}" class="btn btn-leaf">Coba Gratis Sekarang</a>
    <p class="closing-foot">© 2026 StayNest — Teman kelola homestay keluarga.</p>
    <span class="page-num"></span>
  </section>

</div>

<div class="tabs-index" id="tabs">
  <button class="tab-idx active" data-i="0">Sampul</button>
  <button class="tab-idx" data-i="1">Kunci</button>
  <button class="tab-idx" data-i="2">Fitur</button>
  <button class="tab-idx" data-i="3">Harga</button>
  <button class="tab-idx" data-i="4">Mulai</button>
</div>

<div class="nav-arrows">
  <button id="prevBtn" aria-label="Halaman sebelumnya">‹</button>
  <div class="dots" id="dots">
    <span class="dot active"></span><span class="dot"></span><span class="dot"></span><span class="dot"></span><span class="dot"></span>
  </div>
  <button id="nextBtn" aria-label="Halaman berikutnya">›</button>
</div>

<script>
  const book = document.getElementById('book');
  const pages = [...document.querySelectorAll('.page')];
  const tabs = [...document.querySelectorAll('.tab-idx')];
  const dots = [...document.querySelectorAll('.dot')];
  let current = 0;

  function goTo(i){
    i = Math.max(0, Math.min(pages.length - 1, i));
    if (window.innerWidth > 760){
      book.scrollTo({left: i * book.clientWidth, behavior:'smooth'});
    } else {
      pages[i].scrollIntoView({behavior:'smooth'});
    }
    setActive(i);
  }
  function setActive(i){
    current = i;
    tabs.forEach((t, idx) => t.classList.toggle('active', idx === i));
    dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
    pages.forEach((p, idx) => p.classList.toggle('is-active', idx === i));
  }
  tabs.forEach(t => t.addEventListener('click', () => goTo(parseInt(t.dataset.i))));
  document.getElementById('prevBtn').addEventListener('click', () => goTo(current - 1));
  document.getElementById('nextBtn').addEventListener('click', () => goTo(current + 1));

  // Initialize active class on load
  setActive(0);

  let scrollTimeout;
  book.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      const idx = Math.round(book.scrollLeft / book.clientWidth);
      setActive(idx);
    }, 100);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') goTo(current + 1);
    if (e.key === 'ArrowLeft') goTo(current - 1);
  });
</script>

</body>
</html>