<?php
// wire_pin.php
$pageName = "Wire Transfer Pin";
require_once("layouts/header.php");
include("./handlePinVerification.php"); // Updated function file


?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2 mt-5">
                <div class="card component-card">
                    <div class="card-body">
                        <?php
                        if(isset($_SESSION['wire-transfer'])){
                            ?>
                            <div class="user-profile">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Verify Wire Transfer Pin</h3>
                                    </div>
                                </div>
                                <form action="wire_pin.php" method="post">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-center text-info">
                                                <span class="text-muted">Hello,</span> <?= ucwords($fullName) ?>
                                                <span class="text-muted">kindly enter your One-Time Pin (OTP) code to complete this wire transfer successfully.</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-4 mt-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="hidden" name="type" value="wire_transfer">
                                                    <input type="number" class="form-control" name="pin" placeholder="PIN" required>
                                                </div>
                                            </div>
                                            <!-- Hidden Inputs -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php foreach($temp_trans as $key => $value): ?>
                                                        <?php if(!in_array($key, ['trans_otp', 'trans_type'])): ?>
                                                            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <input type="hidden" name="acct_no" value="<?= htmlspecialchars($row['acct_no']) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button class="btn btn-primary col-12" type="submit" name="submit_wire_pin">Submit</button>
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

            <!-- Modal remains the same -->
            <div class="modal fade" id="thankyouModal" tabindex="-1" role="dialog">
                <!-- Modal Content -->
            </div>
        </div>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
