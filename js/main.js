/* Málaga IAM Open Forum · comportamiento común de las páginas (inicio y blog).
   Va en un archivo aparte para que la política de seguridad (CSP) no tenga que permitir scripts en línea. */

/* ---- Página de LinkedIn del foro (vacío = se oculta el enlace del pie) ---- */
const LINKEDIN_URL = "https://www.linkedin.com/company/malaga-iam-open-forum/";

(function(){
  // menú móvil
  const btn=document.getElementById('menu-btn'),nav=document.getElementById('nav');
  if(btn&&nav){
    btn.addEventListener('click',()=>{const o=nav.classList.toggle('open');btn.setAttribute('aria-expanded',o)});
    nav.addEventListener('click',e=>{if(e.target.tagName==='A'){nav.classList.remove('open');btn.setAttribute('aria-expanded','false')}});
  }

  // enlaces a LinkedIn
  ['btn-linkedin','btn-linkedin-2'].forEach(id=>{const a=document.getElementById(id);if(!a)return;
    if(LINKEDIN_URL){a.href=LINKEDIN_URL;a.target='_blank';a.rel='noopener'}else if(id==='btn-linkedin'){a.hidden=true}});

  // trayectoria punteada del logo en la portada (despegue vertical + giro)
  const g=document.getElementById('path-dots');
  if(g){
    const NS='http://www.w3.org/2000/svg',pts=[];
    for(let y=270;y>=150;y-=17)pts.push([281,y]);
    for(let a=195;a<=255;a+=15){const t=a*Math.PI/180;pts.push([361+80*Math.cos(t),150+80*Math.sin(t)])}
    for(let x=362;x<=446;x+=17)pts.push([x,70]);
    pts.forEach((p,i)=>{const c=document.createElementNS(NS,'circle');c.setAttribute('cx',p[0].toFixed(1));c.setAttribute('cy',p[1].toFixed(1));c.setAttribute('r','4.5');c.style.animationDelay=(i*0.06)+'s';g.appendChild(c)});
  }
})();
