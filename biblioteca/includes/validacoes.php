<?php
/**
 * Funções de validação e sanitização para o Sistema de Biblioteca
 */

// ==================== SANITIZAÇÃO ====================

/**
 * Limpa e prepara texto para uso seguro
 */
function sanitizar_texto($texto) {
    return trim(htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'));
}

/**
 * Remove caracteres não numéricos
 */
function apenas_numeros($valor) {
    return preg_replace('/[^0-9]/', '', $valor);
}

/**
 * Escapa dados para exibição segura (anti-XSS)
 */
function escape($valor) {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

// ==================== VALIDAÇÃO DE CPF ====================

/**
 * Valida CPF (11 dígitos + dígitos verificadores)
 */
function validar_cpf($cpf) {
    $cpf = apenas_numeros($cpf);

    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Calcula primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += $cpf[$i] * (10 - $i);
    }
    $resto = $soma % 11;
    $dv1 = ($resto < 2) ? 0 : (11 - $resto);

    if ($cpf[9] != $dv1) {
        return false;
    }

    // Calcula segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += $cpf[$i] * (11 - $i);
    }
    $resto = $soma % 11;
    $dv2 = ($resto < 2) ? 0 : (11 - $resto);

    return $cpf[10] == $dv2;
}

/**
 * Formata CPF para exibição (XXX.XXX.XXX-XX)
 */
function formatar_cpf($cpf) {
    $cpf = apenas_numeros($cpf);
    if (strlen($cpf) == 11) {
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    return $cpf;
}

// ==================== VALIDAÇÃO DE CNPJ ====================

/**
 * Valida CNPJ (14 dígitos + dígitos verificadores)
 */
function validar_cnpj($cnpj) {
    $cnpj = apenas_numeros($cnpj);

    if (strlen($cnpj) != 14) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    // Calcula primeiro dígito verificador
    $soma = 0;
    $multiplicadores1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $multiplicadores1[$i];
    }
    $resto = $soma % 11;
    $dv1 = ($resto < 2) ? 0 : (11 - $resto);

    if ($cnpj[12] != $dv1) {
        return false;
    }

    // Calcula segundo dígito verificador
    $soma = 0;
    $multiplicadores2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for ($i = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $multiplicadores2[$i];
    }
    $resto = $soma % 11;
    $dv2 = ($resto < 2) ? 0 : (11 - $resto);

    return $cnpj[13] == $dv2;
}

/**
 * Formata CNPJ para exibição (XX.XXX.XXX/XXXX-XX)
 */
function formatar_cnpj($cnpj) {
    $cnpj = apenas_numeros($cnpj);
    if (strlen($cnpj) == 14) {
        return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
    }
    return $cnpj;
}

// ==================== VALIDAÇÃO DE TELEFONE ====================

/**
 * Valida telefone (10 ou 11 dígitos com DDD)
 */
function validar_telefone($telefone) {
    $telefone = apenas_numeros($telefone);
    $tamanho = strlen($telefone);

    // 10 dígitos = fixo (XX XXXX-XXXX)
    // 11 dígitos = celular (XX 9XXXX-XXXX)
    return $tamanho >= 10 && $tamanho <= 11;
}

/**
 * Formata telefone para exibição
 */
function formatar_telefone($telefone) {
    $telefone = apenas_numeros($telefone);
    $tamanho = strlen($telefone);

    if ($tamanho == 11) {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
    } elseif ($tamanho == 10) {
        return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
    }
    return $telefone;
}

// ==================== VALIDAÇÃO DE EMAIL ====================

/**
 * Valida email
 */
function validar_email($email) {
    $email = trim($email);
    if (empty($email)) {
        return true; // Vazio é permitido se não for required
    }
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// ==================== VALIDAÇÃO DE CEP ====================

/**
 * Valida CEP (8 dígitos)
 */
function validar_cep($cep) {
    $cep = apenas_numeros($cep);
    return strlen($cep) == 8;
}

/**
 * Formata CEP para exibição (XXXXX-XXX)
 */
function formatar_cep($cep) {
    $cep = apenas_numeros($cep);
    if (strlen($cep) == 8) {
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }
    return $cep;
}

// ==================== VALIDAÇÃO DE ISBN ====================

/**
 * Valida ISBN (10 ou 13 dígitos)
 */
function validar_isbn($isbn) {
    $isbn = apenas_numeros($isbn);
    $tamanho = strlen($isbn);

    if (empty($isbn)) {
        return true; // Vazio é permitido se não for required
    }

    return $tamanho == 10 || $tamanho == 13;
}

// ==================== VALIDAÇÃO DE ANO ====================

/**
 * Valida ano de publicação
 */
function validar_ano($ano) {
    if (empty($ano)) {
        return true; // Vazio é permitido se não for required
    }

    $ano = (int)$ano;
    $ano_atual = (int)date('Y');

    return $ano >= 1000 && $ano <= ($ano_atual + 1);
}

// ==================== VALIDAÇÃO DE DATA ====================

/**
 * Valida formato de data
 */
function validar_data($data, $formato = 'Y-m-d') {
    $d = DateTime::createFromFormat($formato, $data);
    return $d && $d->format($formato) === $data;
}

/**
 * Valida que data1 é anterior ou igual a data2
 */
function data_anterior_ou_igual($data1, $data2) {
    $d1 = new DateTime($data1);
    $d2 = new DateTime($data2);
    return $d1 <= $d2;
}

/**
 * Valida que data1 é anterior a data2
 */
function data_anterior($data1, $data2) {
    $d1 = new DateTime($data1);
    $d2 = new DateTime($data2);
    return $d1 < $d2;
}

// ==================== VALIDAÇÃO DE SENHA ====================

/**
 * Valida requisitos mínimos de senha
 */
function validar_senha($senha) {
    return strlen($senha) >= 6;
}

/**
 * Cria hash seguro de senha
 */
function hash_senha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

/**
 * Verifica senha contra hash
 */
function verificar_senha($senha, $hash) {
    return password_verify($senha, $hash);
}

// ==================== MENSAGENS DE ERRO ====================

/**
 * Retorna mensagens de erro padrão
 */
function msg_erro($tipo) {
    $mensagens = [
        'cpf' => 'CPF inválido. Digite 11 números.',
        'cnpj' => 'CNPJ inválido. Digite 14 números.',
        'telefone' => 'Telefone inválido. Digite 10 ou 11 números (com DDD).',
        'email' => 'Email inválido.',
        'cep' => 'CEP inválido. Digite 8 números.',
        'isbn' => 'ISBN inválido. Digite 10 ou 13 números.',
        'ano' => 'Ano inválido.',
        'data' => 'Data inválida.',
        'senha' => 'Senha deve ter no mínimo 6 caracteres.',
        'obrigatorio' => 'Este campo é obrigatório.',
    ];

    return $mensagens[$tipo] ?? 'Valor inválido.';
}
?>
