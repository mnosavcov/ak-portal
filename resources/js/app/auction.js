import Alpine from "alpinejs";

Alpine.data('auction', (id) => ({
    interval: null,
    auctionMaxBidId: null,
    projectId: null,
    priceBox: {
        highest: false,
        bidExists: false,
        auction: false,
    },
    actualValues: {
        price_text_auction: '-',
        actual_min_bid_amount_text: null,
        actual_min_bid_amount_text2: null,
        end_date_text_normal: null,
        minPrice: null,
    },
    startCheckNewAuction() {
        this.interval = setInterval(() => this.checkNewAuction(), 10000);
    },
    checkNewAuction() {
        fetch('projekty/auction/check/max-bid-id/' + this.projectId)
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    if (this.auctionMaxBidId !== data.maxId) {
                        this.auctionMaxBidId = data.maxId;
                        this.readActualData();
                    }

                    if(data.project_status !== 'publicated') {
                        clearInterval(this.interval);
                    }
                    return;
                }

                alert('Chyba kontroly aukce, proveďte prosím ruční obnovu stránky.');
            })
            .catch(error => {
                alert('Chyba kontroly aukce, proveďte prosím ruční obnovu stránky.');
            });
    },
    readActualData() {
        fetch('projekty/auction/read-actual-data/' + this.projectId)
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    this.actualValues.price_text_auction = data.price_text_auction;
                    this.actualValues.actual_min_bid_amount_text = data.actual_min_bid_amount_text;
                    this.actualValues.actual_min_bid_amount_text2 = data.actual_min_bid_amount_text;
                    this.actualValues.end_date_text_normal = data.end_date_text_normal;
                    this.actualValues.minPrice = data.actual_min_bid_amount;
                    document.getElementById('price-box-bid-list').innerHTML = data.bid_list;
                    Alpine.store('app').targetDate = new Date(data.use_countdown_date_text_long).getTime();
                    this.priceBox.bidExists = data.bidExists;
                    this.priceBox.highest = data.highest;
                    return;
                }

                alert('Chyba kontroly aukce, proveďte prosím ruční obnovu stránky.');
            })
            .catch(error => {
                alert('Chyba kontroly aukce, proveďte prosím ruční obnovu stránky.');
            });
    }
}));
