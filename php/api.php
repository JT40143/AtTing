<?php
require_once 'db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Main switch to handle different actions
switch ($action) {
    case 'get_dashboard_stats':
        getDashboardStats($link);
        break;
    case 'get_monthly_operations':
        getMonthlyOperations($link, $_GET['year'] ?? date('Y'), $_GET['month'] ?? date('m'));
        break;
    case 'get_vendors':
        getVendors($link);
        break;
    case 'get_vendor_details':
        if (isset($_GET['id'])) {
            getVendorDetails($link, $_GET['id']);
        }
        break;
    case 'save_vendor':
        saveVendor($link, $input);
        break;
    case 'get_billing_entities':
        getBillingEntities($link);
        break;
    case 'add_billing_entity':
        addBillingEntity($link, $input);
        break;
    case 'delete_billing_entity':
        deleteBillingEntity($link, $input);
        break;
    case 'save_payment':
        savePayment($link, $input);
        break;
    case 'update_payment_status':
        updatePaymentStatus($link, $input);
        break;
    case 'batch_update_payments':
        batchUpdatePayments($link, $input);
        break;
    case 'search_payments':
        searchPayments($link, $_GET);
        break;
    case 'get_settings':
        getSettings($link);
        break;
    case 'save_settings':
        saveSettings($link, $input);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

// --- Function Implementations ---

function getVendors($link) {
    $sql = "SELECT id, name, cycle, next_payment_date, status FROM vendors WHERE status = 'active' ORDER BY name";
    $result = mysqli_query($link, $sql);
    $vendors = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $vendors]);
}

function getBillingEntities($link) {
    $sql = "SELECT id, name FROM billing_entities WHERE is_active = 1 ORDER BY name";
    $result = mysqli_query($link, $sql);
    $entities = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $entities]);
}

function getMonthlyOperations($link, $year, $month) {
    $start_date = "$year-$month-01";
    $end_date = date("Y-m-t", strtotime($start_date));

    $sql = "
        SELECT
            p.id, p.vendor_id, p.billing_entity_id, p.invoice_number, p.item_description, p.amount, p.status,
            p.date_invoice_received, p.date_notion_registered, p.date_form_created, p.date_cloud_saved, p.date_submitted_to_finance,
            v.name as vendor_name
        FROM payments p
        JOIN vendors v ON p.vendor_id = v.id
        WHERE p.payment_month BETWEEN ? AND ?
        ORDER BY v.name, p.id
    ";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Group payments by vendor
    $grouped = [];
    foreach ($payments as $payment) {
        $grouped[$payment['vendor_id']]['vendor_name'] = $payment['vendor_name'];
        $grouped[$payment['vendor_id']]['payments'][] = $payment;
    }

    echo json_encode(['status' => 'success', 'data' => array_values($grouped)]);
}

function getSettings($link) {
    $sql = "SELECT setting_key, setting_value FROM settings";
    $result = mysqli_query($link, $sql);
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    echo json_encode(['status' => 'success', 'data' => $settings]);
}

function saveSettings($link, $input) {
    if (isset($input['notion_url']) && isset($input['cloud_url'])) {
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('notion_url', ?), ('cloud_url', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $input['notion_url'], $input['cloud_url']);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Settings saved.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save settings.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid settings data.']);
    }
}

