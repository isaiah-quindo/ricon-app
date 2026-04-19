import './bootstrap';

import Alpine from 'alpinejs';
import { HSStaticMethods } from 'preline';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => HSStaticMethods.autoInit());
