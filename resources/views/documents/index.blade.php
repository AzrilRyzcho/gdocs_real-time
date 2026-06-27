<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ZenDocs</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#fff;color:#111;min-height:100vh;}

/* NAV */
nav{
  height:60px;border-bottom:1px solid #f0f0f0;
  display:flex;align-items:center;padding:0 32px;gap:16px;
  position:sticky;top:0;background:#fff;z-index:10;
}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none;}
.logo-mark{
  width:28px;height:28px;background:#111;border-radius:6px;
  display:flex;align-items:center;justify-content:center;
  font-size:15px;color:#fff;font-weight:700;
}
.logo-name{font-size:17px;font-weight:600;color:#111;letter-spacing:-.3px;}
.nav-search{
  margin:0 auto;width:100%;max-width:480px;
  display:flex;align-items:center;gap:10px;
  background:#f7f7f7;border-radius:8px;
  padding:0 14px;height:36px;
  border:1px solid transparent;transition:border .15s;
}
.nav-search:focus-within{background:#fff;border-color:#ddd;}
.nav-search input{flex:1;border:none;outline:none;background:transparent;font-size:14px;color:#111;}
.nav-search input::placeholder{color:#aaa;}
.nav-icon{
  width:32px;height:32px;border-radius:50%;
  background:#111;color:#fff;border:none;cursor:pointer;
  font-size:13px;font-weight:700;
  display:flex;align-items:center;justify-content:center;
}

/* TEMPLATE SECTION */
.tpl-section{
  background:#fafafa;border-bottom:1px solid #f0f0f0;
  padding:28px 0 24px;
}
.inner{max-width:1080px;margin:0 auto;padding:0 32px;}
.row-head{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:16px;
}
.row-head h2{font-size:14px;font-weight:600;color:#111;}
.gallery-btn{
  background:none;border:none;cursor:pointer;
  font-size:13px;color:#666;display:flex;align-items:center;gap:4px;
}
.gallery-btn:hover{color:#111;}
.tpl-row{display:flex;gap:12px;overflow-x:auto;padding-bottom:4px;}
.tpl-row::-webkit-scrollbar{height:0;}
.tpl-card{
  flex-shrink:0;width:120px;cursor:pointer;
  background:none;border:none;padding:0;text-align:left;
}
.tpl-thumb{
  width:120px;height:156px;background:#fff;
  border:1px solid #e8e8e8;border-radius:4px;
  overflow:hidden;transition:border-color .15s,box-shadow .15s;
}
.tpl-card:hover .tpl-thumb{border-color:#111;box-shadow:0 2px 8px rgba(0,0,0,.08);}
.tpl-name{font-size:12px;font-weight:500;color:#111;margin-top:6px;}
.tpl-sub{font-size:11px;color:#aaa;margin-top:1px;}

/* blank card */
.blank-center{display:flex;align-items:center;justify-content:center;height:100%;}
.plus-g{width:40px;height:40px;position:relative;display:flex;align-items:center;justify-content:center;}
.plus-g::before,.plus-g::after{content:'';position:absolute;border-radius:1px;}
.plus-g::before{width:3px;height:32px;background:linear-gradient(#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}
.plus-g::after{width:32px;height:3px;background:linear-gradient(to right,#ea4335 25%,#4285f4 25%,#4285f4 50%,#34a853 50%,#34a853 75%,#fbbc04 75%);}

/* template line previews */
.tp{padding:8px 7px;display:flex;flex-direction:column;gap:3px;}
.tl{height:3px;border-radius:1px;background:#ebebeb;}
.tl.d{background:#c8c8c8;}.tl.b{background:#4285f4;}.tl.r{background:#ea4335;}.tl.t{background:#009688;}.tl.k{background:#fff;}

/* RECENT */
.recent-section{padding:28px 0 60px;}
.recent-head{display:flex;align-items:center;gap:8px;margin-bottom:18px;}
.recent-head h2{font-size:14px;font-weight:600;color:#111;flex:1;}
.filter-btn{
  background:none;border:1px solid #e8e8e8;border-radius:6px;
  padding:5px 10px;font-size:12px;color:#555;cursor:pointer;
  display:flex;align-items:center;gap:4px;transition:border-color .15s;
}
.filter-btn:hover{border-color:#111;}
.vc-btn{
  background:none;border:none;cursor:pointer;padding:5px 6px;
  border-radius:4px;color:#888;transition:background .1s;
  display:flex;align-items:center;
}
.vc-btn:hover,.vc-btn.on{background:#f0f0f0;color:#111;}

/* doc grid */
.doc-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
  gap:14px;
}
.doc-card{
  background:#fff;border:1px solid #e8e8e8;border-radius:6px;
  overflow:hidden;cursor:pointer;text-decoration:none;color:#111;
  transition:border-color .15s,box-shadow .15s;display:block;
}
.doc-card:hover{border-color:#bbb;box-shadow:0 2px 12px rgba(0,0,0,.07);}
.doc-preview{
  height:148px;background:#fafafa;
  border-bottom:1px solid #f0f0f0;
  padding:14px 12px 8px;
  display:flex;flex-direction:column;gap:3px;
}
.dl{height:3px;border-radius:1px;background:#ebebeb;}
.dl.d{background:#bbb;height:4px;}
.doc-footer{padding:10px 12px;display:flex;align-items:center;gap:8px;}
.doc-icon{width:16px;height:16px;flex-shrink:0;}
.doc-meta{flex:1;min-width:0;}
.doc-title{font-size:12px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.doc-date{font-size:11px;color:#aaa;margin-top:1px;}
.doc-more{
  background:none;border:none;cursor:pointer;padding:3px;
  border-radius:4px;color:#aaa;opacity:0;transition:opacity .1s;
  display:flex;align-items:center;
}
.doc-card:hover .doc-more{opacity:1;}
.doc-more:hover{background:#f0f0f0;color:#111;}

/* ctx menu */
.ctx{
  position:absolute;right:0;bottom:36px;
  background:#fff;border:1px solid #e8e8e8;border-radius:6px;
  box-shadow:0 4px 16px rgba(0,0,0,.1);
  min-width:150px;z-index:50;display:none;padding:4px 0;
}
.ctx.on{display:block;}
.ctx-i{
  padding:7px 14px;font-size:12px;cursor:pointer;
  color:#111;display:flex;align-items:center;gap:8px;
  transition:background .1s;
}
.ctx-i:hover{background:#f7f7f7;}
.ctx-i.del{color:#dc2626;}

/* empty */
.empty{text-align:center;padding:48px 0;color:#aaa;grid-column:1/-1;}
.empty svg{margin-bottom:10px;opacity:.3;}
.empty p{font-size:13px;}
</style>
</head>
<body>

<nav>
  <a href="/" class="logo">
    <div class="logo-mark">Z</div>
    <span class="logo-name">ZenDocs</span>
  </a>
  <div class="nav-search">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2.5"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>
    <input type="text" id="searchInput" placeholder="Cari dokumen...">
  </div>
  <div class="nav-icon">A</div>
</nav>

{{-- TEMPLATE --}}
<div class="tpl-section">
  <div class="inner">
    <div class="row-head">
      <h2>Buat dokumen baru</h2>
      <button class="gallery-btn">
        Galeri template
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
      </button>
    </div>
    <div class="tpl-row">

      {{-- Kosong --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Dokumen tanpa judul">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb"><div class="blank-center"><div class="plus-g"></div></div></div>
          <div class="tpl-name">Dokumen kosong</div>
        </button>
      </form>

      {{-- Resume Serif --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Resume">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb"><div class="tp">
            <div class="tl d" style="width:60%;margin-bottom:4px"></div>
            <div class="tl" style="width:40%;margin-bottom:6px"></div>
            @for($i=0;$i<3;$i++)<div class="tl" style="width:88%"></div><div class="tl" style="width:70%;margin-bottom:3px"></div>@endfor
            <div class="tl b" style="width:40%;height:2px;margin-bottom:4px"></div>
            @for($i=0;$i<4;$i++)<div class="tl" style="width:{{82-$i*4}}%"></div>@endfor
          </div></div>
          <div class="tpl-name">Resume</div><div class="tpl-sub">Serif</div>
        </button>
      </form>

      {{-- Resume Koral --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Resume - Koral">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div style="background:#e53935;height:6px"></div>
            <div class="tp">
              <div class="tl d" style="width:65%;margin-bottom:3px"></div>
              <div class="tl r" style="width:30%;height:2px;margin-bottom:6px"></div>
              @for($i=0;$i<6;$i++)<div class="tl" style="width:{{78+$i*2}}%;margin-bottom:2px"></div>@endfor
            </div>
          </div>
          <div class="tpl-name">Resume</div><div class="tpl-sub">Koral</div>
        </button>
      </form>

      {{-- Surat --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Surat">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div style="background:#00897b;height:4px"></div>
            <div class="tp">
              <div class="tl t" style="width:45%;height:2px;margin-bottom:8px"></div>
              @for($i=0;$i<8;$i++)<div class="tl" style="width:{{85-($i%3)*8}}%;margin-bottom:2px"></div>@endfor
            </div>
          </div>
          <div class="tpl-name">Surat</div><div class="tpl-sub">Hijau daun mint</div>
        </button>
      </form>

      {{-- Proposal --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Proposal Proyek">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div style="height:64px;background:linear-gradient(135deg,#4db6ac 0%,#009688 40%,#795548 40%,#5d4037 70%,#ff8a65 70%);position:relative;">
              <div style="position:absolute;bottom:5px;left:7px;font-size:7px;font-weight:700;color:#fff">Nama Proyek</div>
            </div>
            <div class="tp">@for($i=0;$i<5;$i++)<div class="tl" style="width:{{72+$i*4}}%;margin-bottom:3px"></div>@endfor</div>
          </div>
          <div class="tpl-name">Proposal Proyek</div><div class="tpl-sub">Tropis</div>
        </button>
      </form>

      {{-- Brosur --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Brosur">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div style="height:72px;background:linear-gradient(150deg,#1565c0 50%,#e91e63 50%);display:flex;align-items:center;justify-content:center;">
              <div style="width:24px;height:24px;background:rgba(255,255,255,.8);border-radius:50%"></div>
            </div>
            <div class="tp">
              <div class="tl d" style="width:55%;margin-bottom:4px"></div>
              @for($i=0;$i<4;$i++)<div class="tl" style="width:{{78-$i*4}}%;margin-bottom:2px"></div>@endfor
            </div>
          </div>
          <div class="tpl-name">Brosur</div><div class="tpl-sub">Geometrik</div>
        </button>
      </form>

      {{-- Laporan --}}
      <form method="POST" action="{{ route('documents.store') }}" style="display:contents">
        @csrf<input type="hidden" name="title" value="Laporan">
        <button type="submit" class="tpl-card">
          <div class="tpl-thumb">
            <div style="background:#111;height:68px;display:flex;align-items:flex-end;padding:7px">
              <div>
                <div style="font-size:7px;font-weight:700;color:#fff;letter-spacing:1px">LAPORAN</div>
                <div style="font-size:6px;color:#888">TAHUNAN</div>
              </div>
            </div>
            <div class="tp">
              <div class="tl d" style="width:65%;margin-bottom:4px"></div>
              @for($i=0;$i<4;$i++)<div class="tl" style="width:{{80-$i*5}}%;margin-bottom:2px"></div>@endfor
            </div>
          </div>
          <div class="tpl-name">Laporan</div><div class="tpl-sub">Luks</div>
        </button>
      </form>

    </div>
  </div>
</div>

{{-- RECENT --}}
<div class="recent-section">
  <div class="inner">
    <div class="recent-head">
      <h2>Dokumen terbaru</h2>
      <button class="filter-btn">
        Milik siapa saja
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <button class="vc-btn on" id="btnGrid" title="Grid" onclick="setView('grid')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      </button>
      <button class="vc-btn" id="btnAZ" title="Urutkan A-Z" onclick="sortDocs()">
        <svg width="16" height="14" viewBox="0 0 22 16"><text x="0" y="12" font-size="11" font-weight="700" fill="currentColor" font-family="inherit">AZ</text></svg>
      </button>
      <button class="vc-btn" id="btnList" title="Daftar" onclick="setView('list')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="3.5"/><rect x="3" y="10" width="18" height="3.5"/><rect x="3" y="16" width="18" height="3.5"/></svg>
      </button>
    </div>

    <div class="doc-grid" id="docsGrid">
      @forelse($documents as $doc)
      <div class="doc-card" data-title="{{ strtolower($doc->title) }}" data-date="{{ $doc->updated_at->timestamp }}"
           onclick="window.location='{{ route('documents.edit',$doc->id) }}'">
        <div class="doc-preview">
          @php $ws=[90,74,86,60,78,68,88,56,72,52,80,66,56,84,70,60]; @endphp
          @foreach($ws as $i=>$w)
            <div class="dl {{ $i<2?'d':'' }}" style="width:{{$w}}%;{{ $i<2?'margin-bottom:5px;':'' }}"></div>
          @endforeach
        </div>
        <div class="doc-footer">
          <svg class="doc-icon" viewBox="0 0 24 24">
            <path d="M14 2H6C4.9 2 4 2.9 4 4v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z" fill="#4285f4"/>
            <path d="M14 2v6h6" fill="#a8c7fa" opacity=".7"/>
          </svg>
          <div class="doc-meta">
            <div class="doc-title">{{ $doc->title ?: 'Dokumen tanpa judul' }}</div>
            <div class="doc-date">Dibuka {{ $doc->updated_at->locale('id')->diffForHumans() }}</div>
          </div>
          <button class="doc-more" onclick="event.stopPropagation();toggleCtx(this)" title="Opsi">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#888"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
          </button>
          <div class="ctx">
            <div class="ctx-i" onclick="event.stopPropagation();window.location='{{ route('documents.edit',$doc->id) }}'">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
              Buka
            </div>
            <div class="ctx-i" onclick="event.stopPropagation();renameDoc({{ $doc->id }},'{{ addslashes($doc->title) }}')">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.83 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
              Ganti nama
            </div>
            <form method="POST" action="{{ route('documents.destroy',$doc->id) }}" onclick="event.stopPropagation()" onsubmit="return confirm('Hapus dokumen ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="ctx-i del" style="width:100%;background:none;border:none;cursor:pointer;text-align:left">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
                Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
      @empty
      <div class="empty">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <p>Belum ada dokumen. Buat yang pertama di atas!</p>
      </div>
      @endforelse
    </div>
  </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input',function(){
  const q=this.value.toLowerCase();
  document.querySelectorAll('#docsGrid>.doc-card').forEach(c=>{c.style.display=(!q||c.dataset.title.includes(q))?'':'none';});
});
function toggleCtx(btn){
  const m=btn.nextElementSibling,open=m.classList.contains('on');
  document.querySelectorAll('.ctx.on').forEach(x=>x.classList.remove('on'));
  if(!open)m.classList.add('on');
}
document.addEventListener('click',()=>document.querySelectorAll('.ctx.on').forEach(x=>x.classList.remove('on')));
function renameDoc(id,cur){
  const n=prompt('Ganti nama:',cur);
  if(n&&n.trim()&&n!==cur)
    fetch('/documents/'+id,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||''},
      body:JSON.stringify({title:n.trim(),content:'',editor_id:'sys',editor_name:'User'})}).then(()=>location.reload());
}
function setView(m){
  const g=document.getElementById('docsGrid');
  document.getElementById('btnGrid').classList.toggle('on',m==='grid');
  document.getElementById('btnList').classList.toggle('on',m==='list');
  g.style.gridTemplateColumns=m==='list'?'1fr':'';
  document.querySelectorAll('.doc-card').forEach(c=>{
    const p=c.querySelector('.doc-preview');
    if(m==='list'){c.style.display='flex';c.style.height='52px';if(p)p.style.display='none';}
    else{c.style.display='';c.style.height='';if(p)p.style.display='';}
  });
}
let az=false;
function sortDocs(){
  az=!az;document.getElementById('btnAZ').classList.toggle('on',az);
  const g=document.getElementById('docsGrid');
  Array.from(g.querySelectorAll('.doc-card')).sort((a,b)=>az?(a.dataset.title||'').localeCompare(b.dataset.title||''):parseInt(b.dataset.date||0)-parseInt(a.dataset.date||0)).forEach(c=>g.appendChild(c));
}
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>
