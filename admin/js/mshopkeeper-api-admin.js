class MshopKeeperApiSetting {
    constructor() {
        this.initEvents();
    }

    initEvents() {
        $(".sync-button").click(this.syncProduct);
    }

    // Đồng bộ sản phẩm
    syncProduct() {
        $.ajax({
            url: misa_ajax_object .ajax_url,
            method: 'POST',
            data: { 
                action : "sync_product"
            },
            dataType: '',
            success: (res) => {
                console.log(res);
            },
        });
    }

}

$(document).ready(function () {
    const Mshop = new MshopKeeperApiSetting();
});