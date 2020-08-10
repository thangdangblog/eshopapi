class MshopKeeperApiSetting {
    constructor() {
        this.initEvents();
    }

    initEvents() {
        $(".sync-button").click(this.syncProduct);
    }

    // Đồng bộ sản phẩm
    syncProduct = (event) => {
        //Disable nút
        this.disableButton();
        
        // Hiển thị text đồng bộ
        this.showTextSync();
        this.textSync = setInterval(this.showTextSync,900);

        $.ajax({
            url: misa_ajax_object .ajax_url,
            method: 'POST',
            data: { 
                action : "sync_product"
            },
            dataType: '',
            success: (res) => {
                console.log(res);
                if(res == "true") this.showTextSyncComplete();
                
            },
        });
    }

    showTextSync(){
        $(".sync-text-alert").html("Đang đồng bộ sản phẩm.");
        setTimeout(() => {
            $(".sync-text-alert").html("Đang đồng bộ sản phẩm..");
        },300 );
        setTimeout(() => {
            $(".sync-text-alert").html("Đang đồng bộ sản phẩm...");
        },600 );
    }

    disableButton(){
        $(".sync-button").attr("disabled","disabled");
    }

    enableButton(){
        $(".sync-button").attr("disabled",false);
    }

    showTextSyncComplete(){
        clearInterval(this.textSync);
        setTimeout(() => {
            this.enableButton();
            $(".sync-text-alert").html("Đồng bộ thành công.");
        },900)
    }


}

$(document).ready(function () {
    const Mshop = new MshopKeeperApiSetting();
});