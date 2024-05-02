import './bootstrap';

import.meta.glob(["../images/**"]);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

import './app/faq.js';

Alpine.start();
