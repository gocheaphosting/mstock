<?if(!defined("site_root")){exit();}?>
<?php
if($site_moneybookers_account!="") {
    if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
    {
        if($site_moneybookers_ipn==true)
        {
        	$hash=strtoupper(md5($_POST['merchant_id'] . $_POST["transaction_id"] . strtoupper(md5($site_moneybookers_password)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
        
        	if($hash!=$_POST['md5sig'])
        	{
        		exit();
        	}
        
        
            if ($_POST["status"]=="2")
            {

                $transaction_id=transaction_add("moneybookers", $_POST["mb_transaction_id"], $_GET["product_type"], $_POST['order_id']);

                if($_GET["product_type"]=="credits")
                {
                    credits_approve($_POST['order_id'], $transaction_id);
                    send_notification('credits_to_user',$_POST['order_id']);
                    send_notification('credits_to_admin',$_POST['order_id']);
                }

                if($_GET["product_type"]=="subscription")
                {
                    subscription_approve($_POST['order_id']);
                    send_notification('subscription_to_user',$_POST['order_id']);
                    send_notification('subscription_to_admin',$_POST['order_id']);
                }

                if($_GET["product_type"]=="order")
                {
                    order_approve($_POST['order_id']);
                    commission_add($_POST['order_id']);
                    coupons_add(order_user($_POST['order_id']));
                    send_notification('neworder_to_user',$_POST['order_id']);
					send_notification('neworder_to_admin',$_POST['order_id']);
                }
            }
        }
    }
    else
    {
    ?>
    <form method="post" action="<?=moneybookers_url?>"  name="process" id="process">

        <input type="hidden" name="pay_to_email" value="<?=$site_moneybookers_account?>" />
        <input type="hidden" name="return_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>" />
        <input type="hidden" name="cancel_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>" />
        <input type="hidden" name="status_url" value="<?=surl.site_root."/members/payments_process.php?mode=notification&product_type=".$product_type?>&processor=moneybookers" />
        <input type="hidden" name="language" value="EN" />
        <input type="hidden" name="amount" value="<?=$product_total?>" />
        <input type="hidden" name="currency" value="<?=$currency_code1?>" />
        <input type="hidden" name="detail1_description" value="<?=$product_name?>" />
        <input type="hidden" name="detail1_text" value="<?=$product_id?>" />
        <input type="hidden" name="transaction_id" value="<?=$product_id?>" />
        <input type="hidden" name="merchant_fields" value="order_id" />
        <input type="hidden" name="order_id" value="<?=$product_id?>" />

    </form>

    <?php
    }
}
?>