<?php
    echo "<div class='w3-panel w3-pale-green w3-display-container' style='display:block'>";
    echo "<span onclick=\"this.parentElement.style.display='none'\" class='w3-button w3-display-topright'>x</span>";
    echo "<p>" . $_SESSION['mensagem_sucesso'] . "</p></div>";

    // Limpa a mensagem de sucesso após exibição
    unset($_SESSION['mensagem_sucesso']);
?>
