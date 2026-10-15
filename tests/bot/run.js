const { chromium } = require('playwright');
const QS = JSON.parse(require('fs').readFileSync(process.argv[2]||'bt_q.json','utf8'));
(async()=>{const b=await chromium.launch();const p=await b.newPage({viewport:{width:1300,height:850}});const errs=[];p.on('pageerror',e=>errs.push(e.message));
await p.goto(''+(process.env.DASH_URL||'http://127.0.0.1:8080/index.php')+'');await p.fill('#u','admin');await p.fill('#p','S3cret-Pass-123');await p.keyboard.press('Enter');await p.waitForTimeout(4000);
await p.evaluate(()=>{ if(!allRows.length) document.getElementById('btnRefresh').click(); });await p.waitForFunction(()=>allRows.length>0,null,{timeout:30000});
await p.evaluate(()=>{const nm=['Rahul Sharma','Priya Nair','Rahul Verma','Mohammed Aslam','Ananya Iyer'];nm.forEach((n,i)=>{allRows[i]._NAME=n});window.__ids=nm.map((n,i)=>allRows[i].ID);});
for (const q of QS){
  const out=await p.evaluate(async(q)=>{const B=window.__monkeyBot;const rep=await B.brain(q);const d=document.createElement('div');d.innerHTML=(rep&&(rep.h||rep.t))||'(null)';return {txt:d.textContent.replace(/\s+/g,' ').slice(0,420),mood:rep&&rep.mood,edit:B.state.edit?B.state.edit.step:null,pend:B.state.pending?B.state.pending.t:null}},q);
  console.log('Q:',q,'\n  A:',out.txt,'\n  ['+out.mood+(out.edit?' edit='+out.edit:'')+(out.pend?' pend='+out.pend:'')+']');
}
console.log('ERRS',errs);await b.close();process.exit(0)})();
