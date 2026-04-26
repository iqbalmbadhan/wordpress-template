import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import EditorPanel from '../components/EditorPanel';

export default function HomePage() {
  const [form, setForm] = useState({ fname:'', lname:'', email:'', subject:'', message:'' });
  const [sent, setSent] = useState(false);

  // Fade-in
  useEffect(() => {
    const els = document.querySelectorAll('.fade-in');
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    els.forEach(el => obs.observe(el));
    return () => obs.disconnect();
  }, []);

  // Counter animation
  useEffect(() => {
    const counterEls = document.querySelectorAll('.counter');
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const target = parseInt(e.target.dataset.target);
        let start = null;
        const step = (ts) => {
          if (!start) start = ts;
          const prog = Math.min((ts - start) / 1200, 1);
          e.target.textContent = Math.floor(prog * target);
          if (prog < 1) requestAnimationFrame(step);
          else e.target.textContent = target;
        };
        requestAnimationFrame(step);
        obs.unobserve(e.target);
      });
    }, { threshold: 0.5 });
    counterEls.forEach(el => obs.observe(el));
    return () => obs.disconnect();
  }, []);

  // Skill bars
  useEffect(() => {
    const fills = document.querySelectorAll('.skill-fill');
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.width = e.target.dataset.width + '%';
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.3 });
    fills.forEach(el => obs.observe(el));
    return () => obs.disconnect();
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();
    setSent(true);
    setTimeout(() => setSent(false), 5000);
  };

  return (
    <>
      <Navbar />
      <main id="main-content">

        {/* ── HERO ─────────────────────────────────────────────── */}
        <section id="hero" aria-label="Introduction">
          <div className="bg-grid" />
          <div className="bg-glow" />
          <div className="hero-grid">
            <div className="hero-left">
              <div className="hero-eyebrow fade-in">ServiceNow Expert · AI Transformation · Chicago, IL</div>
              <h1 className="fade-in fade-in-d1">
                Transforming<br />Business With <em>AI &amp;</em><br />ServiceNow
              </h1>
              <div className="hero-roles fade-in fade-in-d2" aria-label="Professional roles">
                <div className="hero-role"><div className="hero-role-dot" />ServiceNow Consultant &amp; AI Architect</div>
                <div className="hero-role"><div className="hero-role-dot" style={{background:'var(--accent2)'}} />Digital Transformation &amp; Business Agent</div>
                <div className="hero-role"><div className="hero-role-dot" />Enterprise AI &amp; ITSM Solution Architect</div>
              </div>
              <div className="hero-btns fade-in fade-in-d3">
                <a href="#contact" className="btn-primary" onClick={e=>{e.preventDefault();document.getElementById('contact')?.scrollIntoView({behavior:'smooth'})}}>Start a Conversation</a>
                <a href="#about"   className="btn-outline"  onClick={e=>{e.preventDefault();document.getElementById('about')?.scrollIntoView({behavior:'smooth'})}}>View Resume</a>
              </div>
              <div className="hero-stats fade-in fade-in-d3">
                <div><div className="stat-num"><span className="counter" data-target="20">0</span>+</div><div className="stat-label">Years Experience</div></div>
                <div><div className="stat-num"><span className="counter" data-target="94">0</span><span>%</span></div><div className="stat-label">Client Satisfaction</div></div>
                <div><div className="stat-num"><span className="counter" data-target="50">0</span>+</div><div className="stat-label">Enterprise Clients</div></div>
              </div>
            </div>
            <div className="hero-visual fade-in fade-in-d2">
              <div className="hero-card">
                <div className="hero-card-accent" />
                <div className="hero-card-accent2" />
                <div className="hero-photo-placeholder" style={{background:'var(--bg3)',border:'none',padding:0,overflow:'hidden'}}>
                  <img src="https://www.dawncsimmons.com/wp-content/uploads/2025/12/dawn_c_simmons.png" alt="Dawn Christine Simmons — ServiceNow Consultant &amp; AI Transformation Expert" style={{width:'100%',height:'100%',objectFit:'cover',objectPosition:'center top',display:'block'}} />
                </div>
                <div className="hero-card-name">Dawn C. Simmons</div>
                <div className="hero-card-title">Senior ServiceNow Consultant</div>
                <div className="hero-card-badges">
                  <span className="badge">ServiceNow CSA</span>
                  <span className="badge">ITIL v4</span>
                  <span className="badge">Fortune 500</span>
                  <span className="badge">ITSM</span>
                  <span className="badge">GRC</span>
                </div>
              </div>
              <div className="floating-skill s1">
                <div className="fs-label">ServiceNow</div>
                <div className="fs-pct">100%</div>
                <div className="fs-bar"><div className="fs-fill" style={{width:'100%'}} /></div>
              </div>
              <div className="floating-skill s2">
                <div className="fs-label">ITSM / CMDB</div>
                <div className="fs-pct">98%</div>
                <div className="fs-bar"><div className="fs-fill" style={{width:'98%'}} /></div>
              </div>
            </div>
          </div>
        </section>

        {/* ── AI SOLUTIONS ─────────────────────────────────────── */}
        <section id="ai" aria-labelledby="ai-heading">
          <div className="container">
            <div className="ai-intro-grid">
              <div className="fade-in">
                <div className="section-eyebrow">AI + ServiceNow + Business</div>
                <h2 className="ai-headline" id="ai-heading">Unlocking <em>AI-Powered</em><br />Business Intelligence</h2>
                <p className="ai-lead">Dawn bridges the gap between cutting-edge artificial intelligence and real-world enterprise operations — embedding AI capabilities directly into ServiceNow workflows to automate, predict, and accelerate business outcomes.</p>
                <p className="ai-lead" style={{marginBottom:'28px'}}>From predictive incident management to AI-driven CMDB health scoring and intelligent automation across ITSM, HRSD and SecOps — Dawn brings a practitioner's eye to every AI initiative.</p>
                <div className="ai-pills" aria-label="AI expertise areas">
                  {['Predictive Intelligence','AI Automation','Now Assist (GenAI)','ML Classification','NLP & Virtual Agent','AI-Ops','Process Mining','Intelligent Workflows'].map(p => (
                    <span key={p} className="ai-pill">{p}</span>
                  ))}
                </div>
              </div>
              <div className="ai-visual fade-in fade-in-d1" aria-hidden="true">
                <div className="ai-glow" />
                <div className="ai-visual-label">AI-Powered ServiceNow Workflow</div>
                <div className="ai-flow">
                  {[
                    { icon: <><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></>, name:'Data Ingestion & Signal Detection', desc:'CMDB, incidents, events, HRSD records' },
                    { icon: <><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></>, name:'AI & ML Processing Layer', desc:'Predictive Intelligence, NLP, classification' },
                    { icon: <polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>, name:'Intelligent Automation', desc:'Auto-routing, resolution, virtual agent' },
                    { icon: <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>, name:'Business Outcomes & KPIs', desc:'Cost savings, SLA improvement, ROI' },
                  ].map((step, i) => (
                    <div key={i}>
                      {i > 0 && <div className="ai-connector" />}
                      <div className="ai-flow-step">
                        <div className="ai-flow-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">{step.icon}</svg></div>
                        <div className="ai-flow-text">
                          <div className="ai-flow-name">{step.name}</div>
                          <div className="ai-flow-desc">{step.desc}</div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
            <div className="ai-cards-grid">
              {[
                { delay:'', icon:<><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></>, title:'Predictive Intelligence', desc:"Leverage ServiceNow's built-in ML to auto-classify incidents, predict SLA breaches, and surface patterns before they become problems." },
                { delay:' fade-in-d1', icon:<><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h.01M15 9h.01M9 15h6"/></>, title:'Now Assist (GenAI)', desc:"Deploy ServiceNow's generative AI capabilities — AI-powered case summarization, resolution recommendations, and agent assist across ITSM and HRSD." },
                { delay:' fade-in-d2', icon:<><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></>, title:'Intelligent Automation', desc:"Design and implement AI-driven workflow automation — eliminating repetitive tasks, accelerating approvals, and reducing mean time to resolution (MTTR)." },
                { delay:' fade-in-d3', icon:<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>, title:'AI-Ops & AIOps', desc:"Integrate AI into IT operations monitoring — intelligent event correlation, noise reduction, and proactive anomaly detection across your enterprise infrastructure." },
              ].map(c => (
                <div key={c.title} className={`ai-card fade-in${c.delay}`}>
                  <div className="ai-card-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">{c.icon}</svg></div>
                  <h3 className="ai-card-title">{c.title}</h3>
                  <p className="ai-card-desc">{c.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ── SERVICES ─────────────────────────────────────────── */}
        <section id="services" aria-labelledby="services-heading">
          <div className="container">
            <div className="section-header fade-in">
              <div className="section-eyebrow">What I Do</div>
              <h2 className="section-title" id="services-heading">Expert-level <em>services</em><br />built on 20+ years</h2>
              <p className="section-sub">From global AI-powered program management to hands-on ServiceNow implementation — end-to-end digital transformation for enterprise.</p>
            </div>
            <div className="services-grid" role="list">
              {[
                { num:'01', delay:'', title:'Global Program Director', desc:'Directed multi-million-dollar cloud implementations, aligning ServiceNow Cloud, ITAM, SCCM, GRC, and AI automation to organizational goals across global enterprises.', tags:['ServiceNow','AI Automation','ITAM','GRC'] },
                { num:'02', delay:' fade-in-d1', title:'Enterprise IT & AI Consulting', desc:'ServiceNow and AI transformations in healthcare, pharma, higher education, and energy — delivering end-to-end ITSM, CMDB, SecOps, HRSD, and predictive intelligence solutions.', tags:['ITSM','CMDB','SecOps','HRSD','AI/ML'] },
                { num:'03', delay:' fade-in-d2', title:'Strategic Leadership & Advisory', desc:'Fortune 500 executive guidance — aligning AI and technology strategy with business goals, driving measurable cost savings, efficiency gains, and long-term digital roadmaps.', tags:['Executive Advisory','AI Strategy','Roadmapping'] },
              ].map(s => (
                <article key={s.num} className={`service-card fade-in${s.delay}`} role="listitem" itemScope itemType="https://schema.org/Service">
                  <div className="service-num" aria-hidden="true">{s.num}</div>
                  <h3 className="service-title" itemProp="name">{s.title}</h3>
                  <p className="service-desc" itemProp="description">{s.desc}</p>
                  <div className="service-tags" aria-label="Technologies">{s.tags.map(t => <span key={t} className="stag">{t}</span>)}</div>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* ── ABOUT ────────────────────────────────────────────── */}
        <section id="about" aria-labelledby="about-heading" itemScope itemType="https://schema.org/Person">
          <div className="container">
            <div className="about-grid">
              <div className="about-photo fade-in" style={{background:'none',border:'none',padding:0,overflow:'hidden',borderRadius:'12px',position:'relative'}}>
                <img src="https://www.dawncsimmons.com/wp-content/uploads/2024/09/dawn-c-simmons-abou-me-1.png" alt="Dawn Christine Simmons — Senior ServiceNow Consultant, Chicago IL" style={{width:'100%',height:'100%',objectFit:'cover',objectPosition:'center top',display:'block',borderRadius:'12px'}} />
                <div className="about-photo-accent" />
              </div>
              <div className="about-info fade-in fade-in-d1">
                <div className="section-eyebrow">About Me</div>
                <h2 className="section-title" id="about-heading" style={{marginBottom:'20px'}} itemProp="name">Dynamic Leadership with <em>Global Impact</em></h2>
                <p itemProp="description">Dawn C. Simmons is a transformative, visionary leader with over 20 years of executive experience in digital transformation, AI-powered business solutions, and ServiceNow implementations.</p>
                <p>A recognized expert in enterprise AI integration and ServiceNow architecture, Dawn has delivered measurable results across Fortune 500 companies in healthcare, pharma, higher education, and energy — combining deep technical expertise with executive-level strategic vision.</p>
                <div className="skills-list" aria-label="Professional skills">
                  {[
                    { label:'ServiceNow Platform',          pct:'100%', w:100 },
                    { label:'AI & Predictive Intelligence', pct:'95%',  w:95  },
                    { label:'Service Management COE',       pct:'98%',  w:98  },
                    { label:'Business Process Management',  pct:'92%',  w:92  },
                    { label:'ITSM / ITIL v4',               pct:'96%',  w:96  },
                  ].map(s => (
                    <div key={s.label} className="skill-item">
                      <div className="skill-head"><span>{s.label}</span><span className="skill-pct">{s.pct}</span></div>
                      <div className="skill-track"><div className="skill-fill" data-width={s.w} /></div>
                    </div>
                  ))}
                </div>
                <div className="about-details">
                  <div className="detail-item"><div className="detail-label">Name</div><div className="detail-val">Dawn Christine Simmons</div></div>
                  <div className="detail-item"><div className="detail-label">Location</div><div className="detail-val">Chicago, IL USA</div></div>
                  <div className="detail-item"><div className="detail-label">Email</div><div className="detail-val"><a href="mailto:dawnckhan@gmail.com" style={{color:'var(--accent)',textDecoration:'none'}}>dawnckhan@gmail.com</a></div></div>
                  <div className="detail-item"><div className="detail-label">Phone</div><div className="detail-val"><a href="tel:+19252977901" style={{color:'var(--text)',textDecoration:'none'}}>+1-925-297-7901</a></div></div>
                </div>
                <div style={{marginTop:'32px'}}>
                  <a href="#contact" className="btn-primary" onClick={e=>{e.preventDefault();document.getElementById('contact')?.scrollIntoView({behavior:'smooth'})}}>Download Resume</a>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* ── TESTIMONIALS ─────────────────────────────────────── */}
        <section id="testimonials" aria-labelledby="testimonials-heading">
          <div className="container">
            <div className="section-header fade-in" style={{textAlign:'center',maxWidth:'600px',margin:'0 auto 60px'}}>
              <div className="section-eyebrow" style={{justifyContent:'center'}}>Social Proof</div>
              <h2 className="section-title" id="testimonials-heading">What colleagues<br />&amp; clients <em>say</em></h2>
            </div>
            <div className="testimonials-grid">
              {[
                { delay:'',            init:'SW', name:'Steve West',            role:'Board of Directors, Denver Metro HDI',                    text:"Dawn has demonstrated exemplary leadership in the Support Services industry through her incredible efforts. She is a seasoned management practitioner that understands service management concepts extremely well." },
                { delay:' fade-in-d1', init:'LS', name:'Lori Shaw',             role:'Senior Consultant',                                        text:"Very few people equal Dawn in persistence and dedication. I am continually impressed with her insight, intelligence, tenacity and ability to network across organizations." },
                { delay:' fade-in-d2', init:'VT', name:'Venkatesh Thiruvaipati',role:'Sun Microsystems',                                         text:'"Solution provider" — that can summarize how good she knows the business. She is one of the few people with excellent knowledge about Support Readiness. She is GREAT to work with.' },
                { delay:'',            init:'DA', name:'Dale Avery',             role:'Enterprise Network Services, Sun Microsystems',            text:"Dawn is able to quickly assess the needs of a project and break it into manageable, achievable parts. Her ability to network and work with all personalities facilitates the influencing of all key stakeholders." },
                { delay:' fade-in-d1', init:'FT', name:'Frank Tawil',           role:'Enterprise Network Services, Sun Microsystems Bay Area',   text:"Dawn is one of the most passionate and driven people I know. Her background and experience makes her a very well rounded qualified candidate for any challenging opportunity." },
                { delay:' fade-in-d2', init:'DB', name:'Deepanker Baderia',     role:'Solution Architect, Sun Microsystems',                     text:"Dawn is an excellent project and program manager, bringing diverse skills including effective teamwork, attention to detail, excellent facilitation, and strong leadership. Dawn produces results." },
              ].map(t => (
                <div key={t.init} className={`tcard fade-in${t.delay}`}>
                  <div className="tcard-quote">"</div>
                  <div className="tcard-text">{t.text}</div>
                  <div className="tcard-author">
                    <div className="tcard-avatar">{t.init}</div>
                    <div><div className="tcard-name">{t.name}</div><div className="tcard-role">{t.role}</div></div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* ── BLOG ─────────────────────────────────────────────── */}
        <section id="blog" aria-labelledby="blog-heading">
          <div className="container">
            <div className="section-header fade-in" style={{display:'flex',justifyContent:'space-between',alignItems:'flex-end',marginBottom:'48px'}}>
              <div>
                <div className="section-eyebrow">Insights</div>
                <h2 className="section-title" id="blog-heading" style={{marginBottom:0}}>Latest from<br />the <em>blog</em></h2>
              </div>
              <a href="https://www.dawncsimmons.com/blog" target="_blank" rel="noopener" className="btn-outline" style={{flexShrink:0}}>All Articles</a>
            </div>
            <div className="blog-grid" role="list">
              {[
                { delay:'',            href:'https://www.dawncsimmons.com/claude-ui-ux-market-signals/',  date:'Apr 20, 2026', pub:'2026-04-20', title:'Claude UI/UX Market Signals',  excerpt:'Exploring AI-driven design shifts and what they mean for enterprise digital transformation teams.', grad:'oklch(72% 0.155 195 / 0.06),oklch(72% 0.155 145 / 0.04)' },
                { delay:' fade-in-d1', href:'https://www.dawncsimmons.com/ai-upgrades-redefined-now/',   date:'Apr 13, 2026', pub:'2026-04-13', title:'AI Upgrades Redefined Now',     excerpt:"How ServiceNow's latest AI enhancements are reshaping ITSM workflows and business process automation.", grad:'oklch(72% 0.155 145 / 0.08),oklch(72% 0.155 195 / 0.04)' },
                { delay:' fade-in-d2', href:'https://www.dawncsimmons.com/chicago-cybersecurity/',        date:'Apr 7, 2026',  pub:'2026-04-07', title:'Chicago Cybersecurity',          excerpt:"A deep dive into Chicago's evolving cybersecurity landscape and what enterprise leaders should know now.", grad:'oklch(72% 0.155 220 / 0.08),oklch(72% 0.155 195 / 0.04)' },
              ].map(b => (
                <article key={b.title} className={`blog-card fade-in${b.delay}`} role="listitem" itemScope itemType="https://schema.org/BlogPosting">
                  <a href={b.href} target="_blank" rel="noopener" aria-label={`Read: ${b.title}`}>
                    <div className="blog-card-img" aria-hidden="true">
                      <div className="blog-card-img-accent" style={{background:`linear-gradient(135deg,${b.grad})`}} />
                      <span style={{position:'relative',zIndex:1,fontSize:'11px',color:'var(--muted)',letterSpacing:'.08em'}}>article image</span>
                    </div>
                    <div className="blog-card-body">
                      <div className="blog-date" itemProp="datePublished" content={b.pub}>{b.date}</div>
                      <h3 className="blog-title" itemProp="headline">{b.title}</h3>
                      <p className="blog-excerpt" itemProp="description">{b.excerpt}</p>
                    </div>
                  </a>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* ── CONTACT ──────────────────────────────────────────── */}
        <section id="contact">
          <div className="container">
            <div className="contact-grid">
              <div className="fade-in">
                <div className="section-eyebrow">Get in Touch</div>
                <h2 className="section-title">Let's work<br /><em>together</em></h2>
                <p style={{fontSize:'15px',color:'var(--text2)',lineHeight:1.8,marginBottom:'40px'}}>Ready to transform your enterprise? I'd love to hear about your challenges and explore how we can work together.</p>
                {[
                  { icon:<><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></>, label:'Email',    val:<a href="mailto:dawnckhan@gmail.com">dawnckhan@gmail.com</a> },
                  { icon:<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.72A2 2 0 012.18 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 8.15A16 16 0 0015.54 16.78l1.41-1.41a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>, label:'Phone',   val:<a href="tel:+19252977901">+1-925-297-7901</a> },
                  { icon:<><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></>, label:'Location', val:'Chicago, IL USA' },
                ].map(c => (
                  <div key={c.label} className="contact-item">
                    <div className="contact-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">{c.icon}</svg></div>
                    <div><div className="contact-label">{c.label}</div><div className="contact-val">{c.val}</div></div>
                  </div>
                ))}
              </div>
              <div className="fade-in fade-in-d1">
                <form className="contact-form" id="contactForm" onSubmit={handleSubmit}>
                  <div className="form-row">
                    <div className="form-group">
                      <label htmlFor="fname">First Name</label>
                      <input type="text" id="fname" placeholder="Jane" required value={form.fname} onChange={e=>setForm(f=>({...f,fname:e.target.value}))} />
                    </div>
                    <div className="form-group">
                      <label htmlFor="lname">Last Name</label>
                      <input type="text" id="lname" placeholder="Smith" required value={form.lname} onChange={e=>setForm(f=>({...f,lname:e.target.value}))} />
                    </div>
                  </div>
                  <div className="form-group">
                    <label htmlFor="email">Email Address</label>
                    <input type="email" id="email" placeholder="jane@company.com" required value={form.email} onChange={e=>setForm(f=>({...f,email:e.target.value}))} />
                  </div>
                  <div className="form-group">
                    <label htmlFor="subject">Subject</label>
                    <input type="text" id="subject" placeholder="ServiceNow consultation" value={form.subject} onChange={e=>setForm(f=>({...f,subject:e.target.value}))} />
                  </div>
                  <div className="form-group">
                    <label htmlFor="message">Message</label>
                    <textarea id="message" placeholder="Tell me about your project..." required value={form.message} onChange={e=>setForm(f=>({...f,message:e.target.value}))} />
                  </div>
                  <button type="submit" className="form-submit">Send Message →</button>
                  {sent && <div className="form-success" style={{display:'block'}}>✓ Message sent! I'll get back to you within 24 hours.</div>}
                </form>
              </div>
            </div>
          </div>
        </section>

      </main>
      <Footer />
      <EditorPanel page="home" />
    </>
  );
}
