import './bootstrap';

import Alpine from 'alpinejs';
import { HSStaticMethods } from 'preline';
import Chart from 'chart.js/auto';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

window.Alpine = Alpine;
window.Chart = Chart;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