function addBillingEntity($link, $input) {
    if (!empty($input['name'])) {
        $sql = "INSERT INTO billing_entities (name) VALUES (?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $input['name']);
        if (mysqli_stmt_execute($stmt)) {
            $id = mysqli_insert_id($link);
            echo json_encode(['status' => 'success', 'message' => 'Billing entity added.', 'data' => ['id' => $id, 'name' => $input['name']]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add billing entity.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Billing entity name is required.']);
    }
}

function deleteBillingEntity($link, $input) {
    if (!empty($input['id'])) {
        // We use a soft delete by setting is_active to 0
        $sql = "UPDATE billing_entities SET is_active = 0 WHERE id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $input['id']);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Billing entity deleted.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete billing entity.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Billing entity ID is required.']);
    }
}

function getDashboardStats($link) {
    $year = date('Y');
    $month = date('m');
    $start_date = "$year-$month-01";
    $end_date = date("Y-m-t", strtotime($start_date));

    // 本月應付總額
    $sql_total = "SELECT SUM(amount) as total FROM payments WHERE payment_month BETWEEN ? AND ?";
    $stmt_total = mysqli_prepare($link, $sql_total);
    mysqli_stmt_bind_param($stmt_total, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_total);
    $result_total = mysqli_stmt_get_result($stmt_total);
    $total_amount = mysqli_fetch_assoc($result_total)['total'] ?? 0;

    // 發票狀態
    $sql_invoice = "SELECT status, COUNT(*) as count FROM payments WHERE payment_month BETWEEN ? AND ? GROUP BY status";
    $stmt_invoice = mysqli_prepare($link, $sql_invoice);
    mysqli_stmt_bind_param($stmt_invoice, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt_invoice);
    $result_invoice = mysqli_stmt_get_result($stmt_invoice);
    $invoice_stats = mysqli_fetch_all($result_invoice, MYSQLI_ASSOC);

    $stats = [
        'total_payable' => $total_amount,
        'invoice_received' => 0,
        'invoice_pending' => 0,
    ];

    foreach ($invoice_stats as $stat) {
        if ($stat['status'] == 'received') {
            $stats['invoice_received'] = $stat['count'];
        } else {
            $stats['invoice_pending'] += $stat['count'];
        }
    }

    // Add more stats as needed...

    echo json_encode(['status' => 'success', 'data' => $stats]);
}


function getVendorDetails($link, $id) {
    $sql_vendor = "SELECT * FROM vendors WHERE id = ?";
    $stmt_vendor = mysqli_prepare($link, $sql_vendor);
    mysqli_stmt_bind_param($stmt_vendor, "i", $id);
    mysqli_stmt_execute($stmt_vendor);
    $result_vendor = mysqli_stmt_get_result($stmt_vendor);
    $vendor = mysqli_fetch_assoc($result_vendor);

    if ($vendor) {
        $sql_templates = "SELECT * FROM payment_templates WHERE vendor_id = ?";
        $stmt_templates = mysqli_prepare($link, $sql_templates);
        mysqli_stmt_bind_param($stmt_templates, "i", $id);
        mysqli_stmt_execute($stmt_templates);
        $result_templates = mysqli_stmt_get_result($stmt_templates);
        $templates = mysqli_fetch_all($result_templates, MYSQLI_ASSOC);
        $vendor['templates'] = $templates;
        echo json_encode(['status' => 'success', 'data' => $vendor]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Vendor not found.']);
    }
}

function saveVendor($link, $data) {
    // Basic validation
    if (empty($data['name']) || empty($data['cycle']) || empty($data['next_payment_date'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required vendor fields.']);
        return;
    }

    $id = $data['id'] ?? null;

    if ($id) {
        // Update existing vendor
        $sql = "UPDATE vendors SET name=?, cycle=?, invoice_day=?, next_payment_date=?, contract_start_date=?, contract_end_date=? WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssisssi", $data['name'], $data['cycle'], $data['invoice_day'], $data['next_payment_date'], $data['contract_start_date'], $data['contract_end_date'], $id);
    } else {
        // Insert new vendor
        $sql = "INSERT INTO vendors (name, cycle, invoice_day, next_payment_date, contract_start_date, contract_end_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ssisss", $data['name'], $data['cycle'], $data['invoice_day'], $data['next_payment_date'], $data['contract_start_date'], $data['contract_end_date']);
    }

    if (mysqli_stmt_execute($stmt)) {
        $vendor_id = $id ?? mysqli_insert_id($link);
        // Handle templates
        // First, delete existing templates for this vendor
        $sql_delete_templates = "DELETE FROM payment_templates WHERE vendor_id = ?";
        $stmt_delete = mysqli_prepare($link, $sql_delete_templates);
        mysqli_stmt_bind_param($stmt_delete, "i", $vendor_id);
        mysqli_stmt_execute($stmt_delete);

        // Then, insert new ones
        if (!empty($data['templates'])) {
            $sql_insert_template = "INSERT INTO payment_templates (vendor_id, name, item_description, default_amount) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($link, $sql_insert_template);
            foreach ($data['templates'] as $template) {
                mysqli_stmt_bind_param($stmt_insert, "issd", $vendor_id, $template['name'], $template['item_description'], $template['default_amount']);
                mysqli_stmt_execute($stmt_insert);
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Vendor saved successfully.', 'data' => ['id' => $vendor_id]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save vendor. ' . mysqli_error($link)]);
    }
}

function savePayment($link, $data) {
    if (empty($data['vendor_id']) || empty($data['item_description']) || empty($data['amount'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required payment fields.']);
        return;
    }

    $id = $data['id'] ?? null;
    // Assume payment_month is sent in YYYY-MM format, append day
    $payment_month = $data['payment_month'] . '-01';

    if ($id) {
        $sql = "UPDATE payments SET item_description=?, billing_entity_id=?, invoice_number=?, amount=? WHERE id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sisdi", $data['item_description'], $data['billing_entity_id'], $data['invoice_number'], $data['amount'], $id);
    } else {
        $sql = "INSERT INTO payments (vendor_id, billing_entity_id, item_description, amount, payment_month) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "iisds", $data['vendor_id'], $data['billing_entity_id'], $data['item_description'], $data['amount'], $payment_month);
    }

    if (mysqli_stmt_execute($stmt)) {
        $payment_id = $id ?? mysqli_insert_id($link);
        echo json_encode(['status' => 'success', 'message' => 'Payment saved.', 'data' => ['id' => $payment_id]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save payment.']);
    }
}

function updatePaymentStatus($link, $data) {
    if (empty($data['id']) || empty($data['field'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters.']);
        return;
    }

    $id = $data['id'];
    $field = $data['field']; // e.g., 'date_invoice_received'
    $value = $data['value'] ?? date('Y-m-d');
    $allowed_fields = ['date_invoice_received', 'date_notion_registered', 'date_form_created', 'date_cloud_saved', 'date_submitted_to_finance'];

    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid field.']);
        return;
    }

    // Also update status if it's an invoice received date
    $status_update_sql = "";
    if ($field === 'date_invoice_received' && $value !== null) {
        $status_update_sql = ", status = 'received'";
    }

    $sql = "UPDATE payments SET `$field` = ? $status_update_sql WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $value, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Payment updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update payment.']);
    }
}


function batchUpdatePayments($link, $data) {
    if (empty($data['payment_ids']) || empty($data['field'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing parameters for batch update.']);
        return;
    }

    $ids = $data['payment_ids'];
    $field = $data['field'];
    $value = date('Y-m-d');

    $allowed_fields = ['date_notion_registered', 'date_form_created', 'date_cloud_saved', 'date_submitted_to_finance'];
    if (!in_array($field, $allowed_fields)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid batch update field.']);
        return;
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "UPDATE payments SET `$field` = ? WHERE id IN ($placeholders)";

    $types = 's' . str_repeat('i', count($ids));
    $params = array_merge([$value], $ids);

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Batch update successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Batch update failed.']);
    }
}


function searchPayments($link, $params) {
    $sql = "SELECT p.*, v.name as vendor_name, be.name as billing_entity_name FROM payments p
            JOIN vendors v ON p.vendor_id = v.id
            JOIN billing_entities be ON p.billing_entity_id = be.id
            WHERE 1=1";

    $sql_params = [];
    $types = "";

    if (!empty($params['start_date'])) {
        $sql .= " AND p.payment_month >= ?";
        $types .= "s";
        $sql_params[] = $params['start_date'];
    }
    if (!empty($params['end_date'])) {
        $sql .= " AND p.payment_month <= ?";
        $types .= "s";
        $sql_params[] = $params['end_date'];
    }
    if (!empty($params['vendor_id'])) {
        $sql .= " AND p.vendor_id = ?";
        $types .= "i";
        $sql_params[] = $params['vendor_id'];
    }
    if (!empty($params['billing_entity_id'])) {
        $sql .= " AND p.billing_entity_id = ?";
        $types .= "i";
        $sql_params[] = $params['billing_entity_id'];
    }

    $sql .= " ORDER BY p.payment_month DESC";

    $stmt = mysqli_prepare($link, $sql);
    if (!empty($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$sql_params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $payments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql_total = "SELECT COUNT(*) as total_records, SUM(amount) as total_amount FROM ($sql) as subquery";
    $stmt_total = mysqli_prepare($link, $sql_total);
    if (!empty($types)) {
        mysqli_stmt_bind_param($stmt_total, $types, ...$sql_params);
    }
    mysqli_stmt_execute($stmt_total);
    $result_total = mysqli_stmt_get_result($stmt_total);
    $totals = mysqli_fetch_assoc($result_total);

    echo json_encode(['status' => 'success', 'data' => $payments, 'totals' => $totals]);
}


mysqli_close($link);
?>