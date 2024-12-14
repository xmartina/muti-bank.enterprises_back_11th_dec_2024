<?php
$pageName = "Success";
include_once("layouts/header.php");
include("./userPinfunction.php");

//TEMP TRANSACTION FETCH
$sql = "SELECT * FROM wire_transfer WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'acct_id'=>$user_id
]);
$wire_trans = $stmt->fetch(PDO::FETCH_ASSOC);




$status = wireStatus($wire_trans);





?>

    <div id="content" class="main-content">
    <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
    <div class="col-md-8 offset-md-2 mt-5">
        <div class="card shadow-sm">
    <div class="card-body">
        <?php
        if ($_SESSION['wire_transfer']) {
            // Wire Transfer Processing
            $amount = $wire_trans['amount'];
            $bank_name = $wire_trans['bank_name'];
            $acct_name = $wire_trans['acct_name'];
            $acct_number = $wire_trans['acct_number'];
            $acct_country = $wire_trans['acct_country'];
            $acct_swift = $wire_trans['acct_swift'];
            $acct_routing = $wire_trans['acct_routing'];
            $acct_type = $wire_trans['acct_type'];

            $APP_NAME = $pageTitle;
            $message = $sendMail->UserWireTransfer($currency, $amount, $fullName, $bank_name, $acct_name, $acct_number, $acct_country, $acct_swift, $acct_routing, $acct_type, $APP_NAME);

            // User and Admin Emails
            $subject = "Wire Transfer - $APP_NAME";
            $email_message->send_mail($email, $message, $subject);
            $email_message->send_mail(WEB_EMAIL, $message, $subject);
            ?>

            <div class="text-center mb-4">
                <h4 class="text-success">Your transfer is being processed</h4>
            </div>

            <div class="alert alert-info text-center text-uppercase">
                Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                is being processed. <br> Please note that the transaction will take up to 24 hours to complete.
            </div>

            <div class="progress mb-4" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    100%
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Amount</th>
                            <td><?= htmlspecialchars($currency . $wire_trans['amount'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Reference ID</th>
                            <td><?= htmlspecialchars($wire_trans['refrence_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Bank Name</th>
                            <td><?= htmlspecialchars($wire_trans['bank_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account Name</th>
                            <td><?= htmlspecialchars($wire_trans['acct_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account No</th>
                            <td><?= htmlspecialchars($wire_trans['acct_number'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php
        } elseif ($_SESSION['dom_transfer']) {
            // Domestic Transfer Processing
            $sql = "SELECT * FROM domestic_transfer WHERE acct_id = :acct_id ORDER BY dom_id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['acct_id' => $user_id]);
            $dom_transfer = $stmt->fetch(PDO::FETCH_ASSOC);
            $status = domestic($dom_transfer);

            $amount = $dom_transfer['amount'];
            $bank_name = $dom_transfer['bank_name'];
            $acct_name = $dom_transfer['acct_name'];
            $acct_number = $dom_transfer['acct_number'];
            $acct_type = $dom_transfer['acct_type'];

            $APP_NAME = $pageTitle;
            $message = $sendMail->UserDomTransfer($currency, $amount, $fullName, $bank_name, $acct_name, $acct_number, $acct_type, $APP_NAME);

            // User and Admin Emails
            $subject = "Domestic Transfer - $APP_NAME";
            $email_message->send_mail($email, $message, $subject);
            $email_message->send_mail(WEB_EMAIL, $message, $subject);
            ?>

            <div class="text-center mb-4">
                <h4 class="text-success">Transfer Successful</h4>
            </div>

            <div class="alert alert-info text-center text-uppercase">
                Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                is being processed.
            </div>

            <div class="progress mb-4" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    100%
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Amount</th>
                            <td><?= htmlspecialchars($currency . $dom_transfer['amount'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Reference ID</th>
                            <td><?= htmlspecialchars($dom_transfer['refrence_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Bank Name</th>
                            <td><?= htmlspecialchars($dom_transfer['bank_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account Name</th>
                            <td><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account No</th>
                            <td><?= htmlspecialchars($dom_transfer['acct_number'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Status</th>
                            <td><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php
        } else {
            // No Transfer Found
            ?>
            <div class="text-center">
                <h4 class="text-danger">Sorry, we can't find what you're looking for!</h4>
            </div>
        <?php
        }
        ?>

        <div class="mt-4 text-center">
            <a href="./dashboard.php" class="btn btn-primary mx-2">
                <i class="fa fa-home"></i> Go Home
            </a>
            <a href="javascript:window.print()" class="btn btn-success mx-2">
                <i class="fa fa-print"></i> Print Statement
            </a>
        </div>
    </div>
</div>

    </div>

<?php
include_once("layouts/footer.php");
?>