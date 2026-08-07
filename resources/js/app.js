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
// Exposed so views that inject markup after load (e.g. the group registration
// form adding a participant card) can re-init Preline components.
window.HSStaticMethods = HSStaticMethods;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
