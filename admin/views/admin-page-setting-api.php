<?php
    $MshopkeeperApiData = new MshopkeeperApiData();
    $MshopkeeperApiEndPoint = new MshopkeeperApiEndPoint();
?>
<div class="wrap">
    <h1>Thiết lập API</h1>
    <!-- Notice location -->
    <?php if(isset($_GET['message'])): ?>
        <?php if($_GET['message'] == "save-authenticate-success"): ?>
            <?php showSuccessNotice("Lưu dữ liệu thành công") ?>
        <?php endif; ?>
        <?php if($_GET['message'] == "save-authenticate-error"): ?>
            <?php showErrorNotice("Dữ liệu xác thực không hợp lệ") ?>
        <?php endif; ?>
        <?php if($_GET['message'] == "branch-false"): ?>
            <?php showErrorNotice("Dữ liệu chi nhánh không hợp lệ") ?>
        <?php endif; ?>
        <?php if($_GET['message'] == "save-branch-success"): ?>
            <?php showSuccessNotice("Lưu dữ liệu chi nhánh thành công") ?>
        <?php endif; ?>
        <?php if($_GET['message'] == "save-branch-false"): ?>
            <?php showErrorNotice("Lưu dữ liệu chi nhánh không thành công") ?>
        <?php endif; ?>
    <?php endif; ?>
    <!-- End Notice location -->
    <div class="container">
        <h2>Cài đặt xác thực</h2>
        <form action="<?php echo admin_url('admin.php') ?>" method="POST">
        <?php wp_nonce_field( 'form_save_infomation_misa', 'misa_nonce' ); ?>
        <input type="hidden" value="action_save_authenticator" name="action">
        <label class="label-field" for="">Tên kết nối:</label>
        <input name="name_connection" value="<?php echo $MshopkeeperApiData->getDomain() ?>" type="text">
        <br />
        <label class="label-field" for="">App ID</label>
        <input name="app_id" value="<?php echo $MshopkeeperApiData->getAppID() ?>" type="text">
        <br />
        <label class="label-field" for="">Mã bảo mật</label>
        <input name="secret_code" value="<?php echo $MshopkeeperApiData->getsecretCode() ?>" type="text">
        <br />
        <input type="submit" class="button button-primary" name="save_infomation" value="Lưu dữ liệu">
        </form>
    </div>

    <?php if($MshopkeeperApiEndPoint->getAllBranch()): ?>
    <div class="container">
        <h2>Chọn chi nhánh đồng bộ</h2>
        <form action="<?php echo admin_url('admin.php') ?>" method="POST">
        <?php wp_nonce_field( 'form_save_infomation_misa', 'misa_nonce' ); ?>
        <input type="hidden" value="action_save_branch" name="action">
        <label class="label-field" for="">Chọn chi nhánh:</label>
        <select name="misa_branch">
        <option value="null">Chọn chi nhánh</option>
            <?php foreach($MshopkeeperApiEndPoint->getAllBranch() as $branch): ?>
                <?php if($MshopkeeperApiData->getBranchCode() == $branch->Code): ?>
                <option value="<?php echo $branch->Code ?>" selected><?php echo $branch->Name ?></option>
                <?php continue; ?>
                <?php endif; ?>
                <option value="<?php echo $branch->Code ?>"><?php echo $branch->Name ?></option>
            <?php endforeach; ?>
        </select>
        <br />
        <input type="submit" class="button button-primary" name="save_branch" value="Lưu chi nhánh">
        </form>
    </div>
    <?php endif; ?>

    <div class="container">
        <h2>Đồng bộ sản phẩm</h2>
        <?php if(!$MshopkeeperApiEndPoint->getNumberProduct()):?>
            <?php  showWarningNotice("Chi nhánh không có sản phẩm hoặc không tồn tại"); ?>
        <?php endif; ?>
        <input type="submit" class="button button-primary sync-button" name="save_branch" value="Đồng bộ sản phẩm">
        <span class="sync-text-alert">Có <span class="number-product"><?php echo $MshopkeeperApiEndPoint->getNumberProduct(); ?></span> sản phẩm chưa đồng bộ</span>
        
    </div>


</div>