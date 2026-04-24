import { Link, useLocation } from 'react-router-dom';

export default function Footer() {
  const { pathname } = useLocation();
  const isHome = pathname === '/';
  return (
    <footer role="contentinfo">
      <Link to="/" className="footer-logo">Dawn<span>.</span>C.Simmons</Link>
      <div className="footer-copy">© 2026 Dawn Christine Simmons — ServiceNow Consultant &amp; AI Transformation Expert</div>
      <nav className="footer-links" aria-label="Footer navigation">
        <Link to="/blog">Blog</Link>
        <a href="https://www.dawncsimmons.com/knowledge-base/" target="_blank" rel="noopener">Knowledge Base</a>
        <a href={isHome ? '#contact' : '/#contact'}>Contact</a>
      </nav>
    </footer>
  );
}
