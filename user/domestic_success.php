<?php
$pageName = "Success";
include_once("layouts/header.php");
include("./userPinfunction.php");
?>
<style>
    /* General Styling */

    .card {
        background-color: #ffffff !important;
        border: 1px solid #333333 !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3) !important;
    }

    .card-header {
        background-color: #333333;
        border-bottom: 1px solid #333333;
        text-align: center;
        padding: 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card-header h2 {
        margin: 0;
        font-size: 24px;
        color: #ffffff;
    }

    .alert-text-dec {
        background-color: #fff;
        border: 1.45px solid #333333;
        color: #333333;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        text-align: center;
        text-transform: uppercase;
    }

    .progress {
        background-color: #333333;
        border-radius: 10px;
        overflow: hidden;
        height: 25px;
        margin-bottom: 20px;
    }

    .progress-bar {
        background-color: #333333;
        color: #ffffff;
        font-weight: bold;
        line-height: 25px;
        width: 100%;
    }

    table {
        background-color: #fff !important;
        color: #333333 !important;
    }

    table th {
        background-color: #fff !important;
        color: #333333 !important;
        border-bottom: 2px solid #333333 !important;
    }

    table td, table th {
        padding: 15px;
        vertical-align: middle;
        border-left: 1.45px solid #333333 !important;
    }

    .btn-custom {
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
    }

    .btn-home {
        background-color: #ffffff;
        color: #333333;
        border: none;
    }

    .btn-home:hover {
        background-color: #e0e0e0;
        color: #333333;
    }

    .btn-print {
        background-color: transparent;
        color: #333333;
        border: 2px solid #333333;
    }

    .btn-print:hover {
        background-color: #333333;
        color: #ffffff;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
        .card-header h2 {
            font-size: 20px;
        }

        table th, table td {
            padding: 10px;
        }

        .btn-custom {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2 mt-5">
                <div class="card mx-auto" style="max-width: 800px;">
                    <div class="card-header">
                        <h2>Transfer Confirmation</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        if (strpos($_SERVER['REQUEST_URI'], 'success') !== false) {
                            // DOMESTIC TRANSACTION FETCH
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
                                <h4 class="text-success">Transfer Successful</h4>
                            </div>

                            <div class="alert-text-dec">
                                Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                                <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                                has been processed successfully.<br> Please note that the transaction will take up to 24 hours to complete.
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Amount</th>
                                            <td><?= $currency . number_format($amount, 2, '.', ',') ?></td>
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
                                            <td><?= $status ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        <?php
                        } else {
                            // No Transfer Found
                            ?>
                            <div class="text-center">
                                <h4 class="alert alert-danger">Sorry, we can't find what you're looking for!</h4>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="mt-4 d-flex justify-content-center flex-wrap">
                            <a href="./dashboard.php" class="btn btn-home btn-custom mx-2 mb-2">
                                <i class="fa fa-home me-2"></i> Go Home
                            </a>
                            <a href="javascript:window.print()" class="btn btn-print btn-custom mx-2 mb-2">
                                <i class="fa fa-print me-2"></i> Print Statement
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("layouts/footer.php");
?>
