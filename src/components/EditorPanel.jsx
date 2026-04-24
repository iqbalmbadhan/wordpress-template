import { useState } from 'react';
import { useTheme } from '../context/ThemeContext';

function TweakGroup({ label, children }) {
  return (
    <div className="tweak-group">
      <div className="tweak-label">{label}</div>
      <div className="tweak-row">{children}</div>
    </div>
  );
}

function TBtn({ active, onClick, children }) {
  return (
    <button className={`tweak-btn${active ? ' active' : ''}`} onClick={onClick}>
      {children}
    </button>
  );
}

export default function EditorPanel({ page }) {
  const [visible, setVisible] = useState(false);
  const {
    accent, setAccent,
    bg, setBg,
    font, setFont,
    articleFontSize, setArticleFontSize,
    articleWidth, setArticleWidth,
    blogLayout, setBlogLayout,
  } = useTheme();

  return (
    <>
      <button
        onClick={() => setVisible(v => !v)}
        style={{
          position: 'fixed', bottom: '24px', right: '24px', zIndex: 10000,
          width: '44px', height: '44px', borderRadius: '50%',
          background: 'var(--accent)', border: 'none', cursor: 'pointer',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          boxShadow: '0 8px 24px oklch(0% 0 0 / 0.4)',
          fontSize: '18px', transition: 'all .2s',
        }}
        aria-label="Toggle editor panel"
        title="Open Elementor-style editor"
      >
        {visible ? '×' : '⚙'}
      </button>

      {visible && (
        <div id="tweaks-panel" style={{ display: 'block', bottom: '80px' }}>
          <div className="tweaks-title">⚙ Style Controls</div>

          <TweakGroup label="Color Accent">
            <TBtn active={accent === 'teal'}   onClick={() => setAccent('teal')}>Teal</TBtn>
            <TBtn active={accent === 'violet'} onClick={() => setAccent('violet')}>Violet</TBtn>
            <TBtn active={accent === 'gold'}   onClick={() => setAccent('gold')}>Gold</TBtn>
            <TBtn active={accent === 'coral'}  onClick={() => setAccent('coral')}>Coral</TBtn>
          </TweakGroup>

          <div className="tweak-divider" />

          <TweakGroup label="Background">
            <TBtn active={bg === 'dark'}     onClick={() => setBg('dark')}>Dark</TBtn>
            <TBtn active={bg === 'midnight'} onClick={() => setBg('midnight')}>Midnight</TBtn>
            <TBtn active={bg === 'warm'}     onClick={() => setBg('warm')}>Warm Dark</TBtn>
          </TweakGroup>

          <div className="tweak-divider" />

          <TweakGroup label="Type Style">
            <TBtn active={font === 'playfair'}  onClick={() => setFont('playfair')}>Elegant</TBtn>
            <TBtn active={font === 'geo'}       onClick={() => setFont('geo')}>Geometric</TBtn>
            <TBtn active={font === 'editorial'} onClick={() => setFont('editorial')}>Editorial</TBtn>
          </TweakGroup>

          {page === 'blog' && (
            <>
              <div className="tweak-divider" />
              <TweakGroup label="Layout">
                <TBtn active={blogLayout === 'sidebar'} onClick={() => setBlogLayout('sidebar')}>Sidebar</TBtn>
                <TBtn active={blogLayout === 'full'}    onClick={() => setBlogLayout('full')}>Full Width</TBtn>
              </TweakGroup>
            </>
          )}

          {page === 'article' && (
            <>
              <div className="tweak-divider" />
              <TweakGroup label="Font Size">
                <TBtn active={articleFontSize === 14} onClick={() => setArticleFontSize(14)}>Small</TBtn>
                <TBtn active={articleFontSize === 16} onClick={() => setArticleFontSize(16)}>Normal</TBtn>
                <TBtn active={articleFontSize === 18} onClick={() => setArticleFontSize(18)}>Large</TBtn>
              </TweakGroup>
              <div className="tweak-divider" />
              <TweakGroup label="Layout Width">
                <TBtn active={articleWidth === '760px'}  onClick={() => setArticleWidth('760px')}>Narrow</TBtn>
                <TBtn active={articleWidth === '1200px'} onClick={() => setArticleWidth('1200px')}>Normal</TBtn>
                <TBtn active={articleWidth === '1400px'} onClick={() => setArticleWidth('1400px')}>Wide</TBtn>
              </TweakGroup>
            </>
          )}
        </div>
      )}
    </>
  );
}
