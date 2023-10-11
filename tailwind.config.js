/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        danger: {
          main: '#ED1C24',
          surface: '#FBD2D3',
          border: '#F9B3B6',
          hover: '#C5171E',
          pressed: '#760E12',
          focus: 'rgba(237, 29, 35, 0.2)'
        },
        warning: {
          main: '#FFCB05',
          surface: '#FFF4CD',
          border: '#FFEDAB',
          hover: '#D4A904',
          pressed: '#806502',
          focus: 'rgba(255, 205, 5, 0.2)'
        },
        primary: {
          main: '#EC008C',
          surface: '#FBCCE8',
          border: '#F9AAD9',
          hover: '#C40074',
          pressed: '#760046',
          focus: 'rgba(235, 0, 141, 0.2)'
        },
        success: {
          main: '#32BCAD',
          surface: '#D6F2EF',
          border: '#BAE8E3',
          hover: '#2A9D90',
          pressed: '#195E56',
          focus: 'rgba(50, 189, 173, 0.2)'
        },
        neutral: {
          10: '#D9D9D9',
          20: 'rgba(0, 0, 0, 0.04)',
          30: 'rgba(0, 0, 0, 0.07)',
          40: 'rgba(0, 0, 0, 0.12)',
          50: 'rgba(0, 0, 0, 0.24)',
          60: 'rgba(0, 0, 0, 0.38)',
          70: 'rgba(0, 0, 0, 0.54)',
          80: 'rgba(0, 0, 0, 0.62)',
          90: 'rgba(0, 0, 0, 0.75)',
          100: 'rgba(0, 0, 0, 0.96)',
        },
        red: {
          90: '#ED1C24',
        },
        yellow: {
          90: '#FFCB05',
        },
        purple: {
          90: '#C6168D',
        },
        turqoise: {
          90: '#32BCAD',
        },
        black: {
          90: '#4C4D4F',
        },
        grey: {
          90: '#EEEEEE',
        },
      },
      height: {
        17: 68,
      },
      fontFamily: {
        "indosat": ['Indosat Regular', ...defaultTheme.fontFamily.sans],
        "indosat-medium": ['Indosat Medium Medium', ...defaultTheme.fontFamily.sans],
        "indosat-bold": ['Indosat Bold Bold', ...defaultTheme.fontFamily.sans],
        qualy: ['qualybold', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  prefix: 'tw-',
}