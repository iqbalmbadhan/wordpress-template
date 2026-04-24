import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import EditorPanel from '../components/EditorPanel';
import { useTheme } from '../context/ThemeContext';

const POSTS = [
  { href:'https://www.dawncsimmons.com/ai-upgrades-redefined-now/',         cats:['servicenow','ai'],                   cat:'ServiceNow · AI',             title:'AI Upgrades Redefined Now',         excerpt:'ServiceNow Australia brings AI directly into workflows — learn how release test automation and AI validation accelerate upgrades.',          date:'Apr 13, 2026', read:'6 min', delay:''            },
  { href:'https://www.dawncsimmons.com/chicago-cybersecurity/',               cats:['chicago','cybersecurity'],            cat:'Chicago · Cyber Security',    title:'Chicago Cybersecurity',             excerpt:"HDI Chicagoland's Courageous Cloud Leadership event — Chicago's finest cyber security leaders share trends, tips and best practices.",         date:'Apr 7, 2026',  read:'4 min', delay:' fade-in-d1' },
  { href:'https://www.dawncsimmons.com/automatepros-fastest-release-yet/',   cats:['automatepro','servicenow'],           cat:'AutomatePro · ServiceNow',    title:"AutomatePro's Fastest Release Yet", excerpt:'v9.3 moves ServiceNow teams beyond manual testing — smarter automation, real-time control, and full visibility to accelerate releases.',    date:'Mar 10, 2026', read:'5 min', delay:' fade-in-d2' },
  { href:'https://www.dawncsimmons.com/ai-gender-gap-bias-impact/',          cats:['ai'],                                 cat:'AI · Diversity & Inclusion',  title:'AI Gender-Gap Bias Impact',         excerpt:'AI gender-gap bias creates measurable harm across hiring, healthcare, credit, and customer experience — and how to address it.',              date:'Mar 9, 2026',  read:'7 min', delay:' fade-in-d3' },
  { href:'https://www.dawncsimmons.com/servicenow-ai-best-practices/',       cats:['servicenow','ai'],                   cat:'ServiceNow · AI Best Practices',title:'ServiceNow AI Best Practices',     excerpt:'Stop AI tool sprawl. Use ServiceNow SPM + EAP as your system of record, assign AI ownership across Now Assist, Moveworks, and Claude.',      date:'Feb 18, 2026', read:'6 min', delay:''            },
  { href:'https://www.dawncsimmons.com/iwd-ai-service-management/',          cats:['ai','itsm'],                          cat:'AI · ITSM · Women in Tech',   title:'IWD: AI Service Management',        excerpt:'HDI Chicagoland spotlights the women shaping AI-enabled ITSM, service operations, SecOps, and AI-driven customer experience.',                date:'Feb 24, 2026', read:'5 min', delay:' fade-in-d1' },
];

const FILTERS = [
  { key:'all',          label:'All Posts'      },
  { key:'servicenow',   label:'ServiceNow'     },
  { key:'ai',           label:'AI & Generative'},
  { key:'automatepro',  label:'AutomatePro'    },
  { key:'itsm',         label:'ITSM'           },
  { key:'chicago',      label:'Chicago'        },
  { key:'cybersecurity',label:'Cyber Security' },
  { key:'career',       label:'Jobs & Career'  },
];

