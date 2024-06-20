import './bootstrap';

import.meta.glob(["../images/**"]);

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import mask from '@alpinejs/mask'

window.Alpine = Alpine;

Alpine.plugin(collapse)
Alpine.plugin(mask)

import './app/faq.js';
import './app/register.js';
import './app/profile.js';
import './app/project-edit.js';
import './app/project-list.js';
import './app/admin-project-edit.js';
import './app/admin-category.js';
import './app/admin-user.js';
import './app/verify-user-account.js';

Alpine.start();

function tinymceInit()
{
    tinymce.init({
        selector: "div.tinyBox textarea",
        theme: "modern",
        // width: '100%',
        height: 250,
        menubar: false,
        /*link_list: [
            {title: 'My page 1', value: 'http://www.tinymce.com'},
            {title: 'My page 2', value: 'http://www.tecrail.com'}
        ],*/
        //relative_urls: false,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor",
            "colorpicker"
            //, "fullpage"
        ],
        //browser_spellcheck : true ,
        content_css: "/js/tinymce/style.css",
        // toolbar1: "bold italic underline | bullist numlist outdent indent | image | media | link unlink | forecolor backcolor | emoticons",
        toolbar1: "bold italic underline | bullist numlist outdent indent | image | media | link unlink | forecolor backcolor | emoticons",
        setup: function (editor) {
            editor.on('change', function (event) {
                let textarea = event.target.editorContainer.closest('div.tinyBox-wrap').getElementsByTagName('textarea')[0];
                textarea.value = editor.getContent();
                let inputEvent = new Event('input');
                textarea.dispatchEvent(inputEvent);
            });
        }
    })
}

function countdown(targetDate) {
    const target = new Date(targetDate).getTime();

    function updateCountdown() {
        let now = new Date();
        const distance = target - now;

        if (distance < 0) {
            clearInterval(interval);
            document.getElementById('projectEndDate').textContent = '0 d 0 h 0 m 0 s';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('projectEndDate').textContent = days + ' d ' + hours + ' h ' + minutes + ' m ' + seconds + ' s';
    }
    const interval = setInterval(updateCountdown, 1000);
}

function keepSession() {
    fetch('/keep-session')
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
}

window.tinymceInit = tinymceInit;
window.countdown = countdown;
window.keepSession = keepSession;
tinymceInit();
