import './bootstrap';

import Alpine from 'alpinejs';
import { HSStaticMethods } from 'preline';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
