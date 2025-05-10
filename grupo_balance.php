<?php
require_once 'includes/config.php';
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$conn = $app->getConexionBd();

if (!isset($_SESSION['user_id'])) {
    \es\ucm\fdi\aw\Aplicacion::redirige('login.php');
}

$grupo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$grupo_id) {
    die("El grupo no está especificado.");
}

$gastosObj = new \es\ucm\fdi\aw\GastosGrupales($conn);
$gastos = $gastosObj->getGastosPorParticipanteGrafico($grupo_id);
$participantes = $gastosObj->getParticipantesConGastos($grupo_id);

$totalGasto = array_sum(array_column($participantes, 'gasto'));
$numParticipantes = count($participantes);
$gastoPorPersona = $numParticipantes > 0 ? $totalGasto / $numParticipantes : 0;

$balances = [];
foreach ($participantes as $id => $p) {
    $balances[$id] = [
        'nombre' => $p['nombre'],
        'balance' => round($p['gasto'] - $gastoPorPersona, 2)
    ];
}

ob_start();
?>
<div class="balance-container">
    <h2 class="balance-titulo">Balance del Grupo</h2>

    <div class="resumen" style="display: flex; justify-content: space-between; margin-bottom: 1.5em;">
    <h2><strong>Gasto total:</strong> <?= number_format($totalGasto, 2) ?> €</h2>
    <h2><strong>Gasto esperado por persona:</strong> <?= number_format($gastoPorPersona, 2) ?> €</h2>
</div>

    <div class="lista-balances">
        <h3>Situación individual:</h3>
        <div class="balance-items">
            <?php foreach ($balances as $b): 
                $esPositivo = $b['balance'] >= 0;
                $clase = $esPositivo ? 'positivo' : 'negativo';
            ?>
                <div class="balance-item <?= $clase ?>">
                    <?php if (!$esPositivo): ?>
                        <div class="balance-valor"><?= number_format($b['balance'], 2) ?> €</div>
                        <div class="balance-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                    <?php else: ?>
                        <div class="balance-nombre"><?= htmlspecialchars($b['nombre']) ?></div>
                        <div class="balance-valor">+<?= number_format($b['balance'], 2) ?> €</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="resumen-pagos">
        <h3>Resumen de pagos sugeridos:</h3>
        <ul class="pagos-sugeridos">
            <?php
            $deudores = array_filter($balances, fn($b) => $b['balance'] < 0);
            $acreedores = array_filter($balances, fn($b) => $b['balance'] > 0);

            foreach ($deudores as $idDeudor => $d) {
                foreach ($acreedores as $idAcreedor => $a) {
                    if ($d['balance'] == 0) break;
                    if ($a['balance'] == 0) continue;

                    $monto = min(abs($d['balance']), $a['balance']);

                    echo '<li><span><strong>' . htmlspecialchars($d['nombre']) . '</strong> debe pagar a <strong>' . htmlspecialchars($a['nombre']) . '</strong></span><span class="pago-cantidad">' . number_format($monto, 2) . ' €</span></li>';

                    $balances[$idDeudor]['balance'] += $monto;
                    $balances[$idAcreedor]['balance'] -= $monto;

                    $deudores[$idDeudor]['balance'] += $monto;
                    $acreedores[$idAcreedor]['balance'] -= $monto;

                    if (abs($deudores[$idDeudor]['balance']) < 0.01) {
                        $deudores[$idDeudor]['balance'] = 0;
                        break;
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>
<?php
$contenidoPrincipal = ob_get_clean();
$tituloPagina = "Balance del Grupo";
require __DIR__ . '/includes/vistas/plantilla/plantilla.php';
?>
