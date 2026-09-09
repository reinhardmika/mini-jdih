import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */

export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        ink: {
          900: '#0F2A24',
          700: '#1D4A3F',
          500: '#3D6B5D',
          300: '#5e9585',
        },
        paper: {
          DEFAULT: '#F2F4F3',
          alt: '#eaefed',
        },
        seal: '#A6303A',
        brass: '#d3aa51',
        teal: {
          DEFAULT: '#146B7A',
          700: '#0E5560',
        },
      },
      fontFamily: {
        display: ['Fraunces', 'serif'],
        sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
        mono: ['IBM Plex Mono', ...defaultTheme.fontFamily.mono],
        google: ['Google Sans Flex', ...defaultTheme.fontFamily.sans],
      },
      keyframes: {
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
      },
      animation: {
        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
      },
    },
  },
  plugins: [forms],
};