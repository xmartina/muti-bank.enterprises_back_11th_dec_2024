<?php
$pageName = "Cost of Transfer";
include_once("layouts/header.php");
include("./userPinfunction.php");


?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2 mt-5">
                <div class="card component-card">
                    <div class="card-body">
                        <?php
                        if($_SESSION['wire-transfer']){
                            ?>
                            <div class="user-profile">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center">COT CODE VERIFICATION</h3>

                                    </div>
                                </div>
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                           

                                            <p class="text-center text-info text-uppercase">
                                                <?php
                                                
                                                $message = "Hello, " .$row['firstname'] . " " . $row['lastname'] . ",Please enter your COT code to complete your fund transfer. To obtain a COT code, contact our support team at <a href='mailto:support@multi-bank.enterprises'>support@multi-bank.enterprises</a> to make the payment. <p>Thank you.</p>";
                                                ?>
                                                <div class="text-center text-info text-uppercase">
                                                    <p><?= $message ?></p>
                                                </div>
                                            </p>


                                        </div>
                                    </div>
                                    <div class="row mb-4 mt-4">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <input type="number" class="form-control" name="cot_code" placeholder="input code" aria-label="notification" aria-describedby="basic-addon1" required>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <input type="number" value="<?= $temp_trans['amount'] ?>" name="amount" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['bank_name'] ?>" name="bank_name" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_name_id']?>" name="acct_name" hidden id="">
                                                    <input type="number" value="<?= $temp_trans['acct_number'] ?>" name="acct_number" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_type'] ?>" name="acct_type" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_country'] ?>" name="acct_country" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_swift']?>" name="acct_swift" hidden id="">
                                                    <input type="number" value="<?= $temp_trans['acct_routing'] ?>" name="acct_routing" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_remarks'] ?>" name="acct_remarks" hidden id="">
                                                    <input type="number" value="<?= $temp_trans['acct_id'] ?>" name="account_id" hidden>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group ">
                                                <button class="btn btn-primary col-12" name="cot_submit">Continue Transfer </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php
                        }elseif($_SESSION['dom-transfer']){
                            ?>
                            <div class="user-profile">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Verify Transaction Pin</h3>

                                    </div>
                                </div>
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-center text-info">HELLO, <?= ucwords($fullName)?> KINDLY VALIDATE THE 6 DIGIT OTP SENT TO YOUR <?= $row['acct_phone']?> OR <?= $row['acct_email']?>


                                            </p>

                                        </div>
                                    </div>
                                    <div class="row mb-4 mt-4">
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <div class="input-group ">
                                                    <input type="number" class="form-control" name="acct_cot" placeholder="input code" aria-label="notification" aria-describedby="basic-addon1" required>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <input type="number" value="<?= $temp_trans['amount'] ?>" name="amount" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['bank_name'] ?>" name="bank_name" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_name_id']?>" name="acct_name" hidden id="">
                                                    <input type="number" value="<?= $temp_trans['acct_number'] ?>" name="acct_number" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_type'] ?>" name="acct_type" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['trans_type'] ?>" name="trans_type" hidden id="">
                                                    <input type="text" value="<?= $temp_trans['acct_remarks'] ?>" name="acct_remarks" hidden id="">
                                                    <input type="number" value="<?= $temp_trans['acct_id'] ?>" name="account_id" hidden>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group ">
                                                <button class="btn btn-primary col-12" name="cot_submit">Submit</button>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                </div>
            </div>

<?php
include_once("layouts/footer.php");
?>
