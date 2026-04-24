import { createContext, useContext, useState, useEffect } from 'react';

const ThemeContext = createContext();

const ACCENT_MAP = {
  teal:   ['oklch(72% 0.155 195)', 'oklch(72% 0.155 145)'],
  violet: ['oklch(68% 0.18 290)',  'oklch(68% 0.18 260)'],
  gold:   ['oklch(78% 0.15 85)',   'oklch(78% 0.15 60)'],
  coral:  ['oklch(68% 0.18 30)',   'oklch(68% 0.18 10)'],
};

const BG_MAP = {
  dark:     ['oklch(11% 0.01 260)',  'oklch(14% 0.012 260)', 'oklch(17% 0.012 260)'],
  midnight: ['oklch(8% 0.025 265)',  'oklch(11% 0.03 265)',  'oklch(14% 0.03 265)'],
  warm:     ['oklch(10% 0.015 40)',  'oklch(13% 0.018 40)',  'oklch(16% 0.018 40)'],
};

const FONT_MAP = {
  playfair:  ["'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif"],
  geo:       ["'DM Sans', Helvetica, sans-serif",   "'DM Sans', Helvetica, sans-serif"],
  editorial: ["'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif"],
};

export function ThemeProvider({ children }) {
  const [accent, setAccent] = useState('teal');
  const [bg, setBg] = useState('dark');
  const [font, setFont] = useState('playfair');
  const [articleFontSize, setArticleFontSize] = useState(16);
  const [articleWidth, setArticleWidth] = useState('1200px');
  const [blogLayout, setBlogLayout] = useState('sidebar');

  useEffect(() => {
    const r = document.documentElement;
    r.style.setProperty('--accent',  ACCENT_MAP[accent][0]);
    r.style.setProperty('--accent2', ACCENT_MAP[accent][1]);
  }, [accent]);

  useEffect(() => {
    const r = document.documentElement;
    r.style.setProperty('--bg',  BG_MAP[bg][0]);
    r.style.setProperty('--bg2', BG_MAP[bg][1]);
    r.style.setProperty('--bg3', BG_MAP[bg][2]);
  }, [bg]);

  useEffect(() => {
    const r = document.documentElement;
    r.style.setProperty('--ff-display', FONT_MAP[font][0]);
    r.style.setProperty('--ff-body',    FONT_MAP[font][1]);
  }, [font]);

  return (
    <ThemeContext.Provider value={{
      accent, setAccent,
      bg, setBg,
      font, setFont,
      articleFontSize, setArticleFontSize,
      articleWidth, setArticleWidth,
      blogLayout, setBlogLayout,
    }}>
      {children}
    </ThemeContext.Provider>
  );
}

export const useTheme = () => useContext(ThemeContext);
