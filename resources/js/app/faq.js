// https://postsrc.com/posts/how-to-make-ajax-request-in-alpine-js

import Alpine from "alpinejs";

Alpine.data('faq', (id) => ({
    data: {},
    init() {
        console.log(this.data)
    }

}));
