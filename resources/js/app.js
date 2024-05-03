import './bootstrap';

import.meta.glob(["../images/**"]);

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine;

Alpine.plugin(collapse)

import './app/faq.js';
import './app/register.js';

Alpine.start();
