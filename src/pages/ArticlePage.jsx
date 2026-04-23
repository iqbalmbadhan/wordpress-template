import { useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import EditorPanel from '../components/EditorPanel';
import { useTheme } from '../context/ThemeContext';

const TOC = [
  { id:'design-not-dead',  label:'"Design Is Dead" Is the Wrong Headline' },
  { id:'market-restacking',label:'Claude vs Adobe vs Figma: Market Re-Stacking', sub:[
    { id:'claude-front-door', label:'Claude Design: Fast Front Door' },
    { id:'adobe-enterprise',  label:'Adobe: Enterprise Production'   },
    { id:'figma-governance',  label:'Figma: Design Systems & Governance' },
  ]},
  { id:'workspaces',    label:'AI Workspaces Replace Portals' },
  { id:'uiux-future',   label:'Future of UI/UX Jobs'          },
  { id:'final-signal',  label:'Final Market Signal'           },
  { id:'resources',     label:'Resources'                     },
];

const RELATED = [
  { href:'https://www.dawncsimmons.com/servicenow-ai-best-practices/', cat:'ServiceNow · AI',         title:'ServiceNow AI Best Practices', excerpt:'Stop AI tool sprawl. Use ServiceNow SPM + EAP as your system of record and assign AI ownership across Now Assist, Moveworks, and Claude.', meta:'Feb 18, 2026 · 6 min read', grad:'oklch(72% 0.155 195/0.08)' },
  { href:'https://www.dawncsimmons.com/ai-upgrades-redefined-now/',    cat:'ServiceNow · Upgrades',   title:'AI Upgrades Redefined Now',     excerpt:'ServiceNow Australia brings AI directly into workflows — learn how release test automation and AI validation accelerate upgrades.',           meta:'Apr 13, 2026 · 6 min read', grad:'oklch(72% 0.155 145/0.08)' },
  { href:'https://www.dawncsimmons.com/iwd-ai-service-management/',    cat:'AI · ITSM · Women in Tech',title:'IWD: AI Service Management',   excerpt:'HDI Chicagoland spotlights the women shaping AI-enabled ITSM, service operations, SecOps, and AI-driven customer experience.',                meta:'Feb 24, 2026 · 5 min read', grad:'oklch(72% 0.155 260/0.08)' },
];

function RelatedCard({ href, cat, title, excerpt, meta, grad }) {
  return (
    <a
      href={href}
      target="_blank"
      rel="noopener"
      className="post-card"
      style={{textDecoration:'none',display:'block',transition:'all .3s'}}
      onMouseOver={e=>{e.currentTarget.style.transform='translateY(-4px)';e.currentTarget.style.borderColor='oklch(72% 0.155 195 / 0.4)'}}
      onMouseOut={e=>{e.currentTarget.style.transform='';e.currentTarget.style.borderColor=''}}
    >
      <div style={{aspectRatio:'16/9',background:'var(--bg3)',display:'flex',alignItems:'center',justifyContent:'center',fontSize:'11px',color:'var(--muted)',letterSpacing:'.08em',textTransform:'uppercase',borderBottom:'1px solid var(--border)',position:'relative',overflow:'hidden'}}>
        <div style={{position:'absolute',inset:0,background:`linear-gradient(135deg,${grad},transparent)`}} />
        <span style={{position:'relative'}}>article image</span>
      </div>
      <div style={{padding:'22px'}}>
        <div style={{fontSize:'10px',letterSpacing:'.1em',textTransform:'uppercase',color:'var(--accent)',marginBottom:'8px'}}>{cat}</div>
        <div style={{fontSize:'16px',fontWeight:600,color:'var(--text)',lineHeight:1.4,marginBottom:'8px'}}>{title}</div>
        <div style={{fontSize:'13px',color:'var(--text2)',lineHeight:1.65}}>{excerpt}</div>
        <div style={{fontSize:'11px',color:'var(--muted)',marginTop:'12px'}}>{meta}</div>
      </div>
    </a>
  );
}

export default function ArticlePage() {
  const { articleFontSize, articleWidth } = useTheme();
  const tocLinksRef = useRef([]);

  // Fade-in
  useEffect(() => {
    const els = document.querySelectorAll('.fade-in');
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    els.forEach(el => obs.observe(el));
    return () => obs.disconnect();
  }, []);

  // TOC scroll-spy
  useEffect(() => {
    const headings = document.querySelectorAll('.article-body h2[id], .article-body h3[id]');
    if (!headings.length) return;
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          document.querySelectorAll('.toc-item a').forEach(a => a.classList.remove('toc-active'));
          const a = document.querySelector(`.toc-item a[href="#${e.target.id}"]`);
          if (a) a.classList.add('toc-active');
        }
      });
    }, { rootMargin: '-20% 0% -70% 0%' });
    headings.forEach(h => obs.observe(h));
    return () => obs.disconnect();
  }, []);

  // Article font size
  useEffect(() => {
    document.querySelectorAll('.article-body p, .article-body li').forEach(el => {
      el.style.fontSize = articleFontSize + 'px';
    });
  }, [articleFontSize]);

  const scrollToHeading = (e, id) => {
    e.preventDefault();
    const el = document.getElementById(id);
    if (el) window.scrollTo({ top: el.offsetTop - 90, behavior: 'smooth' });
  };

  return (
    <>
      <Navbar />

      {/* ARTICLE HERO */}
      <header className="article-hero" role="banner">
        <div className="article-hero-inner">
          <nav className="breadcrumb" aria-label="Breadcrumb">
            <Link to="/">Home</Link> ›
            <Link to="/blog">Blog</Link> ›
            <Link to="/blog">ServiceNow</Link> ›
            <span aria-current="page">Claude UI/UX Market Signals</span>
          </nav>
          <div className="article-cat-badge">ServiceNow · AI Design · Market Analysis</div>
          <h1 className="article-title">Claude <em>UI/UX</em> Market Signals</h1>
          <div className="article-meta" role="contentinfo">
            <div className="article-meta-avatar">
              <img src="uploads/dawn_c_simmons.PNG" alt="Dawn Christine Simmons" />
            </div>
            <strong>Dawn Christine Simmons</strong>
            <div className="meta-dot" />
            <time dateTime="2026-04-20">April 20, 2026</time>
            <div className="meta-dot" />
            <span>8 min read</span>
            <div className="meta-dot" />
            <span style={{color:'var(--accent)'}}>ServiceNow</span>
          </div>
          <div className="article-feature-img" aria-label="Article feature image placeholder">
            <div className="article-feature-img-glow" />
            <span style={{position:'relative',zIndex:1}}>featured article image</span>
          </div>
        </div>
      </header>

      {/* ARTICLE LAYOUT */}
      <main id="main-content">
        <div className="article-layout" style={{ maxWidth: articleWidth }}>

          {/* ARTICLE BODY */}
          <article className="article-body" itemScope itemType="https://schema.org/BlogPosting">
            <meta itemProp="headline"      content="Claude UI/UX Market Signals" />
            <meta itemProp="datePublished" content="2026-04-20" />
            <meta itemProp="author"        content="Dawn Christine Simmons" />

            <div className="key-points">
              <div className="key-points-title">Key Takeaways</div>
              <div className="key-point"><div className="kp-dot" /><span>Claude Design compresses early-stage UI/UX work — wireframes, mockups, and concepts in minutes, not days.</span></div>
              <div className="key-point"><div className="kp-dot" /><span>The market is re-stacking — Claude, Adobe, and Figma each hold distinct and complementary value.</span></div>
              <div className="key-point"><div className="kp-dot" /><span>AI-powered workspaces are replacing traditional employee portals as the center of enterprise support.</span></div>
              <div className="key-point"><div className="kp-dot" /><span>UI/UX roles are becoming <em>more</em> valuable — not less — as strategic design work grows in importance.</span></div>
            </div>

            <p itemProp="description"><strong>Claude UI/UX Market Signals:</strong> Friday's Claude Design release immediately created one of the biggest AI and UI/UX market conversations of 2026. The market reacts quickly because Adobe is already connecting its Firefly AI assistant to Claude, while analysts immediately tie Claude Design to new competitive pressure on Adobe and Figma.</p>
            <p>However, the real signal reaches far beyond one product launch. Claude Design proves that AI now compresses first-draft work. UI/UX teams no longer win by protecting wireframes, mockups, and low-level design production alone. Instead, they win by owning design systems, accessibility, workflow design, governance, brand consistency, and production quality.</p>

            <h2 id="design-not-dead">"Design Is Dead" Is the Wrong Headline</h2>
            <p>The loudest sensational headline says, "Design is dead." That is the wrong read on this market moment.</p>
            <div className="pullquote"><p>"AI is not the goal. Value is the goal. Design is not dying. It is advancing."</p></div>
            <p>The better headline is this: <strong>Routine Design Work Is Automating &amp; Strategic UX Design Is More Valuable.</strong></p>
            <p>Smart UI/UX designers know this. What used to be about portal design is now about Design for AI and UI/UX, everything. ServiceNow Support Workflow Portals were replaced by workspaces to embrace the death of "search to try to find something" — now being replaced by Case Management and Now Assist. Design is the next to follow suit.</p>
            <p>Claude accelerates concepts, prototypes, one-pagers, mockups, and content generation. Nevertheless, that shift <strong>does not reduce the value of UI/UX professionals</strong>. Rather, it <strong>increases the value</strong> of:</p>
            <ul>
              <li>UX strategy and human-centered design</li>
              <li>Accessibility and inclusive design</li>
              <li>Enterprise workflow design</li>
              <li>Design systems and governance</li>
              <li>Brand consistency across platforms</li>
              <li>Cross-platform customer and employee experience</li>
            </ul>

            <h2 id="market-restacking">Claude vs Adobe vs Figma: AI UI/UX Market Re-Stacking</h2>
            <p>The market is not collapsing. Instead, the market is re-stacking. There is still strong and distinct value for Claude, Adobe, and Figma — each playing a different role in the new stack.</p>

            <table className="comparison-table" aria-label="AI UI/UX tool comparison">
              <thead>
                <tr><th>Tool</th><th>Primary Role</th><th>Strongest Use Cases</th></tr>
              </thead>
              <tbody>
                <tr><td><strong style={{color:'var(--accent)'}}>Claude Design</strong></td><td>Fast Front Door for AI-Powered UX</td><td>Wireframes, presentations, marketing concepts, employee portal ideas, one-pagers</td></tr>
                <tr><td><strong>Adobe</strong></td><td>Enterprise Creative Production</td><td>Brand consistency, enterprise content governance, professional editing, high-quality visual production</td></tr>
                <tr><td><strong>Figma</strong></td><td>Collaborative Design &amp; Governance</td><td>Design systems, real-time collaboration, prototypes, UX governance, production-ready experiences</td></tr>
              </tbody>
            </table>

            <h3 id="claude-front-door">Claude Design: The Fast Front Door for AI-Powered UX</h3>
            <p>Claude becomes the new front door for ideation and first-pass creation. Teams can now create wireframes, presentations, marketing concepts, internal support experiences, employee portal ideas, and UX drafts — in minutes instead of days.</p>

            <h3 id="adobe-enterprise">Adobe: Enterprise Creative Production &amp; Brand Control</h3>
            <p>Adobe continues to dominate where precision, governance, and enterprise-safe creative production matter most. Adobe is not losing relevance — it is moving higher in the value chain by combining AI-assisted speed with trusted creative control across Photoshop, Illustrator, and Premiere Pro.</p>

            <h3 id="figma-governance">Figma: Collaborative Design Systems &amp; UX Governance</h3>
            <p>Figma's role shifts from "starting point" to "shared execution and governance platform." Teams use Figma to refine AI-generated concepts, apply design systems, collaborate in real time, and govern enterprise UX standards.</p>

            <h2 id="workspaces">Future of Internal Support: AI Workspaces Replace Portals</h2>
            <p>The largest disruption is not just happening in creative design. It is happening in internal support — and that is driving the next wave of UI/UX demand.</p>
            <div className="pullquote"><p>"The future of support is not a portal full of links. It is an AI-powered workspace that delivers value across the entire journey."</p></div>
            <p>Gartner found 91% of High Maturity Service Organizations have dedicated AI leader-driven service transformation. Ownership is the difference between AI noise and AI value. The future is an agentic AI-powered workspace with strong UI/UX that guides people through the full journey.</p>
            <h3>Why Workspaces Beat Portals for AI</h3>
            <p>A portal mainly offers search boxes, links libraries, knowledge articles, forms, and ticket submission. A workspace gives AI far more context — the live ticket, user history, related incidents and changes, team conversations, analytics and trends, workflow state and next steps.</p>
            <p>Therefore, AI can do much more inside a workspace: generate summaries, recommend next best actions, draft responses, suggest knowledge, predict escalations, route work automatically, and guide the agent through the journey.</p>

            <h2 id="uiux-future">Future of UI/UX Jobs in an AI-Driven Marketplace</h2>
            <p>The future belongs to professionals who can design AI-powered experiences, build and govern design systems, create better workspaces, manage human-AI collaboration, improve workflows and knowledge management, and lead accessibility, usability, and trust.</p>
            <p><strong>UI/UX jobs are not disappearing.</strong> Instead, UI/UX roles are becoming more strategic, more technical, and more valuable.</p>

            <h2 id="final-signal">Final Market Signal: Claude Ends Slow Starts, Not Design</h2>
            <p>AI does not erase design. Instead, AI compresses the earliest and slowest parts of the process. Portals do not disappear — but workspaces increasingly become the center of internal support.</p>
            <div className="pullquote"><p>"Claude does not end design. Claude ends slow design starts."</p></div>
            <p>The strongest market message is simple. The next competitive battleground in internal support is no longer the portal home page. It is the AI-powered workspace where support, automation, collaboration, and experience come together.</p>

            <h2 id="resources">Resources</h2>
            <ul>
              <li><a href="https://www.thinkhdi.com/library/supportworld/2026/agentic-ai-service-management.aspx" target="_blank" rel="noopener">AI Is Already Running Service &amp; Support — Prove Outcomes or Pay for Chaos</a></li>
              <li><a href="https://www.linkedin.com/groups/13699504/" target="_blank" rel="noopener">Association of Generative-AI (LinkedIn Group)</a></li>
              <li><a href="https://www.thinkhdi.com/library/supportworld/2026/beyond-the-ticket-factory" target="_blank" rel="noopener">Beyond the Ticket Factory: ITIL 5 Ignites Intelligent Service</a></li>
              <li><a href="https://www.dawncsimmons.com/servicenow-dreams-in-figma/" target="_blank" rel="noopener">ServiceNow Dreams in Figma</a></li>
              <li><a href="https://www.dawncsimmons.com/figma-based-design-tokens/" target="_blank" rel="noopener">Figma Based Design Tokens</a></li>
            </ul>

            <div className="article-tags" role="list" aria-label="Article tags">
              {['Claude Design','AI UI UX','ServiceNow AI','UI UX Market Analysis','AI Design Tools 2026','Figma vs Claude','Enterprise UX','Anthropic AI','AI Workflow Automation'].map(t => (
                <a key={t} href="#" className="article-tag" role="listitem" onClick={e=>e.preventDefault()}>{t}</a>
              ))}
            </div>
          </article>

          {/* SIDEBAR */}
          <aside className="sidebar" aria-label="Article sidebar">

            {/* TOC */}
            <div className="sidebar-card">
              <div className="sidebar-title">Table of Contents</div>
              <ul className="toc-list">
                {TOC.map(item => (
                  <li key={item.id} className="toc-item">
                    <a href={`#${item.id}`} onClick={(e) => scrollToHeading(e, item.id)}>{item.label}</a>
                    {item.sub && (
                      <ul className="toc-list">
                        {item.sub.map(s => (
                          <li key={s.id} className="toc-item sub">
                            <a href={`#${s.id}`} onClick={(e) => scrollToHeading(e, s.id)}>{s.label}</a>
                          </li>
                        ))}
                      </ul>
                    )}
                  </li>
                ))}
              </ul>
            </div>

            {/* Author */}
            <div className="sidebar-card">
              <div className="sidebar-title">About the Author</div>
              <div style={{display:'flex',gap:'12px',alignItems:'center',marginBottom:'12px'}}>
                <div style={{width:'52px',height:'52px',borderRadius:'50%',overflow:'hidden',flexShrink:0,border:'2px solid var(--accent)'}}>
                  <img src="uploads/dawn_c_simmons.PNG" alt="Dawn Christine Simmons" style={{width:'100%',height:'100%',objectFit:'cover',objectPosition:'center top'}} />
                </div>
                <div>
                  <div style={{fontSize:'15px',fontWeight:600}}>Dawn C. Simmons</div>
                  <div style={{fontSize:'12px',color:'var(--muted)'}}>ServiceNow Consultant · AI Expert</div>
                </div>
              </div>
              <p style={{fontSize:'13px',color:'var(--text2)',lineHeight:1.65,marginBottom:'12px'}}>20+ years delivering digital transformation and ServiceNow solutions across Fortune 500 enterprises. Chicago, IL.</p>
              <Link to="/" style={{fontSize:'13px',color:'var(--accent)',textDecoration:'none'}}>Contact Dawn →</Link>
            </div>

            {/* Share */}
            <div className="sidebar-card">
              <div className="sidebar-title">Share This Article</div>
              <div className="share-btns">
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://www.dawncsimmons.com/claude-ui-ux-market-signals/" target="_blank" rel="noopener" className="share-btn">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                  Share on LinkedIn
                </a>
                <a href="https://twitter.com/intent/tweet?url=https://www.dawncsimmons.com/claude-ui-ux-market-signals/&text=Claude+UI/UX+Market+Signals" target="_blank" rel="noopener" className="share-btn">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                  Share on X
                </a>
              </div>
            </div>

          </aside>
        </div>

        {/* RELATED ARTICLES */}
        <section style={{background:'var(--bg2)',borderTop:'1px solid var(--border)',padding:'64px 48px'}} aria-labelledby="related-heading">
          <div style={{maxWidth:'1200px',margin:'0 auto'}}>
            <div style={{fontSize:'11px',letterSpacing:'.12em',textTransform:'uppercase',color:'var(--accent)',display:'flex',alignItems:'center',gap:'10px',marginBottom:'12px'}}>
              <span style={{display:'block',width:'24px',height:'1px',background:'var(--accent)'}} />Keep Reading
            </div>
            <h2 id="related-heading" style={{fontFamily:'var(--ff-display)',fontSize:'clamp(24px,3vw,36px)',fontWeight:900,letterSpacing:'-0.02em',marginBottom:'36px'}}>Related <em style={{fontStyle:'italic',color:'var(--accent)'}}>Articles</em></h2>
            <div style={{display:'grid',gridTemplateColumns:'repeat(3,1fr)',gap:'24px'}}>
              {RELATED.map(r => <RelatedCard key={r.title} {...r} />)}
            </div>
          </div>
        </section>
      </main>

      <Footer />
      <EditorPanel page="article" />
    </>
  );
}
