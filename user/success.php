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
<style type="text/css">
    /* Custom Button Hover Effect */
.btn-secondary:hover {
    background-color: #5a5a5a;
    border-color: #5a5a5a;
}

/* Table Header Styling */
table th {
    background-color: #f8f9fa;
    color: #343a40;
}

/* Alert Styling */
.alert-secondary {
    background-color: #e2e3e5;
    color: #383d41;
}

</style>
    <div id="content" class="main-content">
    <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
    <div class="col-md-8 offset-md-2 mt-5">
    
    <div class="card shadow-sm">
    <div class="card-body">
        <?php
        if ($_SESSION['wire_transfer']) {
            // Wire Transfer Processing
            $amount = htmlspecialchars($wire_trans['amount'], ENT_QUOTES, 'UTF-8');
            $bank_name = htmlspecialchars($wire_trans['bank_name'], ENT_QUOTES, 'UTF-8');
            $acct_name = htmlspecialchars($wire_trans['acct_name'], ENT_QUOTES, 'UTF-8');
            $acct_number = htmlspecialchars($wire_trans['acct_number'], ENT_QUOTES, 'UTF-8');
            $acct_country = htmlspecialchars($wire_trans['acct_country'], ENT_QUOTES, 'UTF-8');
            $acct_swift = htmlspecialchars($wire_trans['acct_swift'], ENT_QUOTES, 'UTF-8');
            $acct_routing = htmlspecialchars($wire_trans['acct_routing'], ENT_QUOTES, 'UTF-8');
            $acct_type = htmlspecialchars($wire_trans['acct_type'], ENT_QUOTES, 'UTF-8');

            $APP_NAME = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
            $message = $sendMail->UserWireTransfer($currency, $amount, $fullName, $bank_name, $acct_name, $acct_number, $acct_country, $acct_swift, $acct_routing, $acct_type, $APP_NAME);
            
            // User and Admin Emails
            $subject = "Wire Transfer - $APP_NAME";
            $email_message->send_mail($email, $message, $subject);
            $email_message->send_mail(WEB_EMAIL, $message, $subject);
            ?>

            <div class="text-center mb-4">
                <h4 class="text-dark">Your transfer is being processed</h4>
            </div>

            <div class="alert alert-secondary text-center text-uppercase">
                Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                is being processed. <br> Please note that the transaction will take up to 24 hours to complete.
            </div>

            <div class="progress mb-4" style="height: 20px;">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    100%
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Amount</th>
                            <td><?= $currency . $amount ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Reference ID</th>
                            <td><?= htmlspecialchars($wire_trans['refrence_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Bank Name</th>
                            <td><?= $bank_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account Name</th>
                            <td><?= $acct_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account No</th>
                            <td><?= $acct_number ?></td>
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

            $amount = htmlspecialchars($dom_transfer['amount'], ENT_QUOTES, 'UTF-8');
            $bank_name = htmlspecialchars($dom_transfer['bank_name'], ENT_QUOTES, 'UTF-8');
            $acct_name = htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8');
            $acct_number = htmlspecialchars($dom_transfer['acct_number'], ENT_QUOTES, 'UTF-8');
            $acct_type = htmlspecialchars($dom_transfer['acct_type'], ENT_QUOTES, 'UTF-8');

            $APP_NAME = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
            $message = $sendMail->UserDomTransfer($currency, $amount, $fullName, $bank_name, $acct_name, $acct_number, $acct_type, $APP_NAME);
            
            // User and Admin Emails
            $subject = "Domestic Transfer - $APP_NAME";
            $email_message->send_mail($email, $message, $subject);
            $email_message->send_mail(WEB_EMAIL, $message, $subject);
            ?>

            <div class="text-center mb-4">
                <h4 class="text-dark">Transfer Successful</h4>
            </div>

            <div class="alert alert-secondary text-center text-uppercase">
                Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                is being processed.
            </div>

            <div class="progress mb-4" style="height: 20px;">
                <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                    100%
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Amount</th>
                            <td><?= $currency . $amount ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Reference ID</th>
                            <td><?= htmlspecialchars($dom_transfer['refrence_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Bank Name</th>
                            <td><?= $bank_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account Name</th>
                            <td><?= $acct_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Account No</th>
                            <td><?= $acct_number ?></td>
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
            <a href="./dashboard.php" class="btn btn-secondary mx-2">
                <i class="fa fa-home"></i> Go Home
            </a>
            <a href="javascript:window.print()" class="btn btn-secondary mx-2">
                <i class="fa fa-print"></i> Print Statement
            </a>
        </div>
    </div>
</div>

    </div>

<?php
include_once("layouts/footer.php");
?>