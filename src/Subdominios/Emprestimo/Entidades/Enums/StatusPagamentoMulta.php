<?php

namespace IvanFuhr\Biblioteca\Subdominios\Emprestimo\Entidades\Enums;

enum StatusPagamentoMulta: string
{
    case AGUARDANDO_PAGAMENTO = 'Aguardando pagamento';
    case PAGO = 'Pago';
}