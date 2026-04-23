import { useState, useEffect, useRef } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';

export default function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);
  const navRef = useRef(null);
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 60);
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    const onClick = (e) => {
      if (navRef.current && !navRef.current.contains(e.target)) setOpen(false);
    };
    document.addEventListener('click', onClick);
    return () => document.removeEventListener('click', onClick);
  }, []);

  useEffect(() => { setOpen(false); }, [location.pathname]);

  const goToSection = (e, id) => {
    e.preventDefault();
    setOpen(false);
    const doScroll = () => {
      const el = document.getElementById(id);
      if (el) window.scrollTo({ top: el.offsetTop - 90, behavior: 'smooth' });
    };
    if (location.pathname !== '/') {
      navigate('/');
      setTimeout(doScroll, 150);
    } else {
      doScroll();
    }
  };

  const isHome   = location.pathname === '/';
  const isBlog   = location.pathname === '/blog';
  const isArt    = location.pathname === '/article';

  return (
    <>
      <nav id="navbar" className={scrolled ? 'scrolled' : ''} ref={navRef} role="navigation" aria-label="Main navigation">
        <Link to="/" className="nav-logo">Dawn<span>.</span></Link>
        <ul className="nav-links">
          <li><Link to="/"        className={isHome ? 'active' : ''}>Home</Link></li>
          <li><a href="#services" onClick={(e) => goToSection(e, 'services')}>Services</a></li>
          <li><a href="#ai"       onClick={(e) => goToSection(e, 'ai')}>AI Solutions</a></li>
          <li><a href="#about"    onClick={(e) => goToSection(e, 'about')}>About</a></li>
          <li><Link to="/blog"    className={isBlog || isArt ? 'active' : ''}>Blog</Link></li>
          <li><a href="#contact"  onClick={(e) => goToSection(e, 'contact')}>Contact</a></li>
        </ul>
        <a href="#contact" className="nav-cta" onClick={(e) => goToSection(e, 'contact')}>Let's Talk</a>
        <button
          className={`nav-hamburger${open ? ' open' : ''}`}
          id="navHamburger"
          aria-label="Toggle navigation"
          aria-expanded={open}
          onClick={() => setOpen(p => !p)}
        >
          <span /><span /><span />
        </button>
      </nav>
      <div className={`nav-mobile-drawer${open ? ' open' : ''}`} id="mobileDrawer" aria-hidden={!open}>
        <ul>
          <li><Link to="/"       onClick={() => setOpen(false)}>Home</Link></li>
          <li><a href="#services" onClick={(e) => goToSection(e, 'services')}>Services</a></li>
          <li><a href="#ai"       onClick={(e) => goToSection(e, 'ai')}>AI Solutions</a></li>
          <li><a href="#about"    onClick={(e) => goToSection(e, 'about')}>About</a></li>
          <li><Link to="/blog"    onClick={() => setOpen(false)}>Blog</Link></li>
          <li><a href="#contact"  onClick={(e) => goToSection(e, 'contact')}>Contact</a></li>
          <li><a href="#contact"  className="btn-primary" style={{display:'inline-block',marginTop:'8px'}} onClick={(e) => goToSection(e, 'contact')}>Let's Talk</a></li>
        </ul>
      </div>
    </>
  );
}
