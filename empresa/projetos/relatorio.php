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

$stmt = $conn->prepare("SELECT * FROM projetos WHERE empresa_id = ? AND titulo LIKE ?");
$stmt->bind_param("is", $empresa_id, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$stmtFiscais = $conn->prepare("SELECT fiscal_id, nome FROM fiscais");
$stmtFiscais->execute();
$resultFiscais = $stmtFiscais->get_result();
$fiscais = [];

while ($fiscal = $resultFiscais->fetch_assoc()) {
    $fiscais[$fiscal['fiscal_id']] = htmlspecialchars($fiscal['nome']);
}

$stmt->close();
$stmtFiscais->close();
$conn->close();

class PDF extends FPDF {
    function Header() {
        $this->Image('../../imgs/obraplanner1.png',15, 11, 35); 
        $this->SetFont('Arial', 'B', 14);
        $this->SetX(10);
        $this->Cell(0, 10, utf8_decode('Relatório de Projetos - Anual'), 0, 1, 'C');
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
$pdf->Cell(20, 10,  utf8_decode('Título'), 1, 0, 'C', true);
$pdf->Cell(20, 10, utf8_decode('Descrição'), 1, 0, 'C' , true);
$pdf->Cell(23, 10,  utf8_decode('Status'), 1, 0, 'C', true);
$pdf->Cell(40, 10,  utf8_decode('Responsável'), 1, 0, 'C' , true);
$pdf->Cell(20, 10,  utf8_decode('Data Início'), 1, 0, 'C' , true);
$pdf->Cell(20, 10,  utf8_decode('Data Término'), 1, 0, 'C' , true);
$pdf->Cell(20, 10,  utf8_decode('Dt/prev/ini'), 1, 0, 'C' , true);
$pdf->Cell(20, 10,  utf8_decode('Dt/prev/ter'), 1, 1, 'C' , true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 6);

$linhaAltura = 10;  

while ($projeto = $result->fetch_assoc()) {
    $pdf->Cell(20, $linhaAltura,  utf8_decode(limitarTexto($projeto['titulo'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, utf8_decode(limitarTexto($projeto['descricao'])), 1, 0, 'C');
    $pdf->Cell(23, $linhaAltura,  utf8_decode($projeto['status']), 1, 0, 'C');
    $pdf->Cell(40, $linhaAltura, isset($fiscais[$projeto['fiscal_id']]) ?  utf8_decode($fiscais[$projeto['fiscal_id']]) : 'N/A', 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_inicio'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_termino'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_prev_ini'])), 1, 0, 'C');
    $pdf->Cell(20, $linhaAltura, date("d/m/Y", strtotime($projeto['data_prev_ter'])), 1, 1, 'C');
}

$pdf->Output('D', 'relatorio_projetos_' . strtolower($periodo) . '.pdf');
?>
