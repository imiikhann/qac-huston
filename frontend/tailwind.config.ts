import type { Config } from "tailwindcss"

const config: Config = {
  darkMode: 'class',
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        qac: {
          50: '#f7f7f8',
          100: '#eeeef0',
          200: '#d9d9de',
          300: '#b8b8c1',
          400: '#91919e',
          500: '#747483',
          600: '#5d5d69',
          700: '#4c4c56',
          800: '#414149',
          900: '#313639',
          950: '#1c1c1f',
        },
        amber: {
          50: '#fff7ed',
          100: '#ffedd5',
          200: '#fed7aa',
          300: '#fdba74',
          400: '#fb923c',
          500: '#FF9000',
          600: '#ea7a00',
          700: '#c56500',
          800: '#9a4e00',
          900: '#7c3a00',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
      },
      backgroundImage: {
        'glass-gradient': 'linear-gradient(135deg, rgba(255,255,255,0.7), rgba(255,255,255,0.2))',
        'glass-gradient-dark': 'linear-gradient(135deg, rgba(49,54,57,0.7), rgba(49,54,57,0.2))',
        'hero-gradient': 'linear-gradient(135deg, #f7f7f8 0%, #ffffff 50%, #fff7ed 100%)',
        'hero-gradient-dark': 'linear-gradient(135deg, #1c1c1f 0%, #313639 50%, #4c4c56 100%)',
        'amber-gradient': 'linear-gradient(135deg, #FF9000, #fb923c)',
        'amber-gradient-dark': 'linear-gradient(135deg, #ea7a00, #FF9000)',
      },
      boxShadow: {
        'glass': '0 8px 32px rgba(0, 0, 0, 0.06), inset 0 1px 0 rgba(255, 255, 255, 0.8)',
        'glass-dark': '0 8px 32px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.05)',
        'glass-hover': '0 16px 48px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.9)',
      },
    },
  },
  plugins: [],
}
export default config
