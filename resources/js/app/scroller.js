import Alpine from "alpinejs";

Alpine.data('scroller', (
    item_size = 210,
    items_wrap = 'items_wrap',
) => ({
    showArrows: false,
    init() {
        const scrollerButton = () => this.scrollerButton(this, items_wrap);

        window.addEventListener('resize', scrollerButton);
        window.addEventListener('load', scrollerButton);
    },

    scrollToNextPage() {
        this.$refs[items_wrap].scrollBy({
            left: item_size,
            behavior: 'smooth'
        });
    },

    scrollToPrevPage() {
        this.$refs[items_wrap].scrollBy({
            left: -1 * item_size,
            behavior: 'smooth'
        });
    },

    scrollerButton(that, items_wrap) {
        if (that.$refs[items_wrap].scrollWidth > that.$refs[items_wrap].offsetWidth) {
            this.showArrows = true;
        } else {
            this.showArrows = false;
        }
    },
}));
