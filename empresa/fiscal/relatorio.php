<?php 
session_start();

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: login.php");
    exit();
}
function limitarTexto($texto, $limite = 10) {
    if (mb_strlen($texto) > $limite) {
        return mb_substr($texto, 0, $limite) . '...';
    }
    return $texto;
}

$periodo = "Anual"; 

$empresa_id = $_SESSION['UsuarioID'];

require('fpdf186/fpdf.php');

$conn = mysqli_connect('localhost', 'root', '', 'obra_planner');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$searchTerm = isset($_GET['search']) && $_GET['search'] !== '' ? '%' . $_GET['search'] . '%' : '%';

$stmt = $conn->prepare("SELECT * FROM fiscais WHERE empresa_id = ? AND nome LIKE ?");
$stmt->bind_param("is", $empresa_id, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();
$conn->close();

class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Fiscais - Anual'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, '' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 6);

$pdf->SetFillColor(0, 86, 179); 
$pdf->SetTextColor(255, 255, 255); 
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 10, 'Nome', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Email', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Telefone', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'CPF', 1, 1, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

$linhaAltura = 10;  

while ($fiscal = $result->fetch_assoc()) {
    $pdf->Cell(50, $linhaAltura,  utf8_decode($fiscal['nome']), 1, 0, 'C');
    $pdf->Cell(50, $linhaAltura,  utf8_decode($fiscal['email']), 1, 0, 'C');
    $pdf->Cell(40, $linhaAltura,  utf8_decode($fiscal['telefone']), 1, 0, 'C');
    $pdf->Cell(40, $linhaAltura,  utf8_decode($fiscal['cpf']), 1, 1, 'C');
}

$pdf->Output('D', 'relatorio_fiscais_' . strtolower($periodo) . '.pdf');
?>