export default function BlogPage() {
  const [active, setActive] = useState('all');
  const [search, setSearch] = useState('');
  const { blogLayout } = useTheme();

  useEffect(() => {
    const els = document.querySelectorAll('.fade-in');
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    els.forEach(el => obs.observe(el));
    return () => obs.disconnect();
  }, []);

  const visible = POSTS.filter(p =>
    (active === 'all' || p.cats.includes(active))
  );

  const isSidebar = blogLayout === 'sidebar';

  return (
    <>
      <Navbar />

      {/* PAGE HERO */}
      <header className="page-hero">
        <div className="hero-bg-text" aria-hidden="true">Blog</div>
        <div className="page-hero-inner">
          <div>
            <div style={{fontSize:'11px',letterSpacing:'.12em',textTransform:'uppercase',color:'var(--accent)',display:'flex',alignItems:'center',gap:'10px',marginBottom:'14px'}}>
              <span style={{display:'block',width:'24px',height:'1px',background:'var(--accent)'}} />Insights &amp; Ideas
            </div>
            <h1>Expert <em>Perspectives</em></h1>
            <p className="page-hero-sub">ServiceNow, AI automation, ITSM, digital transformation, and the future of enterprise — straight from the field.</p>
          </div>
          <div className="hero-stats">
            <div className="hero-stat"><div className="hero-stat-num">49<span>+</span></div><div className="hero-stat-label">Pages</div></div>
            <div className="hero-stat"><div className="hero-stat-num" style={{color:'var(--accent)'}}>480<span>+</span></div><div className="hero-stat-label">Articles</div></div>
            <div className="hero-stat"><div className="hero-stat-num">20<span>+</span></div><div className="hero-stat-label">Categories</div></div>
          </div>
        </div>
      </header>

      {/* FILTER BAR */}
      <div className="filter-bar" role="navigation" aria-label="Category filters">
        <div className="filter-inner">
          {FILTERS.map(f => (
            <button
              key={f.key}
              className={`filter-pill${active === f.key ? ' active' : ''}`}
              onClick={() => setActive(f.key)}
            >
              {f.label}
            </button>
          ))}
        </div>
      </div>

      {/* BLOG LAYOUT */}
      <main id="main-content">
        <div
          className="blog-layout"
          style={isSidebar ? {} : { gridTemplateColumns: '1fr' }}
        >
          {/* POSTS COLUMN */}
          <section aria-label="Blog posts">

            {/* FEATURED */}
            <Link to="/article" className="featured-post fade-in" aria-label="Featured: Claude UI/UX Market Signals">
              <div className="fp-img">
                <div className="fp-img-glow" />
                <span>featured image</span>
              </div>
              <div className="fp-body">
                <div className="fp-eyebrow">
                  <span className="fp-badge">Featured</span>
                  <span className="fp-cat">ServiceNow · AI Design · Market Analysis</span>
                </div>
                <h2 className="fp-title">Claude UI/UX Market Signals</h2>
                <p className="fp-excerpt">Claude Design triggered the biggest AI and UI/UX market conversation of 2026. Explore how Claude is transforming the design cycle, why ServiceNow and Cognizant stand to benefit, and why the future belongs to human-AI collaboration.</p>
                <div className="fp-meta">
                  <time dateTime="2026-04-20">Apr 20, 2026</time>
                  <div className="fp-meta-dot" />
                  <span>8 min read</span>
                </div>
                <div className="fp-read">
                  Read article{' '}
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
              </div>
            </Link>

            {/* POSTS GRID */}
            <div className="posts-section">
              <h2>Recent Posts</h2>
              <div className="posts-grid">
                {visible.map(p => (
                  <a key={p.title} href={p.href} target="_blank" rel="noopener" className={`post-card fade-in${p.delay}`}>
                    <div className="post-img">
                      <div className="post-img-accent" style={{background:'linear-gradient(135deg,oklch(72% 0.155 195/0.1),transparent)'}} />
                      <span>image</span>
                    </div>
                    <div className="post-body">
                      <div className="post-cat">{p.cat}</div>
                      <h3 className="post-title">{p.title}</h3>
                      <p className="post-excerpt">{p.excerpt}</p>
                      <div className="post-meta">
                        <time>{p.date}</time>
                        <div className="post-meta-dot" />
                        <span>{p.read}</span>
                      </div>
                    </div>
                  </a>
                ))}
                {visible.length === 0 && (
                  <p style={{color:'var(--text2)',padding:'40px 0',gridColumn:'1/-1'}}>No posts in this category yet.</p>
                )}
              </div>
            </div>

            {/* PAGINATION */}
            <div className="pagination-wrap fade-in">
              <div className="pagination-label">Showing page 1 of 49 — <strong>480+</strong> articles total</div>
              <nav className="pagination" aria-label="Blog pagination">
                <span className="page-btn active">1</span>
                {[2,3,4,5].map(n => (
                  <a key={n} className="page-btn" href={`https://www.dawncsimmons.com/blog/page/${n}/`} target="_blank" rel="noopener">{n}</a>
                ))}
                <span className="page-btn dots">…</span>
                <a className="page-btn" href="https://www.dawncsimmons.com/blog/page/49/" target="_blank" rel="noopener">49</a>
                <a className="page-btn wide" href="https://www.dawncsimmons.com/blog/page/2/" target="_blank" rel="noopener">Next →</a>
              </nav>
            </div>

          </section>

          {/* SIDEBAR */}
          {isSidebar && (
            <aside className="sidebar" aria-label="Blog sidebar">

              <div className="sidebar-card">
                <div className="sidebar-title">About</div>
                <div className="author-wrap">
                  <div className="author-avatar">
                    <img src="uploads/dawn_c_simmons.PNG" alt="Dawn Christine Simmons" />
                  </div>
                  <div><div className="author-name">Dawn C. Simmons</div><div className="author-role">ServiceNow · AI Expert</div></div>
                </div>
                <p className="author-bio">20+ years delivering digital transformation and ServiceNow solutions across Fortune 500 enterprises. Chicago, IL.</p>
                <Link to="/" style={{fontSize:'13px',color:'var(--accent)',textDecoration:'none'}}>Get in touch →</Link>
              </div>

              <div className="sidebar-card">
                <div className="sidebar-title">Search</div>
                <div className="search-box">
                  <input
                    type="search"
                    className="search-input"
                    placeholder="Search articles…"
                    aria-label="Search blog"
                    value={search}
                    onChange={e => setSearch(e.target.value)}
                    onKeyDown={e => { if (e.key === 'Enter') window.open(`https://www.dawncsimmons.com/blog/?s=${encodeURIComponent(search)}`, '_blank'); }}
                  />
                  <button
                    className="search-btn"
                    aria-label="Search"
                    onClick={() => window.open(`https://www.dawncsimmons.com/blog/?s=${encodeURIComponent(search)}`, '_blank')}
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                  </button>
                </div>
              </div>

              <div className="sidebar-card">
                <div className="sidebar-title">Top Categories</div>
                <div className="cat-list">
                  {[
                    { key:'ai',           label:'AI & Generative', count:30  },
                    { key:'servicenow',   label:'ServiceNow',       count:28  },
                    { key:'automatepro',  label:'AutomatePro',      count:142 },
                    { key:'cybersecurity',label:'Cyber Security',   count:8   },
                    { href:'https://www.dawncsimmons.com/category/healthcare/',        label:'Healthcare',           count:49  },
                    { href:'https://www.dawncsimmons.com/category/success-motivation/',label:'Success & Motivation', count:53  },
                  ].map(c => (
                    c.href
                      ? <a key={c.label} href={c.href} target="_blank" rel="noopener" className="cat-item">{c.label} <span className="cat-count">{c.count}</span></a>
                      : <a key={c.label} href="#" className="cat-item" onClick={e=>{e.preventDefault();setActive(c.key)}}>{c.label} <span className="cat-count">{c.count}</span></a>
                  ))}
                </div>
              </div>

              <div className="sidebar-card">
                <div className="sidebar-title">Popular Tags</div>
                <div className="tags-wrap">
                  {['ServiceNow','AI Automation','ITSM','Claude Design','Now Assist','CMDB','GRC','Chicago','Women in Tech','AutomatePro','ITIL v4','Digital Transform.'].map(t => (
                    <a key={t} href="#" className="tag" onClick={e => e.preventDefault()}>{t}</a>
                  ))}
                </div>
              </div>

            </aside>
          )}
        </div>
      </main>

      <Footer />
      <EditorPanel page="blog" />
    </>
  );
}
