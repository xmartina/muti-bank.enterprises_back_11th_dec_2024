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
 <style>
        /* General Styling */
       
        .card {
            background-color: #1e1e1e;
            border: 1px solid #333333;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background-color: #000000;
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

        .alert {
            background-color: #333333;
            border: none;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #2e7d32;
            color: #ffffff;
        }

        .alert-danger {
            background-color: #c62828;
            color: #ffffff;
        }

        .progress {
            background-color: #333333;
            border-radius: 10px;
            overflow: hidden;
            height: 25px;
            margin-bottom: 20px;
        }

        .progress-bar {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
            line-height: 25px;
        }

        table {
            background-color: #1e1e1e;
            color: #ffffff;
        }

        table th {
            background-color: #000000;
            color: #ffffff;
            border-bottom: 2px solid #333333;
        }

        table td, table th {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-custom {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-home {
            background-color: #ffffff;
            color: #000000;
            border: none;
        }

        .btn-home:hover {
            background-color: #e0e0e0;
            color: #000000;
        }

        .btn-print {
            background-color: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
        }

        .btn-print:hover {
            background-color: #ffffff;
            color: #000000;
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

/*        Adjustment*/
        .card {
    background-color: #ffffff !important;
    border: 1px solid #333333 !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3) !important;
}

.table td, .table th {
    border-top: 1px solid #fff !important;
}

table th {
    background-color: #fff !important;
    color: #333333 !important;
    border-bottom: 2px solid #fff !important;
}

.btn-print {
    color: #333333 !important;
    border: 2px solid #333333 !important;
}

.outline-badge-secondary:focus, .outline-badge-secondary:hover {
    color: #333333 !important;
    background-color: #fff !important;
}

.outline-badge-secondary {
    border: 1px solid #fff !important;
}
tbody {
    border-color: #fff !important;
}
table {
    background-color: #fff !important;
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
                        <h4 class="text-success">Your transfer is being processed</h4>
                    </div>

                    <div class="alert alert-success text-center text-uppercase">
                        Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                        <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                        is being processed. <br> Please note that the transaction will take up to 24 hours to complete.
                    </div>

                    

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Amount</th>
                                    <td><?= $currency . number_format($amount, 2) ?></td>
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
                                    <td><?= $status ?></td>
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

                    <div class="alert alert-outline-dark text-dark text-center text-uppercase">
                        Dear <?= htmlspecialchars(ucwords($fullName), ENT_QUOTES, 'UTF-8') ?>, your transfer to 
                        <strong><?= htmlspecialchars($dom_transfer['acct_name'], ENT_QUOTES, 'UTF-8') ?></strong> 
                        has been processed successfully.
                    </div>

                
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Amount</th>
                                    <td><?= $currency . number_format($amount, 2) ?></td>
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

<?php
include_once("layouts/footer.php");
?>