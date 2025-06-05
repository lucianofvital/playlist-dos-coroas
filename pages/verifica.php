<?php
if(file_exists('status.txt')){
    $status = file_get_contents('status.txt');

    if($status == 'pago'){
        header('Location: sucesso.html');
        exit;
    } else {
        echo "Aguardando pagamento...";
    }
} else {
    echo "Nenhum pagamento encontrado ainda.";
}
?>
